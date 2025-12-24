(function($){
	$('document').ready(function(){
		
		// if($("#custom_user_request_table").length>0){
		// 	$("#custom_user_request_table").DataTable();
		// }

		

		var first=$(".custom_role_ls:first");
		var overflow_dlg=$("#custom-uam-overflow-action-dlg");	
		
       //Delete Subrole
        $(".remove-subrole").click(function(event) {
            event.preventDefault();
            var clickedSubrole = $(this).closest('.custom-uam-subrole-li').data('id');
            var clickedRole = $(this).closest('.custom-uam-role-li').data('role');
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'custom_uam_removesubrole', // Action to trigger the PHP function
                    clickedSubrole: clickedSubrole,
                    clickedRole: clickedRole
                    
                },
                success: function(response) {
                   
                    location.reload(true);
                },
                error: function(xhr, status, error) {
                    // Optionally handle errors here
                    console.error('error',error);
                }
            });
        }); 

        $('#roleissubrole').change(function() {
            if ($(this).is(':checked')) {
                $('#role-fields').show();

            } else {
                $('#role-fields').hide();

            }
        });
		
		$("#custom_uam_save_role").submit(function(e){
			e.preventDefault();
			var form = $(this);
            var roleissubrole = 0;
            var role = $("#custom-uam-input-role-name").val();
            var role_id = form.data('clicked-role-data'); // Get the c
			if(role==''){
				$("#custom-uam-alert-error-txt").css("display","block").text("This field is required");
				return;
			}
			$("#custom-uam-alert-error-txt").css("display","none");
            var formData = {
            action: 'custom_uam_save_role',
            role_name: role,
            roleissubrole: roleissubrole, // Add issubrole parameter to the form data
            role_id:role_id
            };
                
            $.post(form.attr('action'), formData, function(data) {
				if(data.error){
					$("#custom-uam-alert-error-txt").css("display","block").text(data.message);					
				}else{
					window.location.reload();
				}
			}, 'json');
    	});

        // $("#custom_uam_save_subrole").submit(function(e){
        //     e.preventDefault();
        //     var form = $(this);
        //     var roleissubrole = 1;
        //     var role = $("#custom-uam-input-subrole-name").val();
        //     console.log(roleissubrole);
        //     if(role==''){
        //         $("#custom-uam-alert-suberror-txt").css("display","block").text("This field is required");
        //         return;
        //     }
        //     $("#custom-uam-alert-suberror-txt").css("display","none");
        //     var formData = {
        //     action: 'custom_uam_save_subrole',
        //     role_name: role,
        //     roleissubrole: roleissubrole // Add issubrole parameter to the form data
        //     };
                
        //     $.post(form.attr('action'), formData, function(data) {
        //         if(data.error){
        //             $("#custom-uam-alert-suberror-txt").css("display","block").text(data.message);                    
        //         }else{
        //             window.location.reload();
        //         }
        //     }, 'json');
        // });

        $("#custom_uam_update_subrole").submit(function(e){
            e.preventDefault();
            var form = $(this);
            var clickedSubrole = $('#clicked-subroleid-value').val();
            var clickedroleid = $('#clicked-roleid-value').val();
            var newsubrole_name = $('#customsubrole-value').val();
            var roleissubrole = 1;
            
            var formData = {
                action: 'custom_uam_update_subrole',
                clickedSubrole: clickedSubrole,
                clickedroleid: clickedroleid,
                newsubrole_name: newsubrole_name,
                roleissubrole:roleissubrole
            };

            //console.log('formdata',formData);

            $.post(form.attr('action'), formData, function(data) {
                if(data.error){
                    console.error(data.message);                    
                }else{
                    window.location.reload();
                }
            }, 'json');
        });

		$("#custom_uam_cap_save_btn").click(function(e){
			custom_uam_save_cap();
		})





		$("body").undelegate(".custom_role_ls .custom-uam-overflow-action","click").delegate(".custom_role_ls .custom-uam-overflow-action","click",function(){
			var offset=$(this).offset();
			var id=$(this).closest("li").attr("data-id");
			var title=$(this).closest("li").find(".custom-uam-role-li-title").text();
			overflow_dlg.css({"left":offset.left-180,"top":offset.top,"display":"block"});
			overflow_dlg.attr("data-id",id);
			overflow_dlg.attr("data-title",title);			
		});
		

		$("#custom-uam-add-new-role-btn").click(function(){
			$("#custom-uam-alert-add-role").modal("show");
		});


        $('.deleteRoleImg').on('click', function() {
            var roleID = $(this).closest('.custom-uam-role-li').attr('data-id');
            var roleName = $(this).closest('.custom-uam-role-li').find('.custom-uam-role-li-title').text();
            
            if (window.confirm("Are you sure you want to delete the role '" + roleName + "'?")) {
                $.post(custom_uam_ajax_url, {
                    "action": "custom_uam_remove_role",
                    "id": roleID
                }, function(data) {
                    if (data.error) {
                        $("#custom-uam-alert-error-txt-pwd").css("display", "block").text(data.message);
                    } else {
                        window.location.reload();
                    }
                }, 'json');
            }
        });

		$("#custom-uam-overflow-action-dlg-close").click(function(e){
			overflow_dlg.css("display","none");
		})
		$("#custom-uam-overflow-action-dlg ul > li").click(function(e){
			var type=$(this).attr("data-id");
			if(type=="edit"){
				var title=$("#custom-uam-overflow-action-dlg").attr("data-title");
				var id=$("#custom-uam-overflow-action-dlg").attr("data-id");
				var url="#TB_inline?&width=300&height=200&inlineId=custom-uam-alert-add-edit-dlg";
				$("#custom-uam-input-role-name").val(title);
				$("#custom-uam-input-role-id").val(id);
				tb_show(role_form_edit_txt,url);
			}else if(type=="remove"){
				var id=$("#custom-uam-overflow-action-dlg").attr("data-id");
				var title=$("#custom-uam-overflow-action-dlg").attr("data-title");
				if(window.confirm(role_remove_txt+" '"+title+"'")){
					$.post(custom_uam_ajax_url, {"action":"custom_uam_remove_role","id":id}, function(data) {
						if(data.error){
							$("#custom-uam-alert-error-txt-pwd").css("display","block").text(data.message);					
						}else{
							window.location.reload();
						}
					}, 'json');
				}				
			}
			
		})
		
		$("body").undelegate("input.c_uam_cap_cls","change").delegate("input.c_uam_cap_cls","change",function(){
			custom_uam_cap_changed();
		});

		$("body").undelegate("#custom_user_request_table .custom_user_request_accept","click").delegate("#custom_user_request_table .custom_user_request_accept","click",function(){
			acceptUserReq(this);
		});

		function acceptUserReq(e){
            var id = $(e).closest("tr").attr("data-id");
			$("#custom-uam-user-id").val(id);		
			tb_show("Accept user", "#TB_inline?height=150&amp;width=300&amp;inlineId=custom-uam-alert-add-edit-dlg");		
            // Perform an AJAX request to get details from the custom_uam_user_requests table
            $.ajax({
                type: 'POST',
                url: cajax_url, // Assuming cajax_url is defined and points to the WordPress admin-ajax.php file
                data: {
                    action: 'get_user_request_details', // Add a new AJAX action to handle this request on the server-side
                    id: id
                },
			
                success: function(response){
                    // Handle the response from the server
                    // console.log(response);

                    // You can update the modal or perform any other actions based on the response
                },
                error: function(error){
                    console.error(error);
                }
            });

        }

        // $(".custom-uam-role-li").click(function() {
        //     var self = this;
        //     // Check if the clicked list item has the class 'active'
            // if ($(self).hasClass('active')) {
            //    console.log('active');
            // }else{
            //     console.log('notactive');
            // }
        // });

        // $(".custom-uam-role-li").click(function() {
        //     var self = this; // Store the reference to 'this'
        
        //     setTimeout(function() {
        //        // console.log("hai:", $(self).hasClass('active'));
        //         if ($(self).hasClass('active')) {
        //             console.log('active');
        //          }else{
        //              console.log('notactive');
        //          }
        //     }, 10); // Delay execution by 10 milliseconds
        // });


		$("body").undelegate("#custom_user_request_table .custom_user_request_delete","click").delegate("#custom_user_request_table .custom_user_request_delete","click",function(){
			deleteUserReq(this);
		});

		$(".custom_role_ls li:first").trigger('click');

		$("#custom_uam_save_user").submit(function(e){
			e.preventDefault();
			var form = $(this);

            var enteredPassword = $("#custom-uam-input-pwd").val();
			$.post({
                url: form.attr('action'),
                data: {
                    action: 'validate_user_password',
                    entered_password: enteredPassword,
                    // Add any additional data you need to send for validation
                },
				success: function(data) {
                    if (data.error) {
                        // Display error message if the password is incorrect
                        $("#custom-uam-alert-error-txt-pwd").css("display", "block").text(data.message);
                    } else {
                        // Password is correct, proceed with form submission
                        $("#custom-uam-alert-error-txt-pwd").css("display", "none");
                        $.post(form.attr('action'), form.serialize(), function(response) {
                            // Handle the response as needed
                            if (response.error) {
                                // Display error message if submission fails
                                $("#custom-uam-alert-error-txt-pwd").css("display", "block").text(response.message);
                            } else {
                                // Successful submission
                                window.location.reload();
                            }
                        }, 'json'); }
					},
					dataType: 'json'
				}); });


	});

	// function acceptUserReq(e){
    //  var id=$(e).closest("tr").attr("data-id");
    //  $("#custom-uam-user-id").val(id);       
    //  tb_show("Accept user", "#TB_inline?height=150&amp;width=300&amp;inlineId=custom-uam-alert-add-edit-dlg");       
	// }

	function deleteUserReq(e){
		if(window.confirm("Are you sure")){
			var id=$(e).closest("tr").attr("data-id");
			$.post(cajax_url,{"action":"custom_uam_delete_user","id":id}, function(data) {
				if(data.error){
					$("#custom-uam-alert-error-txt").css("display","block").text(data.message);					
				}else{
					window.location.reload();
				}
			}, 'json');
		}
		
	}
	
// Handle clicks on roles
$("body").undelegate(".custom_role_ls .custom-uam-role-li", "click").delegate(".custom_role_ls .custom-uam-role-li", "click", function(event) {
    // Check if the clicked element is a role
    if ($(event.target).hasClass('custom-uam-role-li')) {
        custom_uam_update_capablilities(this);
    }
});

// Handle clicks on subroles
$("body").undelegate(".subroles .custom-uam-subrole-li", "click").delegate(".subroles .custom-uam-subrole-li", "click", function(event) {
    // Check if the clicked element is a subrole
    if ($(event.target).hasClass('custom-uam-subrole-li')) {
        custom_uam_subroleupdate_capablilities(this);
    }
});

    // $("body").undelegate(".custom_role_ls .custom-uam-role-li","click").delegate(".custom_role_ls .custom-uam-role-li","click",function(){
    //  custom_uam_update_capablilities(this);
    // });
    // $("body").undelegate(".subroles .custom-uam-subrole-li","click").delegate(".subroles .custom-uam-subrole-li","click",function(){
    //  custom_uam_subroleupdate_capablilities(this);
    // });
    function custom_uam_subroleupdate_capablilities(e){
        console.log("calledsubrolepermission");
		var id=$(e).attr('data-id');
        console.log('id:',id);
		
        $(".subroles li").removeClass('active');
		$(e).addClass('active');

        $.post(custom_uam_ajax_url, {action:'custom_uam_get_subrole_cap',id:id}, function(data) {
			if(data.error){
				alert(data.message);
			}else{
                console.log('Onesubrole',id);
				$(".c_uam_cap_cls").prop('checked',false);

				var ca_ar=data.datas.capabilities;				
				$.each(ca_ar, function( key, value ) {
					console.log(value);
				  if(value==true){
				  	console.log(key);
				  	$("#"+key).prop('checked',true);
				  }
				});					
				
			}
		}, 'json');

    }

    function custom_uam_update_capablilities(e){
        console.log("called");
        var id=$(e).attr('data-id');
        console.log('id:',id);
        
        $(".custom_role_ls li").removeClass('active');
        $(e).addClass('active');

       

        $.post(custom_uam_ajax_url, {action:'custom_uam_get_role_cap',id:id}, function(data) {
            if(data.error){
                alert(data.message);
            }else{
                console.log('Twosubrole',id);
                $(".c_uam_cap_cls").prop('checked',false);

                var ca_ar=data.datas.capabilities;              
                $.each(ca_ar, function( key, value ) {
                    // console.log(value);
                  if(value==true){
                    // console.log(key);
                    $("#"+key).prop('checked',true);
                  }
                });                 
                
            }
        }, 'json');

	}

	function custom_uam_cap_changed(){
		$("#custom_uam_cap_save_btn").css('display','block');
	}

	function custom_uam_save_cap(){

        if ($(".custom_role_ls li.active").length > 0) 
        {
		var id=$(".custom_role_ls li.active").attr('data-id');
        }

        if ($(".subroles li.active").length > 0) 
        {
        var id=$(".subroles li.active").attr('data-id');
        }

		var ids=[];
		$(".c_uam_cap_cls").each(function(){
			if($(this).prop("checked")){
				ids.push($(this).attr("id"));
			}
		});		

		if(ids.length>0){
			$.post(custom_uam_ajax_url, {data:ids,id:id,action:'custom_uam_save_cap'}, function(data) {
				if(data.error){
					alert(data.message);
				}else{
					alert("Success");
					//window.location.reload();
				}
			}, 'json');
		}		
	}

})(window.jQuery);
