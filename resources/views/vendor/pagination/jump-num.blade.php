@if ($paginator->hasPages())
    {{-- 增加输入框，跳转任意页码和显示任意条数 --}}
    <ul class="pagination no-padding" style="margin: 0 0 0 10px !important;">



    </ul>

    <ul id="page" class="pagination" role="navigation">

        <li>
            <span id="jump-input-wrap" style="padding: 5px 8px; margin-right: 10px;" data-toggle="tooltip" data-placement="bottom" title="输入页码，按回车快速跳转">
                第 <input id="jump-input" type="number" class="text-center no-padding radius" value="{{ $paginator->currentPage() }}" data-total-page="{{ $paginator->lastPage() }}" style="width: 50px;" onkeypress="clickEnter(event)" onkeydown="clickEnter()"> 页 / 共 {{ $paginator->lastPage() }} 页
            </span>

            <a id="jump-btn" class="hide" href="{{ $paginator->url(1) }}"></a>
        </li>

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <span aria-hidden="true">&lsaquo;</span>
            </li>
        @else
            <li>
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
            </li>
        @else
            <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span aria-hidden="true">&rsaquo;</span>
            </li>
        @endif
    </ul>

    <script type="text/javascript" src="{{ asset('script/jquery/1.9.1/jquery.min.js') }}"></script>
    <script type="text/javascript">

        $('#jump-input').on('input propertychange', function () {
            var page = $(this).val()

            if (page) {
                if (page < 1 || page > {{$paginator->lastPage()}}) {
                    $(this).val({{ $paginator->currentPage() }})

                    page = {{ $paginator->currentPage() }}
                }
            }

            var before_page_href = $('#jump-btn').attr('href')

            var after_page_href = before_page_href.replace(/page=\d+/, "page=" + page)

            console.log(after_page_href);

            $('#jump-btn').attr('href', after_page_href)
        })

        function clickEnter (e){
            var e = e || window.event

            if(e.keyCode === 13){
                var jump_btn = document.getElementById('jump-btn')

                jump_btn.click()
            }
        }

    </script>

@endif
