@extends('layouts.base')
@section('page_title', 'Звітність по Фондам та Зборам - donater.com.ua')
@section('page_description', 'Вся звітність по Фондам та актуальним зборам коштів - donater.com.ua')

@section('content')
    <div class="container px-4 py-5" id="custom-cards">
        <h2 class="pb-2 border-bottom">Звітність по Фондам та Зборам</h2>
        @foreach($volunteers->all() as $it => $volunteer)
            @if(1 === (1 + $it) % 3)
                <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-3">
            @endif
            <div class="col">
                <a href="{{ route('zvit.volunteer', ['volunteer' => $volunteer->getKey()]) }}"
                   class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg bg-image-position-center"
                   style="background-image: url('{{ url($volunteer->getAvatar()) }}');">
                    <div class="d-flex flex-column min-vh-25 h-100 p-4 pb-3 text-shadow-1">
                    </div>
                </a>
            </div>
            @if(0 === (1 + $it) % 3)
                </div>
            @endif
        @endforeach
    </div>
@endsection
