<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    public function show(JobPost $job)
    {
        if (!$job->is_active) {
            abort(404);
        }

        return view('applications.form', compact('job'));
    }

    public function store(Request $request, JobPost $job)
    {
        $validator = Validator::make($request->all(), [
            'vorname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefon' => 'nullable|string|max:255',
            'nachricht' => 'required|string|max:2000',
            'lebenslauf' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
            'anschreiben' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB max
            'datenschutz' => 'required|accepted',
        ], [
            'vorname.required' => 'Bitte geben Sie Ihren Vornamen an.',
            'name.required' => 'Bitte geben Sie Ihren Nachnamen an.',
            'email.required' => 'Bitte geben Sie Ihre E-Mail-Adresse an.',
            'email.email' => 'Bitte geben Sie eine gültige E-Mail-Adresse an.',
            'nachricht.required' => 'Bitte geben Sie eine Nachricht ein.',
            'lebenslauf.required' => 'Bitte laden Sie Ihren Lebenslauf hoch.',
            'lebenslauf.mimes' => 'Der Lebenslauf muss eine PDF-, DOC- oder DOCX-Datei sein.',
            'lebenslauf.max' => 'Der Lebenslauf darf maximal 5MB groß sein.',
            'anschreiben.mimes' => 'Das Anschreiben muss eine PDF-, DOC- oder DOCX-Datei sein.',
            'anschreiben.max' => 'Das Anschreiben darf maximal 5MB groß sein.',
            'datenschutz.required' => 'Bitte akzeptieren Sie die Datenschutzerklärung.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Upload files to public storage with proper URLs
            $attachments = [];
            
            if ($request->hasFile('lebenslauf')) {
                $filename = 'Lebenslauf_' . $request->vorname . '_' . $request->name . '_' . time() . '.' . $request->file('lebenslauf')->getClientOriginalExtension();
                $lebenslaufPath = $request->file('lebenslauf')->storeAs('bewerbungen', $filename, 'public');
                
                // Create full public URL
                $lebenslaufUrl = config('app.url') . '/storage/' . $lebenslaufPath;
                
                $attachments[] = [
                    'url' => $lebenslaufUrl,
                    'filename' => $filename
                ];
                
                \Log::info('Lebenslauf uploaded', [
                    'path' => $lebenslaufPath,
                    'url' => $lebenslaufUrl,
                    'file_exists' => file_exists(storage_path('app/public/' . $lebenslaufPath))
                ]);
            }

            if ($request->hasFile('anschreiben')) {
                $filename = 'Anschreiben_' . $request->vorname . '_' . $request->name . '_' . time() . '.' . $request->file('anschreiben')->getClientOriginalExtension();
                $anschreibenPath = $request->file('anschreiben')->storeAs('bewerbungen', $filename, 'public');
                
                // Create full public URL
                $anschreibenUrl = config('app.url') . '/storage/' . $anschreibenPath;
                
                $attachments[] = [
                    'url' => $anschreibenUrl,
                    'filename' => $filename
                ];
                
                \Log::info('Anschreiben uploaded', [
                    'path' => $anschreibenPath,
                    'url' => $anschreibenUrl,
                    'file_exists' => file_exists(storage_path('app/public/' . $anschreibenPath))
                ]);
            }

            // Prepare data for Airtable
            $airtableData = [
                'records' => [
                    [
                        'fields' => [
                            'Name' => $request->name,
                            'Vorname(PG)' => $request->vorname,
                            'Mail von Bewerber/-in' => $request->email,
                            'Telephone' => $request->telefon,
                            'Nachricht' => $request->nachricht,
                            'Link to Jobs' => [$job->airtable_id], // Link to the job
                            'Status' => 'Ready',
                            'Quelle' => ['Ihre-Stelle'],
                            'Unterlagen' => $attachments
                        ]
                    ]
                ]
            ];

            // Log the request for debugging
            \Log::info('Sending application to Airtable', [
                'job_id' => $job->id,
                'airtable_data' => $airtableData,
                'base_id' => config('services.airtable.base_id'),
            ]);

            // Send to Airtable
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.airtable.token'),
                'Content-Type' => 'application/json',
            ])->post('https://api.airtable.com/v0/' . config('services.airtable.base_id') . '/' . config('services.airtable.kandidaten_table'), $airtableData);

            // Log the response for debugging
            \Log::info('Airtable response', [
                'status' => $response->status(),
                'body' => $response->body(),
                'successful' => $response->successful(),
            ]);

            if ($response->successful()) {
                \Log::info('Application successfully sent to Airtable', [
                    'job_id' => $job->id,
                    'applicant' => $request->vorname . ' ' . $request->name,
                    'attachments_count' => count($attachments)
                ]);

                return redirect()->route('application.success', $job)
                    ->with('success', 'Ihre Bewerbung wurde erfolgreich übermittelt!');
            } else {
                throw new \Exception('Airtable API Error (Status: ' . $response->status() . '): ' . $response->body());
            }

        } catch (\Exception $e) {
            // Log the detailed error for debugging
            \Log::error('Application submission failed', [
                'job_id' => $job->id,
                'job_airtable_id' => $job->airtable_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['lebenslauf', 'anschreiben', '_token']),
                'airtable_config' => [
                    'base_id' => config('services.airtable.base_id'),
                    'token_exists' => !empty(config('services.airtable.token')),
                ]
            ]);

            // Note: Files are kept for Airtable to download
            // They can be cleaned up later via a scheduled task if needed

            // Show more specific error in development
            $errorMessage = 'Es ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.';
            if (config('app.debug')) {
                $errorMessage .= ' (Debug: ' . $e->getMessage() . ')';
            }

            return back()->withErrors(['error' => $errorMessage])
                ->withInput();
        }
    }

    public function success(JobPost $job)
    {
        return view('applications.success', compact('job'));
    }
}
