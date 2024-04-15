@php $rows = \App\Models\Fundraising::getAllRows(); @endphp
<div class="m-3"></div>
<div class="stat-slider swiper p-2">
    <div class="blog-slider">
        <div class="blog-slider__wrp swiper-wrapper">
            <div class="blog-slider__item swiper-slide">
                <div class="blog-slider__img">
                    <img src="{{ url('/images/swiper/photo1.jpeg') }}" alt="">
                </div>
                <div class="blog-slider__content">
                    <div class="blog-slider__title">
                        {{ \App\Models\User::all()->count() }} користувачів спробували нашу платформу.
                    </div>
                    <div class="blog-slider__title">
                        {{ \App\Models\User::query()->where('forget', '=', false)->get()->count() }} продовжують користуватися.
                    </div>
                </div>
            </div>

            <div class="blog-slider__item swiper-slide">
                <div class="blog-slider__img">
                    <img src="{{ url('/images/swiper/photo2.jpeg') }}" alt="">
                </div>
                <div class="blog-slider__content">
                    <div class="blog-slider__title">
                        {{ Illuminate\Support\Number::currency(
                            $rows->sum(fn(\App\DTOs\Row $row) => $row->getAmount() > 0 ? round((float)$row->getAmount(), 2) : 0.00),
                            'UAH',
                        ) }} показано в виписках волонтерів з {{ \App\Models\Fundraising::count() }} зборів. Де {{ Illuminate\Support\Number::currency(
                            $rows->sum(fn(\App\DTOs\Row $row) => $row->getAmount() > 0 && strtotime($row->getDate()) > strtotime('-31 day') ?
                                round((float)$row->getAmount(), 2) :
                                0.00
                            ),
                            'UAH',
                        ) }} за крайні 30 днів
                    </div>
                    <div class="blog-slider__title">
                        {{ Illuminate\Support\Number::currency(\App\Models\Donate::sum('amount')) }} задонатили
                        користувачі сайту, Де {{ Illuminate\Support\Number::currency(
                            \App\Models\Donate::query()->where('created_at', '=', date('Y-m-d H:i:s', strtotime('-31 day')))->sum('amount'),
                            'UAH',
                        ) }} за крайні 30 днів
                    </div>
                </div>
            </div>

            <div class="blog-slider__item swiper-slide">
                <div class="blog-slider__img">
                    <img src="{{ url('/images/swiper/photo3.jpeg') }}" alt="">
                </div>
                <div class="blog-slider__content">
                    <div class="blog-slider__title">
                        Користувачі створили {{ \App\Models\Subscribe::withoutTrashed()->count() }} підписок на суму
                        {{ Illuminate\Support\Number::currency(
                            \App\Models\Subscribe::withoutTrashed()->get()->sum(fn(\App\Models\Subscribe $item) => round($item->getAmount(), 2)),
                            'UAH',
                        ) }}
                    </div>
                    <div class="blog-slider__title">
                        {{ \App\Models\Subscribe::withoutTrashed()->where('frequency', '=', 'daily')->count() }} з них це
                        щоденний донат на суму {{ Illuminate\Support\Number::currency(
                            \App\Models\Subscribe::withoutTrashed()->where('frequency', '=', 'daily')
                            ->get()->sum(fn(\App\Models\Subscribe $item) => round($item->getAmount(), 2)),
                            'UAH',
                        ) }}.
                    </div>
                </div>
            </div>

        </div>
        <div class="blog-slider__pagination"></div>
    </div>
</div>
<div class="m-3"></div>
<script type="module">
    let swiper = new window.Swiper('.blog-slider', {
        spaceBetween: 400,
        effect: 'fade',
        loop: true,
        mousewheel: {
            invert: false,
        },
        pagination: {
            el: '.blog-slider__pagination',
            clickable: true,
            type: 'bullets',
        },
        autoplay: {
            delay: 5000,
            disableOnInteraction: true,
        }
    });
</script>
