<h3 class="section-title">shop by</h3>
<div class="widget-header">
    <h4 class="widget-title">Category</h4>
</div>
<div class="sidebar-widget-body">
    <div class="accordion">
        @foreach($categories as $category)
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a href="#collapse{{ $category->id }}" data-toggle="collapse" class="accordion-toggle collapsed">
                        @if(session()->get('language') == 'hindi') {{ $category->category_name_hin }} @else {{ $category->category_name_en }} @endif
                    </a>
                </div>
                <div class="accordion-body collapse" id="collapse{{ $category->id }}" style="height: 0px;">
                    <div class="accordion-inner">

                        @php
                            $subcategories = App\Models\Category::where('category_id', $category->id)->orderBy('category_name_en','ASC')->get();
                        @endphp
                        @foreach($subcategories as $subcategory)
                            <ul>
                                <li>
                                    <a href=#collapse">
                                        @if(session()->get('language') == 'hindi') {{ $subcategory->category_name_hin }} @else {{ $subcategory->category_name_en }} @endif
                                    </a>
                                </li>
                            </ul>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
