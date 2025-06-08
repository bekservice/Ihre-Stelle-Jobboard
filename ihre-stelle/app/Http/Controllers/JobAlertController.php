<?php

namespace App\Http\Controllers;

use App\Models\JobAlert;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class JobAlertController extends Controller
{
    /**
     * Zeigt das Job-Alert-Formular
     */
    public function create()
    {
        // Kategorien für das Formular laden
        $categories = JobPost::where('is_active', true)
            ->whereNotNull('kategorie')
            ->distinct()
            ->pluck('kategorie')
            ->sort();

        // Standorte für das Formular laden
        $locations = JobPost::where('is_active', true)
            ->whereNotNull('city')
            ->distinct()
            ->pluck('city')
            ->sort();

        // Job-Typen für das Formular laden
        $allJobTypes = JobPost::where('is_active', true)
            ->whereNotNull('job_type')
            ->get()
            ->pluck('job_type')
            ->filter()
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        return view('job-alerts.create', compact('categories', 'locations', 'allJobTypes'));
    }

    /**
     * Speichert einen neuen Job-Alert
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'job_types' => 'nullable|array',
            'job_types.*' => 'string',
            'frequency' => 'required|in:immediate,daily,weekly',
        ], [
            'email.required' => 'Bitte geben Sie Ihre E-Mail-Adresse an.',
            'email.email' => 'Bitte geben Sie eine gültige E-Mail-Adresse an.',
            'frequency.required' => 'Bitte wählen Sie eine Benachrichtigungsfrequenz.',
            'frequency.in' => 'Ungültige Benachrichtigungsfrequenz.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Prüfen ob bereits ein Alert für diese E-Mail und Kriterien existiert
            $existingAlert = JobAlert::where('email', $request->email)
                ->where('category', $request->category)
                ->where('location', $request->location)
                ->where('is_active', true)
                ->first();

            if ($existingAlert) {
                return back()->withErrors(['email' => 'Sie haben bereits einen Job-Alert mit diesen Kriterien erstellt.'])->withInput();
            }

            // Neuen Alert erstellen
            $alert = JobAlert::create([
                'email' => $request->email,
                'name' => $request->name,
                'category' => $request->category,
                'location' => $request->location,
                'job_types' => $request->job_types,
                'frequency' => $request->frequency,
            ]);

            // Bestätigungs-E-Mail senden
            $this->sendVerificationEmail($alert);

            return redirect()->route('job-alerts.success')
                ->with('success', 'Job-Alert erfolgreich erstellt! Bitte überprüfen Sie Ihre E-Mails zur Bestätigung.');

        } catch (\Exception $e) {
            \Log::error('Job Alert creation failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->except(['_token']),
            ]);

            return back()->withErrors(['error' => 'Es ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.'])
                ->withInput();
        }
    }

    /**
     * Zeigt die Erfolgsseite
     */
    public function success()
    {
        return view('job-alerts.success');
    }

    /**
     * Verifiziert einen Job-Alert
     */
    public function verify($token)
    {
        $alert = JobAlert::where('token', $token)->first();

        if (!$alert) {
            abort(404, 'Job-Alert nicht gefunden.');
        }

        if ($alert->email_verified_at) {
            return view('job-alerts.already-verified', compact('alert'));
        }

        $alert->verify();

        return view('job-alerts.verified', compact('alert'));
    }

    /**
     * Deaktiviert einen Job-Alert
     */
    public function unsubscribe($token)
    {
        $alert = JobAlert::where('token', $token)->first();

        if (!$alert) {
            abort(404, 'Job-Alert nicht gefunden.');
        }

        $alert->deactivate();

        return view('job-alerts.unsubscribed', compact('alert'));
    }

    /**
     * Zeigt die Verwaltungsseite für einen Alert
     */
    public function manage($token)
    {
        $alert = JobAlert::where('token', $token)->first();

        if (!$alert) {
            abort(404, 'Job-Alert nicht gefunden.');
        }

        // Kategorien und Standorte für Bearbeitung laden
        $categories = JobPost::where('is_active', true)
            ->whereNotNull('kategorie')
            ->distinct()
            ->pluck('kategorie')
            ->sort();

        $locations = JobPost::where('is_active', true)
            ->whereNotNull('city')
            ->distinct()
            ->pluck('city')
            ->sort();

        $allJobTypes = JobPost::where('is_active', true)
            ->whereNotNull('job_type')
            ->get()
            ->pluck('job_type')
            ->filter()
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        return view('job-alerts.manage', compact('alert', 'categories', 'locations', 'allJobTypes'));
    }

    /**
     * Aktualisiert einen Job-Alert
     */
    public function update(Request $request, $token)
    {
        $alert = JobAlert::where('token', $token)->first();

        if (!$alert) {
            abort(404, 'Job-Alert nicht gefunden.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'job_types' => 'nullable|array',
            'job_types.*' => 'string',
            'frequency' => 'required|in:immediate,daily,weekly',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $alert->update([
            'name' => $request->name,
            'category' => $request->category,
            'location' => $request->location,
            'job_types' => $request->job_types,
            'frequency' => $request->frequency,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Job-Alert erfolgreich aktualisiert!');
    }

    /**
     * Sendet Verifikations-E-Mail
     */
    private function sendVerificationEmail(JobAlert $alert)
    {
        $verifyUrl = route('job-alerts.verify', $alert->token);
        $manageUrl = route('job-alerts.manage', $alert->token);

        // Hier würde normalerweise eine E-Mail-Template verwendet
        // Für jetzt loggen wir die URLs
        \Log::info('Job Alert Verification Email', [
            'email' => $alert->email,
            'verify_url' => $verifyUrl,
            'manage_url' => $manageUrl,
        ]);

        // TODO: Implementiere echten E-Mail-Versand
        // Mail::to($alert->email)->send(new JobAlertVerificationMail($alert, $verifyUrl, $manageUrl));
    }
}
