@php
    $structuredData = [
        '@context' => 'https://schema.org/',
        '@type' => 'JobPosting',
        'title' => $job->title,
        'description' => $job->formatted_description ?: strip_tags($job->description),
        'identifier' => [
            '@type' => 'PropertyValue',
            'name' => $job->arbeitsgeber_name ?: 'Ihre Stelle',
            'value' => $job->airtable_id ?: $job->id
        ],
        'datePosted' => $job->created_at->format('Y-m-d'),
        'hiringOrganization' => [
            '@type' => 'Organization',
            'name' => $job->arbeitsgeber_name ?: 'Confidential',
        ],
        'jobLocation' => [
            '@type' => 'Place',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $job->city,
                'postalCode' => $job->postal_code,
                'addressCountry' => $job->country ?: 'DE'
            ]
        ]
    ];

    // Add website to hiring organization if available
    if ($job->arbeitsgeber_website && is_array($job->arbeitsgeber_website) && count($job->arbeitsgeber_website) > 0) {
        $structuredData['hiringOrganization']['sameAs'] = $job->arbeitsgeber_website[0];
    }

    // Add logo if available
    if ($job->job_logo && is_array($job->job_logo) && count($job->job_logo) > 0) {
        $structuredData['hiringOrganization']['logo'] = $job->job_logo[0]['url'];
    }

    // Add employment type
    if ($job->job_type && is_array($job->job_type)) {
        $employmentTypes = [];
        foreach ($job->job_type as $type) {
            switch (strtolower($type)) {
                case 'vollzeit':
                case 'full time':
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
                    $employmentTypes[] = 'PART_TIME';
                    break;
                default:
                    $employmentTypes[] = 'OTHER';
            }
        }
        if (!empty($employmentTypes)) {
            $structuredData['employmentType'] = count($employmentTypes) === 1 ? $employmentTypes[0] : $employmentTypes;
        }
    }

    // Add salary information if available
    if ($job->grundgehalt) {
        $unitText = 'MONTH'; // Default to monthly
        if ($job->bezahlung) {
            switch (strtolower($job->bezahlung)) {
                case 'stündlich':
                case 'hourly':
                    $unitText = 'HOUR';
                    break;
                case 'täglich':
                case 'daily':
                    $unitText = 'DAY';
                    break;
                case 'wöchentlich':
                case 'weekly':
                    $unitText = 'WEEK';
                    break;
                case 'jährlich':
                case 'yearly':
                    $unitText = 'YEAR';
                    break;
            }
        }

        $structuredData['baseSalary'] = [
            '@type' => 'MonetaryAmount',
            'currency' => 'EUR',
            'value' => [
                '@type' => 'QuantitativeValue',
                'value' => (float) $job->grundgehalt,
                'unitText' => $unitText
            ]
        ];
    }

    // Add expiration date if available
    if ($job->ablaufdatum) {
        $structuredData['validThrough'] = \Carbon\Carbon::parse($job->ablaufdatum)->format('Y-m-d\TH:i:s');
    }

    // Add direct apply if email is available
    if ($job->bewerbung_an_mail) {
        $structuredData['directApply'] = true;
    }

    // Add experience requirements if available
    if ($job->berufserfahrung) {
        $experienceText = strtolower($job->berufserfahrung);
        $monthsOfExperience = 0;
        
        if (str_contains($experienceText, 'keine') || str_contains($experienceText, 'no')) {
            $structuredData['experienceRequirements'] = 'no requirements';
        } elseif (preg_match('/(\d+)\s*(jahr|year)/i', $experienceText, $matches)) {
            $monthsOfExperience = (int)$matches[1] * 12;
        } elseif (preg_match('/(\d+)\s*(monat|month)/i', $experienceText, $matches)) {
            $monthsOfExperience = (int)$matches[1];
        }
        
        if ($monthsOfExperience > 0) {
            $structuredData['experienceRequirements'] = [
                '@type' => 'OccupationalExperienceRequirements',
                'monthsOfExperience' => $monthsOfExperience
            ];
        }
    }

    // Add education requirements if available
    if ($job->schulabschluss) {
        $educationText = strtolower($job->schulabschluss);
        $credentialCategory = null;
        
        if (str_contains($educationText, 'abitur') || str_contains($educationText, 'bachelor')) {
            $credentialCategory = 'bachelor degree';
        } elseif (str_contains($educationText, 'master') || str_contains($educationText, 'diplom')) {
            $credentialCategory = 'postgraduate degree';
        } elseif (str_contains($educationText, 'mittlere reife') || str_contains($educationText, 'realschule')) {
            $credentialCategory = 'high school';
        } elseif (str_contains($educationText, 'ausbildung') || str_contains($educationText, 'lehre')) {
            $credentialCategory = 'professional certificate';
        }
        
        if ($credentialCategory) {
            $structuredData['educationRequirements'] = [
                '@type' => 'EducationalOccupationalCredential',
                'credentialCategory' => $credentialCategory
            ];
        }
    }

    // Add work location type for remote work
    if ($job->description && (
        str_contains(strtolower($job->description), 'homeoffice') ||
        str_contains(strtolower($job->description), 'remote') ||
        str_contains(strtolower($job->description), 'home office')
    )) {
        $structuredData['jobLocationType'] = 'TELECOMMUTE';
        $structuredData['applicantLocationRequirements'] = [
            '@type' => 'Country',
            'name' => 'Germany'
        ];
    }
@endphp

<script type="application/ld+json">
{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script> 