@extends('layouts.base')
@php $title = strtr('Збір :fundraising. Збирає :volunteer', [':fundraising' => $fundraising->getName(), ':volunteer' => $fundraising->getVolunteer()->getUsernameWithFullName()]); @endphp
@php $fundraisingBanner = url(app(\App\Services\OpenGraphImageService::class)->getFundraisingImage($fundraising)) @endphp
@section('page_title', $title)
@section('page_description', strtr('Звітність по :fundraising - donater.com.ua', [':fundraising' => $fundraising->getName()]))
@section('og_image_title', $title)
@section('og_image_alt', 'Створить нагадування задонатити на збір ' . $fundraising->getName() . ' на сайті donater.com.ua')
@section('og_image', url($fundraisingBanner))
@push('head-scripts')
    @vite(['resources/js/tabs.js'])
@endpush
@php $withOwner = true; @endphp
@php $additionalClasses = 'btn-xs'; @endphp
@php $withVolunteer = true; @endphp
@php $fundraisingBanner = url(app(\App\Services\OpenGraphImageService::class)->getFundraisingImage($fundraising)) @endphp
@section('content')
    <div class="container px-4 py-5">
        <h2 class="pb-2 border-bottom"><a href="{{ url()->previous() }}" class=""><i class="bi bi-arrow-left"></i></a>
            @include('layouts.fundraising_status', compact('fundraising', 'withOwner', 'additionalClasses'))
        </h2>
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-12 px-2 py-2">
                @include('fundraising.item-card', compact('fundraising', 'withVolunteer'))
                <div class="card mt-4 mb-4 mb-lg-0">
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush rounded-3">
                            <li class="list-group-item p-3">
                                <h4>Скачати банер збору</h4>
                            </li>
                            <a href="{{ $fundraisingBanner }}" download="{{ $fundraising->getKey() }}.png"><img
                                    src="{{ $fundraisingBanner }}" class="col-12"></a>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-8 col-md-12 px-2 py-2">
                <div class="card mb-2">
                    <div class="card-body">
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
                            <p class="lead"><a href="{{ route('login') }}" class="">Авторизуйтеся</a> за допомогою
                                телеграму щоб
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
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
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
                window.addEventListener("load", function () {
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
                            $('#preload script').each((i, s) => {
                                document.head.appendChild(s);
                            });
                            let event1 = document.createEvent("Event");
                            event1.initEvent("DOMContentLoaded", true, true);
                            window.document.dispatchEvent(event1);

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
