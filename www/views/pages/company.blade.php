@extends('layouts.app')


@section('head')
    @pagestyle('company')
@endsection


@section('content')
    <main>
        <section>
            {{-- TODO: mettre le href de la croix --}}
            <img src="/public/icons/close-purple.svg" class="croix" alt=" ">

            <div>
        <img src="{{ $company->logo }}" alt=" "><h1 class="test" >{{ $company->name }}</h1>
            </div>

            <div class="details">{{ $company->internshipCount }} Stages disponibles- $cities -{{$company->website}} </div>

            <div>
                Note : 4.5/5
</div>

            <h2>Stage Disponible</h2>

            <div>
                @foreach ($internship as $i)
                    <div class="box">
                        <div class="a">
                            {{ $i->title }}
                            <br>
                            {{ $i->city->name }} <br> du {{ $i->endDate }} aux {{ $i->beginDate }}
                        </div>
                    </div>
                @endforeach
</div>

        </section>
    </main>
@endsection
