@if ($paginator->hasPages())
    <?php $elements = (new \Illuminate\Pagination\UrlWindow($paginator))->get(); ?>

    <nav class="pager" role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        @if ($paginator->onFirstPage())
            <span class="is-disabled" aria-hidden="true">&laquo;</span>
        @else
            <button type="button" wire:click="previousPage" wire:loading.attr="disabled" aria-label="{{ __('Previous') }}">&laquo;</button>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="is-disabled">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="is-current">{{ $page }}</span>
                    @else
                        <button type="button" wire:click="gotoPage({{ $page }})">{{ $page }}</button>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <button type="button" wire:click="nextPage" wire:loading.attr="disabled" aria-label="{{ __('Next') }}">&raquo;</button>
        @else
            <span class="is-disabled" aria-hidden="true">&raquo;</span>
        @endif
    </nav>
@endif
