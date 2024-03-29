@php use App\Models\Donate; @endphp
@php use App\Models\Subscribe; @endphp
@php use App\Models\User; @endphp
@php use App\Models\UserLink; @endphp
@php use App\Models\UserSetting; @endphp
@php use App\Models\Fundraising; @endphp
@php /** @var User $user */ @endphp
@php /** @var Donate $donate */ @endphp
@php /** @var Fundraising $fundraising */ @endphp
@php /** @var UserLink $userLink */ @endphp
@php /** @var Subscribe $subscribe */ @endphp
@php $btn = true; @endphp
@php $withPrizeInfo = true; @endphp
@php $additionalClasses = 'btn-xs'; @endphp
@php $name = true; @endphp
@php $authUser = auth()?->user(); @endphp
@php $additionalAnalyticsText = ' по донатеру ' . $user->getUserLink(); @endphp
@php $title = strtr(':fullName (:username) - Донатер сайту donater.com.ua', [':fullName' => $user->getFullName(), ':username' => $user->getAtUsername()]); @endphp
@php $description = strtr(':fullName (:username) - Донатер сайту donater.com.ua', [':fullName' => $user->getFullName(), ':username' => $user->getAtUsername()]); @endphp
@php $userBanner = url(app(\App\Services\OpenGraphImageService::class)->getUserImage($user)) @endphp
@php $openedLargeBlocks = $authUser ? $authUser->settings->hasSetting(UserSetting::LARGE_BLOCKS_ARE_OPENED) : true; @endphp
@extends('layouts.base')
@section('page_title', $title)
@section('page_description', $description)
@section('og_image', $userBanner)
@section('og_image_width', '1200')
@section('og_image_height', '630')
@section('og_image_title', $title)
@section('og_image_alt', $description)
@section('breadcrumb-path')
    <li class="breadcrumb-item"><a href="{{ route('users') }}">Донатери</a></li>
    <li class="breadcrumb-item"><a href="{{ route('volunteers') }}">Волонтери</a></li>
@endsection
@section('breadcrumb-current', '@'. $user->getUsername())
@push('head-scripts')
    @vite(['resources/js/masonry.js'])
@endpush
@php $rowClasses = 'row-cols-3 g-0 d-flex justify-content-evenly align-items-center'; @endphp
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card mb-4"
                             onclick="return false;">
                            <div class="card-body text-center">
                                <img src="{{ $user->getAvatar() }}"
                                     @if (auth()?->user()?->can('update', $user))
                                         data-bs-toggle="modal" data-bs-target="#userEditModal"
                                     @endif
                                     alt="avatar"
                                     class="rounded-circle img-fluid" style="width: 150px;">
                                <h3 class="my-3">{{ $user->getFullName() }}</h3>
                                <h4 class="m-3 text-muted">{{ $user->getAtUsername() }}</h4>
                                <h6 class="m-3 text-muted">Дата реєстрації {{ $user->getCreatedAt() }}</h6>
                                @if($ref = \App\Models\Referral::query()->where('referral_id', '=', $user->getId())->get()->first())
                                    <p class="fs-6 1m-3 text-muted">по
                                        запрошенню {!! $ref->getInviter()->getUserHref() !!}</p>
                                @endif
                                <div class="d-flex justify-content-center mb-2">
                                    <div class="form-floating input-group">
                                        <input type="text" class="form-control" id="userLink"
                                               value="{{ $user->getUserLink() }}" disabled>
                                        <label for="userLink">Посилання на цю сторінку</label>
                                        <button class="btn btn-outline-secondary copy-text"
                                                data-message="Посилання"
                                                data-text="{{ $user->getUserLink() }}" onclick="return false;">
                                            <i class="bi bi-copy"></i></button>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center mb-2">
                                    <div class="form-floating input-group">
                                        <input type="text" class="form-control" id="userCode"
                                               value="{{ $user->getUserCode() }}" disabled>
                                        <label for="userCode">Код донатера</label>
                                        <button class="btn btn-outline-secondary copy-text"
                                                data-message="Код донатера"
                                                data-text="{{ $user->getUserCode() }}" onclick="return false;">
                                            <i class="bi bi-copy"></i></button>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-around align-items-center mb-2">
                                    @can('update', $user)
                                        <button class="btn btn-outline-dark {{ $additionalClasses }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#userEditSettingsModal">
                                            Налаштування
                                        </button>
                                    @endif
                                    @if($authUser && $user->fundraisings->count() > 0)
                                        @php $volunteer = $user; @endphp
                                        @include('subscribe.button', compact('volunteer', 'authUser'))
                                    @endif
                                </div>
                            </div>
                        </div>
                        {{--Links block--}}
                        <div class="card mt-4 mb-4 mb-lg-0">
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush rounded-3">
                                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                        <h4>Посилання для зв'язку</h4>
                                        <div>
                                            @if($user->getLinks()->count())
                                                <a href="#collapseLinks" data-bs-toggle="collapse" role="button"
                                                   aria-expanded="false"
                                                   aria-controls="collapseLinks" class="btn arrow-control"
                                                   data-state="up">
                                                    <i class="bi bi-arrow-up"></i>
                                                </a>
                                            @endif
                                            @can('update', $user)
                                                <button data-bs-toggle="modal" data-bs-target="#createLinkModal"
                                                        id="addDonation" class="btn">
                                                    <i class="bi bi-plus-circle-fill"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    </li>
                                    <div class="collapse show" id="collapseLinks">
                                        @foreach($user->getLinks()->all() as $userLink)
                                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                                <i class="{{ $userLink->getIcon() }} fa-lg"></i>
                                                <p class="mb-0">
                                                    <a href="{{ $userLink->getLink() }}">{{ $userLink->getName() }}</a>
                                                    <i data-id="{{ $userLink->getId() }}"
                                                       class="mx-2 bi bi-x-octagon text-danger delete-link">
                                                    </i>
                                                </p>
                                            </li>
                                        @endforeach
                                    </div>
                                </ul>
                            </div>
                        </div>
                        {{--Subscribes block--}}
                        @can('update', $user)
                            @if($user->getSubscribes()->count())
                                <div class="card mt-4 mb-4 mb-lg-0">
                                    <div class="card-body p-0">
                                        <ul class="list-group list-group-flush rounded-3">
                                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                                <h4>
                                                    Підписки на волонтерів
                                                </h4>
                                                <a href="#collapseSubscribes" data-bs-toggle="collapse" role="button"
                                                   aria-expanded="false"
                                                   aria-controls="collapseSubscribes" class="btn arrow-control"
                                                   data-state="{{ $openedLargeBlocks ? 'up' : 'down' }}">
                                                    <i class="bi bi-arrow-{{ $openedLargeBlocks ? 'up' : 'down' }}"></i>
                                                </a>
                                            </li>
                                            @if($user->getSubscribes()->count())
                                                <div class="collapse {{ $openedLargeBlocks ? 'show' : '' }}" id="collapseSubscribes">
                                                    @foreach($user->getSubscribes()->all() as $subscribe)
                                                        <div class="col-md-12">
                                                            @include('layouts.user_item', ['user' => $subscribe->getVolunteer(), 'whoIs' => \App\Http\Controllers\UserController::VOLUNTEERS])
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        @endcan
                        {{--Subscribers block--}}
                        @if($user->fundraisings->count() > 0 && !$user->getSubscribers()->isEmpty())
                            @php
                                $subscribers = $user->getSubscribers();
                            @endphp
                            <div class="card mt-4 mb-4 mb-lg-0">
                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush rounded-3">
                                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                            <h4>Підписані на {{ sensitive('волонтера', $user) }}</h4>
                                            @if($subscribers->count())
                                                <a href="#collapseSubscribers" data-bs-toggle="collapse" role="button"
                                                   aria-expanded="false"
                                                   aria-controls="collapseSubscribers" class="btn arrow-control"
                                                   data-state="{{ $openedLargeBlocks ? 'up' : 'down' }}">
                                                    <i class="bi bi-arrow-{{ $openedLargeBlocks ? 'up' : 'down' }}"></i>
                                                </a>
                                            @endif
                                        </li>
                                        @if($subscribers->count())
                                            <div class="collapse {{ $openedLargeBlocks ? 'show' : '' }}" id="collapseSubscribers">
                                                @foreach($subscribers as $subscriber)
                                                    <div class="col-md-12">
                                                        @include('layouts.user_item', ['user' => $subscriber->getDonater()])
                                                    </div>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        @endif
                        {{--Infobanner block--}}
                        <div class="card mt-4 mb-4 mb-lg-0">
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush rounded-3">
                                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                        <h4>Скачати інфографіку профілю</h4>
                                        <a href="{{ $userBanner }}" download="{{ $user->getUsername() }}.png"
                                           class="btn btn-outline-dark">
                                            <i class="bi bi-arrow-down"></i>
                                        </a>
                                    </li>
                                    <a href="{{ $userBanner }}" target="_blank"><img src="{{ $userBanner }}.small.png"
                                                                                     class="col-12"
                                                                                     alt="Інфографіка профілю {{ $user->getFullName() }}"></a>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        {{--Fundraisings block--}}
                        @if($user->getFundraisings()->count() || !(auth()?->user()?->can('update', $user)))
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 d-flex justify-content-between align-items-start">
                                            <h4>Всі збори</h4>
                                            <div>
                                                @if($user->getFundraisings()->count())
                                                    <a href="#collapseFundraisings" data-bs-toggle="collapse"
                                                       role="button"
                                                       aria-expanded="false"
                                                       aria-controls="collapseFundraisings" class="btn arrow-control"
                                                       data-state="{{ $openedLargeBlocks ? 'up' : 'down' }}">
                                                        <i class="bi bi-arrow-{{ $openedLargeBlocks ? 'up' : 'down' }}"></i>
                                                    </a>
                                                @endif
                                                @if (auth()?->user()?->getId() === $user->getId())
                                                    <a href="{{route('fundraising.new')}}" class="btn ">
                                                        <i class="bi bi-plus-circle-fill"></i>
                                                        Створити
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="collapse {{ $openedLargeBlocks ? 'show' : '' }}" id="collapseFundraisings">
                                        <hr>
                                        <div
                                            class="row row-cols-1 row-cols-xl-2 row-cols-lg-2 row-cols-md-2 row-cols-sm-1 g-4 masonry-grid">
                                            @foreach($user->getFundraisings()->all() as $it => $fundraising)
                                                @include('fundraising.item-card', compact('fundraising', 'rowClasses', 'name'))
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{--Donates block--}}
                        @php $userDonates = $user->getDonatesByVolunteer(); @endphp
                        @if($userDonates)
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 d-flex justify-content-between align-items-start">
                                            <h4>Донатить волонтерам</h4>
                                            <div>
                                                <a href="#collapseDonates" data-bs-toggle="collapse" role="button"
                                                   aria-expanded="false"
                                                   aria-controls="collapseDonates" class="btn arrow-control"
                                                   data-state="{{ $openedLargeBlocks ? 'up' : 'down' }}">
                                                    <i class="bi bi-arrow-{{ $openedLargeBlocks ? 'up' : 'down' }}"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="collapse {{ $openedLargeBlocks ? 'show' : '' }}" id="collapseDonates">
                                        <hr>
                                        <div
                                            class="row row-cols-1 row-cols-xl-2 row-cols-lg-2 row-cols-md-2 row-cols-sm-1 g-4 masonry-grid">
                                            @foreach($userDonates as $item)
                                                @include('item-donates', ['masonry' => 'masonry-grid-item', 'item' => $item, 'user' => User::find($item->volunteer_id)])
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{--Prizes block--}}
                        @php $allPrizes = $user->withPrizes()->prizes @endphp
                        @if($allPrizes->count() || !(auth()?->user()?->can('update', $user)))
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 d-flex justify-content-between align-items-start">
                                            <h4>Призи</h4>
                                            <div>
                                                @if($allPrizes->count())
                                                    <a href="#collapsePrizes" data-bs-toggle="collapse" role="button"
                                                       aria-expanded="false"
                                                       aria-controls="collapsePrizes" class="btn arrow-control"
                                                       data-state="{{ $openedLargeBlocks ? 'up' : 'down' }}">
                                                        <i class="bi bi-arrow-{{ $openedLargeBlocks ? 'up' : 'down' }}"></i>
                                                    </a>
                                                @endif
                                                @if (auth()?->user()?->getId() === $user->getId())
                                                    <a href="{{route('prize.new')}}" class="btn ">
                                                        <i class="bi bi-plus-circle-fill"></i>
                                                        Створити
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="collapse {{ $openedLargeBlocks ? 'show' : '' }}" id="collapsePrizes">
                                        <hr>
                                        <div
                                            class="row row-cols-1 row-cols-xl-2 row-cols-lg-2 row-cols-md-2 row-cols-sm-1 g-4 masonry-grid">
                                            @foreach($allPrizes as $prize)
                                                @include('prize.item-card', compact('prize', 'btn', 'withPrizeInfo'))
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{--Donater Analytics block--}}
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 d-flex justify-content-between align-items-start">
                                        <h4>Донатна Аналітика</h4>
                                        <div>
                                            <a href="#collapseDonateAnalytics" data-bs-toggle="collapse"
                                               role="button"
                                               aria-expanded="false"
                                               aria-controls="collapseDonateAnalytics" class="btn arrow-control"
                                               data-state="{{ $openedLargeBlocks ? 'up' : 'down' }}">
                                                <i class="bi bi-arrow-{{ $openedLargeBlocks ? 'up' : 'down' }}"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="collapse {{ $openedLargeBlocks ? 'show' : '' }}" id="collapseDonateAnalytics">
                                    <div class="row row-cols-1 g-1">
                                        @include('layouts.analytics', ['rows' => $donaterRows, 'charts' => $donaterCharts, 'charts2' => $donaterCharts2, 'charts3' => $donaterCharts3, 'uniq' => 'donateUniq', 'additionalAnalyticsText' => 'sasa',])
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--Volunteer Analytics block--}}
                        @if (auth()?->user()?->can('update', $user) && $user->getFundraisings()?->count())
                            @php $uniq = 'volunteerUniq' @endphp
                            <div class="card mb-1">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 d-flex justify-content-between align-items-start">
                                            <h4>Волонтерська Аналітика</h4>
                                            <div>
                                                <a href="#collapseFundAnalytics" data-bs-toggle="collapse" role="button"
                                                   aria-expanded="false"
                                                   aria-controls="collapseFundAnalytics" class="btn arrow-control"
                                                   data-state="{{ $openedLargeBlocks ? 'up' : 'down' }}">
                                                    <i class="bi bi-arrow-{{ $openedLargeBlocks ? 'up' : 'down' }}"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="collapse {{ $openedLargeBlocks ? 'show' : '' }}" id="collapseFundAnalytics">
                                        <div class="row row-cols-1 g-1">
                                            @include('layouts.analytics', compact('rows', 'charts', 'charts2', 'charts3', 'additionalAnalyticsText', 'uniq'))
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                    </div>
                    <div class="modal fade" id="welcomeVolunteerModal" tabindex="-1"
                         aria-labelledby="welcomeVolunteerModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="welcomeVolunteerModalLabel">Ласкаво просимо на
                                        сторінку донатера {{ '@' . $user->getUsername() }}</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>
                                <form class="form">
                                    <div class="modal-body">
                                        <p>
                                            donater.com.ua - платформа для волонтерів та донаторів, яка робить процес
                                            зборів більш прозорим, а накопичення коштів на потреби ЗСУ - стабільним.
                                        </p>
                                        <p>
                                            <strong>Коротко це той самий "лайк-підписка-дзвоник", де замість нового
                                                контенту вам
                                                приходить посилання на банку вашого волонтера, з обраною вами сумою та
                                                за
                                                вашим розкладом.</strong>
                                        </p>
                                        <p>
                                            <strong>Якщо подробніше</strong>, то головна функція платформи - підписка на
                                            волонтера з зазначенням прийнятної для донатора суми та розкладом
                                            нагадувань. Коли волонтер має відкритий збір, то донатори, що підписалися на
                                            нього, отримують нагадування з посиланням на банку зборів «свого» волонтера.
                                            Таким чином донатори можуть робити регулярні внески на актуальну банку
                                            волонтера. Що більше, нагадування вже має зашитий в посилання код донатора,
                                            що дає можливість побачити, від кого саме з донатерів поступив внесок. А
                                            це важливо, адже платформа надає волонтерам можливість заохочувати своїх
                                            донаторів призами, які розігруються тільки серед донаторів сайту. До речі,
                                            донатори теж можуть пропонувати свої призи для зборів. Від волонтера
                                            потрібно лише додати наявний приз до себе на збір.
                                        </p>
                                        <p>
                                            <strong>Та головне!</strong> Платформу створено для забезпечення регулярного
                                            надсилання донатів. Сума значення не має. Набагато простіше планувати
                                            виробництво FPV, якщо маєш 3000 грн в день від 1000 людей, які регулярно
                                            надсилають 3грн (ми дуже вдячні за донат в 3грн, приводіть друзів, ми хочемо
                                            зробити донатерами всіх, хто здатен користуватися інтернетом), чим очікувати
                                            на фінансування від поодинокого донатора, якій може задонатити (а може й ні)
                                            100к раз на місяць.
                                        </p>
                                        <p>
                                            Повний опис всього функціоналу в форматі треда твітера - <a
                                                href="https://x.com/setnemo/status/1749896475667026256?s=20"
                                                target="_blank">тут</a>. А якщо
                                            хочеться окремим лонгрідом - <a
                                                href="https://telegra.ph/donatercomua---podroboc%D1%96-proektu-01-23"
                                                target="_blank">тут</a>.
                                        </p>
                                        <p>
                                            Наразі сайт у відкритому бета-тесті. Плани розвитку сайту можна почитати в
                                            розділі <a href="{{ route('roadmap') }}" class="">Roadmap</a>.
                                        </p>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-secondary justify-content-evenly"
                                                data-bs-dismiss="modal">
                                            Закрити
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @can('update', $user)
            <div class="modal fade" id="createLinkModal" tabindex="-1" aria-labelledby="createLinkModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="createLinkModalLabel">Створити нове посилання</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form class="form">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <div class="form-floating py-1">
                                        <input type="text" class="form-control" maxlength="180" name="linkUrl"
                                               id="linkUrl"
                                               required>
                                        <label for="linkUrl">
                                            Посилання
                                        </label>
                                    </div>
                                    <div class="form-floating py-1">
                                        <input type="text" class="form-control" maxlength="20" name="linkName"
                                               id="linkName"
                                               required>
                                        <label for="linkName">
                                            Назва
                                        </label>
                                    </div>
                                    <div class="form-floating py-1 input-group">
                                <span class="input-group-text">
                                     <i id="icon-preview" class="bi bi-globe"></i>
                                </span>
                                        <select id="linkIcon" class="form-select" name="linkIcon">
                                            <option value="globe" selected>Оберіть іконку</option>
                                            @foreach(UserLink::ICONS as $style => $name )
                                                <option value="{{ $style }}">
                                                    <i class="{{ $style }}"></i>
                                                    {{ $name  }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-secondary justify-content-evenly"
                                        data-bs-dismiss="modal">
                                    Закрити
                                </button>
                                <button id="createLink" type="submit" class="btn btn-primary">Зберегти</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="userEditModal" tabindex="-1" aria-labelledby="userEditModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="userEditModalLabel">Змінити дані профілю</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form class="form">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-center">
                                <span class="position-relative">
                                    <img src="{{ $user->getAvatar() }}"
                                         alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                                    <label for="file" type="file"
                                           class="position-absolute top-80 start-80 translate-middle badge rounded-pill bg-danger">
                                        <i class="bi bi-pencil-fill font-large"></i>
                                    </label>
                                    <input id="file" type="file" style="display: none;" accept="image/*">
                                </span>
                                    </div>
                                    <div class="form-floating py-1">
                                        <input type="text" class="form-control" maxlength="50" name="username"
                                               id="username"
                                               value="{{ $user->getUsername() }}">
                                        <label for="username">
                                            Нікнейм
                                        </label>
                                    </div>
                                    <div class="form-floating py-1">
                                        <input type="text" class="form-control" maxlength="50" name="firstName"
                                               id="firstName"
                                               value="{{ $user->getFirstName() }}">
                                        <label for="firstName">
                                            Ім'я
                                        </label>
                                    </div>
                                    <div class="form-floating py-1">
                                        <input type="text" class="form-control" maxlength="50" name="lastName"
                                               id="lastName"
                                               value="{{ $user->getLastName() }}">
                                        <label for="lastName">
                                            Прізвище
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-secondary justify-content-evenly"
                                        data-bs-dismiss="modal">
                                    Закрити
                                </button>
                                <button id="userEdit" type="submit" class="btn btn-primary">Зберегти</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="userEditSettingsModal" tabindex="-1"
                 aria-labelledby="userEditSettingsModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="userEditSettingsModalLabel">Змінити налаштування</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form class="form">
                            <div class="modal-body">
                                @foreach(\App\Models\UserSetting::SETTINGS_MAP as $key => $name)
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" value="" id="{{ $key }}"
                                               @if($user->settings->hasSetting($key))
                                                   checked
                                            @endif
                                        >
                                        <label class="form-check-label" for="{{ $key }}">
                                            {{ $name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-secondary justify-content-evenly"
                                        data-bs-dismiss="modal">
                                    Закрити
                                </button>
                                <button id="userEditSettings" type="submit" class="btn btn-primary">Зберегти</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan
        @include('subscribe.modal')
        <script type="module">
            @if($dntr)
            let welcome = Modal.getOrCreateInstance(document.getElementById('welcomeVolunteerModal'));
            welcome.toggle('show');
            @endif
            @can('update', $user)
            $('#linkIcon').click(() => {
                $('#icon-preview').attr('class', $('#linkIcon option:selected').val());
            });
            $('#userEdit').on('click', event => {
                event.preventDefault();
                let formData = {
                    first_name: $('#firstName').val(),
                    last_name: $('#lastName').val(),
                };
                let newUsername = $('#username').val();
                if (newUsername !== '{{ $user->getUsername() }}') {
                    formData.username = newUsername;
                }
                $.ajax({
                    url: '{{ route('user.edit', compact('user')) }}',
                    type: "PATCH",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: data => {
                        window.location.assign(data.url ?? '{{ route('my') }}');
                    },
                    error: data => {
                        let empty = $("<a>");
                        toast(JSON.parse(data.responseText).message, empty, 'text-bg-danger');
                        empty.click();
                        $('meta[name="csrf-token"]').attr('content', data.csrf);
                    },
                });
                return false;
            });
            $('#createLink').on('click', event => {
                event.preventDefault();
                $.ajax({
                    url: '{{ route('user.link') }}',
                    type: "POST",
                    data: {
                        user_id: {{ $user->getId() }},
                        link: $('#linkUrl').val(),
                        name: $('#linkName').val(),
                        icon: $('#linkIcon option:selected').val(),
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: () => {
                        location.reload();
                    },
                    error: data => {
                        let empty = $("<a>");
                        toast(JSON.parse(data.responseText).message, empty, 'text-bg-danger');
                        empty.click();
                        $('meta[name="csrf-token"]').attr('content', data.csrf);
                    },
                });
                return false;
            });
            document.querySelector('#file').addEventListener('change', event => {
                event.preventDefault();
                let formData = new FormData();
                let $file = $('#file');
                let photo = $file.prop('files')[0];
                if (photo) {
                    formData.append('FILE', photo);
                }
                $.ajax({
                    url: '{{ route('user.edit.avatar', compact('user')) }}',
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: data => {
                        window.location.assign(data.url ?? '{{ route('my') }}');
                    },
                    error: data => {
                        let empty = $("<a>");
                        toast(JSON.parse(data.responseText).message, empty, 'text-bg-danger');
                        empty.click();
                        $('meta[name="csrf-token"]').attr('content', data.csrf);
                    },
                });
                return false;
            });
            $('.delete-link').on('click', event => {
                event.preventDefault();
                $.ajax({
                    url: '{{ route('user.link') }}' + '/' + $(event.target).attr('data-id'),
                    type: "DELETE",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function () {
                        location.reload();
                    },
                });
                return false;
            });
            $('#userEditSettings').on('click', event => {
                event.preventDefault();
                $.ajax({
                    url: '{{ route('user.settings', compact('user')) }}',
                    type: "PATCH",
                    data: {
                        settings: {
                            @foreach(\App\Models\UserSetting::SETTINGS_MAP as $key => $name)
                                {{ $key }}: $('#{{ $key }}').is(':checked') ? 1 : 0,
                            @endforeach
                        },
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: () => {
                        location.reload();
                    },
                    error: data => {
                        let empty = $("<a>");
                        toast(JSON.parse(data.responseText).message, empty, 'text-bg-danger');
                        empty.click();
                        $('meta[name="csrf-token"]').attr('content', data.csrf);
                    },
                });
                return false;
            });
            @endcan
        </script>
@endsection
