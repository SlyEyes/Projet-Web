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
            <div>Entreprise<div>
            <article>
                Note : 4.5/5
</article>
<h2>Stage Disponible</h2>
</div>
<article> 
    @foreach ({{$company->stages}} as $stage)
        <div class="box">
            <div class="a">
                {{ $internship->title }}
                <br>
                {{ $internship->city }} {{ $stage->endDate - $stage->beginDate }}
            </div>
        </div>
    @endforeach
</article>

        </section>
    </main>
@endsection
