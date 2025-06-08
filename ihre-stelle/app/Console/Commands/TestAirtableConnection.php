<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestAirtableConnection extends Command
{
    protected $signature = 'airtable:test';
    protected $description = 'Test Airtable API connection and configuration';

    public function handle()
    {
        $this->info('Testing Airtable Configuration...');
        $this->newLine();

        // Check configuration
        $token = config('services.airtable.token');
        $baseId = config('services.airtable.base_id');
        $jobsTable = config('services.airtable.jobs_table');
        $kandidatenTable = config('services.airtable.kandidaten_table');

        $this->info('Configuration:');
        $this->line('Token: ' . ($token ? 'Set (' . substr($token, 0, 10) . '...)' : 'NOT SET'));
        $this->line('Base ID: ' . ($baseId ?: 'NOT SET'));
        $this->line('Jobs Table: ' . ($jobsTable ?: 'NOT SET'));
        $this->line('Kandidaten Table: ' . ($kandidatenTable ?: 'NOT SET'));
        $this->newLine();

        if (!$token || !$baseId) {
            $this->error('Missing required configuration!');
            $this->line('Please set AIRTABLE_API_KEY and AIRTABLE_BASE_ID in your .env file');
            return 1;
        }

        // Test Jobs table access
        $this->info('Testing Jobs table access...');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get("https://api.airtable.com/v0/{$baseId}/{$jobsTable}?maxRecords=1");

        if ($response->successful()) {
            $this->info('✓ Jobs table accessible');
            $data = $response->json();
            $this->line('Records found: ' . count($data['records'] ?? []));
        } else {
            $this->error('✗ Jobs table error: ' . $response->status() . ' - ' . $response->body());
        }

        $this->newLine();

        // Test Kandidaten table access
        $this->info('Testing Kandidaten table access...');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get("https://api.airtable.com/v0/{$baseId}/{$kandidatenTable}?maxRecords=1");

        if ($response->successful()) {
            $this->info('✓ Kandidaten table accessible');
            $data = $response->json();
            $this->line('Records found: ' . count($data['records'] ?? []));
        } else {
            $this->error('✗ Kandidaten table error: ' . $response->status() . ' - ' . $response->body());
        }

        $this->newLine();

        // Test creating a test record
        $this->info('Testing record creation (dry run)...');
        $testData = [
            'records' => [
                [
                    'fields' => [
                        'Name' => 'Test',
                        'Vorname(PG)' => 'User',
                        'Mail von Bewerber/-in' => 'test@example.com',
                        'Status' => 'Ready',
                        'Quelle' => ['Test'],
                        'Nachricht' => 'Test message from Ihre-Stelle application'
                    ]
                ]
            ]
        ];

        $this->line('Would send data: ' . json_encode($testData, JSON_PRETTY_PRINT));
        $this->newLine();

        $this->info('Test completed!');
        return 0;
    }
} 