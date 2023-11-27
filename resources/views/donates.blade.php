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
                        <ul class="list-group list-group">
                            <div class="container m-1 mb-3">
                            <div class="row d-flex justify-content-between">
                                <div class="col-md-6 mt-1">
                                    <h4>Всі благодійні внески</h4>
                                    @if(auth()?->user()?->volunteers?->count())
                                        <a href="{{ route('donate', ['fixCode' => 1]) }}" class="btn">
                                            ДОДАТИ ЗАГУБЛЕНИЙ КОД
                                        </a>
                                    @endif
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
                            @foreach($donates as $it => $donate)
                                <li data-hash="{{ $donate->getUniqHash() }}" class="hashes list-group-item border-0 d-flex justify-content-between align-items-start">
                                    <a href="{{ route('volunteer.show', ['volunteer' => $donate->getVolunteer()]) }}"
                                       class="ms-2 me-auto text-decoration-none"
                                       style="color: rgba(var(--bs-body-color-rgb),var(--bs-text-opacity, 1))">
                                        @php $donater = $donate->donater()->first(); @endphp
                                        <div class="fw-bold">{{ $donater->getAtUsername() }} - {{ $donater->getFullName() }}</div>
                                        <div class="fw-bold">{{ $donate->getHumanType() }}</div>
                                        Код внеску {{ $donate->getUniqHash() }}.
                                        Створено {{ $donate->getCreatedAt()->format('Y-m-d H:i:s') }}.
                                        @if($donate->isValidated())
                                            Завалідовано {{ $donate->getValidatedAt()->format('Y-m-d H:i:s') }}.
                                        @endif
                                    </a>
                                    @if($donate->isValidated())
                                        <span class="badge text-bg-success ">
                                                    Завалідовано
                                                    <i class="bi bi-check-circle-fill text-bg-success"></i>
                                                </span>
                                    @else
                                        <span class="badge text-bg-secondary rounded-pill">
                                                    очікує на валідацію
                                                    <i class="bi bi-clock text-bg-secondary"></i>
                                                </span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="row">
            {{ $donates->links('layouts.pagination', ['elements' => $donates]) }}
        </div>
    </div>
    <script type="module">
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
