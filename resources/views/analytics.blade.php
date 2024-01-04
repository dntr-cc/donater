@extends('layouts.base')
@section('page_title', 'donater.com.ua - Інтернет спільнота реальних людей, які донатять на Сили Оборони України.')
@section('page_description', 'donater.com.ua - Інтернет спільнота реальних людей, які донатять на Сили Оборони України.')

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
