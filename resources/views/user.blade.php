@php use App\Models\Donate; @endphp
@php use App\Models\User; @endphp
@php use App\Models\UserLink; @endphp
@php use App\Models\Fundraising; @endphp
@php /** @var User $user */ @endphp
@php /** @var Donate $donate */ @endphp
@php /** @var Fundraising $fundraising */ @endphp
@php /** @var UserLink $link */ @endphp
@php $withZvitLink = true; @endphp
@php $additionalClasses = 'btn-xs'; @endphp
@php $donates = $user->getDonates(); @endphp
@extends('layouts.base')
@section('page_title', strtr(':fullName (:username) - користувач сайту donater.com.ua', [':fullName' => $user->getFullName(), ':username' => $user->getAtUsername()]))
@section('page_description', strtr(':fullName (:username) - користувач сайту donater.com.ua', [':fullName' => $user->getFullName(), ':username' => $user->getAtUsername()]))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="container py-5">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card mb-4"
                             onclick="return false;">
                            <div class="card-body text-center">
                                <img src="{{ $user->getAvatar() }}"
                                     @if (auth()?->user()?->can('update', $user))
                                         data-bs-toggle="modal" data-bs-target="#userEditModal"
                                     @endif
                                     alt="avatar"
                                     class="rounded-circle img-fluid" style="width: 150px;">
                                <h3 class="my-3">{{ $user->getFullName() }}</h3>
                                @if ($user->isPremium())
                                    <span title="Створені збори" class="badge bg-golden p-1">
                                    <i class="bi bi-telegram" title="Telegram Premium"
                                       style="color: #fff;"></i>
                                    </span>
                                @endif
                                @if ($user->fundraisings->count())
                                    <span title="Створені збори" class="badge p-1 bg-info">
                                    &nbsp;{{ $user->fundraisings->count() }}&nbsp;
                                </span>
                                @endif
                                @if ($user->getApprovedDonateCount())
                                    <span title="Завалідовані донати" class="badge p-1 bg-success">
                                    &nbsp;{{ $user->getApprovedDonateCount() }}&nbsp;
                                </span>
                                @endif
                                <h4 class="m-3 text-muted">{{ $user->getAtUsername() }}</h4>
                                <h6 class="m-3 text-muted">Дата реєстрації {{ $user->getCreatedAt() }}</h6>
                                <div class="d-flex justify-content-center mb-2">
                                    <div class="form-floating input-group">
                                        <input type="text" class="form-control" id="userLink"
                                               value="{{ $user->getUserLink() }}" disabled>
                                        <label for="userLink">Посилання на цю сторінку</label>
                                        <button id="copyLink" class="btn btn-outline-secondary" onclick="return false;">
                                            <i class="bi bi-copy"></i></button>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center mb-2">
                                    <div class="form-floating input-group">
                                        <input type="text" class="form-control" id="userCode"
                                               value="{{ $user->getUserCode() }}" disabled>
                                        <label for="userCode">Код донатера (регістр букв обов'язковий!)</label>
                                        <button id="copyCode" class="btn btn-outline-secondary" onclick="return false;">
                                            <i class="bi bi-copy"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4 mb-lg-0">
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush rounded-3">
                                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                        <h4>Посилання</h4>
                                        @if (auth()?->user()?->can('update', $user))
                                            <button data-bs-toggle="modal" data-bs-target="#createLinkModal"
                                                    id="addDonation" class="btn">
                                                <i class="bi bi-plus-circle-fill"></i>
                                            </button>
                                        @endcan
                                    </li>
                                    @foreach($user->getLinks()->all() as $link)
                                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                            <i class="{{ $link->getIcon() }} fa-lg"></i>
                                            <p class="mb-0">
                                                <a href="{{ $link->getLink() }}">{{ $link->getName() }}</a>
                                                <i data-id="{{ $link->getId() }}"
                                                   class="mx-2 bi bi-x-octagon text-danger delete-link">
                                                </i>
                                            </p>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 d-flex justify-content-between align-items-start">
                                        <h4>Збори та Фонди</h4>
                                        @if (auth()?->user()?->getId() === $user->getId())
                                            <a href="{{route('fundraising.new')}}" class="btn ">
                                                <i class="bi bi-plus-circle-fill"></i>
                                                Створити
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                @foreach($user->getFundraisings() as $it => $fundraising)
                                    <div class="row">
                                        <div
                                            class="col-sm-12 d-flex justify-content-between align-items-start fundraising"
                                            data-fundraising="{{ $fundraising->getKey() }}">
                                            <div class="fw-bold">
                                                @if($fundraising->isEnabled())
                                                    <a href="{{route('fundraising.show', ['fundraising' => $fundraising->getKey()])}}" class="btn btn-xs btn-info">ЗБІР ТРИВАЄ</a>
                                                @else
                                                    <a href="{{route('fundraising.show', ['fundraising' => $fundraising->getKey()])}}" class="btn btn-xs btn-danger">ЗБІР ЗАКРИТО</a>
                                                @endif{{ $fundraising->getName() }}
                                                @include('layouts.links', compact('fundraising', 'withZvitLink', 'additionalClasses'))
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach

                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="me-auto mt-auto">
                                                <h4>Благодійні внески</h4>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            @include('layouts.donates', compact('donates'))
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (auth()?->user()?->can('update', $user))
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div class="me-auto mt-auto">
                                                    <h4>Ваша аналітика по всім зборам (бачите тільки ви)</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-12 px-2 py-2">
                                                @include('layouts.analytics', compact('rows', 'charts', 'charts2', 'charts3'))
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        @auth
            <div class="modal fade" id="createLinkModal" tabindex="-1" aria-labelledby="createLinkModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="createLinkModalLabel">Створити нове посилання</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form class="form">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <div class="form-floating py-1">
                                        <input type="text" class="form-control" maxlength="180" name="linkUrl"
                                               id="linkUrl"
                                               required>
                                        <label for="linkUrl">
                                            Посилання
                                        </label>
                                    </div>
                                    <div class="form-floating py-1">
                                        <input type="text" class="form-control" maxlength="20" name="linkName"
                                               id="linkName"
                                               required>
                                        <label for="linkName">
                                            Назва
                                        </label>
                                    </div>
                                    <div class="form-floating py-1 input-group">
                                <span class="input-group-text">
                                     <i id="icon-preview" class="bi bi-globe"></i>
                                </span>
                                        <select id="linkIcon" class="form-select" name="linkIcon">
                                            <option value="globe" selected>Оберіть іконку</option>
                                            @foreach(UserLink::ICONS as $style => $name )
                                                <option value="{{ $style }}">
                                                    <i class="{{ $style }}"></i>
                                                    {{ $name  }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-secondary justify-content-evenly"
                                        data-bs-dismiss="modal">
                                    Закрити
                                </button>
                                <button id="createLink" type="submit" class="btn btn-primary">Зберегти</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="userEditModal" tabindex="-1" aria-labelledby="userEditModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="userEditModalLabel">Змінити дані профілю</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form class="form">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-center">
                                <span class="position-relative">
                                    <img src="{{ $user->getAvatar() }}"
                                         alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                                    <label for="file" type="file"
                                           class="position-absolute top-80 start-80 translate-middle badge rounded-pill bg-danger">
                                        <i class="bi bi-pencil-fill font-large"></i>
                                    </label>
                                    <input id="file" type="file" style="display: none;" accept="image/*">
                                </span>
                                    </div>
                                    <div class="form-floating py-1">
                                        <input type="text" class="form-control" maxlength="50" name="username"
                                               id="username"
                                               value="{{ $user->getUsername() }}">
                                        <label for="username">
                                            Нікнейм
                                        </label>
                                    </div>
                                    <div class="form-floating py-1">
                                        <input type="text" class="form-control" maxlength="50" name="firstName"
                                               id="firstName"
                                               value="{{ $user->getFirstName() }}">
                                        <label for="firstName">
                                            Ім'я
                                        </label>
                                    </div>
                                    <div class="form-floating py-1">
                                        <input type="text" class="form-control" maxlength="50" name="lastName"
                                               id="lastName"
                                               value="{{ $user->getLastName() }}">
                                        <label for="lastName">
                                            Прізвище
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-secondary justify-content-evenly"
                                        data-bs-dismiss="modal">
                                    Закрити
                                </button>
                                <button id="userEdit" type="submit" class="btn btn-primary">Зберегти</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="welcomeVolunteerModal" tabindex="-1" aria-labelledby="welcomeVolunteerModalLabel" aria-modal="true" >
                <div class="modal-dialog modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="welcomeVolunteerModalLabel">Ласкаво просимо на сторінку донатера @{{ $user->getUsername() }}</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form class="form">
                            <div class="modal-body">
                                <p>
                                    donater.com.ua це платформа, яка зроблена для волонтерів допомагати робити збори
                                    більш прозорими. Благодійники можуть вказувати свій унікальний код донатера, це
                                    допомогає потім через виписку метчіти донати з користувачами сайту. Таким чином
                                    це дає волонтерам додаткові можливості, такі як розіграші серед донаторів з різними
                                    налаштуваннями, аналітика по збору, чи загалом по всім зборам, тощо.
                                </p>
                                <p>
                                    Наразі сайт в відкритому бета-тесті, найближчим часом планується реалізувати
                                    нагадування для донаторів, функціонал розіграшів, можливість донаторів робити
                                    розіграш на вашому зборі без вашої участі, щоб підпушити ваш збір.
                                    Приблизний план розвитку є в розділі Roadmap
                                </p>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-secondary justify-content-evenly" data-bs-dismiss="modal">
                                    Закрити
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endauth
        <script type="module">
            @if($dntr)
            $('#welcomeVolunteerModal').modal('show')
            @endif
            let copyLink = $('#copyLink');
            copyLink.on('click', event => {
                event.preventDefault();
                copyContent($('#userLink').val());
                return false;
            });
            toast('Посилання скопійовано', copyLink);
            let copyCode = $('#copyCode');
            copyCode.on('click', event => {
                event.preventDefault();
                copyContent($('#userCode').val());
                return false;
            });
            toast('Код скопійовано', copyCode);

            @auth
            $('#linkIcon').click(() => {
                $('#icon-preview').attr('class', $('#linkIcon option:selected').val());
            });
            $('#userEdit').on('click', event => {
                event.preventDefault();
                let formData = {
                    first_name: $('#firstName').val(),
                    last_name: $('#lastName').val(),
                };
                let newUsername = $('#username').val();
                if (newUsername !== '{{ $user->getUsername() }}') {
                    formData.username = newUsername;
                }
                $.ajax({
                    url: '{{ route('user.edit', compact('user')) }}',
                    type: "PATCH",
                    data: formData,
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
            $('#createLink').on('click', event => {
                event.preventDefault();
                $.ajax({
                    url: '{{ route('user.link') }}',
                    type: "POST",
                    data: {
                        user_id: {{ $user->getId() }},
                        link: $('#linkUrl').val(),
                        name: $('#linkName').val(),
                        icon: $('#linkIcon option:selected').val(),
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
            document.querySelector('#file').addEventListener('change', event => {
                event.preventDefault();
                let formData = new FormData();
                let $file = $('#file');
                let photo = $file.prop('files')[0];
                if (photo) {
                    formData.append('FILE', photo);
                }
                $.ajax({
                    url: '{{ route('user.edit.avatar', compact('user')) }}',
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
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
            $('.delete-link').on('click', event => {
                event.preventDefault();
                $.ajax({
                    url: '{{ route('user.link') }}' + '/' + $(event.target).attr('data-id'),
                    type: "DELETE",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function () {
                        location.reload();
                    },
                });
                return false;
            });
            @endauth
        </script>
@endsection
