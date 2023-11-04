@extends('layouts.base')
@section('page_title', 'Часті питання по сайту donater.com.ua')
@section('page_description', 'Часті питання по сайту donater.com.ua')

@section('content')
    <div class="container px-4 py-5">
        <h2 class="pb-2 border-bottom">
            Довідка
        </h2>
        <div class="row">
            <div class="col-md-12 px-2 py-2 bg-body-secondary">
                <div class="accordion accordion-flush" id="accordionFAQ">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq-collapseOne" aria-expanded="false"
                                    aria-controls="faq-collapseOne">
                                Як відредагувати свій профіль?
                            </button>
                        </h2>
                        <div id="faq-collapseOne" class="accordion-collapse collapse" aria-labelledby="faq-headingOne"
                             data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Увійти на свою сторінку та натиснути на своє фото. Відкриється поп-ап, де ви можете
                                змінити фото, ім‘я, прізвище, посилання на профіль тощо.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq-collapseTwo" aria-expanded="false"
                                    aria-controls="faq-collapseTwo">
                                Що значать іконки біля імені користувачів?
                            </button>
                        </h2>
                        <div id="faq-collapseTwo" class="accordion-collapse collapse" aria-labelledby="faq-headingTwo"
                             data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                <ul>
                                    <li>
                                        <span title="Створені збори" class="badge bg-golden p-1">
                                            <i class="bi bi-telegram" title="Telegram Premium"
                                               style="color: #fff;"></i>
                                        </span> - користувач має Telegram Premium
                                    </li>
                                    <li>
                                        <span title="Створені збори" class="badge p-1 bg-info">33</span> - користувач
                                        створив 33 збори на сайті
                                    </li>
                                    <li>
                                        <span title="Завалідовані донати" class="badge p-1 bg-success">22</span> -
                                        користувач
                                        має 22 донати (які беруться з виписок волонтерів)
                                    </li>
                                    <li>
                                        <span title="Призи для зборів" class="badge p-1 bg-warning">11</span> - користувач
                                        додав 11 призів для розіграшів
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq-headingEnd">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq-collapseEnd" aria-expanded="false"
                                    aria-controls="faq-collapseEnd">
                                Як поставити питання?
                            </button>
                        </h2>
                        <div id="faq-collapseEnd" class="accordion-collapse collapse" aria-labelledby="faq-headingEnd"
                             data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Написати в телеграм @setnemo - якщо питання поширене, то я додам в цей розділ.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
