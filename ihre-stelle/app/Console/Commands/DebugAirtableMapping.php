<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DebugAirtableMapping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:airtable-mapping {record_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug Airtable field mappings for a specific record';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $recordId = $this->argument('record_id');
        $token = config('services.airtable.token');
        
        if (!$token) {
            $this->error('No Airtable token configured.');
            return;
        }

        $this->info("Debugging Airtable mapping for record: {$recordId}");

        $url = "https://api.airtable.com/v0/appwdZ74k0TlXQf0Y/Jobs/{$recordId}";
        $response = Http::withToken($token)->get($url);

        if (!$response->ok()) {
            $this->error('Request failed: ' . $response->status());
            return;
        }

        $data = $response->json();
        $fields = $data['fields'] ?? [];

        $this->info('=== AIRTABLE FIELDS ===');
        
        // Arbeitsgeber Felder
        $this->line('');
        $this->info('🏢 ARBEITSGEBER FELDER:');
        $this->checkField($fields, 'Arbeitsgeber name', 'arbeitsgeber_name');
        $this->checkField($fields, 'Arbeitsgeber-Final', 'arbeitsgeber_final_id');
        $this->checkField($fields, 'Arbeitsgeber Tel', 'arbeitsgeber_tel');
        $this->checkField($fields, 'Arbeitsgeber website', 'arbeitsgeber_website');

        // Job Details
        $this->line('');
        $this->info('💼 JOB DETAILS:');
        $this->checkField($fields, 'Job Titel', 'title');
        $this->checkField($fields, 'Kategorie', 'kategorie');
        $this->checkField($fields, 'JobTyp', 'job_type');
        $this->checkField($fields, 'Status', 'status');
        $this->checkField($fields, 'Stadt', 'city');
        $this->checkField($fields, 'PLZ_Job', 'postal_code');

        // Attachments
        $this->line('');
        $this->info('📎 ATTACHMENTS:');
        $this->checkField($fields, 'Banner (FB)', 'banner_fb');
        $this->checkField($fields, 'Job Logo', 'job_logo');
        $this->checkField($fields, 'Info für uns', 'info_fuer_uns');

        // Multiple Select Felder
        $this->line('');
        $this->info('🏷️ MULTIPLE SELECT:');
        $this->checkField($fields, 'Autotags', 'autotags');
        $this->checkField($fields, 'Benefits', 'benefits');
        $this->checkField($fields, 'Rolle im Job', 'rolle_im_job');

        $this->line('');
        $this->info('=== MAPPING TEST COMPLETE ===');
    }

    private function checkField(array $fields, string $airtableField, string $dbField): void
    {
        if (isset($fields[$airtableField])) {
            $value = $fields[$airtableField];
            $type = gettype($value);
            
            if (is_array($value)) {
                if (empty($value)) {
                    $preview = '[]';
                } else {
                    // Für Attachments (komplexe Arrays)
                    if (isset($value[0]) && is_array($value[0]) && isset($value[0]['url'])) {
                        $preview = '[' . count($value) . ' attachment(s)]';
                    } else {
                        // Für einfache Arrays
                        $simpleValues = array_map(function($item) {
                            return is_string($item) ? $item : json_encode($item);
                        }, array_slice($value, 0, 3));
                        $preview = '[' . implode(', ', $simpleValues) . ']';
                        if (count($value) > 3) {
                            $preview .= '...';
                        }
                    }
                }
            } else {
                $preview = is_string($value) ? substr($value, 0, 50) : $value;
                if (is_string($value) && strlen($value) > 50) {
                    $preview .= '...';
                }
            }
            
            $this->line("✅ {$airtableField} → {$dbField}: ({$type}) {$preview}");
        } else {
            $this->line("❌ {$airtableField} → {$dbField}: FIELD NOT FOUND");
        }
    }
}
