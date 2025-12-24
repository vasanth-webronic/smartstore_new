jQuery(function ($) {

    var selectedRoleId = null; // Variable to store the selected role ID
    var selectedRolename= null ;
    // Roles Tab Click Event

    // $(document).on('tb_unload', function() {
    //     // Empty the entered-artnos list
    //     $("#entered-artnos").empty();
    //     $("#all-artnos").val('');
    // });

    $('#roles-tab').on('click', function(e) {
        e.preventDefault();
        $('#roles-tab').addClass('navdd-tab-active').attr('aria-current', 'page');
        $('#users-tab').removeClass('navdd-tab-active').removeAttr('aria-current');

        $('#roles-content').show();
        $('#users-content').hide();
        $('#right-roles-content').show();
        $('#right-users-content').hide();
        $('#user-search').hide();

        // Trigger click event on the first role list item and mark it as active
        var firstRoleItem = $(".custom_restrict_ls li:first");
        firstRoleItem.addClass('active'); // Add active class
        custom_uam_update_restrictcapabilities(firstRoleItem[0]);
    });

    // Users Tab Click Event
    $('#users-tab').on('click', function(e) {
        e.preventDefault();
        $('#users-tab').addClass('navdd-tab-active').attr('aria-current', 'page');
        $('#roles-tab').removeClass('navdd-tab-active').removeAttr('aria-current');

        $('#roles-content').hide();
        $('#users-content').show();
        $('#right-roles-content').hide();
        $('#right-users-content').show();
        $('#user-search').show();

        // Remove active class from all user list items
        var userItems = $('.custom-uam-restrictproductuser-li');
        userItems.removeClass('active');

        // Add active class to the first user list item
        if (userItems.length > 0) {
            var firstUserItem = $(".custom_restrictuser_ls li:first");
            firstUserItem.addClass('active'); // Add active class
            custom_uam_update_restrictusercapabilities(firstUserItem[0]); // Call the function with the first user item
        }
    });

    $('#user-search').on('input', function() {
        var filter = $(this).val().toLowerCase();
        var userItems = $('.custom-uam-restrictproductuser-li');

        userItems.each(function() {
            var userName = $(this).find('.custom-uam-restrictproductuser-li-title span:first-child').text().toLowerCase();
            if (userName.includes(filter)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Roles product Search
    $('#roleproduct-search').on('input', function() {
        var searchTerm = $(this).val().toLowerCase();
        console.log('rolesearch:', searchTerm);
    
        var noResults = true;
    
        $('#c_uam_caprole_ul li').each(function() {
            var artNo = $(this).find('span').eq(2).text().toLowerCase();
    
            if (artNo.includes(searchTerm)) {
                console.log('rolesearchartNo:', artNo);
                $(this).show();
                noResults = false;
            } else {
                $(this).hide();
               
            }
        });
    
        if (noResults) {
            $('#no-results').show();
        } else {
            $('#no-results').hide();
        }
    });

    // Users Product Search
    $('#userproduct-search').on('input', function() {
        var searchTerm = $(this).val().toLowerCase();
        //console.log('usersearch:',searchTerm);
        var nouserResults = true;
        $('#c_uam_capuser_ul li').each(function() {
            var artNo = $(this).find('span').eq(2).text().toLowerCase();
           
            if (artNo.includes(searchTerm)) {
                //console.log('usersearchartNo:',artNo);
                $(this).show();
                nouserResults = false;
            } else {
                //console.log('hai');
                $(this).hide();
            }
        });
        if (nouserResults) {
            $('#nouser-results').show();
        } else {
            $('#nouser-results').hide();
        }
    });

    $('.custom-uam-restrictproduct-li').on('click', function() {
        // Reset edit button style
        $('#editrestrictproduct').css('color', '#9CA3AF');
        $('#editrestrictproduct').data('editurl', '');
        $('.restrict-tooltip').css('display', 'block').css('opacity', '1');
       
    });

    $('.custom-uam-restrictproductuser-li').on('click', function() {
        // Reset edit button style
        $('#editrestrictuserproduct').css('color', '#9CA3AF');
        $('#editrestrictuserproduct').data('editurl', '');
        $('.restrict-tooltipuser').css('display', 'block').css('opacity', '1');
    });

    // Roles Tab Item Click Event
    $("body").on("click", ".custom_restrict_ls .custom-uam-restrictproduct-li", function(event) {
        // Remove active class from all role items
        $(".custom_restrict_ls .custom-uam-restrictproduct-li").removeClass("active");

        // Add active class to the clicked role item
        $(this).addClass("active");
        $('.restrict-prod-checkbox-item-container').hide();
        // Update the capabilities based on the clicked role
        custom_uam_update_restrictcapabilities(this);

    });

    // Trigger click event on the first role list item and mark it as active
    var firstRoleItem = $(".custom_restrict_ls li:first");
    firstRoleItem.addClass('active'); // Add active class
    custom_uam_update_restrictcapabilities(firstRoleItem[0]);

    // Users Tab Item Click Event
    $("body").on("click", ".custom_restrictuser_ls .custom-uam-restrictproductuser-li", function(event) {
        // Remove active class from all user items
        $(".custom_restrictuser_ls .custom-uam-restrictproductuser-li").removeClass("active");

        // Add active class to the clicked user item
        $(this).addClass("active");

        // Update the capabilities based on the clicked user
        custom_uam_update_restrictusercapabilities(this);
    });

    //When the page loads, ensure the first user item is active if users-content is visible
    $(document).ready(function() {
        if ($('#users-content').is(':visible')) {
            var firstUserItem = $(".custom_restrictuser_ls li:first");
            if (firstUserItem.length) {
                firstUserItem.addClass('active'); // Add active class
                custom_uam_update_restrictusercapabilities(firstUserItem[0]);
            }
        }
    });

    //Users function
    function custom_uam_update_restrictusercapabilities(e) {
        console.log("called");
        var id = $(e).attr('data-id');
        var rolename = $(e).attr('data-role');
        console.log('id:', id);
        
        // Store the selected role ID
        selectedRoleId = id;
        selectedRolename = rolename;
        $(".custom_restrictuser_ls li").removeClass('active');
        $(e).addClass('active');
    
        // Clear previous data
        $("#c_uam_capuser_ul").empty();
        // Clear search input
        $('#userproduct-search').val('');
        $('#nouser-results').hide();
        $('#loaderuser').show();
        // Disable the search input field and change cursor to 'not-allowed'
        $('#userproduct-search').prop('disabled', true).css('cursor', 'not-allowed');
        // Uncheck and hide the select all checkbox
        $('#restrictuser_select_all').prop('checked', false);
        // Clear delete button data attributes
        $('#deleterestrictuserproduct').removeData('artno');
        $('#deleterestrictuserproduct').removeData('roleid');
        $('#deleterestrictuserproduct').removeData('rolename');
        $.post(ajaxurl, { action: 'custom_uam_get_role_data', id: id }, function(response) {
            if (response.success) {
                var data = response.data;
                var listHtml = '';
    
                if (data.length > 0) {
                    $.each(data, function(index, value) {
                        // Get product title using AJAX
                        $.post(ajaxurl, { action: 'get_product_title_by_sku', sku: value.art_no }, function(productResponse) {
                            if (productResponse.success) {
                                var productTitle = productResponse.data.title;
                                $.post(ajaxurl, { action: 'get_edit_url_by_sku', sku: value.art_no }, function(editUrlResponse) {
                                    if (editUrlResponse.success) {
                                        var editUrl = editUrlResponse.data.edit_url;
                                        // listHtml += '<div class="check-item" style="display: flex; align-items: center;" data-artno="' + value.art_no + '" data-roleid="' + selectedRoleId + '" data-editurl="' + editUrl + '" data-rolename="' + rolename + '">';
                                        // listHtml += '<li class="list-item" style="background-color: rgb(255, 255, 255);padding: 10px;margin: 10px 15px;border-radius: 4px;" data-artno="' + value.art_no + '" data-roleid="' + selectedRoleId + '" data-editurl="' + editUrl + '" data-rolename="' + selectedRolename + '">';
                                        listHtml += '<div class="check-item" style="display: flex; align-items: center;" data-artno="' + value.art_no + '" data-roleid="' + selectedRoleId + '" data-editurl="' + editUrl + '" data-rolename="' + rolename + '">';
                                        listHtml += '<input type="checkbox" class="restrictuser-checkbox" style="margin-left: 10px; display:none;">'; // Checkbox outside the <li>
                                        listHtml += '<li class="list-item" style="width:100%;background-color: rgb(255, 255, 255); padding: 10px; margin: 10px 15px; border-radius: 4px; color: black; list-style: none; display: flex; flex-direction: column;" data-artno="' + value.art_no + '" data-roleid="' + selectedRoleId + '" data-editurl="' + editUrl + '" data-rolename="' + rolename + '">';
                                        listHtml += '<div style="padding: 4px; border-radius: 4px;">';
                                        listHtml += '<div style="display: flex; align-items: center;">';
                                        listHtml += '<span style="flex: 0.5; font-weight: bold; text-align: left;">Article Number</span>';
                                        listHtml += '<span style="flex: 0 0 10px; text-align: center;">:</span>';
                                        listHtml += '<span style="flex: 2; text-align: left; margin-left: 10px;">' + value.art_no + '</span>';
                                        listHtml += '</div>';
                                        listHtml += '<div style="display: flex; align-items: flex-start; margin-top: 5px;">';
                                        listHtml += '<span style="flex: 0.5; font-weight: bold; text-align: left;">Product Name</span>';
                                        listHtml += '<span style="flex: 0 0 10px; text-align: center; ">:</span>';
                                        listHtml += '<span style="flex: 2; text-align: left; margin-left: 10px;">' + productTitle + '</span>';
                                        listHtml += '</div>';
                                        listHtml += '</li>';
                                        listHtml += '</div>';
                                        $("#c_uam_capuser_ul").html(listHtml);
                                          $('#loaderuser').hide();
                                          $('#userproduct-search').prop('disabled', false).css('cursor', 'auto');
                                    } else {
                                        console.error(editUrlResponse.data);
                                    }
                                }, 'json');
                            } else {
                                console.error(productResponse.data);
                            }
                        }, 'json');
                    });
                } else {
                    listHtml += '<li style="background-color: rgb(255, 255, 255);padding: 10px;margin: 10px 15px;border-radius: 4px;">';
                    listHtml += '<span style="color: #000000; font-weight: medium;">No products found.</span>';
                    listHtml += '</li>';
                    $("#c_uam_capuser_ul").html(listHtml);
                    $('#loaderuser').hide();
                    $('#userproduct-search').prop('disabled', false).css('cursor', 'auto');
                }
            } else {
                $("#c_uam_capuser_ul").html('<li style="background-color: rgb(255, 255, 255);padding: 10px;margin: 10px 15px;border-radius: 4px;"><span style="color: #000000; font-weight: medium;">No products found.</span></li>');
                $('#loaderuser').hide();
                $('#userproduct-search').prop('disabled', false).css('cursor', 'auto');
            }
        }, 'json');
    }
    // Update the event handler for individual checkboxes
    $(document).on('change', '.restrictuser-checkbox', function() {
        var allChecked = true;
        $('.restrictuser-checkbox').each(function() {
            if (!$(this).prop('checked')) {
                allChecked = false;
                return false; // Exit each loop early if any checkbox is unchecked
            }
        });

        // Update the state of Select All checkbox
        $('#restrictuser_select_all').prop('checked', allChecked);
    });

    // Update the Select All checkbox change handler
    $('#restrictuser_select_all').on('change', function() {
        var isChecked = $(this).prop('checked');
        $('.restrictuser-checkbox').prop('checked', isChecked);
    });
    // Event delegation for dynamically created list items
    $("#c_uam_capuser_ul").on('click', '.list-item', function() {
        var isSelected = $(this).css('background-color') === 'rgb(34, 113, 177)' && $(this).css('color') === 'rgb(255, 255, 255)';

        $(".list-item").css({
            'background-color': 'white',
            'color': 'black'
        }); 
        // If the item was not already selected, set it as selected
        if (!isSelected) {
            console.log('1');
            $(this).css({
                'background-color': '#2271B1',
                'color': 'white'
            });

            // Store the edit URL in the edit button
            $('#editrestrictuserproduct').data('editurl', $(this).data('editurl'));
            $('#editrestrictuserproduct').css('color', '#ffffff');
        
            $('#deleterestrictuserproduct').data('artno', $(this).data('artno'));
            $('#deleterestrictuserproduct').data('roleid', $(this).data('roleid'));
            $('#deleterestrictuserproduct').data('rolename', $(this).data('rolename'));
        }else {
            // If the item is deselected, clear the data attributes of edit and delete buttons
            $('#editrestrictuserproduct').removeData('editurl');
            $('#editrestrictuserproduct').css('color', 'rgb(156, 163, 175)');
            
            $('#deleterestrictuserproduct').removeData('artno');
            $('#deleterestrictuserproduct').removeData('roleid');
            $('#deleterestrictuserproduct').removeData('rolename');
        }
    });
    
    // Edit User product
    $('#editrestrictuserproduct').on('click', function(event) {
        event.stopPropagation(); // Prevent propagation to document click handler
        var editUrl = $(this).data('editurl');
        console.log(editUrl);
    
        if (editUrl) {
            // Open edit URL in new tab
            window.open(editUrl, '_blank');
        } else {
            // Show tooltip if edit URL is not available
            var tooltip = $(this).find('.restrict-tooltipuser');
            tooltip.css('display', 'block').css('opacity', '1');
        }
    });

    // Handle click on document to hide tooltip when clicking outside
    $(document).on('click', function(event) {
        // Check if the clicked element is not the edit button or the tooltip itself
        if (!$(event.target).closest('#editrestrictuserproduct').length && !$(event.target).closest('.restrict-tooltipuser').length) {
            // Hide all tooltips
            $('.restrict-tooltipuser').css('display', 'none').css('opacity', '0');
        }
    });

    let usercheckboxesVisible = false;

    // Function to toggle checkboxes visibility
    function usertoggleCheckboxesVisibility(visible) {
        if (visible) {
            $('.restrictuser-prod-checkbox-item-container').show();
            $('.restrictuser-checkbox').show();
        } else {
            $('.restrictuser-prod-checkbox-item-container').hide();
            $('.restrictuser-checkbox').hide();
        }
    }

    // Delete User product
    $("#deleterestrictuserproduct").on('click', function() {
        var artNoToDelete = $(this).data('artno');
        var roleIdToDelete = selectedRoleId;
        var rolenameToDelete = $(this).data('rolename');
        console.log('rolenameToDelete:', rolenameToDelete);
        console.log('artNoToDelete:', artNoToDelete);
        console.log('roleIdToDelete:', roleIdToDelete);

        if(artNoToDelete)
        {
            // Show the modal
            $('#custom-uam-alert-msg').html(
                'Are you sure you want to remove<br>' +
                'Article <span style="color:#000000;">\'</span><span style="color:#D5352C;">' + artNoToDelete + '</span><span style="color:#000000;">\'</span> from Role <span style="color:#000000;">\"</span><span style="color:#D5352C;">' + rolenameToDelete + '</span><span style="color:#000000;">\"</span>?<br><br>' +
                '<span style="color: #75757E; font-weight: normal;">You can\'t undo this action.</span><br>'
            );
            $('#custom-uam-alert-delete-dlg').show();
            $('#deleteover').show();
            // Store the artNo and roleId in the confirm button for later use
            $('#confirm-remove-btn').data('artno', artNoToDelete);
            $('#confirm-remove-btn').data('roleid', roleIdToDelete);
        }else{
            // Check if there are any remaining items for the role
            var remainingItems = $('li[data-roleid="' + roleIdToDelete + '"]').length;

            if (remainingItems > 0) {
                // Toggle visibility of select all checkbox
                usertoggleCheckboxesVisibility(!usercheckboxesVisible);
                usercheckboxesVisible = !usercheckboxesVisible;
            } else {
                // Hide checkboxes if no items
                usertoggleCheckboxesVisibility(false);
                usercheckboxesVisible = false;
            }
            var selectedArtNos = [];
            var resselectAllCheckboxuser = $('#restrictuser_select_all');
            var resproductCheckboxesuser = $('.restrictuser-checkbox');
            var productCheckboxesContaineruser = $('.restrictuser-prod-checkbox-item-container');

            resselectAllCheckboxuser.on('change', function() {
                resproductCheckboxesuser.prop('checked', this.checked);
            });

            resproductCheckboxesuser.each(function() {
                if ($(this).is(':checked')) {
                    selectedArtNos.push($(this).closest('.check-item').data('artno'));
                }
            });

            console.log('selectedArtNumbers:', selectedArtNos);
            console.log('Role ID to Delete:', roleIdToDelete);

            $('#confirm-remove-btn').data('artnos', selectedArtNos.join(',')); // Store the selected artnos in confirm button
            $('#confirm-remove-btn').data('roleid', roleIdToDelete);

            // Show the modal for multiple deletion only if checkboxes are checked
            if (selectedArtNos.length > 0) {
                $('#custom-uam-alert-msg').html(
                    'Are you sure you want to proceed with<br>' +
                    'removing the selected products?<br><br>' +
                    '<span style="color: #75757E; font-weight: normal;">You can\'t undo this action.</span><br>'
                );

                $('#custom-uam-alert-delete-dlg').show();
                $('#deleteover').show();
            } else {
                // If no checkboxes are checked, hide the modal and its overlay
                $('#custom-uam-alert-delete-dlg').hide();
                $('#deleteover').hide();
            }
        }
    });

    //Roles function
    function custom_uam_update_restrictcapabilities(e) {
        console.log("called");
        var id = $(e).attr('data-id');
        var rolename = $(e).attr('data-role');
        console.log('id:', id);
    
        // Store the selected role ID
        selectedRoleId = id;
        selectedRolename = rolename;
        $(".custom_restrict_ls li").removeClass('active');
        $(e).addClass('active');
    
        // Clear previous data
        $("#c_uam_caprole_ul").empty();
        // Clear search input
        $('#roleproduct-search').val('');
        $('#no-results').hide();

        // Disable the search input field and change cursor to 'not-allowed'
        $('#roleproduct-search').prop('disabled', true).css('cursor', 'not-allowed');

        // Show the loading spinner
        $('#loaderhid').show();
        // Uncheck and hide the select all checkbox
        $('#restrict_select_all').prop('checked', false);
        $('.restrict-prod-checkbox-item-container').hide();
        // Clear delete button data attributes
        $('#deleterestrictproduct').removeData('artno');
        $('#deleterestrictproduct').removeData('roleid');
        $('#deleterestrictproduct').removeData('rolename');
        // Fetch data via AJAX
        $.post(ajaxurl, { action: 'custom_uam_get_role_data', id: id }, function(response) {
            if (response.success) {
                var data = response.data;
                var listHtml = '';
    
                if (data.length > 0) {
                    var ajaxCalls = [];
    
                    $.each(data, function(index, value) {
                        var productTitleAjax = $.post(ajaxurl, { action: 'get_product_title_by_sku', sku: value.art_no });
                        var editUrlAjax = $.post(ajaxurl, { action: 'get_edit_url_by_sku', sku: value.art_no });
    
                        ajaxCalls.push(
                            $.when(productTitleAjax, editUrlAjax).done(function(productResponse, editUrlResponse) {
                                if (productResponse[0].success && editUrlResponse[0].success) {
                                    var productTitle = productResponse[0].data.title;
                                    var editUrl = editUrlResponse[0].data.edit_url;
                                    listHtml += '<div class="check-item" style="display: flex; align-items: center;" data-artno="' + value.art_no + '" data-roleid="' + selectedRoleId + '" data-editurl="' + editUrl + '" data-rolename="' + rolename + '">';
                                    listHtml += '<input type="checkbox" class="restrict-checkbox" style="margin-right: 10px; display:none;">'; // Checkbox outside the <li>
                                    listHtml += '<li class="list-item" style="width:100%;background-color: rgb(255, 255, 255); padding: 10px; margin: 10px 5px; border-radius: 4px; color: black; list-style: none; display: flex; flex-direction: column;" data-artno="' + value.art_no + '" data-roleid="' + selectedRoleId + '" data-editurl="' + editUrl + '" data-rolename="' + rolename + '">';
                                    listHtml += '<div style="padding: 4px; border-radius: 4px;">';
                                    listHtml += '<div style="display: flex; align-items: center;">';
                                    listHtml += '<span style="flex: 0.5; font-weight: bold; text-align: left;">Article Number</span>';
                                    listHtml += '<span style="flex: 0 0 10px; text-align: center;">:</span>';
                                    listHtml += '<span style="flex: 2; text-align: left; margin-left: 10px;">' + value.art_no + '</span>';
                                    listHtml += '</div>';
                                    listHtml += '<div style="display: flex; align-items: flex-start; margin-top: 5px;">';
                                    listHtml += '<span style="flex: 0.5; font-weight: bold; text-align: left;">Product Name</span>';
                                    listHtml += '<span style="flex: 0 0 10px; text-align: center; ">:</span>';
                                    listHtml += '<span style="flex: 2; text-align: left; margin-left: 10px;">' + productTitle + '</span>';
                                    listHtml += '</li>';
                                    listHtml += '</div>';
                                } else {
                                    console.error(productResponse[0].data || editUrlResponse[0].data);
                                }
                            })
                        );
                    });
    
                    $.when.apply($, ajaxCalls).always(function() {
                        $("#c_uam_caprole_ul").html(listHtml);
                        // Hide the loading spinner after all AJAX calls are completed
                        $('#loaderhid').hide();
                        $('#roleproduct-search').prop('disabled', false).css('cursor', 'auto');
                    });
                } else {
                    listHtml += '<li style="background-color: rgb(255, 255, 255); padding: 10px; margin: 2px 5px; border-radius: 4px;">';
                    listHtml += '<span style="color: #000000; font-weight: medium;">No products found.</span>';
                    listHtml += '</li>';
                    $("#c_uam_caprole_ul").html(listHtml);
                    // Hide the loading spinner
                    $('#loaderhid').hide();
                    $('#roleproduct-search').prop('disabled', false).css('cursor', 'auto');
                }
            } else {
                $("#c_uam_caprole_ul").html('<li style="background-color: rgb(255, 255, 255); padding: 10px; margin: 2px 5px; border-radius: 4px;"><span style="color: #000000; font-weight: medium;">No products found.</span></li>');
                // Hide the loading spinner
                $('#loaderhid').hide();
                $('#roleproduct-search').prop('disabled', false).css('cursor', 'auto');
            }
        }, 'json');
    }
    // Update the event handler for individual checkboxes
    $(document).on('change', '.restrict-checkbox', function() {
        var allChecked = true;
        $('.restrict-checkbox').each(function() {
            if (!$(this).prop('checked')) {
                allChecked = false;
                return false; // Exit each loop early if any checkbox is unchecked
            }
        });

        // Update the state of Select All checkbox
        $('#restrict_select_all').prop('checked', allChecked);
    });

    // Update the Select All checkbox change handler
    $('#restrict_select_all').on('change', function() {
        var isChecked = $(this).prop('checked');
        $('.restrict-checkbox').prop('checked', isChecked);
    });
    // Event delegation for dynamically created list items
    $("#c_uam_caprole_ul").on('click', '.list-item', function() {
        var isSelected = $(this).css('background-color') === 'rgb(34, 113, 177)' && $(this).css('color') === 'rgb(255, 255, 255)';

        // Reset background and text color of all list items
        $(".list-item").css({
            'background-color': 'white',
            'color': 'black'
        });

        // If the item was not already selected, set it as selected
        if (!isSelected) {
            console.log('1');
            $(this).css({
                'background-color': '#2271B1',
                'color': 'white'
            });

            // Store the edit URL in the edit button
            $('#editrestrictproduct').data('editurl', $(this).data('editurl'));
            $('#editrestrictproduct').css('color', '#ffffff');
        
            $('#deleterestrictproduct').data('artno', $(this).data('artno'));
            $('#deleterestrictproduct').data('roleid', $(this).data('roleid'));
            $('#deleterestrictproduct').data('rolename', $(this).data('rolename'));
        }else {
            // If the item is deselected, clear the data attributes of edit and delete buttons
            $('#editrestrictproduct').removeData('editurl');
            $('#editrestrictproduct').css('color', 'rgb(156, 163, 175)');
            
            $('#deleterestrictproduct').removeData('artno');
            $('#deleterestrictproduct').removeData('roleid');
            $('#deleterestrictproduct').removeData('rolename');
        }
    });

    let checkboxesVisible = false;

    // Function to toggle checkboxes visibility
    function toggleCheckboxesVisibility(visible) {
        if (visible) {
            $('.restrict-prod-checkbox-item-container').show();
            $('.restrict-checkbox').show();
        } else {
            $('.restrict-prod-checkbox-item-container').hide();
            $('.restrict-checkbox').hide();
        }
    }

    // Delete Roles product
    $("#deleterestrictproduct").on('click', function() {
        var artNoToDelete = $(this).data('artno');
        var roleIdToDelete = selectedRoleId;
        var rolenameToDelete = $(this).data('rolename');
        console.log('rolenameToDelete:', rolenameToDelete);

        if (artNoToDelete) {
            // Show the modal for single deletion
            $('#custom-uam-alert-msg').html(
                'Are you sure you want to remove<br>' +
                'Article <span style="color:#000000;">\'</span><span style="color:#D5352C;">' + artNoToDelete + '</span><span style="color:#000000;">\'</span> from Role <span style="color:#000000;">\"</span><span style="color:#D5352C;">' + rolenameToDelete + '</span><span style="color:#000000;">\"</span>?<br><br>' +
                '<span style="color: #75757E; font-weight: normal;">You can\'t undo this action.</span><br>'
            );
            $('#custom-uam-alert-delete-dlg').show();
            $('#deleteover').show();

            // Store the artNo and roleId in the confirm button for later use
            $('#confirm-remove-btn').data('artno', artNoToDelete);
            $('#confirm-remove-btn').data('roleid', roleIdToDelete);

        } else {
            // Check if there are any remaining items for the role
            var remainingItems = $('li[data-roleid="' + roleIdToDelete + '"]').length;

            if (remainingItems > 0) {
                // Toggle visibility of select all checkbox
                toggleCheckboxesVisibility(!checkboxesVisible);
                checkboxesVisible = !checkboxesVisible;
            } else {
                // Hide checkboxes if no items
                toggleCheckboxesVisibility(false);
                checkboxesVisible = false;
            }

            var selectedArtNos = [];
            var resselectAllCheckbox = $('#restrict_select_all');
            var resproductCheckboxes = $('.restrict-checkbox');
            var productCheckboxesContainer = $('.restrict-prod-checkbox-item-container');

            resselectAllCheckbox.on('change', function() {
                resproductCheckboxes.prop('checked', this.checked);
            });

            resproductCheckboxes.each(function() {
                if ($(this).is(':checked')) {
                    selectedArtNos.push($(this).closest('.check-item').data('artno'));
                }
            });

            console.log('selectedArtNumbers:', selectedArtNos);
            console.log('Role ID to Delete:', roleIdToDelete);

            $('#confirm-remove-btn').data('artnos', selectedArtNos.join(',')); // Store the selected artnos in confirm button
            $('#confirm-remove-btn').data('roleid', roleIdToDelete);

            // Show the modal for multiple deletion only if checkboxes are checked
            if (selectedArtNos.length > 0) {
                $('#custom-uam-alert-msg').html(
                    'Are you sure you want to proceed with<br>' +
                    'removing the selected products?<br><br>' +
                    '<span style="color: #75757E; font-weight: normal;">You can\'t undo this action.</span><br>'
                );

                $('#custom-uam-alert-delete-dlg').show();
                $('#deleteover').show();
            } else {
                // If no checkboxes are checked, hide the modal and its overlay
                $('#custom-uam-alert-delete-dlg').hide();
                $('#deleteover').hide();
            }
        }
    });

    $('#confirm-remove-btn').on('click', function() {
        var artNoToDelete = $(this).data('artno');
        var multipleartNosToDelete = $(this).data('artnos') ? $(this).data('artnos').split(',') : null;
        var roleIdToDelete = $(this).data('roleid');
    
        if (artNoToDelete) {
            // Single article deletion
            checkStackPrice_Before_delete(artNoToDelete, roleIdToDelete);
        }  
         if (multipleartNosToDelete) {
            // Multiple articles deletion
            deleteMultipleArticles(multipleartNosToDelete, roleIdToDelete);
        }

        // Clear the data attributes after the operation
        $(this).removeData('artno').removeData('multiartnos').removeData('roleid');

            // Hide the modal
        $('#custom-uam-alert-delete-dlg').hide();
        $('#deleteover').hide();
    });    
    function checkStackPrice_Before_delete(artNoToDelete, roleIdToDelete) {
        var artNoToDelete =artNoToDelete;
        var multipleartNosToDelete = $(this).data('artnos') ? $(this).data('artnos').split(',') : null;
        var roleIdToDelete = roleIdToDelete;
       $.post(ajaxurl, {
           action: 'checkStackPrice_Before_delete',
           artNo: artNoToDelete,
           roleId: roleIdToDelete
       }, function(response) {
           console.log(response);
           if (response.success) {
               if (response.data.length!=0) {
                   deleteArticle(artNoToDelete,roleIdToDelete);
                   alert(response.data[0]);
                   //alert('This user is removed from one of the stack pricing rule for this product as this product is still restricted for aother users');
               }else{
                  deleteArticle(artNoToDelete,roleIdToDelete);
               }
           }
       });
   }
    
    function deleteArticle(artNoToDelete, roleIdToDelete) {
        // AJAX request to delete single article
        $.post(ajaxurl, {
            action: 'custom_uam_delete_artno',
            artNo: artNoToDelete,
            roleId: roleIdToDelete
        }, function(deleteResponse) {
            if (deleteResponse.success) {
                console.log('Art No deleted successfully:', artNoToDelete);
                // Remove the deleted item from UI            
                $('div[data-artno="' + artNoToDelete + '"][data-roleid="' + roleIdToDelete + '"]').remove();
                // Check if there are any remaining articles for the role
                var remainingItems = $('li[data-roleid="' + roleIdToDelete + '"]').length;
                console.log('Remaining items for role ID ' + roleIdToDelete + ':', remainingItems);
    
                if (remainingItems == 0) {
                    var noArticlesHtml = '<li style="background-color: rgb(255, 255, 255);padding: 10px;margin: 2px 5px;border-radius: 4px;">';
                    noArticlesHtml += '<span style="color: #000000; font-weight: medium;">No products found.</span>';
                    noArticlesHtml += '</li>';
                    $("#c_uam_caprole_ul").html(noArticlesHtml);
                    $("#c_uam_capuser_ul").html(noArticlesHtml);
                    $('.restrict-prod-checkbox-item-container').hide();
                    $('.restrictuser-prod-checkbox-item-container').hide();
                }
                $('#deleterestrictproduct').removeData('artno roleid rolename');
                $('#deleterestrictuserproduct').removeData('artno roleid rolename');
                $('#editrestrictproduct').removeData('editurl');
                $('#editrestrictproduct').css('color', 'rgb(156, 163, 175)');  
                $('#editrestrictuserproduct').removeData('editurl');
                $('#editrestrictuserproduct').css('color', 'rgb(156, 163, 175)');      

            } else {
                console.error('Failed to delete Art No:', deleteResponse.data);
            }
        }, 'json').fail(function(error) {
            console.error('AJAX Error:', error.statusText);
        });
    }
    
    function deleteMultipleArticles(multipleartNosToDelete, roleIdToDelete) {
       //console.log('Deleting articles:', multipleartNosToDelete, 'for Role ID:', roleIdToDelete);
        
        // AJAX request to delete multiple articles
        $.post(ajaxurl, {
            action: 'custom_uam_delete_multipleartno',
            multipleartNosToDelete: multipleartNosToDelete.join(','), // Convert array to comma-separated string
            roleId: roleIdToDelete
        }, function(deleteResponse) {
            if (deleteResponse.success) {
                console.log('Art Nos deleted successfully:', multipleartNosToDelete);
                
                // Remove the deleted items from UI
                multipleartNosToDelete.forEach(function(artNo) {
                    $('div[data-artno="' + artNo + '"][data-roleid="' + roleIdToDelete + '"]').remove();
                });
                
                // Check if there are any remaining articles for the role
                var remainingItems = $('li[data-roleid="' + roleIdToDelete + '"]').length;
                console.log('Remaining items for role ID ' + roleIdToDelete + ':', remainingItems);
    
                if (remainingItems == 0) {
                    var noArticlesHtml = '<li style="background-color: rgb(255, 255, 255);padding: 10px;margin: 2px 5px;border-radius: 4px;">';
                    noArticlesHtml += '<span style="color: #000000; font-weight: medium;">No products found.</span>';
                    noArticlesHtml += '</li>';
                    $("#c_uam_caprole_ul").html(noArticlesHtml);
                    $("#c_uam_capuser_ul").html(noArticlesHtml);
                }
                $('.restrict-prod-checkbox-item-container').hide();
                $('.restrictuser-prod-checkbox-item-container').hide();
                checkboxesVisible = false;
                usercheckboxesVisible = false;
                $('#deleterestrictproduct').removeData('artno roleid rolename');
                $('#deleterestrictuserproduct').removeData('artno roleid rolename');

            } else {
                console.error('Failed to delete Art Nos:', deleteResponse.data);
            }
        }, 'json').fail(function(error) {
            console.error('AJAX Error:', error.statusText);
        });
    }
    
    // Handle the Cancel button click
    $('#cancel-remove-btn').on('click', function() {
        // Hide the modal
        $('#custom-uam-alert-delete-dlg').hide();
        $('#deleteover').hide();
        // Uncheck all checkboxes
        $('.restrict-checkbox').prop('checked', false);
        $('#restrict_select_all').prop('checked', false);
        $('.restrictuser-checkbox').prop('checked', false);
        $('#restrictuser_select_all').prop('checked', false);
    });
    $('#deleterestrictproduct').prop('disabled', true);
    $('#deleterestrictuserproduct').prop('disabled', true);


     // Edit Roles product
     $('#editrestrictproduct').on('click', function(event) {
        event.stopPropagation(); // Prevent propagation to document click handler
        var editUrl = $(this).data('editurl');
        console.log(editUrl);

        if (editUrl) {
            // Open edit URL in new tab
            window.open(editUrl, '_blank');
        } else {
            // Show tooltip if edit URL is not available
            var tooltip = $(this).find('.restrict-tooltip');
            tooltip.css('display', 'block').css('opacity', '1');
        }
    });

    // Handle click on document to hide tooltip when clicking outside
    $(document).on('click', function(event) {
        // Check if the clicked element is not the edit button or the tooltip itself
        if (!$(event.target).closest('#editrestrictproduct').length && !$(event.target).closest('.restrict-tooltip').length) {
            // Hide all tooltips
            $('.restrict-tooltip').css('display', 'none').css('opacity', '0');
        }
    });
    
    // Function to clear input value and search results
    function clearSearchData() {
        $('#custom-uam-input-restrict-artno').val(''); // Clear input field
        $('#restrict-search-results').hide(); // Hide results container
        $('#restrict-search-results .art-search-results').empty(); // Clear search results
    }

    // Event listener for dialog close
    $(document).on('tb_unload', function() {
        clearSearchData(); // Clear search data when dialog is closed
        $("#entered-artnos").empty(); // Empty the entered-artnos list
        $("#all-artnos").val('');
        artNos = []; // Clear the artNos array
    });

   // Initialize the search functionality
    $('#custom-uam-input-restrict-artno').on('input', function () {
        var searchTerm = $(this).val().trim();
        var $resultsContainer = $('#restrict-search-results .art-search-results');
        var remainingChars = 3 - searchTerm.length;
        
        if (searchTerm === "") {
            // If search term is empty, hide the results container and return
            $('#restrict-search-results').hide();
            $resultsContainer.html('<ul class="chosen-results"><li class="no-results">Please enter 3 or more characters</li></ul>'); // Initial message
            return;
        } else if (remainingChars > 0) {
            // If not enough characters entered, show a message with the remaining count
            $resultsContainer.html('<ul class="chosen-results"><li class="no-results">Please enter ' + remainingChars + ' or more characters</li></ul>');
            $('#restrict-search-results').show();
            return;
        }

        console.log('searchTerm:', searchTerm);
        
        $('#restrict-search-results').show(); // Show the results container
        
        $(document).on('click', function (event) {
            if (!$resultsContainer.is(event.target) && $resultsContainer.has(event.target).length === 0) {
                $('#restrict-search-results').hide(); // Hide the results container if clicked outside
            }
        });
    
        // Show loading spinner or indicator
        $resultsContainer.html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
    
        // AJAX request
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'restrict_get_product_matches_skus',
                search_query: searchTerm,
            },
            success: function (response) {
                $resultsContainer.empty();
                if (response.length) {
                    response.forEach(function (artno) {
                        $resultsContainer.append(
                            '<div style="padding-right: 1.25rem; padding-bottom: 5px; padding-top: 5px; padding-left: 10px; cursor: pointer;" class="art-search-item" data-artno="' + artno + '">' +
                            artno +
                            '</div>'
                        );
                    });
                    $('#restrict-search-results').show(); // Show the results container after appending results
                } else {
                    $resultsContainer.html('<div style="padding-right: 1.25rem; padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">No results found</div>');
                    $('#restrict-search-results').show(); // Show the results container even if no results
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.error('Error: ' + errorThrown);
                $resultsContainer.html('<div style="padding-right: 1.25rem; padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">Error fetching results</div>');
                $('#restrict-search-results').show(); // Show the results container even if error
            },
            complete: function () {
                $resultsContainer.find('.loading-spinner').remove(); // Remove spinner if added
            }
        });
    });

    $(document).on('click', '#restrict-search-results .art-search-item', function () {
        var selectedArtNo = $(this).data('artno');
        console.log('Selected Art No:', selectedArtNo);
    
        // Do something with the selected item
        // For example, you can set the input value to the selected Art No
        $('#custom-uam-input-restrict-artno').val(selectedArtNo);
    
        // Hide the results container
        $('#restrict-search-results').hide();
    });


    const addArtNoBtn = document.getElementById('add-artno-btn');
    const artNoInput = document.getElementById('custom-uam-input-restrict-artno');
    const enteredArtNosContainer = document.getElementById('entered-artnos');
    const allArtNosInput = document.getElementById('all-artnos');
    const saveButton = document.getElementById('submit');
    const form = document.getElementById('custom_uam_save_restrict_artno');
    let artNos = [];

    function updateHiddenInput() {
        allArtNosInput.value = JSON.stringify(artNos);
    }

    function disableAddButton() {
        addArtNoBtn.disabled = true;
        addArtNoBtn.style.setProperty('background-color', '#2271b1', 'important'); // Set the background color to a disabled state with !important
        addArtNoBtn.style.setProperty('color', 'white', 'important'); // Change the cursor to indicate the button is disabled with !important
        addArtNoBtn.style.setProperty('border-color', '#2271b1', 'important'); // Change the cursor to indicate the button is disabled with !important
    }

    function enableAddButton() {
        addArtNoBtn.disabled = false;
        addArtNoBtn.style.backgroundColor = ''; // Restore the original background color
        addArtNoBtn.style.cursor = ''; // Restore the original cursor
    }

    if (addArtNoBtn) {
        addArtNoBtn.addEventListener('click', function () {
            const artNo = artNoInput.value.trim();
            const roleId = selectedRoleId; // Get the stored role ID
        var activeTabtype = $(".navdd-tab-active").attr('id'); // Get the active tab ID
        var selectedRoleElement = document.querySelector('.custom-uam-restrictproduct-li.active');
        var roleName = selectedRoleElement ? selectedRoleElement.getAttribute('data-role') : 'Role';
        var actype = activeTabtype === 'roles-tab' ? 'role' : 'user'; // Determine the type

        // console.log('actype', actype);
        //     console.log('artNo', artNo);
        //     console.log('roleId', roleId);

            if (artNo && roleId) {
                if (artNos.includes(artNo)) {
                    alert('This Article Number has already been added.');
                    artNoInput.value = ''; // Clear input field
                    return;
                }

                // Disable the add button to prevent multiple clicks
                disableAddButton();

            // First AJAX request to check if the Art No, Role ID, and Type combination exists
            const checkSameArtNoData = {
                action: 'custom_uam_checksame_artno_exists',
                artNo: artNo,
                roleId: roleId,
                type: actype // Include the type
            };

            jQuery.post(ajaxurl, checkSameArtNoData, function (response) {
                if (response.success) {
                    if (response.data.exists) {
                        // Show alert if the Art No already exists with the given Role ID and Type
                        const alertMessage = `This Article Number is already restricted to the "${roleName}" role. You might want to modify or remove the existing restriction before adding this Article Number.`;
                        alert(alertMessage);
                        artNoInput.value = ''; // Clear input field
                        enableAddButton(); // Re-enable the button
                    } else {
                        // Second AJAX request to check if the Art No and Role ID combination exists
                        const checkArtNoData = {
                    action: 'custom_uam_check_artno_exists',
                    artNo: artNo,
                    roleId: roleId
                };

                        jQuery.post(ajaxurl, checkArtNoData, function (response) {
                    if (response.success) {
                                if (response.data.exists) {
                                    // Update the confirmation message to include the roleId
                                    const confirmationMessage = `This product is already assigned to "${roleName}" role. Continuing will restrict this product to “${roleId}” only, making it inaccessible to other "${roleName}" users.`;
                                    
                                    if (confirm(confirmationMessage)) {
                                        addArtNo(artNo);
                                    } else {
                                        artNoInput.value = ''; // Clear input field
                                    }
                                } else {
                                    addArtNo(artNo);
                                }
                            } else {
                                console.error('Error checking Art No:', response.data);
                            }
                            enableAddButton();
                        }).fail(function (error) {
                            console.error('AJAX Error checking Art No:', error.statusText);
                            enableAddButton();
                        });
                    }
                } else {
                    console.error('Error checking Art No with Role ID and Type:', response.data);
                    enableAddButton();
                }
            }).fail(function (error) {
                console.error('AJAX Error checking Art No with Role ID and Type:', error.statusText);
                enableAddButton();
            });
        }
    });
}

function addArtNo(artNo) {
    const getProductData = {
        action: 'custom_uam_get_product_title_by_sku',
        artNo: artNo
    };

    jQuery.post(ajaxurl, getProductData, function (productResponse) {
        if (productResponse.success) {
            const productTitle = productResponse.data.title;
            if (productTitle) {
                // Create HTML elements to display the entered Art No and product title
                const divWrapper = document.createElement('div');
                divWrapper.style.background = '#e6e5e5'; // Background color for the wrapper div
                divWrapper.style.padding = '4px';
                divWrapper.style.borderRadius = '4px';
                divWrapper.style.marginBottom = '10px'; // Bottom margin for spacing

                const listItem = document.createElement('li');
                listItem.style.backgroundColor = '#FFFFFF';
                listItem.style.padding = '10px';
                listItem.style.margin = '5px 0';
                listItem.style.borderRadius = '4px';

                const artNoContainer = document.createElement('div');
                artNoContainer.style.display = 'flex';
                artNoContainer.style.alignItems = 'center';

                const artNameContainer = document.createElement('div');
                artNameContainer.style.display = 'flex';
                artNameContainer.style.alignItems = 'flex-start';
                artNameContainer.style.marginTop = '5px'; // Margin for spacing between Art No and Art Name

                const artNoLabel = document.createElement('span');
                artNoLabel.textContent = 'Article Number';
                artNoLabel.style.flex = '1';
                artNoLabel.style.fontWeight = 'bold';
                artNoLabel.style.textAlign = 'left';

                const artNoColon = document.createElement('span');
                artNoColon.textContent = ':';
                artNoColon.style.flex = '0 0 10px';
                artNoColon.style.textAlign = 'center';
                artNoColon.style.marginLeft = '-65px';

                const artNoValue = document.createElement('span');
                artNoValue.textContent = artNo;
                artNoValue.style.flex = '2';
                artNoValue.style.textAlign = 'left';
                artNoValue.style.marginLeft = '10px';

                const artNameLabel = document.createElement('span');
                artNameLabel.textContent = 'Product Name';
                artNameLabel.style.flex = '1';
                artNameLabel.style.fontWeight = 'bold';
                artNameLabel.style.textAlign = 'left';

                const artNameColon = document.createElement('span');
                artNameColon.textContent = ':';
                artNameColon.style.flex = '0 0 10px';
                artNameColon.style.textAlign = 'center';
                artNameColon.style.marginLeft = '-50px';

                const artNameValue = document.createElement('span');
                artNameValue.textContent = productTitle;
                artNameValue.style.flex = '2';
                artNameValue.style.textAlign = 'left';
                artNameValue.style.marginLeft = '10px';

                const deleteIcon = document.createElement('img');
                deleteIcon.src = 'https://smartstoring.eu/wp-content/plugins/thingsatweb/img/close-circle.png';
                deleteIcon.alt = 'Delete';
                deleteIcon.style.cursor = 'pointer';
                deleteIcon.style.marginLeft = '10px';
                deleteIcon.style.width = '20px';
                deleteIcon.style.height = '20px';

                deleteIcon.addEventListener('click', function () {
                    divWrapper.remove();
                    const index = artNos.indexOf(artNo);
                    if (index !== -1) {
                        artNos.splice(index, 1);
                    }
                    updateHiddenInput();
                });

                artNoContainer.appendChild(artNoLabel);
                artNoContainer.appendChild(artNoColon);
                artNoContainer.appendChild(artNoValue);

                artNameContainer.appendChild(artNameLabel);
                artNameContainer.appendChild(artNameColon);
                artNameContainer.appendChild(artNameValue);
                artNameContainer.appendChild(deleteIcon);

                listItem.appendChild(artNoContainer);
                listItem.appendChild(artNameContainer);

                divWrapper.appendChild(listItem);
                enteredArtNosContainer.appendChild(divWrapper);

                artNos.push(artNo);
                updateHiddenInput();

                artNoInput.value = ''; // Clear input field after successful addition
            } else {
                alert('Art No was not found.');
                artNoInput.value = ''; // Clear input field after showing alert
            }
        } else {
            console.error('Error fetching product title:', productResponse.data);
            alert('Art No was not found');
            artNoInput.value = ''; // Clear input field after showing alert
        }
        // Re-enable the add button
        enableAddButton();
    }).fail(function (error) {
        console.error('AJAX Error fetching product title:', error.statusText);
        alert('AJAX Art No was not found.');
        artNoInput.value = ''; // Clear input field after showing alert
        // Re-enable the add button
        enableAddButton();
    });                        
}
var isSubmitting = false;

// Function to handle form submission

$("#custom_uam_save_restrict_artno").submit(function(e) {
    e.preventDefault();
    var form = $(this);
    var restrictartno = $("#all-artnos").val();

    // Check if artNos array is empty
    if (!restrictartno || restrictartno.trim() === "" || artNos.length === 0) {
        alert('Please add at least one Art No before saving.');
        return;
    }

    // Check if the form is already being submitted
    if (isSubmitting) {
        return false;
    }

    isSubmitting = true; // Set the flag to indicate form submission
    var role_id = selectedRoleId; // Get the stored role ID
var role_name = selectedRolename;
    var activeTab = $(".navdd-tab-active").attr('id'); // Get the active tab ID

    var type = activeTab === 'roles-tab' ? 'role' : 'user'; // Determine the type
//console.log('type', type);

    // Disable the submit button to prevent multiple submissions
    form.find(':submit').prop('disabled', true);
// Show the loading spinner
$('#loaderhid').show();

$.post(form.attr('action'), {
    action: 'custom_uam_save_restrict_artno',
    restrict_artno: restrictartno,
    role_id: role_id,
    type: type
}, function(response) {
    console.log(response);
    let arr = response.data.new_items[0].AlertArray;

    if(arr.length==1){
        alert(arr[0]);
    }
    else if(arr.length>0){
        alert('As this product as some rules in stack price for other users too there is a change in stack pricing please check');
    }

    if (response.error) {
        $("#custom-uam-alert-error-txt").css("display", "block").text(response.data.message);
        isSubmitting = false; // Reset the flag on error
        form.find(':submit').prop('disabled', false); // Re-enable the submit button on error
        $('#loaderhid').hide(); // Hide the loading spinner on error
        } else if (response.success) {
            var newItems = response.data.new_items; // Array of new items

            if (newItems.length > 0) {
                $("#c_uam_caprole_ul li:contains('No products found.')").remove();
                $("#c_uam_capuser_ul li:contains('No products found.')").remove();
                // Clear the entered-artnos list
                
                // Process each new item asynchronously
                newItems.forEach(function(newItem) {
                    // Fetch product title
                    $.post(ajaxurl, { action: 'get_product_title_by_sku', sku: newItem.art_no }, function(productResponse) {
                        if (productResponse.success) {
                            var productTitle = productResponse.data.title;
                    
                            // Fetch edit URL
                            $.post(ajaxurl, { action: 'get_edit_url_by_sku', sku: newItem.art_no }, function(editUrlResponse) {
                                if (editUrlResponse.success) {
                                    tb_remove(); 
                                var editUrl = editUrlResponse.data.edit_url;

                            if(type='role'){
                                var listItemHtml = '<div class="check-item" style="display: flex; align-items: center;" data-artno="' + newItem.art_no + '" data-roleid="' + role_id + '" data-editurl="' + editUrl + '" data-rolename="' + role_name + '">';
                                listItemHtml += '<input type="checkbox" class="restrict-checkbox" style="margin-right: 10px; display:none;">'; // Checkbox outside the <li>
                                listItemHtml += '<li class="list-item" style="width:100%;background-color: rgb(255, 255, 255); padding: 10px; margin: 10px 5px; border-radius: 4px; color: black; list-style: none; display: flex; flex-direction: column;" data-artno="' + newItem.art_no + '" data-roleid="' + role_id + '" data-editurl="' + editUrl + '" data-rolename="' + role_name + '">';
                                listItemHtml += '<div style="padding: 4px; border-radius: 4px;">';
                                listItemHtml += '<div style="display: flex; align-items: center;">';
                                listItemHtml += '<span style="flex: 0.5; font-weight: bold; text-align: left;">Article Number</span>';
                                listItemHtml += '<span style="flex: 0 0 10px; text-align: center;">:</span>';
                                listItemHtml += '<span style="flex: 2; text-align: left; margin-left: 10px;">' + newItem.art_no + '</span>';
                                listItemHtml += '</div>';
                                listItemHtml += '<div style="display: flex; align-items: flex-start; margin-top: 5px;">';
                                listItemHtml += '<span style="flex: 0.5; font-weight: bold; text-align: left;">Product Name</span>';
                                listItemHtml += '<span style="flex: 0 0 10px; text-align: center; ">:</span>';
                                listItemHtml += '<span style="flex: 2; text-align: left; margin-left: 10px;">' + productTitle + '</span>';
                                listItemHtml += '</li>';
                                listItemHtml += '</div>';
                                $("#c_uam_caprole_ul").append(listItemHtml);
                            }
                            if(type='user'){

                                var listHtml = '<div class="check-item" style="display: flex; align-items: center;" data-artno="' + newItem.art_no + '" data-roleid="' + role_id + '" data-editurl="' + editUrl + '" data-rolename="' + role_name + '">';
                                    listHtml += '<input type="checkbox" class="restrictuser-checkbox" style="margin-left: 10px; display:none;">'; // Checkbox outside the <li>
                                    listHtml += '<li class="list-item" style="width:100%;background-color: rgb(255, 255, 255); padding: 10px; margin: 10px 15px; border-radius: 4px; color: black; list-style: none; display: flex; flex-direction: column;" data-artno="' + newItem.art_no + '" data-roleid="' + role_id + '" data-editurl="' + editUrl + '" data-rolename="' + role_name + '">';
                                    listHtml += '<div style="padding: 4px; border-radius: 4px;">';
                                    listHtml += '<div style="display: flex; align-items: center;">';
                                    listHtml += '<span style="flex: 0.5; font-weight: bold; text-align: left;">Article Number</span>';
                                    listHtml += '<span style="flex: 0 0 10px; text-align: center;">:</span>';
                                    listHtml += '<span style="flex: 2; text-align: left; margin-left: 10px;">' + newItem.art_no + '</span>';
                                    listHtml += '</div>';
                                    listHtml += '<div style="display: flex; align-items: flex-start; margin-top: 5px;">';
                                    listHtml += '<span style="flex: 0.5; font-weight: bold; text-align: left;">Product Name</span>';
                                    listHtml += '<span style="flex: 0 0 10px; text-align: center; ">:</span>';
                                    listHtml += '<span style="flex: 2; text-align: left; margin-left: 10px;">' + productTitle + '</span>';
                                    listHtml += '</div>';
                                    listHtml += '</li>';
                                    listHtml += '</div>';
                                    $("#c_uam_capuser_ul").append(listHtml);
                                } 

                                // Hide the loading spinner after all AJAX calls are completed
                                $('#loaderhid').hide();
                                isSubmitting = false; // Reset the flag on success
                                form.find(':submit').prop('disabled', false); // Re-enable the submit button on success
                                } else {
                                    console.error('Error fetching edit URL:', editUrlResponse.data);
                                    isSubmitting = false; // Reset the flag on error
                                    form.find(':submit').prop('disabled', false); // Re-enable the submit button on error
                                $('#loaderhid').hide(); // Hide the loading spinner on error
                                }
                            }, 'json');
                        } else {
                            console.error('Error fetching product title:', productResponse.data);
                            isSubmitting = false; // Reset the flag on error
                            form.find(':submit').prop('disabled', false); // Re-enable the submit button on error
                        $('#loaderhid').hide(); // Hide the loading spinner on error
                        }
                    }, 'json');
                });
            } else {
                console.error('No new items found in server response.');
                isSubmitting = false; // Reset the flag if no new items found
                form.find(':submit').prop('disabled', false); // Re-enable the submit button if no new items found
            $('#loaderhid').hide(); // Hide the loading spinner if no new items found
            }
        } 
    }, 'json');
});

























//cat


var selectedRoleIdcategory = null; // Variable to store the selected role ID
var selectedRolenamecategory= null ;
// Roles Tab Click Event

$('#roles-categorytab').on('click', function(e) {
    e.preventDefault();
    $('#roles-categorytab').addClass('navdd-categorytab-active').attr('aria-current', 'page');
    $('#users-categorytab').removeClass('navdd-categorytab-active').removeAttr('aria-current');

    $('#roles-categorycontent').show();
    $('#users-categorycontent').hide();
    $('#right-roles-categorycontent').show();
    $('#right-users-categorycontent').hide();
    $('#user-categorysearch').hide();

    // Trigger click event on the first role list item and mark it as active
    var firstRoleItemcategory = $(".custom_restrictcategory_ls li:first");
    firstRoleItemcategory.addClass('active'); // Add active class
    custom_uam_update_restrictcategorycapabilities(firstRoleItemcategory[0]);
});

// Users Tab Click Event
$('#users-categorytab').on('click', function(e) {
    e.preventDefault();
    $('#users-categorytab').addClass('navdd-categorytab-active').attr('aria-current', 'page');
    $('#roles-categorytab').removeClass('navdd-categorytab-active').removeAttr('aria-current');

    $('#roles-categorycontent').hide();
    $('#users-categorycontent').show();
    $('#right-roles-categorycontent').hide();
    $('#right-users-categorycontent').show();
    $('#user-categorysearch').show();

    // Remove active class from all user list items
    var userItemscategory = $('.custom-uam-restrictcategoryproductuser-li');
    userItemscategory.removeClass('active');

    // Add active class to the first user list item
    if (userItemscategory.length > 0) {
        var firstUserItemcategory = $(".custom_restrictcategoryuser_ls li:first");
        firstUserItemcategory.addClass('active'); // Add active class
        custom_uam_update_restrictcategoryusercapabilities(firstUserItemcategory[0]); // Call the function with the first user item
    }
});

$('#user-categorysearch').on('input', function() {
    var filtercategory = $(this).val().toLowerCase();
    var userItemscategory = $('.custom-uam-restrictcategoryproductuser-li');

    userItemscategory.each(function() {
        var userNamecategory = $(this).find('.custom-uam-restrictcategoryproductuser-li-title span:first-child').text().toLowerCase();
        if (userNamecategory.includes(filtercategory)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
});

// Roles product Search
$('#roleproduct-categorysearch').on('input', function() {
    var searchTerm = $(this).val().toLowerCase();
    console.log('rolesearch:', searchTerm);

    var noResults = true;

    $('#c_uam_caprole_ul_category li').each(function() {
        var artNo = $(this).find('span').eq(2).text().toLowerCase();

        if (artNo.includes(searchTerm)) {
            console.log('rolesearchartNo:', artNo);
            $(this).show();
            noResults = false;
        } else {
            $(this).hide();
           
        }
    });

    if (noResults) {
        $('#no-results-category').show();
    } else {
        $('#no-results-category').hide();
    }
});

// Users Product Search
$('#userproduct-search-category').on('input', function() {
    var searchTerm = $(this).val().toLowerCase();
    //console.log('usersearch:',searchTerm);
    var nouserResults = true;
    $('#c_uam_capuser_ul_category li').each(function() {
        var artNo = $(this).find('span').eq(2).text().toLowerCase();
       
        if (artNo.includes(searchTerm)) {
            //console.log('usersearchartNo:',artNo);
            $(this).show();
            nouserResults = false;
        } else {
            //console.log('hai');
            $(this).hide();
        }
    });
    if (nouserResults) {
        $('#nouser-results-category').show();
    } else {
        $('#nouser-results-category').hide();
    }
});

$('.custom-uam-restrictcategoryproduct-li').on('click', function() {
    // Reset edit button style
    $('#editrestrictproductcategory').css('color', '#9CA3AF');
    $('#editrestrictproductcategory').data('editurl', '');
    $('.restrict-tooltipcategory').css('display', 'block').css('opacity', '1');
   
});

$('.custom-uam-restrictcategoryproductuser-li').on('click', function() {
    // Reset edit button style
    $('#editrestrictuserproductcategory').css('color', '#9CA3AF');
    $('#editrestrictuserproductcategory').data('editurl', '');
    $('.restrict-tooltipcategoryuser').css('display', 'block').css('opacity', '1');
});

// Roles Tab Item Click Event
$("body").on("click", ".custom_restrictcategory_ls .custom-uam-restrictcategoryproduct-li", function(event) {
    // Remove active class from all role items
    $(".custom_restrictcategory_ls .custom-uam-restrictcategoryproduct-li").removeClass("active");

    // Add active class to the clicked role item
    $(this).addClass("active");
    $('.restrictcategory-prod-checkbox-item-container').hide();
    // Update the capabilities based on the clicked role
    custom_uam_update_restrictcategorycapabilities(this);

});

// Trigger click event on the first role list item and mark it as active
var firstRoleItemcategory = $(".custom_restrictcategory_ls li:first");
firstRoleItemcategory.addClass('active'); // Add active class
custom_uam_update_restrictcategorycapabilities(firstRoleItemcategory[0]);

// Users Tab Item Click Event
$("body").on("click", ".custom_restrictcategoryuser_ls .custom-uam-restrictcategoryproductuser-li", function(event) {
    // Remove active class from all user items
    $(".custom_restrictcategoryuser_ls .custom-uam-restrictcategoryproductuser-li").removeClass("active");

    // Add active class to the clicked user item
    $(this).addClass("active");

    // Update the capabilities based on the clicked user
    custom_uam_update_restrictcategoryusercapabilities(this);
});

//When the page loads, ensure the first user item is active if users-categorycontent is visible
$(document).ready(function() {
    if ($('#users-categorycontent').is(':visible')) {
        var firstUserItemcategory = $(".custom_restrictcategoryuser_ls li:first");
        if (firstUserItemcategory.length) {
            firstUserItemcategory.addClass('active'); // Add active class
            custom_uam_update_restrictcategoryusercapabilities(firstUserItemcategory[0]);
        }
    }
});

//Users function
function custom_uam_update_restrictcategoryusercapabilities(e) {
    console.log("called");
    var id = $(e).attr('data-id');
    var rolename = $(e).attr('data-role');
    console.log('id:', id);
    
    // Store the selected role ID
    selectedRoleIdcategory = id;
    selectedRolenamecategory = rolename;
    $(".custom_restrictcategoryuser_ls li").removeClass('active');
    $(e).addClass('active');

    // Clear previous data
    $("#c_uam_capuser_ul_category").empty();
    // Clear search input
    $('#userproduct-search-category').val('');
    $('#nouser-results-category').hide();
    $('#loaderusercategory').show();
    // Disable the search input field and change cursor to 'not-allowed'
    $('#userproduct-search-category').prop('disabled', true).css('cursor', 'not-allowed');
    // Uncheck and hide the select all checkbox
    $('#restrictcategoryuser_select_all').prop('checked', false);
    // Clear delete button data attributes
    $('#deleterestrictcategoryuserproduct').removeData('artno');
    $('#deleterestrictcategoryuserproduct').removeData('roleid');
    $('#deleterestrictcategoryuserproduct').removeData('rolename');
    $.post(ajaxurl, { action: 'custom_uam_get_role_categorydata', id: id }, function(response) {
        if (response.success) {
            var data = response.data;
            var listHtml = '';

            if (data.length > 0) {
                $.each(data, function(index, value) {

                                    listHtml += '<div class="check-item" style="display: flex; align-items: center;" data-artno="' + value.art_no + '" data-roleid="' + selectedRoleIdcategory + '" data-rolename="' + rolename + '">';
                                    listHtml += '<input type="checkbox" class="restrictcategoryuser-checkbox" style="margin-left: 10px; display:none;">'; // Checkbox outside the <li>
                                    listHtml += '<li class="list-item" style="width:100%;background-color: rgb(255, 255, 255); padding: 10px; margin: 10px 15px; border-radius: 4px; color: black; list-style: none; display: flex; flex-direction: column;" data-artno="' + value.art_no + '" data-roleid="' + selectedRoleIdcategory + '"  data-rolename="' + rolename + '">';
                                    listHtml += '<div style="padding: 4px; border-radius: 4px;">';
                                    listHtml += '<div style="display: flex; align-items: center;">';
                                    listHtml += '<span style="flex: 0.5; font-weight: bold; text-align: left;">Category Name</span>';
                                    listHtml += '<span style="flex: 0 0 10px; text-align: center;">:</span>';
                                    listHtml += '<span style="flex: 2; text-align: left; margin-left: 10px;">' + value.art_no + '</span>';
                                    listHtml += '</div>';
                                    listHtml += '</li>';
                                    listHtml += '</div>';
                                    $("#c_uam_capuser_ul_category").html(listHtml);
                                      $('#loaderusercategory').hide();
                                      $('#userproduct-search-category').prop('disabled', false).css('cursor', 'auto');
                });
            } else {
                listHtml += '<li style="background-color: rgb(255, 255, 255);padding: 10px;margin: 10px 15px;border-radius: 4px;">';
                listHtml += '<span style="color: #000000; font-weight: medium;">No products found.</span>';
                listHtml += '</li>';
                $("#c_uam_capuser_ul_category").html(listHtml);
                $('#loaderusercategory').hide();
                $('#userproduct-search-category').prop('disabled', false).css('cursor', 'auto');
            }
        } else {
            $("#c_uam_capuser_ul_category").html('<li style="background-color: rgb(255, 255, 255);padding: 10px;margin: 10px 15px;border-radius: 4px;"><span style="color: #000000; font-weight: medium;">No products found.</span></li>');
            $('#loaderusercategory').hide();
            $('#userproduct-search-category').prop('disabled', false).css('cursor', 'auto');
        }
    }, 'json');
}
// Update the event handler for individual checkboxes
$(document).on('change', '.restrictcategoryuser-checkbox', function() {
    var allChecked = true;
    $('.restrictcategoryuser-checkbox').each(function() {
        if (!$(this).prop('checked')) {
            allChecked = false;
            return false; // Exit each loop early if any checkbox is unchecked
        }
    });

    // Update the state of Select All checkbox
    $('#restrictcategoryuser_select_all').prop('checked', allChecked);
});

// Update the Select All checkbox change handler
$('#restrictcategoryuser_select_all').on('change', function() {
    var isChecked = $(this).prop('checked');
    $('.restrictcategoryuser-checkbox').prop('checked', isChecked);
});
// Event delegation for dynamically created list items
$("#c_uam_capuser_ul_category").on('click', '.list-item', function() {
    var isSelected = $(this).css('background-color') === 'rgb(34, 113, 177)' && $(this).css('color') === 'rgb(255, 255, 255)';

    $(".list-item").css({
        'background-color': 'white',
        'color': 'black'
    }); 
    // If the item was not already selected, set it as selected
    if (!isSelected) {
        console.log('1');
        $(this).css({
            'background-color': '#2271B1',
            'color': 'white'
        });

        // Store the edit URL in the edit button
        $('#editrestrictuserproductcategory').data('editurl', $(this).data('editurl'));
        $('#editrestrictuserproductcategory').css('color', '#ffffff');
    
        $('#deleterestrictcategoryuserproduct').data('artno', $(this).data('artno'));
        $('#deleterestrictcategoryuserproduct').data('roleid', $(this).data('roleid'));
        $('#deleterestrictcategoryuserproduct').data('rolename', $(this).data('rolename'));
    }else {
        // If the item is deselected, clear the data attributes of edit and delete buttons
        $('#editrestrictuserproductcategory').removeData('editurl');
        $('#editrestrictuserproductcategory').css('color', 'rgb(156, 163, 175)');
        
        $('#deleterestrictcategoryuserproduct').removeData('artno');
        $('#deleterestrictcategoryuserproduct').removeData('roleid');
        $('#deleterestrictcategoryuserproduct').removeData('rolename');
    }
});

// Edit User product
$('#editrestrictuserproductcategory').on('click', function(event) {
    event.stopPropagation(); // Prevent propagation to document click handler
    var editUrl = $(this).data('editurl');
    console.log(editUrl);

    if (editUrl) {
        // Open edit URL in new tab
        window.open(editUrl, '_blank');
    } else {
        // Show tooltip if edit URL is not available
        var tooltip = $(this).find('.restrict-tooltipcategoryuser');
        tooltip.css('display', 'block').css('opacity', '1');
    }
});

// Handle click on document to hide tooltip when clicking outside
$(document).on('click', function(event) {
    // Check if the clicked element is not the edit button or the tooltip itself
    if (!$(event.target).closest('#editrestrictuserproductcategory').length && !$(event.target).closest('.restrict-tooltipcategoryuser').length) {
        // Hide all tooltips
        $('.restrict-tooltipcategoryuser').css('display', 'none').css('opacity', '0');
    }
});

let usercategorycheckboxesVisible = false;

// Function to toggle checkboxes visibility
function usertogglecategoryCheckboxesVisibility(visible) {
    if (visible) {
        $('.restrictcategoryuser-prod-checkbox-item-container').show();
        $('.restrictcategoryuser-checkbox').show();
    } else {
        $('.restrictcategoryuser-prod-checkbox-item-container').hide();
        $('.restrictcategoryuser-checkbox').hide();
    }
}

// Delete User product
$("#deleterestrictcategoryuserproduct").on('click', function() {
    var artNoToDelete = $(this).data('artno');
    var roleIdToDelete = selectedRoleIdcategory;
    var rolenameToDelete = $(this).data('rolename');
    console.log('rolenameToDelete:', rolenameToDelete);
    console.log('artNoToDelete:', artNoToDelete);
    console.log('roleIdToDelete:', roleIdToDelete);

    if(artNoToDelete)
    {
        // Show the modal
        $('#custom-uam-alert-msg-category').html(
            'Are you sure you want to remove<br>' +
            'Category <span style="color:#000000;">\'</span><span style="color:#D5352C;">' + artNoToDelete + '</span><span style="color:#000000;">\'</span> from Role <span style="color:#000000;">\"</span><span style="color:#D5352C;">' + rolenameToDelete + '</span><span style="color:#000000;">\"</span>?<br><br>' +
            '<span style="color: #75757E; font-weight: normal;">You can\'t undo this action.</span><br>'
        );
        $('#custom-uam-alert-delete-dlg-category').show();
        $('#deleteovercategory').show();
        // Store the artNo and roleId in the confirm button for later use
        $('#confirm-remove-categorybtn').data('artno', artNoToDelete);
        $('#confirm-remove-categorybtn').data('roleid', roleIdToDelete);
    }else{
        // Check if there are any remaining items for the role
        var remainingItems = $('li[data-roleid="' + roleIdToDelete + '"]').length;

        if (remainingItems > 0) {
            // Toggle visibility of select all checkbox
            usertogglecategoryCheckboxesVisibility(!usercategorycheckboxesVisible);
            usercategorycheckboxesVisible = !usercategorycheckboxesVisible;
        } else {
            // Hide checkboxes if no items
            usertogglecategoryCheckboxesVisibility(false);
            usercategorycheckboxesVisible = false;
        }
        var selectedArtNos = [];
        var resselectAllCheckboxuser = $('#restrictcategoryuser_select_all');
        var resproductCheckboxesuser = $('.restrictcategoryuser-checkbox');
        var productCheckboxesContaineruser = $('.restrictcategoryuser-prod-checkbox-item-container');

        resselectAllCheckboxuser.on('change', function() {
            resproductCheckboxesuser.prop('checked', this.checked);
        });

        resproductCheckboxesuser.each(function() {
            if ($(this).is(':checked')) {
                selectedArtNos.push($(this).closest('.check-item').data('artno'));
            }
        });

        console.log('selectedArtNumbers:', selectedArtNos);
        console.log('Role ID to Delete:', roleIdToDelete);

        $('#confirm-remove-categorybtn').data('artnos', selectedArtNos.join(',')); // Store the selected artnos in confirm button
        $('#confirm-remove-categorybtn').data('roleid', roleIdToDelete);

        // Show the modal for multiple deletion only if checkboxes are checked
        if (selectedArtNos.length > 0) {
            $('#custom-uam-alert-msg-category').html(
                'Are you sure you want to proceed with<br>' +
                'removing the selected products?<br><br>' +
                '<span style="color: #75757E; font-weight: normal;">You can\'t undo this action.</span><br>'
            );

            $('#custom-uam-alert-delete-dlg-category').show();
            $('#deleteovercategory').show();
        } else {
            // If no checkboxes are checked, hide the modal and its overlay
            $('#custom-uam-alert-delete-dlg-category').hide();
            $('#deleteovercategory').hide();
        }
    }
});

//Roles function
function custom_uam_update_restrictcategorycapabilities(e) {
    console.log("called");
    var id = $(e).attr('data-id');
    var rolename = $(e).attr('data-role');
    console.log('id:', id);

    // Store the selected role ID
    selectedRoleIdcategory = id;
    selectedRolenamecategory = rolename;
    $(".custom_restrictcategory_ls li").removeClass('active');
    $(e).addClass('active');

    // Clear previous data
    $("#c_uam_caprole_ul_category").empty();
    // Clear search input
    $('#roleproduct-categorysearch').val('');
    $('#no-results-category').hide();

    // Disable the search input field and change cursor to 'not-allowed'
    $('#roleproduct-categorysearch').prop('disabled', true).css('cursor', 'not-allowed');

    // Show the loading spinner
    $('#loaderhidcategory').show();
    // Uncheck and hide the select all checkbox
    $('#restrictcategory_select_all').prop('checked', false);
    $('.restrictcategory-prod-checkbox-item-container').hide();
    // Clear delete button data attributes
    $('#deleterestrictcategoryproduct').removeData('artno');
    $('#deleterestrictcategoryproduct').removeData('roleid');
    $('#deleterestrictcategoryproduct').removeData('rolename');
    // Fetch data via AJAX
    $.post(ajaxurl, { action: 'custom_uam_get_role_categorydata', id: id }, function(response) {
        if (response.success) {
            var data = response.data;
            var listHtml = '';

            if (data.length > 0) {
                var ajaxCalls = [];

                $.each(data, function(index, value) {
                    // var productTitleAjax = $.post(ajaxurl, { action: 'get_product_title_by_sku', sku: value.art_no });
                    // var editUrlAjax = $.post(ajaxurl, { action: 'get_edit_url_by_sku', sku: value.art_no });

                    // ajaxCalls.push(
                    //     $.when(productTitleAjax, editUrlAjax).done(function(productResponse, editUrlResponse) {
                            // if (productResponse[0].success && editUrlResponse[0].success) {
                                // var productTitle = productResponse[0].data.title;
                                // var editUrl = editUrlResponse[0].data.edit_url;
                                listHtml += '<div class="check-item" style="display: flex; align-items: center;" data-artno="' + value.art_no + '" data-roleid="' + selectedRoleIdcategory + '" data-rolename="' + rolename + '">';
                                listHtml += '<input type="checkbox" class="restrictcategory-checkbox" style="margin-right: 10px; display:none;">'; // Checkbox outside the <li>
                                listHtml += '<li class="list-item" style="width:100%;background-color: rgb(255, 255, 255); padding: 10px; margin: 10px 5px; border-radius: 4px; color: black; list-style: none; display: flex; flex-direction: column;" data-artno="' + value.art_no + '" data-roleid="' + selectedRoleIdcategory + '" data-rolename="' + rolename + '">';
                                listHtml += '<div style="padding: 4px; border-radius: 4px;">';
                                listHtml += '<div style="display: flex; align-items: center;">';
                                listHtml += '<span style="flex: 0.5; font-weight: bold; text-align: left;">Category Name</span>';
                                listHtml += '<span style="flex: 0 0 10px; text-align: center;">:</span>';
                                listHtml += '<span style="flex: 2; text-align: left; margin-left: 10px;">' + value.art_no + '</span>';
                                listHtml += '</div>';
                                // listHtml += '<div style="display: flex; align-items: flex-start; margin-top: 5px;">';
                                // listHtml += '<span style="flex: 0.5; font-weight: bold; text-align: left;">Product Name</span>';
                                // listHtml += '<span style="flex: 0 0 10px; text-align: center; ">:</span>';
                                // listHtml += '<span style="flex: 2; text-align: left; margin-left: 10px;">' + productTitle + '</span>';
                                listHtml += '</li>';
                                listHtml += '</div>';
                            // } else {
                            //     console.error(productResponse[0].data || editUrlResponse[0].data);
                            // }
                        // })
                    // );
                });

                $.when.apply($, ajaxCalls).always(function() {
                    $("#c_uam_caprole_ul_category").html(listHtml);
                    // Hide the loading spinner after all AJAX calls are completed
                    $('#loaderhidcategory').hide();
                    $('#roleproduct-categorysearch').prop('disabled', false).css('cursor', 'auto');
                });
            } else {
                listHtml += '<li style="background-color: rgb(255, 255, 255); padding: 10px; margin: 2px 5px; border-radius: 4px;">';
                listHtml += '<span style="color: #000000; font-weight: medium;">No products found.</span>';
                listHtml += '</li>';
                $("#c_uam_caprole_ul_category").html(listHtml);
                // Hide the loading spinner
                $('#loaderhidcategory').hide();
                $('#roleproduct-categorysearch').prop('disabled', false).css('cursor', 'auto');
            }
        } else {
            $("#c_uam_caprole_ul_category").html('<li style="background-color: rgb(255, 255, 255); padding: 10px; margin: 2px 5px; border-radius: 4px;"><span style="color: #000000; font-weight: medium;">No products found.</span></li>');
            // Hide the loading spinner
            $('#loaderhidcategory').hide();
            $('#roleproduct-categorysearch').prop('disabled', false).css('cursor', 'auto');
        }
    }, 'json');
}
// Update the event handler for individual checkboxes
$(document).on('change', '.restrictcategory-checkbox', function() {
    var allChecked = true;
    $('.restrictcategory-checkbox').each(function() {
        if (!$(this).prop('checked')) {
            allChecked = false;
            return false; // Exit each loop early if any checkbox is unchecked
        }
    });

    // Update the state of Select All checkbox
    $('#restrictcategory_select_all').prop('checked', allChecked);
});

// Update the Select All checkbox change handler
$('#restrictcategory_select_all').on('change', function() {
    var isChecked = $(this).prop('checked');
    $('.restrictcategory-checkbox').prop('checked', isChecked);
});
// Event delegation for dynamically created list items
$("#c_uam_caprole_ul_category").on('click', '.list-item', function() {
    var isSelected = $(this).css('background-color') === 'rgb(34, 113, 177)' && $(this).css('color') === 'rgb(255, 255, 255)';

    // Reset background and text color of all list items
    $(".list-item").css({
        'background-color': 'white',
        'color': 'black'
    });

    // If the item was not already selected, set it as selected
    if (!isSelected) {
        console.log('1');
        $(this).css({
            'background-color': '#2271B1',
            'color': 'white'
        });

        // Store the edit URL in the edit button
        $('#editrestrictproductcategory').data('editurl', $(this).data('editurl'));
        $('#editrestrictproductcategory').css('color', '#ffffff');
    
        $('#deleterestrictcategoryproduct').data('artno', $(this).data('artno'));
        $('#deleterestrictcategoryproduct').data('roleid', $(this).data('roleid'));
        $('#deleterestrictcategoryproduct').data('rolename', $(this).data('rolename'));
    }else {
        // If the item is deselected, clear the data attributes of edit and delete buttons
        $('#editrestrictproductcategory').removeData('editurl');
        $('#editrestrictproductcategory').css('color', 'rgb(156, 163, 175)');
        
        $('#deleterestrictcategoryproduct').removeData('artno');
        $('#deleterestrictcategoryproduct').removeData('roleid');
        $('#deleterestrictcategoryproduct').removeData('rolename');
    }
});

let categorycheckboxesVisible = false;

// Function to toggle checkboxes visibility
function togglecategoryCheckboxesVisibility(visible) {
    if (visible) {
        $('.restrictcategory-prod-checkbox-item-container').show();
        $('.restrictcategory-checkbox').show();
    } else {
        $('.restrictcategory-prod-checkbox-item-container').hide();
        $('.restrictcategory-checkbox').hide();
    }
}

// Delete Roles product
$("#deleterestrictcategoryproduct").on('click', function() {
    var artNoToDelete = $(this).data('artno');
    var roleIdToDelete = selectedRoleIdcategory;
    var rolenameToDelete = $(this).data('rolename');
    console.log('rolenameToDelete:', rolenameToDelete);

    if (artNoToDelete) {
        // Show the modal for single deletion
        $('#custom-uam-alert-msg-category').html(
            'Are you sure you want to remove<br>' +
            'Category <span style="color:#000000;">\'</span><span style="color:#D5352C;">' + artNoToDelete + '</span><span style="color:#000000;">\'</span> from Role <span style="color:#000000;">\"</span><span style="color:#D5352C;">' + rolenameToDelete + '</span><span style="color:#000000;">\"</span>?<br><br>' +
            '<span style="color: #75757E; font-weight: normal;">You can\'t undo this action.</span><br>'
        );
        $('#custom-uam-alert-delete-dlg-category').show();
        $('#deleteovercategory').show();

        // Store the artNo and roleId in the confirm button for later use
        $('#confirm-remove-categorybtn').data('artno', artNoToDelete);
        $('#confirm-remove-categorybtn').data('roleid', roleIdToDelete);

    } else {
        // Check if there are any remaining items for the role
        var remainingItems = $('li[data-roleid="' + roleIdToDelete + '"]').length;

        if (remainingItems > 0) {
            // Toggle visibility of select all checkbox
            togglecategoryCheckboxesVisibility(!categorycheckboxesVisible);
            categorycheckboxesVisible = !categorycheckboxesVisible;
        } else {
            // Hide checkboxes if no items
            togglecategoryCheckboxesVisibility(false);
            categorycheckboxesVisible = false;
        }

        var selectedArtNos = [];
        var resselectAllCheckbox = $('#restrictcategory_select_all');
        var resproductCheckboxes = $('.restrictcategory-checkbox');
        var productCheckboxesContainer = $('.restrictcategory-prod-checkbox-item-container');

        resselectAllCheckbox.on('change', function() {
            resproductCheckboxes.prop('checked', this.checked);
        });

        resproductCheckboxes.each(function() {
            if ($(this).is(':checked')) {
                selectedArtNos.push($(this).closest('.check-item').data('artno'));
            }
        });

        console.log('selectedArtNumbers:', selectedArtNos);
        console.log('Role ID to Delete:', roleIdToDelete);

        $('#confirm-remove-categorybtn').data('artnos', selectedArtNos.join(',')); // Store the selected artnos in confirm button
        $('#confirm-remove-categorybtn').data('roleid', roleIdToDelete);

        // Show the modal for multiple deletion only if checkboxes are checked
        if (selectedArtNos.length > 0) {
            $('#custom-uam-alert-msg-category').html(
                'Are you sure you want to proceed with<br>' +
                'removing the selected products?<br><br>' +
                '<span style="color: #75757E; font-weight: normal;">You can\'t undo this action.</span><br>'
            );

            $('#custom-uam-alert-delete-dlg-category').show();
            $('#deleteovercategory').show();
        } else {
            // If no checkboxes are checked, hide the modal and its overlay
            $('#custom-uam-alert-delete-dlg-category').hide();
            $('#deleteovercategory').hide();
        }
    }
});

$('#confirm-remove-categorybtn').on('click', function() {
    var artNoToDelete = $(this).data('artno');
    var multipleartNosToDelete = $(this).data('artnos') ? $(this).data('artnos').split(',') : null;
    var roleIdToDelete = $(this).data('roleid');

    if (artNoToDelete) {
        // Single article deletion
        deletecategoryArticle(artNoToDelete, roleIdToDelete);
    }  
     if (multipleartNosToDelete) {
        // Multiple articles deletion
        deleteMultiplecategoryArticles(multipleartNosToDelete, roleIdToDelete);
    }

    // Clear the data attributes after the operation
    $(this).removeData('artno').removeData('multiartnos').removeData('roleid');

        // Hide the modal
    $('#custom-uam-alert-delete-dlg-category').hide();
    $('#deleteovercategory').hide();
});    

function deletecategoryArticle(artNoToDelete, roleIdToDelete) {
    // AJAX request to delete single article
    $.post(ajaxurl, {
        action: 'custom_uam_delete_category',
        artNo: artNoToDelete,
        roleId: roleIdToDelete
    }, function(deleteResponse) {
        if (deleteResponse.success) {
            console.log('Art No deleted successfully:', artNoToDelete);
            // Remove the deleted item from UI            
            $('div[data-artno="' + artNoToDelete + '"][data-roleid="' + roleIdToDelete + '"]').remove();
            // Check if there are any remaining articles for the role
            var remainingItems = $('li[data-roleid="' + roleIdToDelete + '"]').length;
            console.log('Remaining items for role ID ' + roleIdToDelete + ':', remainingItems);

            if (remainingItems == 0) {
                var noArticlesHtml = '<li style="background-color: rgb(255, 255, 255);padding: 10px;margin: 2px 5px;border-radius: 4px;">';
                noArticlesHtml += '<span style="color: #000000; font-weight: medium;">No products found.</span>';
                noArticlesHtml += '</li>';
                $("#c_uam_caprole_ul_category").html(noArticlesHtml);
                $("#c_uam_capuser_ul_category").html(noArticlesHtml);
                $('.restrictcategory-prod-checkbox-item-container').hide();
                $('.restrictcategoryuser-prod-checkbox-item-container').hide();
            }
            $('#deleterestrictcategoryproduct').removeData('artno roleid rolename');
            $('#deleterestrictcategoryuserproduct').removeData('artno roleid rolename');
            $('#editrestrictproductcategory').removeData('editurl');
            $('#editrestrictproductcategory').css('color', 'rgb(156, 163, 175)');  
            $('#editrestrictuserproductcategory').removeData('editurl');
            $('#editrestrictuserproductcategory').css('color', 'rgb(156, 163, 175)');      

        } else {
            console.error('Failed to delete Art No:', deleteResponse.data);
        }
    }, 'json').fail(function(error) {
        console.error('AJAX Error:', error.statusText);
    });
}

function deleteMultiplecategoryArticles(multipleartNosToDelete, roleIdToDelete) {
   //console.log('Deleting articles:', multipleartNosToDelete, 'for Role ID:', roleIdToDelete);
    
    // AJAX request to delete multiple articles
    $.post(ajaxurl, {
        action: 'custom_uam_delete_multiplecategory',
        multipleartNosToDelete: multipleartNosToDelete.join(','), // Convert array to comma-separated string
        roleId: roleIdToDelete
    }, function(deleteResponse) {
        if (deleteResponse.success) {
            console.log('Art Nos deleted successfully:', multipleartNosToDelete);
            
            // Remove the deleted items from UI
            multipleartNosToDelete.forEach(function(artNo) {
                $('div[data-artno="' + artNo + '"][data-roleid="' + roleIdToDelete + '"]').remove();
            });
            
            // Check if there are any remaining articles for the role
            var remainingItems = $('li[data-roleid="' + roleIdToDelete + '"]').length;
            console.log('Remaining items for role ID ' + roleIdToDelete + ':', remainingItems);

            if (remainingItems == 0) {
                var noArticlesHtml = '<li style="background-color: rgb(255, 255, 255);padding: 10px;margin: 2px 5px;border-radius: 4px;">';
                noArticlesHtml += '<span style="color: #000000; font-weight: medium;">No products found.</span>';
                noArticlesHtml += '</li>';
                $("#c_uam_caprole_ul_category").html(noArticlesHtml);
                $("#c_uam_capuser_ul_category").html(noArticlesHtml);
            }
            $('.restrictcategory-prod-checkbox-item-container').hide();
            $('.restrictcategoryuser-prod-checkbox-item-container').hide();
            categorycheckboxesVisible = false;
            usercategorycheckboxesVisible = false;
            $('#deleterestrictcategoryproduct').removeData('artno roleid rolename');
            $('#deleterestrictcategoryuserproduct').removeData('artno roleid rolename');

        } else {
            console.error('Failed to delete Art Nos:', deleteResponse.data);
        }
    }, 'json').fail(function(error) {
        console.error('AJAX Error:', error.statusText);
    });
}

// Handle the Cancel button click
$('#cancel-remove-categorybtn').on('click', function() {
    // Hide the modal
    $('#custom-uam-alert-delete-dlg-category').hide();
    $('#deleteovercategory').hide();
    // Uncheck all checkboxes
    $('.restrictcategory-checkbox').prop('checked', false);
    $('#restrictcategory_select_all').prop('checked', false);
    $('.restrictcategoryuser-checkbox').prop('checked', false);
    $('#restrictcategoryuser_select_all').prop('checked', false);
});
$('#deleterestrictcategoryproduct').prop('disabled', true);
$('#deleterestrictcategoryuserproduct').prop('disabled', true);


 // Edit Roles product
 $('#editrestrictproductcategory').on('click', function(event) {
    event.stopPropagation(); // Prevent propagation to document click handler
    var editUrl = $(this).data('editurl');
    console.log(editUrl);

    if (editUrl) {
        // Open edit URL in new tab
        window.open(editUrl, '_blank');
    } else {
        // Show tooltip if edit URL is not available
        var tooltip = $(this).find('.restrict-tooltipcategory');
        tooltip.css('display', 'block').css('opacity', '1');
    }
});

// Handle click on document to hide tooltip when clicking outside
$(document).on('click', function(event) {
    // Check if the clicked element is not the edit button or the tooltip itself
    if (!$(event.target).closest('#editrestrictproductcategory').length && !$(event.target).closest('.restrict-tooltipcategory').length) {
        // Hide all tooltips
        $('.restrict-tooltipcategory').css('display', 'none').css('opacity', '0');
    }
});






















function clearPreviousCategories() {
    // Clear the hidden input field
    allArtNosInputcategory.value = ''; // Clear the hidden input
    // Empty the entered-artnos list (front-end)
    enteredArtNosContainercategory.innerHTML = ''; // Empty the list of entered categories
    // Reset the artNoscategory array (to prevent old categories from being saved again)
    artNoscategory = [];
}


    // Function to clear input value and search results
    function clearSearchrestrictcategoryData() {
        $('#custom-uam-input-restrictcategory-artno').val(''); // Clear input field
        $('#restrictcategory-search-results').hide(); // Hide results container
        $('#restrictcategory-search-results .restrictcategory-art-search-results').empty(); // Clear search results
    }

    // Event listener for dialog close
    $(document).on('tb_unload', function() {
        clearSearchrestrictcategoryData(); // Clear search data when dialog is closed
        $("#restrictcategory-entered-artnos").empty(); // Empty the entered-artnos list
        $("#restrictcategory-all-artnos").val('');
        artNos = []; // Clear the artNos array
    });

   // Initialize the search functionality
   $('#custom-uam-input-restrictcategory-artno').on('input', function () {
    var searchTerm = $(this).val().trim();
    var $resultsContainer = $('#restrictcategory-search-results .restrictcategory-art-search-results');
    var remainingChars = 3 - searchTerm.length;
    
    if (searchTerm === "") {
        // If search term is empty, hide the results container and return
        $('#restrictcategory-search-results').hide();
        $resultsContainer.html('<ul class="chosen-results"><li class="no-results-category">Please enter 3 or more characters</li></ul>'); // Initial message
        return;
    } else if (remainingChars > 0) {
        // If not enough characters entered, show a message with the remaining count
        $resultsContainer.html('<ul class="chosen-results"><li class="no-results-category">Please enter ' + remainingChars + ' or more characters</li></ul>');
        $('#restrictcategory-search-results').show();
        return;
    }

    console.log('searchTerm:', searchTerm);
    
    $('#restrictcategory-search-results').show(); // Show the results container
    
    $(document).on('click', function (event) {
        if (!$resultsContainer.is(event.target) && $resultsContainer.has(event.target).length === 0) {
            $('#restrictcategory-search-results').hide(); // Hide the results container if clicked outside
        }
    });

    // Show loading spinner or indicator
    $resultsContainer.html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');

    // AJAX request
    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'restrictcategory_get_product_matches_skus',
            search_query: searchTerm,
        },
        success: function(response) {
            var $resultsContainer = $('#restrictcategory-search-results .restrictcategory-art-search-results');
            $resultsContainer.empty();
    
            if (response.length) {
                response.forEach(function(artno) {
                    $resultsContainer.append(
                        '<div style="padding-right: 1.25rem; padding-bottom: 5px; padding-top: 5px; padding-left: 10px; cursor: pointer;" class="restrictcategory-art-search-item" data-artno="' + artno + '">' +
                        artno + 
                        '</div>'
                    );
                });
                $('#restrictcategory-search-results').show();
            } else {
                $resultsContainer.html('<div style="padding-right: 1.25rem; padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">No results found</div>');
                $('#restrictcategory-search-results').show();
            }
        },
        error: function(xhr, textStatus, errorThrown) {
            console.error('Error: ' + errorThrown);
            var $resultsContainer = $('#restrictcategory-search-results .restrictcategory-art-search-results');
            $resultsContainer.html('<div style="padding-right: 1.25rem; padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">Error fetching results</div>');
            $('#restrictcategory-search-results').show();
        },
        complete: function () {
            $resultsContainer.find('.loading-spinner').remove(); // Remove spinner if added
        }
    });
});

$(document).on('click', '#restrictcategory-search-results .restrictcategory-art-search-item', function () {
    var selectedArtNo = $(this).data('artno');
    console.log('Selected Art No:', selectedArtNo);

    // Do something with the selected item
    // For example, you can set the input value to the selected Art No
    $('#custom-uam-input-restrictcategory-artno').val(selectedArtNo);

    // Hide the results container
    $('#restrictcategory-search-results').hide();
});

const addArtNoBtncategory = document.getElementById('restrictcategory-add-artno-btn');
const artNoInputcategory = document.getElementById('custom-uam-input-restrictcategory-artno');
const enteredArtNosContainercategory = document.getElementById('restrictcategory-entered-artnos');
const allArtNosInputcategory = document.getElementById('restrictcategory-all-artnos');
const saveButtoncategory = document.getElementById('submit');
const formcategory = document.getElementById('custom_uam_save_restrictcategory_artno');
let artNoscategory = [];

function updatecategoryHiddenInput() {
    allArtNosInputcategory.value = JSON.stringify(artNoscategory);
}

function disablecategoryAddButton() {
    addArtNoBtncategory.disabled = true;
    addArtNoBtncategory.style.setProperty('background-color', '#2271b1', 'important'); // Set the background color to a disabled state with !important
    addArtNoBtncategory.style.setProperty('color', 'white', 'important'); // Change the cursor to indicate the button is disabled with !important
    addArtNoBtncategory.style.setProperty('border-color', '#2271b1', 'important'); // Change the cursor to indicate the button is disabled with !important
}

function enablecategoryAddButton() {
    addArtNoBtncategory.disabled = false;
    addArtNoBtncategory.style.backgroundColor = ''; // Restore the original background color
    addArtNoBtncategory.style.cursor = ''; // Restore the original cursor
}

if (addArtNoBtncategory) {
    // console.log('hgghghghghghghghghghghg');
    addArtNoBtncategory.addEventListener('click', function () {
        // console.log('hgghghghgteamnhjh');
    const artNo = artNoInputcategory.value.trim();
        const roleId = selectedRoleIdcategory; // Get the stored role ID
        var activeTabtype = $(".navdd-categorytab-active").attr('id'); // Get the active tab ID
        var selectedRoleElement = document.querySelector('.custom-uam-restrictcategoryproduct-li.active');
        var roleName = selectedRoleElement ? selectedRoleElement.getAttribute('data-role') : 'Role';
        var actype = activeTabtype === 'roles-categorytab' ? 'role' : 'user'; // Determine the type

        console.log('actype', actype);
        console.log('artNo', artNo);
        console.log('roleId', roleId);
        console.log('artNo',artNo);

        if (artNo && roleId) 
        {
            if (artNoscategory.includes(artNo)) {
                alert('This Category name has already been added.');
                artNoInputcategory.value = ''; // Clear input field
                return;
            }

            // Disable the add button to prevent multiple clicks
            disablecategoryAddButton();

            // First AJAX request to check if the Art No, Role ID, and Type combination exists
            const checkSameArtNoDatacategory = {
                action: 'custom_uam_checksame_category_exists',
                artNo: artNo,
                roleId: roleId,
                type: actype // Include the type
            };

            jQuery.post(ajaxurl, checkSameArtNoDatacategory, function (response) {
                if (response.success) {
                    if (response.data.exists) {
                        console.log('10000');
                        // Show alert if the Art No already exists with the given Role ID and Type
                        const alertMessage = `This Category name is already restricted to the "${roleName}" role. You might want to modify or remove the existing restriction before adding this Category name.`;
                        alert(alertMessage);
                        artNoInputcategory.value = ''; // Clear input field
                        enablecategoryAddButton(); // Re-enable the button
                    } else {
                        console.log('222220');
                        // Second AJAX request to check if the Art No and Role ID combination exists
                        const checkArtNoData = {
                            action: 'custom_uam_check_restrict_exists',
                            artNo: artNo,
                            roleId: roleId
                        };

                        jQuery.post(ajaxurl, checkArtNoData, function (response) {
                        if (response.success) {
                                    if (response.data.exists) {
                                        // Update the confirmation message to include the roleId
                                        const confirmationMessage = `This product is already assigned to "${roleName}" role. Continuing will restrict this product to “${roleId}” only, making it inaccessible to other "${roleName}" users.`;
                                        
                                        if (confirm(confirmationMessage)) {
                                            addArtNocategory(artNo);
                                        } else {
                                            artNoInputcategory.value = ''; // Clear input field
                                        }
                                    } else {
                                        addArtNocategory(artNo);
                                    }
                                } else {
                                    console.error('Error checking Art No:', response.data);
                                }
                                enablecategoryAddButton();
                            }).fail(function (error) {
                                console.error('AJAX Error checking Art No:', error.statusText);
                                enablecategoryAddButton();
                            });
                        }
                } else {
                    console.error('Error checking Art No with Role ID and Type:', response.data);
                    enablecategoryAddButton();
                }
            }).fail(function (error) {
                console.error('AJAX Error checking Art No with Role ID and Type:', error.statusText);
                enablecategoryAddButton();
            });
        }
});
}

function addArtNocategory(artNo) {
    const divWrapper = document.createElement('div');
    divWrapper.style.background = '#e6e5e5'; // Background color for the wrapper div
    divWrapper.style.padding = '4px';
    divWrapper.style.borderRadius = '4px';
    divWrapper.style.marginBottom = '10px'; // Bottom margin for spacing

    const listItem = document.createElement('li');
    listItem.style.backgroundColor = '#FFFFFF';
    listItem.style.padding = '10px';
    listItem.style.margin = '5px 0';
    listItem.style.borderRadius = '4px';

    const artNoContainer = document.createElement('div');
    artNoContainer.style.display = 'flex';
    artNoContainer.style.alignItems = 'center';

    const artNameContainer = document.createElement('div');
    artNameContainer.style.display = 'flex';
    artNameContainer.style.alignItems = 'flex-start';
    artNameContainer.style.marginTop = '5px'; // Margin for spacing between Art No and Art Name

    const artNoLabel = document.createElement('span');
    artNoLabel.textContent = 'Category Name';
    artNoLabel.style.flex = '1';
    artNoLabel.style.fontWeight = 'bold';
    artNoLabel.style.textAlign = 'left';

    const artNoColon = document.createElement('span');
    artNoColon.textContent = ':';
    artNoColon.style.flex = '0 0 10px';
    artNoColon.style.textAlign = 'center';
    artNoColon.style.marginLeft = '-65px';

    const artNoValue = document.createElement('span');
    artNoValue.textContent = artNo;
    artNoValue.style.flex = '2';
    artNoValue.style.textAlign = 'left';
    artNoValue.style.marginLeft = '10px';

    const deleteIcon = document.createElement('img');
    deleteIcon.src = 'https://smartstoring.eu/wp-content/plugins/thingsatweb/img/close-circle.png';
    deleteIcon.alt = 'Delete';
    deleteIcon.style.cursor = 'pointer';
    deleteIcon.style.marginLeft = '10px';
    deleteIcon.style.width = '20px';
    deleteIcon.style.height = '20px';

    deleteIcon.addEventListener('click', function () {
        divWrapper.remove();
        const index = artNoscategory.indexOf(artNo);
        if (index !== -1) {
            artNoscategory.splice(index, 1);
        }
        updatecategoryHiddenInput();
    });

    artNoContainer.appendChild(artNoLabel);
    artNoContainer.appendChild(artNoColon);
    artNoContainer.appendChild(artNoValue);
    artNoContainer.appendChild(deleteIcon);


    listItem.appendChild(artNoContainer);

    divWrapper.appendChild(listItem);
    enteredArtNosContainercategory.appendChild(divWrapper);

    artNoscategory.push(artNo);
    updatecategoryHiddenInput();

    artNoInputcategory.value = ''; 
}

var isSubmittingcategory = false;

// Function to handle form submission

$("#custom_uam_save_restrictcategory_artno").submit(function(e) {
    e.preventDefault();
    var form = $(this);
    var restrictartno = $("#restrictcategory-all-artnos").val();
    console.log('restrictartno',restrictartno);
    console.log('form',form);

    // Check if artNos array is empty
    if (!restrictartno || restrictartno.trim() === "" || artNoscategory.length === 0) {
        alert('Please add at least one Art No before saving.');
        return;
    }

    // Check if the form is already being submitted
    if (isSubmittingcategory) {
        return false;
    }

    isSubmittingcategory = true; // Set the flag to indicate form submission
    var role_id = selectedRoleIdcategory; // Get the stored role ID
    var role_name = selectedRolenamecategory;
    var activeTab = $(".navdd-categorytab-active").attr('id'); // Get the active tab ID

    var type = activeTab === 'roles-categorytab' ? 'role' : 'user'; // Determine the type
    //console.log('type', type);

    // Disable the submit button to prevent multiple submissions
    form.find(':submit').prop('disabled', true);
    // Show the loading spinner
    $('#loaderhid').show();

    $.post(form.attr('action'), {
        action: 'custom_uam_save_restrictcategory_artno',
        restrict_artno: restrictartno,
        role_id: role_id,
        type: type
    }, function(response) {
        console.log(response);
        let arr = response.data.new_items[0].AlertArray;

        if(arr.length==1){
            alert(arr[0]);
        }
        else if(arr.length>0){
            alert('As this product as some rules in stack price for other users too there is a change in stack pricing please check');
        }

        if (response.error) {
            $("#custom-uam-alert-error-txt").css("display", "block").text(response.data.message);
            isSubmittingcategory = false; // Reset the flag on error
            form.find(':submit').prop('disabled', false); // Re-enable the submit button on error
            $('#loaderhid').hide(); // Hide the loading spinner on error
            } else if (response.success) {
                clearPreviousCategories(); // Clear the artNos array and the entered categories

                var newItems = response.data.new_items; // Array of new items

                if (newItems.length > 0) {
                    $("#c_uam_caprole_ul_category li:contains('No products found.')").remove();
                    $("#c_uam_capuser_ul_category li:contains('No products found.')").remove();
                    // Clear the entered-artnos list
                    
                    // Process each new item asynchronously
                    newItems.forEach(function(newItem) {
                        tb_remove(); 
                                if(type='role'){
                                    var listItemHtml = '<div class="check-item" style="display: flex; align-items: center;" data-artno="' + newItem.art_no + '" data-roleid="' + role_id + '"  data-rolename="' + role_name + '">';
                                    listItemHtml += '<input type="checkbox" class="restrictcategory-checkbox" style="margin-right: 10px; display:none;">'; // Checkbox outside the <li>
                                    listItemHtml += '<li class="list-item" style="width:100%;background-color: rgb(255, 255, 255); padding: 10px; margin: 10px 5px; border-radius: 4px; color: black; list-style: none; display: flex; flex-direction: column;" data-artno="' + newItem.art_no + '" data-roleid="' + role_id + '"  data-rolename="' + role_name + '">';
                                    listItemHtml += '<div style="padding: 4px; border-radius: 4px;">';
                                    listItemHtml += '<div style="display: flex; align-items: center;">';
                                    listItemHtml += '<span style="flex: 0.5; font-weight: bold; text-align: left;">Category Name</span>';
                                    listItemHtml += '<span style="flex: 0 0 10px; text-align: center;">:</span>';
                                    listItemHtml += '<span style="flex: 2; text-align: left; margin-left: 10px;">' + newItem.art_no + '</span>';
                                    listItemHtml += '</div>';
                                    listItemHtml += '</li>';
                                    listItemHtml += '</div>';
                                    $("#c_uam_caprole_ul_category").append(listItemHtml);
                                }
                                if(type='user'){

                                    var listHtml = '<div class="check-item" style="display: flex; align-items: center;" data-artno="' + newItem.art_no + '" data-roleid="' + role_id + '" data-rolename="' + role_name + '">';
                                        listHtml += '<input type="checkbox" class="restrictcategoryuser-checkbox" style="margin-left: 10px; display:none;">'; // Checkbox outside the <li>
                                        listHtml += '<li class="list-item" style="width:100%;background-color: rgb(255, 255, 255); padding: 10px; margin: 10px 15px; border-radius: 4px; color: black; list-style: none; display: flex; flex-direction: column;" data-artno="' + newItem.art_no + '" data-roleid="' + role_id + '"  data-rolename="' + role_name + '">';
                                        listHtml += '<div style="padding: 4px; border-radius: 4px;">';
                                        listHtml += '<div style="display: flex; align-items: center;">';
                                        listHtml += '<span style="flex: 0.5; font-weight: bold; text-align: left;">Category Name</span>';
                                        listHtml += '<span style="flex: 0 0 10px; text-align: center;">:</span>';
                                        listHtml += '<span style="flex: 2; text-align: left; margin-left: 10px;">' + newItem.art_no + '</span>';
                                        listHtml += '</div>';

                                        listHtml += '</li>';
                                        listHtml += '</div>';
                                        $("#c_uam_capuser_ul_category").append(listHtml);
                                    } 

                                    // Hide the loading spinner after all AJAX calls are completed
                                    $('#loaderhid').hide();
                                    isSubmittingcategory = false; // Reset the flag on success
                                    form.find(':submit').prop('disabled', false); // Re-enable the submit button on success
                                }, 'json');
                } else {
                    console.error('No new items found in server response.');
                    isSubmittingcategory = false; // Reset the flag if no new items found
                    form.find(':submit').prop('disabled', false); // Re-enable the submit button if no new items found
                $('#loaderhid').hide(); // Hide the loading spinner if no new items found
                }
            } 
    }, 'json');
});












































    



    
    $('#adduser-role, #selrole').on('change', function() {
        var selectedRole = $(this).val();
        console.log("selectedRole", selectedRole);
        var resellerRoles = ['custom_uam_reseller_eur', 'custom_uam_reseller_sek', 'custom_uam_b2b'];
        console.log('selectedRole:::', selectedRole);
        if (resellerRoles.includes(selectedRole)) {
            $('#customer_no_row').show();
        } else {
            $('#customer_no_row').hide();
        }
    
        // Parse the JSON data
        var roleData = $('#jsndata').val();
        //console.log('roleData', roleData);
        // Check if selectedRole exists in the parsed JSON data
        if (roleData[selectedRole] && roleData[selectedRole]['roleissubrole'] === '1') {
            $('#subcustomer_no_row').show();
        } else {
            $('#subcustomer_no_row').hide();
        }
    });
    
    $('#pass1, #pass2').on('input', function () {
        var passwordField = document.getElementById('pass1');
        var confirmField = document.getElementById('pass2');
        var toggleButton = document.querySelector('.wp-hide-pw');
        var errorText = document.getElementById('password-error');

        toggleButton.addEventListener('click', function () {
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleButton.setAttribute('aria-label', 'Hide password');
                toggleButton.querySelector('.dashicons').classList.remove('dashicons-hidden');
                toggleButton.querySelector('.dashicons').classList.add('dashicons-visibility');
            } else {
                passwordField.type = 'password';
                toggleButton.setAttribute('aria-label', 'Show password');
                toggleButton.querySelector('.dashicons').classList.remove('dashicons-visibility');
                toggleButton.querySelector('.dashicons').classList.add('dashicons-hidden');
            }
        });

        if (confirmField.value === passwordField.value) {
            errorText.style.display = 'none';
        } else {
            errorText.style.display = 'block';
        }
    });
    // Trigger change event initially to set initial visibility and options
    $('#adduser-role, #selrole').trigger('change');
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

    $('.taw-spareparts-row-delete').on('click', function () {

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
    
    $('.taw-spareparts-row-edit').on('click', function () {

        let data = $(this).parents("td").find(".data-hld");
        let id = data.attr('data-id');
        let parent_article = data.attr('data-parentarticle');
        let spare_article = data.attr('data-sparearticle');
        let min_qty = data.attr('data-minqty');
       
        console.log(id);
        console.log(parent_article);
        console.log(spare_article);
        console.log(min_qty);

        $('#taw-form-article-id').val(id);
        $('#taw-form-article-spareparts-parentnumber').val(parent_article);
        $('#taw-form-article-spareparts-sparepartsArtnumber').val(spare_article);
        $('#taw-form-article-spareparts-minqty').val(min_qty);   

    });

    $('#taw-form-article-spareparts-submit').on('click', function () {

        let id =  $('#taw-form-article-id').val();
        let parent_article = $('#taw-form-article-spareparts-parentnumber').val();
        let spare_article =  $('#taw-form-article-spareparts-sparepartsArtnumber').val();
        let min_qty =  $('#taw-form-article-spareparts-minqty').val();
        console.log(parent_article,spare_article,min_qty);
        let data = {
            'action': 'taw_save_article_spareparts',
            'parent_article': parent_article,
            'spare_article': spare_article,
            'min_qty': min_qty,
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


    $('.taw-diagram-row-delete').on('click', function () {

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
    $('.taw-diagram-row-edit').on('click', function () {

        let data = $(this).parents("td").find(".data-hld");

        let id = data.attr('data-id');

        let diagram = data.attr('data-diagram');
        let diagram2 = data.attr('data-diagram2');
        let diagram3 = data.attr('data-diagram3');
        let art_no = data.attr('data-artno');
        

        $('#artno-label, #taw-form-article-diagram-artno').hide();
        $('#add-new-btn').click(function() {
            $('#artno-label, #taw-form-article-diagram-artno').show();
        });
        $('#edit-btn').click(function() {
            $('#artno-label, #taw-form-article-diagram-artno').hide();
        });
       
        $('#taw-form-article-id').val(id);
        $('#taw-form-article-diagram-artno').val(art_no);
        $('#taw-form-article-diagram').val(diagram);
        $('#taw-form-article-diagram2').val(diagram2);
        $('#taw-form-article-diagram3').val(diagram3);
        
       
    });

    $('#taw-form-article-diagram-submit').on('click', function () {

        let art_no = $('#taw-form-article-diagram-artno').val();
        let diagram = $('#taw-form-article-diagram').val();
        let diagram2 = $('#taw-form-article-diagram2').val();
        let diagram3 =  $('#taw-form-article-diagram3').val();
        
        let id =  $('#taw-form-article-id').val();
     
        let data = {
            'action': 'taw_save_article_diagram',
            'diagram': diagram,
            'diagram2': diagram2,
            'diagram3': diagram3,
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

        });
    } 
});
