@extends('layouts.base')
@section('page_title', 'Донатити будуть всі. Телеграм бот для нагадувань донатити своїм волонтерам')
@section('page_description', 'Сервіс дозволяє отримувати донатерам повідомлення в Телеграм з актуальним посиланням на банку вашого волонтера за обраним розкладом')
@section('og_image', url('/images/donater.com.ua.png'))
@section('og_image_width', '1200')
@section('og_image_height', '630')
@section('content')
    <div class="container my-5">
        <section class="jumbotron text-center">
            <div class="container">
                <h1 class="jumbotron-heading">Телеграм бот для нагадувань з посиланням на банку: щоденно, раз на
                    тиждень, місяць тощо</h1>
                @if(!auth()->user())
                    <p class="lead text-muted">Авторизуйтеся в телеграм-боті - це створить вам акаунт на сайті.</p>
                    <p>
                        <a id="enableBot" target="_blank"
                           href="{{ config('telegram.bots.donater-bot.url') }}?start={{ session()->get(\App\Http\Controllers\Auth\LoginController::LOGIN_HASH, '') }}"
                           class="btn btn-primary my-2">Підключити бота</a>
                    </p>
                @endif
                <p class="lead text-muted">
                    Якщо ви волонтер/ка - додавайте свій збір, додавайте "діп-лінки" для підписки на щоденний донат в
                    один клік. Наша команда хоче, щоб ви закривали свої збори на Сили Оборони стабільніше. Тому
                    долучайтеся до чату волонтерів після додавання першого збору.
                <p>
                <p class="lead text-muted">
                    Якщо ви донатер/ка - підписуйтеся на свого волонтера/ку (спитайте посилання на сторінку у волонтера/ки),
                    щоб задати розклад. Якщо вашого волонтера/ки нема на сайті, запросіть його/її/їх своїм кодом донатера,
                    код донатера є на вашій сторінці.
                <p>
                <p class="lead text-muted">
                    Незалежно від вашої ролі користування - ви отримаєте аналітику по витратах для донатерів, та
                    надходженням коштів для волонтерів. Наразі ця аналітика мінімальна, але з часом це стане вашим інструментом
                    фінансового планування.
                <p>
                    @auth
                        <a href="{{route('fundraising.new')}}" class="btn btn btn-outline-danger">
                            <i class="bi bi-plus-circle-fill"></i>
                            Додати збір
                        </a>
                        <a href="{{ route('my') }}" class="btn btn-outline-primary my-2">Ваша сторінка</a>
                    @endauth
                </p>
            </div>
        </section>
        <section>
            <div class="container">
                @include('layouts.welcome_text')
            </div>
        </section>
    </div>
@endsection
