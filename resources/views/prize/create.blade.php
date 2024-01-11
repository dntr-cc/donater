@extends('layouts.base')
@section('page_title', 'Створити новий приз - donater.com.ua')
@section('page_description', 'Створити новий приз - donater.com.ua')
@push('head-scripts')
    @vite(['resources/js/tinymce.js'])
@endpush
@php use App\DTOs\RaffleUser; @endphp
@php use App\Models\Prize; @endphp
@section('content')
    <form id="fundraising-new">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content rounded-4 shadow">
                            <div class="modal-header p-4 pb-2 border-bottom-0 justify-content-center">
                                <h2 class="title fs-5" id="createPrizeModalLabel">Створити новий приз для
                                    зборів</h2>
                            </div>
                            <div class="modal-b-ody p-3 pt-0">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-center">
                                        <span class="position-relative">
                                            <div class="card border-0 rounded-4 shadow-lg">
                                                <img id="avatarImage" src="{{ url('/images/avatars/avatar.jpeg') }}"
                                                     class="bg-image-position-center"
                                                     alt="avatar">
                                            </div>
                                            <label for="file" type="file"
                                                   class="position-absolute top-100 start-100 translate-middle badge rounded-pill bg-danger">
                                                <i class="bi bi-pencil-fill font-large"></i>
                                            </label>
                                            <input id="avatar" type="text" style="display: none;" aria-label="Баннер"
                                                   value="/images/avatars/avatar.jpeg">
                                            <input id="file" type="file" style="display: none;" accept="image/*">
                                        </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mt-3">
                                            <div class="col-md-12 mb-3">
                                                <div class="form-floating">
                                                    <input type="text" min="3" maxlength="50" class="form-control" id="name"
                                                           value="">
                                                    <label for="name">
                                                        Назва призу чи призів
                                                    </label>
                                                </div>
                                            </div>
                                            @foreach(RaffleUser::TYPES as $type => $name)
                                                <div class="form-check m-2">
                                                    <input class="form-check-input" type="radio" name="raffle_type" id="{{ $type }}">
                                                    <label class="form-check-label" for="{{ $type }}">
                                                        {{ $name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12 mb-3">
                                                <div id="price_input" class="form-floating">
                                                    <input type="number" step="1" min="1" class="form-control" id="price"
                                                           value="1">
                                                    <label for="price">
                                                        Ціна квитка
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <div class="form-floating">
                                                    <input type="number" step="1" min="1" class="form-control" id="winners"
                                                           value="1">
                                                    <label for="winners">
                                                        Кількість переможців
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            @foreach(Prize::AVAILABLE_TYPES as $type => $name)
                                                <div class="form-check m-2">
                                                    <input class="form-check-input" type="radio" name="available_type" id="{{ $type }}" checked>
                                                    <label class="form-check-label" for="{{ $type }}">
                                                        {{ $name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>


                                <div class="row mt-3">
                                    <h5>Опис призу чи призів:</h5>
                                    <textarea id="description"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-5 mb-4">
                                        <div class="footer-modal d-flex justify-content-between">
                                            <a href="{{ route('my') }}" type="button" class="btn btn-secondary ms-4">
                                                Моя сторінка
                                            </a>
                                            <button id="createPrize" type="submit" class="btn btn-primary me-4"
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
        let {{ RaffleUser::TYPE_PER_TICKET }} = $('#{{ RaffleUser::TYPE_PER_TICKET }}');
        let {{ RaffleUser::TYPE_PER_PERSON }} = $('#{{ RaffleUser::TYPE_PER_PERSON }}');
        let {{ RaffleUser::TYPE_PER_PERSON_AND_SUM }} = $('#{{ RaffleUser::TYPE_PER_PERSON_AND_SUM }}');

        function showAndHidePrice(event) {
            event.preventDefault();
            let priceInput = $('#price_input');
            if ({{ RaffleUser::TYPE_PER_PERSON }}.is(':checked')) {
                priceInput.addClass('hide');
            } else {
                priceInput.removeClass('hide');
            }
            let label = $('label[for="price"]');
            if ({{ RaffleUser::TYPE_PER_PERSON_AND_SUM }}.is(':checked')) {
                label.text('Ціна входу, обмежити по сумі донатів');
            } else {
                label.text('Ціна квитка');
            }

        }

        {{ RaffleUser::TYPE_PER_TICKET }}.change(event => {
            showAndHidePrice(event);
        });
        {{ RaffleUser::TYPE_PER_PERSON }}.change(event => {
            showAndHidePrice(event);
        });
        {{ RaffleUser::TYPE_PER_PERSON_AND_SUM }}.change(event => {
            showAndHidePrice(event);
        });

        document.querySelector('#file').addEventListener('change', event => {
            event.preventDefault();
            let formData = new FormData();
            let $file = $('#file');
            let photo = $file.prop('files')[0];
            if (photo) {
                formData.append('FILE', photo);
            }
            $.ajax({
                url: '{{ route('prize.avatar') }}',
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
                error: data => {
                    let empty = $("<a>");
                    toast(JSON.parse(data.responseText).message, empty, 'text-bg-danger');
                    empty.click();
                },
            });
            return false;
        });

        $('#createPrize').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('prize.create') }}',
                type: "POST",
                data: {
                    name: $('#name').val(),
                    description: window.tinymce.get('description').getContent().replace('<img ', '<img style="max-width: 100%;" ') || '<p></p>',
                    avatar: $('#avatar').val(),
                    user_id: <?= auth()?->user()?->getId() ?>,
                    raffle_type: $('input[name="raffle_type"]:checked').attr('id'),
                    raffle_winners: $('#winners').val(),
                    raffle_price: $('#price').val(),
                    available_type: $('input[name="available_type"]:checked').attr('id'),
                    available_for: null,
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
