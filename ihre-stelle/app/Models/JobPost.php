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
        'schulabschluss',
        'ablaufdatum',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_modified_at' => 'datetime',
        'angelegt_am' => 'datetime',
        'last_modified_time_status' => 'datetime',
        'lastmodify_time' => 'datetime',
        'job_type' => 'json',
        'job_typ_multiple' => 'json',
        'arbeitsgeber_tel' => 'json',
        'arbeitsgeber_website' => 'json',
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

    /**
     * Get employment type for Schema.org format
     */
    public function getEmploymentTypeForSchema(): array
    {
        if (!$this->job_type || !is_array($this->job_type)) {
            return ['OTHER'];
        }

        $employmentTypes = [];
        foreach ($this->job_type as $type) {
            switch (strtolower(trim($type))) {
                case 'vollzeit':
                case 'full time':
                case 'vollzeit & teilzeit':
                    $employmentTypes[] = 'FULL_TIME';
                    break;
                case 'teilzeit':
                case 'part time':
                    $employmentTypes[] = 'PART_TIME';
                    break;
                case 'praktikum':
                case 'internship':
                    $employmentTypes[] = 'INTERN';
                    break;
                case 'freelance':
                case 'freiberuflich':
                    $employmentTypes[] = 'CONTRACTOR';
                    break;
                case 'minijob':
                case 'aushilfe':
                case '450€ job':
                    $employmentTypes[] = 'PART_TIME';
                    break;
                case 'ausbildung':
                case 'apprenticeship':
                    $employmentTypes[] = 'INTERN';
                    break;
                default:
                    $employmentTypes[] = 'OTHER';
            }
        }
        
        return array_unique($employmentTypes);
    }

    /**
     * Get salary information for Schema.org format
     */
    public function getSalaryForSchema(): ?array
    {
        if (!$this->grundgehalt) {
            return null;
        }

        // Extract numeric value from grundgehalt
        $salaryValue = null;
        if (preg_match('/(\d+(?:[.,]\d+)?)/', $this->grundgehalt, $matches)) {
            $salaryValue = (float) str_replace(',', '.', $matches[1]);
        }

        if (!$salaryValue) {
            return null;
        }

        $unitText = 'MONTH'; // Default
        if ($this->bezahlung) {
            switch (strtolower(trim($this->bezahlung))) {
                case 'stündlich':
                case 'hourly':
                case 'pro stunde':
                    $unitText = 'HOUR';
                    break;
                case 'täglich':
                case 'daily':
                case 'pro tag':
                    $unitText = 'DAY';
                    break;
                case 'wöchentlich':
                case 'weekly':
                case 'pro woche':
                    $unitText = 'WEEK';
                    break;
                case 'jährlich':
                case 'yearly':
                case 'pro jahr':
                    $unitText = 'YEAR';
                    break;
                case 'monatlich':
                case 'monthly':
                case 'pro monat':
                default:
                    $unitText = 'MONTH';
                    break;
            }
        }

        return [
            '@type' => 'MonetaryAmount',
            'currency' => 'EUR',
            'value' => [
                '@type' => 'QuantitativeValue',
                'value' => $salaryValue,
                'unitText' => $unitText
            ]
        ];
    }

    /**
     * Get organization information for Schema.org format
     */
    public function getOrganizationForSchema(): array
    {
        $organization = [
            '@type' => 'Organization',
            'name' => $this->arbeitsgeber_name ?: 'Vertraulich',
        ];

        // Add website
        if ($this->arbeitsgeber_website && is_array($this->arbeitsgeber_website) && !empty($this->arbeitsgeber_website)) {
            $website = $this->arbeitsgeber_website[0];
            if (!str_starts_with($website, 'http')) {
                $website = 'https://' . $website;
            }
            $organization['sameAs'] = $website;
            $organization['url'] = $website;
        }

        // Add logo from various sources
        $logoUrl = null;
        
        // 1. Try downloaded logo from "Info für uns"
        if ($this->info_fuer_uns && str_contains($this->info_fuer_uns, 'job-logos/')) {
            $logoUrl = asset('storage/' . $this->info_fuer_uns);
        }
        // 2. Try job_logo from Airtable
        elseif ($this->job_logo && is_array($this->job_logo) && !empty($this->job_logo)) {
            $logoUrl = $this->job_logo[0]['url'] ?? null;
        }
        // 3. Fallback to default logo
        else {
            $logoUrl = asset('logo/ihre-stelle_logo_quer-logo.png');
        }

        if ($logoUrl) {
            $organization['logo'] = $logoUrl;
        }

        // Add contact information
        if ($this->arbeitsgeber_tel && is_array($this->arbeitsgeber_tel) && !empty($this->arbeitsgeber_tel)) {
            $organization['telephone'] = $this->arbeitsgeber_tel[0];
        }

        return $organization;
    }

    /**
     * Get location information for Schema.org format
     */
    public function getLocationForSchema(): array
    {
        $location = [
            '@type' => 'Place',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $this->city ?: 'Deutschland',
                'addressCountry' => $this->country ?: 'DE'
            ]
        ];

        if ($this->postal_code) {
            $location['address']['postalCode'] = $this->postal_code;
        }

        // Add streetAddress with intelligent fallback strategy
        $streetAddressResult = $this->getStreetAddressFallback();
        if ($streetAddressResult) {
            if (is_array($streetAddressResult)) {
                $location['address']['streetAddress'] = $streetAddressResult['address'];
                // Add debug comment for development
                if (config('app.debug')) {
                    $location['_debug_streetAddress_source'] = $streetAddressResult['source'];
                }
            } else {
                $location['address']['streetAddress'] = $streetAddressResult;
            }
        }

        // Add coordinates if available
        if ($this->latitude && $this->longitude) {
            $location['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => (float) $this->latitude,
                'longitude' => (float) $this->longitude
            ];
        }

        return $location;
    }

    /**
     * Get street address with fallback strategy for Schema.org
     */
    private function getStreetAddressFallback()
    {
        // Strategy 1: Extract street address from job description
        if ($this->description) {
            $streetFromDescription = $this->extractStreetFromDescription();
            if ($streetFromDescription) {
                return config('app.debug') 
                    ? ['address' => $streetFromDescription, 'source' => 'job_description']
                    : $streetFromDescription;
            }
        }

        // Strategy 2: Extract from "info_fuer_uns" field if it contains address information
        if ($this->info_fuer_uns && is_string($this->info_fuer_uns) && !str_contains($this->info_fuer_uns, 'job-logos/')) {
            $streetFromInfo = $this->extractStreetFromText($this->info_fuer_uns);
            if ($streetFromInfo) {
                return config('app.debug') 
                    ? ['address' => $streetFromInfo, 'source' => 'info_fuer_uns']
                    : $streetFromInfo;
            }
        }

        // Strategy 3: Use company name as location identifier
        if ($this->arbeitsgeber_name && $this->arbeitsgeber_name !== 'Vertraulich') {
            $fallbackAddress = "bei {$this->arbeitsgeber_name}";
            return config('app.debug') 
                ? ['address' => $fallbackAddress, 'source' => 'company_name_fallback']
                : $fallbackAddress;
        }

        // Strategy 4: Use city center as fallback (common for job postings)
        if ($this->city) {
            $fallbackAddress = "Stadtgebiet {$this->city}";
            return config('app.debug') 
                ? ['address' => $fallbackAddress, 'source' => 'city_fallback']
                : $fallbackAddress;
        }

        // Strategy 5: Generic fallback
        $fallbackAddress = "Arbeitsort vor Ort";
        return config('app.debug') 
            ? ['address' => $fallbackAddress, 'source' => 'generic_fallback']
            : $fallbackAddress;
    }

    /**
     * Extract street address from job description
     */
    private function extractStreetFromDescription(): ?string
    {
        return $this->extractStreetFromText($this->description);
    }

    /**
     * Extract street address from any text content
     */
    private function extractStreetFromText(?string $text): ?string
    {
        if (!$text) {
            return null;
        }
        
        // Pattern 1: German street patterns (Straßenname + Hausnummer)
        $patterns = [
            // "Musterstraße 123" or "Musterstr. 123"
            '/([A-ZÄÖÜ][a-zäöüß]+(?:straße|str\.?|weg|platz|gasse|allee|ring|damm)\s+\d+[a-zA-Z]?)/iu',
            
            // "Am Musterweg 5" or "An der Hauptstraße 10"
            '/((?:Am|An\s+der?|Zur?|In\s+der?)\s+[A-ZÄÖÜ][a-zäöüß]+(?:straße|str\.?|weg|platz|gasse|allee|ring|damm)\s+\d+[a-zA-Z]?)/iu',
            
            // "Hauptstraße 15-17" (ranges)
            '/([A-ZÄÖÜ][a-zäöüß]+(?:straße|str\.?|weg|platz|gasse|allee|ring|damm)\s+\d+[-–]\d+[a-zA-Z]?)/iu',
            
            // Simple patterns like "Adresse: Musterstraße 1"
            '/(?:Adresse|Standort|Arbeitsort):\s*([A-ZÄÖÜ][a-zäöüß]+(?:straße|str\.?|weg|platz|gasse|allee|ring|damm)\s+\d+[a-zA-Z]?)/iu',
            
            // Patterns with shortened street names like "Pegnitzstr. 31"
            '/([A-ZÄÖÜ][a-zäöüß]+str\.\s+\d+[a-zA-Z]?)/iu',
            
            // International patterns for address lines
            '/(?:Adresse|Address|Standort|Arbeitsort):\s*([A-ZÄÖÜ][a-zäöüß\s]+\d+[a-zA-Z]?)/iu'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $streetAddress = trim($matches[1]);
                
                // Validate that it's a reasonable street address
                if (strlen($streetAddress) > 5 && strlen($streetAddress) < 100) {
                    return $streetAddress;
                }
            }
        }

        // Pattern 2: Look for company address formats
        if (preg_match('/(?:Adresse|Standort|Arbeitsort):\s*([^\n\r]+)/iu', $text, $matches)) {
            $addressLine = trim($matches[1]);
            
            // Clean up and validate
            $addressLine = preg_replace('/[,;]+.*$/', '', $addressLine); // Remove everything after comma/semicolon
            $addressLine = trim($addressLine);
            
            if (strlen($addressLine) > 5 && strlen($addressLine) < 100 && 
                !preg_match('/\b(?:Tel|Telefon|E-?Mail|Website|www)\b/i', $addressLine)) {
                return $addressLine;
            }
        }

        // Pattern 3: Direct address pattern like "Gewerbestr. 1" or "Kaufbeurer Str. 8"
        if (preg_match('/\b([A-ZÄÖÜ][a-zäöüß]*(?:str|straße|weg|platz|gasse|allee|ring|damm)\.?\s+\d+[a-zA-Z]?)\b/iu', $text, $matches)) {
            $streetAddress = trim($matches[1]);
            
            if (strlen($streetAddress) > 5 && strlen($streetAddress) < 100) {
                return $streetAddress;
            }
        }

        return null;
    }

    /**
     * Get experience requirements for Schema.org format
     */
    public function getExperienceRequirementsForSchema(): ?array
    {
        if (!$this->berufserfahrung) {
            return null;
        }

        $experienceText = strtolower(trim($this->berufserfahrung));
        
        // Check for "no experience" keywords
        if (str_contains($experienceText, 'keine') || 
            str_contains($experienceText, 'neuling') ||
            str_contains($experienceText, 'berufseinsteiger') ||
            str_contains($experienceText, 'no experience')) {
            return [
                '@type' => 'OccupationalExperienceRequirements',
                'monthsOfExperience' => 0
            ];
        }

        // Extract years/months of experience
        $monthsOfExperience = 0;
        
        if (preg_match('/(\d+)\s*(?:jahr|year)(?:e|s)?/i', $experienceText, $matches)) {
            $monthsOfExperience = (int)$matches[1] * 12;
        } elseif (preg_match('/(\d+)\s*(?:monat|month)(?:e|s)?/i', $experienceText, $matches)) {
            $monthsOfExperience = (int)$matches[1];
        }

        if ($monthsOfExperience > 0) {
            return [
                '@type' => 'OccupationalExperienceRequirements',
                'monthsOfExperience' => $monthsOfExperience
            ];
        }

        return null;
    }

    /**
     * Get education requirements for Schema.org format
     */
    public function getEducationRequirementsForSchema(): ?array
    {
        if (!$this->schulabschluss) {
            return null;
        }

        $educationText = strtolower(trim($this->schulabschluss));
        $credentialCategory = null;
        
        if (str_contains($educationText, 'abitur') || 
            str_contains($educationText, 'bachelor') ||
            str_contains($educationText, 'fachabitur')) {
            $credentialCategory = 'bachelor degree';
        } elseif (str_contains($educationText, 'master') || 
                  str_contains($educationText, 'diplom') ||
                  str_contains($educationText, 'magister')) {
            $credentialCategory = 'postgraduate degree';
        } elseif (str_contains($educationText, 'mittlere reife') || 
                  str_contains($educationText, 'realschule') ||
                  str_contains($educationText, 'hauptschule')) {
            $credentialCategory = 'high school';
        } elseif (str_contains($educationText, 'ausbildung') || 
                  str_contains($educationText, 'lehre') ||
                  str_contains($educationText, 'berufsschule')) {
            $credentialCategory = 'professional certificate';
        }
        
        if ($credentialCategory) {
            return [
                '@type' => 'EducationalOccupationalCredential',
                'credentialCategory' => $credentialCategory
            ];
        }

        return null;
    }

    /**
     * Get skills and qualifications for Schema.org format
     */
    public function getSkillsForSchema(): array
    {
        $skills = [];

        // Add from autotags
        if ($this->autotags && is_array($this->autotags)) {
            foreach ($this->autotags as $tag) {
                $skills[] = ucfirst(trim($tag));
            }
        }

        // Add from rolle_im_job
        if ($this->rolle_im_job && is_array($this->rolle_im_job)) {
            foreach ($this->rolle_im_job as $role) {
                $skills[] = ucfirst(trim($role));
            }
        }

        return array_unique($skills);
    }

    /**
     * Check if job supports remote work
     */
    public function isRemoteWorkAvailable(): bool
    {
        if (!$this->description) {
            return false;
        }

        $description = strtolower($this->description);
        return str_contains($description, 'homeoffice') ||
               str_contains($description, 'remote') ||
               str_contains($description, 'home office') ||
               str_contains($description, 'mobiles arbeiten') ||
               str_contains($description, 'telearbeit');
    }

    /**
     * Get all unique employers with job counts
     */
    public static function getEmployersWithJobCounts()
    {
        return self::where('is_active', true)
            ->whereNotNull('arbeitsgeber_name')
            ->where('arbeitsgeber_name', '!=', '')
            ->selectRaw('arbeitsgeber_name, arbeitsgeber_website, arbeitsgeber_tel, info_fuer_uns, job_logo, COUNT(*) as job_count, MIN(city) as primary_city')
            ->groupBy('arbeitsgeber_name', 'arbeitsgeber_website', 'arbeitsgeber_tel', 'info_fuer_uns', 'job_logo')
            ->orderBy('job_count', 'desc')
            ->get()
            ->map(function ($employer) {
                $employer->slug = \Str::slug($employer->arbeitsgeber_name);
                return $employer;
            });
    }

    /**
     * Get all unique cities with job counts
     */
    public static function getCitiesWithJobCounts()
    {
        return self::where('is_active', true)
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->selectRaw('city, postal_code, country, latitude, longitude, COUNT(*) as job_count')
            ->groupBy('city', 'postal_code', 'country', 'latitude', 'longitude')
            ->orderBy('job_count', 'desc')
            ->get()
            ->map(function ($city) {
                $city->slug = \Str::slug($city->city);
                return $city;
            });
    }

    /**
     * Get jobs by employer name
     */
    public static function getJobsByEmployer($employerName)
    {
        return self::where('is_active', true)
            ->where('arbeitsgeber_name', $employerName)
            ->latest('created_at')
            ->paginate(12);
    }

    /**
     * Get jobs by city
     */
    public static function getJobsByCity($cityName)
    {
        return self::where('is_active', true)
            ->where('city', $cityName)
            ->latest('created_at')
            ->paginate(12);
    }

    /**
     * Get top categories for a city
     */
    public static function getTopCategoriesForCity($cityName, $limit = 5)
    {
        return self::where('is_active', true)
            ->where('city', $cityName)
            ->whereNotNull('kategorie')
            ->selectRaw('kategorie, COUNT(*) as job_count')
            ->groupBy('kategorie')
            ->orderBy('job_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get top employers for a city
     */
    public static function getTopEmployersForCity($cityName, $limit = 5)
    {
        return self::where('is_active', true)
            ->where('city', $cityName)
            ->whereNotNull('arbeitsgeber_name')
            ->where('arbeitsgeber_name', '!=', '')
            ->selectRaw('arbeitsgeber_name, COUNT(*) as job_count')
            ->groupBy('arbeitsgeber_name')
            ->orderBy('job_count', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($employer) {
                $employer->slug = \Str::slug($employer->arbeitsgeber_name);
                return $employer;
            });
    }

    /**
     * Get cities where employer has jobs
     */
    public static function getCitiesForEmployer($employerName)
    {
        return self::where('is_active', true)
            ->where('arbeitsgeber_name', $employerName)
            ->whereNotNull('city')
            ->selectRaw('city, COUNT(*) as job_count')
            ->groupBy('city')
            ->orderBy('job_count', 'desc')
            ->get()
            ->map(function ($city) {
                $city->slug = \Str::slug($city->city);
                return $city;
            });
    }

    /**
     * Get categories for employer
     */
    public static function getCategoriesForEmployer($employerName)
    {
        return self::where('is_active', true)
            ->where('arbeitsgeber_name', $employerName)
            ->whereNotNull('kategorie')
            ->selectRaw('kategorie, COUNT(*) as job_count')
            ->groupBy('kategorie')
            ->orderBy('job_count', 'desc')
            ->get();
    }
}
