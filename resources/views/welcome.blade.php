@extends('layouts.base')
@section('page_title', 'donater.com.ua - Інтернет спільнота реальних людей, які донатять на Сили Оборони України.')
@section('page_description', 'donater.com.ua - Інтернет спільнота реальних людей, які донатять на Сили Оборони України.')

@section('content')
    <div class="container my-5">
        <div class="row row-cols-1 row-cols-md-3 mb-3">
            <div class="col">
                <div class="card mb-4 rounded-3 shadow-sm">
                    <div class="card-header py-3">
                        <h4 class="my-0 fw-semibold text-center border-secondary-subtle">АВТОРИЗУЙТЕСЬ</h4>
                    </div>
                    <div class="card-body">
                        <ol class="mt-3 mb-4">
                            <li class="mt-4">
                                Відкриваєте сорінку авторизації
                                <a href="https://donater.com.ua/login" target="_blank" class="">donater.com.ua/login</a>
                            </li>
                            <li class="mt-4">
                                <span class="flex">
                                    Копіюєте код, натиснувши на
                                    <button id="copyCode" class="me-4 btn btn-sm btn-outline-secondary"
                                            onclick="return false;">
                                        <i class="bi bi-copy"></i>
                                    </button>
                                </span>
                            </li>
                            <li class="mt-4">
                                <span class="flex">
                                    Відкриваєте телеграм бота натиснувши на кнопку<br>
                                    <a href="{{ config('telegram.bots.donater-bot.url') }}"
                                       class="col-md-12 btn btn-sm btn-outline-primary"
                                       target="_blank"><i class="bi bi-telegram"> Відкрити Телеграм</i></a>
                                </span>
                            </li>
                            <li class="mt-4">
                                Відправляєте телеграм боту повідомлення з кодом
                            </li>
                            <li class="mt-4">
                                Відкриваєте свою сторінку
                                <a href="https://donater.com.ua/my" target="_blank" class="">donater.com.ua/my</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card mb-4 rounded-3 shadow-sm">
                    <div class="card-header py-3 text-bg-success border-success-subtle">
                        <h4 class="my-0 fw-semibold text-center">ПІДПИШІТЬСЯ НА СВОГО ВОЛОНТЕРА</h4>
                    </div>
                    <div class="card-body">
                        <ol class="mt-3 mb-4">
                            <li class="mt-4">
                                Відкриваєте свою сторінку з
                                <a href="{{ route('volunteers') }}" target="_blank" class="">волонтерами</a>
                            </li>
                            <li class="mt-4">
                                Знайдіть свого волонтера
                            </li>
                            <li class="mt-4">
                                Натисніть на <button type="button" class="btn btn-outline-success">
                                    🍩 <i class="bi bi-currency-exchange"></i> Підписатися
                                </button>
                            </li>
                            <li class="mt-4">
                                Налаштуйте суму та час нагадування донату
                            </li>
                            <li class="mt-4">
                                Кожень день очікуйте в назначений час повідомлення в бота. Там буде ваш код донатера та посилання на
                                банку збору вашого волонтера
                            </li>
                            <li class="mt-4">
                                Зробіть донат, в коментарі додайте свій код донатера
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card mb-4 rounded-3 shadow-sm border-primary">
                    <div class="card-header py-3 text-bg-warning border-warning-subtle">
                        <h4 class="my-0 fw-semibold text-center">ОЧІКУЙТЕ ВАЛІДАЦІЮ</h4>
                    </div>
                    <div class="card-body">
                        <ol class="mt-3 mb-4">
                            <li class="mt-4">
                                Очікуєте поки волонтер закине виписку на Гугл Диск. Коли завалідується вам прийде повідомлення в
                                телеграм бота
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
