<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            // Grundlegende Felder
            $table->string('anrede')->nullable()->after('title');
            $table->string('ansprechpartner_hr')->nullable()->after('anrede');
            $table->text('info_fuer_uns')->nullable()->after('ansprechpartner_hr');
            $table->string('bewerbungen_an_link')->nullable()->after('info_fuer_uns');
            $table->string('bewerbung_an_mail')->nullable()->after('bewerbungen_an_link');
            $table->timestamp('angelegt_am')->nullable()->after('bewerbung_an_mail');
            $table->timestamp('last_modified_time_status')->nullable()->after('angelegt_am');
            
            // Kategorisierung
            $table->string('kategorie')->nullable()->after('last_modified_time_status');
            $table->json('job_typ_multiple')->nullable()->after('kategorie'); // Multiple select als JSON
            $table->string('land')->nullable()->after('country');
            
            // Arbeitgeber Informationen
            $table->string('arbeitsgeber_final_id')->nullable()->after('land');
            $table->string('arbeitsgeber_name')->nullable()->after('arbeitsgeber_final_id');
            $table->string('arbeitsgeber_tel')->nullable()->after('arbeitsgeber_name');
            $table->string('arbeitsgeber_website')->nullable()->after('arbeitsgeber_tel');
            
            // Bezahlung
            $table->string('bezahlung')->nullable()->after('arbeitsgeber_website');
            $table->string('grundgehalt')->nullable()->after('bezahlung');
            
            // Bilder/Attachments
            $table->json('banner_fb')->nullable()->after('grundgehalt'); // Attachment als JSON
            $table->json('job_logo')->nullable()->after('banner_fb'); // Info für uns Attachment
            
            // Tags und Benefits
            $table->json('autotags')->nullable()->after('job_logo'); // Multiple select als JSON
            $table->json('benefits')->nullable()->after('autotags'); // Multiple select als JSON
            $table->json('rolle_im_job')->nullable()->after('benefits'); // Multiple select als JSON
            
            // Erfahrung und Details
            $table->string('berufserfahrung')->nullable()->after('rolle_im_job');
            $table->string('plz_job')->nullable()->after('postal_code');
            $table->string('bundesland_job')->nullable()->after('plz_job');
            $table->string('short_link')->nullable()->after('bundesland_job');
            $table->string('job_typ_en')->nullable()->after('short_link');
            $table->string('record_id')->nullable()->after('job_typ_en');
            $table->timestamp('lastmodify_time')->nullable()->after('record_id');
            
            // Bewerbungen Anzahl
            $table->integer('bewerbungen_anzahl')->default(0)->after('lastmodify_time');
            
            // Indizes für bessere Performance
            $table->index('kategorie');
            $table->index('berufserfahrung');
            $table->index('record_id');
            $table->index('lastmodify_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            $table->dropColumn([
                'anrede', 'ansprechpartner_hr', 'info_fuer_uns', 'bewerbungen_an_link',
                'bewerbung_an_mail', 'angelegt_am', 'last_modified_time_status',
                'kategorie', 'job_typ_multiple', 'land', 'arbeitsgeber_final_id',
                'arbeitsgeber_name', 'arbeitsgeber_tel', 'arbeitsgeber_website',
                'bezahlung', 'grundgehalt', 'banner_fb', 'job_logo', 'autotags',
                'benefits', 'rolle_im_job', 'berufserfahrung', 'plz_job',
                'bundesland_job', 'short_link', 'job_typ_en', 'record_id',
                'lastmodify_time', 'bewerbungen_anzahl'
            ]);
        });
    }
};
