@php /** @var App\Models\Fundraising $fundraising */ @endphp
@php $withOwner = $withOwner ?? false; @endphp
@php $additionalClasses = $additionalClasses ?? ''; @endphp
@php $owner = $fundraising->owner()->first(); @endphp
@php $href = route('fundraising.show', ['fundraising' => $fundraising->getKey()]); @endphp
@if($fundraising->isEnabled())
    <a href="{{ $href }}" class="btn btn-success {{ $additionalClasses }}">ЗБІР ТРИВАЄ</a>
@elseif($fundraising->donates->count())
    <a href="{{ $href }}" class="btn btn-outline-dark {{ $additionalClasses }}">ЗБІР ЗАКРИТО</a>
@else
    <a href="{{ $href }}" class="btn btn-secondary {{ $additionalClasses }}">СКОРО РОЗПОЧНЕТЬСЯ</a>
@endif
{{ $fundraising->getName() }}.@if($withOwner) Збирає <a href="{{ $owner->getUserLink() }}">{{ $owner->getFullName() }} [{{ $owner->getAtUsername() }}]</a>@endif
