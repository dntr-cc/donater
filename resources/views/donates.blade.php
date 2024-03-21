@extends('layouts.base')
@section('page_title', 'Всі донати - donater.com.ua')
@section('page_description', 'Стрічка благодійних внесків від користувачів donater.com.ua')
@section('og_image', url('/images/index.png'))
@section('og_image_width', '1200')
@section('og_image_height', '470')
@section('og_image_alt', 'donater.com.ua - Донатити будуть всі. Телеграм бот для нагадувань з посиланням на банку: щоденно, раз на тиждень, місяць тощо.')
@php use App\Models\Donate; @endphp
@php /* @var Donate $donate */ @endphp
@php $donatesWithUser = true; @endphp
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="container m-1 mb-3">
                            <div class="row d-flex justify-content-between">
                                <div class="col-md-6 mt-1">
                                    <h4>Всі донати</h4>
                                </div>
                            </div>
                        </div>
                        @include('layouts.donates', compact('donates', 'donatesWithUser'))
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="row">
            <div class="">Якщо вам подобається бачити, як українці донатять - ви можете
                завантажити любий додаток з RSS та додати собі віджет на телефон. Посилання на
                RSS: <span id="rss" class="text-danger">{{ route('donates.rss') }}</span>
                <button id="copyRSS" class="btn btn-sm btn-outline-secondary"
                        onclick="return false;">
                    <i class="bi bi-copy"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="row">
            {{ $donates->links('layouts.pagination', ['elements' => $donates]) }}
        </div>
    </div>
    <script type="module">
        let copyRSS = $('#copyRSS');
        copyRSS.on('click', function (e) {
            e.preventDefault();
            copyContent($('#rss').text());
            return false;
        });
        toast('RSS посилання скопійовано', copyRSS);
    </script>
@endsection
