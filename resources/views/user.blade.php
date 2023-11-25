@php use App\Models\Donate; @endphp
@php use App\Models\User; @endphp
@php use App\Models\UserLink; @endphp
@php use App\Models\Volunteer; @endphp
@php /** @var User $user */ @endphp
@php /** @var Donate $donate */ @endphp
@php /** @var Volunteer $volunteer */ @endphp
@php /** @var UserLink $link */ @endphp
@php $withZvitLink = true; @endphp
@php $additionalClasses = 'btn-xs'; @endphp
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
                                     @if (auth()?->user()?->getId() === $user->getId())
                                         data-bs-toggle="modal" data-bs-target="#userEditModal"
                                     @endif
                                     alt="avatar"
                                     class="rounded-circle img-fluid" style="width: 150px;">
                                <h3 class="my-3">{{ $user->getFullName() }}</h3>
                                @if ($user->isPremium())
                                    <button type="button" title="Telegram Premium"
                                            class="btn btn-lg btn-outline-golden rounded-pill position-relative">
                                        <i class="bi bi-telegram golden icon-size-x-large"></i>
                                    </button>
                                @endif
                                @if ($user->getApprovedDonateCount())
                                    <button type="button" title="Завалідовані донати"
                                            class="btn btn-lg btn-secondary-outline-light rounded-pill position-relative">
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                            {{ $user->getApprovedDonateCount() }}
                                            <span class="visually-hidden">завалідовані донати</span>
                                        </span>
                                    </button>
                                @endif
                                @if ($user->getNotValidatedDonatesCount())
                                    <button type="button" title="Очікують валідації"
                                            class="btn btn-lg btn-outline-secondary-light rounded-pill position-relative">
                                        <i class="bi bi-clock-fill text-success-light"></i>
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                                            {{ $user->getNotValidatedDonatesCount() }}
                                            <span class="visually-hidden">Очікують валідації</span>
                                        </span>
                                    </button>
                                @endif
                                <h4 class="m-3 text-muted">{{ $user->getAtUsername() }}</h4>
                                <div class="d-flex justify-content-center mb-2">
                                    <div class="form-floating input-group">
                                        <input type="text" class="form-control" id="userLink"
                                               value="{{ $user->getUserLink() }}" disabled>
                                        <label for="userLink">Посилання на цю сторінку</label>
                                        <button id="copyLink" class="btn btn-outline-secondary" onclick="return false;">
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
                                        @if (auth()?->user()?->getId() === $user->getId())
                                            <button data-bs-toggle="modal" data-bs-target="#createLinkModal"
                                                    id="addDonation" class="btn btn-secondary-outline">
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
                                            <a href="{{route('volunteer.new')}}" class="btn btn-secondary-outline">
                                                <i class="bi bi-plus-circle-fill"></i>
                                                Створити
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                @foreach($user->getVolunteers() as $it => $volunteer)
                                <div class="row">
                                    <div class="col-sm-12 d-flex justify-content-between align-items-start volunteer"
                                        data-volunteer="{{ $volunteer->getKey() }}">
                                        <div class="fw-bold">
                                            {{ $volunteer->getName() }}
                                            @include('layouts.links', compact('volunteer', 'withZvitLink', 'additionalClasses'))
                                        </div>
                                    </div>
                                </div>
                                    <hr>
                                @endforeach

                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-body">
                                <ul class="list-group list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="me-auto mt-auto">
                                            <h4>Благодійні внески</h4>
                                        </div>
                                    </li>
                                    @foreach($user->getDonates() as $it => $donate)
                                        <li class="list-group-item d-flex justify-content-between align-items-start">
                                            <a href="{{ route('volunteer.show', ['volunteer' => $donate->getVolunteer()]) }}"
                                               class="ms-2 me-auto text-decoration-none" style="color: rgba(var(--bs-body-color-rgb),var(--bs-text-opacity, 1))">
                                                <div class="fw-bold">{{ $donate->getHumanType() }}</div>
                                                Код внеску {{ $donate->getUniqHash() }}.
                                                Створено {{ $donate->getCreatedAt()->format('Y-m-d H:i:s') }}.
                                                @if($donate->isValidated())
                                                    Завалідовано {{ $donate->getValidatedAt()->format('Y-m-d H:i:s') }}
                                                    .
                                                @endif
                                            </a>
                                            @if($donate->isValidated())
                                                <span class="badge text-bg-success ">
                                                    Завалідовано
                                                    <i class="bi bi-check-circle-fill text-bg-success"></i>
                                                </span>
                                            @else
                                                <span class="badge text-bg-secondary rounded-pill">
                                                    очікує на валідацію
                                                    <i class="bi bi-clock text-bg-secondary"></i>
                                                </span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
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
                                @csrf
                                <div class="form-floating py-1">
                                    <input type="text" class="form-control" maxlength="180" name="linkUrl" id="linkUrl"
                                           required>
                                    <label for="linkUrl">
                                        Посилання
                                    </label>
                                </div>
                                <div class="form-floating py-1">
                                    <input type="text" class="form-control" maxlength="20" name="linkName" id="linkName"
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
                            <p id="linkError" style="display: none;" class="lead fw-bold text-danger"></p>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-secondary justify-content-evenly"
                                    data-bs-dismiss="modal">
                                Закрити
                            </button>
                            <button id="createLink" type="submit" class="btn btn-primary">Зберігти</button>
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
                                @csrf
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
                                    <input type="text" class="form-control" maxlength="50" name="username" id="username"
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
                                    <input type="text" class="form-control" maxlength="50" name="lastName" id="lastName"
                                           value="{{ $user->getLastName() }}">
                                    <label for="lastName">
                                        Прізвище
                                    </label>
                                </div>
                            </div>
                            <p id="userEditError" style="display: none;" class="lead fw-bold text-danger"></p>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-secondary justify-content-evenly"
                                    data-bs-dismiss="modal">
                                Закрити
                            </button>
                            <button id="userEdit" type="submit" class="btn btn-primary">Зберігти</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endauth
    <script type="module">
        let copyLink = $('#copyLink');
        copyLink.on('click', function (e) {
            e.preventDefault();
            copyContent($('#userLink').val());
            return false;
        });
        toast('Посилання скопійовано', copyLink);

        @auth
        $('#linkIcon').click(() => {
            $('#icon-preview').attr('class', $('#linkIcon option:selected').val());
        });
        $('#userEdit').on('click', function (e) {
            e.preventDefault();
            let formData = {
                first_name: $('#firstName').val(),
                last_name: $('#lastName').val(),
            };
            let newUsername = $('#username').val();
            if (newUsername !== '{{ $user->getUsername() }}') {
                formData.username = newUsername;
            }
            $.ajax({
                url: '{{ url('/user') }}' + '/{{ $user->getUsername() }}',
                type: "PATCH",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $(`[name="_token"]`).val()
                },
                success: function () {
                    location.reload();
                },
                error: function (data) {
                    $('#userEditError').html(JSON.parse(data.responseText).message).show();
                },
            });
            return false;
        });
        $('#createLink').on('click', (event) => {
            event.preventDefault();
            $.ajax({
                url: '{{ route('user.link') }}',
                type: "POST",
                data: {
                    _token: $(`#createLinkModal [name="_token"]`).val(),
                    user_id: <?= auth()?->user()?->getId() ?>,
                    link: $('#linkUrl').val(),
                    name: $('#linkName').val(),
                    icon: $('#linkIcon option:selected').val(),
                },
                success: function () {
                    location.reload();
                },
                error: function (data) {
                    $('#linkError').html(JSON.parse(data.responseText).message).show();
                },
            });
            return false;
        });
        document.querySelector('#file').addEventListener('change', (event) => {
            event.preventDefault();
            let formData = new FormData();
            let $file = $('#file');
            let photo = $file.prop('files')[0];
            if (photo) {
                formData.append('FILE', photo);
            }
            $.ajax({
                url: '{{ url('/user') }}' + '/{{ $user->getUsername() }}' + '/avatar',
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function () {
                    location.reload();
                },
                error: function (data) {
                    $('#userEditError').html(JSON.parse(data.responseText).message).show();
                },
            });
            return false;
        });
        $('.delete-link').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('user.link') }}' + '/' + $(this).attr('data-id'),
                type: "DELETE",
                data: {
                    _token: $(`[name="_token"]`).val(),
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
