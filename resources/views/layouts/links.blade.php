@php /** @var App\Models\Fundraising $fundraising */ @endphp
@php /** @var bool $withZvitLink */ @endphp
@php $withZvitLink = $withZvitLink ?? false; @endphp
@php $withJarLink = $withJarLink ?? false; @endphp
@php $withPageLink = $withPageLink ?? false; @endphp
@php $additionalClasses = $additionalClasses ?? ''; @endphp
@php $raffles = $raffles ?? false; @endphp

@if($withJarLink)
    <a href="{{ $fundraising->getLink() }}" target="_blank" class="btn  m-1 {{ $additionalClasses }}">
        <i class="bi bi-bank"></i>
        Банка</a>
@endif
@if($withPageLink)
    <a href="{{ $fundraising->getPage() }}" target="_blank" class="btn  m-1 {{ $additionalClasses }}">
        <i class="bi bi-house-door-fill"></i>
        Сторінка збору
    </a>
@endif
@if($fundraising->isEnabled())
    <a href="{{route('donate', ['fundraising' => $fundraising->getKey()])}}" class="btn  m-1 {{ $additionalClasses }}">
        <i class="bi bi-plus-circle-fill"></i>
        Задонатити
    </a>
@endif
@if($withZvitLink)
    <a href="{{route('fundraising.show', ['fundraising' => $fundraising->getKey()])}}"
       class="btn  m-1 {{ $additionalClasses }}">
        <i class="bi bi-info-square-fill"></i>
        Звіт
    </a>
@endif
@if(request()->user()?->can('update', $fundraising))
    <a href="{{route('fundraising.edit', ['fundraising' => $fundraising->getKey()])}}"
       class="btn  m-1 {{ $additionalClasses }}">
        <i class="bi bi-pencil-fill"></i>
        Редагування
    </a>
    {{--    @if($raffles)--}}
    {{--        <a href="{{route('fundraising.raffle', ['fundraising' => $fundraising->getKey()])}}" class="btn  m-1 {{ $additionalClasses }}">--}}
    {{--            <i class="bi bi-dice-3-fill"></i>--}}
    {{--            Розіграш--}}
    {{--        </a>--}}
    {{--    @endif--}}
    @if($fundraising->isEnabled())
        <a href="{{route('fundraising.stop', ['fundraising' => $fundraising->getKey()])}}"
           class="btn  m-1 {{ $additionalClasses }}">
            <i class="bi bi-arrow-down-circle-fill"></i>
            Зупинити
        </a>
    @else
        <a href="{{route('fundraising.start', ['fundraising' => $fundraising->getKey()])}}"
           class="btn  m-1 {{ $additionalClasses }}">
            <i class="bi bi-arrow-up-circle-fill"></i>
            Розпочати
        </a>
    @endif
@endif
