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
            // Upload files to temporary storage
            $attachments = [];
            
            if ($request->hasFile('lebenslauf')) {
                $lebenslaufPath = $request->file('lebenslauf')->store('temp', 'public');
                $lebenslaufUrl = url('storage/' . $lebenslaufPath);
                $attachments[] = [
                    'url' => $lebenslaufUrl,
                    'filename' => 'Lebenslauf_' . $request->vorname . '_' . $request->name . '.' . $request->file('lebenslauf')->getClientOriginalExtension()
                ];
            }

            if ($request->hasFile('anschreiben')) {
                $anschreibenPath = $request->file('anschreiben')->store('temp', 'public');
                $anschreibenUrl = url('storage/' . $anschreibenPath);
                $attachments[] = [
                    'url' => $anschreibenUrl,
                    'filename' => 'Anschreiben_' . $request->vorname . '_' . $request->name . '.' . $request->file('anschreiben')->getClientOriginalExtension()
                ];
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

            // Send to Airtable
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.airtable.token'),
                'Content-Type' => 'application/json',
            ])->post('https://api.airtable.com/v0/' . config('services.airtable.base_id') . '/Kandidaten', $airtableData);

            if ($response->successful()) {
                // Clean up temporary files after successful upload
                if (isset($lebenslaufPath)) {
                    Storage::disk('public')->delete($lebenslaufPath);
                }
                if (isset($anschreibenPath)) {
                    Storage::disk('public')->delete($anschreibenPath);
                }

                return redirect()->route('application.success', $job)
                    ->with('success', 'Ihre Bewerbung wurde erfolgreich übermittelt!');
            } else {
                throw new \Exception('Fehler beim Übermitteln der Bewerbung: ' . $response->body());
            }

        } catch (\Exception $e) {
            // Clean up temporary files on error
            if (isset($lebenslaufPath)) {
                Storage::disk('public')->delete($lebenslaufPath);
            }
            if (isset($anschreibenPath)) {
                Storage::disk('public')->delete($anschreibenPath);
            }

            return back()->withErrors(['error' => 'Es ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.'])
                ->withInput();
        }
    }

    public function success(JobPost $job)
    {
        return view('applications.success', compact('job'));
    }
}
