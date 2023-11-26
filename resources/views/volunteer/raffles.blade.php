@extends('layouts.base')
@section('page_title', 'Всі збори з розіграшами - donater.com.ua')
@section('page_description', 'Всі збори з розіграшами - donater.com.ua')
@php $withZvitLink = true; @endphp

@section('content')
    <div class="container px-4 py-5" >
        <h2 class="pb-2 border-bottom">
            Всі збори з розіграшами та призами
        </h2>
        <p class="lead">
            Для участі в розіграші треба зробити донат з кодом. Код ви можете знайти натиснувши
            <a href="{{route('donate')}}" class="btn ">
                <i class="bi bi-plus-circle-fill"></i>
                Задонатити
            </a>
        </p>
    </div>
    @foreach($volunteers->all() as $it => $volunteer)
        <div class="container px-4 py-5" >
            <h3 class="pb-2 border-bottom">
                {{ $volunteer->getName() }}
            </h3>
            <div class="d-flex">
                <div class="row">
                    <div class="col-md-4 px-2 py-2">
                        <div class="card border-0 rounded-4 shadow-lg">
                            <a href="{{ route('volunteer.show', ['volunteer' => $volunteer->getKey()]) }}" class="card">
                                <img src="{{ url($volunteer->getAvatar()) }}" class="bg-image-position-center"
                                     alt="{{ $volunteer->getName() }}">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8 px-2 py-2">
                        <div>
                            {!! $volunteer->getDescription() !!}
                        </div>
                    @include('layouts.links', compact('volunteer', 'withZvitLink'))
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection