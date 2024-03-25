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
                <p class="card-text">{{ \Illuminate\Support\Str::ucfirst(sensitive('створив', $prize->getDonater())) }}: {!! $prize->getDonater()->getUserHref() !!}</p>
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

@include('fundraising.predict', compact('fundraising', 'prize', 'raffleUserCollection', 'type', 'price', 'winners'))

@if(auth()?->user()?->can('update', $prize))
    @if($fundraising->isEnabled())
        Кнопка провести розіграш з'явиться після зупинки збору. Зачекайте крайню виписку, щоб всі донатори взяли участь.
    @else
        @if($prize->isEnabled())
        <div class="m-2">
            <div class="m-3 lead text-danger" id="raffle-warning0{{ $prize->getId() }}">
                Натиснув провесті розіграш сайт проведе розіграш призу "{{ $prize->getName() }}".
            </div>
            <div class="m-3 lead text-danger" id="raffle-warning1{{ $prize->getId() }}">
                Перед цим переконайтеся, що волонтер/ка додал/а КРАЙНЮ виписку, та в виписці є всі учасники.
            </div>
            <button id="get_winners_{{ $prize->getId() }}" class="btn btn-outline-danger">Провести розіграш</button>
            <div class="mt-3 lead" id="show_raffle_result_{{ $prize->getId() }}"></div>
        </div>
        <script type="module">
            let winners = $('#get_winners_{{ $prize->getId() }}');
            winners.on('click', event => {
                event.preventDefault();
                winners.attr('disabled', true);
                $('#raffle-warning0{{ $prize->getId() }}').remove();
                $('#raffle-warning1{{ $prize->getId() }}').remove();
                $.ajax({
                    url: '{{ route('prize.raffle', compact('prize')) }}',
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: data => {
                        if (data.html && data.csrf) {
                            $('#show_raffle_result_{{ $prize->getId() }}').html(data.html);
                            $('meta[name="csrf-token"]').attr('content', data.csrf);
                        } else {
                            let empty = $("<a>");
                            toast('Щось геть пішло не так, пішіть @setnemo в телеграм', empty, 'text-bg-danger');
                            empty.click();
                            winners.attr('disabled', false);
                        }
                    },
                    error: data => {
                        let empty = $("<a>");
                        toast(JSON.parse(data.responseText).message, empty, 'text-bg-danger');
                        empty.click();
                        winners.attr('disabled', false);
                    },
                });
                return false;
            });
        </script>
        @endif
    @endif
@endif
