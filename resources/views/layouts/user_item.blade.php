@php /** @var App\Models\User $user */ @endphp
@php $additionalClasses = 'btn-sm'; @endphp
<div class="row row-cols-2 d-flex justify-content-evenly g-1 m-2">
    <img src="{{ url($user->getAvatar()) }}" width="150px" class="object-fit-cover-150 card-img-top rounded-circle img-fluid" alt="{{ $user->getFullName() }}">
    <div class="card-body">
        <h6 class="card-title mb-4"><strong>{{ ucfirst(sensitive('донатер', $user)) }}</strong>
            <a href="{{ $user->getUserLink() }}">{{ $user->getFullName() }}</a>
            <a href="{{ $user->getUserLink() }}">[{{ $user->getAtUsername() }}]</a>
        </h6>
    </div>
    <div class="card-footer w-100 text-muted">
        Підписано на волонтерів: {{ $user->getSubscribersAsSubscriber()->count() }}<br>
        Зроблено донатів: {{ $user->getDonateCount() }}<br>
        Задоначено через сайт: {{ $user->getDonatesSumAll() }} грн.<br>
        Додано призів: {{  $user->getPrizesCount() }} шт.<br>
    </div>
</div>
