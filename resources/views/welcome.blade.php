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
                    <p class="lead text-muted">Авторизуйтеся в телеграм-боті - це створить вам аккаунт на сайті.</p>
                    <p>
                        <a id="enableBot" target="_blank"
                           href="{{ config('telegram.bots.donater-bot.url') }}?start={{ session()->get(\App\Http\Controllers\Auth\LoginController::LOGIN_HASH, '') }}"
                           class="btn btn-primary my-2">Підключити бота</a>
                    </p>
                @endif
                <p class="lead text-muted">Підписуйтеся на свого волонтера, щоб задати розклад. Якщо вашого волонтера
                    нема на сайті, запросіть його своім кодом донатера, код донатера є на вашій сторінці</p>
                <p>
                    <a href="{{ route('volunteers') }}" class="btn btn-outline-success my-2">Обрати волонтера</a>
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
        <h2 class="fw-semibold text-center border-secondary-subtle m-3">Покрокова інструкція</h2>
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
                                    Відкриваєте телеграм бота натиснувши на кнопку "Відкрити Телеграм"
                                </span>
                            </li>
                            <li class="mt-4">
                                Вас авторизує на сайті автоматично.
                            </li>
                            <li class="mt-4">
                                <span class="flex">
                                    Якщо ви робите це з різних пристроїв - використовуйте код логіну,
                                    його треба відправити телеграм боту для авторизації на сайті
                                </span>
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
                                Натисніть на
                                <button type="button" class="btn btn-outline-success">
                                    🍩 <i class="bi bi-currency-exchange"></i> Підписатися
                                </button>
                            </li>
                            <li class="mt-4">
                                Налаштуйте суму та час нагадування донату
                            </li>
                            <li class="mt-4">
                                Кожень день очікуйте в назначений час повідомлення в бота. Там буде ваш код донатера та
                                посилання на
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
                                донатерів сайту)
                            </li>
                            <li class="mt-4">
                                Періодично оновлюйте виписку в гугл докс, текст запиту в підтримку моно буде
                                доступний по натисканню кнопки "ЗАПИТ В МОНО". Відштовхуйтеся від кількості донатів з
                                кодами донатера, але раз 14 днів виписку треба закидати обов'язково.
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
        <div>
            <p class="lead">
            <p>
                donater.com.ua - платформа для волонтерів та донаторів, яка робить процес
                зборів більш прозорим, а накопичення коштів на потреби ЗСУ - стабільним.
            </p>
            <p>
                <strong>Коротко це той самий "лайк-підписка-дзвоник", де замість нового контенту вам
                    приходить посилання на банку вашого волонтера, з обраною вами сумою та за
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
            </p>
        </div>
    </div>
@endsection
