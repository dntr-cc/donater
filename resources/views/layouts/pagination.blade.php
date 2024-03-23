@php use Illuminate\Pagination\CursorPaginator; @endphp
@php /** @var CursorPaginator $paginator */ @endphp

@if ($paginator->hasPages())
    <nav class="d-flex justify-items-center">
        <div class="container">
            <div class="row">
                <div class="col-12 flex-sm-fill d-sm-flex align-items-sm-center justify-content-center mt-4">
                    <p class="small text-muted">
                        {!! __('Показано') !!}
                        <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                        {!! __('по') !!}
                        <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                        {!! __('з') !!}
                        <span class="fw-semibold">{{ $paginator->total() }}</span>
                        {!! __('результатів') !!}
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-12 flex-sm-fill d-sm-flex align-items-sm-center justify-content-center mt-4">
                    <div>
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($paginator->onFirstPage())
                                <li class="d-none page-item disabled" aria-disabled="true"
                                    aria-label="{!! __('Попередня') !!}">
                                    <span class="page-link" aria-hidden="true">&lsaquo;</span>
                                </li>
                            @else
                                <li class="d-none page-item">
                                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                                       aria-label="{!! __('Попередня') !!}">&lsaquo;</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @php
                            $count = count($elements[2] ?? []);
                            if ($count > 6) {
                                $count2 = $count3 = (int)($count / 2);
                                foreach ($elements[2] as $key => $element) {
                                    --$count;
                                    --$count2;
                                    if ($count2 > 0) {
                                        unset($elements[2][$key]) ;
                                    }
                                    if ($count < $count3 - 1) {
                                        unset($elements[2][$key]) ;
                                    }
                                }
                            }
                            @endphp
                            @foreach ($elements as $element)
                                {{-- "Three Dots" Separator --}}
                                @if (is_string($element))
                                    <li class="page-item disabled" aria-disabled="true"><span
                                            class="page-link">{{ $element }}</span></li>
                                @endif

                                {{-- Array Of Links --}}
                                @if (is_array($element))
                                    @foreach ($element as $page => $url)
                                        @if ($page == $paginator->currentPage())
                                            <li class="page-item active" aria-current="page"><span
                                                    class="page-link">{{ $page }}</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link"
                                                                     href="{{ $url }}">{{ $page }}</a></li>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($paginator->hasMorePages())
                                <li class="d-none page-item">
                                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"
                                       aria-label="{!! __('Наступна') !!}">&rsaquo;</a>
                                </li>
                            @else
                                <li class="d-none page-item disabled" aria-disabled="true"
                                    aria-label="{!! __('Наступна') !!}">
                                    <span class="page-link" aria-hidden="true">&rsaquo;</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
@endif
