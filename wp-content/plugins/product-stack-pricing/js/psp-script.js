jQuery(document).ready(function ($) {

    function initPspScript() {
        $('#close-psp-add-product-popup').click(function () {
            $('.product-card-popup-container').find('.product-card-popup').remove();
            $('#popup-product-card-nodatamsg').show();
            $('#add-product-search-input').val('')
            $('#container-psp-add-product-popup').fadeOut();
        });


        $('#psp-user-pop-default-search').on('input', function () {
            var searchText = $(this).val().trim().toLowerCase();

            $('.all-users-by-role').each(function () {
                var userCard = $(this);
                var userName = userCard.find('.search-include-users').text().trim().toLowerCase();
                if (userName.includes(searchText)) {
                    userCard.show();
                } else {
                    userCard.hide();
                }
            });
        });

  
        $(document).ready(function() {
            function preventInvalidInput(event) {
                const charCode = event.which || event.keyCode;
                if (charCode === 45 || charCode === 43) {
                    event.preventDefault();
                }
            }
    
            function preventInvalidPaste(event) {
                const clipboardData = (event.originalEvent || event).clipboardData.getData('text');
                if (clipboardData.includes('-') || clipboardData.includes('+')) {
                    event.preventDefault();
                }
            }
    
            $('#psp-qty-default, #psp-price-default').on('keypress', preventInvalidInput);
            $('#psp-qty-default, #psp-price-default').on('paste', preventInvalidPaste);
        });
        $('.all-users-by-role').on('click', function () {
        

            const button = $(this).find('button');  
            if (button.hasClass('disabled')) {
                return;
            }
            if (!button.hasClass('disabled')) {
                $(this).toggleClass('user-popup-card');
            }
       
            $(this).find('.checked-icon-psp').css('visibility', function(i, visibility) {
                return visibility === 'visible' ? 'hidden' : 'visible';
            });

            // Check if all elements have the 'user-popup-card' class
            var allSelected = $('.all-users-by-role').length === $('.all-users-by-role.user-popup-card').length;
     

            
            // Update the #select-all-users checkbox
            $('#select-all-users').prop('checked', allSelected);
            if(allSelected){
                $('.checked-icon-psp-all').css('visibility', 'visible');
            }else{
                $('.checked-icon-psp-all').css('visibility', 'hidden');

            }

        });
        $('#psp-product-add-btn').click(function () {
            $('#container-psp-add-product-popup').fadeIn();
        });

        $('#close-psp-add-rule-popup').click(function () {

            $('#container-psp-add-rule-popup').hide();

        });

        $('#psp_edit_edit_button').click(function () {
            var editUrl = $(this).data('editurl');
         

            if (editUrl) {
                // Open edit URL in new tab
                window.open(editUrl, '_blank');
            } else {
                // Show tooltip if edit URL is not available
                var tooltip = $(this).find('.psp-tooltip');
                tooltip.css('visibility', 'visible').css('opacity', '1');


            }
        });
        $(document).click(function (event) {
            // Check if the clicked element is not the edit button or the tooltip itself
            if (!$(event.target).closest('#psp_edit_edit_button').length) {
                // Hide the tooltip
                $('.psp-tooltip').css('visibility', 'hidden').css('opacity', '0');
            }
        });






        $('.delete-rule-btn').click(function () {
            var ruleId = $(this).data('id');
            var artno = $(this).data('artno');
            var ruleDescription = $(this).data('description'); // Assuming you have a data attribute for the rule description

            // Set the rule description in the popup
            $('#rule-description').html(ruleDescription);

            // Show the popup
            $('#container-psp-delete-rule-popup').show();

            // Handle the confirmation button click
            $('#confirm-delete-btn').off('click').on('click', function () {
                $('#container-psp-delete-rule-popup').hide();
                $('#psp-added-rule-container').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');

                $.ajax({
                    url: psp_ajax_obj.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'psp_delete_stacking_rule', // AJAX action to delete rule
                        nonce: psp_ajax_obj.psp_search_nonce, // Nonce for security
                        id: ruleId,
                        artno: artno
                    },
                    success: function (response) {
                     
                        // Optionally, perform actions after successful deletion
                        refreshRuleList(artno);
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.error('Error: ' + errorThrown);
                        refreshRuleList(artno);
                        // Handle errors gracefully
                    }
                });
            });

            // Handle the cancel button click
            $('#cancel-delete-btn').off('click').on('click', function () {
                $('#container-psp-delete-rule-popup').hide();
            });
        });

        $('#select-all-users').change(function (e) {
            console.log('feygfd');
            e.stopPropagation();
            if (this.checked) {
                $('.all-users-by-role').addClass('user-popup-card');
                $('.all-users-by-role').find('.checked-icon-psp').css('visibility', 'visible');
                $('.checked-icon-psp-all').css('visibility', 'visible');

                // Add code here to select all users and add them to the container if needed
            } else {
                $('.all-users-by-role').removeClass('user-popup-card');
                $('.all-users-by-role').find('.checked-icon-psp').css('visibility', 'hidden');
                $('.checked-icon-psp-all').css('visibility', 'hidden');


            }
        });



        $(document).on('click', '#psp-search-results .art-search-item > div', function () {
            var artno = $(this).text();
            var prodname = $(this).data('prodname');
            
            if (!prodname) {
                return; // Exit the function
            }
            $('#add-product-search-input').val(artno);
            $('#add-product-search-input').data('prodname', prodname);

            $('#psp-search-results').addClass('hidden');
        });
        $(document).on('click', '#psp-user-search-results .user-search-item > div', function () {
            var username = $(this).text();
            var userId = $(this).data('userid');
            var customer_no = $(this).data('customerno');
            var subcustomer_no = $(this).data('subcustomerno');
      

            $('#add-user-search-input').val(username);
            $('#add-user-search-input').data('userId', userId);
            $('#add-user-search-input').data('customerno', customer_no);
            $('#add-user-search-input').data('subcustomerno', subcustomer_no);

            $('#psp-user-search-results').addClass('hidden');
        });


        $('#add-user-search-input').on('input', function () {
            var searchTerm = $(this).val().trim();
            var $resultsContainer = $('#psp-user-search-results .user-search-item');
            $('#psp-user-search-results').removeClass('hidden');

            $(document).on('click', function (event) {
                if (!$resultsContainer.is(event.target) && $resultsContainer.has(event.target).length === 0) {
                    // Click was outside the results container
                    $('#psp-user-search-results').addClass('hidden');
                }
            });

            // Show loading spinner or indicator
            $resultsContainer.html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');

            // AJAX request
            $.ajax({
                url: psp_ajax_obj.ajax_url,
                type: 'POST',
                data: {
                    action: 'psp_get_user_matches', // AJAX action defined in PHP
                    search_query: searchTerm, // Corrected to match PHP handler's expectation
                    nonce: psp_ajax_obj.psp_search_nonce // Nonce for security
                },
                success: function (response) {
                  

                    $resultsContainer.empty();

                    if (response.data.length) {
                        response.data.forEach(function (user) {
                            $resultsContainer.append(
                                '<div class="my-1 px-5 py-2 bg-slate-50 rounded-lg hover:bg-slate-300 cursor-pointer user-search-item" data-userid="' + user.userid + '" data-customerno="' + user.customer_no + '" data-subcustomerno="' + user.subcustomer_no + '">' +
                                user.userName +
                                '</div>'
                            );
                        });
                        $('#psp-user-search-results').removeClass('hidden');
                    } else {
                        // Handle case where no results are found
                        $resultsContainer.html('<div class="my-1 px-5 py-2">No results found</div>');
                        $('#psp-user-search-results').removeClass('hidden');
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.error('Error: ' + errorThrown);
                    $resultsContainer.html('<div class="my-1 px-5 py-2 bg-slate-50 rounded-lg ">Error fetching results</div>');
                    $('#psp-user-search-results').removeClass('hidden');
                },
                complete: function () {
                    // Hide loading spinner or indicator after AJAX completes (success or error)
                    $resultsContainer.find('.loading-spinner').remove(); // Remove spinner if added
                }
            });
        });



        // Show popup container
        $('#psp-product-add-btn').click(function () {

            $('#container-psp-add-product-popup').fadeIn();
        });

        // Close popup container
        $('#close-psp-add-product-popup').click(function () {
            $('#container-psp-add-product-popup').fadeOut();
        });



        $('#user_add_popup').click(function () {
            var userName = $('#add-user-search-input').val().trim();
            var userId = $('#add-user-search-input').data('userId');
            var customer_no = $('#add-user-search-input').data('customerno');
            var subcustomer_no = $('#add-user-search-input').data('subcustomerno');
            var messadd = $('#popup-user-card-nodatamsg');

            var useridorg = userId
            if (customer_no != '') {
                userId = customer_no
            } if (subcustomer_no != '') {
                userId = subcustomer_no
            } else {
                userId = userId
            }

        

            if (userName && userId) {
            
                messadd.hide();
                // Create product card HTML
                var userCardHtml = `
                
                                <div class="bg-white p-2 relative rounded border cursor-pointer user-popup-card hover:bg-slate-50 flex gap-2 items-center justify-start" data-userid="`+ useridorg + `">
                    <div class="max-w-[100px]" >
                    <h4 class="font-semibold">`+ userName + `</h4>
                    <p>`+ userId + `</p>
                    </div>
                       <div class="flex item-center !w-[20px]">
                        <button class="!text-psp-red  right-2  remove-user-card-item">
                                <img  class="start-0 !w-[20px] rounded-full  !text-psp-red" src="`+ psp_ajax_obj.plugin_url + `img/fluent--delete-12-regular.png" alt="">
                            </button>
                       </div>
                </div>
                    
             
                `;

                // Append product card to container
                $('#popup-user-container').append(userCardHtml);

                // Clear search input
                $('#add-user-search-input').val('');
                $('#add-user-search-input').data('userId', '');
                $('#add-user-search-input').data('customerno', '');
                $('#add-user-search-input').data('subcustomerno', '');

                initPspScript();
            }

        });

        $('#psp-role-default').change(function () {
            var artNo = $('#psp-rule-add-btn').data('artno');
          
            var selectedRole = $(this).val();
            var enable_all_users = $('#select-all-users').prop('checked');

 

            $('#popup-user-container').html('<div class="text-center col-span-full py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
            $.ajax({
                url: psp_ajax_obj.ajax_url,
                type: 'POST',
                data: {
                    action: 'psp_get_users_by_role',
                    role: selectedRole,
                    artNo:artNo
                },
                success: function (response) {
                    if (response.success) {
                        let users = response.data.users;
                        const restrictedRules = response.data.restricted_rules;
                        const rolesRoleIds = response.data.roles_role_ids;
                        const restrictedUsers = response.data.restricted_users;
                        const Role=response.data.Role;
                        
                        let matchingUserIDs = [];
                        users.forEach(function(user) {
                            let userID = String(user.ID); // Convert userID to string
                            if (restrictedUsers.includes(userID)) {
                                // Store matching user IDs in the array
                                matchingUserIDs.push(user);
                            }
                        })
                        

                        users.forEach(function(user) {
                            let userID = String(user.ID); // Convert userID to string
                            if (!restrictedUsers.includes(userID)) {
                                // Store matching user IDs in the array
                                matchingUserIDs.push(user);
                            }
                        })
                       
                        users=matchingUserIDs;

                        var userCardHtml = '';
                        if (users.length) {

                            users.forEach(function (user) {
                                let isClickable;

                                if (restrictedUsers.length > 0) {
                                    if(Role){
                                        isClickable = restrictedUsers.includes(String(user.ID));
                                    }else{
                                        isClickable=true;
                                    }
                                } else {
                                    isClickable = true; // If there are no restricted users, make it clickable
                                }
                             
                                const buttonClass = isClickable ? '' : 'disabled'; // No class if clickable, 'disabled' if unclickable
                                const buttonStyle = isClickable ? '' : 'pointer-events: none; opacity: 1;'; // Normal style if clickable, unclickable style if not
                        
                                const imgSrc = isClickable 
                                ? `${psp_ajax_obj.plugin_url}img/MaterialSymbolsCheckCircleRounded.svg` 
                                : `${psp_ajax_obj.plugin_url}img/solar--user-block-bold.svg`;
                               
                                //const imgVisibility = imgSrc === `${psp_ajax_obj.plugin_url}img/DisabledIcon.svg` ? 'visible' : 'hidden';
                                //const imgVisibilityStyle = enable_all_users || imgVisibility === 'visible' ? 'visible' : 'hidden';

                                userCardHtml += `
                                <div class="bg-white p-2 relative rounded border cursor-pointer ${enable_all_users || isClickable ? 'user-popup-card' : ''} all-users-by-role hover:bg-slate-50 flex gap-2 items-center justify-start" data-userid="${user.ID}">
                                    <div class="flex item-center !w-[20px]">
                                        <button class="!text-psp-blue right-2 border border-psp-blue rounded-full ${buttonClass}" style="${buttonStyle}">
                                         <img class="start-0 !w-[20px] rounded-full checked-icon-psp !text-psp-blue" style="visibility: ${true ? 'visible' : 'hidden'};" src="${imgSrc}" alt="">
                                        </button>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold search-include-users">${user.display_name}</h4>
                                        <p class="search-include-users">${user.customer_no}</p>
                                    </div>
                                </div>
                            `;
                            });
                            
                            $('#popup-user-container').html(userCardHtml);
                           
                            $('.all-users-by-role').on('click', function () {
                          
                                const button = $(this).find('button');

                                if (button.hasClass('disabled')) {
                                    if ($(button).find('img').length === 0) {
                                        // The button has the 'disabled' class and no <img> child
                                        return; // Exit the function
                                    }

                                }
                                if (!button.hasClass('disabled')) {
                                    $(this).toggleClass('user-popup-card');
                                }

                      
                                // Check if the button does not have the 'disabled' class
                                if (button.hasClass('disabled')) {
                                    console.log('inside');
                                   return;
                                }
                           
                                // Check if all elements have the 'user-popup-card' class
                                var allSelected = $('.all-users-by-role').length === $('.all-users-by-role.user-popup-card').length;
                                $(this).find('.checked-icon-psp').css('visibility', function(i, visibility) {
                                    return visibility === 'visible' ? 'hidden' : 'visible';
                                });

                           


                                // Update the #select-all-users checkbox
                                $('#select-all-users').prop('checked', allSelected);
                                if(allSelected){
                                    $('.checked-icon-psp-all').css('visibility', 'visible');
                                }else{
                                    $('.checked-icon-psp-all').css('visibility', 'hidden');
                    
                                }

                            });

                            $('#select-all-users').change(function () {
                                if (this.checked) {
                                    $('.all-users-by-role').addClass('user-popup-card');
                                    $('.all-users-by-role').find('.checked-icon-psp').css('visibility', 'visible');

                                    $('.checked-icon-psp-all').css('visibility', 'visible');

                                    // Add code here to select all users and add them to the container if needed
                                } else {
                                    $('.all-users-by-role').removeClass('user-popup-card');
                                    $('.all-users-by-role').find('.checked-icon-psp').css('visibility', 'hidden');
                                    $('.checked-icon-psp-all').css('visibility', 'hidden');


                                }
                            });                 

                        }
                        else {
                            $('#popup-user-container').html(`<div class="text-center col-span-full py-4">No users available for this role</div>`);
                        }
                    } else {
                        console.log('Error: ' + response.data);
                    }
                  
                },
                error: function (xhr, status, error) {
                    console.log('AJAX Error: ' + error);
                }
            });
        });

        // Remove product card on remove button click
        $(document).on('click', '.remove-product-card-item', function () {
            $(this).closest('.product-card-popup').remove();
            cardlen = $('.product-card-popup').length;
            
            if (cardlen == 0) {
                $('#popup-product-card-nodatamsg').show();
            }
        });

        // Remove user card on remove button click
        $(document).on('click', '.remove-user-card-item', function () {
            $(this).closest('.user-popup-card').remove();
            cardlen = $('.user-popup-card').length;
            
            if (cardlen == 0) {
                $('#popup-user-card-nodatamsg').show();
            }
        });





    }

    initPspScript();



    $(document).ready(function () {
        let $selectAllCheckbox = $('#psp_select_all');
        let $productCheckboxes = $('.psp_product_checkbox');
        let $productCheckboxesContainer = $('.psp-prod-checkbox-item-container');

        let $deleteButton = $('#psp_delete_button');

        // Select All functionality
        $selectAllCheckbox.on('change', function () {
            $productCheckboxes.prop('checked', this.checked);
        });


            // Update Select All checkbox based on individual checkboxes
    $productCheckboxes.on('change', function () {
        if ($productCheckboxes.length === $productCheckboxes.filter(':checked').length) {
            $selectAllCheckbox.prop('checked', true);
        } else {
            $selectAllCheckbox.prop('checked', false);
        }
    });

        // Delete button click event
        $deleteButton.on('click', function () {

           
            var selectedArtNumbers = [];
            $productCheckboxes.each(function () {
                if ($(this).is(':checked')) {
                    selectedArtNumbers.push($(this).val());
                }
            });
             // Collect artno from active added-product-item elements
        $('.added-product-item.active').each(function () {
            selectedArtNumbers.push($(this).data('artno'));
        });
            $productCheckboxesContainer.each(function () {
                if ($(this) && selectedArtNumbers.length == 0) {
                    $(this).toggleClass('hidden')
                } else {
                    // Set the rule description in the popup
                    if (selectedArtNumbers.length == 1) {
                        $('#rule-description').text('Article Number : ' + selectedArtNumbers[0]);
                    } else {
                        $('#rule-description').text('Selected Products');
                    }
                    // Show the popup
                    $('#container-psp-delete-rule-popup').show();

                    // Handle the confirmation button click
                    $('#confirm-delete-btn').off('click').on('click', function () {
                        $('#container-psp-delete-rule-popup').hide();
                        $('#psp-added-rule-container').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
                        $('#psp-added-product-container').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');

                        $.ajax({
                            url: psp_ajax_obj.ajax_url,
                            type: 'POST',
                            data: {
                                action: 'psp_delete_product', // AJAX action to delete rule
                                nonce: psp_ajax_obj.psp_search_nonce, // Nonce for security
                                artnos: selectedArtNumbers,
                            },
                            success: function (response) {
                                
                                // Optionally, perform actions after successful deletion
                                refreshRuleList();
                                refreshProductList()
                                selectedArtNumbers = []
                                $productCheckboxes.each(function () {
                                    $(this).prop('checked', false);
                                });
                            },
                            error: function (xhr, textStatus, errorThrown) {
                                console.error('Error: ' + errorThrown);
                                refreshRuleList();
                                refreshProductList()
                                selectedArtNumbers = []

                                // Handle errors gracefully
                            }
                        });
                    });
                    // Handle the cancel button click
                    $('#cancel-delete-btn').off('click').on('click', function () {
                        $('#container-psp-delete-rule-popup').hide();
                    });
                }
            });

        });
    });

    $('#psp-rule-add-btn').click(function () {
        
        $('#container-psp-add-rule-popup').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');

        let retrievedArtno = $('#psp-rule-add-btn').data('artno');
        var ruleId = $(this).data('id');
        refreshEditAddRulePopup(ruleId,retrievedArtno);
        $('#container-psp-add-rule-popup').fadeIn();

        initPspScript();

    });

    $('#add-product-search-input').on('focus', function () {
      
        if($('#add-product-search-input').val() != ''){
        $('#psp-search-results').removeClass('hidden');
    

        }
        
    })

    $('#add-product-search-input').on('input', function () {

        var searchTerm = $(this).val().trim();
        var $resultsContainer = $('#psp-search-results .art-search-item');
        $('#psp-search-results').removeClass('hidden');

        $(document).on('click', function (event) {
            if (!$('#add-product-search-input').is(event.target) && !$resultsContainer.is(event.target) && $resultsContainer.has(event.target).length === 0) {
                // Click was outside the results container
                $('#psp-search-results').addClass('hidden');
            }
        });

        // Show loading spinner or indicator
        $resultsContainer.html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');

        if (searchTerm.length >= 3) {

        // AJAX request
        $.ajax({
            url: psp_ajax_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'psp_get_product_matches_skus', // AJAX action defined in PHP
                search_query: searchTerm, // Corrected to match PHP handler's expectation
                nonce: psp_ajax_obj.psp_search_nonce // Nonce for security
            },
            success: function (response) {
                $resultsContainer.empty();

                if (response.length) {
                    response.forEach(function (prod) {
                        $resultsContainer.append(
                            '<div class="my-1 px-5 py-2 bg-slate-50 rounded-lg hover:bg-slate-300 cursor-pointer art-search-item" data-prodname="' + prod.prodname + '">' +
                            prod.artno +
                            '</div>'
                        );
                    });
                    $('#psp-search-results').removeClass('hidden');
                } else {
                    // Handle case where no results are found
                    $resultsContainer.html('<div class="my-1 px-5 py-2  ">No results found</div>');
                    $('#psp-search-results').removeClass('hidden');
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.error('Error: ' + errorThrown);
                $resultsContainer.html('<div class="my-1 px-5 py-2 text-psp-red ">Error fetching results</div>');
                $('#psp-search-results').removeClass('hidden');
            },
            complete: function () {
                // Hide loading spinner or indicator after AJAX completes (success or error)
                $resultsContainer.find('.loading-spinner').remove(); // Remove spinner if added
            }
        });

    } else {
        // Hide the results container if search term is less than 3 characters
        $resultsContainer.html('<div class="my-1 px-5 py-2 text-psp-grey">Type more than 3 characters</div>');
        
    }

    });

    // Add product card on "Add" button click
    $('#art_add_popup').click(function () {
        var artNo = $('#add-product-search-input').val().trim();
        var prodName = $('#add-product-search-input').data('prodname');
        var messadd = $('#popup-product-card-nodatamsg');
    
        if (artNo && prodName) {
            // Check if a card with the same artNo already exists
            var exists = false;
            $('.product-card-popup-container .prodArtPopup').each(function() {
                if ($(this).text().trim() === artNo) {
                    exists = true;
                    return false; // break out of each loop
                }
            });
    
            if (exists) {
                alert('Product card already added');
                return; // Skip adding the duplicate card
            }
    
            messadd.hide();
            // Create product card HTML
            var productCardHtml = `
                <div class="bg-white p-3 rounded-lg flex justify-between items-center product-card-popup">
                    <div class="w-full flex gap-1">
                        <table class="w-full text-left">
                            <tr class="align-top">
                                <td class="text-black text-start w-[100px]"><strong>Article No</strong></td>
                                <td class="text-psp-blue font-bold flex mr-2 w-[3px]"><strong>:</strong></td>
                                <td class="align-top prodArtPopup w-[72%]">${artNo}</td>
                            </tr>
                            <tr class="align-top">
                                <td class="text-black text-start w-[100px]"><strong>Product Name</strong></td>
                                <td class="text-psp-blue font-bold flex mr-2 w-[3px]"><strong>:</strong></td>
                                <td class="align-top prodNamePopup w-[72%]">${prodName}</td>
                            </tr>
                        </table>
                        <button class="text-psp-red remove-product-card-item">
                            <img class="start-0 !w-[22px] rounded-full p-1 bg-psp-red" src="`+ psp_ajax_obj.plugin_url + `img/MaterialSymbolsCancelRounded.svg" alt="">
                        </button>
                    </div>
                </div>
            `;
    
            // Append product card to container
            $('.product-card-popup-container').append(productCardHtml);
    
            // Clear search input
            $('#add-product-search-input').val('');
            $('#add-product-search-input').data('prodname', ''); // Clear stored data
        }
    });
    


    // Save button functionality
    $('#popipAddProdSaveBTN').click(function () {
        var productArray = [];
        // Show loading text

        var $button = $(this);
        var originalText = $button.text();
       


        // Collect data from each product card
        $('.product-card-popup').each(function () {
            var artNo = $(this).find('.prodArtPopup').text();
            var prodName = $(this).find('.prodNamePopup').text();

            // Push data to array
            productArray.push({
                artNo: artNo,
                prodName: prodName
            });
        });
    
if(productArray.length> 0){
        // Send AJAX request to save data
        $button.prop('disabled', true).css('background-color', '#ccc');
        $.ajax({
            url: psp_ajax_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'psp_save_product_data', // AJAX action defined in PHP
                nonce: psp_ajax_obj.psp_search_nonce, // Nonce for security
                productArray: productArray // Data to be saved
            },
            success: function (response) {
                
                refreshProductList();

                    $button.prop('disabled', false).text(originalText).css('background-color', '');
                    $('.product-card-popup-container').find('.product-card-popup').remove();
            $('#popup-product-card-nodatamsg').show();
            $('#add-product-search-input').val('')
            $('#container-psp-add-product-popup').fadeOut();
               
            },
            error: function (xhr, textStatus, errorThrown) {
                console.error('Error: ' + errorThrown);
                // Handle errors gracefully
                // Change button to indicate error
                $button.text('Error').css('background-color', 'red');

                // Revert button back to original state after 2 seconds
                setTimeout(function () {
                    $button.prop('disabled', false).text(originalText).css('background-color', '');
                }, 2000);
            }
        });}
    });

    // Click handler for added-product-item
    $(document).on('click', '.added-product-item', function () {

        let artno = $(this).data('artno');
       

        var setStatus = (status, element) => {

            ptStatus = status;
            $('#all').removeClass("bg-psp-blue text-[#FFFFFF]");
            $('#active').removeClass("bg-psp-blue text-[#FFFFFF]");
            $('#inactive').removeClass("bg-psp-red text-[#FFFFFF]");

            if (status === "all") {
                $('#all').addClass("bg-psp-blue text-[#FFFFFF]");
            } else if (status === "active") {
                $('#active').addClass("bg-psp-blue text-[#FFFFFF]");
            } else if (status === "inactive") {
                $('#inactive').addClass("bg-psp-red text-[#FFFFFF]");
            }

            var artno = $('#psp-rule-add-btn').data('artno');
       
            //refreshRuleList(artNo,status);
              // prasanth Filter the elements based on the selected status
         let hasVisibleElements = false;
            $('.prodaddedrull').each(function() {
                var element = $(this);
                if (status === "all") {
                    element.show(); // Show all elements
                    hasVisibleElements = true;
                } else if (status === "active" && element.hasClass('active')) {
                    element.show(); // Show only active elements
                    hasVisibleElements = true;
                } else if (status === "inactive" && element.hasClass('inactive')) {
                    element.show(); // Show only inactive elements
                    hasVisibleElements = true;
                } else {
                    element.hide(); // Hide the element if it doesn't match the selected status
                }
            });

            // If no elements are visible, show the "No Rule Available" message
            if (!hasVisibleElements) {
                $('.statusMessage').show();
                $('.statustxtpsp').text(" "+status); // Update the status text in the message
            } else {
                $('.statusMessage').hide();
            }
            

        }

        var artNo = $(this).data('artno');

        var wasActive = $(this).hasClass('active');

        // Remove 'active' class from all elements
        $('.added-product-item').removeClass('active');
    
        // Toggle 'active' class on the clicked element
        if (!wasActive) {
            $(this).addClass('active');
        }
    
        // Check if the clicked element does not have the 'active' class
        if (!$(this).hasClass('active')) {
            // Perform additional actions when the 'active' class is not present
            $('#psp-rule-add-btn').hide();
            $('#psp-rule-add-btn').data('artno', '');
            $('.psp-prod-status-selector-container').hide();
        
    
            $('#psp_edit_edit_button').data('editurl', '');
            $('#psp_edit_edit_button').removeClass('text-white')
                                       .addClass('text-gray-400');

                                       artNo = false
        } else {
            // Perform additional actions when the 'active' class is present
            
            const activeProductItem = document.querySelector('.added-product-item.active');
            const artNo = activeProductItem.getAttribute('data-artno');
         
        
            $('#psp-rule-add-btn').data('artno', artno);
            $('#psp-rule-add-btn').show();
        //    $('#psp-rule-add-btn').data('artno', $(this).data('artno'));
            $('.psp-prod-status-selector-container').show();
   
    
            var editurl = $(this).data('editurl');
            $('#psp_edit_edit_button').data('editurl', editurl);
            $('#psp_edit_edit_button').removeClass('text-gray-400')
                                       .addClass('text-white');
        }
        let ptStatus = "all";
        setStatus(ptStatus, $('#all'));
        $('.psp-prod-status-selector').on('click', function() {
            const status = $(this).attr('id');
            setStatus(status, this);
        });
        
        
        $('#psp-added-rule-container').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');


        // AJAX request to fetch data for the selected artNo
        $.ajax({
            url: psp_ajax_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'psp_fetch_product_data', // AJAX action defined in PHP
                nonce: psp_ajax_obj.psp_search_nonce, // Nonce for security
                artNo: artNo // Art number to fetch data for
            },
            success: function (response) {
                if(artNo){
                refreshRuleList(artNo)}else{
                    refreshRuleList()
                }
                // Optionally, update UI with fetched data
            },
            error: function (xhr, textStatus, errorThrown) {
                console.error('Error fetching product data:', errorThrown);
            }
        });

        initPspScript();
    });

    $(document).ready(function() {
        // Enable the input field when the document is fully loaded
        $('#psp-product-default-search').prop('disabled', false).css('cursor', 'text');
    });

    $('#psp-product-default-search').on('input change paste', function () {
        var searchTerm = $(this).val().trim();
        var $noProductMessage = $('#no-product-message');
        var $searchTermSpan = $('#search-term');

        
        var $products = $('#psp-added-product-container .flex.gap-2.items-center');

        var visibleProductCount = 0;
        // Filter elements
        $products.each(function() {
            var $product = $(this);
            var productText = $product.text().toLowerCase();
            if (productText.includes(searchTerm)) {
                $product.show();
                visibleProductCount++;
            } else {
                $product.hide();
            }

            if (visibleProductCount === 0) {
                $searchTermSpan.text(searchTerm);
                $noProductMessage.show();
            } else {
                $noProductMessage.hide();
            }
        });

    });

    $(document).on('click', '.edit-rule-btn', function () {
        $('#container-psp-add-rule-popup').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
       let retrievedArtno = $('#psp-rule-add-btn').data('artno');
     
        var ruleId = $(this).data('id');
        refreshEditAddRulePopup(ruleId,retrievedArtno);
        $('#container-psp-add-rule-popup').fadeIn();
        initPspScript();
    });

    // Function to refresh the product list
    function refreshProductList(searchterm = '') {
        $('#psp-added-product-container').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');

        $.ajax({
            url: psp_ajax_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'psp_refresh_product_list', // AJAX action to refresh product list
                nonce: psp_ajax_obj.psp_search_nonce, // Nonce for security
                searchterm: searchterm
            },
            success: function (response) {
                
                $('#psp-added-product-container').html(response.data);
                $('#psp_edit_edit_button').removeClass('text-white')
                $('#psp_edit_edit_button').addClass('text-gray-400')
                $('#psp_edit_edit_button').data('editurl', '')
                initPspScript()
                $('.psp_product_checkbox').prop('checked', false);
                $(document).ready(function () {
                    let $selectAllCheckbox = $('#psp_select_all');
                    let $productCheckboxes = $('.psp_product_checkbox');
                    let $productCheckboxesContainer = $('.psp-prod-checkbox-item-container');
            
                    let $deleteButton = $('#psp_delete_button');
            
                    // Select All functionality
                    $selectAllCheckbox.on('change', function () {
                        $productCheckboxes.prop('checked', this.checked);
                    });

                        // Update Select All checkbox based on individual checkboxes
    $productCheckboxes.on('change', function () {
        if ($productCheckboxes.length === $productCheckboxes.filter(':checked').length) {
            $selectAllCheckbox.prop('checked', true);
        } else {
            $selectAllCheckbox.prop('checked', false);
        }
    });
            
                    // Delete button click event
                    $deleteButton.on('click', function () {
            
                       
                        var selectedArtNumbers = [];
                        $productCheckboxes.each(function () {
                            if ($(this).is(':checked')) {
                                selectedArtNumbers.push($(this).val());
                            }
                        });

                         // Collect artno from active added-product-item elements
        $('.added-product-item.active').each(function () {
            selectedArtNumbers.push($(this).data('artno'));
        });
                        $productCheckboxesContainer.each(function () {
                            if ($(this) && selectedArtNumbers.length == 0) {
                                $(this).toggleClass('hidden')
                            } else {
                                // Set the rule description in the popup
                                if (selectedArtNumbers.length == 1) {
                                    $('#rule-description').text('Article Number : ' + selectedArtNumbers[0]);
                                } else {
                                    $('#rule-description').text('Selected Products');
                                }
                                // Show the popup
                                $('#container-psp-delete-rule-popup').show();
            
                                // Handle the confirmation button click
                                $('#confirm-delete-btn').off('click').on('click', function () {
                                    $('#container-psp-delete-rule-popup').hide();
                                    $('#psp-added-rule-container').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
                                    $('#psp-added-product-container').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
            
                                    $.ajax({
                                        url: psp_ajax_obj.ajax_url,
                                        type: 'POST',
                                        data: {
                                            action: 'psp_delete_product', // AJAX action to delete rule
                                            nonce: psp_ajax_obj.psp_search_nonce, // Nonce for security
                                            artnos: selectedArtNumbers,
                                        },
                                        success: function (response) {
                                            
                                            // Optionally, perform actions after successful deletion
                                            refreshRuleList();
                                            refreshProductList()
                                            selectedArtNumbers = []
                                            $productCheckboxes.each(function () {
                                                $(this).prop('checked', false);
                                            });
                                        },
                                        error: function (xhr, textStatus, errorThrown) {
                                            console.error('Error: ' + errorThrown);
                                            refreshRuleList();
                                            refreshProductList()
                                            selectedArtNumbers = []
            
                                            // Handle errors gracefully
                                        }
                                    });
                                });
                                // Handle the cancel button click
                                $('#cancel-delete-btn').off('click').on('click', function () {
                                    $('#container-psp-delete-rule-popup').hide();
                                });
                            }
                        });
            
                    });
                });
            },
            error: function (xhr, textStatus, errorThrown) {
                console.error('Error: ' + errorThrown);
                // Handle errors gracefully
            }
        });
    }
    function getDataUserIdFromDivWithoutDisabledButton() {

        const divs = document.querySelectorAll('.all-users-by-role');
        const results = [];
        let countWithoutDisabled = 0;

        divs.forEach(div => {
            const button = div.querySelector('button');
            
            if (button && !button.classList.contains('disabled')) {
                const userId = div.getAttribute('data-userid');
                results.push(userId);
                countWithoutDisabled++;
            }
        });

        return {
            totalDivCount: divs.length,
            countWithoutDisabled,
            results
        };
    }
    
    
    
    function refreshEditAddRulePopup(id = '',artno='') {
        
        $.ajax({
            url: psp_ajax_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'psp_refresh_EditAddRulePopup', // AJAX action to refresh product list
                nonce: psp_ajax_obj.psp_search_nonce, // Nonce for security
                id: id,
                artno:artno
            },
            success: function (response) {
                
                $('#container-psp-add-rule-popup').html(response.data.content);

                initPspScript()

              $('.select-all-user-btn').click(function (event) { 
                event.stopPropagation();
                console.log("Yesss");
                    var selectAllCheckbox = $('#select-all-users');
                    var isChecked = selectAllCheckbox.prop('checked');
                    selectAllCheckbox.prop('checked', !isChecked);

                    $('.all-users-by-role').each(function() {
                        var $this = $(this);
                        var hasEnabledButton = $this.find('button').not('.disabled').length > 0;
                    if (hasEnabledButton) {
                    if (isChecked) {
                        $this.addClass('user-popup-card');
                        $this.find('.checked-icon-psp').css('visibility', 'visible');
                        $('.checked-icon-psp-all').css('visibility', 'visible');
                    } else {
                            $this.removeClass('user-popup-card');
                            $this.find('.checked-icon-psp').css('visibility', 'hidden');
                            $('.checked-icon-psp-all').css('visibility', 'hidden');
                        }   
                }
            });
        });

            
                        // Save button functionality
        $('#popipAddRuleSaveBTN').click(function () {
            let result=getDataUserIdFromDivWithoutDisabledButton();
            let countWithoutDisable=result.countWithoutDisabled;
            let notDisabled=result.results;
            notDisabled= notDisabled.map(value => parseInt(value, 10));
            let totalDiv=result.totalDivCount;

            var $button = $(this);
            var originalText = $button.text();
            $button.prop('disabled', true).css('background-color', '#ccc');

            $('#psp-added-rule-container').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');


            var users = [];

            var qty = $('#psp-qty-default').val()
            var price = $('#psp-price-default').val()
            var selectAllCheckboxval = $('#select-all-users').prop('checked');
            var statusCheckboxval = $('#psp-toggle-btn').prop('checked');
         

            var artNo = $('#psp-rule-add-btn').data('artno')
            var verifyEdit = $('#edit-rule-verify').val()
            var role = $('#psp-role-default').val()

            // Collect data from each product card
            $('.user-popup-card').each(function () {

                var userid = $(this).data('userid')
                // Push data to array
                users.push(userid);
            });

            users = users.filter(value => notDisabled.includes(value));
            
            if(users.length===totalDiv){
                selectAllCheckboxval=1;
            }
            else{
                selectAllCheckboxval=0;
            }
  
            data = {
                action: 'psp_save_stacking_rule',
                nonce: psp_ajax_obj.psp_search_nonce,
                artNo: artNo,
                users: users,
                qty: qty,
                price: price,
                role: role,
                id: verifyEdit,
                selectAllCheckboxval:selectAllCheckboxval,
                status:statusCheckboxval
            }
            console.log(data);
            
            // Send AJAX request to save data
            $.ajax({
                url: psp_ajax_obj.ajax_url,
                type: 'POST',
                data: data,
                success: function (response) {
                   
                    if (response.success) {
                        
                        $('#error-message-psp-rule-popup')
                            .text(response.data)
                            .css('color', 'green');

            $('#container-psp-add-rule-popup').fadeOut();

                    } else {
                        $('#error-message-psp-rule-popup')
                            .text(response.data)
                            .css('color', 'red');
                        console.error('Data save failed', response.data);
                    }
                    
                    refreshRuleList(artNo)
                    
                        $button.prop('disabled', false).text(originalText).css('background-color', '');
                   
                    $('.all-users-by-role').on('click', function () {
                    
                        const button = $(this).find('button');
                        if (button.hasClass('disabled')) {
                            return;
                         
                        }
                        if (!button.hasClass('disabled')) {
                            $(this).toggleClass('user-popup-card');
                        }
                      

                        // Check if all elements have the 'user-popup-card' class
                        var allSelected = $('.all-users-by-role').length === $('.all-users-by-role.user-popup-card').length;
                        $(this).find('.checked-icon-psp').css('visibility', function(i, visibility) {
    return visibility === 'visible' ? 'hidden' : 'visible';
});

                        // Update the #select-all-users checkboxs
                        $('#select-all-users').prop('checked', allSelected);
                        if(allSelected){
                            $('.checked-icon-psp-all').css('visibility', 'visible');
                        }else{
                            $('.checked-icon-psp-all').css('visibility', 'hidden');
            
                        }

                    });

                    $('#select-all-users').change(function () {
                        
                        if (this.checked) {
                            $('.all-users-by-role').addClass('user-popup-card');
                            $('.all-users-by-role').find('.checked-icon-psp').css('visibility', 'visible');
                            $('.checked-icon-psp-all').css('visibility', 'visible');

                            // Add code here to select all users and add them to the container if needed
                        } else {
                            $('.all-users-by-role').removeClass('user-popup-card');
                            $('.all-users-by-role').find('.checked-icon-psp').css('visibility', 'hidden');
                            $('.checked-icon-psp-all').css('visibility', 'hidden');


                        }
                    
                    });


                },
                error: function (xhr, textStatus, errorThrown) {
                    refreshRuleList(artNo)
                    console.error('Error: ' + errorThrown);
                    $button.prop('disabled', false).text(originalText).css('background-color', '');

                }
            });



            // Optionally, you can perform further actions with productArray (e.g., send it via AJAX)
        });

            },
            error: function (xhr, textStatus, errorThrown) {
                console.error('Error: ' + errorThrown);
                // Handle errors gracefully
            }
        });
    }

    // Function to refresh the product list
    function refreshRuleList(artNo = '',status='all') {
        $.ajax({
            url: psp_ajax_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'psp_refresh_rule_list', // AJAX action to refresh product list
                nonce: psp_ajax_obj.psp_search_nonce, // Nonce for security
                artNo: artNo,
                status:status
            },
            success: function (response) {
               
                $('#psp-added-rule-container').html(response.data);

                initPspScript()
            },
            error: function (xhr, textStatus, errorThrown) {
                console.error('Error: ' + errorThrown);
                // Handle errors gracefully
            }
        });
    }

});
