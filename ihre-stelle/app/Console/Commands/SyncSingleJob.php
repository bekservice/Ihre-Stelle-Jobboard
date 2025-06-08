<?php

namespace App\Console\Commands;

use App\Models\JobPost;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SyncSingleJob extends Command
{
    protected $signature = 'airtable:sync-job {id : The Airtable record ID}';

    protected $description = 'Sync a single job from Airtable by ID';

    private const BASE_URL = 'https://api.airtable.com/v0/appwdZ74k0TlXQf0Y/Jobs';

    private const INACTIVE_STATUSES = [
        'Archive', 'Abgelaufen', 'Inactive-Web', 'Inactive',
    ];

    public function handle(): void
    {
        $airtableId = $this->argument('id');
        
        $token = config('services.airtable.token');
        if (!$token) {
            $this->error('No Airtable token configured.');
            return;
        }

        $this->info("Syncing job with Airtable ID: {$airtableId}");

        try {
            // Fetch specific record from Airtable
            $url = self::BASE_URL . '/' . $airtableId;
            $response = Http::withToken($token)->get($url);

            if (!$response->ok()) {
                $this->error('Failed to fetch record from Airtable: ' . $response->status());
                return;
            }

            $record = $response->json();
            $job = $this->importRecord($record);

            $this->info("✓ Job synchronized successfully:");
            $this->line("  Title: {$job->title}");
            $this->line("  Slug: {$job->slug}");
            $this->line("  URL: https://ihre-stelle.de/jobs/{$job->slug}");
            $this->line("  Active: " . ($job->is_active ? 'Yes' : 'No'));

        } catch (\Exception $e) {
            $this->error('Sync failed: ' . $e->getMessage());
        }
    }

    private function importRecord(array $record): JobPost
    {
        $fields = $record['fields'] ?? [];
        $status = $fields['Status'] ?? null;
        $active = !in_array($status, self::INACTIVE_STATUSES, true);

        $post = JobPost::firstOrNew(['airtable_id' => $record['id']]);
        
        // Alle Felder synchronisieren
        $post->fill([
            'title' => $fields['Job Titel'] ?? 'Job',
            'anrede' => $fields['Anrede'] ?? null,
            'description' => $fields['Job beschreibung'] ?? null,
            'ansprechpartner_hr' => $fields['Ansprechpartner (HR)'] ?? null,
            'bewerbungen_an_link' => $fields['Bewerbungen an Link'] ?? null,
            'bewerbung_an_mail' => $fields['Bewerbung an Mail'] ?? null,
            'status' => $status,
            'kategorie' => $fields['Kategorie'] ?? null,
            'job_type' => $this->processMultipleSelect($fields['JobTyp'] ?? null),
            'city' => $fields['Stadt'] ?? null,
            'country' => $fields['Land'] ?? 'Deutschland',
            'postal_code' => $fields['PLZ_Job'] ?? null,
            'longitude' => $fields['longitude'] ?? null,
            'latitude' => $fields['latitude'] ?? null,
            'contact_email' => $fields['Bewerbung an Mail'] ?? null,
            'berufserfahrung' => $fields['Berufserfahrung'] ?? null,
            'record_id' => $fields['Record_ID'] ?? $record['id'],
            'last_modified_at' => $this->parseDate($fields['Lastmodify time'] ?? null),
            'angelegt_am' => $this->parseDate($fields['Angelegt am'] ?? null),
            'is_active' => $active,
            
            // Arbeitgeber Informationen
            'arbeitsgeber_name' => $fields['Arbeitsgeber Name'] ?? null,
            'arbeitsgeber_tel' => $this->processMultipleSelect($fields['Arbeitsgeber Tel'] ?? null),
            'arbeitsgeber_website' => $this->processMultipleSelect($fields['Arbeitsgeber Website'] ?? null),
            
            // Job Details
            'grundgehalt' => $fields['Grundgehalt'] ?? null,
            'bezahlung' => $fields['Bezahlung'] ?? null,
            'schulabschluss' => $fields['Schulabschluss'] ?? null,
            'rolle_im_job' => $this->processMultipleSelect($fields['Rolle im Job'] ?? null),
            'ablaufdatum' => $this->parseDate($fields['Ablaufdatum'] ?? null),
            
            // Multiple Select Felder
            'job_typ_multiple' => $this->processMultipleSelect($fields['JobTyp'] ?? null),
            'autotags' => $this->processMultipleSelect($fields['Autotags'] ?? null),
            'benefits' => $this->processMultipleSelect($fields['Benefits'] ?? null),
            
            // Attachments/Logos
            'banner_fb' => $this->processAttachments($fields['Banner FB'] ?? null),
            'job_logo' => $this->processAttachments($fields['Job Logo'] ?? null),
        ]);

        if (!$post->exists) {
            $post->slug = Str::slug($post->title.'-'.Str::random(6));
        }

        $post->save();

        // Update Airtable with Ihre-Stelle URL
        $this->updateAirtableRecord($record['id'], $post);

        return $post;
    }

    private function parseDate(?string $dateString): ?string
    {
        if (!$dateString) {
            return null;
        }
        
        try {
            return \Carbon\Carbon::parse($dateString)->toDateTimeString();
        } catch (\Exception $e) {
            return null;
        }
    }

    private function processAttachments(?array $attachments): ?array
    {
        if (!$attachments || !is_array($attachments)) {
            return null;
        }
        
        $processed = [];
        
        foreach ($attachments as $attachment) {
            $processed[] = [
                'id' => $attachment['id'] ?? null,
                'url' => $attachment['url'] ?? null,
                'filename' => $attachment['filename'] ?? null,
                'type' => $attachment['type'] ?? null,
                'size' => $attachment['size'] ?? null,
            ];
        }
        
        return empty($processed) ? null : $processed;
    }

    private function processMultipleSelect($value): ?array
    {
        if (!$value || !is_array($value)) {
            return null;
        }
        
        return $value;
    }

    private function updateAirtableRecord(string $recordId, JobPost $job): void
    {
        $token = config('services.airtable.token');
        if (!$token) {
            return;
        }

        $url = "https://api.airtable.com/v0/appwdZ74k0TlXQf0Y/Jobs/{$recordId}";
        $jobUrl = "https://ihre-stelle.de/jobs/{$job->slug}";

        $data = [
            'fields' => [
                'Ihre-StelleLink' => $jobUrl
            ]
        ];

        try {
            $response = Http::withToken($token)
                ->patch($url, $data);

            if ($response->ok()) {
                $this->line("✓ Updated Airtable record with URL: {$jobUrl}");
            } else {
                $this->warn("Failed to update Airtable record {$recordId}: " . $response->body());
            }
        } catch (\Exception $e) {
            $this->error("Error updating Airtable record {$recordId}: " . $e->getMessage());
        }
    }
} 