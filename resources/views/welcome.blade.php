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
                        <h4 class="my-0 fw-semibold text-center">ЗРОБИТЬ ДОНАТ</h4>
                    </div>
                    <div class="card-body">
                        <ol class="mt-3 mb-4">
                            <li class="mt-4">
                                <span class="flex">
                                    Поруч з написом <b>Благодійні внески</b> натискаєте на кнопку
                                    <button class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-plus-circle-fill"></i>
                                    </button>
                                </span>
                            </li>
                            <li class="mt-4">
                                <span class="flex">
                                    Копіюєте код, натиснувши на кнопку
                                    <button id="copyCode" class="me-4 btn btn-sm btn-outline-secondary"
                                            onclick="return false;">
                                        <i class="bi bi-copy"></i>
                                    </button>
                                </span>
                            </li>
                            <li class="mt-4">
                                Обираєте збір
                            </li>
                            <li class="mt-4">
                                Відкриваєте банку по посиланню (відкриється в новій вкладці)
                            </li>
                            <li class="mt-4">
                                В коментар додаєте код, поповнюєте банку
                            </li>
                            <li class="mt-4">
                                <span class="flex">
                                    Повертаєтесь во вкладку с сайт, натискаете кнопку
                                    <button id="createDonate" type="button" class="btn btn-sm btn-primary">Зберігти</button>
                                </span>
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
                                телеграм бота, а бейдж
                                <span class="badge text-bg-secondary rounded-pill">
                                    очікує на валідацію
                                    <i class="bi bi-clock text-bg-secondary"></i>
                                </span>
                                заміниться на
                                <span class="badge text-bg-success ">
                                    Завалідовано
                                    <i class="bi bi-check-circle-fill text-bg-success"></i>
                                </span>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
