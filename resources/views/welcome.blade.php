@extends('layouts.base')
@section('page_title', 'donater.com.ua - Донатити будуть всі')
@section('page_description', 'donater.com.ua - Донатити будуть всі. Телеграм бот для нагадувань донатів своїм волонтерам.')

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
                        <h4 class="my-0 fw-semibold text-center">ЯК ДОНАТЕР</h4>
                    </div>
                    <div class="card-body">
                        <ol class="mt-3 mb-4">
                            <li class="mt-4">
                                Відкриваєте сторінку з
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
                                Зробіть донат, якщо ви переходили по посиланню з бота - код донатера вже є в коментарях
                                (також моно дозволяє заповнити суму автоматично, якщо вона більше 100грн)
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card mb-4 rounded-3 shadow-sm border-primary">
                    <div class="card-header py-3 text-bg-warning border-warning-subtle">
                        <h4 class="my-0 fw-semibold text-center">ЯК ВОЛОНТЕР</h4>
                    </div>
                    <div class="card-body">
                        <ol class="mt-3 mb-4">
                            <li class="mt-4">
                                Додавайте свій збір
                            </li>
                            <li class="mt-4">
                                Очікуйте повідомлення в бота з запрощенням в чат волонтерів, де ви зможете знайти
                                підтримку та консультації. Шерити свій досвід закупок - важливо.
                            </li>
                            <li class="mt-4">
                                Прохайте своїх донатерів підписатися на вас. Кожна підписка буде повідомлятися в бота,
                                в налаштуваннях це можно відключити.
                            </li>
                            <li class="mt-4">
                                Очікуйте, підписок на вас, якщо ця людина має подарунки для зборів,
                                ви зможете додати іх собі на збір, щоб підпушити свою авдиторію (розіграш тільки серед
                                користувачів сайту)
                            </li>
                            <li class="mt-4">
                                Раз на пару днів оновлюйте виписку в гугл докс, текст запиту в підтримку моно буде
                                доступний по натисканню кнопки "ЗАПИТ В МОНО"
                            </li>
                            <li class="mt-4">
                                Очікуйте щодня донати від своїх серійних донатерів, коли їм буде приходити нагадування
                                в бота по підпісці.
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
