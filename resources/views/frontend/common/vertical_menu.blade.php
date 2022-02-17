<div class="side-menu animate-dropdown outer-bottom-xs">
    <div class="head"><i class="icon fa fa-align-justify fa-fw"></i> Categories</div>
    <nav class="yamm megamenu-horizontal">
        <ul class="nav">
            @foreach($categories as $category)
                <li class="dropdown menu-item">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon fa fa-shopping-bag" aria-hidden="true"></i>
                        @if(session()->get('language') == 'hindi') {{ $category->category_name_hin }} @else {{ $category->category_name_en }} @endif
                    </a>
                    <ul class="dropdown-menu mega-menu">
                        <li class="yamm-content">
                            <div class="row">
                                @php
                                    $subcategories = App\Models\Category::where('category_id', $category->id)->orderBy('category_name_en','ASC')->where('subcategory_id',0)->get();
                                @endphp
                                @foreach($subcategories as $subcategory)
                                    @php
                                        $link = (session()->get('language') == 'hindi') ? $subcategory->category_slug_hin : $subcategory->category_slug_en;
                                    @endphp
                                    <div class="col-sm-12 col-md-3">
                                        <h2 class="title">
                                            <a href="/category/{{  $subcategory->id }}/{{ $link }}" style="padding: 0;">
                                                @if(session()->get('language') == 'hindi') {{ $subcategory->category_name_hin }} @else {{ $subcategory->category_name_en }} @endif
                                            </a>
                                        </h2>
                                        @php
                                            $subsubcategories = App\Models\Category::where('subcategory_id',$subcategory->id)->orderBy('category_name_en','ASC')->get();
                                        @endphp
                                        @foreach($subsubcategories as $subsubcategory)
                                            @php
                                                $link = (session()->get('language') == 'hindi') ? $subsubcategory->category_slug_hin  :  $subsubcategory->category_slug_en;
                                            @endphp
                                            <ul class="links list-unstyled">
                                                <li>
                                                    <a href="/subcategory/{{ $subsubcategory->id }}/{{ $link }}">
                                                        @if(session()->get('language') == 'hindi') {{ $subsubcategory->category_name_hin }} @else {{ $subsubcategory->category_name_en }} @endif
                                                    </a>
                                                </li>
                                            </ul>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                            <!-- /.row -->
                        </li>
                    </ul>
                </li>
            @endforeach
        </ul>
        <!-- /.nav -->
    </nav>
    <!-- /.megamenu-horizontal -->
</div>
