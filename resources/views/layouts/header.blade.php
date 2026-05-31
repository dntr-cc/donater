@section('header')
<header>
    <div class="px-3 py-2 bg-dark text-white">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <div class="d-flex align-items-center my-2 my-lg-0 me-lg-auto text-white text-decoration-none">
                    <a class="text-white text-decoration-none"
                       href="{{ url('/') }}">
                        <nobr>🍩 Донати за розкладом ({{ config('app.current_revision') }})</nobr>
                    </a>
                </div>
                <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
                    @if (auth()->user()?->isSuperAdmin())
                        <a href="{{ route('fundraisings') }}" class="nav-link text-white">
                            <i class="bi bi-list-task d-inline mx-auto mb-1"></i>
                            Всі збори
                        </a>
                    @endif
                    <li>
                        <a href="{{ route('analytics') }}" class="nav-link text-white">
                            <i class="bi bi-pie-chart-fill d-inline mx-auto mb-1"></i>
                            Аналітика
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('fundraising.new') }}" class="nav-link text-white">
                            <i class="bi bi-plus-circle-fill d-inline mx-auto mb-1"></i>
                            Додати збір
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('prizes') }}" class="nav-link text-white">
                            <i class="bi bi-gift d-inline mx-auto mb-1"></i>
                            Призи
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a
                            data-mdb-dropdown-init
                            class="nav-link dropdown-toggle text-white"
                            href="#"
                            id="navbarDropdown"
                            role="button"
                            aria-expanded="false"
                        >
                            <i class="bi bi-info-circle d-inline mx-auto mb-1"></i> Інформація
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a href="{{ route('faq') }}" class="dropdown-item">
                                    <i class="bi bi-question-circle-fill d-inline mx-auto mb-1"></i>
                                    Довідка
                                </a>
                            </li>
                            <li><hr class="dropdown-divider" /></li>
                            <li>
                                <a href="{{ route('roadmap') }}" class="dropdown-item">
                                    <i class="bi bi-sign-turn-right-fill d-inline mx-auto mb-1"></i>
                                    Roadmap
                                </a>
                            </li>
                            <li><hr class="dropdown-divider" /></li>
                            <li>
                                <a href="https://telegra.ph/donatercomua---podroboc%D1%96-proektu-01-23" target="_blank" class="dropdown-item">
                                    <i class="bi bi-three-dots-vertical d-inline mx-auto mb-1"></i>
                                    Опис (30хв читання)
                                </a>
                            </li>
                            <li><hr class="dropdown-divider" /></li>
                            <li>
                            <a href="{{ route('donates') }}" class="dropdown-item">
                                    <i class="bi bi-lightning-fill d-inline mx-auto mb-1"></i>
                                    Донати
                                </a>
                    </li>
                            <li><hr class="dropdown-divider" /></li>
                            <li>
                                <a href="https://t.me/donatercomua" target="_blank" class="dropdown-item">
                                    <i class="bi bi-telegram d-inline mx-auto mb-1"></i>
                                    Telegram
                                </a>
                            </li>
                            <li>
                                <a href="https://www.facebook.com/profile.php?id=61557227290492" target="_blank" class="dropdown-item">
                                    <i class="bi bi-facebook d-inline mx-auto mb-1"></i>
                                    Facebook
                                </a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/donater.com.ua" target="_blank" class="dropdown-item">
                                    <i class="bi bi-instagram d-inline mx-auto mb-1"></i>
                                    Instagram
                                </a>
                            </li>
                            <li>
                                <a href="https://www.linkedin.com/company/donatercomua" target="_blank" class="dropdown-item">
                                    <i class="bi bi-linkedin d-inline mx-auto mb-1"></i>
                                    LinkedIn
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item me-3 me-lg-0 dropdown">
                        <a
                            data-mdb-dropdown-init
                            class="nav-link dropdown-toggle text-white"
                            href="#"
                            id="navbarDropdown"
                            role="button"
                            aria-expanded="false"
                        >
                            <i class="bi-person-fill text-white"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            @guest
                                <li>
                                    <a href="{{ route('login') }}" class="dropdown-item">
                                        <i class="bi-arrow-right-short d-inline mx-auto mb-1"></i>
                                        Увійти
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('my') }}" class="dropdown-item">
                                        <i class="bi bi-person d-inline mx-auto mb-1"></i>
                                        Моя сторінка
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('logout') }}" class="dropdown-item"
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
                    </li>

                </ul>
            </div>
        </div>
    </div>
</header>
@endsection
