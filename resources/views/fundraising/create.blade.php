@extends('layouts.base')
@section('page_title', 'Створити новий збір - donater.com.ua')
@section('page_description', 'Створити новий збір - donater.com.ua')
@push('head-scripts')
    @vite(['resources/js/tinymce.js'])
@endpush
@section('content')
    <form id="fundraising-new">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content rounded-4 shadow">
                            <div class="modal-header p-4 pb-2 border-bottom-0 justify-content-center">
                                <h2 class="title fs-5" id="createFundraisingModalLabel">Створити новий збір</h2>
                            </div>
                            <div class="modal-b-ody p-3 pt-0">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-center">
                                        <span class="position-relative">
                                            <div class="card border-0 rounded-4 shadow-lg">
                                                <img id="avatarImage" src="{{ url('images/banners/default.png') }}"
                                                     class="bg-image-position-center"
                                                     alt="avatar">
                                            </div>
                                            <label for="file" type="file"
                                                   class="position-absolute top-100 start-100 translate-middle badge rounded-pill bg-danger">
                                                <i class="bi bi-pencil-fill font-large"></i>
                                            </label>
                                            <input id="avatar" type="text" style="display: none;" aria-label="Баннер"
                                                   value="/images/banners/default.png">
                                            <input id="file" type="file" style="display: none;" accept="image/*">
                                        </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="name"
                                                           value="" required maxlength="50">
                                                    <label for="name">
                                                        Назва (до 50 символів)
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="key"
                                                           value="" required>
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
                                                           value="" required>
                                                    <label for="link">
                                                        Посилання на монобанку
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="page"
                                                           value="" required>
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
                                                       target="_blacnk">в такому форматі.</a> Будь ласка, зробіть копію цієї таблиці.  Також треба зробити доступ до таблиці для редагування. Треба додати в редактори (Editor) email
                                                    <span id="editorEmail" class="text-warning">zbir-404114@zbir-404114.iam.gserviceaccount.com</span>
                                                    <button id="copyEmail" class="btn btn-sm btn-outline-secondary"
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
                                                           value="" required>
                                                    <label for="spreadsheet_id">
                                                        Посилання на Google Spreadsheet
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <h5>Опис збору чи Фонду:</h5>
                                    <textarea id="description"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-5 mb-4">
                                        <div class="footer-modal d-flex justify-content-between">
                                            <a href="{{ route('my') }}" type="button" class="btn btn-secondary ms-4">
                                                Моя сторінка
                                            </a>
                                            <button id="createFundraising" type="submit" class="btn btn-primary me-4"
                                                    onclick="return false;">
                                                Зберегти
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script type="module">
        const APP_URL = '{!! json_encode(url('/')) !!}';
        let copyEmail = $('#copyEmail');
        copyEmail.on('click', function (e) {
            e.preventDefault();
            copyContent($('#editorEmail').text());
            return false;
        });
        toast('Email скопійовано', copyEmail);

        document.querySelector('#file').addEventListener('change', event => {
            event.preventDefault();
            let formData = new FormData();
            let $file = $('#file');
            let photo = $file.prop('files')[0];
            if (photo) {
                formData.append('FILE', photo);
            }
            $.ajax({
                url: '{{ route('fundraising.avatar') }}',
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: data => {
                    let avatar = APP_URL + 'images/banners/default.png';
                    if (data.avatar) {
                        avatar = data.avatar;
                    }
                    $('#avatarImage').attr('src', avatar);
                    $('#avatar').val(avatar);
                    $('meta[name="csrf-token"]').attr('content', data.csrf);
                },
                error: data => {
                    let empty = $("<a>");
                    toast(JSON.parse(data.responseText).message, empty, 'text-bg-danger');
                    empty.click();
                },
            });
            return false;
        });
        document.querySelector('#key').addEventListener('change', event => {
            event.preventDefault();
            let key = $('#key');
            const regex = /^[a-zA-Z0-9_-]+$/g;
            if (regex.test(key.val())) {
                key.removeClass('is-invalid').addClass('is-valid');
            } else {
                key.removeClass('is-valid').addClass('is-invalid');
                return;
            }
            $.ajax({
                url: '{{ route('fundraising.key') }}',
                type: "POST",
                data: {
                    key: key.val()
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: data => {
                    key.removeClass('is-invalid').addClass('is-valid');
                    $('meta[name="csrf-token"]').attr('content', data.csrf);
                },
                error: data => {
                    key.removeClass('is-valid').addClass('is-invalid');
                    $('meta[name="csrf-token"]').attr('content', JSON.parse(data.responseText).csrf);
                },
            });
            return false;
        });
        document.querySelector('#name').addEventListener('change', event => {
            event.preventDefault();
            let name = $('#name');
            if (name.val().length > 0) {
                name.removeClass('is-invalid').addClass('is-valid');
                let value = window.slug(name.val(), {lang: 'uk', separator: '_'});
                let element = document.querySelector('#key');
                element.value = value;
                element.dispatchEvent(new Event('change'));
            } else {
                name.removeClass('is-valid').addClass('is-invalid');
            }
            return false;
        });
        document.querySelector('#link').addEventListener('change', event => {
            event.preventDefault();
            let link = $('#link');
            const regex = /(https:\/\/|http:\/\/)?send.monobank.ua\/jar\/[a-zA-Z0-9]{8,12}/g;
            if (link.val().match(regex)) {
                link.removeClass('is-invalid').addClass('is-valid');
            } else {
                link.removeClass('is-valid').addClass('is-invalid');
            }
            return false;
        });
        document.querySelector('#page').addEventListener('change', event => {
            event.preventDefault();
            let page = $('#page');
            if (window.isValidUrl(page.val())) {
                page.removeClass('is-invalid').addClass('is-valid');
            } else {
                page.removeClass('is-valid').addClass('is-invalid');
            }
            return false;
        });

        function getSpreadsheetId(spreadsheet) {
            const regex = /.*\/d\/(.*)\/edit.*/;
            let match = spreadsheet.match(regex);

            return match[1];
        }

        document.querySelector('#spreadsheet_id').addEventListener('change', event => {
            event.preventDefault();
            let id = '';
            let spreadsheet_id = $('#spreadsheet_id');
            const regex = /(https:\/\/)?docs.google.com\/spreadsheets\/d\/[a-zA-Z0-9-_]+(\/|\/edit|\/edit.*)/g;
            if (spreadsheet_id.val().match(regex)) {
                id = getSpreadsheetId(spreadsheet_id.val());
            } else {
                spreadsheet_id.removeClass('is-valid').addClass('is-invalid');
                return ;
            }
            $.ajax({
                url: '{{ route('fundraising.spreadsheet') }}',
                type: "POST",
                data: {
                    spreadsheet_id: id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: data => {
                    spreadsheet_id.removeClass('is-invalid').addClass('is-valid');
                    $('meta[name="csrf-token"]').attr('content', data.csrf);
                },
                error: data => {
                    spreadsheet_id.removeClass('is-valid').addClass('is-invalid');
                    $('meta[name="csrf-token"]').attr('content', JSON.parse(data.responseText).csrf);
                },
            });
            return false;
        });

        $('#createFundraising').on('click', function (e) {
            e.preventDefault();
            if ($('.is-invalid').length > 0 || $('.is-valid').length < 5) {
                let empty = $("<a>");
                toast('Перевірте заповнені поля, будь ласка', empty, 'text-bg-danger');
                empty.click();
                return ;
            }
            $.ajax({
                url: '{{ route('fundraising.create') }}',
                type: "POST",
                data: {
                    user_id: <?= auth()?->user()?->getId() ?>,
                    fundraising_id: $('#chooseFundraising option:selected').val(),
                    key: $('#key').val(),
                    name: $('#name').val(),
                    link: $('#link').val(),
                    page: $('#page').val(),
                    avatar: $('#avatar').val(),
                    description: window.tinymce.get('description').getContent().replace('<img ', '<img style="max-width: 100%;" ') || '<p></p>',
                    spreadsheet_id: getSpreadsheetId($('#spreadsheet_id').val()),
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: data => {
                    window.location.assign(data.url ?? '{{ route('my') }}');
                },
                error: data => {
                    let empty = $("<a>");
                    toast(JSON.parse(data.responseText).message, empty, 'text-bg-danger');
                    empty.click();
                    $('meta[name="csrf-token"]').attr('content', JSON.parse(data.responseText).csrf);
                },
            });
            return false;
        });
    </script>
@endsection
