@extends('layouts.base')
@section('page_title', 'Всі збори з розіграшами - donater.com.ua')
@section('page_description', 'Всі збори з розіграшами - donater.com.ua')

@section('content')
    <div class="container px-4 py-5" >
        <h2 class="pb-2 border-bottom">
            Всі збори з розіграшами та призами
        </h2>
        <p class="lead">
            Для участі в розіграші треба зробити донат з кодом. Код ви можете знайти натиснувши
            <a href="{{route('donate')}}" class="btn btn-sm btn-outline-success">
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
                    <span
                        class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg bg-image-position-center"
                        style="background-image: url('{{ url($volunteer->getAvatar()) }}');">
                        <div class="d-flex flex-column min-vh-25 h-100 p-4 pb-3 text-shadow-1">
                        </div>
                    </span>
                    </div>
                    <div class="col-md-8 px-2 py-2">
                        <p class="lead">
                            {!! $volunteer->getDescription() !!}
                        </p>
                        <a href="{{ $volunteer->getLink() }}" target="_blank" class="btn btn-outline-success">Банка</a>
                        <a href="{{ $volunteer->getPage() }}" target="_blank" class="btn btn-outline-success">Сторінка
                            збору</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
