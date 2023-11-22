@extends('layouts.base')
@section('page_title', 'Звітність по Фондам та Зборам - donater.com.ua')
@section('page_description', 'Вся звітність по Фондам та актуальним зборам коштів - donater.com.ua')
@php $withZvitLink = true; @endphp
@section('content')
    <div class="container px-4 py-5">
        <h2 class="pb-2 border-bottom">Звітність по Фондам та Зборам</h2>
        @foreach($volunteers->all() as $it => $volunteer)
            <div class="container px-4 py-5">
                <h3 class="pb-2 border-bottom">
                    {{ $volunteer->getName() }}
                </h3>
                <div class="row">
                    <div class="col-md-4 px-2 py-2">
                        <div class="card border-0 rounded-4 shadow-lg">
                            <a href="{{ route('zvit.volunteer', ['volunteer' => $volunteer->getKey()]) }}" class="card">
                                <img src="{{ url($volunteer->getAvatar()) }}" class="bg-image-position-center"
                                     alt="{{ $volunteer->getName() }}">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8 px-2 py-2">
                        <p class="lead">
                            {!! $volunteer->getDescription() !!}
                        </p>
                        @include('layouts.links', compact('volunteer', 'withZvitLink'))
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
