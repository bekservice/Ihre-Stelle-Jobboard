@extends('layouts.app')
@php use Illuminate\Support\Str; @endphp

@section('title', 'Stellenangebote')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Aktuelle Stellenangebote</h1>
    <div class="space-y-6">
        @forelse($jobs as $job)
            <div class="bg-white shadow rounded p-6">
                <h2 class="text-xl font-semibold mb-2">
                    <a href="{{ route('jobs.show', $job->slug) }}" class="hover:underline">
                        {{ $job->title }}
                    </a>
                </h2>
                <p class="text-sm text-gray-600 mb-2">
                    {{ $job->city }} {{ $job->postal_code }} - {{ $job->country }}
                </p>
                <p class="line-clamp-3 text-gray-700">{{ Str::limit(strip_tags($job->description), 200) }}</p>
            </div>
        @empty
            <p>Zurzeit sind keine Stellen verfügbar.</p>
        @endforelse
    </div>
    <div class="mt-6">
        {{ $jobs->links() }}
    </div>
@endsection
