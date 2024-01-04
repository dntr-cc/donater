@extends('layouts.base')
@section('page_title', 'Всі благодійні внески - donater.com.ua')
@section('page_description', 'Стрічка благодійних внесків від користувачів donater.com.ua')
@php use App\Models\Donate; @endphp
@php /* @var Donate $donate */ @endphp
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="container m-1 mb-3">
                            <div class="row d-flex justify-content-between">
                                <div class="col-md-6 mt-1">
                                    <h4>Всі благодійні внески</h4>
                                </div>
                                {{--                                <div class="col-md-6 mt-1">--}}
                                {{--                                    <div class="form-floating">--}}
                                {{--                                        <input id="search" type="search" class="form-control mb-2" placeholder="Пошук коду">--}}
                                {{--                                        <label for="search">Пошук коду</label>--}}
                                {{--                                        <p class="mt-2 ms-1 lead" id="result">Знайдено: {{ $donates->count() }}</p>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                {{--                                <hr>--}}
                            </div>
                        </div>
                        @include('layouts.donates', compact('donates'))
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
        $('#search').on('change input', event => {
            event.preventDefault();
            let hash = $('#search').val() || '';
            let hashes = document.querySelectorAll('.hashes');
            let result = $('#result');
            if (hash.length === 0) {
                hashes.forEach(item => {
                    $(item).removeClass('hide');
                });
                result.text('Знайдено: :count'.replace(':count', hashes.length.toString()));
                return;
            }
            let showed = 0;
            hashes.forEach(item => {
                if ($(item).attr('data-hash').includes(hash)) {
                    $(item).removeClass('hide');
                    showed++;
                } else {
                    $(item).addClass('hide');
                }
            });
            result.text('Знайдено: :count'.replace(':count', showed.toString()));
        });
    </script>
@endsection
