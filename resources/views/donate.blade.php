@extends('layouts.base')
@section('page_title', 'Звітність по Фондам та Зборам - donater.com.ua')
@section('page_description', 'Вся звітність по Фондам та актуальним зборам коштів - donater.com.ua')
@php use App\Services\DonateService; @endphp
@php use App\Models\Volunteer; @endphp
@php $uniqueCode = app(DonateService::class)->getNewUniqueHash(); @endphp

@section('content')
    <div class="container px-4 py-5">
    <div class="row justify-content-center">
    <div class="col-md-6">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header p-5 pb-4 border-bottom-0">
                    <h1 class="title fs-5" id="createDonateModalLabel">Новий благодійний внесок</h1>
                </div>
                <div class="modal-body p-5 pt-0">
                    <form id="donate">
                        @csrf
                        <div class="mb-3">
                            <div class="form-floating input-group">
                                <input type="text" class="form-control" id="donateCode"
                                       value="{{ $uniqueCode }}" disabled>
                                <label for="donateCode">
                                    Унікальний код
                                </label>
                                <button id="copyDonateHash" class="btn btn-outline-secondary"
                                        onclick="return false;">
                                    <i class="bi bi-copy"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form">
                                <select id="chooseVolunteer" class="form-select form-select-lg mb-3"
                                        aria-label="Оберіть збір">
                                    <option value="0" selected>Оберіть збір</option>
                                    @foreach(Volunteer::getActual()->all() as $volunteer)
                                        <option data-url="{{ $volunteer->getLink() }}"
                                                value="{{ $volunteer->getId() }}">
                                            {{ $volunteer->getName() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div class="m-3 lead d-flex justify-content-center text-center">
                                <a id="jarLink" target="_blank" href=""></a>
                            </div>
                            <div class="mt-3 d-flex justify-content-center">
                                <img id="commentImg" class="col-md" style="width: 70%; display: none;"
                                     src="{{ url('/images/donat.png') }}" alt="">
                            </div>
                            <div class="mt-3 lead d-flex justify-content-center text-center"
                                 id="acceptDonate"></div>
                        </div>
                        <div class="footer-modal d-flex justify-content-between">
                            <a href="{{ route('my') }}" type="button" class="btn btn-secondary">
                                Моя сторінка
                            </a>
                            <button id="createDonate" type="button" class="btn btn-primary" disabled
                               onclick="return false;">
                                Зберігти</button>
                        </div>
                    </form>
                </div>
                </div>
                </div>

            </div>
        </div>
    </div>
    <script type="module">
        let copyDonateHash = $('#copyDonateHash');
        copyDonateHash.on('click', function (e) {
            e.preventDefault();
            copyContent($('#donateCode').val());
            return false;
        });
        toast('Код скопійовано', copyDonateHash);

        @auth
        $('#createDonate').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('donate') }}',
                type: "POST",
                data: {
                    _token: $(`#donate [name="_token"]`).val(),
                    uniq_hash: $('#donateCode').val(),
                    user_id: <?= auth()?->user()?->getId() ?>,
                    volunteer_id: $('#chooseVolunteer option:selected').val(),
                },
                success: function (data) {
                    window.location.assign(data.url ?? '{{ route('my') }}');
                },
            });
            return false;
        });

        $('#jarLink').click(() => {
            $('#createDonate').attr('disabled', false);
        });
        document.querySelector('#chooseVolunteer').addEventListener('change', () => {
            let selected = $('#chooseVolunteer option:selected');
            console.log(selected)
            if (selected.val() > 0) {
                $('#jarLink').attr('href', selected.data('url')).text(
                    'Відкрийте банку по посиланню, зробіть донат, обов\'язково код в коментарі'
                );
                $('#commentImg').show();
                let volunteer = selected.text().trim();
                let donateCode = $('#donateCode').val();
                const text = 'Після донату з кодом "code" в банку volunteer треба натиснути "Зберігти". Без цього внесок не буде зараховано!'
                $('#acceptDonate').text(text.replace('code', donateCode).replace('volunteer', volunteer));
            } else {
                $('#commentImg').hide();
                $('#jarLink').attr('href', '').text('');
                $('#acceptDonate').text('');
                $('#createDonate').attr('disabled', true);
            }
        });
        @endauth
    </script>
@endsection
