jQuery(function ($) {
    if($('#taw-prod-items').length == 0) return;
    var cur_cate = "";
    var wH = $(window).height();
    var ftH = $('#site-header').height();
    var startP = $('#taw-prod-items').offset().top;

    if (typeof category != "undefined") {
        cur_cate = category;
    }
    

    var headerHeight = $('#masthead').outerHeight(true);
    var containerHeight = wH - headerHeight;
    // only for lg, xl screen
    if($(window).outerWidth(true) >= 1024) {

        $('.scrollable-container').css({'height':containerHeight+'px'});
    }
    $('#filter-container-large').css({'top':headerHeight+'px'});
    $('#filter-list-option').css({'top':headerHeight+'px'});
    $('.list-item-container').css({'padding-top':headerHeight+'px'}); 
    $('#taw-prod-items-hld').css({'min-height':containerHeight+'px'});

    var filteration = {};
    if(existingFilter) {

        let array = existingFilter.split("::");
        for (let index = 0; index < array.length; index++) {
            const element = array[index];
            if(element) {

                let splitElement = element.split("=");
                let key = splitElement[0];
                let value = splitElement[1];
                let splitValue = value.split(",");
                filteration[key] = splitValue;
                var parentHolder = "#taw_attr_" + key;
                if ($('#filter-container-large').css('display') == 'none') {
                    parentHolder = "#mobile_taw_attr_" + key; //small screen
                }
                
                for (let i = 0; i< splitValue.length; i++) {
                    const dataId = splitValue[i];
                   jQuery(parentHolder + ' .taw_attr_option[data-id=' + dataId +']').addClass('active'); 
                }
            }
        }
        if(!$('.filter_clear').hasClass('active')) {
            $('.filter_clear').addClass('active'); 
        }

    }else {

        if($('.filter_clear').hasClass('active')) {
            $('.filter_clear').removeClass('active'); 
        } 
    }

    var loading = true;
    var paramQ = {category:category, filter: filteration, action: "getProductsByFilter", layout: layoutType, orderby: '', order: '', searchText: '', page: 1, end: false };

    $('document').ready(function () {

        productsItemView();

        // $(window).scroll(function () {

        //     var hT = $('#site-header').offset().top - (wH + ftH + startP);
        //     var wS = $(this).scrollTop();
        //     if (wS > hT) {
        //         if (!loading) {
        //             console.log("start load");
        //             loadData();
        //         }
        //     }
        // });

        $(".attribute-type").on("click", function () {
            var type = $(this).attr("data-id");

            var html = $("#taw_attr_" + type).html();
            console.log(type);

            $("#taw_filter_modal .modal-content").html(html);

            if($("#taw_filter_modal .modal-content .taw_attr_option").length > 3){

                $("#taw_filter_modal").css("paddingTop","50px");

            }else{
                
                $("#taw_filter_modal").css("paddingTop","100px");
            }

            //set active items
            if (paramQ.filter[type]) {
               
                console.log(paramQ.filter[type]);
                for (var i = 0; i < paramQ.filter[type].length; i++) {
                    var id = paramQ.filter[type][i];
                    var item = $("#taw_filter_modal .modal-content").find(".taw_attr_option[data-id='" + id + "']");
                    if (item.length > 0) {
                        item.addClass("active");
                    }
                }
            }

            $("#taw_filter_modal").toggleClass("show");
        });

        $("body").undelegate(".taw_attr_option", "click").delegate(".taw_attr_option", "click", function () {
            $(this).toggleClass("active");
            collectingFilterData(this);
        });

        $("#taw_filter_sortby").on("change", function (e) {
          paramQ.order=$(this).val();
          paramQ.orderby=$('option:selected', this).attr('data-orderby');         
          loadDataFromStart();
        });

      //  loadData();

    });

    function collectingFilterData(e) {
        // var html = "";
                
                paramQ.filter= {};

                $(".taw_attr_option.active").each(function (e) {
                    var id = $(this).attr("data-id");

                    var attr = $(this).closest('.filter-option-container').attr("data-key");
                    // var title = $(this).attr("data-title");
                    // console.log(attr);
                    console.log(id);
                    
                    
                    if (!paramQ.filter[attr]) {
                        paramQ.filter[attr] = [];
                    }
                    paramQ.filter[attr].push(id);
                    // html += "<li class='m-1 text-xs text-white bg-red-600 rounded-xl px-2 py-1'>" + title + "</li>";
                });

                var filterExist = ""; // used in page refresh time
                for(var i in paramQ.filter){
                    if(paramQ.filter[i].length > 0) {
                        filterExist += i + "=" + paramQ.filter[i] + "::";
                    } 
                }
                let newState = "?filter=" + filterExist;
                let params = (new URL(document.location)).searchParams;
                let layoutLocal = params.get("layout");
                if(layoutLocal) {
                    newState += "&layout=" + layoutLocal;
                }
                window.history.replaceState(null, null, newState);

                // $(".hld_" + attr).find("ul").html(html);
                // $(this).toggleClass("show");
                loadDataFromStart();

                if (Object.keys(paramQ.filter).length === 0) { 
                    if($('.filter_clear').hasClass('active')) {
                        $('.filter_clear').removeClass('active'); 
                    }
                }else{
                    if(!$('.filter_clear').hasClass('active')) {
                        $('.filter_clear').addClass('active'); 
                    }
                }
    }

    // function loadDataFromStart(showLoader = true) {
    //     paramQ.end = false;
    //     paramQ.page = 1;
    //     loadData(showLoader);
    // }    
    function loadDataFromStart(showLoader = true) {
        paramQ.end = false;
        paramQ.page = 1;
        if(paramQ.category === ''){
            // Function to get the value of a parameter from the URL
            function getParameterValue(url, parameter) {
                var match = url.match(new RegExp('[?&]' + parameter + '=([^&]+)'));
                return match ? decodeURIComponent(match[1].replace(/\+/g, ' ')) : null;
            }

            // Get the value of the 's' parameter
            var sValue = getParameterValue(window.location.href, 's');

            paramQ.searchText = sValue;

            loadData(showLoader);

            console.log(paramQ)
        }else{
            loadData(showLoader);
        }

        
    }  
    function getSiteCurrentLang() {
        return typeof currentLanguage !== 'undefined' ? currentLanguage : 'en';
    }
    let isLoadMoreClick = false; 
    let isinputsearch = false; 
    let isclearsearch = false;
    let issearchbtn = false;  

    function loadData(showLoader = true) {
        // console.log("loadData function called");
         if (isLoadMoreClick) {
             showLoader = false;  // Set showLoader to false if it's a "Load More" click
            //  isLoadMoreClick = false;  // Reset the flag
         }
         if (isinputsearch) {
             showLoader = false;  
             isinputsearch = false;  
         }
         if (isclearsearch) {
             showLoader = false;  
             isclearsearch = false;  
         }
         if (issearchbtn) {
            showLoader = false;  
            issearchbtn = false;  
        }
     
        if (paramQ.end) {
            return;
        }
        if (typeof aurl != "undefined") {
            if (paramQ.page == 1) {
                $("#taw-prod-loader").css("position", "absolute");
            } else {
                $("#taw-prod-loader").css("position", "relative");
            }
            $("#taw-prod-loader").css("display", showLoader ? "block" : "none");
            $("#productloader").css("display", showLoader ? "grid" : "none");

            let producthtml = $("#taw-prod-items").html();

            if(!isLoadMoreClick){
            $("#taw-prod-items").html($("#productloader").html());
            }

            if (showLoader) {
                //console.log("adding active");
                $('#blurimage').addClass('active');
                $('#blurimg').addClass('active');
            }
           
            loading = true;
            jQuery.ajax({
                type: "POST",
                url: aurl,
                data: paramQ,
                success: function (data) {
                  
                        //console.log("Removing active class from blurimage and blurimg");
                        $("#taw-prod-loader").css("display", "none"); 
                        $("#productloader").css("display", "none");       
                        $('#blurimage').removeClass('active');
                        $('#blurimg').removeClass('active');
                   
                        if (paramQ.page == 1) {
                            $("#taw-prod-items").html(data);
                        }else{
                            $("#taw-prod-items").html(producthtml);

                            $("#taw-prod-items").append(data);
                        }
                    
                        let totall_page_count =  parseInt($("#taw_page_count").val(), 10);
                        console.log(totall_page_count , paramQ.page ,totall_page_count == paramQ.page )
                    if (totall_page_count == paramQ.page  ) {
                        paramQ.end = true;
                        $("#taw_filter_item_load_more").css("display","none");
                    } else{
                        paramQ.end = false;
                        $("#taw_filter_item_load_more").css("display","flex");

                    }
       
                   
                    if (isLoadMoreClick) {
                        $('#taw_filter_item_load_more').find('.aloader').css('display', 'none');

                        isLoadMoreClick = false;  // Reset the flag
                    }
                    // if(!filter.end){
                    //    // $(".scrollbar").perfectScrollbar('destroy');
                    //     $(".scrollbar").perfectScrollbar();
                    // }

                    var lang = getSiteCurrentLang();
                    //console.log(lang);
                    var countText = (lang === 'en') ? ' Products' : ' Produkter';
                    $("#taw_total_prod_count").html($("#taw_prod_count").val() + countText);


                    //    $("#taw_total_prod_count").html($("#taw_prod_count").val() + " Products");

                    loading = false;
                    paramQ.page++;
                },
                error: function (errorThrown) {
                    $("#taw-prod-loader").css("display", "none");
                    alert("something went wrong" );
                    console.log(errorThrown)
                    if (isLoadMoreClick) {
                        $('#taw_filter_item_load_more').find('.aloader').css('display', 'none');

                        isLoadMoreClick = false;  // Reset the flag
                    }
                }
            });
        }
    }

    $(".filter_clear").on("click", function (e) {

        if (Object.keys(paramQ.filter).length === 0) { return; }

        $('.filter_clear').removeClass('active'); 
        paramQ.filter = {};
        //remove filter value from url
        let newState = "?filter=";
        let params = (new URL(document.location)).searchParams;
        let layoutLocal = params.get("layout");
        if(layoutLocal) {

            newState += "&layout=" + layoutLocal;
        }
        window.history.replaceState(null, null, newState);
        $(".taw_attr_item_hld .taw_attr_option").removeClass('active');
        loadDataFromStart();
    });

    $("#taw-pro-img-grid").on("click", function (e) {
        $("#taw-prod-loader").hide();
        if (paramQ.layout != 'grid') {
            paramQ.layout = 'grid';
            productsItemView(false);
            let newState = "?layout=grid";
            let params = (new URL(document.location)).searchParams;
            let filterLocal = params.get("filter");
            if(filterLocal) {

                newState += "&filter=" + filterLocal;
            }
            window.history.replaceState(null, null, newState);
        }
    });

    $("#taw-pro-img-list").on("click", function (e) {
        $("#taw-prod-loader").hide();
        if (paramQ.layout != 'list') {
            paramQ.layout = 'list';
            productsItemView(false);
            let newState = "?layout=list";
            let params = (new URL(document.location)).searchParams;
            let filterLocal = params.get("filter");
            if(filterLocal) {

                newState += "&filter=" + filterLocal;
            }
            window.history.replaceState(null, null, newState);
        }
    });

    $("#taw-pro-img-search").on("click", function (e) {

        if (paramQ.layout != 'list') {
            paramQ.layout = 'list';
            productsItemView();
            let newState = "?layout=list";
            let params = (new URL(document.location)).searchParams;
            let filterLocal = params.get("filter");
            if(filterLocal) {

                newState += "&filter=" + filterLocal;
            }
            window.history.replaceState(null, null, newState);
        }
    });

    function productsItemView(showLoader = true) {

        if (paramQ.layout == 'grid') {
            loadDataFromStart(showLoader);
            $('#taw-prod-items')
            .removeClass('list')
            .addClass('grid');
            $('#taw-pro-img-grid').addClass('active');
            $('#taw-pro-img-list').removeClass('active');

        }else{
            loadDataFromStart(showLoader);
            $('#taw-prod-items')
            .removeClass('grid')
            .addClass('list');
            $('#taw-pro-img-grid').removeClass('active');
            $('#taw-pro-img-list').addClass('active');
        }
    }

    $("#tab-title-description").on("click", function (e) {
        window.alert("clicked");
        // $('.taw-tab-option').addClass('active');

    });

    $("#taw_input_search_btn").on("click", function () {
    var searchText = $("#taw_input_search").val().trim();
    if (searchText) {
        if (searchText !== localStorage.getItem('lastSearchText')) {
            paramQ.searchText = searchText;
            issearchbtn = true; 
// console.log("after search", paramQ);
            loadDataFromStart();
localStorage.setItem('lastSearchText', searchText);
        } else {
            console.log("Same search text. Skipping search.");
        }
        } else {
            $(this).toggleClass("active");
            $('#taw_input_search_holder').toggleClass("active");   
        }
    });

    $("#taw_input_search_clear").on("click", function () {
        if ($("#taw_input_search").val()) {
            paramQ.searchText = ""; 
            $("#taw_input_search").val('');
            isclearsearch = true; 
            loadDataFromStart(); 
localStorage.removeItem('lastSearchText');
        }      
    });

    $("#taw_input_search").on("keyup", function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            var searchText = $(this).val().trim();
        if (searchText !== localStorage.getItem('lastSearchText')) {
            paramQ.searchText = searchText;
            isinputsearch = true; 
// console.log("after search", paramQ);
           loadDataFromStart();
localStorage.setItem('lastSearchText', searchText);
        }
        }
    });

    $('body').off('click', '#taw_filter_item_load_more').on('click', '#taw_filter_item_load_more', function () {
        isLoadMoreClick = true; 
        $('#taw_filter_item_load_more').find('.aloader').css('display', 'block');

              loadData(false);
    });

    //tab view
    
});