jQuery(function ($) {
    var img_h;
    var IMG_RES = 1000;
    var IMG_RES_THUMB = 150;
    var IMG_LEFT_BLK_W = 34;
    var IMG_RIGHT_BLK_W = 15;
    var IMG_MID_BLK_W = 48;
    var ruleMap = {
        "Door Width": "form.size.size_text",
        "Door Height": "form.size.size_text",
        "Wood Type": "form.material.m_id",
        "Glass Type": "form.glass.option_id",
        "Hinge": "form.size.hinge",
        "Kickplate": "form.kickplate.title",
        "Door Open": "form.additional.door_open",
        "Frame Extension": "form.additional.frame_extn",
        "Side Light": "form.sidelight.type",
        "Sliding Door": "form.material.sliding_door",
    }

    var priceKeyMap = {
        "material": "base",
        "glass": "glass",
        "kickplate": "base",
        "size": "size",
        "sidelight": "type",
        "accessories": "type",
        "hinge": "hinge"
    }

    var propMap = {
        "material": "material",
        "glass": "glass",
        "kickplate": "kickplate",
        "size": "size",
        "sidelight": "sidelight",
        "accessories": "accessories",
        "hinge": "size"
    }

    var optionIdMap = {
        "material": "id",
        "glass": "option_id",
        "kickplate": "id",
        "size": "size_id"
    }

    var propType = {
        "Door Width": "size",
        "Door Height": "size",
        "Wood Type": "material",
        "Glass Type": "glass",
        "Hinge": "hinge",
        "Kickplate": "kickplate",
        "Door Open": "door_open",
        "Frame Extension": "frame_extn",
        "Side Light": "sidelight",
        "Sliding Door": "sliding_door",
    }


    function setContentHeight() {

        var w = $(window).width();
        if (w > 768) {
            var menu_h = $("#masthead").height();//header-ekit 

            menu_h = menu_h ? menu_h : 0;
            var body_h = $(window).height();
            var adminBarH = $("#wpadminbar").height();
            adminBarH = adminBarH ? adminBarH : 0;

            var contentH = body_h - (menu_h + adminBarH);
            img_h = contentH - 200;
            $("#content-custom-hld").css("height", contentH + "px");
            $("#content-custom-summary-hld").css("height", contentH + "px");
        } else {
            /** mobile vivew */
            var h = (w / 100) * 50;
            img_h = h - (40);
            $("#taw-prod-slider-hld").css("height", h + "px");
            $("#prod-text-info-hld").css("top", h + "px");
            $("#taw-prod-slider-img-hld-cont img").css("height", h + "px");
        }

    }

    function showSlider() {
        $("#prod-slide-show-hld").css("display", "block");
        $("#prod-slide-show").slick({
            dots: true,
            infinite: true,
            speed: 500,
            fade: true,
            cssEase: 'linear'
        });
    }

    function initCommon() {
        $("body").undelegate(".taw-tab-cont", "click").delegate(".taw-tab-cont", "click", function () {
            var item = $(this).attr("data-id");
            $(".taw-tab-cont-item").removeClass("active");

            $(".taw-tab-cont").removeClass("font-bold");
            $(this).addClass("font-bold");

            $("#taw-tab-cont-" + item).addClass("active");
        });
        if ($('#show_slide_gal').length > 0) {
            $("body").undelegate("#show_slide_gal", "click").delegate("#show_slide_gal", "click", function () {
                showSlider();
            });

            $("body").undelegate("#prod-slide-show-hld .clse_ic", "click").delegate("#prod-slide-show-hld .clse_ic", "click", function () {
                $("#prod-slide-show-hld").css("display", "none");
            });
        }

        $('.taw-tab-cont:first').trigger("click");
    }

    if (singleProduct) {
        $(document).ready(function () {
            setContentHeight();
            initCommon();

            $("#co9n .image img").css("height", img_h);
            $("#co9n").slick({
                dots: false,
                lazyLoad: 'ondemand',
                speed: 500,
                fade: true
            });

            $("#taw-prod-loader").css("display", "none");
        });

    } else {
        var lst_prod = { material: 0, glass: 0, size: 0, sidelight: {}, packages: '', packages_sl_door: '', packages_inward_door: '', kickplate: '' },
            sidelight_img_obj = {},
            sidelight_size_obj = {},
            item_price = {},
            acs_all = {},
            acs_sl_all = {},
            acs_inward_all = {},
            base_prod_html = '',
            base_glass_html = '',
            base_kickplate_html = '',
            TotalPrice = 0,
            sidelightloading = false,
            initial_load = true, final_img_url = "", final_size = 0, article_condition = {}, article_condition_param = {};

        item_price['material'] = { base: { price: 0, art_nos: '', action: "+" } };
        item_price['kickplate'] = { base: { price: 0, art_nos: '', action: "+" } };
        item_price['glass'] = { glass: { price: 0, art_nos: '', action: "+" }, glass_type: { price: 0, art_nos: '', action: "+" } };
        item_price['size'] = { size: { price: 0, art_nos: '', action: "+" }, hinge: { price: 0, art_nos: '', action: "+" } };
        item_price['additional'] = { door_open: { price: 0, art_nos: '', action: "+" }, frame_extn: { price: 0, art_nos: '', action: "+" }, sliding_door: { price: 0, art_nos: '', action: "+" } };
        item_price['sidelight'] = { type: { price: 0, art_nos: '' }, glass_type: { price: 0, art_nos: '' } };
        item_price['accessories'] = {};// {pkg:{price:0,art_nos:''}}
        item_price['shipping'] = { base: { price: 0, art_nos: '', action: "+" } };// {pkg:{price:0,art_nos:''}}

        //original price
        var changed_price = {};

        var sidelight_hide_by_size = 0;


        var loaderHtml = '<div class="z-50 w-full h-20 grid place-content-center" >' +
            '<span class="aloader"></span>' +
            '</div>';

        //var noneItem=''

        $("body").undelegate('.product-item-base.t-c-select', "click").delegate(".product-item-base.t-c-select", "click", function () {

            var html = '';
            var changeSlider = true;
            var tab = $(this).closest("ul").attr("data-type");

            $(this).closest("ul").find("li").removeClass("active");
            $(this).addClass("active");



            if (!(tab == "packages" || tab == "sidelight")) {
                var items = $(this).attr("data-img");
                items = JSON.parse(items);

                var o = 0;
                items.forEach(obj => {
                    if (o == 0) {
                        o++;
                        final_img_url = obj.img;
                        if (tab == "kickplate") {
                            return false;
                        }
                    }

                    html += '<div><div class="image" style="text-align:center;position:relative;"><span><img data-id="' + obj.id + '" style="height:' + img_h + 'px;display:inline-block;" data-lazy="' + obj.img + '" /></span></div></div>';

                });

                var loadSlider = true;
                if (tab == "kickplate") {
                    //if (form.sidelight.type != "none") {
                    loadSlider = false;
                    //}
                }
                if (loadSlider) {
                    if ($('#co9n').html() != "") {
                        $('#co9n').slick('unslick');
                    }
                    $('#co9n').html(html);

                    $("#co9n").slick({
                        dots: false,
                        lazyLoad: 'ondemand',
                        speed: 500,
                        fade: true
                    });
                }
            }

            if (tab == "material") {
                setMaterialForm(this);
            } else if (tab == "glass") {
                setGlassForm(this);
            } else if (tab == "size") {
                setSizeForm();
            } else if (tab == "packages") {
                setAccessoriesForm(this);
            } else if (tab == "sidelight") {
                setSidelightForm(this);
            } else if (tab == "kickplate") {
                setKickplateForm(this);
            }

        });

        function updatePrice() {
            checkArticleConditionRule();
            var price = 0;
            for (var key in changed_price) {
                if (changed_price.hasOwnProperty(key)) {
                    var priceObj = changed_price[key];
                    for (var p in priceObj) {
                        if (priceObj.hasOwnProperty(p)) {
                            if (typeof priceObj[p].action != "undefined" && priceObj[p].action == "-") {
                                // price -= parseFloat(priceObj[p].price);
                            } else {
                                price += parseFloat(priceObj[p].price);
                            }
                        }
                    }
                }
            }

            $("#prod-text-info-hld #prod-price .cprice b").html(price);
            TotalPrice = price;
            saveOptionsInCookie();


        }

        function checkArticleConditionRule() {

            changed_price = JSON.parse(JSON.stringify(item_price));

            for (var key in form) {
                if (key == 'material' || key == 'glass' || key == 'size' || key == 'kickplate' || key == 'sidelight' || key == 'accessories') {

                    if (key == "accessories") {

                        var activePkg = $('#taw-blk-prod-packages').find("li.active");
                        var pkg_id = activePkg.attr("data-id");
                        
                        var rules = activePkg.attr("data-artnos-actions");
                        if (rules) {
                            rules = JSON.parse(rules);
                        }else{
                            rules = [];
                        }

                        var check = false;
                        for (var i = 0; i < rules.length; i++) {
                            var o = rules[i];
                            console.log(o);
                            for (var a in item_price.accessories) {
                                var ao = item_price.accessories[a];

                                if ("pkg-" + pkg_id + "-" + a == o.option_id && o.art_no == ao.art_nos) {

                                    //check add artno
                                    var add_art = false;
                                    var price = parseInt(ao.price);
                                    if (o.add_art_nos && o.add_art_nos != 'null') {
                                        var aritcles = o.add_art_nos;

                                        for (var item_artno in aritcles) {
                                            var condition = checkRule(aritcles[item_artno].condition);

                                            if (condition.check) {
                                                add_art = true;
                                                price = price + parseInt(aritcles[item_artno].price);
                                                changed_price.accessories[a].price = price;
                                                changed_price.accessories[a].art_nos = ao.art_nos + "," + item_artno;
                                                if (!changed_price.accessories[a].art_no_old) {
                                                    changed_price.accessories[a]['art_no_old'] = ao.art_nos;
                                                }
                                            }
                                        }
                                    }

                                    //check switch articles
                                    var switch_art = false;
                                    if (!add_art && o.switch_art_nos && o.switch_art_nos != 'null') {
                                        var aritcles = o.switch_art_nos;

                                        for (var item_artno in aritcles) {
                                            var condition = checkRule(aritcles[item_artno].condition);
                                            var switchPrice = aritcles[item_artno].price;


                                            if (condition.check) {
                                                changed_price.accessories[a].price = switchPrice;
                                                changed_price.accessories[a].art_nos = item_artno;
                                                changed_price.accessories[a]['art_no_old'] = ao.art_nos;
                                                switch_art = true;
                                                break;
                                            }
                                        }
                                    }

                                    
                                    //check switch by selection
                                    //check switch articles
                                    if (!(add_art || switch_art) && o.switch_by_selection && o.switch_by_selection.varient != 'null') {
                                        console.log("inside switch art");
                                        var condition = checkRule(o.switch_by_selection.condition);
                                        if (condition.check) {
                                            var varients = o.switch_by_selection.varient;
                                            var html = "<span class='taw-acs-variant-title'>title</span> <div style='position:absolute' class='taw-acs-variant-selection-hld'>";

                                            for (var art_no in varients) {
                                                var obj = varients[art_no];
                                                console.log(obj);
                                                html += "<label data-key='" + a + "' data-price='" + obj.price + "' data-artno='" + art_no + "' data-src='" + obj[1] + "' data-title='" + obj[0] + "'><span class='item-chek'></span></label>";
                                            }

                                            html += "</div>";

                                            var o = $('.taw-acs-slider-item[data-cate="' + a + '"][data-artno="' + ao.art_nos + '"]');

                                            o.attr("data-variant", 1);
                                            o.find("img").css({ "height": "72%", "marginTop": "12px" });
                                            o.find(".iihld").css("padding", "2px").append(html);
                                            o.find(".taw-acs-variant-selection-hld label:first").trigger("click");

                                            break;
                                        }
                                    }



                                }
                            }
                        }

                        /***** update price */
                        var price = 0;
                        for (var a in changed_price.accessories) {
                            var ao = changed_price.accessories[a];

                            price += parseInt(ao.price);
                            var art_no = (ao.art_no_old) ? ao.art_no_old : ao.art_nos;
                            $('.taw-acs-slider[data-cate="' + a + '"] .taw-acs-slider-item[data-artno="' + art_no + '"]').find(".price-txt").text("+" + ao.price + " kr");
                        }

                        activePkg.find("p.price").text("+" + price + " kr");


                    } else if (typeof article_condition[form.material.id] != "undefined") {
                        if (typeof article_condition[form.material.id][key] != "undefined") {

                            var rules = article_condition[form.material.id][key];


                            for (var i = 0; i < rules.length; i++) {
                                var o = rules[i];

                                var check = false;
                                if (key == "sidelight") {
                                    var oar = o.option_id.split("-");
                                    if (oar.length == 4) {
                                        var side_type = oar[0];
                                        var door_w = parseInt(oar[1]);
                                        var side_w_min = parseInt(oar[2]);
                                        var side_w_max = parseInt(oar[3]);

                                        var c_door_w = parseInt(form.size.size_text.split("x")[0]);

                                        if (side_type == 'single' && (form.sidelight.type == 'left' || form.sidelight.type == 'right') || side_type == form.sidelight.type) {
                                            if (door_w == c_door_w) {
                                                if (form.sidelight.size >= side_w_min && form.sidelight.size <= side_w_max) {
                                                    check = true;
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    check = (o.option_id == eval('form.' + key + "." + optionIdMap[key]));
                                }

                                if (check) {

                                    var item = $("#taw-blk-prod-" + key + " li.active");
                                    var price = parseInt(item.attr("data-price"));
                                    var artno = item.attr("data-artnos");

                                    //check add artno
                                    var add_art = false;
                                    if (o.add_art_nos && o.add_art_nos != 'null') {
                                        var aritcles = o.add_art_nos;

                                        for (var item_artno in aritcles) {
                                            var condition = checkRule(aritcles[item_artno].condition);

                                            if (condition.check) {
                                                add_art = true;
                                                price = price + parseInt(aritcles[item_artno].price);
                                                changed_price[propMap[key]][priceKeyMap[key]].price = price;
                                                changed_price[propMap[key]][priceKeyMap[key]].art_nos = artno + "," + item_artno;
                                            }
                                        }
                                    }

                                    if (!add_art && o.switch_art_nos && o.switch_art_nos != 'null') {
                                        var aritcles = o.switch_art_nos;

                                        for (var item_artno in aritcles) {
                                            var condition = checkRule(aritcles[item_artno].condition);
                                            var switchPrice = aritcles[item_artno].price;


                                            if (condition.check) {

                                                //changed_price[key][priceKeyMap[key]].action = "-";
                                                changed_price[propMap[key]][priceKeyMap[key]].art_nos = item_artno;

                                                //$("li.product-item-base.active[data-type")
                                                if (condition.prop.type) {
                                                    changed_price[propMap[key]][priceKeyMap[key]].price = 0;
                                                    changed_price[propMap[condition.prop.type]][priceKeyMap[condition.prop.type]].price = switchPrice;
                                                }

                                                break;
                                            }
                                        }
                                    }
                                }
                            }

                        }
                    }
                }
            }

            //update price lable
            for (var key in form) {
                setPriceTxt(key);
            }
        }

        function updateNoPackageConditionUI() {
           
            var rules = article_condition['products']
         
            if(typeof rules=="undefined"){
                return;
            }

            if(rules.product){
                rules=rules.product;
            }

            for (var i = 0; i < rules.length; i++) {
                var o = rules[i];
                var condition_art_no=o.art_no;



                //check add artno
                var add_art = false;
               // var price = parseInt(ao.price);
                if (o.add_art_nos && o.add_art_nos != 'null') {
                    var aritcles = o.add_art_nos;

                    for (var item_artno in aritcles) {
                        var condition = checkRule(aritcles[item_artno].condition);
                        console.log(aritcles[item_artno]);
                        if (condition.check) {
                            add_art = true;
                            var add_price=parseInt(aritcles[item_artno].price);
                            $('.taw-acs-slider-item[data-artno="' + condition_art_no + '"]').each(function(){
                                var elm=$(this);
                                var art_no=elm.attr("data-artno");
                                var price=parseInt(elm.attr("data-price"));
                                price = price + add_price;
                                elm.attr("data-price",price);
                                elm.attr("data-artno",art_no + "," + item_artno);
                                elm.find(".price-txt").text(price);
                               
                            });
                        }
                    }
                }

                //check switch articles
                var switch_art = false;
                if (!add_art && o.switch_art_nos && o.switch_art_nos != 'null') {
                    var aritcles = o.switch_art_nos;

                    for (var item_artno in aritcles) {
                        var condition = checkRule(aritcles[item_artno].condition);
                        var switchPrice = aritcles[item_artno].price;


                        if (condition.check) {
                            $('.taw-acs-slider-item[data-artno="' + condition_art_no + '"]').each(function(){
                                var elm=$(this);
                                elm.attr("data-price",switchPrice);
                                elm.attr("data-artno",item_artno);
                                elm.find(".price-txt").text(switchPrice);
                            });
                            switch_art = true;
                            break;
                        }
                    }
                }

                //check switch by selection
                //check switch articles
                if (!(add_art || switch_art) && o.switch_by_selection && o.switch_by_selection.varient != 'null') {
                   
                    var condition = checkRule(o.switch_by_selection.condition);
                    if (condition.check) {
                        var varients = o.switch_by_selection.varient;
                        var html = "<span class='taw-acs-variant-title'>title</span> <div style='position:absolute' class='taw-acs-variant-selection-hld'>";

                        $('.taw-acs-slider-item[data-artno="' + condition_art_no + '"]').each(function(){
                           
                            var elm=$(this);
                            var cate_id=elm.attr("data-cate");
                            for (var art_no in varients) {
                                var obj = varients[art_no];                            
                                html += "<label data-key='" + cate_id + "' data-price='" + obj.price + "' data-artno='" + art_no + "' data-src='" + obj[1] + "' data-title='" + obj[0] + "'><span class='item-chek'></span></label>";
                            }
    
                            html += "</div>";
    
                            elm.attr("data-variant", 1);
                            elm.find("img").css({ "height": "72%", "marginTop": "12px" });
                            elm.find(".iihld").css("padding", "2px").append(html);                      
                            elm.find(".taw-acs-variant-selection-hld label:first").trigger("click");
                        });                           
                    }
                    
                }
            }
        }

        function setPriceTxt(key, sign) {
            if (key == 'material' || key == 'glass' || key == 'size' || key == 'kickplate' || key == 'sidelight') {
                if (key == "size") {
                    var item = $("#taw-blk-prod-" + key + "");
                } else if (key == "sidelight") {
                    var item = $("#taw-blk-prod-sidelight-opt");
                } else {
                    var item = $("#taw-blk-prod-" + key + " li.active");
                }


                var price;

                if (changed_price[propMap[key]][priceKeyMap[key]]) {
                    price = changed_price[propMap[key]][priceKeyMap[key]].price;
                }

                if (key != "material") {
                    price = "+" + price;
                }

                if (item.length > 0) {

                    item.find(".taw-p-price-txt").text(price + " kr");

                    //when size set also set inner values
                    if (key == 'size') {
                        key = 'hinge';
                        if (changed_price[propMap[key]][priceKeyMap[key]]) {
                            price = changed_price[propMap[key]][priceKeyMap[key]].price;
                            $("#taw-blk-prod-hinge .taw-p-price-txt").text(price + " kr");
                        }

                    }
                }

            }
        }

        function checkRule(rules) {
            var all_true;
            var matched_prop;
            /*** first match rule set check */
            for (var index in rules) {
                all_true = true;
                matched_prop = {};

                var rule = rules[index];

                /***** && condtion check */
                for (var prop in rule) {
                    var cont_ar = rule[prop];
                    var cont = cont_ar[0];
                    var chk_val = cont_ar[1];

                    var val_ar = chk_val.split(",");

                    var orCond = false;

                    var wrap;
                    /**** || conditon check */
                    for (var vv = 0; vv < val_ar.length; vv++) {

                        chk_val = (val_ar[vv].split("::")[0]);

                        if (isNaN(chk_val)) {
                            chk_val = chk_val.toLowerCase();
                        }

                        if (ruleMap[prop]) {
                            var v = eval(ruleMap[prop]);
                            if (prop == "Door Width") {
                                v = v.split("x")[0];
                            } else if (prop == "Door Height") {
                                v = v.split("x")[1];
                            }

                            if (isNaN(v)) {
                                wrap = "'";
                                v = v.toLowerCase();
                            } else {
                                wrap = "";
                            }
                            if (!eval(wrap + v + wrap + cont + wrap + chk_val + wrap)) {
                                orCond = false;
                            } else {

                                if (propType[prop]) {
                                    matched_prop = { "type": propType[prop], "val": chk_val };
                                }
                                orCond = true;
                                break;
                            }
                        }
                    }
                    if (!orCond) {
                        all_true = false;
                        break;
                    }
                }
                if (all_true) {
                    break;
                }
            }

            return { "check": all_true, "prop": matched_prop };
        }





        $(document).ready(function () {
            initPage();
            initCommon();



            $("body").undelegate(".taw-toggle-cont-title", "click").delegate(".taw-toggle-cont-title", "click", function () {
                $(this).closest(".taw-toggle-cont-hld").toggleClass("active");
                var type = $(this).attr("data-type");
                if (type == "make-owm-pkg") {
                    setPackageAcs({}, free_txt.make_own_pkg);
                }
            });

            $("body").undelegate("#taw-blk-prod-size li", "click").delegate("#taw-blk-prod-size li", "click", function () {
                $("#taw-blk-prod-size li").removeClass("active");
                $(this).addClass("active");

                form.size.size_id = $(this).val();
                form.size.size_text = $(this).text();
                item_price.size.size.price = $(this).attr("data-price");
                item_price.size.size.art_nos = $(this).attr('data-artnos');

                $("#taw-blk-prod-size-price-txt").html("+" + item_price.size.size.price);
                updatePrice();

                //check side light option for height
                var h = parseInt(form.size.size_text.split("x")[1]);
                if (h >= 22) {
                    if (!$("#taw-blk-prod-sidelight").hasClass("hide-cont")) {
                        sidelight_hide_by_size = 1;
                        //hide side light options click none item
                        $("#taw-blk-prod-sidelight ul.blocks-gallery-grid li:first").trigger("click");
                        $("#taw-blk-prod-sidelight").addClass("hide-cont");
                    }

                } else {
                    if (sidelight_hide_by_size == 1) {
                        $("#taw-blk-prod-sidelight").removeClass("hide-cont");
                    }
                }
                console.log(h);

                //setSideLightImg(form.sidelight.type,$("#co9n .image img:first"),360);

                //updateSideLightHtml();
            });

            $("body").undelegate("#taw-blk-prod-hinge li", "click").delegate("#taw-blk-prod-hinge li", "click", function () {

                var v = $(this).attr("data-val");
                $("#taw-blk-prod-hinge li").removeClass("active");
                $(this).addClass("active");
                form.size.hinge = v;

                setSideLightImg("main", form.sidelight.type, "", final_size, IMG_RES);
                item_price.size.hinge.price = $(this).attr("data-price");
                item_price.size.hinge.art_nos = $(this).attr("data-artnos");

                $("#taw-blk-prod-hinge-price-txt").html("+" + item_price.size.hinge.price);
                updatePrice();
                //saveOptionsInCookie();

            });

            $("body").undelegate("#taw-blk-prod-event-additional-opt li", "click").delegate("#taw-blk-prod-event-additional-opt li", "click", function () {
                $(this).closest("ul").find("li").removeClass("active");
                $(this).addClass("active");
                var type = $(this).closest("ul").attr("data-type");
                var val = $(this).attr("data-val");
                form.additional[type] = val
                item_price.additional[type].price = $(this).attr("data-price");
                item_price.additional[type].art_nos = $(this).attr("data-artnos");
                $("#taw-blk-prod-price-" + type).html("+" + item_price.additional[type].price);

                var f3 = family_name.substr(0, 3);
                if (f3 == "YDR" || f3 == "YDS") {
                    if (type == "door_open") {
                        if (val == "inward") {
                            getAccessoriesDoorPkgs(f3 + '_I', function (res) {
                                if (res) {
                                    updateHtml('packages');
                                }
                            })
                        } else {
                            updateHtml('packages');
                        }
                    }
                }

                updatePrice();
            });

            $("body").undelegate("#taw-blk-prod-sliding-door li", "click").delegate("#taw-blk-prod-sliding-door li", "click", function () {
                $(this).closest("ul").find("li").removeClass("active");
                $(this).addClass("active");

                var type = $(this).attr("data-val");

                var activeMaterial = $("#taw-blk-prod-material ul li.active");
                var sliding_door = "";
                form.material.sliding_door = type;
                if (type == "normal") {

                    updateHtml('packages');

                    item_price.material.base.art_nos = activeMaterial.attr("data-artnos");
                    item_price.material.base.price = activeMaterial.attr("data-price");
                } else {
                    item_price.material.base.art_nos = activeMaterial.attr("data-sliding-artno");
                    item_price.material.base.price = activeMaterial.attr("data-sliding-price");
                    sliding_door = item_price.material.base.art_nos;
                    getAccessoriesDoorPkgs('SL', function (res) {
                        if (res) {
                            updateHtml('packages');
                        }
                    })
                }

                form.material.sliding_door_artno = sliding_door;

                $("#taw-blk-prod-sliding-door .flex-none").html(item_price.material.base.price);

                updatePrice();
            });

            $("body").undelegate(".taw-edit-package", "click").delegate(".taw-edit-package", "click", function () {
                var pkg = $(this).closest("li").attr("data-artnos-cat");
                pkg = JSON.parse(pkg);
                var title = $(this).closest("li").find(".taw-title").html();
                setPackageAcs(pkg, free_txt.edit + "" + title);
            });

            $("body").undelegate(".taw-acs-slider-item", "click").delegate(".taw-acs-slider-item", "click", function (e) {

                var has_active = $(this).hasClass("active");
                $(this).closest(".taw-acs-slider").find(".taw-acs-slider-item").removeClass("active");

                var cat_id = $(this).attr("data-cate");
                cat_id = $.trim(cat_id);
                var all_cate = $(this).closest(".taw-acs-slider").attr("data-cate");
                all_cate = all_cate.replace(",", "::");

                if (!has_active) {
                    $(this).addClass("active");
                    var o = $(this);
                    item_price.accessories[all_cate] = { action: '+', price: o.attr("data-price"), art_nos: o.attr("data-artno"), img: o.find("img").attr("src"), title: o.find("p.grid span:first").text(), cate_id: cat_id }

                } else {
                    delete item_price.accessories[all_cate];
                }

                var cur_parent = $(this).closest(".taw-acs-slider").attr("data-parent-cate");
                var parent_cates = {};
                $(".taw-acs-slider").each(function () {
                    var parent = $(this).attr("data-parent-cate");
                    if (parent) {
                        if (parent != cur_parent) {
                            $(this).addClass("disabled");
                            $(this).find(".slick-list .taw-acs-slider-item.active").trigger("click");
                        }
                        var ps = parent.split(",");
                        for (var i = 0; i < ps.length; i++) {
                            parent_cates[ps[i]] = parent;
                        }
                    }
                });

                for (var key in item_price.accessories) {
                    var c = item_price.accessories[key].cate_id;
                    if (parent_cates[c]) {
                        var el = $(".taw-acs-slider[data-parent-cate='" + parent_cates[c] + "'");
                        if (el.length > 0) {
                            el.removeClass("disabled");
                        }
                    }

                }

                //console.log(parent_cates,all_cates);

                // var This=$(this);
                // var cur_cat_id=This.attr("data-cate");
                // if(cur_cat_id!=cat_id){
                //     var cate=$.trim(This.attr("data-parent-cate"));
                //     var cate_ar=cate.split(",");
                //     for(var i=0;i<cate_ar.length;i++){                        
                //         var cat=$.trim(cate_ar[i]);
                //         if(cat){                          
                //             if(cat==cat_id){
                //                 This.removeClass("disabled");
                //             }else{
                //                 This.addClass("disabled");
                //                 This.find(".slick-list .taw-acs-slider-item.active").trigger("click");
                //             }
                //         }                        
                //     }    
                // }               

                //console.log(item_price.accessories);

                updatePrice();
            });


            $("body").undelegate("#taw-blk-prod-sidelight-opt-size li", "click").delegate("#taw-blk-prod-sidelight-opt-size li", "click", function () {

                var cursize = parseInt($(this).attr("data-val"));
                form.sidelight.size = cursize;
                $("#taw-blk-prod-sidelight-opt-size li").removeClass("active");
                $(this).addClass("active");

                var door_w = parseInt(form.size.size_text.split("x")[0]);
                var main_img = $("#co9n .image img:first");
                var width = 480;
                var onepx = width / door_w;

                final_size = ((cursize - door_w) * onepx) - 2 * 34;

                if (form.sidelight.type == "both") {
                    final_size = final_size / 2;
                }

                setSideLightImg("main", form.sidelight.type, "", final_size, IMG_RES);

                item_price.sidelight.type.price = $(this).attr("data-price");
                item_price.sidelight.type.art_nos = $(this).attr("data-artnos");

                $("#taw-blk-prod-sidelight-opt .taw-p-price-txt").text("+" + item_price.sidelight.type.price);
                updatePrice();
            });

            $("body").undelegate(".taw-glass-type-opt li", "click").delegate(".taw-glass-type-opt li", "click", function () {
                var type = $(this).closest("ul").attr("data-type");
                form[type].glass_type = $(this).attr("data-val");
                var price = $(this).attr("data-price");

                if (type == "sidelight" && form.sidelight.type == "both") {
                    price = parseInt(price) * 2;
                }

                item_price[type].glass_type.price = price;
                $(this).closest("ul").find("li").removeClass("active");
                $(this).addClass("active");
                updatePrice();
                //taw-blk-prod-price-glasstype-sidelight
                $("#taw-blk-prod-price-glasstype-" + type).html("+" + item_price[type].glass_type.price);
            });



            $("body").undelegate("#taw_prod_custom_back_btn", "click").delegate("#taw_prod_custom_back_btn", "click", function () {
                // $("#prod-customize-summary-hld").css("display", "none");
                // $("#prod-customize-hld").css("display", "block");

                //$(".taw-acs-slider").slick("resize");
                //$("#co9n").slick("resize");

                location.reload();
            });

            $("body").undelegate("#taws_place_order_frm", "submit").delegate("#taws_place_order_frm", "submit", function (e) {
                var country = $("#taw_f_prod_shipment_country").val();
                var postal_code = $("#taw_f_shipment_post_code").val();
                var city = $("#taw_f_shipment_city").val();
                if (postal_code == '') {
                    $("#taw_f_shipment_post_code").css("borderColor", "red");
                    $("#taw_f_shipment_post_code").attr("placeholder", free_txt.required);
                    return false;
                } else {
                    $(this).find('input[name="country"]').val(country);
                    $(this).find('input[name="post_code"]').val(postal_code);
                    $(this).find('input[name="city"]').val(city);
                    $("#taw_f_shipment_post_code").css("borderColor", "#eaeaea");
                }
                return true;

            });

            $("#view_summary_btn").on('click', function () {


                setFinalImage();


                var w = $(window).width();
                if (w > 768) {
                    var hld_h = $("#content-custom-summary-hld").height();
                    var title_h = ($(".hideable-item-h").length * 60) + 30;
                    $("#taw-prod-slider-img-hld-cont").css("height", (hld_h - title_h) + "px");
                    $(".hideable-item-c").css("height", ((hld_h - title_h) / 3) + "px");
                }

                var order = {};

                //set wood type
                var title = free_txt.wood_type;
                var v = form.material.title;
                var a_n = changed_price.material.base.art_nos;
                if (form.material.custom_color != "") {
                    v += free_txt.color + " - " + form.material.custom_color;
                }

                if (form.material.sliding_door_artno != "") {
                    v += " - " + free_txt.sliding_door;
                    a_n = form.material.sliding_door_artno;
                }

                var price = changed_price.material.base.price;
                var html = getSummaryListItem(title, v, price);
                order['Wood Type'] = { art_nos: a_n, title: v, price: price, custom_color: form.material.custom_color }

                if (form.glass.id != 0 && $("#taw-blk-prod-glass").is(":visible")) {

                    //set glass option
                    title = free_txt.glass_type;
                    v = form.glass.title;
                    price = changed_price.glass.glass.price;
                    order['Glass Type'] = { title: v, price: price, art_nos: changed_price.glass.glass.art_nos }
                    html += getSummaryListItem(title, v, price);
                }

                //set glass style 
                if ($("#taw-blk-prod-glass-opt").is(":visible")) {
                    title = free_txt.glass_style;
                    v = form.glass.glass_type;
                    price = changed_price.glass.glass_type.price;
                    order['Glass Style'] = { title: v, price: price, art_nos: changed_price.glass.glass_type.art_nos }
                    html += getSummaryListItem(title, v, price);
                }

                //set size
                title = free_txt.size;
                v = form.size.size_text;
                price = changed_price.size.size.price;
                order['Size'] = { title: v, price: price, art_nos: changed_price.glass.glass_type.art_nos }
                html += getSummaryListItem(title, v, price);

                //set hinge
                title = free_txt.hinge;
                v = free_txt[form.size.hinge] ?? form.size.hinge;
                price = changed_price.size.hinge.price;
                order['Hinge'] = { title: v, price: price, art_nos: changed_price.size.hinge.art_nos }
                html += getSummaryListItem(title, v, price);

                //set kickplate
                if (form.kickplate.id != 0 && $("#taw-blk-prod-kickplate").is(":visible")) {
                    title = free_txt.kickplate;
                    v = form.kickplate.title;
                    price = changed_price.kickplate.base.price;
                    order['Kickplate'] = { title: v, price: price, art_nos: changed_price.kickplate.base.art_nos }
                    html += getSummaryListItem(title, v, price);
                }

                //set kickplate
                var side_size = 0;
                if (form.sidelight.type != "none" && $("#taw-blk-prod-sidelight").is(":visible")) {
                    title = free_txt.sidelight;
                    side_size = form.sidelight.size;
                    v = free_txt.type + " : " + (free_txt[form.sidelight.type] ?? form.sidelight.type) + ", " + free_txt.size + " : " + form.sidelight.size;
                    price = changed_price.sidelight.type.price;
                    order['Sidelight'] = { title: v, price: price, art_nos: changed_price.sidelight.type.art_nos, type: form.sidelight.type }
                    html += getSummaryListItem(title, v, price);

                    title = free_txt.sidelight_glass_style;
                    v = form.sidelight.glass_type;
                    price = changed_price.sidelight.glass_type.price;
                    order['Sidelight Glass Style'] = { title: v, price: price, art_nos: changed_price.sidelight.glass_type.art_nos }
                    html += getSummaryListItem(title, v, price);
                }

                //hide additional option when inner door
                if ($("#taw-blk-prod-additional").is(":visible")) {
                    //set door open
                    title = free_txt.door_open;
                    v = free_txt[form.additional.door_open] ?? form.additional.door_open;
                    price = changed_price.additional.door_open.price;
                    order['Door Open'] = { title: v, price: price, art_nos: changed_price.additional.door_open.art_nos }
                    html += getSummaryListItem(title, v, price);

                    //set Frame extension
                    title = free_txt.frame_extension;
                    v = free_txt[form.additional.frame_extn] ?? form.additional.frame_extn;
                    price = changed_price.additional.frame_extn.price;
                    order['Frame Extension'] = { title: v, price: price, art_nos: changed_price.additional.frame_extn.art_nos }
                    html += getSummaryListItem(title, v, price);
                }

                var acs_html = '';
                order["Accessories"] = [];

                for (var acs in changed_price.accessories) {
                    var obj = changed_price.accessories[acs];

                    order["Accessories"].push({ title: obj.title, price: obj.price, art_nos: obj.art_nos });
                    acs_html += '<div><div class="border rounded h-24 grid place-content-center iihld"><img class="h-20 w-20" src="' + obj.img + '" /></div><p class="text-sm">' + obj.title + '</p><p class="text-sm"> +' + obj.price + ' kr</p></div>';
                }

                if (acs_html != '') {
                    title = free_txt.accessories;
                    html += '<li class="my-2" >' +
                        '<div class="grid grid-cols-1">' +
                        '<div >' +
                        '<h3 class="text-xl pb-1" >' + title + '</h3> <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5  gap-3">' +
                        acs_html +
                        '</div></div>' +
                        '</div>' +
                        '</li>';
                }

                $("#summary-cont-selected-items").html(html);

                order['extra'] = { 'price': TotalPrice, 'sidelight_size': side_size, 'family_id': family_id, 'family_name': family_name, "pid": form.extra.pid, "prod_type": form.extra.prod_type, "title": prod_title, "final_img_id": form.extra.final_img_id };
                order = JSON.stringify(order);

                $("#taw_form_order input[name='order_data']").val(order);

                if (isPreConfig == 1) {
                    $("#taw_form_order input[name='form_data']").val(JSON.stringify(form));
                    var art_nos = [];
                    for (var key in changed_price) {
                        if (changed_price.hasOwnProperty(key)) {
                            var priceObj = changed_price[key];
                            for (var p in priceObj) {
                                if (priceObj.hasOwnProperty(p)) {
                                    var ar = priceObj[p].art_nos.split(",");
                                    ar.forEach(function (e) {
                                        e = $.trim(e);
                                        if (e && e != '0') {
                                            art_nos.push(e);
                                            if (key == "sidelight" && form.sidelight.type == "both") {
                                                art_nos.push(e)
                                            }
                                        }

                                    });
                                }
                            }
                        }
                    }

                    $("#taw_form_order input[name='price_article']").val(art_nos);
                    $("#taw_form_order input[name='final_price']").val(TotalPrice);
                }

                $("#prod-customize-summary-hld").css("display", "block");
                $("#prod-customize-hld").css("display", "none");


            });

            $("body").undelegate("#taw_f_shipment_post_code,#taw_f_prod_quantity", "change")
                .delegate("#taw_f_shipment_post_code,#taw_f_prod_quantity", "change", function () {
                    $("#taw_shipment_loader").addClass("active");
                    var qty = $("#taw_f_prod_quantity").val();
                    var country = $("#taw_f_prod_shipment_country").val();
                    var postal_code = $("#taw_f_shipment_post_code").val();

                    jQuery.ajax({
                        type: "POST",
                        url: aurl,
                        data: {
                            action: "calculate_shipment_cost",
                            qty: qty,
                            size: form.size.size_text,
                            sidelight_size: form.sidelight.size,
                            country: country,
                            postal_code: postal_code,
                            family_name: family_name
                        },
                        success: function (data) {
                            $("#taw_shipment_loader").removeClass("active");
                            $("#taw_f_shipment_post_code").css("borderColor", "#eaeaea");
                            if (data.success) {

                                //item_price.shipping.base.price=data.data.total_cost;
                                //updatePrice();
                                $("#taw_shipment_cost").text(data.data.total_cost);
                            } else {
                                alert("something went wrong")
                            }

                        }

                    });
                });



            $("body").undelegate(".hideable-item-head", "click").delegate(".hideable-item-head", "click", function () {

                var w = $(window).width();
                var hld = $(this).closest('.hideable-item-h');
                var active = hld.hasClass("active");
                $(".hideable-item-h").removeClass('active');
                if (w > 768) {
                    var hld_h = $("#content-custom-summary-hld").height();
                    var title_h = ($(".hideable-item-h").length * 60) + 30;
                    if (active) {
                        $("#taw-prod-slider-img-hld-cont").css("height", (hld_h - title_h) + "px");
                        hld.removeClass("active");
                    } else {
                        $("#taw-prod-slider-img-hld-cont").css("height", ((hld_h - title_h) / 3) + "px");
                        hld.addClass("active");
                    }
                } else {

                    if (active) {
                        hld.removeClass("active");
                    } else {
                        hld.addClass("active");
                    }
                }

            });

            $("body").undelegate("#taw_f_custom_color_enter", "keyup").delegate("#taw_f_custom_color_enter", "keyup", function () {

                var code = "#" + $(this).val();
                var d = $(this).closest("div");

                d.css("background", code);
                d.attr("data-color", code);
            });

            $("body").undelegate("#taw_f_custom_color_enter", "change").delegate("#taw_f_custom_color_enter", "change", function () {

                var prihld = $("#taw-blk-prod-material li.t-c-select.active .taw_choose_custom_color_hld");
                prihld.css("background", "#" + $(this).val());
                form.material.custom_color = "#" + $(this).val();
                updatePrice();
            });

            $("body").undelegate(".taw_f_prod_quantity_hld span", "click").delegate(".taw_f_prod_quantity_hld span", "click", function () {

                var qty = $("#taw_f_prod_quantity").val();
                var action = $(this).attr("data-action");
                if (action == "plus") {
                    qty++;
                } else {
                    if (qty > 1) {
                        qty--;
                    }
                }
                $("#taw_f_prod_quantity").val(qty);

            });




            $("#taw_form_order form").on('submit', function (e) {
                //e.preventDefault();                
                var qty = $("#taw_f_prod_quantity").val();
                $(this).find("input[name='qty']").val(qty);
                if (form.sidelight.type != "none") {
                    var img_data = $("#taw-prod-slider-img-hld .main-img").attr("src");
                    $(this).find("input[name='img_data']").val(img_data);
                } else {
                    $(this).find("input[name='img_data']").val("");
                }
            });

            $("body").undelegate("#btn-share-link", "click").delegate("#btn-share-link", "click", function () {

                var url = $(this).attr("data-url");
                var This = this;
                if (url == "") {
                    $(this).find(".share-btn").addClass("aloader");
                    jQuery.ajax({
                        type: "POST",
                        url: aurl,
                        data: {
                            action: "getShareLink",
                            family_id: family_id,
                            family_name: family_name,
                            form_data: form
                        },
                        success: function (data) {
                            $(This).find(".share-btn").removeClass("aloader");
                            $(This).attr("data-url", data.data);
                            tippy(This, {
                                content: '<div class="flex"><input id="taw_url_to_share" type="text" value="' + data.data + '"> <button class="p-4" id="taw_url_to_share_cpy">Copy</button></div>',
                                theme: 'light',
                                hideOnClick: 'toggle',
                                trigger: "click",
                                allowHTML: true,
                                interactive: true,
                            });

                            $(This).trigger("click");

                            $("body").undelegate("#taw_url_to_share_cpy", "click").delegate("#taw_url_to_share_cpy", "click", function () {
                                /* Get the text field */
                                var copyText = document.getElementById("taw_url_to_share");

                                /* Select the text field */
                                copyText.select();
                                copyText.setSelectionRange(0, 99999); /* For mobile devices */


                                document.execCommand("copy");

                            });

                        },
                        error: function (errorThrown) {
                            alert("something went wrong, please try again later");
                        }
                    });
                }
            });


            $("body").undelegate(".taw-acs-variant-selection-hld label", "click").delegate(".taw-acs-variant-selection-hld label", "click", function (e) {
                e.stopPropagation();
                var key = $(this).attr("data-key");
 
                $(this).closest(".taw-acs-variant-selection-hld").find("label").removeClass("active");
                $(this).addClass("active");
                var img = $(this).attr("data-src");
                var price = parseInt($(this).attr("data-price"));
                
                var title = $(this).attr("data-title");
                $(this).closest(".iihld").find("img").attr("src", img);
                $(this).closest(".iihld").find(".taw-acs-variant-title").text(title);
                $(this).closest(".taw-acs-slider-item").find(".price-txt").text("+" + price);

                if (item_price.accessories[key]) {
                    var artno = $(this).attr("data-artno");
                    var old_price = item_price.accessories[key]['price'];
                    item_price.accessories[key]['price'] = price;
                    item_price.accessories[key]['img'] = img;
                    item_price.accessories[key]['art_nos'] = artno;
                    var pkg_price = (pkg_price - old_price) + price;
                    $('#taw-blk-prod-packages').find("li.active p.price").text("+" + pkg_price);
                    //updatePrice();
                }
                

            });

        });



        function initTooltip() {
            tippy('.tippy', {
                content(reference) {
                    const id = reference.getAttribute('data-template');
                    const template = document.getElementById(id);
                    return template.innerHTML;
                },
                allowHTML: true,
                theme: 'light',
                animation: 'fade'
            });
        }

        function setFinalImage() {

            var main_img = $("#co9n .slick-slide:first img");
            if (form.sidelight.type == "none") {
                form.extra.final_img_id = $("#co9n .slick-slide:first img").attr("data-id");
            } else {
                form.extra.final_img_id = '';
            }
            $("#taw-prod-slider-img-hld .main-img").attr("src", main_img.attr("src"));
        }



        function resizeImgCanvas(img_src, elm, height) {
            var img = new Image();
            //var width = 160;


            img.onload = function () {
                var canvas = document.createElement('canvas'),
                    ctx = canvas.getContext("2d"),
                    oc = document.createElement('canvas'),
                    octx = oc.getContext('2d');

                canvas.height = height;
                canvas.width = canvas.height * img.width / img.height; // destination canvas size


                var cur = {
                    width: Math.floor(img.width * 0.5),
                    height: Math.floor(img.height * 0.5)
                }

                oc.width = cur.width;
                oc.height = cur.height;

                octx.drawImage(img, 0, 0, cur.width, cur.height);

                while (cur.height * 0.5 > height) {
                    cur = {
                        width: Math.floor(cur.width * 0.5),
                        height: Math.floor(cur.height * 0.5)
                    };
                    octx.drawImage(oc, 0, 0, cur.width * 2, cur.height * 2, 0, 0, cur.width, cur.height);
                }

                ctx.drawImage(oc, 0, 0, cur.width, cur.height, 0, 0, canvas.width, canvas.height);

                if (elm == "" || typeof elm == "undefined") {
                    elm = $("#co9n .image img:first");
                }
                elm.attr("src", canvas.toDataURL());
            }
            img.src = img_src;
        }

        function getAccessoriesDoorPkgs(pkg_code, cbk) {

            var code = 'packages_sl_door';
            if (pkg_code == "YDR_I" || pkg_code == 'YDS_I') {
                code = 'packages_inward_door';
            }

            if (lst_prod[code] == '') {
                jQuery("#taw-blk-prod-packages .blocks-gallery-grid").html(loaderHtml);
                jQuery.ajax({
                    type: "POST",
                    url: aurl,
                    data: {
                        action: "getAccessoriesDoorPkgs",
                        family_id: family_id,
                        pkg_code: pkg_code
                    },
                    success: function (data) {
                        lst_prod[code] = data;
                        cbk(true);
                    },
                    error: function (errorThrown) {
                        cbk(false);
                    }
                });
            } else {
                cbk(true);
            }

        }

        function initColorPicker() {


            $("body").undelegate(".taw-custom-color-item", "click").delegate(".taw-custom-color-item", "click", function () {
                var type = $(this).attr("data-type");
                $(".taw-custom-color-item").removeClass("active");
                $(this).addClass("active");

                var prihld = $("#taw-blk-prod-material li.t-c-select.active .taw_choose_custom_color_hld");
                var price = prihld.attr("data-" + type + "-price");
                var art_nos = prihld.attr("data-" + type + "-artnos");

                item_price.material.base.price = price;
                item_price.material.base.art_nos = art_nos;

                prihld.closest("li").find(".taw-p-price-txt").text("+ " + price + " kr");
                prihld.css("background", $(this).attr("data-color"));

                if (type == "custom") {
                    form.material.custom_color = $(this).attr("data-color");

                    if ($(this).attr("data-input") == "1") {
                        form.material.custom_color_input = 1;
                    } else {
                        form.material.custom_color_input = 0;
                    }
                } else {
                    form.material.custom_color = "";
                }

                updatePrice();
            });


            if (form.additional.frame_extn != "") {
                $("#taw-blk-prod-event-additional-opt ul[data-type='frame_extn'] li[data-val='" + form.additional.frame_extn + "']").trigger("click");
            }

            if (form.additional.door_open != "") {
                $("#taw-blk-prod-event-additional-opt ul[data-type='door_open'] li[data-val='" + form.additional.door_open + "']").trigger("click");
            }

            if (form.size.hinge != "") {
                $("#taw-blk-prod-hinge li[data-val='" + form.size.hinge + "']").trigger("click");
            }

            if (form.glass.title != "") {
                $("#taw-glass-type-opt li[data-title='" + form.glass.title + "']").trigger("click");
            }

            if (form.material.custom_color != "") {
                if (form.material.custom_color_input == 1) {
                    $(".taw-custom-color-item[data-input='1']").attr("data-color", form.material.custom_color);
                    $(".taw-custom-color-item[data-input='1']").css("background", form.material.custom_color);
                    $(".taw-custom-color-item[data-input='1'] input").val(form.material.custom_color.substring(1));
                }

                $(".taw-custom-color-item[data-color='" + form.material.custom_color + "']").trigger("click");

            }

            if (family_name.substr(0, 2).toLocaleLowerCase() == "id") {
                $("#taw-blk-prod-sliding-door ul li[data-val='" + form.material.sliding_door + "']").trigger("click");
            }
        }

        function getSummaryListItem(name, v, price) {
            if (price == 0) {
                price = "- ";
            } else {
                price = "+ " + price + "  " + currency_txt;
            }
            var html = '<li class="my-2" >' +
                '<div class="grid grid-cols-2">' +
                '<div >' +
                '<h3 class="text-xl">' + name + '</h3>' +
                '<p>' + v + '</p>' +
                '</div>' +
                '<div class="text-right">' +
                '<p class="price">' + price + '</p>' +
                '</div>' +
                '</div>' +
                '</li>';
            return html;
        }

        function setPackageAcs(pkg, title) {
            $("#taw-blk-prod-package-makeown .product_sub_heading").html(title);
            // $("#taw-blk-prod-package-makeown-item").show();

            var check = false;
            if (form.material.sliding_door == "slide") {
                if ($.isEmptyObject(acs_sl_all)) {
                    check = true;
                }
            } if (form.additional.door_open == "inward") {
                if ($.isEmptyObject(acs_inward_all)) {
                    check = true;
                }
            } else if ($.isEmptyObject(acs_all)) {
                check = true;
            }
            if (check) {
                getAccessoriesAll(function (res) {
                    if (res) {
                        setActivePackage(pkg);
                    }
                });
            } else {
                setActivePackage(pkg);
            }
        }

        function setActivePackage(pkg) {
            updateAcsHtml();
            item_price.accessories = {};


            $(".taw-acs-slider-item").removeClass("active");
            var pkg_price = 0;


            for (var key in pkg) {

                if (pkg.hasOwnProperty(key)) {
                    var list = pkg[key];
                    for (var p in list) {

                        if (list.hasOwnProperty(p)) {
                            var o = $(".taw-acs-slider-item[data-artno='" + list[p] + "']");

                            if (o.length > 0) {
                               
                                var cur_art_no = o.attr("data-artno");
                                var price = o.attr("data-price");
                                o.addClass("active");
                                pkg_price += parseInt(price);

                                item_price.accessories[key] = { action: '+', price: price, art_nos: cur_art_no, img: o.find("img").attr("src"), title: $(o[0]).find("span.pkg-title").text() };

                                var index = o.attr("data-slick-index");//index();
                                // alert(index);
                                $(o).closest(".taw-acs-slider").slick('slickGoTo', index);
                            }
                        }
                    }
                }
            }
            $('#taw-blk-prod-packages').find("li.active p.price").text("+" + pkg_price);
            updatePrice();
        }

        function checkPackageRule() {
            var activePkg = $('#taw-blk-prod-packages').find("li.active");
            var take_action = {};
            var art_actions = activePkg.attr("data-artnos-actions");

            if (art_actions) {
                art_actions = JSON.parse(art_actions);
            } else {
                art_actions = {};
            }

            for (var artno in art_actions) {
                var actions = art_actions[artno];

                take_action[artno] = { 'add_article': '', 'switch_article': '', 'switch_by_selection': '' };

                for (var action in actions) {
                    if (action == "add_article" || action == "switch_article" || action == "switch_by_selection") {

                        var item_artno = actions[action];
                        for (var i_artno in item_artno) {
                            var all_true = checkRule(item_artno[i_artno]['condition']);
                            take_action[artno][action][i_artno] = { "price": item_artno[i_artno]['price'], "condition": all_true };
                        }
                    }
                }

            }
            return take_action;
        }

        function getAccessoriesAll(cbk) {
            jQuery("#taw-blk-prod-package-makeown-item").html(loaderHtml);

            var pkg_code = $("#taw-blk-prod-packages li:first").attr("data-pkg-code");
            jQuery.ajax({
                type: "POST",
                url: aurl,
                data: {
                    action: "getCategoryAccessories",
                    family_name: family_name,
                    sliding_door: form.material.sliding_door,
                    door_open: form.additional.door_open,
                    pkg_code: pkg_code
                },
                success: function (data) {
                    if (form.material.sliding_door == "slide") {
                        acs_sl_all = data.data;
                    } else if (form.additional.door_open == "inward") {
                        acs_inward_all = data.data;
                    } else {
                        acs_all = data.data;
                    }
                    cbk(true);
                },
                error: function (errorThrown) {
                    cbk(false);
                }
            });

        }

        function updateAcsHtml() {
            var html = '';
            var item;
            if (form.material.sliding_door == "slide") {
                item = acs_sl_all;
            } if (form.additional.door_open == "inward") {
                item = acs_inward_all;
            } else {
                item = acs_all;
            }

            for (var key in item) {

                if (item.hasOwnProperty(key)) {

                    var cates = item[key];

                    var keys = key.split("<>");

                    var h = '<div class="flex my-3 text-xl mt-3">' +
                        '<h4 style="color:#373637" class="flex-grow">' + keys[0] + '</h4>' +
                        '</div>';

                    html += h;

                    var parent_category = $.trim(keys[2]);

                    html += '<div class="taw-acs-slider w-full ' + (parent_category ? 'disabled' : '') + '" data-cate="' + keys[1] + '" data-parent-cate="' + keys[2] + '">';
                    for (var c in cates) {
                        var items = cates[c];
                        for (var p in items) {
                            if (items.hasOwnProperty(p)) {
                                html += '<div data-cate="' + c + '" data-price="' + items[p].price + '" data-artno="' + items[p].artno + '"  class="taw-acs-slider-item text-xl text-center" title="outward">' +

                                    "<div class='border rounded w-full p-3 iihld aspect-[6/5]'><img class='' src='" + (items[p].img) + "' title='" + items[p].name + "' /></div>" +
                                    "<p class='h-10 text-sm overflow-hidden grid place-items-center'><span class='pkg-title'>" + items[p].name + "</span></p>" +
                                    "<p class='h-6 text-base price-txt'>+" + items[p].price + " kr</p>" +
                                    "</div>";
                            }
                        }
                    }
                    html += "</div>";
                }
            }

            $("#taw-blk-prod-package-makeown-item").html(html);

            setSliderForAcs();
           
            if(form.accessories.pkg_id=="0"){                
                updateNoPackageConditionUI();
            }
            

        }

        function setSliderForAcs() {

            $('.taw-acs-slider').slick({
                infinite: false,
                speed: 300,
                lazyLoad: 'ondemand',
                slidesToShow: 4,
                slidesToScroll: 4,
                responsive: [
                    {
                        breakpoint: 1440,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 4,
                            infinite: true,
                        }
                    },
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    }

                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ]
            });


        }

        function initPage() {
            setContentHeight();
            $(".taw-tab-cont:first").trigger("click");

            if (formCache) {
                var item;
                if (form_share) {
                    item = form_share;
                } else {
                    item = getCookie();
                }

                if (item) {
                    form = JSON.parse(item);
                }
            }

            //check family for inner door
            if (family_name.substring(0, 2) == "ID") {
                $("#taw-blk-prod-sliding-door").removeClass("hide-cont");
                $("#taw-blk-prod-sidelight").addClass("hide-cont");
                $("#taw-blk-prod-kickplate").addClass("hide-cont");
                $("#taw-blk-prod-additional").addClass("hide-cont");
            } else {
                $("#taw-blk-prod-sidelight").removeClass("hide-cont");
                $("#taw-blk-prod-kickplate").removeClass("hide-cont");
                $("#taw-blk-prod-additional").removeClass("hide-cont");

                var fmly = family_name.substring(0, 3);

                if (fmly == "YDR") {
                    $("#taw-blk-prod-glass-opt").removeClass("hide-cont");
                } else if (fmly == "YDL") {
                    $("#taw-blk-prod-additional").addClass("hide-cont");
                    $("#taw-blk-prod-sidelight").addClass("hide-cont");
                }
            }

            jQuery.ajax({
                type: "POST",
                url: aurl,
                data: {
                    action: "getProductFamilyItem",
                    family_id: family_id,
                    family_name: family_name,
                },
                success: function (data) {
                    lst_prod['material'] = data.data.material;
                    lst_prod['packages'] = data.data.packages;
                    //lst_prod['size'] = data.data.size;

                    if (form.size.size_text == "") {
                        form.size.size_text = data.data.size_default;
                    }


                    updateHtml('material');
                    updateHtml("packages");
                    //updateHtml("size");

                    setGlassTypes(data.data.glass_types, 'taw-glass-type-opt');
                    setGlassTypes(data.data.sidelight_glass_types, 'taw-sidelight-glass-type-opt');

                    initColorPicker();


                    initial_load = false;
                },
                error: function (errorThrown) {
                    alert("something went wrong");
                }
            });
        }

        function hidepageloader() {
            $("#taw-prod-loader").hide();
        }


        function setGlassTypes(item, id) {
            html = '';
            for (var i = 0; i < item.length; i++) {
                var o = item[i];
                var img = '<img class="rounded" src="' + o.pic + '" />';
                var cont = '<div><h4>' + o.name + '</h4><p>' + o.price + ' kr</p></div>';
                html += '<li data-id="' + o.id + '" data-price="' + o.price + '" data-artnos="' + o.art_nos + '" data-val="' + o.name + '" class="text-xl text-center py-2"><div>' + img + cont + '</div></li>'
            }
            $('#' + id).html(html);
        }



        function setMaterialForm(e) {
            var id = $(e).attr("data-id");

            if ($(e).find(".taw_choose_custom_color_hld").length > 0) {
                $(".taw-custom-color-hld").css("display", "grid");
            } else {
                $(".taw-custom-color-hld").css("display", "none");
            }

            if (initial_load || id != form.material.id) {
                //unset excecpt material
                lst_prod['glass'] = 0;
                // lst_prod['size'] = 0;
                //lst_prod['sidelight'] = 0;
                form.material.id = id;
                form.material.m_id = $(e).attr("data-material-id");
                form.material.title = $(e).find(".taw-p-title-txt").text();
                form.material.name = $(e).find(".taw-p-title-txt").attr("data-name");

                base_prod_html = $(e).prop('outerHTML');

                item_price.material.base.price = $(e).attr("data-price");
                item_price.material.base.art_nos = $(e).attr("data-artnos");

                //$(".taw-help-cont-hld img").attr("src", $(e).find("img").attr("src"));
                $(".taw-help-cont-hld img[src='']").attr("src", $(e).find("img").attr("src"));
                initTooltip();
                updatePrice();
                getProductOptionsByBase();
            }
        }

        function setKickplateForm(e) {

            var id = $(e).attr("data-id");
            form.kickplate.title = $(e).attr("data-kickplate");
            if (id == 0) {
                base_kickplate_html = '';

            } else {
                base_kickplate_html = $(e).prop('outerHTML');
            }
            if (id != form.kickplate.id) {
                form.kickplate.id = id;
                item_price.kickplate.base.price = $(e).attr("data-price");
                item_price.kickplate.base.art_nos = $(e).attr("data-artnos");
                updatePrice();
            }

            updateSideLightHtml();




        }

        function updateSideLightHtml() {

            if (sidelightloading) {
                return;
            }

            var html = base_prod_html;
            if (base_kickplate_html != '') {
                html = base_kickplate_html;
            } else if (base_glass_html != '') {
                html = base_glass_html;
            }

            $("#taw-blk-prod-sidelight .blocks-gallery-grid").html(html);

            if (typeof lst_prod['sidelight'][form.material.id] == "undefined") {
                if (typeof lst_prod['sidelight'] != "undefined") {
                    lst_prod['sidelight'] = {};
                }
                lst_prod['sidelight'][form.material.id] = {};
            }

            var reqSize = true;
            if (typeof lst_prod['sidelight'][form.material.id][form.size.size_id] != "undefined") {
                reqSize = false;
                sidelight_size_obj = lst_prod['sidelight'][form.material.id][form.size.size_id];
            }

            var reqImgs = true;
            if (typeof lst_prod['sidelight'][form.material.id]['imgs'] != "undefined") {
                reqImgs = false;
                sidelight_img_obj = lst_prod['sidelight'][form.material.id]['imgs'];
            }


            if (!reqImgs && !reqSize) {
                setSidlightListItem(html);
            } else {
                sidelightloading = true;
                jQuery.ajax({
                    type: "POST",
                    url: aurl,
                    data: {
                        action: "getProdSideLights",
                        material_name: form.material.name,
                        selected_size: form.size.size_text,
                        family_name: family_name,
                        reqImgs: reqImgs,
                        reqSize: reqSize
                    },
                    success: function (data) {
                        sidelightloading = false;

                        sidelight_img_obj = data.data.imgs;
                        sidelight_size_obj = data.data.size;
                        if (reqImgs) {
                            lst_prod['sidelight'][form.material.id]['imgs'] = sidelight_img_obj;
                        }
                        if (reqSize) {
                            lst_prod['sidelight'][form.material.id][form.size.size_id] = sidelight_size_obj;
                        }

                        setSidlightListItem(html);

                    },
                    error: function (errorThrown) {
                        alert("something went wrong");
                    }
                });
            }
        }

        function setSidlightListItem(html) {
            var leftS, rightS, bothS;
            if (sidelight_size_obj.length == 0) {
                setSideLightImg("main", form.sidelight.type, "", final_size, IMG_RES);
                $("#taw-blk-prod-sidelight").addClass("hide-cont");
                hidepageloader();
                return;
            } else {
                $("#taw-blk-prod-sidelight").removeClass("hide-cont");
            }

            var prefix_title="Ytterdörr ";      

            if (sidelight_size_obj['single']) {

                $("#taw-blk-prod-sidelight .blocks-gallery-grid").append(html);
                $("#taw-blk-prod-sidelight .blocks-gallery-grid").append(html);
                //left
                leftS = $("#taw-blk-prod-sidelight .blocks-gallery-grid li:nth-child(2)");
                leftS.find(".taw-p-title-txt").html(prefix_title+free_txt.left_side);
                leftS.find(".taw-p-price-txt").html("");
                leftS.attr("data-type", "left");

                setSideLightImg("thumb", "left", leftS.find(".main"), 50, IMG_RES_THUMB);

                //right
                rightS = $("#taw-blk-prod-sidelight .blocks-gallery-grid li:nth-child(3)");
                rightS.find(".taw-p-title-txt").html(prefix_title+free_txt.right_side);
                rightS.find(".taw-p-price-txt").html("");
                rightS.attr("data-type", "right");

                setSideLightImg("thumb", "right", rightS.find(".main"), 50, IMG_RES_THUMB);
            }

            if (sidelight_size_obj['double']) {
                $("#taw-blk-prod-sidelight .blocks-gallery-grid").append(html);
                bothS = $("#taw-blk-prod-sidelight .blocks-gallery-grid li:last");
                bothS.find(".taw-p-title-txt").html(prefix_title+free_txt.both_side);
                bothS.find(".taw-p-price-txt").html("");
                bothS.attr("data-type", "both");

                setSideLightImg("thumb", "both", bothS.find(".main"), 50, IMG_RES_THUMB);
            }

            setNoneItem('sidelight', 'type');
            hidepageloader();

            return;
        }



        function updateSideLightInnerHtml(type, elm) {


            if (type == "left") {
                var img = "<img src='" + getSideLightBlkImg(type, 50) + "'>";
                elm.find(".ihld").prepend(img);
            } else if (type == "right") {
                var img = "<img src='" + getSideLightBlkImg(type, 50) + "'>";
                elm.find(".ihld").append(img);
            } else if (type == "both") {

                elm.find(".ihld").prepend(getSideLightInnerHtml("m-r-1", h));
                elm.find(".ihld").append(getSideLightInnerHtml("flip m-l-1", h));
            }




            // var h = $(".ihld .main").height();
            // if (type == "left") {
            //     elm.find(".ihld").prepend(getSideLightInnerHtml("m-r-1", h));
            // } else if (type == "right") {
            //     elm.find(".ihld").append(getSideLightInnerHtml("flip m-l-1", h));
            // } else if (type == "both") {
            //     elm.find(".ihld").prepend(getSideLightInnerHtml("m-r-1", h));
            //     elm.find(".ihld").append(getSideLightInnerHtml("flip m-l-1", h));
            // }
        }

        function setSideLightImg(req_type, type, elm, side_w, img_h) {

            flipMainImage(function (main_img_data) {

                if (form.sidelight.type == "none" && req_type == "main") {
                    var elm2 = $("#co9n .image img:first");

                    elm2.attr("src", main_img_data);
                    return;
                }

                var canvas = document.createElement('canvas'),
                    ctx = canvas.getContext("2d");

                var height = 1000;
                var width = 480;

                var leftM = 0;
                canvas.height = height;

                leftM = side_w + (IMG_LEFT_BLK_W + IMG_RIGHT_BLK_W);
                width += leftM;
                if (type == "both") {
                    width += leftM;
                }
                canvas.width = width;

                var img_left = new Image();
                img_left.src = sidelight_img_obj.left;

                img_left.onload = function () {
                    ctx.drawImage(img_left, 0, 0, IMG_LEFT_BLK_W, height);

                    var img_mid = new Image();
                    img_mid.src = sidelight_img_obj.mid;

                    img_mid.onload = function () {
                        var pat = ctx.createPattern(img_mid, "repeat");
                        ctx.rect(IMG_LEFT_BLK_W, 0, side_w, height);
                        ctx.fillStyle = pat;
                        ctx.fill();


                        var img_right = new Image();
                        img_right.src = sidelight_img_obj.right;

                        img_right.onload = function () {
                            ctx.drawImage(img_right, leftM - IMG_RIGHT_BLK_W, 0, IMG_RIGHT_BLK_W, height);

                            if (type == "left") {

                                var img_m = new Image();
                                img_m.src = main_img_data;
                                img_m.onload = () => {
                                    ctx.drawImage(img_m, leftM, 0, width - leftM, height);
                                    resizeImgCanvas(canvas.toDataURL(), elm, img_h);
                                }

                            } else if (type == "right") {
                                var fliped = new Image();
                                fliped.src = canvas.toDataURL();
                                fliped.onload = () => {
                                    ctx.save();
                                    ctx.scale(-1, 1);
                                    ctx.drawImage(fliped, 0, 0, width * -1, height);
                                    ctx.restore();

                                    var img_m = new Image();
                                    img_m.src = main_img_data;
                                    img_m.onload = () => {
                                        ctx.drawImage(img_m, 0, 0, (width - leftM), height);
                                        resizeImgCanvas(canvas.toDataURL(), elm, img_h);
                                    }

                                }
                            } else {
                                var fliped = new Image();

                                fliped.src = canvas.toDataURL();

                                fliped.onload = () => {
                                    ctx.save();
                                    ctx.scale(-1, 1);
                                    ctx.drawImage(fliped, 0, 0, width * -1, height);
                                    ctx.restore();

                                    var img_m = new Image();
                                    img_m.src = main_img_data;
                                    img_m.onload = () => {
                                        ctx.drawImage(img_m, (leftM), 0, (width - (2 * leftM)), height);
                                        resizeImgCanvas(canvas.toDataURL(), elm, img_h);
                                    }
                                }
                            }
                        }
                    }
                }
            });

        }

        function flipMainImage(callback) {

            if (form.size.hinge == "right") {
                callback(final_img_url);
            } else {

                var canvas = document.createElement('canvas');
                var ctx = canvas.getContext('2d');

                var fliped = new Image();
                fliped.src = final_img_url;

                fliped.onload = () => {
                    canvas.width = fliped.naturalWidth;
                    canvas.height = fliped.naturalHeight;
                    ctx.save();
                    ctx.scale(-1, 1);
                    ctx.drawImage(fliped, 0, 0, fliped.naturalWidth * -1, fliped.naturalHeight);
                    ctx.restore();
                    callback(canvas.toDataURL());
                }
            }

        }



        function getSideLightInnerHtml(cls, h) {
            var size = 7;
            var html = '<div class="taw-prod-sidelight-attach ' + cls + '" style="height:' + h + 'px;display:inline-block;vertical-align:middle;">' +
                '<div style="background:url(\'' + sidelight_img_obj.left + '\') no-repeat;background-size:100% 100%;width:7px;height:' + h + 'px;display:inline-block;"></div>' +
                '<div style="background-image:url(\'' + sidelight_img_obj.mid + '\');width:' + size + 'px;height:100%;display:inline-block;background-size:100% 100%;"></div>' +
                '<div style="background:url(\'' + sidelight_img_obj.right + '\') no-repeat;width:7px;height:100%;display:inline-block;background-size:100% 100%;"></div></div>';
            return html;
        }


        function setGlassForm(e) {

            if ($(e).attr("data-type") == "glass") {
                form.glass.id = $(e).attr("data-id");

                item_price.glass.glass.price = $(e).attr("data-price");
                item_price.glass.glass.art_nos = $(e).attr("data-artnos");

                form.glass.option_id = $(e).attr("data-option-id");
                base_glass_html = $(e).prop('outerHTML');

                $("#taw-blk-prod-glass-opt").removeClass("hide-cont")
            } else {
                //reset price
                base_glass_html = '';
                form.glass.id = 0;
                form.glass.option_id = 0;
                item_price.glass.glass.price = 0;
                item_price.glass.glass.art_nos = '';

                $("input[name='taw-prod-glass-type']:first").prop("checked", true);
                item_price.glass.glass_type.price = 0;
                item_price.glass.glass_type.art_nos = '';

                if (family_name.substring(0, 3) != "YDR") {
                    $("#taw-blk-prod-glass-opt").addClass("hide-cont");
                }

            }
            form.glass.title = $(e).find(".taw-p-title-txt").text();
            updateSideLightHtml();
            updatePrice();
            updateHtml('kickplate');
        }



        function setSidelightForm(e) {

            form.sidelight.type = $(e).attr("data-type");

            $("#co9n .taw-prod-sidelight-attach").remove();

            if (form.sidelight.type == "none") {

                form.sidelight.size = 0;
                item_price.sidelight.type.price = 0;
                item_price.sidelight.type.art_nos = '';

                setSideLightImg("main", form.sidelight.type, "", final_size, IMG_RES);

                $("#taw-blk-prod-sidelight-opt").addClass("hide-cont");
                updatePrice();
                return;
            }

            $("#taw-blk-prod-sidelight-opt").removeClass("hide-cont");

            var sizeStart, sizeEnd;
            var sizeHtml = "";
            var lst = [];
            if (form.sidelight.type == "both") {
                if (sidelight_size_obj['double']) {
                    lst = sidelight_size_obj['double'];
                }

            } else {
                if (sidelight_size_obj['single']) {
                    lst = sidelight_size_obj['single'];
                }
            }

            for (var i = 0; i < lst.length; i++) {
                var item = lst[i];
                sizeStart = item.min;
                sizeEnd = item.max;
                for (sizeStart; sizeStart <= sizeEnd; sizeStart++) {
                    sizeHtml += '<li data-price="' + item.price + '" data-val="' + sizeStart + '" data-artnos="' + item.art_nos + '" class="text-xl rounded text-center py-2 btn-c">' + sizeStart + '</li>';
                }
            }


            $("#taw-blk-prod-sidelight-opt-size").html(sizeHtml);

            setActiveSideLight();

        }

        function setActiveSideLight() {
            var size_item = $("#taw-blk-prod-sidelight-opt-size li[data-val=" + form.sidelight.size + "]");
            if (size_item.length > 0) {
                size_item.trigger("click");
            } else {
                $("#taw-blk-prod-sidelight-opt-size li:first").trigger("click");
            }

            var glass_type = $("#taw-sidelight-glass-type-opt li[data-val=" + form.sidelight.glass_type + "]");
            if (glass_type.length > 0) {
                glass_type.trigger("click");
            } else {
                $("#taw-sidelight-glass-type-opt li:first").trigger("click");
            }
        }



        function getProductOptionsByBase() {

            $("#taw-blk-prod-kickplate .blocks-gallery-grid").html(loaderHtml);
            $("#taw-blk-prod-glass .blocks-gallery-grid").html(loaderHtml);
            //$("#taw-blk-prod-size .blocks-gallery-grid").html(loaderHtml);
            var reqCont = false;
            if (typeof article_condition[form.material.id] == "undefined") {
                reqCont = true;
            }
            jQuery.ajax({
                type: "POST",
                url: aurl,
                data: {
                    action: "getProductOptionsByBase",
                    family_id: family_id,
                    family_name: family_name,
                    base_id: form.material.id,
                    reqCont: reqCont
                },
                success: function (data) {

                    // lst_prod['size'] = data.data.size;
                    lst_prod['glass'] = data.data.glass;
                    lst_prod['kickplate'] = data.data.kickplate;
                    lst_prod['size'] = data.data.size;
                    if (reqCont) {
                        article_condition[form.material.id] = data.data.condition;
                        article_condition['products'] = data.data.condition_products;
                    }

                    updateHtml('size');

                    updateHtml('glass');
                    // updateHtml('size');
                    updateHtml('kickplate');

                },
                error: function (errorThrown) {
                    alert("something went wrong");
                }
            });
        }

        function setAccessoriesForm(e) {
            var id = $(e).attr("data-id");
            //if (id != form.accessories.pkg_id) {

            var pkg = $(e).attr("data-artnos-cat");

            if (pkg) {
                pkg = JSON.parse(pkg);
                var title = $(e).attr("data-title");
                form.accessories.pkg_id = id;
                form.accessories.title = title;
                setPackageAcs(pkg, "Edit " + title);
            }
            console.log(id);
            if(id==0){
                updateNoPackageConditionUI();
            }


            // }
        }



        function updateHtml(tab) {

            var html = lst_prod[tab];
            if (tab == "kickplate") {
                html = html[form.material.id][form.glass.option_id];
                if (!html) {
                    html = '';
                }
            } else if (tab == "packages") {
                if (form.material.sliding_door == "slide" && lst_prod['packages_sl_door'] != '') {
                    html = lst_prod['packages_sl_door'];
                } else if (form.additional.door_open == "inward" && lst_prod['packages_inward_door'] != '') {
                    html = lst_prod['packages_inward_door'];
                }
            }



            if (html == '') {
                $("#taw-blk-prod-" + tab).css("display", "none");
            } else {
                $("#taw-blk-prod-" + tab).css("display", "block");
            }
            $("#taw-blk-prod-" + tab + " .blocks-gallery-grid").html(html);

            if (tab == "material") {
                if (form.material.id != 0) {
                    $("#taw-blk-prod-" + tab + " .blocks-gallery-grid li[data-id=" + form.material.id + "]").trigger("click");
                } else {
                    $("#taw-blk-prod-" + tab + " .blocks-gallery-grid li:first").trigger("click");
                }

            } else if (tab == "glass") {
                $("#taw-blk-prod-" + tab + " .blocks-gallery-grid").prepend(base_prod_html);
                setNoneItem(tab, 'title');
                if (form.glass.glass_type != "") {
                    $("#taw-blk-prod-" + tab + " li[data-val=" + form.glass.glass_type + "]").trigger("click");
                }
            } else if (tab == "kickplate") {

                var default_family = '';
                if (base_glass_html != "") {
                    default_family = $(base_glass_html);
                } else {
                    default_family = $(base_prod_html);
                }

                $("#taw-blk-prod-" + tab + " .blocks-gallery-grid li").each(function (i, e) {
                    if ($(e).attr("data-img") == "[]") {
                        $(e).attr("data-img", default_family.attr("data-img"));
                        $(e).find("img").attr("src", default_family.find("img").attr("src"));
                    }
                });

                if (form.kickplate.title != "") {
                    $("#taw-blk-prod-" + tab + " li[data-kickplate=" + form.kickplate.title + "]").trigger("click");
                }

                //$("#taw-blk-prod-" + tab + " .blocks-gallery-grid").prepend(base_prod_html);
                //setNoneItem(tab,'id');

            } else if (tab == "size") {

                if (form.size.size_text) {
                    var se = $("#taw-blk-prod-" + tab + " .blocks-gallery-grid li[data-sizename='" + form.size.size_text + "']");
                    if (se.length > 0) {
                        se.trigger("click");
                    } else {
                        $("#taw-blk-prod-" + tab + " .blocks-gallery-grid li:first").trigger("click");
                    }
                } else {
                    $("#taw-blk-prod-" + tab + " .blocks-gallery-grid li:first").trigger("click");
                }

            } else if (tab == "packages") {
                //if (form.accessories.pkg_id != "") {
                //     $("#taw-blk-prod-" + tab + " .blocks-gallery-grid li[data-id='" + form.accessories.pkg_id + "']").trigger("click");
                // } else {
                $("#taw-blk-prod-" + tab + " .blocks-gallery-grid li:first").trigger("click");
               
                // }

            }


        }

        function setNoneItem(tab, key) {
            var e = $("#taw-blk-prod-" + tab + " .blocks-gallery-grid li:first");

            e.find(".taw-p-title-txt").html(free_txt.none);
            e.find(".taw-p-price-txt").html("");
            e.attr("data-price", "0");
            e.attr("data-artnos", "");
            e.attr("data-id", "0");
            e.attr("data-type", "none");
            e.find("img").attr("src", '/wp-content/plugins/thingsatweb/img/bx-no-entry.png').addClass("none-item");


            var elm = $("#taw-blk-prod-" + tab + " .blocks-gallery-grid li[data-" + key + "='" + form[tab][key] + "']");
            if (elm.length > 0) {
                elm.trigger("click");
            } else {
                $("#taw-blk-prod-" + tab + " .blocks-gallery-grid li:first").trigger("click");
            }

        }



        $('.wlt-update-post').on('click', function () {

            let postID = $('.wlt-edit-post-name').attr('post_id');
            let postContent = $('.wlt-edit-post-content').val();
            let postName = $('.wlt-edit-post-name').val();

            let ajaxurl = wlt.ajax_url;
            let data = {
                'action': 'wlt_edit_update_post',
                'post_id': postID,
                'post_content': postContent,
                'post_name': postName
            }

            jQuery.post(ajaxurl, data, function (responce) {
                location.reload(true);
            });

        });


        $("body").undelegate("#taw-prod-conf-preview-close", "click").delegate("#taw-prod-conf-preview-close", "click", function () {
            $("#taw-prod-conf-preview-hld").css("display", "none");
        });

        function setCookie(cname, cvalue, exdays) {
            const d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            let expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie() {
            if (!formCache) return "";
            var name = "taw_" + family_id + "=";

            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        function saveOptionsInCookie() {
            if (!formCache) return;

            var name = "taw_" + family_id;
            setCookie(name, JSON.stringify(form), 1);
        }

    }

});