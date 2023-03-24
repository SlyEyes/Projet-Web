@extends('layouts.app')


@section('head')
    @pagestyle('company')
@endsection


@section('content')
    <main class="main">
        <section class="section section-md">
            <img src="/public/icons/close-purple.svg" class="close-btn" alt="x" onclick="window.history.back()">

            <div class="company-header">
                <img src="{{ $company->logo }}" alt="{{ $company->name }} logo">

                <div class="company-details">
                    <h3>{{ $company->name }}</h3>
                    <div>
                        {{ $company->internshipCount }}
                        @if($company->internshipCount > 1)
                            stages disponibles
                        @else
                            stage disponible
                        @endif
                        -
                        {{ $cities }}
                        <br/>
                        <a class="link" href="{{ $company->website }}">
                            {{ $company->website }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- TODO: global rating --}}

            @if(count($internships) > 0)
                <div class="company-section">
                    <h3>Stages Disponibles ({{ count($internships) }})</h3>

                    <div class="internships">
                        @foreach ($internships as $internship)
                            <a class="company-internship" href="/internship/{{ $internship['id'] }}">
                                <div class="internship-title">
                                    {{ $internship['title'] }}
                                </div>
                                <div class="internship-subtitle small">
                                    {{ $internship['city']->name }}, {{ $internship['duration'] }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
            {{-- TODO: ratings --}}

        </section>
    </main>
@endsection
