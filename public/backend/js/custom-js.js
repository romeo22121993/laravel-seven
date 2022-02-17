$(function () {
    'use strict'

    $(document).ready(function(){
        $('#image, .edit_slider #slider_img').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showImage').attr('src',e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });


        /**
         * Ajax requests for getting sub/subsub categories
         *
         */
        $(".subsubcategory, .product_add, .edit_product_page").find('select[name="category_id"]').on('change', function(){

            let category_id = $(this).val();
            if(category_id) {
                $.ajax({
                    url: '/ajax/category/subcategory/'+category_id,
                    type:"GET",
                    dataType:"json",
                    success:function(data) {
                        var d =$('select[name="subcategory_id"]').empty();
                        $.each(data, function(key, value){
                            $('select[name="subcategory_id"]').append('<option value="'+ value.id +'">' + value.category_name_en + '</option>');
                        });
                    },
                });
            } else {
                alert('danger');
            }
        });


        $(".product_add, .edit_product_page").find('select[name="subcategory_id"]').on('change', function(){
            let subcategory_id = $(this).val();
            if(subcategory_id) {
                $.ajax({
                    url: "/ajax/category/sub-subcategory/"+subcategory_id,
                    type:"GET",
                    dataType:"json",
                    success:function(data) {
                        var d =$('select[name="subsubcategory_id"]').empty();
                        $.each(data, function(key, value){
                            $('select[name="subsubcategory_id"]').append('<option value="'+ value.id +'">' + value.category_name_en + '</option>');
                        });
                    },
                });
            } else {
                alert('danger');
            }
        });


        /**
         * Function showing thumbnail image without reloading page
         *
         */
        $(".product_thambnail").on('change', function(){
            mainThamUrl($(this));
        });

        function mainThamUrl(input){
            console.log( 'this', input );
            input = input[0];
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e){
                    $('#mainThmb').attr('src',e.target.result).width(80).height(80);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }


        /**
         * Function showing multiimage without reloading page
         *
         */
        $('#multiImg').on('change', function(){ //on file input change
            if (window.File && window.FileReader && window.FileList && window.Blob) //check File API supported browser
            {
                var data = $(this)[0].files; //this file data

                $.each(data, function(index, file){ //loop though each file
                    if(/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)){ //check supported file type
                        var fRead = new FileReader(); //new filereader
                        fRead.onload = (function(file){ //trigger function on successful read
                            return function(e) {
                                var img = $('<img/>').addClass('thumb').attr('src', e.target.result) .width(80)
                                    .height(80); //create image element
                                $('#preview_img').append(img); //append image to output element
                            };
                        })(file);
                        fRead.readAsDataURL(file); //URL representing the file's data.
                    }
                });

            }else{
                alert("Your browser doesn't support File API!"); //if File API is absent
            }
        });

    });
});
