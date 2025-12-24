(function($){
    $(document).ready(function(){
        
        // $(".product-img-info-hld .badgee").click(function(e){                          
        //     var key=$(this).attr("data-key");                           
        //     var tbl_item=$("#spare_list_table").find(".t_row[data-key='"+key+"']");
        //     if(tbl_item.length>0){
        //         if(!$(this).hasClass("active")) {
        //             tbl_item.addClass("active");
        //             $(this).addClass("active");
        //         }else{
        //             tbl_item.removeClass("active");
        //             $(this).removeClass("active");
        //         }
        //     }else{
        //         if(!$(this).hasClass("active")) {
        //             tbl_item.addClass("active");
        //             $(this).addClass("active");
        //         }else{
        //             tbl_item.removeClass("active");
        //             $(this).removeClass("active");
        //         }
        //     }
            
        // });

        // $(".product-img-info-hld").click(function(e){			
        //     if(!$(e.target).hasClass("badgee")){
        //         $("#product-img-info-hld-msg").css('display',"none");
        //     }
        // });

        $(".badgee").click(function(e) {
            var key = $(this).attr("data-key");

            // Remove active class from all .badgee and .t_row elements
            $(".badgee").removeClass("active");
            $(".t_row").removeClass("active");

            // Add active class to the clicked .badgee and corresponding .t_row elements
            $(this).addClass("active");
            $(".t_row[data-key='" + key + "']").addClass("active");

            // Get the index of the corresponding .spare-card element
            var slideIndex = $(".spare-card[data-key='" + key  + "']").index()-1;

            // Go to the slide corresponding to the clicked badge
            $('.slick-carousel-spare').slick('slickGoTo', slideIndex);
        });

        $(".product-img-info-hld").click(function(e) {
            if (!$(e.target).hasClass("badgee")) {
                $(".badgee").removeClass("active");
                $(".t_row").removeClass("active");
                $("#product-img-info-hld-msg").css('display', "none");
            }
        });

        $("#spare_list .add_to_cart").click(function(e){	
            var title=$(this).closest(".t_row").find(".t_title").text();
            var img=$(this).closest(".t_row").find(".t_img img").attr("src");
            var art_no=$(this).closest(".t_row").find(".t_art_num").text();
            var price=$(this).attr("data-price");
            var qty=$(this).attr("data-qty");

            $("#t_modal .t_item_img img").attr("src",img);
            $("#t_modal .t_item_info .name").text(title);
            $("#t_modal .t_item_info .artno").text(art_no);
            $("#t_modal .t_item_info .price span").text(price);
            $("#t_modal .t_item_action .min_req span").text(qty);

            // updaprice=price*qty;
            // $("#t_modal .t_total_info .t_total span").text(updaprice);
            // $("#t_modal").show();
            $("#t_modal").css("display", "block");
            
        });
        $("#spare_list_table .add_to_cart").click(function(e){	
            var title=$(this).closest(".t_row").find(".t_title").text();
            var img=$(this).closest(".t_row").find(".t_img img").attr("src");
            var art_no=$(this).closest(".t_row").find(".t_art_num").text();
            var price=$(this).attr("data-price");
            var qty=$(this).attr("data-qty");

            $("#t_modal .t_item_img img").attr("src",img);
            $("#t_modal .t_item_info .name").text(title);
            $("#t_modal .t_item_info .artno").text(art_no);
            $("#t_modal .t_item_info .price span").text(price);
            $("#t_modal .t_item_action .min_req span").text(qty);

            // updaprice=price*qty;
            // $("#t_modal .t_total_info .t_total span").text(updaprice);
            // $("#t_modal").show();
            $("#t_modal").css("display", "block");
            
        });

        $("#t_modal .t_close_btn").click(function(e){			
            $("#t_modal").hide();
            $("body").attr("scroll","yess");
            $("body").css("overflow","scroll");
        });

        // $("#t_modal .t_time_action_item .t_time_action_item_plus").click(function(e){			
        //     var cur=$("#t_modal .t_time_action_item .t_time_action_item_val")
        //     var count=parseInt(cur.text())+1;
        //     var curcount=cur.text(count);
        //     console.log(count);
        //     // console.log(count);
        //     updateCartPrice(count);
        // });

        // $("#t_modal .t_time_action_item .t_time_action_item_minus").click(function(e){			
        //     var cur=$("#t_modal .t_time_action_item .t_time_action_item_val");

        //     var count=parseInt(cur.text());
        //     if(count>1){
        //         count--;
        //     }
        //     console.log(count);
        //     cur.text(count);
        //     updateCartPrice(count);
        // });

        // function updateCartPrice(qty){
        //     var price=parseInt($("#t_modal .t_item_info .price span").text());                           
        //     price=price*qty;
        //     $("#t_modal .t_total_info .t_total span").text(price);
            
        // }
    });

    
})(jQuery);