@extends('layouts.base')
@section('page_title', 'Створити новий збір - donater.com.ua')
@section('page_description', 'Створити новий збір - donater.com.ua')
@push('head-scripts')
    @vite(['resources/js/tinymce.js'])
@endpush
@php use App\Models\Fundraising; @endphp
@php /** @var Fundraising $fundraising */@endphp
@section('content')
    <form id="fundraising-new">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content rounded-4 shadow">
                            <div class="modal-header p-4 pb-2 border-bottom-0 justify-content-center">
                                <h2 class="title fs-5" id="updateFundraisingModalLabel">Редагування</h2>
                            </div>
                            <div class="modal-b-ody p-3 pt-0">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-center">
                                        <span class="position-relative">
                                            <div class="card border-0 rounded-4 shadow-lg">
                                                <img id="avatarImage" src="{{ url($fundraising->getAvatar()) }}"
                                                     class="bg-image-position-center"
                                                     alt="avatar">
                                            </div>
                                            <label for="file" type="file"
                                                   class="position-absolute top-100 start-100 translate-middle badge rounded-pill bg-danger">
                                                <i class="bi bi-pencil-fill font-large"></i>
                                            </label>
                                            <input id="avatar" type="text" style="display: none;" aria-label="Баннер"
                                                   value="{{ $fundraising->getAvatar() }}">
                                            <input id="file" type="file" style="display: none;" accept="image/*">
                                        </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="name"
                                                           value="{{ $fundraising->getName() }}" required
                                                           maxlength="50">
                                                    <label for="name">
                                                        Назва (до 50 символів)
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="key"
                                                           value="{{ $fundraising->getKey() }}" required>
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
                                                           value="{{ $fundraising->getJarLink() }}" required>
                                                    <label for="link">
                                                        Посилання на монобанку
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="page"
                                                           value="{{ $fundraising->getPage() }}" required>
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
                                                       target="_blacnk">в такому форматі.</a> Будь ласка, зробіть копію
                                                    цієї таблиці.
                                                    Якщо ви не додате свою таблицю або не будете оновлювати виписку -
                                                    збір буде видалено автоматично.
                                                    Також треба зробити доступ до таблиці для редагування. Треба додати
                                                    в
                                                    редактори (Editor) email
                                                    <span id="editorEmail" class="text-warning">zbir-404114@zbir-404114.iam.gserviceaccount.com </span>
                                                    <button id="copyEmail" class="btn btn-sm btn-outline-secondary"
                                                            onclick="return false;">
                                                        <i class="bi bi-copy"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="spreadsheet_id"
                                                           value="{{ $fundraising->getSpreadsheetLink() }}" required>
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
                                    <div>
                                        <textarea id="description"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-5 mb-4">
                                        <div class="footer-modal d-flex justify-content-between">
                                            <a href="{{ route('my') }}" type="button" class="btn btn-secondary ms-4">
                                                Моя сторінка
                                            </a>
                                            <button id="updateFundraising" type="submit" class="btn btn-primary me-4"
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
        const text = `{!! $fundraising->getDescription() !!}`;
        let config = window.baseTinymceConfig;
        Object.assign(config, {init_instance_callback: editor => editor.setContent(text)});
        window.tinymce.init(config);
        let copyEmail = $('#copyEmail');
        copyEmail.on('click', function (e) {
            e.preventDefault();
            copyContent($('#editorEmail').text());
            return false;
        });
        toast('Email скопійовано', copyEmail);

        let updateFundraisingButton = $('#updateFundraising');

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
                    let avatar = APP_URL + '/images/avatars/avatar.jpeg';
                    if (data.avatar) {
                        avatar = data.avatar;
                    }
                    $('#avatarImage').attr('src', avatar);
                    $('#avatar').val(avatar);
                    $('meta[name="csrf-token"]').attr('content', data.csrf);
                },
            });
            return false;
        });
        document.querySelector('#key').addEventListener('change', event => {
            event.preventDefault();
            updateFundraisingButton.attr('disabled', true);
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
                    updateFundraisingButton.attr('disabled', false);
                    $('meta[name="csrf-token"]').attr('content', data.csrf);
                },
                error: data => {
                    key.removeClass('is-valid').addClass('is-invalid');
                    $('meta[name="csrf-token"]').attr('content', data.csrf);
                },
            });
            return false;
        });
        document.querySelector('#name').addEventListener('change', event => {
            event.preventDefault();
            updateFundraisingButton.attr('disabled', true);
            let name = $('#name');
            if (name.val().length > 0) {
                name.removeClass('is-invalid').addClass('is-valid');
                let value = window.slug(name.val(), {lang: 'uk', separator: '_'});
                let element = document.querySelector('#key');
                element.value = value;
                element.dispatchEvent(new Event('change'));
                updateFundraisingButton.attr('disabled', false);
            } else {
                name.removeClass('is-valid').addClass('is-invalid');
            }
            return false;
        });
        document.querySelector('#link').addEventListener('change', event => {
            event.preventDefault();
            updateFundraisingButton.attr('disabled', true);
            let link = $('#link');
            const regex = /(https:\/\/|http:\/\/)?send.monobank.ua\/jar\/[a-zA-Z0-9]{8,12}/g;
            if (link.val().match(regex)) {
                link.removeClass('is-invalid').addClass('is-valid');
                updateFundraisingButton.attr('disabled', false);
            } else {
                link.removeClass('is-valid').addClass('is-invalid');
            }
            return false;
        });
        document.querySelector('#page').addEventListener('change', event => {
            event.preventDefault();
            updateFundraisingButton.attr('disabled', true);
            let page = $('#page');
            if (window.isValidUrl(page.val())) {
                page.removeClass('is-invalid').addClass('is-valid');
                updateFundraisingButton.attr('disabled', false);
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
            updateFundraisingButton.attr('disabled', true);
            let id = '';
            let spreadsheet_id = $('#spreadsheet_id');
            const regex = /(https:\/\/)?docs.google.com\/spreadsheets\/d\/[a-zA-Z0-9-_]+(\/|\/edit|\/edit#gid=0)/g;
            if (spreadsheet_id.val().match(regex)) {
                id = getSpreadsheetId(spreadsheet_id.val());
            } else {
                spreadsheet_id.removeClass('is-valid').addClass('is-invalid');
                return;
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
                    updateFundraisingButton.attr('disabled', false);
                },
                error: data => {
                    spreadsheet_id.removeClass('is-valid').addClass('is-invalid');
                    $('meta[name="csrf-token"]').attr('content', data.csrf);
                },
            });
            return false;
        });

        updateFundraisingButton.on('click', function (e) {
            e.preventDefault();
            if ($('.is-invalid').length > 0) {
                let empty = $("<a>");
                toast('Перевірте заповнені поля, будь ласка', empty, 'text-bg-danger');
                empty.click();
                console.log($('#description').text())
                return;
            }
            $.ajax({
                url: '{{ route('fundraising.update', compact('fundraising')) }}',
                type: "PATCH",
                data: {
                    user_id: {{ $fundraising->getUserId() }},
                    key: $('#key').val(),
                    name: $('#name').val(),
                    link: $('#link').val(),
                    page: $('#page').val(),
                    avatar: $('#avatar').val(),
                    description: window.tinymce.get('description').getContent() || '<p></p>',
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
                    $('meta[name="csrf-token"]').attr('content', data.csrf);
                },
            });
            return false;
        });
    </script>
@endsection
