jQuery(function ($) {
    $("#taw-s3-btn-sync").on("click",function(){

       var data= {
            action: "syncMediaToS3",
            page_num: 0,
            count:-1,
            req:1           
        }
        $(this).addClass("loading");
        uploadMediaS3(data);
        
    });

    function uploadMediaS3(data){       

        jQuery.ajax({
            type: "POST",
            url: aurl,
            data:data,
            success: function (res) {
                if(res.success){
                    $("#taw-s3-btn-sync .load-text").html(res.data.percentage+" %");
                    if(res.data.req==1){
                        $("#taw-s3-info-sync .msg").html(res.data.remaining_item);                        
                        uploadMediaS3(res.data);
                    }else{
                        $("#taw-s3-info-sync .msg").html("Completed");
                        $("#taw-s3-btn-sync").removeClass("loading");
                    }
                }
              
            },
            error: function (errorThrown) {
               
            }
        });
    }
});