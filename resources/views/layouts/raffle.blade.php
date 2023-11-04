@php use App\Models\Fundraising; @endphp
@php use App\Models\Prize; @endphp
@php /** @var Fundraising $fundraising */@endphp
@php /** @var Prize $prize */@endphp
@php $type =  $prize->getRaffleType(); @endphp
@php $price =  $prize->getRafflePrice(); @endphp
@php $winners = $prize->getRaffleWinners(); @endphp
@php $raffleUserCollection = $fundraising->rafflesPredictCollection(); @endphp

<div class="card m-2">
    <div class="row no-gutters">
        <div class="col-auto">
            <img src="{{ url($prize->getAvatar()) }}" class="img-fluid" style="max-width: 200px" alt="">
        </div>
        <div class="col">
            <div class="card-block px-2">
                <h4 class="card-title mt-2">{{ $prize->getName() }}</h4>
                <p class="card-text">Створив: {!! $prize->getDonater()->getUserHref() !!}</p>
                <a href="{{ route('prize.show', ['prize' => $prize->getId()])}}"
                   class="btn btn-xs m-1">
                    <i class="bi bi-eye"></i>
                    Подробиці
                </a>
            </div>
        </div>
    </div>
    <div class="card-footer w-100 text-muted">
        Умови: {{ \App\DTOs\RaffleUser::TYPES[$prize->getRaffleType() ?? ''] ?? ''}}.
        Переможців: {{ $prize->getRaffleWinners() }}. Ціна квитка (якщо треба): {{ $prize->getRafflePrice() }}
    </div>
</div>

@include('fundraising.predict', compact('fundraising', 'raffleUserCollection', 'type', 'price', 'winners'))

