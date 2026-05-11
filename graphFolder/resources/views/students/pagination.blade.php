@if ($paginator->hasPages())

<style>
.pagination{
    display:flex;
    justify-content:center;
    align-items:center;
    gap:10px;
    flex-wrap:wrap;
    margin-top:35px;
}

.page-btn{
    min-width:44px;
    height:44px;
    padding:0 14px;
    border-radius:14px;
    background:white;
    color:#334155;
    border:1px solid #e2e8f0;
    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    font-weight:600;
    transition:all .25s ease;
    box-shadow:0 4px 10px rgba(15,23,42,.05);
}

.page-btn:hover{
    background:#6366f1;
    color:white;
    border-color:#6366f1;
    transform:translateY(-2px);
}

.page-btn.active{
    background:linear-gradient(135deg,#6366f1,#8b5cf6);
    color:white;
    border-color:transparent;
    box-shadow:0 10px 20px rgba(99,102,241,.25);
}

.page-btn.disabled{
    opacity:.4;
    pointer-events:none;
}
</style>

<nav class="pagination">

    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span class="page-btn disabled">‹</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="page-btn">‹</a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)

        @if (is_string($element))
            <span class="page-btn disabled">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)

                @if ($page == $paginator->currentPage())
                    <span class="page-btn active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif

            @endforeach
        @endif

    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="page-btn">›</a>
    @else
        <span class="page-btn disabled">›</span>
    @endif

</nav>
@endif