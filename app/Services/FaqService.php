<?php

namespace App\Services;

class FaqService
{
    public const array FAQ = [
        'HowToInviteSomeone' => [
            'question' => 'Як запросити людину на платформу?',
            'answer'   => 'Увійти на свою сторінку та скопіюйте код донатера. Попросить друга чи подругу відкрити цей код в браузері (не вбудованому браузері месенджера як це буває) та зареєструватися.',
        ],
        'HowToEditProfile' => [
            'question' => 'Як відредагувати свій профіль?',
            'answer'   => 'Увійти на свою сторінку та натиснути на своє фото. Відкриється поп-ап, де ви можете змінити фото, ім‘я, прізвище, посилання на профіль тощо.',
        ],
        'WhenMyDonatesAddedToWebsite' => [
            'question' => 'Коли я побачу свої донати на сайті?',
            'answer'   => 'Як тільки волонтери, яким ви робили донати, додатьу свіжу виписку на сайт. Зазвичай це раз на 7-8 днів',
        ],
        'WhatsHappensWithCSRF' => [
            'question' => 'Що таке помилка "CSRF token mismatch."?',
            'answer'   => 'На сайті використовується CSRF token для кожного запиту на сервер. Він одноразовий, тому інколи при лагах інтернету можливо що він не оновився. Також це можливо якщо повернутися на сторінку кнопкою "назад", або якщо вкладка була відкрита дуже давно. Просто оновіть сторінку та помилка пропаде.',
        ],
        'WhichSettingsIsAvailableForDonater' => [
            'question' => 'Які налаштування мені доступні як донатеру/ці?',
            'answer'   => <<<'HTML'
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
HTML
,
        ],
        'WhichSettingsIsAvailableForVolunteer' => [
            'question' => 'Які налаштування мені доступні як волонтеру/ці?',
            'answer'   => <<<'HTML'
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
HTML
,
        ],
        'HowToWorkTrustScore' => [
            'question' => 'Як працює розрахунок "Рівень достовірності підписки"?',
            'answer'   => 'Дата відліку - 2024-03-27 09:59:59. Починаючи з цього часу сайт почав фіксувати донати по підписці додатковим унікальним кодом. Середній час оновлення виписки на сайті становить 7-8 днів. Тому для розрахунку береться період з 2024-03-27 09:59:59 по поточну дату мінус 8 днів. Якщо в момент надсилання повідомлення нагадування донату волонтер/ка мав/ла відкритий збір, то система очікує знайти унікальний код в виписці. Це буде працювати тільки в випадку коли волонтери оновлюють виписку. Якщо волонтери цього не роблять - система з часом видаляє збір. Цей функціонал зараз знаходиться на тестуванні, та з часом він буде більше точно відображати відносини донатерів та волонтерів.',
        ],
        'WhichSanctionWebsiteHasForVolunteer' => [
            'question' => 'Які санкції очікують волонтерів, якщо вони не закидають виписку?',
            'answer'   => 'Система перевіряє виписки щодня. Якщо дата крайнього донату (або час створення збору) більше 7 днів - система повідомляє волонтерам що треба оновити виписку. Якщо дата крайнього донату (або час створення збору) більше 10 днів - система видаляє збір. Як тільки волонтер закине актуальну виписку - збір буде відновлено автоматично',
        ],
        'WhichSanctionWebsiteHasForBotBlockers' => [
            'question' => 'Які санкції очікують користувачів, які заблокували бота?',
            'answer'   => 'Якщо сайт спробує відправити повідомлення в бота користувачу, який заблокував бота - система видалить користувача з сайту, а також всі збори та підписки, які було створено',
        ],
        'HowToAsk' => [
            'question' => 'Як поставити питання?',
            'answer'   => 'Написати в телеграм @setnemo - якщо питання поширене, то я додам в цей розділ.',
        ],
    ];
}
