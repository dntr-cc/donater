@php use App\Models\FundraisingDetail; @endphp
@php use App\Models\Fundraising; @endphp
@php /** @var Fundraising $fundraising */ @endphp
@php $fundraising = $fundraising ?? null @endphp
@php $actionId = $actionId ?? 'createFundraising' @endphp
@php $btnText = $btnText ?? 'Створити' @endphp
<form id="form-{{ $actionId }}">
    <div class="p-3 pt-0">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="d-flex justify-content-center">
                    <span class="position-relative">
                        <div class="card border-0 rounded-4 shadow-lg">
                            <img id="avatarImage" src="{{ url($fundraising?->getAvatar() ?? '/images/banners/ava-fund-default.png') }}"
                                 class="bg-image-position-center"
                                 alt="avatar">
                        </div>
                        <label for="file" type="file"
                               class="position-absolute top-100 start-100 translate-middle badge rounded-pill bg-danger">
                            <i class="bi bi-pencil-fill font-large"></i>
                        </label>
                        <input id="avatar" type="text" style="display: none;" aria-label="Баннер"
                               value="{{ $fundraising?->getAvatar() ?? '/images/banners/ava-fund-default.png' }}">
                        <input id="file" type="file" style="display: none;" accept="image/*">
                    </span>
                </div>
                <div class="text-center">
                    <p class="m-3 text-muted lh-1 fs-6">
                        Якщо вам не подобається ваш аватар збору -
                        ви можете згенерувати свій за допомогою
                        <a href="https://rewlogan.com/cover" target="_blank">rewlogan/cover</a>
                    </p>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="row">
                    <div class="col-12">
                        <h4>Обов'язкові поля:</h4>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="name"
                                   value="{{ $fundraising?->getName() ?? '' }}" required maxlength="60">
                            <label for="name">
                                Назва (до 60 символів)
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="key"
                                   value="{{ $fundraising?->getKey() ?? '' }}" required>
                            <label for="key">
                                Унікальний префікс для посилання
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="link"
                                   value="{{ $fundraising?->getJarLink(false) ?? '' }}" required>
                            <label for="link">
                                Посилання на монобанку
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="page"
                                   value="{{ $fundraising?->getPage() ?? '' }}" required>
                            <label for="page">
                                Посилання на сторінку збору чи Фонду
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-text">
                            Таблиця по посиланню має бути строго
                            <a href="https://docs.google.com/spreadsheets/d/1-7UQWTU2RxRtXP2d5Z6nBc2pUlqMTk7rt695n5JnTBs/edit#gid=0"
                               target="_blank">в такому форматі.</a> Будь ласка, зробіть копію цієї таблиці. Також треба
                            зробити доступ до таблиці для редагування. Треба додати в редактори (Editor) email
                            <span id="editorEmail"
                                  class="text-warning">zbir-404114@zbir-404114.iam.gserviceaccount.com</span>
                            <button class="btn btn-sm btn-outline-secondary copy-text"
                                    data-message="Email" data-text="zbir-404114@zbir-404114.iam.gserviceaccount.com"
                                    onclick="return false;">
                                <i class="bi bi-copy"></i>
                            </button>
                            Якщо ви не будете оновлювати виписку терміном в 7 днів - вам прийде
                            повідомлення що ваш збір скоро буде видалено. Перевірка на 7 днів
                            відбувається щодня в 09:00. Видалення з терміном 10 днів після
                            крайнього донату в таблиці - щодня в 23:59. Ви також отримаєте
                            повідомлення про це. Збір видаляється не остаточно, його можна
                            відновити, якщо ви закинете актуальну виписку. Поки збір видалено ваши
                            підписники не отримують нагадування про донат.
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="spreadsheet_id"
                                   value="{{ $fundraising?->getSpreadsheetLink() ?? '' }}" required>
                            <label for="spreadsheet_id">
                                Посилання на Google Spreadsheet
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <h4>Додаткові поля:</h4>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="card_mono"
                                   value="{{ $fundraising?->getDetails()?->getCardMono() ?? '' }}" maxlength="19">
                            <label for="card_mono">
                                Картка банки
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control"
                                   id="card_privat"
                                   value="{{ $fundraising?->getDetails()?->getCardPrivat() ?? '' }}">
                            <label for="card_privat">
                                Картка Приват
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="paypal"
                                   value="{{ $fundraising?->getDetails()?->getPayPal() ?? '' }}">
                            <label for="paypal">
                                PayPal
                            </label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row mt-3">
            <h5>Опис збору чи Фонду:</h5>
            <textarea id="description" aria-label="description"></textarea>
        </div>
        <div class="row">
            <div class="col-md-12 mt-5 mb-4">
                <div class="footer-modal d-flex justify-content-between">
                    <a href="{{ route('my') }}" type="button" class="btn btn-secondary ms-4">
                        Моя сторінка
                    </a>
                    <button id="{{ $actionId }}" type="submit" class="btn btn-primary me-4"
                            onclick="return false;">
                        {{ $btnText }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
