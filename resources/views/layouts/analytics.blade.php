@php /** @var \App\Collections\RowCollection $rows */ @endphp
@php /** @var \IcehouseVentures\LaravelChartjs\Builder $charts */ @endphp
@php /** @var \IcehouseVentures\LaravelChartjs\Builder $charts2 */ @endphp
@php /** @var \IcehouseVentures\LaravelChartjs\Builder $charts3 */ @endphp

@if($rows)
    <h3 class="mt-5 mb-2">Сума донатів в день</h3>
    <div>
        {!! $charts->render() !!}
    </div>
    <h3 class="mt-5 mb-2">Кількість донатів по сумі</h3>
    <div>
        {!! $charts2->render() !!}
    </div>
    <h3 class="mt-5 mb-2">Сума донатів по сумі</h3>
    <div>
        {!! $charts3->render() !!}
    </div>
@else
    <h6>Google Spreadsheet Api повернуло помилку. Повторіть пізніше.</h6>
@endif
