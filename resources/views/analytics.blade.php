@extends('layouts.base')
@section('page_title', 'Аналітика по всім зборам на сайті donater.com.ua')
@section('page_description', 'Сума донатів в день, кількість донатів в розрізі сум донатів тощо')
@section('og_image', url('/images/donater.com.ua.png'))
@section('og_image_width', '1200')
@section('og_image_height', '630')
@section('og_image_title', 'Аналітика по всім зборам на сайті donater.com.ua')
@section('og_image_alt', 'Сума донатів в день, кількість донатів в розрізі сум донатів тощо')
@php $additionalAnalyticsText = ' (всі збори на сайті donater.com.ua)' @endphp

@section('content')
    <div class="container px-4 py-5">
        <h2 class="pb-2 border-bottom">
            Аналітика (всі збори на сайті)
        </h2>
        <div class="row">
            <div class="col-12">
                @include('layouts.analytics', compact('rows', 'charts', 'charts2', 'charts3'))
            </div>
        </div>
    </div>
@endsection
