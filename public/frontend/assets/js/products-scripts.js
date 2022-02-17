jQuery(document).ready(function() {
    "use strict";

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        }
    })


    /**
     * Function for products
     *
     */

    $("#search").on("focus", function ( e ) {
        console.log('focus');
        e.preventDefault();
        search_result_show();
    })

    $("#search").on("blur", function ( e ) {
        e.preventDefault();
        console.log('blur');
        search_result_hide();
    })

    function search_result_hide(){
        $("#searchProducts").slideUp();
    }

    function search_result_show(){
        $("#searchProducts").slideDown();
    }

    /**
     * Sesrching by key up on search input
     *
     */
    $("body").on("keyup", "#search", function(){

        let text = $("#search").val();
        if (text.length > 1) {

            $.ajax({
                data: {search: text},
                url : "/ajax/search-product",
                method : 'post',
                beforSend : function(request){
                    return request.setReuestHeader('X-CSRF-Token',("meta[name='csrf-token']"))
                },
                success:function(result){
                    $("#searchProducts").html(result);
                }

            });

        }

        if (text.length < 1 ) $("#searchProducts").html("");

    });


    $(".add_to_cart_btn, .addToCartBtn").on("click", function ( e ) {
        e.preventDefault();
        addToCart();
    })

    $(".WishlistBtn").on("click", function ( e ) {
        e.preventDefault();
        let id = $(this).attr('id');
        addToWishList(id);
    })

    $(".wishlistRemoveBtn").on("click", function ( e ) {
        e.preventDefault();
        let id = $(this).attr('id');
        wishlistRemove(id)
    })

    $(document).on("click", ".cartRemoveBtn", function ( e ) {
        e.preventDefault();
        let id = $(this).attr('id');
        cartRemove(id);
        $(this).parents('tr').remove();
    })

    $(document).on("click", ".cartIncrementBtn", function ( e ) {
        e.preventDefault();
        let id = $(this).attr('id');
        cartIncrement(id);
    })

    $(document).on("click", ".cartDecrementBtm", function ( e ) {
        e.preventDefault();
        let id = $(this).attr('id');
        cartDecrement(id);
    })

    $(".productViewBtn").on("click", function ( e ) {
        e.preventDefault();
        let id = $(this).attr('id');
        productView(id);
    })

    $(document).on("click",  ".miniCartRemove", function ( e ) {
        e.preventDefault();
        let id = $(this).attr('id');
        miniCartRemove(id);
    })

    /**
     * Function getting ajax data by product id
     *
     * @param id
     */
    function productView(id){
        // alert(id)
        $.ajax({
            type: 'GET',
            url: '/ajax/product/view/modal/'+id,
            dataType:'json',
            success:function(data){
                // console.log(data)
                $('#pname').text(data.product.product_name_en);
                $('#price').text(data.product.selling_price);
                $('#pcode').text(data.product.product_code);
                $('#pcategory').text(data.product.category.category_name_en);
                $('#pbrand').text(data.product.brand.brand_name_en);
                $('#pimage').attr('src','/'+data.product.product_thambnail);

                $('#product_id').val(id);
                $('#qty').val(1);

                // Product Price
                if (data.product.discount_price == null) {
                    $('#pprice').text('');
                    $('#oldprice').text('');
                    $('#pprice').text(data.product.selling_price);
                }else{
                    $('#pprice').text(data.product.discount_price);
                    $('#oldprice').text(data.product.selling_price);
                }

                // Start Stock opiton
                if (data.product.product_qty > 0) {
                    $('#aviable').text('');
                    $('#stockout').text('');
                    $('#aviable').text('aviable');
                }else{
                    $('#aviable').text('');
                    $('#stockout').text('');
                    $('#stockout').text('stockout');
                }

                // Color
                $('select[name="color"]').empty();
                $.each(data.color,function(key,value){
                    $('select[name="color"]').append('<option value="'+value+'">'+value+'</option>')
                })

                // Size
                $('select[name="size"]').empty();
                $.each(data.size,function(key,value){
                    $('select[name="size"]').append('<option value="'+value+'">'+value+'</option>')
                    if (data.size == "") {
                        $('#sizeArea').hide();
                    }else{
                        $('#sizeArea').show();
                    }

                }) // end size

            }

        })

    }

    /**
     * Function adding to cart
     *
     */
    function addToCart(){
        let product_name = $('#pname').text();
        let id = $('#product_id').val();
        let color = $('#color option:selected').text();
        let size = $('#size option:selected').text();
        let quantity = $('#qty').val();

        $.ajax({
            type: "POST",
            dataType: 'json',
            data:{
                color:color, size:size, quantity:quantity, product_name:product_name
            },
            url: "/ajax/cart/data/store/"+id,
            success:function(data){

                miniCart()
                $('#closeModel').click();
                // console.log(data)

                // Start Message
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 3000
                })
                if ( $.isEmptyObject(data.error) ) {
                    Toast.fire({
                        type: 'success',
                        title: data.success
                    })

                } else {
                    Toast.fire({
                        type: 'error',
                        title: data.error
                    })
                }
            }
        })

    }

    /**
     * Function mini cart
     *
     */
    function miniCart(){
        $.ajax({
            type: 'GET',
            url: '/ajax/product/mini/cart',
            dataType:'json',
            success:function(response){

                $('span[id="cartSubTotal"]').text(response.cartTotal);
                $('span[id="cartSubQut"]').text(response.cartQty);
                $('#cartQty').text(response.cartQty);
                var miniCart = ""

                $.each(response.carts, function(key,value){
                    miniCart += `
                        <div class="cart-item product-summary">
                            <div class="row">
                                <div class="col-xs-4">
                                    <div class="image"> <a href="detail.html"><img src="/${value.options.image}" alt=""></a> </div>
                                </div>
                                <div class="col-xs-7">
                                    <h3 class="name"><a href="index.php?page-detail">${value.name}</a></h3>
                                    <div class="price"> ${value.price} * ${value.qty} </div>
                                </div>
                                <div class="col-xs-1 action">
                                    <button type="submit" class="miniCartRemove" id="${value.rowId}" onclick="miniCartRemove(this.id)"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <!-- /.cart-item -->
                        <div class="clearfix"></div>
                        <hr>
                    `
                });

                $('#miniCart').html(miniCart);
            }
        })

    }
    miniCart()

    /**
     * Removing mini cart function
     *
     * @param rowId
     */
    function miniCartRemove(rowId){
        $.ajax({
            type: 'GET',
            url: '/ajax/minicart/product-remove/'+rowId,
            dataType:'json',
            success:function(data){
                miniCart();
                // Start Message
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 3000
                })
                if ($.isEmptyObject(data.error)) {
                    Toast.fire({
                        type: 'success',
                        title: data.success
                    })

                }else{
                    Toast.fire({
                        type: 'error',
                        title: data.error
                    })

                }
            }
        });

    }

    //  end mini cart remove
    /**
     * Function load more for products
     *
     * @param page
     */
    function loadmoreProduct(page){
        $.ajax({
            type: "get",
            url: "?page="+page,
            beforeSend: function(response){
                $('.ajax-loadmore-product').show();
            }
        })

        .done(function(data){
            console.log('data', data);
            // if ( !data) {
            //     $(".ajax-loadmore-product-btn").hide();
            // }
            if (data.grid_view == "" || data.list_view == "" || (data.grid_view == " " || data.list_view == " ") ) {
                console.log('here end!!');
                $(".ajax-loadmore-product-btn").hide();
                $('.ajax-loadmore-product').hide();
                return;
            }
            $('.ajax-loadmore-product').hide();

            $('#grid_view_product').append(data.grid_view);
            $('#list_view_product').append(data.list_view);
        })

        .fail(function(){
            alert('Something Went Wrong');
        })

    }

    function load_more_function() {

        let page = 1;
        // $(window).scroll(function (){
        //     if ($(window).scrollTop() +$(window).height() >= $(document).height()){
        //         console.log('load more');
        //         page ++;
        //         loadmoreProduct(page);
        //     }
        // });

        $(".ajax-loadmore-product-btn").on("click", function (e) {
            e.preventDefault();
            page ++;
            loadmoreProduct(page);
        })
    }
    load_more_function();

    /**
     * Function adding to wishlist
     *
     * @param product_id
     */
    function addToWishList(product_id){
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "/ajax/add-to-wishlist/"+product_id,
            success:function(data){

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                })

                if ( $.isEmptyObject(data.error) ) {
                    Toast.fire({
                        type: 'success',
                        icon: 'success',
                        title: data.success
                    })
                } else {
                    Toast.fire({
                        type: 'error',
                        icon: 'error',
                        title: data.error
                    })
                }
            }

        })

    }

    /**
     * Function of wishlist page
     *
     */
    function wishlist(){
        $.ajax({
            type: 'GET',
            url: '/ajax/get-wishlist-product',
            dataType:'json',
            success:function(response){
                var rows = ""
                $.each(response, function(key,value){
                    rows += `<tr>
                    <td class="col-md-2"><img src="/${value.product.product_thambnail} " alt="imga"></td>
                    <td class="col-md-7">
                        <div class="product-name"><a href="#">${value.product.product_name_en}</a></div>

                        <div class="price">
                        ${value.product.discount_price == null
                            ? `${value.product.selling_price}`
                            :
                            `${value.product.discount_price} <span>${value.product.selling_price}</span>`
                        }
                        </div>
                    </td>
                    <td class="col-md-2">
                        <button class="btn btn-primary icon" type="button" title="Add Cart" data-toggle="modal" data-target="#exampleModal" id="${value.product_id}" onclick="productView(this.id)"> Add to Cart </button>
                    </td>
                    <td class="col-md-1 close-btn">
                        <button type="submit" class="" id="${value.id}" onclick="wishlistRemove(this.id)"><i class="fa fa-times"></i></button>
                    </td>
                </tr>`
                });

                $('#wishlist').html(rows);
            }
        })

    }
    // wishlist(); - done on backend side

    /**
     * Function of deleting wishlist item
     *
     * @param id
     */
    function wishlistRemove(id){
        $.ajax({
            type: 'GET',
            url: '/ajax/wishlist-remove/'+id,
            dataType:'json',
            success:function(data){
                wishlist();

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                })

                if ($.isEmptyObject(data.error)) {
                    Toast.fire({
                        type: 'success',
                        icon: 'success',
                        title: data.success
                    })
                }else{
                    Toast.fire({
                        type: 'error',
                        icon: 'error',
                        title: data.error
                    })
                }

            }
        });

    }

    /**
     * Function ajax for cart page - generating fully html for it!
     *
     */
    function cart(){
        $.ajax({
            type: 'GET',
            url: '/ajax/get-cart-product',
            dataType:'json',
            success:function(response){
                var rows = ""
                $.each(response.carts, function(key,value){
                    rows += `<tr>
                        <td class="col-md-2"><img src="/${value.options.image} " alt="imga" style="width:60px; height:60px;"></td>

                        <td class="col-md-2">
                            <div class="product-name"><a href="#">${value.name}</a></div>
                            <div class="price">
                                ${value.price}
                            </div>
                        </td>

                        <td class="col-md-2">
                            <strong>${value.options.color} </strong>
                        </td>

                        <td class="col-md-2">
                            ${value.options.size == null
                                ?
                                `<span> .... </span>`
                                :
                                `<strong>${value.options.size} </strong>`
                            }
                        </td>

                        <td class="col-md-2">
                            ${value.qty > 1
                                ? `<button type="submit" class="btn btn-danger btn-sm cartDecrementBtm" id="${value.rowId}"  >-</button> `
                                : `<button type="submit" class="btn btn-danger btn-sm" disabled >-</button> `
                            }

                            <input type="text" value="${value.qty}" min="1" max="100" disabled="" style="width:25px;" >
                            <button type="submit" class="btn btn-success btn-sm cartIncrementBtn" id="${value.rowId}"  >+</button>
                        </td>

                        <td class="col-md-2">
                            <strong>$${value.subtotal} </strong>
                        </td>

                        <td class="col-md-1 close-btn">
                            <button type="submit" class="cartRemoveBtn" id="${value.rowId}" ><i class="fa fa-times"></i></button>
                        </td>
                    </tr>
                    `
                });

                rows += `
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><h5>Total Quantity: <b> ${response.cartQty}</b></h5></td>
                        <td><h5>Total Price: <b>$${response.cartTotal}</b></h5></td>
                        <td></td>
                    </tr>
                    `;

                $('#cartPage').html(rows);
            }
        })

     }

    /**
     * Function cart part page for updating total values after ajax
     *
     */
    function cart_part(){
        $.ajax({
            type: 'GET',
            url: '/ajax/get-cart-product',
            dataType:'json',
            success:function(response){
                let rows = ""
                $(".cartQtyTd").text(response.cartQty);
                $(".cartTotalTd").text(response.cartTotal.toFixed(2) + '$');
            }
        })
    }
    // cart(); - did it in backend side

    /**
     * Function removing item from cart page
     *
     * @param id
     */
    function cartRemove(id){
        $.ajax({
            type: 'GET',
            url: '/ajax/cart-remove/'+id,
            dataType:'json',
            success:function(data){
                couponCalculation();
                cart();

                cart_part()
                miniCart();

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                })

                if ( $.isEmptyObject(data.error) ) {
                    Toast.fire({
                        type: 'success',
                        icon: 'success',
                        title: data.success
                    })
                } else {
                    Toast.fire({
                        type: 'error',
                        icon: 'error',
                        title: data.error
                    })
                }
            }
        });

    }
    // End Cart remove

    // -------- CART INCREMENT --------//
    /**
     * Function cart incrementing
     *
     * @param rowId
     */
    function cartIncrement(rowId){
        $.ajax({
            type:'GET',
            url: "/ajax/cart-increment/"+rowId,
            dataType:'json',
            success:function(data){
                couponCalculation();
                cart();
                cart_part();
                miniCart();
            }
        });
    }
    // ---------- END CART INCREMENT -----///

    // -------- CART Decrement  --------//
    /**
     * Function cart decrementing
     *
     * @param rowId
     */
    function cartDecrement(rowId){
        $.ajax({
            type:'GET',
            url: "/ajax/cart-decrement/"+rowId,
            dataType:'json',
            success:function(data){
                couponCalculation();
                cart();
                cart_part();
                miniCart();
            }
        });
    }

    // ---------- END CART Decrement -----///

    //////////////// =========== Coupon Apply Start ================= ////  -->

    $(".applyCouponBtn").on("click", function ( e ){
        e.preventDefault();

        applyCoupon();
    })

    $(document).on('click', '.couponRemoveBtn', function (e) {
        e.preventDefault();
        couponRemove();
    })

    /**
     * Function applying coupon
     *
     */
    function applyCoupon(){
        var coupon_name = $('#coupon_name').val();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: { coupon_name:coupon_name },
            url: "/ajax/coupon-apply",
            success:function(data){
                couponCalculation();
                if ( data.validity == true ) {
                   $('#couponField').hide();
                }

                // Start Message
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                })

                if ($.isEmptyObject(data.error)) {
                    Toast.fire({
                        type: 'success',
                        icon: 'success',
                        title: data.success
                    })
                } else {
                    Toast.fire({
                        type: 'error',
                        icon: 'error',
                        title: data.error
                    })
                }
            }
        })
    }

    /**
     * Function coupon calculation
     *
     */
    function couponCalculation(){
        $.ajax({
            type:'GET',
            url: "/ajax/coupon-calculation",
            dataType: 'json',
            success:function(data){
                if (data.total && data.total>0) {
                    $('#couponCalField').html(
                        `<tr>
                            <th>
                                <div class="cart-sub-total">
                                    Subtotal<span class="inner-left-md">$ ${data.total.toFixed(2)}</span>
                                </div>
                                <div class="cart-grand-total">
                                    Grand Total<span class="inner-left-md">$ ${data.total.toFixed(2)}</span>
                                </div>
                            </th>
                        </tr>`
                    )
                }
                else {
                    $('#couponCalField').html(
                        `<tr>
                            <th>
                                <div class="cart-sub-total">
                                    Subtotal<span class="inner-left-md">$ ${data.subtotal}</span>
                                </div>

                                <div class="cart-sub-total">
                                    Coupon<span class="inner-left-md">$ ${data.coupon_name}</span>
                                    <button type="submit" class="couponRemoveBtn"><i class="fa fa-times"></i>  </button>
                                </div>

                                <div class="cart-sub-total">
                                    Discount Amount<span class="inner-left-md">$ ${data.discount_amount}</span>
                                </div>

                                <div class="cart-grand-total">
                                    Grand Total<span class="inner-left-md">$ ${data.total_amount}</span>
                                </div>
                            </th>
                        </tr>`
                    )
                }
            }
        });
    }
    couponCalculation();

    /**
     * Function removing of coupon
     *
     */
    function couponRemove(){
        $.ajax({
            type:'GET',
            url: "/ajax/coupon-remove",
            dataType: 'json',
            success:function(data){
                couponCalculation();
                $('#couponField').show();
                $('#coupon_name').val('');

                 // Start Message
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000
                })

                if ($.isEmptyObject(data.error)) {
                    Toast.fire({
                        type: 'success',
                        icon: 'success',
                        title: data.success
                    })
                }else{
                    Toast.fire({
                        type: 'error',
                        icon: 'error',
                        title: data.error
                    })
                }
            }
        });

    }

    /**
     * Checkout page
     *
     */
    $('select[name="division_id"]').on('change', function(){
        let division_id = $(this).val();
        if ( division_id ) {
            $.ajax({
                url: "/ajax/district-get/"+division_id,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    $('select[name="state_id"]').empty();
                    var d =$('select[name="district_id"]').empty();
                    $.each(data, function(key, value){
                        $('select[name="district_id"]').append('<option value="'+ value.id +'">' + value.district_name + '</option>');
                    });
                },
            });
        } else {
            alert('danger');
        }
    });

    $('select[name="district_id"]').on('change', function(){
        let district_id = $(this).val();
        if ( district_id ) {
            $.ajax({
                url: "/ajax/state-get/"+district_id,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    console.log('data', data);
                    let d = $('select[name="state_id"]').empty();
                    $.each(data, function(key, value){
                        $('select[name="state_id"]').append('<option value="'+ value.id +'">' + value.state_name + '</option>');
                    });
                },
            });
        } else {
            alert('danger');
        }
    });


    /**
     * Payments
     */
    if ( $(".stripe_page").length > 0 ) {
        // Create a Stripe client.
        var stripe = Stripe('pk_test_51IUTWzALc6pn5BvMAUegqRHV0AAokjG7ZuV6RWcj5rxB9KCAwamgtWpw9T4maGAe34WmDkD6LSn1Yge3nzex6gYk004pILHsNh');
        // Create an instance of Elements.
        var elements = stripe.elements();
        // Custom styling can be passed to options when creating an Element.
        // (Note that this demo uses a wider set of styles than the guide below.)
        var style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});
        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');
        // Handle real-time validation errors from the card Element.
        card.on('change', function (event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            stripe.createToken(card).then(function (result) {
                if (result.error) {
                    // Inform the user if there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });

        // Submit the form with the token ID.
        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);
            // Submit the form
            form.submit();
        }
    }


    /**
     * Shop filters action
     *
     */

})
