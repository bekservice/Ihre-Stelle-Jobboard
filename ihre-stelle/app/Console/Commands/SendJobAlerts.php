<?php

namespace App\Console\Commands;

use App\Models\JobAlert;
use App\Models\JobPost;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendJobAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job-alerts:send {--frequency=daily : Frequency to send (immediate, daily, weekly)} {--test-email= : Send test to specific email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send job alert emails to subscribers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $frequency = $this->option('frequency');
        $testEmail = $this->option('test-email');

        $this->info("Sending job alerts for frequency: {$frequency}");

        if ($testEmail) {
            $this->info("Test mode: sending to {$testEmail}");
        }

        // Hole aktive und verifizierte Alerts für die gewählte Frequenz
        $alertsQuery = JobAlert::active()
            ->verified()
            ->where('frequency', $frequency);

        if ($testEmail) {
            $alertsQuery->where('email', $testEmail);
        }

        $alerts = $alertsQuery->get();

        if ($alerts->isEmpty()) {
            $this->info('No alerts found for the specified criteria.');
            return;
        }

        $this->info("Found {$alerts->count()} alerts to process");

        $sentCount = 0;
        $errorCount = 0;

        foreach ($alerts as $alert) {
            try {
                $this->processAlert($alert, $frequency);
                $sentCount++;
                $this->line("✓ Sent alert to {$alert->email}");
            } catch (\Exception $e) {
                $errorCount++;
                $this->error("✗ Failed to send alert to {$alert->email}: " . $e->getMessage());
                Log::error('Job Alert sending failed', [
                    'alert_id' => $alert->id,
                    'email' => $alert->email,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Job alerts sent: {$sentCount} successful, {$errorCount} failed");
    }

    /**
     * Verarbeitet einen einzelnen Alert
     */
    private function processAlert(JobAlert $alert, string $frequency): void
    {
        // Bestimme den Zeitraum für neue Jobs
        $since = $this->getSinceDate($alert, $frequency);

        // Finde passende Jobs
        $jobs = $this->findMatchingJobs($alert, $since);

        if ($jobs->isEmpty()) {
            $this->line("  No new jobs found for {$alert->email}");
            return;
        }

        // Sende E-Mail
        $this->sendAlertEmail($alert, $jobs, $frequency);

        // Markiere Alert als versendet
        $alert->markAsSent();
    }

    /**
     * Bestimmt das "seit" Datum basierend auf Frequenz und letztem Versand
     */
    private function getSinceDate(JobAlert $alert, string $frequency): \Carbon\Carbon
    {
        if ($alert->last_sent_at) {
            return $alert->last_sent_at;
        }

        // Fallback basierend auf Frequenz
        return match ($frequency) {
            'immediate' => now()->subHour(),
            'daily' => now()->subDay(),
            'weekly' => now()->subWeek(),
            default => now()->subDay(),
        };
    }

    /**
     * Findet passende Jobs für einen Alert
     */
    private function findMatchingJobs(JobAlert $alert, \Carbon\Carbon $since): \Illuminate\Database\Eloquent\Collection
    {
        $query = JobPost::where('is_active', true)
            ->where('created_at', '>=', $since)
            ->orderBy('created_at', 'desc');

        // Kategorie-Filter
        if ($alert->category) {
            $query->where('kategorie', $alert->category);
        }

        // Standort-Filter
        if ($alert->location) {
            $query->where(function ($q) use ($alert) {
                $q->where('city', 'like', "%{$alert->location}%")
                  ->orWhere('plz_job', 'like', "%{$alert->location}%");
            });
        }

        // Job-Typ-Filter
        if ($alert->job_types && count($alert->job_types) > 0) {
            $query->where(function ($q) use ($alert) {
                foreach ($alert->job_types as $jobType) {
                    $q->orWhereJsonContains('job_type', $jobType);
                }
            });
        }

        $jobs = $query->get();

        // Zusätzliche Filterung mit der matchesJob-Methode
        return $jobs->filter(function ($job) use ($alert) {
            return $alert->matchesJob($job);
        });
    }

    /**
     * Sendet die Alert-E-Mail
     */
    private function sendAlertEmail(JobAlert $alert, $jobs, string $frequency): void
    {
        $subject = $this->getEmailSubject($jobs->count(), $frequency);
        $manageUrl = route('job-alerts.manage', $alert->token);
        $unsubscribeUrl = route('job-alerts.unsubscribe', $alert->token);

        // Hier würde normalerweise eine E-Mail-Template verwendet
        // Für jetzt loggen wir die E-Mail-Details
        Log::info('Job Alert Email', [
            'to' => $alert->email,
            'subject' => $subject,
            'job_count' => $jobs->count(),
            'jobs' => $jobs->pluck('job_titel')->toArray(),
            'manage_url' => $manageUrl,
            'unsubscribe_url' => $unsubscribeUrl,
        ]);

        // TODO: Implementiere echten E-Mail-Versand
        // Mail::to($alert->email)->send(new JobAlertMail($alert, $jobs, $manageUrl, $unsubscribeUrl));
    }

    /**
     * Generiert den E-Mail-Betreff
     */
    private function getEmailSubject(int $jobCount, string $frequency): string
    {
        $frequencyText = match ($frequency) {
            'immediate' => 'Neuer Job',
            'daily' => 'Tägliche Job-Zusammenfassung',
            'weekly' => 'Wöchentliche Job-Zusammenfassung',
            default => 'Job-Alert',
        };

        if ($jobCount === 1) {
            return "Ihre-Stelle.de: {$frequencyText} - 1 passender Job";
        }

        return "Ihre-Stelle.de: {$frequencyText} - {$jobCount} passende Jobs";
    }
}
