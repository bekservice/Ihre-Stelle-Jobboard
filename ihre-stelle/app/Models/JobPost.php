<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'airtable_id',
        'title',
        'anrede',
        'description',
        'ansprechpartner_hr',
        'info_fuer_uns',
        'bewerbungen_an_link',
        'bewerbung_an_mail',
        'angelegt_am',
        'last_modified_time_status',
        'status',
        'kategorie',
        'job_type',
        'job_typ_multiple',
        'city',
        'country',
        'land',
        'arbeitsgeber_final_id',
        'arbeitsgeber_name',
        'arbeitsgeber_tel',
        'arbeitsgeber_website',
        'postal_code',
        'plz_job',
        'bundesland_job',
        'bezahlung',
        'grundgehalt',
        'banner_fb',
        'job_logo',
        'longitude',
        'latitude',
        'autotags',
        'benefits',
        'rolle_im_job',
        'berufserfahrung',
        'short_link',
        'job_typ_en',
        'record_id',
        'last_modified_at',
        'lastmodify_time',
        'bewerbungen_anzahl',
        'contact_email',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_modified_at' => 'datetime',
        'angelegt_am' => 'datetime',
        'last_modified_time_status' => 'datetime',
        'lastmodify_time' => 'datetime',
        'job_typ_multiple' => 'json',
        'banner_fb' => 'json',
        'job_logo' => 'json',
        'autotags' => 'json',
        'benefits' => 'json',
        'rolle_im_job' => 'json',
        'bewerbungen_anzahl' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $post) {
            $post->slug = $post->slug ?: Str::slug($post->title.'-'.Str::random(6));
        });
    }

    /**
     * Get the job description with HTML formatting
     */
    public function getFormattedDescriptionAttribute(): string
    {
        if (!$this->description) {
            return '';
        }
        
        // Convert markdown-like formatting to HTML
        $description = $this->description;
        
        // Convert markdown links [text](url) to HTML links
        $description = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2" target="_blank" rel="noopener noreferrer" class="text-primary-orange hover:text-accent-orange underline">$1</a>', $description);
        
        // Convert email links like E-Mail: email@domain.com to clickable links
        $description = preg_replace('/E-Mail:\s*([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/', 'E-Mail: <a href="mailto:$1" class="text-primary-orange hover:text-accent-orange underline">$1</a>', $description);
        
        // Convert web links like Web: www.domain.com to clickable links
        $description = preg_replace('/Web:\s*((?:https?:\/\/)?(?:www\.)?[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}(?:\/[^\s]*)?)/', 'Web: <a href="$1" target="_blank" rel="noopener noreferrer" class="text-primary-orange hover:text-accent-orange underline">$1</a>', $description);
        
        // Ensure URLs have proper protocol
        $description = preg_replace('/href="(www\.[^"]+)"/', 'href="https://$1"', $description);
        $description = preg_replace('/href="([^"]+)"(?![^>]*https?:\/\/)/', 'href="https://$1"', $description);
        
        // Convert **bold** to <strong>
        $description = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $description);
        
        // Convert line breaks to <br>
        $description = nl2br($description);
        
        // Convert bullet points (- text) to <ul><li>
        $lines = explode("\n", $description);
        $inList = false;
        $result = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/^-\s+(.+)/', $line, $matches)) {
                if (!$inList) {
                    $result[] = '<ul class="list-disc list-inside space-y-1 mb-4">';
                    $inList = true;
                }
                $result[] = '<li>' . trim($matches[1]) . '</li>';
            } else {
                if ($inList) {
                    $result[] = '</ul>';
                    $inList = false;
                }
                if (!empty($line)) {
                    $result[] = $line;
                }
            }
        }
        
        if ($inList) {
            $result[] = '</ul>';
        }
        
        return implode("\n", $result);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
