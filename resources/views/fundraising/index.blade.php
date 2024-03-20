@extends('layouts.base')
@section('page_title', 'Всі збори та фонди - donater.com.ua')
@section('page_description', 'Вся звітність по Фондам та актуальним зборам коштів - donater.com.ua')
@php $withJarLink = true; @endphp
@php $withZvitLink = true; @endphp
@php $raffles = true; @endphp
@php $withOwner = true; @endphp
@php $additionalClasses = 'btn-sm'; @endphp
@php $additionalClassesColor = 'bg-light-subtle'; @endphp
@section('content')
    <div class="container px-4 py-5">
        <h2 class="pb-2">Всі збори</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($fundraisings->all() as $it => $fundraising)
                <div class="col">
                    <div class="card h-100 {{ $additionalClassesColor }}">
                        <a href="{{ route('fundraising.show', compact('fundraising')) }}">
                        <img src="{{ url($fundraising->getAvatar()) }}" class="card-img-top"
                             alt="{{ $fundraising->getName() }}"></a>
                        <div class="m-1 mt-3 {{ $additionalClassesColor }}">
                            <div class="text-center m-4">
                                <h3 class="mt-3">{{ $fundraising->getName() }}</h3>
                                <a class="btn btn-primary text-center" href="{{ route('fundraising.show', compact('fundraising')) }}">Подробиці</a>
                            </div>
                            @if($fundraising->isEnabled())
                                <h4 class="text-center">Збір триває, зробити донат</h4>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-animated-reverse active-right progress-bar-striped" role="progressbar"
                                         style="width: 100%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-around m-3">
                                    <a class="btn btn-dark btn-xs" target="_blank" href="{{ $fundraising->getJarLink(true, 100, 'With ❤️ to 🇺🇦') }}">🍩 100грн.</a>
                                    <a class="btn btn-dark btn-xs" target="_blank" href="{{ $fundraising->getJarLink(true, 500, 'With ❤️ to 🇺🇦') }}">🍩 500грн.</a>
                                    <a class="btn btn-dark btn-xs" target="_blank" href="{{ $fundraising->getJarLink(true, 1000, 'With ❤️ to 🇺🇦'), }}">🍩 1000грн.</a>
                                </div>
                            @elseif($fundraising->donates->count())
                                <h5 class="text-center">Збір закрито</h5>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 100%"
                                         aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            @else
                                <h5 class="text-center">Збір скоро розпочнеться</h5>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-animated progress-bar-striped bg-secondary" role="progressbar"
                                         style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer {{ $additionalClassesColor }}">
                            @include('layouts.fundraising_status_new', compact('fundraising'))
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="col-12">
        <div class="row">
            {{ $fundraisings->links('layouts.pagination', ['elements' => $fundraisings]) }}
        </div>
    </div>

    @auth()
        @include('subscribe.modal')
    @endauth
@endsection
