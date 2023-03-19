@extends('layouts.app', ['title' => $pageTitle . ' - LinkedOut'])


@section('head')
    @pagestyle('dashboard')
    @pagescript('dashboard')
@endsection


@section('content')
    <main>
        @include('components.dashboard.aside')

        <section>
            <div class="dashboard-header">
                <h3>{{ $pageTitle }}</h3>
                <a href="/dashboard/{{ $collection }}/new" class="icon-btn">
                    <img src="/public/icons/plus-white.svg" alt="+">
                </a>
            </div>

            @includeWhen(empty($destination), 'components.dashboard.list')
        </section>
    </main>
@endsection
