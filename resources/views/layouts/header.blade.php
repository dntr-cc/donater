@section('header')
<header>
    <div class="px-3 py-2 bg-dark text-white">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <div class="d-flex align-items-center my-2 my-lg-0 me-lg-auto text-white text-decoration-none">
                    <a class="text-white text-decoration-none"
                       href="{{ url('/') }}">
                        <nobr>🍩 donater.com.ua</nobr>
                    </a>
{{--                    <form class="d-flex align-items-right px-3">--}}
{{--                        <input type="search" class="form-control" placeholder="Пошук донатерів" aria-label="Search">--}}
{{--                    </form>--}}
                </div>

                <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
                    <li>
                        <a href="{{ route('roadmap') }}" class="nav-link text-white">
                            <i class="bi bi-sign-turn-right-fill d-inline mx-auto mb-1"></i>
                            Roadmap
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('analytics') }}" class="nav-link text-white">
                            <i class="bi bi-pie-chart-fill d-inline mx-auto mb-1"></i>
                            Аналітика
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('raffles') }}" class="nav-link text-white">
                            <i class="bi bi-dice-5-fill d-inline mx-auto mb-1"></i>
                            Збори з призами
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('fundraising.all') }}" class="nav-link text-white">
                            <i class="bi bi-activity d-inline mx-auto mb-1"></i>
                            Всі збори
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('donates') }}" class="nav-link text-white">
                            <i class="bi bi-lightning-fill d-inline mx-auto mb-1"></i>
                            Донати
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('users') }}" class="nav-link text-white">
                            <i class="bi bi-people-fill d-inline mx-auto mb-1"></i>
                            Користувачі
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('volunteers') }}" class="nav-link text-white">
                            <i class="bi bi-star-fill d-inline mx-auto mb-1"></i>
                            Волонтери
                        </a>
                    </li>
                    @guest
                        <li>
                            <a href="{{ route('login') }}" class="nav-link text-white">
                                <i class="bi-arrow-right-short d-inline mx-auto mb-1"></i>
                                Увійти
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('my') }}" class="nav-link text-white">
                                <i class="bi bi-person d-inline mx-auto mb-1"></i>
                                Моя сторінка
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}" class="nav-link text-white"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-door-closed d-inline mx-auto mb-1"></i>
                                Вийти
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </div>
</header>
@endsection
