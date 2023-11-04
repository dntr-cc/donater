@section('main')
<main class="py-4 flex-shrink-0">
    <div class="container">
        @yield('content')
    </div>
    <div id="toasts" class="toast-container position-fixed bottom-0 top-0 end-0 pt-2">
        <div id="toast" class="toast align-items-center text-bg-success border-0"
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
