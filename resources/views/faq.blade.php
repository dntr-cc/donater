@extends('layouts.base')
@section('page_title', 'Часті питання по сайту donater.com.ua')
@section('page_description', 'Часті питання по сайту donater.com.ua')

@section('content')
    <div class="container">
        <h2 class="pb-2 border-bottom">
            Довідка
        </h2>
        <div class="row">
            <div class="col-md-12 px-2 py-2 bg-body-secondary">
                <div class="accordion accordion-flush" id="accordionFAQ">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq-headingHowToInviteSomeone">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq-collapseHowToInviteSomeone" aria-expanded="false"
                                    aria-controls="faq-collapseHowToInviteSomeone">
                                Як запросити донатера?
                            </button>
                        </h2>
                        <div id="faq-collapseHowToInviteSomeone" class="accordion-collapse collapse" aria-labelledby="faq-headingHowToInviteSomeone"
                             data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Увійти на свою сторінку та скопіюйте код донатера. Попросить друга чи подругу відкрити
                                цей код в браузері (не вбудованому браузері месенджера як це буває) та зареєструватися.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq-headingHowToEditProfile">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq-collapseHowToEditProfile" aria-expanded="false"
                                    aria-controls="faq-collapseHowToEditProfile">
                                Як відредагувати свій профіль?
                            </button>
                        </h2>
                        <div id="faq-collapseHowToEditProfile" class="accordion-collapse collapse" aria-labelledby="faq-headingHowToEditProfile"
                             data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Увійти на свою сторінку та натиснути на своє фото. Відкриється поп-ап, де ви можете
                                змінити фото, ім‘я, прізвище, посилання на профіль тощо.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq-headingIconsMeaning">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq-collapseIconsMeaning" aria-expanded="false"
                                    aria-controls="faq-collapseIconsMeaning">
                                Що значать іконки біля імені донатерів?
                            </button>
                        </h2>
                        <div id="faq-collapseIconsMeaning" class="accordion-collapse collapse" aria-labelledby="faq-headingIconsMeaning"
                             data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                <ul>
                                    <li>
                                        <span title="Створені збори" class="badge bg-golden p-1">
                                            <i class="bi bi-telegram" title="Telegram Premium"
                                               style="color: #fff;"></i>
                                        </span> - Донатер має Telegram Premium
                                    </li>
                                    <li>
                                        <span title="Створені збори" class="badge p-1 bg-info">33</span> - Донатер
                                        створив 33 збори на сайті
                                    </li>
                                    <li>
                                        <span title="Завалідовані донати" class="badge p-1 bg-success">22</span> -
                                        Донатер
                                        має 22 донати (які беруться з виписок волонтерів)
                                    </li>
                                    <li>
                                        <span title="Призи для зборів" class="badge p-1 bg-warning">11</span> - Донатер
                                        додав 11 призів для розіграшів
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq-headingHowToAsk">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq-collapseHowToAsk" aria-expanded="false"
                                    aria-controls="faq-collapseHowToAsk">
                                Як поставити питання?
                            </button>
                        </h2>
                        <div id="faq-collapseHowToAsk" class="accordion-collapse collapse" aria-labelledby="faq-headingHowToAsk"
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
