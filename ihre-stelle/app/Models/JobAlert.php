<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'category',
        'location',
        'job_types',
        'frequency',
        'is_active',
        'token',
        'last_sent_at',
        'email_verified_at',
    ];

    protected $casts = [
        'job_types' => 'array',
        'is_active' => 'boolean',
        'last_sent_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $alert) {
            $alert->token = $alert->token ?: Str::random(32);
        });
    }

    /**
     * Scope für aktive Alerts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope für verifizierte E-Mail-Adressen
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope für bestimmte Kategorie
     */
    public function scopeForCategory($query, $category)
    {
        return $query->where(function ($q) use ($category) {
            $q->where('category', $category)
              ->orWhereNull('category'); // Alle Kategorien
        });
    }

    /**
     * Scope für bestimmten Standort
     */
    public function scopeForLocation($query, $location)
    {
        return $query->where(function ($q) use ($location) {
            $q->where('location', 'like', "%{$location}%")
              ->orWhereNull('location'); // Alle Standorte
        });
    }

    /**
     * Scope für bestimmte Job-Typen
     */
    public function scopeForJobTypes($query, $jobTypes)
    {
        if (empty($jobTypes)) {
            return $query;
        }

        return $query->where(function ($q) use ($jobTypes) {
            foreach ($jobTypes as $jobType) {
                $q->orWhereJsonContains('job_types', $jobType);
            }
            $q->orWhereNull('job_types'); // Alle Job-Typen
        });
    }

    /**
     * Prüft ob der Alert für einen Job relevant ist
     */
    public function matchesJob(JobPost $job): bool
    {
        // Kategorie prüfen
        if ($this->category && $this->category !== $job->kategorie) {
            return false;
        }

        // Standort prüfen
        if ($this->location && $job->city && 
            stripos($job->city, $this->location) === false && 
            stripos($this->location, $job->city) === false) {
            return false;
        }

        // Job-Typen prüfen
        if ($this->job_types && $job->job_type) {
            $jobTypes = is_array($job->job_type) ? $job->job_type : [$job->job_type];
            $hasMatch = false;
            foreach ($this->job_types as $alertJobType) {
                if (in_array($alertJobType, $jobTypes)) {
                    $hasMatch = true;
                    break;
                }
            }
            if (!$hasMatch) {
                return false;
            }
        }

        return true;
    }

    /**
     * Markiert den Alert als versendet
     */
    public function markAsSent(): void
    {
        $this->update(['last_sent_at' => now()]);
    }

    /**
     * Verifiziert die E-Mail-Adresse
     */
    public function verify(): void
    {
        $this->update(['email_verified_at' => now()]);
    }

    /**
     * Deaktiviert den Alert
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }
}
