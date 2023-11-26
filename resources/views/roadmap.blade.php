@php use App\Models\Volunteer; @endphp
@extends('layouts.base')
@section('page_title', 'Плани проекту donater.com.ua')
@section('page_description', 'donater.com.ua - Інтернет спільнота реальних людей, які донатять на Сили Оборони України.')

@section('content')
    <div class="container">
        <h2 class="mb-3">Розвиток ресурсу donater.com.ua</h2>
        <section class="pb-4">
            <div class="border rounded-5">
                <section class="w-100 p-5">
                    <section class="">
                        <ul class="timeline-with-icons">
                            <li class="timeline-item mb-5">
                                <span class="timeline-icon">
                                  <i class="bi bi-rocket-fill text-primary"></i>
                                </span>
                                <h5 class="fw-bold">Ідея ver.0.0.0</h5>
                                <p class="text-muted mb-2 fw-bold">16 листопада 2023</p>
                                <p class="text-muted">
                                    Все починалося з ідеї зробити сайт, який дозволить показати свої донати (не суми, а
                                    регулярність) і при цьому залишитись анонімом.
                                </p>
                            </li>
                            <li class="timeline-item mb-5">
                                <span class="timeline-icon">
                                  <i class="bi bi-gear-fill text-primary"></i>
                                </span>
                                <h5 class="fw-bold">MVP ідеї, ver.0.0.1</h5>
                                <p class="text-muted mb-2 fw-bold">18 листопада 2023</p>
                                <p class="text-muted">
                                    Для перевірки ідеї було створено цей сайт. Щоб почати тестувати реалізовано:
                                </p>
                                <ol>
                                    <li>Авторизація через телеграм бот по коду</li>
                                    <li>Атоматичне створення аккаунту з інформації яку надав телеграм</li>
                                    <li>Створення благодійний внесків з кодами, які треба писати в коментарі до донату
                                    </li>
                                    <li>Автоматична перевірка гугл таблиці з випискою, при наявності кода - валідувати
                                    </li>
                                    <li>Показувати посилання на банку та виписку в розділі звіти</li>
                                </ol>
                            </li>
                            <li class="timeline-item mb-5">
                                <span class="timeline-icon">
                                  <i class="bi bi-gear-wide-connected text-primary"></i>
                                </span>
                                <h5 class="fw-bold">MVP ідеї, ver.0.0.2</h5>
                                <p class="text-muted mb-2 fw-bold">20 листопада 2023</p>
                                <p class="text-muted">
                                    Для більш зручного користування додано:
                                </p>
                                <ol>
                                    <li>Редагування профілю, зміна фото яке стягнуло з телеграм</li>
                                    <li>Покрокова інструкція для знайомства з функціоналом</li>
                                    <li>Виправлені помилки логіну, знайдені багі</li>
                                    <li>Додавання посилань на інші платформи (і видаленння)</li>
                                    <li>Створення цього роадмапу</li>
                                </ol>
                            </li>
                            <li class="timeline-item mb-5">
                                <span class="timeline-icon">
                                  <i class="bi bi-star-fill text-primary"></i>
                                </span>
                                <h5 class="fw-bold">MVP ідеї, ver.0.0.3</h5>
                                <p class="text-muted mb-2 fw-bold">26 листопада 2023</p>
                                <p class="text-muted">
                                    Велике оновлення волонтерського функціоналу:
                                </p>
                                <ul>
                                    <li>Додавання нових зборів чи Фондів</li>
                                    <li>Редагування вже створених зборів</li>
                                    <li>Зупинити чи розпочати збір (виключає чи включає можливість створити благодійний внесок до збору)</li>
                                    <li>Сортування зборів по кількості донатів на збір</li>
                                </ul>
                            </li>
                            <li class="timeline-item mb-5">
                                <span class="timeline-icon">
                                  <i class="bi bi-stars text-primary"></i>
                                </span>
                                <h5 class="fw-bold">MVP ідеї, ver.0.0.4</h5>
                                <p class="text-muted mb-2 fw-bold">десь в майбутньому</p>
                                <p class="text-muted">
                                    Планування майбутнього розвитку:
                                </p>
                                <ul>
                                    <li>Робити розіграши серед користувачів (буде багато)</li>
                                    <li>Волонтерський функціонал:</li>
                                    <ul>
                                        <li>Додавати документи до транзакцій з мінусом в виписці (доступ до цих
                                            документів буде мати тільки ті, хто донатив на збір, мають завалідований
                                            внесок)
                                        </li>
                                        <li>Налаштування персонального повідомлення в момент створення внеску, чи після
                                            валідації
                                        </li>
                                        <li>Дати можливість волонтеру писати своїм донатерам раз на добу повідомлення в
                                            бота
                                        </li>
                                        <li>Зробити можливість провесті розіграш серед користувачів натиснув дві
                                            кнопки
                                        </li>
                                        <li>Поступово прийти до ситуації коли ми маємо закритий збір - виписку бачуть
                                            всі,
                                            звіт по чекам який прив'язано до мінус транзакцій тількі ті, хто донатив.
                                            Волонтери можуть видалити весь цей шлак з телефону. Хочешь бачити всі звіти
                                            - донать. Без донату тільки виписка в тому форматі, як вона є зараз - сума
                                            донату та аккаунт який знайшовся по коду коментаря
                                        </li>
                                    </ul>
                                    <li>Налаштування звичайних користувачів:</li>
                                    <ul>
                                        <li>Додати можливість підписатися на волонтера - автоматично отримувати
                                            повідомлення в бота якщо створено новий збір
                                        </li>
                                        <li>Додати функціонал який дозволить присилати збори на поповнення. Крітерії
                                            вибору треба розробити. Має виглядати як "я можу донатити 3 грн кожен день -
                                            присилайте посилання в бота, одразу з кодом валідації"
                                        </li>
                                        <li>Розробити модель оцінки "серійний донатер". Регулярні донати це те, куди
                                            треба рухатися, сума не важлива. Такі донатери будуть давати рейтинг
                                            волонтерам через збори на які вони донатять. Таким чином будемо бачити
                                            рейтинг волонтерів. Більше серійних донатерів - вище рейтинг волонтера.
                                            Більше шансів що інші користувачі долучаться до зборів цього волонтеру
                                        </li>
                                    </ul>
                                    <li>Загальний функціонал:</li>
                                    <ul>
                                        <li>
                                            При наявності активної аудиторії - прогнозування збору. Якщо є користувачі
                                            хто вже заповнив скільки тягне донатів, то це дозволить волонтеру бачити
                                            прогноз часу скільки треба щоб закрити збір. Це дозволить більш точно
                                            планувати збори та витрати на розхідники (авто, фпв, дрони)
                                        </li>
                                        <li>
                                            Максимально винести все в бота. Щоб на сайт ходили тільки по звіти,
                                            документи по звіту тощо.
                                        </li>
                                    </ul>
                                </ul>
                            </li>
                            <p class="text-muted lead">
                                ОСНОВНА МЕТА: зробити життя волонтерів аматорів простішим, дати ім інструмент який
                                дозволить якісніше взаємодіяти з донатерами, ділитися детальними звітами тільки з
                                донатерами збору, донатери мають планування донатів, працює планування закриття зборів
                                на базі виставлених донатерами планів.
                            </p>
                        </ul>
                    </section>
                </section>
            </div>
        </section>
    </div>
@endsection
