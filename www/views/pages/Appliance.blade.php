@extends('layouts.app')


@section('head')
    @pagestyle('appliance')
@endsection


@section('content')
    <main>
        <section>
        {{-- TODO: mettre le href de la croix --}}

            <img src="/public/icons/close-purple.svg" class="cross" alt=" ">
            <div class="appliance-header">
            <img src="/public/images/LinkedOut.png" alt=" ">
            <div class="appliance-title">
                <h3>Intitulé du stage</h3>
                <div> 4 mois-Nancy-Credits agricoles 
                </div>
            </div>
        </div>



            <div>
                <article>
Envoyer votre candidature composé de votre CV et lettre de motivation à l'adresse suivante : <a href=>...</a>.
</article>
</div>

            

            <div>
    
    <div class="validation"><input type="checkbox" class="checkbox" id="check" name="verification" value="true" > Avant de postuler, j'atteste que j'ai bien envoyé le mail de candidature à l'entreprise.</div>
</div>

            <div>
                <button class="btn-primary">Valider ma candidature</button>
            </div>

        </section>
    </main>
@endsection
