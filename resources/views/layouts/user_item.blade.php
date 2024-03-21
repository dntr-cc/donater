@php /** @var App\Models\User $user */ @endphp
@php $additionalClasses = 'btn-sm'; @endphp
    <div class="row d-flex justify-content-center">
        <div class="col-4 p-2 d-flex align-items-center">
            <img src="{{ url($user->getAvatar()) }}" class="card-img-top rounded-circle img-fluid" alt="...">
        </div>
        <div class="col-4 d-flex align-items-center">
            <div class="card-body">
                <p class="card-title mb-4">
                <a href="{{ $user->getUserLink() }}">{{ $user->getFullName() }}</a>
                <a href="{{ $user->getUserLink() }}">[{{ $user->getAtUsername() }}]</a>
                </p>
            </div>
        </div>
        <div class="card-footer w-100 text-muted">
            Підписано на волонтерів: {{ $user->getSubscribersAsSubscriber()->count() }}<br>
            Зроблено донатів: {{ $user->getDonateCount() }}<br>
            Загалом задоначено з кодом донатера: {{ $user->getDonates()->allSum() }} грн.<br>
            Додано призів: {{  $user->getPrizesCount() }} шт.<br>
        </div>
    </div>
