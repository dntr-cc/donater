@section('main')
<main class="py-4 flex-shrink-0">
    <div class="container">
        @if (!in_array(request()?->route()?->getName(), config('app.excluded_breadcrumbs')))
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Головна</a></li>
                @yield('breadcrumb-path')
                <li class="breadcrumb-item active" aria-current="page">@yield('breadcrumb-current', '')</li>
            </ol>
        </nav>
        @endif
        @yield('content')
    </div>
    <div id="toasts" class="toast-container position-fixed bottom-0 top-0 end-0 pt-2">
        <div id="toast" class="toast align-items-center border-0"
             role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toastText toast-body"></div>
                <button type="button" id="closeToast"
                        class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
</main>
@endsection
