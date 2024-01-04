@extends('layouts.base')
@section('page_title', 'Аналітика по всім зборам на сайті donater.com.ua')
@section('page_description', 'Аналітика по всім зборам на сайті donater.com.ua. Сума донатів в день, кількість донатів в розрізі сум донатів тощо')

@section('content')
    <div class="container px-4 py-5">
        <h2 class="pb-2 border-bottom">
            Аналітика (всі збори на сайті)
        </h2>
        <div class="row">
            <div class="col-md-12 px-2 py-2">
                @include('layouts.analytics', compact('rows', 'charts', 'charts2', 'charts3'))
            </div>
        </div>
    </div>
@endsection
