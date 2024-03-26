@push('head-scripts')
    @vite(['resources/js/pickerjs.js'])
    @vite(['resources/sass/pickerjs.scss'])
@endpush
<div class="modal fade" id="subscribe" tabindex="-1" aria-labelledby="subscribeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="subscribeLabel"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="lead"></p>
                <form>
                    <input type="number" class="form-control hide" min="1" name="volunteer_id"
                           id="volunteer_id" aria-label="volunteer_id">
                    <div class="form-floating py-1">
                        <input type="number" class="form-control" min="1" name="amount"
                               id="amount" value="33">
                        <label for="amount">
                            Сума щоденного донату, ₴
                        </label>
                    </div>
                    <div class="form-floating py-1">
                        <select id="frequency" class="form-select">
                            @foreach(\App\Models\SubscribesMessage::FREQUENCY_NAME_MAP as $key => $name)
                                <option value="{{ $key }}">{{$name}}</option>
                            @endforeach
                        </select>
                        <label for="frequency">
                            Періодичність нагадувань
                        </label>
                    </div>
                    <div class="form-floating py-1">
                        <input type="text" class="form-control js-time-picker" name="first_message_at"
                               id="first_message_at" value="">
                        <label for="first_message_at">
                            День та час наступного нагадування донату від бота
                        </label>
                        <div class="js-mini-picker-container"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary justify-content-evenly"
                        data-bs-dismiss="modal">
                    Закрити
                </button>
                <button id="subscribe-del" type="button" class="btn btn-danger">Видалити</button>
                <button id="subscribe-action" type="button" class="btn btn-primary"></button>
            </div>
        </div>
    </div>
</div>
<script type="module">
    let subscribeAction = $('#subscribe-action');
    $('#subscribe-del').on('click', event => {
        event.preventDefault();
        $.ajax({
            url: subscribeAction.attr('data-del-url'),
            type: "DELETE",
            data: {},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: () => {
                location.reload();
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
    @auth
    subscribeAction.on('click', event => {
        event.preventDefault();
        $.ajax({
            url: subscribeAction.attr('data-url'),
            type: subscribeAction.attr('data-action-type'),
            data: {
                user_id: {{ auth()->user()->getId() }},
                volunteer_id: $('#volunteer_id').val(),
                frequency: $('#frequency').val(),
                amount: $('#amount').val(),
                first_message_at: $('#first_message_at').val(),
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: () => {
                location.reload();
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
    @endauth
    $('#amount').on('change input', () => {
        $('#sum').val($('#amount').val() * 30);
    });

    let subscribe = document.getElementById('subscribe');
    let picker = null;
    subscribe.addEventListener('show.bs.modal', event => {
        let button = event.relatedTarget;
        subscribe.querySelector('#volunteer_id').value = button.getAttribute('data-bs-volunteer-id');
        subscribe.querySelector('#frequency').value = button.getAttribute('data-bs-frequency');
        let time = button.getAttribute('data-bs-first-message-at');
        subscribe.querySelector('#first_message_at').value = time;
        if (picker) {
            picker.destroy();
        }
        let isNew = button.getAttribute('data-bs-update') === '0';
        let btnText = isNew ? 'Підписатися' : 'Оновити';
        let btnClass = isNew ? 'btn-primary' : 'btn-warning';
        let actionType = isNew ? 'POST' : 'PATCH';
        if (isNew) {
            $('#subscribe-del').hide();
        } else {
            $('#subscribe-del').show();
        }
        @php $current = auth()->user() @endphp
        subscribe.querySelector('#subscribe-action').textContent = btnText;
        subscribe.querySelector('#subscribe-action').classList.remove('btn-primary');
        subscribe.querySelector('#subscribe-action').classList.remove('btn-warning');
        subscribe.querySelector('#subscribe-action').classList.add(btnClass);
        subscribe.querySelector('#subscribe-del').classList.remove('hide');
        subscribe.querySelector('#subscribe-action').setAttribute('data-del-url', button.getAttribute('data-bs-del-url'));
        subscribe.querySelector('#subscribe-action').setAttribute('data-url', button.getAttribute('data-bs-url'));
        subscribe.querySelector('#subscribe-action').setAttribute('data-action-type', actionType);
        picker = new Picker(document.getElementById('first_message_at'), {
            container: '.js-mini-picker-container',
            format: 'YYYY-MM-DD HH:mm',
            setDate: time,
            controls: true,
            inline: true,
            rows: 5,
        });
        let volunteerKey = button.getAttribute('data-bs-volunteer-key');
        let actionTypeTxtTitle = isNew ? 'Підписатися' : 'Редагувати підписку';
        let actionTypeTxtContent1 = isNew ? '{{ sensitive('Ви хочете стати серійним донатером', $current) }}' : '{{ sensitive('Ви серійний донатер', $current) }}';
        let actionTypeTxtContent2 = isNew ? ' буде бачити' : ' бачить';
        let actionTypeTxtContent3 = isNew ? 'розраховувати' : 'розраховує';
        let actionTypeTxtContent4 = isNew ? 'вибрати' : 'змінити';
        subscribe.querySelector('.modal-title').textContent = actionTypeTxtTitle + ' на @' + volunteerKey;
        subscribe.querySelector('.modal-body p.lead').textContent = actionTypeTxtContent1 + ' для волонтера @' +
            volunteerKey + '. ' + button.getAttribute('data-bs-volunteer-name') +
            actionTypeTxtContent2 + ' вашу підписку та ' + actionTypeTxtContent3 +
            ' на донат згідно умов, які ви зараз зможете ' + actionTypeTxtContent4 +
            '. Налаштування всіх ваших підписок ви можете змінити на сторінці Вашого профілю.';
    })
</script>
