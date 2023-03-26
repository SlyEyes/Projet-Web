@extends('layouts.app', ['title' => $pageTitle . ' - LinkedOut'])


@section('head')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" async></script>
    @pagestyle('dashboard')
    @pagescript('dashboard')
@endsection


@section('content')
    <main class="main">
        <div class="dashboard-container">
            @include('components.dashboard.aside')

            <section>
                <div class="dashboard-header">
                    <h3>{{ $pageTitle }}</h3>
                    @if ($layout == 'list')
                        <a href="/dashboard/{{ $collection }}/new" class="icon-btn">
                            <img src="/public/icons/plus-white.svg" alt="+">
                        </a>
                    @else
                        <div class="icon-btn-group">
                            @if ($layout == 'edit' && $collection == 'internships')
                                <form method="post">
                                    <input type="hidden" name="delete" value="true">
                                    <button class="icon-btn red-icon-btn" id="delete-btn">
                                        <img src="/public/icons/trash-white.svg" alt="x">
                                    </button>
                                </form>
                            @endif

                            <button form="content-edit" class="icon-btn">
                                <img src="/public/icons/check-white.svg" alt="v">
                            </button>
                        </div>
                    @endif
                </div>

                @includeWhen($layout == 'list', 'components.dashboard.list')

                @if ($layout == 'edit' || $layout == 'create')
                    @if($error)
                        <div class="error">
                            <p>{{ $error }}</p>
                        </div>
                    @endif
                    <form id="content-edit" method="POST">
                        @includeWhen(
                            is_numeric(array_search($collection, ['students', 'tutors', 'administrators'])),
                            'components.dashboard.person-edit'
                        )
                        @includeWhen($collection == 'internships', 'components.dashboard.internship-edit')
                        @includeWhen($collection == 'companies', 'components.dashboard.company-edit')
                    </form>
                @endif
            </section>
        </div>
    </main>
@endsection
