@php use Illuminate\Foundation\Application; @endphp
@section('footer')
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-4 my-4 border-top">
        <div class="container d-flex justify-content-between">
            <div>
                <span class="mb-9 mb-md-0 text-body-secondary">
                    ver.0.0.8.{{ config('app.version') }} © 2023 Artem Pakhomov with volunteers.
                    Work with ❤️ to Ukraine, Laravel {{ Application::VERSION }}, PHP {{ phpversion() }}, Linux Alpine,
                    Docker, <a target="_blank" href="https://github.com/dntr-cc/donater" class="">GitHub</a> and GitHub Actions
                </span>
            </div>
            <div>
                <span class="mb-3 mb-md-0 text-body-secondary">
                    <img alt="GitHub last deploy" src="https://img.shields.io/github/last-commit/dntr-cc/donater/main?style=flat&link=https%3A%2F%2Fgithub.com%2Fdntr-cc%2Fdonater&label=deployed">
                    <img alt="GitHub License" src="https://img.shields.io/github/license/dntr-cc/donater?cacheSeconds=1&link=https%3A%2F%2Fgithub.com%2Fdntr-cc%2Fdonater%2Fblob%2Fmain%2FLICENSE">
                </span>
            </div>
        </div>
    </footer>
@endsection
