jQuery(function ($) {

    var currFamilyId = 0;
    var currProdId = 0; 
 
    var currentTab = "material";
    var currProdMaterial = "", currProdArtno = "";
    var currentConditionElm="",currentConditionTab="";

    $('#taw-family-form-submit').on('click', function () {

        let title = $('#taw-form-family-title').val();
        let status = $('#taw-form-family-status').val();
        let type = $('#taw-form-family-type').val();
        let id = $('#taw-form-family-id').val();


        let data = {
            'action': 'taw_save_lookup',
            'title': title,
            'status': status,
            'type': type,
            'id': id
        }

        jQuery.post(aurl, data, function (responce) {
            location.reload(true);
        });
    });

    $('body').undelegate('.taw-edit-custom-product-name', 'click').delegate('.taw-edit-custom-product-name', 'click', function () {
        
        var name=$(this).attr("data-name");
        var id=$(this).attr("data-id");

        $("#taw-preconfig-form-edit-title").val(name);

        $("#taw-preconfig-form-edit-submit").attr("data-id",id);

        tb_show("Edit", "#TB_inline?height=150&amp;width=400&amp;inlineId=taw-preconfig-form-edit");

    });

    $('body').undelegate('.taw-edit-custom-product-tags', 'click').delegate('.taw-edit-custom-product-tags', 'click', function () {
               
        var id=$(this).attr("data-id");      
        var tags=$(this).attr("data-tags"); 
        $("#taw-preconfig-form-tags-item li input").prop("checked",false);
        if(tags){
            tags=tags.split(",");            
            tags.forEach(element => {
                console.log(element);
                var e=$("#taw-preconfig-form-tags-item li input[value='"+element+"']");
                if(e.length>0){
                    e.prop("checked",true);
                }
            });
        }  

        $("#taw-preconfig-form-tag-submit").attr("data-id",id);

        tb_show("Edit", "#TB_inline?height=300&amp;width=400&amp;inlineId=taw-preconfig-form-tags");

    });

    $('#taw-preconfig-form-tag-submit').on('click', function () {
        var tags=[];
        $("ul#taw-preconfig-form-tags-item li input").each(function(e){
            var checkd=$(this).prop("checked");
            if(checkd){
                tags.push($(this).val());
            }
        });
        var data = {
            action: 'save_custom_product_tags',
            tags:tags,
            id:$(this).attr("data-id") 
        };  

        $.post(aurl, data, function (responce) {
            
            if(responce.data.success){
                window.location.reload();
            }else{
                alert('something went wrong, Please try again later');
            }
           

        });
    })

    $('body').undelegate('.taw-edit-product-campaign', 'click').delegate('.taw-edit-product-campaign', 'click', function () {
       
        var hld_data=$(this).closest("tr").find(".data-hld")
        var data=hld_data.attr("data-formdata");
        var id=$(this).attr("data-id");
        data=JSON.parse(data);

        var settings=hld_data.attr("data-settings");
   
        settings=JSON.parse(settings);       

        var html="";
        if(data){
            for (var key in data) {
                var o=data[key];
                var v,title,checked,name,val;
                
                if(key=="family"||key=="extra"){
                    continue;
                }else if(key=="size"){
                    v=o.size_text;
                    name="size_id";
                    val=o[name];
                }else if(key=="sidelight"){
                    v=o.type;
                    name="type";
                }else if(key=="glass"){

                    v=o.title;
                    title="Glass";                    
                    checked=settings[key]?true:false;
                    name="id";
                    html+='<li><span><input data-name="'+name+'" data-val="'+o[name]+'" value="'+key+'" type="checkbox" '+(checked?"checked":"")+'></span>  '+title+' : '+v+' </li>';   
                    
                    key="glass::glass_type";
                    v=o['glass_type'];
                    title="Glass Type";                    
                    checked=settings[key]?true:false;
                    name="glass_type";
                    html+='<li><span><input data-name="'+name+'" data-val="'+o[name]+'" value="'+key+'" type="checkbox" '+(checked?"checked":"")+'></span>  '+title+' : '+v+' </li>';   
                    
                    continue;

                }else if(key=="additional"){
                    key="additional::door_open";
                    v=o['door_open'];
                    title="Door Open";
                    name='door_open';

                    checked=settings[key]?true:false;
                    html+='<li><span><input data-name="'+name+'" data-val="'+o[name]+'" value="'+key+'" type="checkbox" '+(checked?"checked":"")+'></span>  '+title+' : '+v+' </li>';   
                    key="additional::frame_extn";
                    checked=settings[key]?true:false; 
                    name="frame_extn";
                    title="Frame Extn"
                    v=o[name];
                    html+='<li><span><input data-name="'+name+'" data-val="'+v+'" value="'+key+'" type="checkbox" '+(checked?"checked":"")+'></span>  '+title+' : '+v+' </li>';   
                    
                    continue;                
                }else if(key=="accessories"){
                    name="pkg_id";
                    title="Accessories"
                    v=o[name];
                    checked=settings[key]?true:false; 
                    html+='<li><span><input data-name="'+name+'" data-val="'+v+'" value="'+key+'" type="checkbox" '+(checked?"checked":"")+'></span>  '+title+' : '+o.title+' </li>';   
                    continue;
                }else{
                    v=o.title;
                    name="id";
                }

                title=key.replace("_"," ");
                title=title.charAt(0).toUpperCase() + title.slice(1);

                checked=settings[key]?true:false;

                html+='<li><span><input data-name="'+name+'"  data-val="'+o[name]+'" value="'+key+'" type="checkbox" '+(checked?"checked":"")+'></span>  '+title+' : '+v+' </li>';            
            }
        }

        $("#taw-preconfig-form-campaign-cont ul").html(html);

        var status=hld_data.attr("data-status");
        var offer=hld_data.attr("data-offer");
        var start_date=hld_data.attr("data-start-date");
        var end_date=hld_data.attr("data-end-date");
        var offer_icon=hld_data.attr("data-offer-icon");

        $("#taw-preconfig-form-campaign-status").val(status);
        $("#taw-preconfig-form-campaign-offer").val(offer);
        $("#taw-preconfig-form-campaign-start-date").val(start_date);
        $("#taw-preconfig-form-campaign-end-date").val(end_date);
        $("#taw-preconfig-form-campaign-end-date").val(end_date);

        if(offer_icon){
            $(".upload-offer-img .custom-img-container ").html('<img src="'+offer_icon+'" alt="" style="max-height:100px;"/>');
            // Hide the add image link
            $('.upload-offer-img .upload-custom-img').css("display","none");
            $('.upload-offer-img .delete-custom-img').css("display","block");         
        }else{
            $(".upload-offer-img .custom-img-container ").html("");
            $('.upload-offer-img .upload-custom-img').css("display","block");
            $('.upload-offer-img .delete-custom-img').css("display","none");        
        }
      
        $("#taw-preconfig-form-campaign-submit").attr("data-id",id);

        tb_show("Campaign Settings", "#TB_inline?height=350&amp;width=400&amp;inlineId=taw-preconfig-form-campaign");

    });
    

    // $('#taw-edit-product-sync-offer').on('click', function () {
    //     var id=$(this).attr("data-id");
    //     $("#taw-preconfig-form-sync-submit").attr("data-id",id);

    //     tb_show("Sync Offer", "#TB_inline?height=150&amp;width=400&amp;inlineId=taw-preconfig-form-sync");
    // });

    $('#taw-preconfig-form-sync-submit').on('click', function () {
       
        var data = {
            action: 'sync_product_offer',
            page_num:0 
        };  

        $(this).addClass("loading");
        $(this).attr("disabled",true);
       

        syncProductOffer(data);

    })

    function syncProductOffer(data){       
        
        jQuery.post(aurl, data, function (responce) {
            
       
            var btn=$('#taw-preconfig-form-sync-submit');      
            if(responce.data.end==1){
                btn.removeClass("loading");
                btn.attr("disabled",false);
                btn.find('.load-text').text("Completed");
            }else{
                btn.find('.load-text').text(responce.data.percentage+" %");
            
                syncProductOffer(responce.data);
            }
            

        });
    }


    $('#taw-preconfig-tags-sync-submit').on('click', function () {
       
        var data = {
            action: 'sync_product_tags',
            page_num:0 
        };  

        $(this).addClass("loading");
        $(this).attr("disabled",true);
       

        syncProductTags(data);

    })

    function syncProductTags(data){       
        
        jQuery.post(aurl, data, function (responce) {
            
       
            var btn=$('#taw-preconfig-tags-sync-submit');      
            if(responce.data.end==1){
                btn.removeClass("loading");
                btn.attr("disabled",false);
                btn.find('.load-text').text("Completed");
            }else{
                btn.find('.load-text').text(responce.data.percentage+" %");               
                syncProductTags(responce.data);
            }
            

        });
    }

    
    $('#taw-preconfig-form-campaign-submit').on('click', function () {
        
        var settings = {};
        $("#taw-preconfig-form-campaign-cont li input[type='checkbox']").each(function () {
            if ($(this).prop("checked")) {
                var name=$(this).attr("data-name");
                var val=$(this).attr("data-val");
                settings[$(this).val()]={"name":name,"val":val};
            }
        });   
        
        var status=$("#taw-preconfig-form-campaign-status").val();
        var offer=$("#taw-preconfig-form-campaign-offer").val();
        var start_date=$("#taw-preconfig-form-campaign-start-date").val();
        var end_date=$("#taw-preconfig-form-campaign-end-date").val();
        var offer_icon_id=$(".upload-offer-img .custom-img-id").val();
        var offer_icon_remove=$(".upload-offer-img .custom-img-remove").val();
        //$("#taw-preconfig-form-campaign-offer").val(offer);

        var id=$(this).attr("data-id");
        var data = {
            action: 'save_product_campaign_settings',
            settings:settings,
            status:status,
            offer:offer,
            start_date:start_date,
            end_date:end_date,
            offer_icon_id:offer_icon_id,
            offer_icon_remove:offer_icon_remove,
            id:id
        };    

        jQuery.post(aurl, data, function (responce) {
            location.reload(true);          
        });
    });

    $('#taw-preconfig-form-edit-submit').on('click', function () {
        var title=$("#taw-preconfig-form-edit-title").val();
        if(title==''){
            alert("Enter title");
            return;
        }
        var id=$(this).attr("data-id");
        var data = {
            action: 'save_pre_config_product_title',
            title:title,
            id:id   
        };    

        jQuery.post(aurl, data, function (responce) {
            location.reload(true);           
        });
    });
   


    /**
 * Edit wp list table data
 */
    $('.taw-familty-row-edit').on('click', function () {

        let data = $(this).parents("td").find(".data-hld");

        let id = data.attr('data-id');
        let title = data.attr('data-title');
        let type = data.attr('data-type');
        let status = data.attr('data-status');

        $('#taw-form-family-id').val(id);
        $('#taw-form-family-title').val(title);
        $('#taw-form-family-status').val(status);
        $('#taw-form-family-type').val(type);

    });

    $('.taw-familty-row-delete').on('click', function () {

        let id = $(this).parents("td").find(".data-hld").attr('data-id');
        let action = $(this).parents("td").find(".data-hld").attr('data-action');

        if (confirm("Are you sure?")) {
            let data = {
                'action': action,
                'id': id
            }

            jQuery.post(aurl, data, function (responce) {
                location.reload(true);
            });
        }


    });   

    $('.taw-attribute-row-delete').on('click', function () {

        let id = $(this).parents("td").find(".data-hld").attr('data-id');
        let action = $(this).parents("td").find(".data-hld").attr('data-action');

        if (confirm("Are you sure?")) {
            let data = {
                'action': action,
                'id': id
            }
            
            jQuery.post(aurl, data, function (responce) {
                location.reload(true);
            });      
        }


    });


    $('.taw-attribute-row-edit').on('click', function () {

        let data = $(this).parents("td").find(".data-hld");
        let id = data.attr('data-id');
        let art_no = data.attr('data-artno');
        let attr_id = data.attr('data-type');
        let term_ids = data.attr('data-attributes');
        $('#artno-label, #taw-form-article-attribute-artno').hide();
        $('#add-new-btn').click(function() {
            $('#artno-label, #taw-form-article-attribute-artno').show();
        });
        $('#edit-btn').click(function() {
            $('#artno-label, #taw-form-article-attribute-artno').hide();
        });
       

        $('#taw-form-article-id').val(id);
       
        $('#taw-form-article-attribute-type').val(attr_id);
        $('#taw-form-article-attribute-attributes').val(term_ids);
        

    });

    $('.taw-accessories-row-delete').on('click', function () {

        let id = $(this).parents("td").find(".data-hld").attr('data-id');
        let action = $(this).parents("td").find(".data-hld").attr('data-action');
        console.log(id);

        if (confirm("Are you sure?")) {
            let data = {
                'action': action,
                'id': id
            }
            
            jQuery.post(aurl, data, function (responce) {
                location.reload(true);
            });      
        }
    });
    
    $('.taw-accessories-row-edit').on('click', function () {

        let data = $(this).parents("td").find(".data-hld");
        let id = data.attr('data-id');
        let parent_article = data.attr('data-parentarticle');
        let acs_article = data.attr('data-acsarticle');
        let no_plates = data.attr('data-noplates');
       
        console.log(id);
        console.log(parent_article);
        console.log(acs_article);
        console.log(no_plates);

        $('#taw-form-article-id').val(id);
        $('#taw-form-article-accessories-parentnumber').val(parent_article);
        $('#taw-form-article-accessories-accessoriesArtnumber').val(acs_article);
        $('#taw-form-article-accessories-plates').val(no_plates);   

    });

    $('#taw-form-article-accessories-submit').on('click', function () {

        let id =  $('#taw-form-article-id').val();
        let parent_article = $('#taw-form-article-accessories-parentnumber').val();
        let acs_article =  $('#taw-form-article-accessories-accessoriesArtnumber').val();
        let no_plates =  $('#taw-form-article-accessories-plates').val();
        console.log(parent_article,acs_article,no_plates);
        let data = {
            'action': 'taw_save_article_accessories',
            'parent_article': parent_article,
            'acs_article': acs_article,
            'no_plates': no_plates,
            'id': id
        }

        jQuery.post(aurl, data, function (responce) {
            if(responce.data.status!='1'){
                alert(responce.data.status);
            }else{
                location.reload(true);
            }
        });
    });

    $('#taw-form-article-attribute-submit').on('click', function () {

        let id =  $('#taw-form-article-id').val();
        let art_no = $('#taw-form-article-attribute-artno').val();
        let attr_id =  $('#taw-form-article-attribute-type').val();
        let term_ids =  $('#taw-form-article-attribute-attributes').val();
        console.log(art_no,attr_id,term_ids);
        let data = {
            'action': 'taw_save_article_attribute',
            'attr_id': attr_id,
            'term_ids': term_ids,
            'art_no': art_no,
            'id': id
        }

        jQuery.post(aurl, data, function (responce) {
            if(responce.data.status!='1'){
                alert(responce.data.status);
            }else{
                location.reload(true);
            }
        });
    });

    $('.taw-category-row-delete').on('click', function () {

        let id = $(this).parents("td").find(".data-hld").attr('data-id');
        let action = $(this).parents("td").find(".data-hld").attr('data-action');

        if (confirm("Are you sure?")) {
            let data = {
                'action': action,
                'id': id
            }
            
            jQuery.post(aurl, data, function (responce) {
                location.reload(true);
            });      
        }


    });
    $('.taw-category-row-edit').on('click', function () {

        let data = $(this).parents("td").find(".data-hld");
        let id = data.attr('data-id');
        let category = data.attr('data-category');
        let parentcategory = data.attr('data-parentcategory');
        let art_no = data.attr('data-artno');
        
        $('#artno-label, #taw-form-article-category-artno').hide();
        $('#add-new-btn').click(function() {
            $('#artno-label, #taw-form-article-category-artno').show();
        });
        $('#edit-btn').click(function() {
            $('#artno-label, #taw-form-article-category-artno').hide();
        });

        $('#taw-form-article-id').val(id);
        $('#taw-form-article-category-artno').val(art_no);
        $('#taw-form-article-category-termid').val(category);
        $('#taw-form-article-parentcategory-termid').val(parentcategory);
        

    });
    $('#taw-form-article-category-submit').on('click', function () {

        let id =  $('#taw-form-article-id').val();
        let art_no = $('#taw-form-article-category-artno').val();
        let term_id =  $('#taw-form-article-category-termid').val();
        let parent_cate =  $('#taw-form-article-parentcategory-termid').val();
        
        let data = {
            'action': 'taw_save_article_category',
            'term_id': term_id,
            'parent_cate': parent_cate,
            'art_no': art_no,
            'id': id
        }

        jQuery.post(aurl, data, function (responce) {
            if(responce.data.status!='1'){
                alert(responce.data.status);
            }else{
                location.reload(true);
            }
        });
    });

    $('.taw-customerprice-row-delete').on('click', function () {

        let id = $(this).parents("td").find(".data-hld").attr('data-id');
        let action = $(this).parents("td").find(".data-hld").attr('data-action');

        if (confirm("Are you sure?")) {
            let data = {
                'action': action,
                'id': id
            }
            
            jQuery.post(aurl, data, function (responce) {
                location.reload(true);
            });
            
        }
    });

    $('.taw-customerprice-row-edit').on('click', function () {

        let data = $(this).parents("td").find(".data-hld");

        let id = data.attr('data-id');

        let customer_no = data.attr('data-customerno');
        let price = data.attr('data-price');
        let currency = data.attr('data-currency');
        let art_no = data.attr('data-artno');
        $('#artno-label, #taw-form-article-customerprice-artno').hide();
        $('#add-new-btn').click(function() {
            $('#artno-label, #taw-form-article-customerprice-artno').show();
        });
        $('#edit-btn').click(function() {
            $('#artno-label, #taw-form-article-customerprice-artno').hide();
        });

        $('#taw-form-article-id').val(id);
        $('#taw-form-article-customerprice-artno').val(art_no);
        $('#taw-form-article-customerprice-customerno').val(customer_no);
        $('#taw-form-article-customerprice-price').val(price);
        $('#taw-form-article-customerprice-currency').val(currency);
        
    });
   

    $('#taw-form-article-customerprice-submit').on('click', function () {

        let art_no = $('#taw-form-article-customerprice-artno').val();
        let customer_no =  $('#taw-form-article-customerprice-customerno').val();
        let price =  $('#taw-form-article-customerprice-price').val();
        let currency =  $('#taw-form-article-customerprice-currency').val();
      
        let id =  $('#taw-form-article-id').val();


        let data = {
            'action': 'taw_save_article_customerprice',
            'customer_no': customer_no,
            'price': price,
            'currency': currency,
            'art_no': art_no,
            'id': id
        }

        jQuery.post(aurl, data, function (responce) {
            if(responce.data.status!='1'){
                alert(responce.data.status);
            }else{
                location.reload(true);
            }
        });
    });

    $('.taw-title-row-delete').on('click', function () {

        let id = $(this).parents("td").find(".data-hld").attr('data-id');
        let action = $(this).parents("td").find(".data-hld").attr('data-action');

        if (confirm("Are you sure?")) {
            let data = {
                'action': action,
                'id': id
            }
            
            jQuery.post(aurl, data, function (responce) {
                location.reload(true);
            });
            
        }
    });

    $('.taw-title-row-edit').on('click', function () {

        let data = $(this).parents("td").find(".data-hld");

        let id = data.attr('data-id');
        let title = data.attr('data-title');
        let desc = data.attr('data-desc');
        //let shortdesc = data.attr('data-shortdesc');
        let art_no = data.attr('data-artno');
        
        $('#artno-label, #taw-form-article-title-artno').hide();
        $('#add-new-btn').click(function() {
            $('#artno-label, #taw-form-article-title-artno').show();
        });
        $('#edit-btn').click(function() {
            $('#artno-label, #taw-form-article-title-artno').hide();
        });

        $('#taw-form-article-id').val(id);
        $('#taw-form-article-title-artno').val(art_no);
        $('#taw-form-article-title-titles').val(title);
        $('#taw-form-article-title-description').val(desc);
       // $('#taw-form-article-title-shortdescription').val(shortdesc);
      
        
    });

    $('#taw-form-article-title-submit').on('click', function () {

        let art_no = $('#taw-form-article-title-artno').val();
        let title =  $('#taw-form-article-title-titles').val();
        let desc =  $('#taw-form-article-title-description').val();
       // let shortdesc =  $('#taw-form-article-title-shortdescription').val();
       
      
        let id =  $('#taw-form-article-id').val();


        let data = {
            'action': 'taw_save_article_title',
            'title': title,
            'desc': desc,
          //  'shortdesc': shortdesc,
          
            'art_no': art_no,
            'id': id
        }

        jQuery.post(aurl, data, function (responce) {
            if(responce.data.status!='1'){
                alert(responce.data.status);
            }else{
                location.reload(true);
            }
        });
    });

    $('.taw-picture-row-delete').on('click', function () {

        let id = $(this).parents("td").find(".data-hld").attr('data-id');
        let action = $(this).parents("td").find(".data-hld").attr('data-action');

        if (confirm("Are you sure?")) {
            let data = {
                'action': action,
                'id': id
            }
            
            jQuery.post(aurl, data, function (responce) {
                location.reload(true);
            });
            
        }
    });

    $('.taw-picture-row-edit').on('click', function () {

        let data = $(this).parents("td").find(".data-hld");

        let id = data.attr('data-id');

        let pic_name = data.attr('data-picture');
        let colour = data.attr('data-colour');
        let alt_text = data.attr('data-alt_text');
        let art_no = data.attr('data-artno');
        let gallery_name = data.attr('data-gallery');

        $('#artno-label, #taw-form-article-picture-artno').hide();
        $('#add-new-btn').click(function() {
            $('#artno-label, #taw-form-article-picture-artno').show();
        });
        $('#edit-btn').click(function() {
            $('#artno-label, #taw-form-article-picture-artno').hide();
        });
       
        $('#taw-form-article-id').val(id);
        $('#taw-form-article-picture-artno').val(art_no);
        $('#taw-form-article-picture-pic').val(pic_name);
        $('#taw-form-article-picture-gallery').val(gallery_name);
        $('#taw-form-article-picture-colour').val(colour);
        $('#taw-form-article-picture-alttext').val(alt_text);
       
    });

    $('#taw-form-article-picture-submit').on('click', function () {

        let art_no = $('#taw-form-article-picture-artno').val();
        let pic_name = $('#taw-form-article-picture-pic').val();
        let gallery_name = $('#taw-form-article-picture-gallery').val();
        let colour =  $('#taw-form-article-picture-colour').val();
        let alt_text =  $('#taw-form-article-picture-alttext').val();
        
    
        let id =  $('#taw-form-article-id').val();
     
        let data = {
            'action': 'taw_save_article_picture',
            'picture': pic_name,
            'gallery': gallery_name,
            'colour': colour,
            'alt_text': alt_text,
            'art_no': art_no,
            'id': id
            
        }
       
       
        jQuery.post(aurl, data, function (responce) {
            if(responce.data.status!='1'){
                alert(responce.data.status);
            }else{
                location.reload(true);
            }
        });
    });


    $('.taw-price-row-delete').on('click', function () {

        let id = $(this).parents("td").find(".data-hld").attr('data-id');
        let action = $(this).parents("td").find(".data-hld").attr('data-action');

        if (confirm("Are you sure?")) {
            let data = {
                'action': action,
                'id': id
            }
            
            jQuery.post(aurl, data, function (responce) {
                location.reload(true);
            });
            
        }
    });
    

    $('.taw-price-row-edit').on('click', function () {

        let data = $(this).parents("td").find(".data-hld");

        let id = data.attr('data-id');

        let price_b2b = data.attr('data-priceb2b');
        let price_reseller_eur = data.attr('data-priceresellereur');
        let price_reseller_sek = data.attr('data-priceresellersek');
        let art_no = data.attr('data-artno');

        $('#artno-label, #taw-form-article-price-artno').hide();
        $('#add-new-btn').click(function() {
            $('#artno-label, #taw-form-article-price-artno').show();
        });
        $('#edit-btn').click(function() {
            $('#artno-label, #taw-form-article-price-artno').hide();
        });
        $('#taw-form-article-id').val(id);
        $('#taw-form-article-price-priceb2b').val(price_b2b);
        $('#taw-form-article-price-priceresellereur').val(price_reseller_eur);
        $('#taw-form-article-price-priceresellersek').val(price_reseller_sek);
        
    });
   

    $('#taw-form-article-price-submit').on('click', function () {

        let art_no = $('#taw-form-article-price-artno').val();
        let price_b2b =  $('#taw-form-article-price-priceb2b').val();
        let price_reseller_eur =  $('#taw-form-article-price-priceresellereur').val();
        let price_reseller_sek =  $('#taw-form-article-price-priceresellersek').val();
      
        let id =  $('#taw-form-article-id').val();


        let data = {
            'action': 'taw_save_article_price',
            'price_b2b': price_b2b,
            'price_reseller_eur': price_reseller_eur,
            'price_reseller_sek': price_reseller_sek,
            'art_no': art_no,
            'id': id
        }

        jQuery.post(aurl, data, function (responce) {
            if(responce.data.status!='1'){
                alert(responce.data.status);
            }else{
                location.reload(true);
            }
        });
    });

    


    $('#taw-prod-form-sub-submit').on('click', function () {
        let material_id = $('#taw-family-product-form-material').val();
        let art_no = $('#taw-family-product-form-artno').val();

        let data = {
            'action': 'taw_save_family_prod',
            'material_id': material_id,
            'family_id': currFamilyId,
            'art_no': art_no
        }

        jQuery.post(aurl, data, function (responce) {           
            if(responce.data.success){
                $("#taw-lst-f-p ul").append(responce.data.html);
                $("#TB_closeWindowButton").trigger("click");
            }else{
                alert("something went wrong");
            }
        });

    });


    $('ul#taw-lst-m li').on('click', function () {
        $('ul#taw-lst-m li').removeClass("active");
        $(this).addClass("active");
        $("#taw-lst-f-p").html('<div style="text-align: center;vertical-align: middle; min-height: 500px;  display: block; padding-top: 200px;"><span class="dashicons dashicons-update aloader"></span></div>');
        $("#taw-lst-f-p-c").html("");
        currFamilyId = $(this).attr('data-id');

        let data = {
            'action': 'taw_get_prod_material',
            'id': currFamilyId
        }
        jQuery.post(aurl, data, function (responce) {
            // location.reload( true );
            var data = responce.data.items;
            var html = '<ul class="taw-lst-hld">';
            for (var i = 0; i < data.length; i++) {
                var o = data[i];
                html += '<li data-id="' + o.id + '" data-artno="' + o.art_no + '" data-material="' + o.name + '"><p>' + o.name + '</p><p>' + o.art_no + '</p></li>';
            }
            html += "</ul>";

            $("#taw-lst-f-p").html(html);
            $("#taw-lst-f-p-c").html("<div style='padding:20px;'><h3>Product Gallery</h3>"+responce.data.gal+"</div>");
            $("#taw-lst-f-p-c").append()
            var html="<hr/><h3>OM</h3><div><input type='hidden' value='"+responce.data.section.id+"' id='taw_save_om_text_id' /><input style='width: 100%;min-height: 40px;margin-bottom: 10px;' id='taw_save_om_text_title' value='"+responce.data.section.title+"' /><textarea style='width: 100%;min-height: 100px;margin-bottom: 10px;' id='taw_save_om_text_content'>"+responce.data.section.content+"</textarea><button style='height: 30px;width: 120px;' id='taw_save_om_text_btn'>Save</button></div>";
            $("#taw-lst-f-p-c").append(html);
            

        });

    });

    $('body').undelegate('#taw_save_om_text_btn', 'click').delegate('#taw_save_om_text_btn', 'click', function () {
       
        $(this).text("Saving ...");
        $(this).attr("disabled",true);
       var title=$("#taw_save_om_text_title").val();       
       var content=$("#taw_save_om_text_content").val();
       var id=$("#taw_save_om_text_id").val();
       let data = {
            'action': 'save_family_about_text',
            'id': id,
            'family_id': currFamilyId,
            'title': title,
            'content': content,
        }
        var T=$(this);
        jQuery.post(aurl, data, function (responce) {
            // location.reload( true );

            T.text("Saved");
            T.attr("disabled",false);
         
        });

    });

    

    $('body').undelegate('#taw-lst-f-p li', 'click').delegate('#taw-lst-f-p li', 'click', function () {
        $('#taw-lst-f-p li').removeClass("active");
        $(this).addClass("active");

        currProdId = $(this).attr('data-id');
        currProdMaterial = $(this).attr('data-material');
        currProdArtno = $(this).attr('data-artno');
        $("#taw-lst-f-p-c").html('<div style="text-align: center;vertical-align: middle; min-height: 500px;  display: block; padding-top: 200px;"><span class="dashicons dashicons-update aloader"></span></div>');

        let data = {
            'action': 'taw_get_prod_config',
            'id': currProdId,
            'material': currProdMaterial,
            'art_no': currProdArtno,
        }
        jQuery.post(aurl, data, function (responce) {
            // location.reload( true );

            $("#taw-lst-f-p-c").html(responce);         
        });

    });

    $('body').undelegate('#prod-config-item-menu li', 'click').delegate('#prod-config-item-menu li', 'click', function () {
        $('#prod-config-item-menu li').removeClass("active");

        currentTab = $(this).attr("data-type");
        $(this).addClass("active");
        $('.p-c-i-c').hide();
        var id = $(this).attr("data-id");
        $("#" + id).show();
        if (!$("#" + id).hasClass("loaded")) {
            $("#" + id).html('<div style="text-align: center;vertical-align: middle; min-height: 500px;  display: block; padding-top: 200px;"><span class="dashicons dashicons-update aloader"></span></div>');


            var mainType = $(this).closest("ul").attr("data-type");
            var url = "get_prod_config_tab_item";
            if (mainType == "acs") {
                url = "taw_get_accessories_tab";
            }
            var data = {
                action: url,
                base_id: currProdId,
                type: currentTab,
                family_id: currFamilyId
            };

            jQuery.post(aurl, data, function (responce) {
                $("#" + id).html(responce);
                $("#" + id).addClass("loaded");

                if(currentTab=="size"){
                    initSizeSortFunc();
                }
            });
        }

    });

    $('body').undelegate('.taw_select_media', 'click').delegate('.taw_select_media', 'click', function (e) {

        e.preventDefault();

        var lang = $(this).attr("data-lang");

        var image_frame;
        if (image_frame) {
            image_frame.open();
        }
        // Define image_frame as wp.media object
        image_frame = wp.media({
            title: 'Select Media',
            multiple: true,
            library: {
                type: 'image',
            }
        });

        image_frame.on('close', function () {
            // On close, get selections and save to the hidden input
            // plus other AJAX stuff to refresh the image preview
            var selection = image_frame.state().get('selection');
            var gallery_ids = new Array();
            var my_index = 0;
            selection.each(function (attachment) {
                gallery_ids[my_index] = attachment['id'];
                my_index++;
            });
            var ids = gallery_ids.join(",");

            //  $('input#myprefix_image_id').val(ids);
            save_images(ids, lang);
        });

        image_frame.on('open', function () {
            // On open, get the id from the hidden input
            // and select the appropiate images in the media manager
            /* var selection =  image_frame.state().get('selection');
             var ids = $('input#myprefix_image_id').val().split(',');
             ids.forEach(function(id) {
               var attachment = wp.media.attachment(id);
               attachment.fetch();
               selection.add( attachment ? [ attachment ] : [] );
             });*/

        });

        image_frame.open();
    });

    $('body').undelegate('.taw-acs-arttype-btn', 'change').delegate('.taw-acs-arttype-btn', 'change', function (e) {
       var val=$(this).val();
       if(val=="Checked"||val=="Not Checked"){
            $(this).closest(".item-cont").find(".taw-acs-condition-btn").css("display","none");           
       }else{
            $(this).closest(".item-cont").find(".taw-acs-condition-btn").css("display","block");     
       }

       if(val=="Switch Article"||val=="Switch Variant"){
             $(this).closest(".item-cont").find(".taw-acs-switch-art-btn").css("display","inline-block");         
       }else{
            $(this).closest(".item-cont").find(".taw-acs-switch-art-btn").css("display","none");
       }

       var data = {
            action: 'save_package_art_rule_type',
            id: $(this).attr("data-id"),
            type: val
        };

        jQuery.post(aurl, data, function (responce) {
            if(responce.data.success){

            }
        });
    });


    $('body').undelegate('.taw-acs-switch-art-btn', 'click').delegate('.taw-acs-switch-art-btn', 'click', function (e) {
        var parent=$(this).closest(".taw_article_condition_hld");
        
        var art_no = parent.attr("data-art-no");
        var option_id = parent.attr("data-option_id");
        var save_type = parent.attr("data-save-type");
        var id = parent.attr("data-id");
        var h = 120;
        var column=$(this).attr("data-column");
        var type = $(this).attr("data-type");
        
        $("#taw-acs-condition-gid").val(type);
      
        var a=parent.find(".taw-acs-txt-"+column).text();
        $("#taw-accessories-switch-artno-form-artno").val(a);

        $("#taw-accessories-switch-artno-form-submit").attr("data-id",id);
        $("#taw-accessories-switch-artno-form-submit").attr("data-column",column);
        $("#taw-accessories-switch-artno-form-submit").attr("data-save-type",save_type);
        $("#taw-accessories-switch-artno-form-submit").attr("data-art-no",art_no);
        $("#taw-accessories-switch-artno-form-submit").attr("data-option_id",option_id);
        
        tb_show("Edit", "#TB_inline?height=" + h + "&amp;width=400&amp;inlineId=taw-accessories-switch-artno-form");
    });

    $('body').undelegate('#taw-accessories-switch-artno-form-submit', 'click')
    .delegate('#taw-accessories-switch-artno-form-submit', 'click', function (e) {
       
        var art_nos=$("#TB_ajaxContent #taw-accessories-switch-artno-form-artno").val();
        var id=$(this).attr("data-id");
        var save_type=$(this).attr("data-save-type");
        var column=$(this).attr("data-column");
        var art_no=$(this).attr("data-art-no");
        var option_id=$(this).attr("data-option_id");
        var data = {
            action: 'save_article_condition_artnos',
            id: id,
            new_art_nos: art_nos,
            save_type:save_type,
            column:column,
            option_id:option_id,
            art_no:art_no
        };

        jQuery.post(aurl, data, function (responce) {
            $("#TB_closeWindowButton").trigger("click");
            if(responce.success){
                currentConditionElm.trigger("click");
            }
        });
    }); 

    $('body').undelegate('.taw-acs-switch-by-selection-btn', 'click').delegate('.taw-acs-switch-by-selection-btn', 'click', function (e) {
        var parent=$(this).closest(".taw_article_condition_hld");
        $("#taw-accessories-switch-by-selection-form-submit").attr("data-id",parent.attr("data-id"));
        $("#taw-accessories-switch-by-selection-form-submit").attr("data-art-no",parent.attr("data-art-no"));
        $("#taw-accessories-switch-by-selection-form-submit").attr("data-save-type",parent.attr("data-save-type"));
        $("#taw-accessories-switch-by-selection-form-submit").attr("data-option_id",parent.attr("data-option_id"));
        tb_show("Add Variant", "#TB_inline?height=320&amp;width=400&amp;inlineId=taw-accessories-switch-by-selection-form");
    });

    
    
    
    $('body').undelegate('.taw_choose_media', 'click').delegate('.taw_choose_media', 'click', function (e) {

        e.preventDefault();

        var ele=$(this);

        var image_frame;
        if (image_frame) {
            image_frame.open();
        }
        // Define image_frame as wp.media object
        image_frame = wp.media({
            title: 'Select Media',
            multiple: false,
            library: {
                type: 'image',
            }
        });

        image_frame.on('close', function () {
            // On close, get selections and save to the hidden input
            // plus other AJAX stuff to refresh the image preview
            var selection = image_frame.state().get('selection');
           
            //var gallery_ids = new Array();
           // var my_index = 0;
            var url="";
            selection.each(function (attachment) {
               // gallery_ids[my_index] = attachment['id'];
               // my_index++;
                url=attachment['changed']['url'];                
            });
           //var ids = gallery_ids.join(",");

            //  $('input#myprefix_image_id').val(ids);
           ele.attr("data-img-id",url);
           ele.css("background-image","url('"+url+"')");
        });

        image_frame.on('open', function () {});

        image_frame.open();
    });


    $('body').undelegate('.taw-rule-action-lst li', 'click')
    .delegate('.taw-rule-action-lst li', 'click', function (e) {
        $(this).closest(".taw-option-tbl").find(".taw-rule-action-lst li").removeClass("active");
        $(this).addClass("active");
        currentConditionTab=$(this).attr("data-id");
        $(this).closest(".taw-option-tbl").find(".taw-rule-action-content-hld .trach").css("display","none");
        $(this).closest(".taw-option-tbl").find(".taw-rule-action-content-hld #"+currentConditionTab).css("display","block");
    });

    // Ajax request to refresh the image preview
    function save_images(ids, lang) {
       
        var data = {
            action: 'save_prod_option_img',
            ids: ids,
            base_id: gal_base_id,
            type: gal_type,
            lang: lang,
        };

        jQuery.post(aurl, data, function (responce) {
            if(responce.data.success){
                if(currentTab=='material'){
                    $("#taw-lst-f-p li[data-id='"+currProdId+"']").trigger("click");                   
                }else if(gal_type=='acs'){
                    $("#taw-lst-acs li[data-id='"+gal_base_id+"']").trigger("click");                   
                }else{
                    $("#prod-config-item-cont-"+currentTab).removeClass("loaded");
                    $("#prod-config-item-menu li[data-type='"+currentTab+"']").trigger("click");  
                }
                            
            }else{
                alert("something went wrong,try again later");
            }
        });
    }

    $('body').undelegate('.taw-add-btn', 'click').delegate('.taw-add-btn', 'click', function (e) {
        var type = $(this).attr("data-type");
        var id = "taw-product-option-form";
        var h = 300;
        if (type == "sidelight") {
            id = "taw-product-option-sidelight-form";
            h = 400;
        } else if (type == "accessories") {
            id = "taw-accessories-group-form";
            h = 200;
        } else {
            $("#taw-product-option-form-type").val(type);
        }
        tb_show("Add Option", "#TB_inline?height=" + h + "&amp;width=400&amp;inlineId=" + id);
    })
    //taw-product-option-form

    $('body').undelegate('#taw-product-option-form-submit', 'click').delegate('#taw-product-option-form-submit', 'click', function (e) {
        var option = $("#taw-product-option-form-option").val();
        var art_no = $("#taw-product-option-form-artno").val();
        var type = $("#taw-product-option-form-type").val();
       
        var data = {
            action: 'save_prod_option_data',
            art_no: art_no,
            base_id: currProdId,
            type: type,
            option_id: option,
           // family_id: currFamilyId,
        };

        jQuery.post(aurl, data, function (responce) {
            if(responce.data.success){  
                $("#taw-lst-f-p li[data-id='"+currProdId+"']").trigger("click");
                $("#TB_closeWindowButton").trigger("click");
            }else{
                alert("something went wrong , try again later");
            }
        });
    })

    $('body').undelegate('#taw-product-option-sidelight-form-submit', 'click').delegate('#taw-product-option-sidelight-form-submit', 'click', function (e) {
        var art_no = $("#taw-p-o-f-s-artno").val();
        var option = $("#taw-p-o-f-s-option").val();
        var minw = $("#taw-p-o-f-s-minw").val();
        var maxw = $("#taw-p-o-f-s-maxw").val();
        var sidetype = $("#taw-p-o-f-s-sidetype").val();

        var data = {
            action: 'save_prod_option_data',
            art_no: art_no,
            base_id: currProdId,
            minw: minw,
            maxw: maxw,
            type: "sidelight",
            sidetype: sidetype,
            option_id: option,
        };

        jQuery.post(aurl, data, function (responce) {
            if(responce.data.success){
                $("#prod-config-item-cont-"+currentTab).removeClass("loaded");
                $("#prod-config-item-menu li[data-type='"+currentTab+"']").trigger("click"); 
                $("#TB_closeWindowButton").trigger("click");
            }else{
                alert("something went wrong , try again later");
            }
        });
    });

    $('body').undelegate('.taw-delete-option', 'click').delegate('.taw-delete-option', 'click', function (e) {

        if (confirm("Are you sure?")) {
            var id = $(this).attr("data-id");
            var type = $(this).attr("data-type");

            var data = {
                action: 'remove_prod_option',
                id: id,
                type: type
            };
            var T = this;

            jQuery.post(aurl, data, function (responce) {
                $(T).closest("tr").remove();
            });
        }

    });


    $('body').undelegate('.taw-delete-order-group', 'click').delegate('.taw-delete-order-group', 'click', function (e) {
        var id=$(this).attr("data-id");
        if (confirm("Are you sure?")) {
            var id = $(this).attr("data-id");
            var data = {
                action: 'remove_accessories_group_cate',
                id: id
            };
            var T = this;

            jQuery.post(aurl, data, function (responce) {
                $(T).closest("tr").remove();
            });
        }

    });

   

    $('body').undelegate('#taw-lst-acs li', 'click').delegate('#taw-lst-acs li', 'click', function () {
        $('#taw-lst-acs li').removeClass("active");
        $(this).addClass("active");

        currProdId = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        currentTab = "acs";

        $("#taw-actions-pkg-hld").css("display",'block');
        $("#taw-title-pkg-txt").text($(this).text());
       

        $("#taw-lst-f-p-c").html('<div style="text-align: center;vertical-align: middle; min-height: 500px;  display: block; padding-top: 200px;"><span class="dashicons dashicons-update aloader"></span></div>');

        let data = {
            'action': 'taw_get_accessories_config',
            'id': currProdId,
            'group_type': type,
        }
        jQuery.post(aurl, data, function (responce) {
            // location.reload( true );


            $("#taw-lst-f-p-c").html(responce);

        });
    });

    $('body').undelegate('#taw-delete-pkg-btn', 'click').delegate('#taw-delete-pkg-btn', 'click', function () {
        if (confirm("Are you sure?")) {
            let data = {
                'action': 'remove_accessories_config',
                'id': currProdId
            }
            jQuery.post(aurl, data, function (responce) {
                location.reload( true );    
            });
        }
    });

    $('body').undelegate('.taw-edit-pkg-btn', 'click').delegate('.taw-edit-pkg-btn', 'click', function () {
       
        var title;
        if($(this).attr("data-type")=="add"){   
            title="Add Package"; 
            $('#taw-accessories-form-type').val("");
            $('#taw-accessories-form-name').val("");
            $('#taw-accessories-form-desc').val("");
            $('#taw-accessories-form-id').val("");
        }else{
            title="Edit Package"; 
            var item=$("#taw-lst-acs li.active");
            var values=item.attr("data-type");
            $("#taw-accessories-form-type").val("");
            $.each(values.split(","), function(i,e){
                $("#taw-accessories-form-type option[value='" + e + "']").prop("selected", true);               
            });
           
            $('#taw-accessories-form-name').val($("#taw-title-pkg-txt").text());
            $('#taw-accessories-form-desc').val(item.attr("data-desc"));
            $('#taw-accessories-form-id').val(currProdId);
        }       

        tb_show(title, "#TB_inline?height=400&amp;width=300&amp;inlineId=taw-accessories-form");
    });


    $('body').undelegate('#taw-lst-acs-cate li', 'click').delegate('#taw-lst-acs-cate li', 'click', function () {
        $('#taw-lst-acs-cate li').removeClass("active");
        $(this).addClass("active");

        currProdId = $(this).attr('data-id');
        currentTab = "acs";
        $("#taw-lst-f-p-c").html('<div style="text-align: center;vertical-align: middle; min-height: 500px;  display: block; padding-top: 200px;"><span class="dashicons dashicons-update aloader"></span></div>');

        let data = {
            'action': 'taw_get_accessories_group',
            'id': currProdId
        }
        jQuery.post(aurl, data, function (responce) {

            $("#taw-lst-f-p-c").html(responce);

            $("#sortable").sortable({
                revert       : true,
                stop         : function(event,ui){ 
                    var items=event.target.children;
                    var ids=[];
                    for(var i=0;i<items.length;i++){
                        ids.push($(items[i]).attr("data-id"));                       
                    }

                    var data = {
                        action: 'save_package_cate_group_order',
                        ids:ids
                    };

                    $("#taw_order_loader").show();

                    jQuery.post(aurl, data, function (responce) {
                        if (responce.data.success) {
                            $("#taw_order_loader").hide();
                           // location.reload(true);
                        }
                    });
                }              
            }) 
                
               

        });
    });

    $('body').undelegate('#taw-accessories-form-sbmt', 'click').delegate('#taw-accessories-form-sbmt', 'click', function (e) {
        var name = $("#taw-accessories-form-name").val();
        var desc = $("#taw-accessories-form-desc").val();
        var type = $("#taw-accessories-form-type").val();
        var id = $("#taw-accessories-form-id").val();

        var data = {
            action: 'save_accessories',
            desc: desc,
            name: name,
            type: type,
            id:id
        };

        jQuery.post(aurl, data, function (responce) {
            if (responce.data.success) {
                location.reload(true);
            }

        });
    });

    $("body").undelegate(".taw_acs_cate_section_btn").delegate(".taw_acs_cate_section_btn",'click',function(){
        var type=$(this).attr("data-type");
    
        var title;
        var items=$("#taw-accessories-cate-form-categories li");
        items.find("input").prop("checked",false);
        if(type=="add"){
            title="Add Section";
        }else{
            title="Edit Section";
            var cate=$(this).attr("data-cate").split(",");
                   
            cate.forEach(e => {               
                items.find('input[value="'+e+'"]').prop('checked',true);
            });
            
            $("#taw-form-family-id").val($(this).attr("data-id"));
            $("#taw-form-family-name").val($(this).attr("data-title"));
        }

        tb_show(title, "#TB_inline?height=500&amp;width=400&amp;inlineId=taw-accessories-cate-form");
    });

    $('body').undelegate('#taw-acs-cate-form-submit', 'click').delegate('#taw-acs-cate-form-submit', 'click', function (e) {
        var category = [];
        var parent_category = [];

        $("#taw-accessories-cate-form-categories li input[type='checkbox']").each(function () {
            if ($(this).prop("checked")) {
                category.push($(this).val());
            }
        });

        $("#taw-accessories-cate-form-parent-categories li input[type='checkbox']").each(function () {
            if ($(this).prop("checked")) {
                parent_category.push($(this).val());
            }
        });

        var title=$("#taw-form-family-name").val();
        var id=$("#taw-form-family-id").val();

        var data = {
            action: 'save_package_cate_group',
            category: category,
            parent_category: parent_category,
            type: currProdId,
            title: title,
            id: id,
        };

        jQuery.post(aurl, data, function (responce) {
            if (responce.data.success) {

                $("#TB_closeWindowButton").trigger("click");
                if (responce.data.success) {
                    $("#taw-lst-acs-cate li[data-id='"+currProdId+"']").trigger("click");
                }else{
                    alert("something went wrong, please try again later");
                }
            }

        });
    });


   

    $('body').undelegate('#taw-section-form-sbmt', 'click').delegate('#taw-section-form-sbmt', 'click', function (e) {
        var name = $("#taw-section-form-name").val();
      
       
        var data = {
            action: 'save_section_group',         
            name: name
        };

        jQuery.post(aurl, data, function (responce) {
            if (responce.data.success) {
                location.reload(true);
            }

        });
    });

    $('body').undelegate('#taw-lst-family-section li', 'click').delegate('#taw-lst-family-section li', 'click', function () {
        $('#taw-lst-family-section li').removeClass("active");
        $(this).addClass("active");

        currProdId = $(this).attr('data-id');
        currentTab = "acs";
        $("#taw-lst-family-section-text").html('<div style="text-align: center;vertical-align: middle; min-height: 500px;  display: block; padding-top: 200px;"><span class="dashicons dashicons-update aloader"></span></div>');

        let data = {
            'action': 'taw_get_section_text',
            'id': currProdId
        }
        jQuery.post(aurl, data, function (responce) {
            // location.reload( true );


            $("#taw-lst-family-section-text").html(responce);

        });
    });

   

    $('body').undelegate('#taw-accessories-group-form-submit', 'click').delegate('#taw-accessories-group-form-submit', 'click', function (e) {
        var cateid = $("#taw-accessories-group-form-cateid").val();
        var art_no = $("#taw-accessories-group-form-artno").val();

        if(art_no==""){
            $("#taw-accessories-group-form-artno").css("border","solid 1px red");
            $("#taw-accessories-group-form-artno").attr("placeholder","required");
            return;
        }
        $("#taw-accessories-group-form-artno").css("border","solid 1px #8c8f94");     

        var data = {
            action: 'save_package_accessories_artno',
            ag_cat_id: cateid,
            art_no: art_no,
            pkg_id: currProdId,
        };

        jQuery.post(aurl, data, function (responce) {
            $("#TB_closeWindowButton").trigger("click");
            if (responce.data.success) {                
                $("#taw-lst-acs li[data-id='"+currProdId+"']").trigger("click");                
            }else{
                alert("something went wrong, try again later");
            }

        });
    });

    $('body').undelegate('#taw-acs-family-form-submit', 'click').delegate('#taw-acs-family-form-submit', 'click', function (e) {
        var family_id = [];

        $("#taw-acs-family-form-family li input[type='checkbox']").each(function () {
            if ($(this).prop("checked")) {
                family_id.push($(this).val());
            }
        });
        var data = {
            action: 'save_package_family',
            family_id: family_id,
            pkg_id: currProdId,
        };

        jQuery.post(aurl, data, function (responce) {
            if (responce.data.success) {
                //location.reload(true);

                $("#TB_closeWindowButton").trigger("click");
                if (responce.data.success) {
                    $("#acs-config-item-cont-family").removeClass("loaded");
                    $("#prod-config-item-menu li[data-type='prod-family']").trigger("click");
                }else{
                    alert("something went wrong, please try again later");
                }
            }

        });
    });

    

    $('body').undelegate('#taw-accessories-condition-property', 'change').delegate('#taw-accessories-condition-property', 'change', function (e) {
        var option = $(this).find("option:checked");
        var type = option.attr("data-type");
        
        $("#taw-accessories-condition-value .item").css("display","none");

        if (type == "option") {
            $("#taw-accessories-condition-condition").val("==");
            var option = option.attr("data-options").split(',');
            var html='';
            for(var i=0;i<option.length;i++){
                html+='<option value="'+option[i]+'">'+(option[i].split("::")[1]?option[i].split("::")[1]:option[i])+'</option>';
            }
            $("#taw-accessories-condition-value select").html(html);

            $("#taw-accessories-condition-condition .option").attr("disabled",true);
        }else{
            $("#taw-accessories-condition-condition .option").attr("disabled",false);
        }

        $("#taw-accessories-condition-value").attr("data-input",type);

        $("#taw-accessories-condition-value ." + type).css("display", "block");
    });

    $('body').undelegate('#taw-accessories-condition-form-submit', 'click').delegate('#taw-accessories-condition-form-submit', 'click', function (e) {
        var property=$("#taw-accessories-condition-property").val();
        var condition=$("#taw-accessories-condition-condition").val();
        var input=$("#taw-accessories-condition-value").attr("data-input");
        var value=$("#taw-accessories-condition-value ."+input+" .val").val();

        var id=$(this).attr("data-id");
        var column=$(this).attr("data-column");
        var art_no=$(this).attr("data-artno");
        var save_type=$(this).attr("data-save-type");
        var option_id=$(this).attr("data-option_id");
        var item_artno=$(this).attr("data-item-artno");
        var rule_set_key=$(this).attr("data-rule-set-key");

        var data = {
            action: 'save_article_condition',            
            id: id,
            column: column,
            art_no: art_no,
            save_type: save_type,
            property:property,
            condition:condition,
            value:value,
            option_id:option_id,
            item_artno:item_artno,
            rule_set_key:rule_set_key,
        };

        jQuery.post(aurl, data, function (responce) {

            $("#TB_closeWindowButton").trigger("click");
            if (responce.data.success) {
               
                currentConditionElm.trigger("click"); 
                             
            }else{
                alert("something went wrong, please try again later");
            }

        });
    });

    $('body').undelegate('.taw-delete-acs-rule', 'click').delegate('.taw-delete-acs-rule', 'click', function (e) {

        if (confirm("Are you sure?")) {
            var parent=$(this).closest(".taw_article_condition_hld");
            var id = parent.attr("data-id");
            var item_id = parent.attr("data-item-id");
            var key = $(this).attr("data-key");
            var rule_set_key = $(this).attr("data-rule-set-key");
            var type = $(this).attr("data-type");
            var column = $(this).attr("data-column");
            var save_type = parent.attr("data-save-type");
            var art_no = parent.attr("data-art-no");
            var item_artno = $(this).attr("data-item-artno");

         
            var data = {
                action: 'remove_article_condition',
                property: key,
                id:id,
                type:type,
                column:column,
                save_type:save_type,
                item_artno:item_artno,
                art_no:art_no,
                rule_set_key:rule_set_key,
                item_id:item_id,
            };
            var T = this;

            jQuery.post(aurl, data, function (responce) {
                if(type=="article"){
                    $(T).closest(".taw-row").remove();
                }else if(type=="rule_set"){
                    $(T).closest(".rule-set-item").remove();
                }else{
                    $(T).closest("tr").remove();
                }
            });
        }

    });

    $('body').undelegate('.taw-acs-condition-btn', 'click').delegate('.taw-acs-condition-btn', 'click', function (e) {
        var parent=$(this).closest(".taw_article_condition_hld");
        var gid =parent.attr("data-id");
        var column = $(this).attr("data-column");
        var art_no = parent.attr("data-art-no");
        var save_type = parent.attr("data-save-type");
        var option_id = parent.attr("data-option_id");
        var id = "taw-accessories-condition-form";
        var item_art=$(this).attr("data-art-no");
        var rule_set_key=$(this).attr("data-rule-set-key");
        var h = 330;

        $("#taw-accessories-condition-form-submit").attr("data-id",gid);
        $("#taw-accessories-condition-form-submit").attr("data-column",column);
        $("#taw-accessories-condition-form-submit").attr("data-artno",art_no);
        $("#taw-accessories-condition-form-submit").attr("data-save-type",save_type);
        $("#taw-accessories-condition-form-submit").attr("data-option_id",option_id);
        $("#taw-accessories-condition-form-submit").attr("data-item-artno",item_art);
        $("#taw-accessories-condition-form-submit").attr("data-rule-set-key",rule_set_key);
       
        tb_show(item_art+" - Add Rule", "#TB_inline?height=" + h + "&amp;width=400&amp;inlineId=" + id);
    });


    $('body').undelegate('.taw-acs-add-more-condition-btn', 'click').delegate('.taw-acs-add-more-condition-btn', 'click', function (e) {
        var parent=$(this).closest(".taw_article_condition_hld");
        var id =parent.attr("data-id");
        var column = $(this).attr("data-column");
        var art_no = parent.attr("data-art-no");
        var save_type = parent.attr("data-save-type");
        var option_id = parent.attr("data-option_id");
        var item_artno=$(this).attr("data-art-no");

        var data = {
            action: 'save_article_condition',            
            id: id,
            column: column,
            art_no: art_no,
            save_type: save_type,
            option_id:option_id,
            item_artno:item_artno,
        };

        jQuery.post(aurl, data, function (responce) {

            $("#TB_closeWindowButton").trigger("click");
            if (responce.data.success) {
                currentConditionElm.trigger("click");
            }else{
                alert("something went wrong, please try again later");
            }

        });
       
    });

    
   

    $('body').undelegate('#taw-accessories-switch-by-selection-form-submit', 'click').delegate('#taw-accessories-switch-by-selection-form-submit', 'click', function (e) {
          
        var swtich_art_no=$("#taw-accessories-switch-by-selection-form-artno").val();
        var label=$("#taw-accessories-switch-by-selection-form-label").val();
        var img_id=$("#taw-accessories-switch-by-selection-form-img").attr("data-img-id");       
        var id=$(this).attr("data-id");    
        var art_no=$(this).attr("data-art-no");    
        var save_type=$(this).attr("data-save-type");    
        var option_id=$(this).attr("data-option_id");    

        var data = {
            action: 'save_article_switch_by_selection',            
            id: id,
            img_id: img_id,
            label:label,
            swtich_art_no:swtich_art_no,
            save_type:save_type,
            art_no:art_no,
            option_id:option_id,
        };

        jQuery.post(aurl, data, function (responce) {

            $("#TB_closeWindowButton").trigger("click");
            if (responce.success) {
                currentConditionElm.trigger("click");
            }else{
                alert("something went wrong, please try again later");
            }

        });

    });

    $('body').undelegate('#taw-preconfig-form-submit', 'click').delegate('#taw-preconfig-form-submit', 'click', function (e) {
        var title=$("#taw-preconfig-form-title").val();
        var family=$("#taw-preconfig-form-family").val();
        
        if(title==""){
            $("#taw-preconfig-form-title").css("border-color","red");
            return;
        }
        var far=family.split("::");
        var type=$(this).attr("data-type");

        var action='save_pre_config_product';
        if(type=="campaign"){
            action='save_product_campaign';
        }

        var data = {
            action: action,
            title: title,
            family_id:far[0],
            family_name:far[1]        
        };
        var T = this;

        jQuery.post(aurl, data, function (responce) {
            if(responce.success){
                window.open(responce.data);          
            }else{
                alert("something went wrong please try again later");
            }
           
        });
    });

    $('body').undelegate('#taw-family-section-text-form-sbmt', 'click').delegate('#taw-family-section-text-form-sbmt', 'click', function (e) {
        var title=$("#taw-family-section-text-form-title").val();
        var content=$("#taw-family-section-text-form-content").val();
        var id=$(this).attr("data-id");
        if(title==""){
            $("#taw-family-section-text-form-title").css("border-color","red");
            return;
        }else{
            $("#taw-family-section-text-form-title").css("border-color","#8c8f94");
        }

        if(content==""){
            $("#taw-family-section-text-form-content").css("border-color","red");
            return;
        }else{
            $("#taw-family-section-text-form-content").css("border-color","#8c8f94");
        }
      
        var data = {
            action: 'save_family_section_text',
            title: title,
            content:content,
            id:id,            
            group_id:$("#taw-family-section-text-form-group_id").val(),            
        };
        var T = this;

        jQuery.post(aurl, data, function (responce) {
            if(responce.success){
                $("#TB_closeWindowButton").trigger("click");
                $("#taw-family-section-text-form-sbmt").attr("data-id","");
                $("#taw-family-section-text-form-title").val("");
                $("#taw-family-section-text-form-content").val("");

                $("#prod-section-text-item-section").removeClass("loaded");
                $("#prod-section-text-item-menu li:first").trigger("click");
            }else{
                alert("something went wrong please try again later");
            }
           
        });
    });

    $('body').undelegate('#prod-section-text-item-menu li', 'click').delegate('#prod-section-text-item-menu li', 'click', function () {
        $('#prod-section-text-item-menu li').removeClass("active");

        currentTab = $(this).attr("data-type");
        $(this).addClass("active");
        $('.p-c-i-c').hide();
        var id = $(this).attr("data-id");
        $("#" + id).show();
        if (!$("#" + id).hasClass("loaded")) {
            $("#" + id).html('<div style="text-align: center;vertical-align: middle; min-height: 500px;  display: block; padding-top: 200px;"><span class="dashicons dashicons-update aloader"></span></div>');
           
            var url = "section_text_item_tab";
            
            var data = {
                action: url,
                type: currentTab,
                group_id: $("#prod-section-text-item-menu").attr("data-group_id")
            };

            jQuery.post(aurl, data, function (responce) {
                $("#" + id).html(responce);
                $("#" + id).addClass("loaded");
            });
        }

    });


    $('body').undelegate('#taw-section-family-form-submit', 'click').delegate('#taw-section-family-form-submit', 'click', function (e) {
        var family_id = [];

        $("#taw-acs-family-form-family li input[type='checkbox']").each(function () {
            if ($(this).prop("checked")) {
                family_id.push($(this).val());
            }
        });
        
        var data = {
            action: 'save_section_text_family',
            family_id: family_id,
            group_id: $("#prod-section-text-item-menu").attr("data-group_id"),
        };

        jQuery.post(aurl, data, function (responce) {
            if (responce.data.success) {
                location.reload(true);
            }

        });
    });

    $('body').undelegate('.taw-view-option-condition', 'click')
    .delegate('.taw-view-option-condition', 'click', function (e) {
        
        var type=$(this).attr("data-type");
        var art_no=$(this).attr("data-art-no");
        var option_id=$(this).attr("data-option_id");
        var option_name=$(this).attr("data-option_name");
        currentConditionElm=$(this);        
       
        if(typeof product_id!="undefined"){
            currProdId=product_id;
        }     

        $(this).closest(".taw-option-tbl").find(".taw-view-option-condition").removeClass('active');
        $(this).addClass("active");

        var loader='<div style="text-align: center;vertical-align: middle; height:100%; display: block; padding-top: 130px;"><span class="dashicons dashicons-update aloader"></span></div>';
        
        var hld;
        if(type=="pkg"){           
            hld=$("#acs-config-item-cont-accessories .taw-view-condition-hld");
        }else if(type=="product"){
            hld=$("#taw-product-view-condition-hld");
        }else{
            hld=$("#prod-config-item-cont-"+currentTab+" .taw-view-condition-hld");
        }
       
        hld.html(loader);      
    
        var data = {
            action: 'get_article_condition',
            base_id: currProdId,
            type: type,
            option_id:option_id,
            option_name:option_name,
            art_no:art_no
        };

        jQuery.post(aurl, data, function (responce) {
               
            hld.html(responce);
            hld.find("#sortable_varient").sortable({
                revert       : true,
                stop         : function(event,ui){ 
                    var items=event.target.children;
                    var ids=[];
                    for(var i=0;i<items.length;i++){
                        ids.push($(items[i]).attr("data-id"));                       
                    }                   

                    var data = {
                        action: 'save_condition_varient_order',
                        value:ids,                     
                        id:$(this).attr("data-item-id")
                    };

                    // $("#taw_order_loader").show();

                    jQuery.post(aurl, data, function (responce) {
                        if (responce.data.success) {

                        }
                    });
                }              
            }) 


            if(currentConditionTab){
               var tab=hld.find(".taw-rule-action-lst li[data-id="+currentConditionTab+"]");
               if(tab){
                    tab.trigger("click");
               }
            }       
        });       
    });    

    $('body').undelegate('.taw-edit-family-section-btn', 'click')
    .delegate('.taw-edit-family-section-btn', 'click', function (e) {
        var title=$(this).closest("tr").find(".s_title").html();
        var desc=$(this).closest("tr").find(".s_desc").html();

        $("#taw-family-section-text-form-title").val(title);
        $("#taw-family-section-text-form-content").val(desc);

        var id=$(this).attr("data-id");
        $("#taw-family-section-text-form-sbmt").attr("data-id",id);

        tb_show("Edit", "#TB_inline?height=350&amp;width=400&amp;inlineId=taw-family-section-text-form");
        
    });

    function initSizeSortFunc(){
        if($("#sortable_sizes").length>0){
            $("#sortable_sizes").sortable({
                revert       : true,
                stop         : function(event,ui){ 
                    var items=event.target.children;
                    var ids=[];
                    for(var i=0;i<items.length;i++){
                        ids.push($(items[i]).attr("data-id"));                       
                    } 
        
                    var data = {
                        action: 'save_size_reorder',
                        ids:ids
                    };
        
                    // // $("#taw_order_loader").show();
        
                    jQuery.post(aurl, data, function (responce) {
                       console.log(responce);
                    });
                },start : function(){
                    console.log("started");
                }         
            }) 
        }

        $('body').undelegate('#sortable_sizes .taw-edit-option', 'click')
        .delegate('#sortable_sizes .taw-edit-option', 'click', function (e) {
            var art_no=$(this).attr("data-art-no");
            var sliding_art_no=$(this).attr("data-sliding-art-no");
            var id=$(this).attr("data-id");
        

            $("#taw-form-size-artno-v").val(art_no);
            $("#taw-form-size-artno-slide-v").val(sliding_art_no);
        

            var id=$(this).attr("data-id");
            $("#taw-form-size-artno-submit").attr("data-id",id);

            tb_show("Edit", "#TB_inline?height=200&amp;width=400&amp;inlineId=taw-form-size-artno");
            
        });

        $('body').undelegate('#taw-form-size-artno-submit', 'click')
        .delegate('#taw-form-size-artno-submit', 'click', function (e) {
            var art_no=$("#taw-form-size-artno-v").val();
            var slide_art_no=$("#taw-form-size-artno-slide-v").val();
            var id=$(this).attr("data-id");

            var data = {
                action: 'save_size_artno',
                art_no:art_no,
                slide_art_no:slide_art_no,
                id:id,
            };

            // // $("#taw_order_loader").show();

            jQuery.post(aurl, data, function (responce) {
           
               $("#TB_closeWindowButton").trigger("click");
               $("#prod-config-item-cont-size").removeClass("loaded")
               $("#prod-config-item-menu li.active").trigger("click");

            //    $("#taw-lst-f-p li.active").trigger("click");
            });

        });

        
    }


    $('.btn-sync').on('click',function (e) {

        $(this).addClass("loading");
        $(this).attr("disabled",true);
        var type= $(this).attr("data-type");
        syncProductData(type,0);
        
        
    });



    function syncProductData(type,page_num){
        var data = {
            action: 'sync_woocommerce_product_data',           
            page_num: page_num,           
            type: type,           
        };

        jQuery.post(aurl, data, function (responce) {
            if (responce.success) {     
               var btn=$('.btn-sync[data-type="'+type+'"');      
               if(responce.data.end==1){
                btn.removeClass("loading");
                btn.attr("disabled",false);
                btn.find('.load-text').text("Completed");
               }else{
                btn.find('.load-text').text(responce.data.percentage+" %");
                syncProductData(type,responce.data.page_num);
               }
            }

        });
    }
    $('.btn-syncri').on('click', function (e) {

        var btn = $(this); // Store the button in a variable
    
        btn.addClass("loading");
        btn.attr("disabled", true);
        var type = btn.attr("data-type");
        sync_woocommerce_ajax(type,0);
       
    });
function sync_woocommerce_ajax(type,page_num)
{
    
    var data = {
        action: 'sync_woocommerce_data',
        page_num: page_num,
        type: type,
    };
    jQuery.post(aurl, data, function (responce) {
        console.log(responce);
        var responseMessage = $('#response-message');
        if (responce.success) {     
           var btn=$('.btn-syncri[data-type="'+type+'"');      
           if(responce.data.end==1){
            btn.html('Synchronized successfully');
            //responseMessage.show();
            
           }else{
            btn.html(responce.data.percentage+" %");
            //responseMessage.show();
           // btn.find('.load-text').text(responce.data.percentage+" %");
           sync_woocommerce_ajax(type,responce.data.page_num);
         //  syncProductData(type,responce.data.page_num);
           }
        }

    });/*
    jQuery.post(aurl, data, function (response) {
        console.log('AJAX Response:', response);
        var responseMessage = $('#response-message');

        if (response === 'Synchronized successfully') {
            // Show the response message and hide the button
            responseMessage.html('Synchronized successfully');
            responseMessage.show();
            btn.hide();
        } else {
            // Show an error message
            responseMessage.html('Could not sync data');
            responseMessage.show();
        }
        btn.removeClass("loading");
    btn.attr("disabled", false);
    }); */
} 
});
