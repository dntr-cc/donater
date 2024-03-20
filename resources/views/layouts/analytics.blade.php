@php /** @var \App\Collections\RowCollection $rows */ @endphp
@php /** @var \IcehouseVentures\LaravelChartjs\Builder $charts */ @endphp
@php /** @var \IcehouseVentures\LaravelChartjs\Builder $charts2 */ @endphp
@php /** @var \IcehouseVentures\LaravelChartjs\Builder $charts3 */ @endphp
@php $additionalAnalyticsText = $additionalAnalyticsText ?? '' @endphp

@if(!empty($rows))
    <a id="text-analytics" class="btn m-1">
        <i class="bi bi-search-heart-fill"></i>
        Аналітика текстом
    </a>
    <h3 class="mt-5 mb-2">Сума донатів в день</h3>
    <div>
        {!! $charts?->render() !!}
    </div>
    <div class="row">
        <div class="col-12">
            <h3 class="mt-5">Кількість донатів по сумі</h3>
            <div>
                {!! $charts2?->render() !!}
            </div>
        </div>
        <div class="col-12">
            <h3 class="mt-5">Сума донатів по сумі</h3>
            <div>
                {!! $charts3?->render() !!}
            </div>
        </div>
    </div>
    <script type="module">
        let analyticsText = `{{ $rows->analyticsToText($additionalAnalyticsText) }}`;
        let buttonAnalytics = $('#text-analytics');
        buttonAnalytics.on('click', event => {
            event.preventDefault();
            copyContent(analyticsText);
            return false;
        });
        toast('Текст аналітики скопійовано', buttonAnalytics);
    </script>
@else
    <h6>Google Spreadsheet Api повернуло помилку. Повторіть пізніше.</h6>
@endif
