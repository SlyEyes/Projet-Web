@extends('layouts.app')


@section('head')
    @pagestyle('company')
@endsection


@section('content')
    <main>
        <section>
            <img src="/public/icons/close-purple.svg" class="croix" alt=" " href="/">

            <div>
        {{--<img src="{{ $company->logo }}" alt=" "><h1 class="test" >{{ $company->name }}</h1>--}}
            </div>

            <h2>Entreprise</h2>

            <article>
                Note : 4.5/5
            </article>

            <h2>Stage Disponible</h2>

            <article>
                @foreach ($internship as $i)
                    <div class="box">
                        <div class="a">
                            {{ $i->title }}
                            <br>
                            {{ $i->city }} {{ $i->endDate }} - {{ $i->beginDate }}
                        </div>
                    </div>
                @endforeach
            </article>

        </section>
    </main>
@endsection
