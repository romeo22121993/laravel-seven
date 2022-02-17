<div class="sidebar-widget product-tag wow fadeInUp">
    <h3 class="section-title">Product tags</h3>
    <div class="sidebar-widget-body outer-top-xs">
        <div class="tag-list">

            @if(session()->get('language') == 'hindi')

                @foreach($tags_hin as $tag)
                    @php
                        $class = ( $chosen_tag == $tag ) ? "active" : "";
                    @endphp
                    <a class="item {{ $class }}" title="Phone" href="{{ url('product/tag/'.$tag) }}">
                        {{ str_replace(',',' ',$tag)  }}
                    </a>

                @endforeach
            @else

                @foreach($tags_en as $tag)
                    @php
                        $class = ( $chosen_tag == $tag ) ? "active" : "";
                    @endphp
                    <a class="item {{ $class }}" title="Phone" href="{{ url('product/tag/'.$tag) }}">
                        {{ str_replace(',',' ',$tag)  }}
                    </a>

                @endforeach
            @endif

        </div>
    </div>
</div>
