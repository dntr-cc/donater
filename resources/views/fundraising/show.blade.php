@extends('layouts.base')
@section('page_title', strtr('Звітність по :fundraising - donater.com.ua', [':fundraising' => $fundraising->getName()]))
@section('page_description', strtr('Звітність по :fundraising - donater.com.ua', [':fundraising' => $fundraising->getName()]))
@push('head-scripts')
    @vite(['resources/js/tabs.js'])
    @vite(['resources/js/chartjs.js'])
@endpush
@php $withJarLink = true; @endphp
@php $withPageLink = true; @endphp
@php $withOwner = true; @endphp
@php $withPrizes = true; @endphp
@php $disableShortCodes = false; @endphp
@section('content')
    <div class="container px-4 py-5">
        <h2 class="pb-2 border-bottom"><a href="{{ url()->previous() }}" class=""><i class="bi bi-arrow-left"></i></a>
            @include('layouts.fundraising_status', compact('fundraising', 'withOwner'))
        </h2>
        <div class="row">
            <div class="col-md-4 px-2 py-2">
                <div class="card border-0 rounded-4 shadow-lg">
                    <a href="{{ route('fundraising.show', ['fundraising' => $fundraising->getKey()]) }}" class="card">
                        <img src="{{ url($fundraising->getAvatar()) }}" class="bg-image-position-center"
                             alt="{{ $fundraising->getName() }}">
                    </a>
                </div>
                <div class="d-flex justify-content-center mb-2 px-2 py-2">
                    <div class="form-floating input-group">
                        <input type="text" class="form-control" id="shortLink"
                               value="{{ $fundraising->getShortLink() }}" disabled>
                        <label for="userShortLink">Коротке посилання</label>
                        <button id="copyShortLink" class="btn btn-outline-secondary" onclick="return false;">
                            <i class="bi bi-copy"></i></button>
                    </div>
                </div>
                    @if($fundraising->isEnabled())
                    <div class="mb-2 px-2 py-2 {{ $fundraising->getClassByState() }}">
                        @include('layouts.monodonat', compact('fundraising'))
                    </div>
                    @endif
                <div class="d-flex justify-content-center mb-2 px-2 py-2">
                    @include('layouts.fundraising_status_new', compact('fundraising'))
                </div>
            </div>
            <div class="col-md-8 px-2 py-2">
                <div>
                    {!! $fundraising->getDescription() !!}
                </div>
                @foreach($fundraising->getPrizes() as $pIt => $prize)
                    @foreach($prize->getWinners() as $wIt => $winner)
                        <div class="m-3 lead text-danger">
                            Приз #{{ $pIt + 1 }}, переможець #{{ $wIt + 1 }}
                            : {!! $winner->getWinner()->getUserHref() !!}
                        </div>
                    @endforeach
                @endforeach()
                <div class="mt-3"></div>
                @guest
                    <p class="lead"><a href="{{ route('login') }}" class="">Авторизуйтеся</a> за допомогою телеграму щоб
                        отримати код донатера</p>
                @else
                    <div class="d-flex justify-content-center mb-2 px-2 py-2">
                        <div class="form-floating input-group">
                            <input type="text" class="form-control" id="userCode"
                                   value="{{ auth()?->user()->getUserCode() }}" disabled>
                            <label for="userCode">Код донатера</label>
                            <button id="copyCode" class="btn btn-outline-secondary" onclick="return false;">
                                <i class="bi bi-copy"></i></button>
                        </div>
                    </div>
                @endguest
                @include('layouts.links', compact('fundraising', 'withJarLink', 'withPageLink', 'withPrizes', 'disableShortCodes'))
            </div>
        </div>
        <div id="preload" class="preload border border-dark border-1 border-light-subtle">
            <div class="d-flex justify-content-center ">
                <div class="d-flex justify-content-center m-5">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @auth()
        @include('subscribe.modal')
    @endauth
    <script type="module">
    @auth
        let copyCode = $('#copyCode');
        copyCode.on('click', event => {
            event.preventDefault();
            copyContent($('#userCode').val());
            return false;
        });
        toast('Код скопійовано', copyCode);
    @endauth
    window.addEventListener("load", function(){
        $.ajax({
            url: '{{ route('fundraising.preload', compact('fundraising')) }}',
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (data) => {
                $('#preload').removeClass('preload border border-dark border-1 border-light-subtle').html(data.html)
                $('meta[name="csrf-token"]').attr('content', data.csrf);
                window.initMDBTab();
                let DOMContentLoaded_event = document.createEvent("Event");
                DOMContentLoaded_event.initEvent("DOMContentLoaded", true, true);
                window.document.dispatchEvent(DOMContentLoaded_event);
            },
            error: data => {
                let empty = $("<a>");
                toast(JSON.parse(data.responseText).message, empty, 'text-bg-danger');
                empty.click();
                $('meta[name="csrf-token"]').attr('content', data.csrf);
            },
        });
    });
    </script>
@endsection
