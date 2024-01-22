@php use Illuminate\Foundation\Application; @endphp
@section('footer')
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-4 my-4 border-top">
        <div class="container">
            <div class="col-md-12 d-flex">
                <span class="mb-3 mb-md-0 text-body-secondary">
                    ver.0.0.5.{{ config('app.version') }} © 2023 Artem Pakhomov with <a href="{{ route('volunteers') }}" class="">volunteers</a>. Work with ❤️ to Ukraine, Laravel {{ Application::VERSION }}, PHP {{ phpversion() }}, Linux Alpine, Docker, GitHub Actions</span>
            </div>
        </div>
    </footer>
@endsection
