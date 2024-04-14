@php use App\Models\FundraisingDetail; @endphp
@php use App\Models\Fundraising; @endphp
@php /** @var Fundraising $fundraising */ @endphp
@php $fundraising = $fundraising ?? null @endphp
@php $actionId = $actionId ?? 'createFundraising' @endphp
<script type="module">
    const APP_URL = '{!! json_encode(url('/')) !!}';
    const text = `{!! $fundraising?->getDescription() ?? '<p></p>'!!}`;
    let config = window.baseTinymceConfig;
    Object.assign(config, {init_instance_callback: editor => editor.setContent(text)});
    window.tinymce.init(config);
    let actionFundraisingButton = $('#{{ $actionId }}');

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
                let avatar = APP_URL + '/images/banners/ava-fund-default.png';
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
        actionFundraisingButton.attr('disabled', true);
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
                actionFundraisingButton.attr('disabled', false);
                $('meta[name="csrf-token"]').attr('content', data.csrf);
            },
            error: data => {
                key.removeClass('is-valid').addClass('is-invalid');
                let empty = $("<a>");
                toast(JSON.parse(data.responseText).message, empty, 'text-bg-danger');
                empty.click();
                $('meta[name="csrf-token"]').attr('content', data.csrf);
            },
        });
        return false;
    });
    document.querySelector('#name').addEventListener('change', event => {
        event.preventDefault();
        actionFundraisingButton.attr('disabled', true);
        let name = $('#name');
        if (name.val().length > 0) {
            name.removeClass('is-invalid').addClass('is-valid');
            let value = window.slug(name.val(), {lang: 'uk', separator: '_'});
            let element = document.querySelector('#key');
            element.value = value;
            element.dispatchEvent(new Event('change'));
            actionFundraisingButton.attr('disabled', false);
        } else {
            name.removeClass('is-valid').addClass('is-invalid');
        }
        return false;
    });
    document.querySelector('#link').addEventListener('change', event => {
        event.preventDefault();
        actionFundraisingButton.attr('disabled', true);
        let link = $('#link');
        const regex = /(https:\/\/|http:\/\/)?send.monobank.ua\/jar\/[a-zA-Z0-9]{8,12}/g;
        if (link.val().match(regex)) {
            link.removeClass('is-invalid').addClass('is-valid');
            actionFundraisingButton.attr('disabled', false);
        } else {
            link.removeClass('is-valid').addClass('is-invalid');
        }
        return false;
    });
    document.querySelector('#page').addEventListener('change', event => {
        event.preventDefault();
        actionFundraisingButton.attr('disabled', true);
        let page = $('#page');
        if (window.isValidUrl(page.val())) {
            page.removeClass('is-invalid').addClass('is-valid');
            actionFundraisingButton.attr('disabled', false);
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
        actionFundraisingButton.attr('disabled', true);
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
                actionFundraisingButton.attr('disabled', false);
            },
            error: data => {
                spreadsheet_id.removeClass('is-valid').addClass('is-invalid');
                let empty = $("<a>");
                toast(JSON.parse(data.responseText).message, empty, 'text-bg-danger');
                empty.click();
                $('meta[name="csrf-token"]').attr('content', data.csrf);
            },
        });
        return false;
    });

    actionFundraisingButton.on('click', function (e) {
        e.preventDefault();

        @if ($fundraising)
        if ($('.is-invalid').length > 0) {
        @else
        if ($('.is-invalid').length > 0 || $('.is-valid').length < 5) {
        @endif()
            let empty = $("<a>");
            toast('Перевірте заповнені поля, будь ласка', empty, 'text-bg-danger');
            empty.click();
            return;
        }
        $.ajax({
            url: '{{ $fundraising ? route('fundraising.update', compact('fundraising')) : route('fundraising.create') }}',
            type: "{{ $fundraising ? 'PATCH' : 'POST' }}",
            data: {
                user_id: {{ $fundraising?->getUserId() ?? auth()?->user()?->getId() }},
                key: $('#key').val(),
                name: $('#name').val(),
                link: $('#link').val(),
                page: $('#page').val(),
                avatar: $('#avatar').val(),
                card_mono: $('#card_mono').val(),
                card_privat: $('#card_privat').val(),
                paypal: $('#paypal').val(),
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
