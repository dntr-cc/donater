@extends('layouts.base')
@php [$ogImageWidth, $ogImageHeight] = getimagesize(config('app.env') === 'local' ? public_path($fundraising->getAvatar()) : url($fundraising->getAvatar())); @endphp
@section('page_title', strtr('Звітність по :fundraising - donater.com.ua', [':fundraising' => $fundraising->getName()]))
@section('page_description', strtr('Звітність по :fundraising - donater.com.ua', [':fundraising' => $fundraising->getName()]))
@section('og_image', url($fundraising->getAvatar()))
@section('og_image_width', $ogImageWidth)
@section('og_image_height', $ogImageHeight)
@section('og_image_alt', $fundraising->getName())
@push('head-scripts')
    @vite(['resources/js/tabs.js'])
    @vite(['resources/js/chartjs.js'])
@endpush
@php $withJarLink = true; @endphp
@php $withPageLink = true; @endphp
@php $withOwner = true; @endphp
@php $withPrizes = true; @endphp
@php $disableShortCodes = false; @endphp
@php $additionalClasses = 'btn-xs'; @endphp
@section('content')
    <div class="container px-4 py-5">
        <h2 class="pb-2 border-bottom"><a href="{{ url()->previous() }}" class=""><i class="bi bi-arrow-left"></i></a>
            @include('layouts.fundraising_status', compact('fundraising', 'withOwner', 'additionalClasses'))
        </h2>
        <div class="row">
            <div class="col-md-4 px-2 py-2">
                @include('fundraising.item-card', compact('fundraising'))
            </div>
            <div class="col-md-8 px-2 py-2">
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
                        @include('layouts.links', compact('fundraising', 'withJarLink', 'withPageLink', 'withPrizes', 'disableShortCodes'))
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
