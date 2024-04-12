@extends('layouts.base')
@section('page_title', 'Аналітика по всім зборам на сайті donater.com.ua')
@section('page_description', 'Сума донатів в день, кількість донатів в розрізі сум донатів тощо')
@section('og_image', url('/images/donater.com.ua.png'))
@section('og_image_width', '1200')
@section('og_image_height', '630')
@push('head-scripts')
    @vite(['resources/js/tabs.js'])
@endpush
@section('content')
    <div class="container">
        <ul class="nav nav-tabs mb-3" id="icons" role="tablist">
            <li class="nav-item" role="presentation">
                <a data-mdb-tab-init class="nav-link active" id="icons-tab-1" href="#volunteers-tab" role="tab"
                   aria-controls="volunteers-tabs" aria-selected="true">
                    <i class="bi bi-tablet-landscape-fill"></i> Виписки волонтерів
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a data-mdb-tab-init class="nav-link" id="icons-tab-2" href="#donates-tab" role="tab"
                   aria-controls="donates-tabs" aria-selected="false">
                    <i class="bi bi-lightning-fill"></i> Донатери користувачів
                </a>
            </li>
        </ul>

        <div class="tab-content" id="icons-content">
            <div class="tab-pane fade show active" id="volunteers-tab" role="tabpanel" aria-labelledby="volunteers-tab">
                <h2 class="pb-2 border-bottom">
                    Аналітика (всі донати всіх зборів на сайті)
                </h2>
                <div class="row">
                    <div class="col-12">
                        @include('layouts.analytics', [
                            'rows' => $rows,
                            'charts' => $charts,
                            'charts2' => $charts2,
                            'charts3' => $charts3,
                            'uniq' => 'volunteers',
                            'additionalAnalyticsText' => ' (всі збори на сайті donater.com.ua)',
                        ])
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="donates-tab" role="tabpanel" aria-labelledby="donates-tab">
                <h2 class="pb-2 border-bottom">
                    Аналітика (всі донати від користувачів сайту на всіх зборах на сайті)
                </h2>
                <div class="row">
                    <div class="col-12">
                        @include('layouts.analytics', [
                            'rows2' => $rows2,
                            'charts' => $chartsAll,
                            'charts2' => $charts2All,
                            'charts3' => $charts3All,
                            'uniq' => 'donates',
                            'additionalAnalyticsText' => ' (всі донати з сайту donater.com.ua)',
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        window.initMDBTab();
    </script>
@endsection
