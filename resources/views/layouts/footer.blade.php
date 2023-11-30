@section('footer')
<footer class="d-flex flex-wrap justify-content-between align-items-center py-4 my-4 border-top">
    <div class="container">
        <div class="col-md-12 d-flex">
        <div class="col-md-8">
            <span class="mb-3 mb-md-0 text-body-secondary">ver.0.0.4.{{ config('app.version') }} © 2023 Artem Pakhomov with DOU community</span>
        </div>
        <div class="col-md-4">
            <span class="mb-3 mb-md-0 text-body-secondary">Work with ❤️ to Ukraine, Laravel {{ \Illuminate\Foundation\Application::VERSION }}, PHP {{ phpversion() }}, Alpine 3.18</span>
        </div>
        </div>
    </div>
</footer>
@endsection
