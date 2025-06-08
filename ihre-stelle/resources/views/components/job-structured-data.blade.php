@php
    // Base structured data with required fields
    $structuredData = [
        '@context' => 'https://schema.org/',
        '@type' => 'JobPosting',
        'title' => $job->title ?: 'Stellenausschreibung',
        'description' => $job->formatted_description ?: strip_tags($job->description) ?: 'Detaillierte Stellenbeschreibung verfügbar.',
        'identifier' => [
            '@type' => 'PropertyValue',
            'name' => $job->arbeitsgeber_name ?: 'Ihre-Stelle.de',
            'value' => $job->airtable_id ?: $job->id
        ],
        'datePosted' => $job->created_at ? $job->created_at->format('Y-m-d') : now()->format('Y-m-d'),
        'hiringOrganization' => $job->getOrganizationForSchema(),
        'jobLocation' => $job->getLocationForSchema(),
        'url' => route('jobs.show', $job->slug),
        'applicationContact' => [
            '@type' => 'ContactPoint',
            'contactType' => 'HR',
            'email' => $job->bewerbung_an_mail ?: $job->contact_email ?: 'bewerbung@ihre-stelle.de'
        ]
    ];

    // Add employment type
    $employmentTypes = $job->getEmploymentTypeForSchema();
    if (!empty($employmentTypes)) {
        $structuredData['employmentType'] = count($employmentTypes) === 1 ? $employmentTypes[0] : $employmentTypes;
    }

    // Add salary information
    $salary = $job->getSalaryForSchema();
    if ($salary) {
        $structuredData['baseSalary'] = $salary;
    }

    // Add experience requirements
    $experience = $job->getExperienceRequirementsForSchema();
    if ($experience) {
        $structuredData['experienceRequirements'] = $experience;
    }

    // Add education requirements
    $education = $job->getEducationRequirementsForSchema();
    if ($education) {
        $structuredData['educationRequirements'] = $education;
    }

    // Add skills and qualifications
    $skills = $job->getSkillsForSchema();
    if (!empty($skills)) {
        $structuredData['skills'] = implode(', ', $skills);
        $structuredData['qualifications'] = $skills;
    }

    // Add job category/industry
    if ($job->kategorie) {
        $structuredData['industry'] = $job->kategorie;
        $structuredData['occupationalCategory'] = $job->kategorie;
    }

    // Add expiration date
    if ($job->ablaufdatum) {
        try {
            $expirationDate = \Carbon\Carbon::parse($job->ablaufdatum);
            $structuredData['validThrough'] = $expirationDate->format('Y-m-d\TH:i:s');
        } catch (\Exception $e) {
            // Fallback: 30 days from now
            $structuredData['validThrough'] = now()->addDays(30)->format('Y-m-d\TH:i:s');
        }
    } else {
        // Default expiration: 30 days from posting
        $structuredData['validThrough'] = ($job->created_at ?: now())->addDays(30)->format('Y-m-d\TH:i:s');
    }

    // Add direct apply capability
    if ($job->bewerbung_an_mail || $job->contact_email) {
        $structuredData['directApply'] = true;
    }

    // Add work location type for remote work
    if ($job->isRemoteWorkAvailable()) {
        $structuredData['jobLocationType'] = 'TELECOMMUTE';
        $structuredData['applicantLocationRequirements'] = [
            '@type' => 'Country',
            'name' => 'Germany'
        ];
    }

    // Add benefits if available
    if ($job->benefits && is_array($job->benefits) && !empty($job->benefits)) {
        $structuredData['jobBenefits'] = implode(', ', $job->benefits);
    }

    // Add contact person if available
    if ($job->ansprechpartner_hr) {
        $structuredData['applicationContact']['name'] = $job->ansprechpartner_hr;
        if ($job->anrede) {
            $structuredData['applicationContact']['honorificPrefix'] = $job->anrede;
        }
    }

    // Add telephone contact if available
    if ($job->arbeitsgeber_tel && is_array($job->arbeitsgeber_tel) && !empty($job->arbeitsgeber_tel)) {
        $structuredData['applicationContact']['telephone'] = $job->arbeitsgeber_tel[0];
    }

    // Add job posting URL for applications
    if ($job->bewerbungen_an_link) {
        $structuredData['applicationContact']['url'] = $job->bewerbungen_an_link;
    } else {
        // Fallback to our application form
        $structuredData['applicationContact']['url'] = route('applications.form', $job->slug);
    }

    // Add special requirements or notes
    $specialRequirements = [];
    
    // Check for driver's license requirements
    if ($job->description && (
        str_contains(strtolower($job->description), 'führerschein') ||
        str_contains(strtolower($job->description), 'driver') ||
        str_contains(strtolower($job->description), 'klasse b')
    )) {
        $specialRequirements[] = 'Führerschein erforderlich';
    }

    // Check for language requirements
    if ($job->description && (
        str_contains(strtolower($job->description), 'englisch') ||
        str_contains(strtolower($job->description), 'english') ||
        str_contains(strtolower($job->description), 'fremdsprache')
    )) {
        $specialRequirements[] = 'Fremdsprachenkenntnisse';
    }

    if (!empty($specialRequirements)) {
        $structuredData['specialCommitments'] = implode(', ', $specialRequirements);
    }

    // Add work hours if mentioned in description
    if ($job->description) {
        $description = strtolower($job->description);
        if (str_contains($description, 'vollzeit')) {
            $structuredData['workHours'] = '40 Stunden/Woche';
        } elseif (str_contains($description, 'teilzeit')) {
            $structuredData['workHours'] = 'Teilzeit';
        }
    }

    // Add incentive compensation if mentioned
    if ($job->description && (
        str_contains(strtolower($job->description), 'bonus') ||
        str_contains(strtolower($job->description), 'prämie') ||
        str_contains(strtolower($job->description), 'provision')
    )) {
        $structuredData['incentiveCompensation'] = 'Leistungsbezogene Vergütung verfügbar';
    }

    // Add job immediate start if mentioned
    if ($job->description && (
        str_contains(strtolower($job->description), 'sofort') ||
        str_contains(strtolower($job->description), 'ab sofort') ||
        str_contains(strtolower($job->description), 'immediate')
    )) {
        $structuredData['jobImmediateStart'] = true;
    }

    // Add total job openings if multiple positions
    if ($job->description && preg_match('/(\d+)\s*(?:stelle|position|job)/i', $job->description, $matches)) {
        $structuredData['totalJobOpenings'] = (int)$matches[1];
    } else {
        $structuredData['totalJobOpenings'] = 1;
    }

    // Add job posting source
    $structuredData['hiringOrganization']['@id'] = route('home');
    $structuredData['publisher'] = [
        '@type' => 'Organization',
        'name' => 'Ihre-Stelle.de',
        'url' => route('home'),
        'logo' => asset('logo/ihre-stelle_logo_quer-logo.png')
    ];

    // Add breadcrumb for better SEO
    $structuredData['mainEntityOfPage'] = [
        '@type' => 'WebPage',
        '@id' => route('jobs.show', $job->slug),
        'breadcrumb' => [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => route('home')
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Jobs',
                    'item' => route('jobs.search')
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $job->title,
                    'item' => route('jobs.show', $job->slug)
                ]
            ]
        ]
    ];

    // Clean up any null values to avoid JSON issues
    $structuredData = array_filter($structuredData, function($value) {
        return $value !== null && $value !== '' && $value !== [];
    });
@endphp

<script type="application/ld+json">
{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script> 