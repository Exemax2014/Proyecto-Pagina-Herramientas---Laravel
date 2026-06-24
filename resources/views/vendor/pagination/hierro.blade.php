@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navegación de páginas" class="hf-pagination">
        <div class="hf-pagination__summary">
            @if ($paginator->firstItem())
                Mostrando {{ $paginator->firstItem() }} a {{ $paginator->lastItem() }} de {{ $paginator->total() }} compras
            @else
                Mostrando {{ $paginator->count() }} compras
            @endif
        </div>

        <div class="hf-pagination__controls">
            @if ($paginator->onFirstPage())
                <span class="hf-pagination__link hf-pagination__link--nav is-disabled" aria-disabled="true">
                    Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="hf-pagination__link hf-pagination__link--nav">
                    Anterior
                </a>
            @endif

            <div class="hf-pagination__pages">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="hf-pagination__ellipsis" aria-disabled="true">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="hf-pagination__link is-active" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="hf-pagination__link" aria-label="Ir a la página {{ $page }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="hf-pagination__link hf-pagination__link--nav">
                    Siguiente
                </a>
            @else
                <span class="hf-pagination__link hf-pagination__link--nav is-disabled" aria-disabled="true">
                    Siguiente
                </span>
            @endif
        </div>
    </nav>
@endif
