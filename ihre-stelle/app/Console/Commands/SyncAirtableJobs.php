<?php

namespace App\Console\Commands;

use App\Models\JobPost;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SyncAirtableJobs extends Command
{
    protected $signature = 'airtable:sync';

    protected $description = 'Sync jobs from Airtable';

    private const BASE_URL = 'https://api.airtable.com/v0/appwdZ74k0TlXQf0Y/Jobs';

    private const INACTIVE_STATUSES = [
        'Archive', 'Abgelaufen', 'Inactive-Web', 'Inactive',
    ];

    public function handle(): void
    {
        $token = config('services.airtable.token');
        if (!$token) {
            $this->error('No Airtable token configured.');
            return;
        }

        $url = self::BASE_URL;
        $params = [
            'pageSize' => 100,
        ];

        do {
            $response = Http::withToken($token)->get($url, $params);
            if (!$response->ok()) {
                $this->error('Request failed: '.$response->status());
                return;
            }

            $data = $response->json();
            foreach ($data['records'] as $record) {
                $this->importRecord($record);
            }
            $url = self::BASE_URL;
            $params['offset'] = $data['offset'] ?? null;
        } while (!empty($params['offset']));
    }

    private function importRecord(array $record): void
    {
        $fields = $record['fields'] ?? [];
        $status = $fields['Status'] ?? null;
        $active = !in_array($status, self::INACTIVE_STATUSES, true);

        $post = JobPost::firstOrNew(['airtable_id' => $record['id']]);
        $post->fill([
            'title' => $fields['Job Titel'] ?? 'Job',
            'description' => $fields['Job beschreibung'] ?? null,
            'status' => $status,
            'job_type' => $fields['JobTyp'][0] ?? null,
            'city' => $fields['Stadt'] ?? null,
            'country' => $fields['Land'] ?? null,
            'postal_code' => $fields['PLZ_Job'] ?? null,
            'longitude' => $fields['longitude'] ?? null,
            'latitude' => $fields['latitude'] ?? null,
            'contact_email' => $fields['Bewerbung an Mail'] ?? null,
            'last_modified_at' => $fields['Lastmodify time'] ?? null,
            'is_active' => $active,
        ]);

        if (!$post->exists) {
            $post->slug = Str::slug($post->title.'-'.Str::random(6));
        }

        $post->save();
    }
}
