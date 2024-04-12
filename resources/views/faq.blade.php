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
                                Як запросити людину на платформу?
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
                        <h2 class="accordion-header" id="faq-headingWhatsHappensWithCSRF">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq-collapseWhatsHappensWithCSRF" aria-expanded="false"
                                    aria-controls="faq-collapseWhatsHappensWithCSRF">
                                Що таке помилка "CSRF token mismatch."?
                            </button>
                        </h2>
                        <div id="faq-collapseWhatsHappensWithCSRF" class="accordion-collapse collapse" aria-labelledby="faq-headingWhatsHappensWithCSRF"
                             data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                На сайті використовується CSRF token для кожного запиту на сервер. Він одноразовий, тому
                                інколи при лагах інтернету можливо що він не оновився. Також це можливо якщо повернутися
                                на сторінку кнопкою "назад", або якщо вкладка була відкрита дуже давно. Просто оновіть
                                сторінку та помилка пропаде.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq-headingWhichSettingsIsAvailableForDonater">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq-collapseWhichSettingsIsAvailableForDonater" aria-expanded="false"
                                    aria-controls="faq-collapseWhichSettingsIsAvailableForDonater">
                                Які налаштування мені доступні як донатеру/ці?
                            </button>
                        </h2>
                        <div id="faq-collapseWhichSettingsIsAvailableForDonater" class="accordion-collapse collapse" aria-labelledby="faq-headingWhichSettingsIsAvailableForDonater"
                             data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                <p>Налаштування доступні на вашій сторінці, під вашим аватаром є кнопка "НАЛАШТУВАННЯ"</p>
                                <ul>
                                    <li>
                                        Не брати участь в розіграшах: автоматично виключати вас при розіграші призів,
                                        які розігруються на сайті. За замовченням вимкнено.
                                    </li>
                                    <li>
                                        Показувати відсотки замість дробі в шансах розіграшів. За замовченням вимкнено.
                                    </li>
                                    <li>
                                        Не отримувати повідомлення маркетингових нагадувань чи розсилок. Не стосується
                                        важливих повідомлень від адміна, які ігнорують це налаштування. За замовченням
                                        вимкнено.
                                    </li>
                                    <li>
                                        Використовувати фемінітиви, коли описують мою роль (донатерка). За замовченням вимкнено.
                                    </li>
                                    <li>
                                        При відкритті профілів користувачів всі блоки окрім посилань будуть розгорнуті.
                                        За замовченням вимкнено.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq-headingWhichSettingsIsAvailableForVolunteer">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq-collapseWhichSettingsIsAvailableForVolunteer" aria-expanded="false"
                                    aria-controls="faq-collapseWhichSettingsIsAvailableForVolunteer">
                                Які налаштування мені доступні як волонтеру/ці?
                            </button>
                        </h2>
                        <div id="faq-collapseWhichSettingsIsAvailableForVolunteer" class="accordion-collapse collapse" aria-labelledby="faq-headingWhichSettingsIsAvailableForVolunteer"
                             data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                <p>Налаштування доступні на вашій сторінці, під вашим аватаром є кнопка "НАЛАШТУВАННЯ"</p>
                                <ul>
                                    <li>
                                        Не брати участь в розіграшах: автоматично виключати вас при розіграші призів,
                                        які розігруються на сайті. За замовченням вимкнено.
                                    </li>
                                    <li>
                                        Показувати відсотки замість дробі в шансах розіграшів. За замовченням вимкнено.
                                    </li>
                                    <li>
                                        Не отримувати повідомлення про додавання/видалення/зміни підписок ваших донатерів.
                                        За замовченням вимкнено.
                                    </li>
                                    <li>
                                        Використовувати фемінітиви, коли описують мою роль (донатерка). За замовченням вимкнено.
                                    </li>
                                    <li>
                                        При відкритті профілів користувачів всі блоки окрім посилань будуть розгорнуті.
                                        За замовченням вимкнено.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq-headingHowToWorkTrustScore">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq-collapseHowToWorkTrustScore" aria-expanded="false"
                                    aria-controls="faq-collapseHowToWorkTrustScore">
                                Як працює розрахунок "Рівень достовірності підписки"?
                            </button>
                        </h2>
                        <div id="faq-collapseHowToWorkTrustScore" class="accordion-collapse collapse" aria-labelledby="faq-headingHowToWorkTrustScore"
                             data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Дата відліку - 2024-03-27 09:59:59. Починаючи з цього часу сайт почав фіксувати донати по
                                підписці додатковим унікальним кодом. Середній час оновлення виписки на сайті становить
                                7-8 днів. Тому для розрахунку береться період з 2024-03-27 09:59:59 по поточну дату мінус
                                8 днів. Якщо в момент надсилання повідомлення нагадування донату волонтер/ка мав/ла відкритий
                                збір, то система очікує знайти унікальний код в виписці. Це буде працювати тільки в випадку
                                коли волонтери оновлюють виписку. Якщо волонтери цього не роблять - система з часом видаляє
                                збір. Цей функціонал зараз знаходиться на тестуванні, та з часом він буде більше точно
                                відображати відносини донатерів та волонтерів.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq-headingWhichSanctionWebsiteHasForVolunteer">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq-collapseWhichSanctionWebsiteHasForVolunteer" aria-expanded="false"
                                    aria-controls="faq-collapseWhichSanctionWebsiteHasForVolunteer">
                                Які санкції очікують волонтерів, якщо вони не закидають виписку?
                            </button>
                        </h2>
                        <div id="faq-collapseWhichSanctionWebsiteHasForVolunteer" class="accordion-collapse collapse" aria-labelledby="faq-headingWhichSanctionWebsiteHasForVolunteer"
                             data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Система перевіряє виписки щодня. Якщо дата крайнього донату (або час створення збору)
                                більше 7 днів - система повідомляє волонтерам що треба оновити виписку. Якщо дата
                                крайнього донату (або час створення збору) більше 10 днів - система видаляє збір. Як
                                тільки волонтер закине актуальну виписку - збір буде відновлено автоматично
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq-headingWhichSanctionWebsiteHasForBotBlockers">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq-collapseWhichSanctionWebsiteHasForBotBlockers" aria-expanded="false"
                                    aria-controls="faq-collapseWhichSanctionWebsiteHasForBotBlockers">
                                Які санкції очікують користувачів, які заблокували бота?
                            </button>
                        </h2>
                        <div id="faq-collapseWhichSanctionWebsiteHasForBotBlockers" class="accordion-collapse collapse" aria-labelledby="faq-headingWhichSanctionWebsiteHasForBotBlockers"
                             data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Якщо сайт спробує відправити повідомлення в бота користувачу, який заблокував бота -
                                система видалить користувача з сайту, а також всі збори та підписки, які було створено
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
