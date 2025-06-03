@extends('layouts.app')

@section('title', $job->title)

@section('content')
    <article class="prose max-w-none">
        <h1 class="text-3xl font-bold mb-4">{{ $job->title }}</h1>
        <p class="text-sm text-gray-600 mb-4">
            {{ $job->city }} {{ $job->postal_code }} - {{ $job->country }} | {{ $job->job_type }}
        </p>
        <div class="mb-4">
            {!! nl2br(e($job->description)) !!}
        </div>
        @if($job->contact_email)
            <p class="mt-6">Bewerbung an: <a class="text-blue-600" href="mailto:{{ $job->contact_email }}">{{ $job->contact_email }}</a></p>
        @endif
    </article>
@endsection
