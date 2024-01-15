@extends('layouts.base')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <form>
                <h1 class="h3 mb-3 fw-normal">🍩 Авторизація</h1>
                <div class="form-floating input-group">
                    <input type="text" class="form-control" id="loginCode" value="{{ $loginHash }}" disabled>
                    <label for="loginCode">Код для логіну через телеграм</label>
                    <button id="copyCode" class="btn btn-outline-secondary" onclick="return false;">
                        <i class="bi bi-copy"></i>
                    </button>
                </div>
                <div class="text-center input-group pt-2">
                    <a href="{{ config('telegram.bots.donater-bot.url') }}?start={{ $loginHash }}"
                        class="btn btn-outline-primary w-100" target="_blank"><i class="bi bi-telegram"> Відкрити
                            Телеграм</i></a>
                </div>
                <p class="mt-5 mb-3 text-body-secondary">Після відправки кода Телеграм боту вас автоматично
                    запустить в ваш обліковий запис</p>
            </form>
        </div>
    </div>
</div>
<script type="module">
    let copyCode = $('#copyCode');
    copyCode.on('click', function (e) {
        e.preventDefault();
        copyContent($('#loginCode').val());
        return false;
    });
    toast('Код скопійовано', copyCode);

    setInterval(() => {
        $.ajax({
            url: "{{ route('login') }}",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                loginHash: $('#loginCode').val(),
            },
            success: function (data) {
                console.log(data);
                window.location.assign(data.url ?? '{{ route('my') }}');
            },
        });
    }, 1000);
</script>
@endsection
