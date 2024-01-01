@extends('layouts.base')
@section('page_title', 'Створити новий благодійний внесок - donater.com.ua')
@section('page_description', 'Створити новий благодійний внесок - donater.com.ua')
@php use App\Services\DonateService; @endphp
@php use App\Models\Fundraising; @endphp
@php /** @var Fundraising $fundraising */ @endphp
@php $choosen = request()->get('fundraising', ''); @endphp
@php $fixCode = auth()->user()->fundraisings->count() && request()->get('fixCode', false); @endphp
@php $uniqueCode = $fixCode? '' : app(DonateService::class)->getNewUniqueHash(); @endphp
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="modal-dialog" role="document">
                    <div class="modal-content rounded-4 shadow">
                        <div class="modal-header p-4 pb-2 border-bottom-0 justify-content-center">
                            <h1 class="title fs-5" id="createDonateModalLabel">Новий благодійний внесок</h1>
                        </div>
                        <div class="modal-body p-3 pt-0">
                            <form id="donate">
                                <div class="mb-3">
                                    <div class="form-floating input-group">
                                        <input type="text" class="form-control" id="donateCode"
                                               value="{{ $uniqueCode }}" @if(!$fixCode) disabled @endif >
                                        <label for="donateCode">
                                            Унікальний код
                                        </label>
                                        @if(!$fixCode)
                                            <button id="copyDonateHash" class="btn btn-outline-secondary"
                                                    onclick="return false;">
                                                <i class="bi bi-copy"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form">
                                        <select id="chooseFundraising" class="form-select form-select-lg mb-3"
                                                aria-label="Оберіть збір">
                                            <option value="0" @if('' === $choosen)
                                                selected
                                                    @endif>Оберіть збір
                                            </option>
                                            @php $all = $fixCode ? auth()->user()->fundraisings : Fundraising::getActual()->all(); @endphp
                                            @foreach($all as $fundraising)
                                                <option data-url="{{ $fundraising->getLink() }}"
                                                        data-key="{{ $fundraising->getKey() }}"
                                                        value="{{ $fundraising->getId() }}"
                                                        @if($fundraising->getKey() === $choosen)
                                                            selected
                                                        @endif>
                                                    {{ $fundraising->getName() }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if($fixCode)
                                        <div class="form">
                                            <select id="userId" class="form-select form-select-lg mb-3"
                                                    aria-label="Оберіть користувача">
                                                @foreach(\App\Models\User::all() as $user)
                                                    <option value="{{ $user->getId() }}">
                                                        {{ $user->getAtUsername() }} - {{ $user->getFullName() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <br>
                                    <div class="mb-3 lead text-center">
                                        <p id="jarText"></p>
                                        <a id="jarLink" class="mb-3" target="_blank" href=""></a>
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
                                    <button id="createDonate" type="button" class="btn btn-primary"
                                            @if(!$fixCode) disabled @endif
                                            onclick="return false;">
                                        Зберегти
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        const choosen = '{{ $choosen }}';
        const fixCode = '{{ $fixCode }}';
        gtag('event', 'begin_donation', {
            'choosen': choosen,
            'fix_code': fixCode,
        });
        let copyDonateHash = $('#copyDonateHash');
        copyDonateHash.on('click', function (e) {
            e.preventDefault();
            copyContent($('#donateCode').val());
            gtag('event', 'copy_code', {
                'choosen': choosen,
                'fix_code': fixCode,
            });
            return false;
        });
        toast('Код скопійовано', copyDonateHash);

        $('#createDonate').on('click', event => {
            event.preventDefault();
            let fixCode = {{ $fixCode ? 'true' : 'false' }};
            let userId = fixCode ? $('#userId option:selected').val() : {{ auth()->user()->getId() }};
            let fundraisingId = $('#chooseFundraising option:selected').val();
            if (!(fundraisingId > 0)) {
                let empty = $("<a>");
                toast('Треба обрати збір', empty, 'text-bg-danger');
                empty.click();
                return;
            }
            $.ajax({
                url: '{{ route('donate') }}',
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    uniq_hash: $('#donateCode').val(),
                    user_id: userId,
                    fundraising_id: fundraisingId,
                },
                success: data => {
                    gtag('event', 'finish_donation', {
                        'choosen': choosen,
                        'fix_code': fixCode,
                        'user_id': userId,
                        'fundraising_id': fundraisingId,
                    });
                    window.location.assign(data.url ?? '{{ route('my') }}');
                },
                error: data => {
                    let empty = $("<a>");
                    let message = JSON.parse(data.responseText).message;
                    toast(message, empty, 'text-bg-danger');
                    empty.click();
                    $('meta[name="csrf-token"]').attr('content', data.csrf);
                    gtag('event', 'failed_donation', {
                        'choosen': choosen,
                        'fix_code': fixCode,
                        'user_id': userId,
                        'fundraising_id': fundraisingId,
                        'message': message,
                    });
                },
            });
            return false;
        });
        $('#jarLink').click(() => {
            gtag('event', 'open_jar', {
                'choosen': choosen,
                'fix_code': fixCode,
            });
            $('#createDonate').attr('disabled', false);
        });

        function fundraisingHasBeenChoosen(selected) {
            $('#jarText').text(
                'Відкрийте банку по посиланню, зробіть донат, обов\'язково код в коментарі. Кнопка Зберегти буде активована після кліку на посилання банки збору'
            );
            $('#jarLink').attr('href', selected.data('url')).text(
                'ВІДКРИТИ БАНКУ'
            ).addClass('btn  btn-lg font-x-large');
            $('#commentImg').show();
            let fundraising = selected.text().trim();
            let donateCode = $('#donateCode').val();
            const text = 'Після донату з кодом "code" в банку fundraising треба натиснути "Зберегти". Без цього внесок не буде зараховано!'
            $('#acceptDonate').text(text.replace('code', donateCode).replace('fundraising', fundraising));
        }

        if (choosen) {
            fundraisingHasBeenChoosen($('#chooseFundraising option:selected'));
        }
        document.querySelector('#chooseFundraising').addEventListener('change', () => {
            let selected = $('#chooseFundraising option:selected');
            if (selected.val() > 0) {
                fundraisingHasBeenChoosen(selected);
            } else {
                $('#commentImg').hide();
                $('#jarText').text('');
                $('#jarLink').attr('href', '').text('').removeClass('btn  btn-lg font-x-large');
                $('#acceptDonate').text('');
                $('#createDonate').attr('disabled', true);
            }
        });
    </script>
@endsection
