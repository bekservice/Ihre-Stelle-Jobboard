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
        'description',
        'status',
        'job_type',
        'city',
        'country',
        'postal_code',
        'longitude',
        'latitude',
        'last_modified_at',
        'contact_email',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_modified_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $post) {
            $post->slug = $post->slug ?: Str::slug($post->title.'-'.Str::random(6));
        });
    }
}
