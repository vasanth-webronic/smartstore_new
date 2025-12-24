jQuery(document).ready(function ($) {

    $('#main_select').on('change', function () {
        const selectedValue = $(this).val();

        if (selectedValue === 'category') {
            $('#seg').show(); // Show category section
            $('#artseg').hide(); // Hide product section
            //$('#custom-uam-alert-add-edit-dlg').hide(); // Hide Add Article Number form
        } else if (selectedValue === 'product') {
            $('#seg').hide(); // Hide category section
            $('#artseg').show(); // Show product section
            //$('#custom-uam-alert-add-edit-dlg').show(); // Show Add Article Number form
        } else {
            $('#seg').hide(); // Hide all sections
            $('#artseg').hide();
            $('#custom-uam-alert-add-edit-dlg').hide();
        }
    });

    // Categorywise function

    // Handle toggle functionality
    $(document).on('click', '.toggle-icon', function () {
        const targetId = $(this).data('target'); // Get target ID
        const targetElement = $('#' + targetId); // Select the target UL element

        if (targetElement.is(':visible')) {
            targetElement.slideUp(); // Hide the list
            $(this).text('+'); // Change the icon to +
        } else {
            targetElement.slideDown(); // Show the list
            $(this).text('-'); // Change the icon to -
        }
    });

     // Handle form submission for category selection
     $('#seg_form').on('submit', function (event) {
        event.preventDefault();

        const category = $('#cate_no').val();
        $('#loader').show(); // Show loader
        $('#seg_results').hide(); // Hide results container during loading

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'get_attr_category_segment_data', // Action hook for PHP function
                cate_no: category,
            },
            success: function (response) {
                if (response.success) {
                    $('#seg_results').html(response.data.html); // Inject HTML response
                    $('#loader').hide();
                    $('#seg_results').show();
                    initSortable(); // Initialize sortable functionality
                } else {
                    alert(response.data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert('Something category went wrong. Please try again.');
                $('#loader').hide();
            },
        });
    });

    // Initialize SortableJS for dynamically loaded elements
    function initSortable() {
        new Sortable(document.getElementById('product_features'), { animation: 150 });
        new Sortable(document.getElementById('technical_specifications'), { animation: 150 });
        new Sortable(document.getElementById('weight_volume'), { animation: 150 });
        new Sortable(document.getElementById('weight_capacity'), { animation: 150 });

        // Save reordered values
        $('#save_order').on('click', function () {
            const productFeaturesOrder = [];
            const technicalSpecificationsOrder = [];
            const weightVolumeOrder = [];
            const weightCapacityOrder = [];

            // Collect the new order of Product Features
            $('#product_features li').each(function () {
                productFeaturesOrder.push({ id: $(this).data('id') }); // Collect only the ID
            });

            // Collect the new order of Technical Specifications
            $('#technical_specifications li').each(function () {
                technicalSpecificationsOrder.push({ id: $(this).data('id') });
            });

            // Collect the new order of Weight Volume
            $('#weight_volume li').each(function () {
                weightVolumeOrder.push({ id: $(this).data('id') });
            });

            // Collect the new order of Weight Capacity
            $('#weight_capacity li').each(function () {
                weightCapacityOrder.push({ id: $(this).data('id') });
            });

            // AJAX call to save the new order
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'save_reordered_attributes',
                    cate_no: $('#cate_no').val(),
                    product_features: productFeaturesOrder,
                    technical_specifications: technicalSpecificationsOrder,
                    weight_volume: weightVolumeOrder,
                    weight_capacity: weightCapacityOrder,
                },
                success: function (response) {
                    if (response.success) {
                        alert('Order saved successfully!');
                    } else {
                        alert('Failed to save order: ' + response.data.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Something save went wrong. Please try again.');
                },
            });
        });
    }

    // Productwise function

    const artnoList = []; // Array to store the added artno values

    // Add Artno to the List
    $('#segadd-artno-btn').on('click', function () {
        const artno = $('#custom-uam-input-seg-artno').val().trim();
        if (!artno) {
            alert('Please enter a valid Article Number.');
            return;
        }

        if (artnoList.includes(artno)) {
            alert('This Article Number is already added.');
            return;
        }

        artnoList.push(artno);

        // Append the artno to the list container
        $('#artno-list-container').append(`
            <div class="artno-item" style="padding: 10px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong>Article Number:</strong> ${artno}
                </div>
                <img src="https://smartstoring.eu/wp-content/plugins/thingsatweb/img/close-circle.png" 
                    class="remove-artno-btn" data-artno="${artno}" alt="Delete" style="cursor: pointer; margin-left: 10px; width: 20px; height: 20px;" />            
            </div>
        `);

        // Clear the input field
        $('#custom-uam-input-seg-artno').val('');
    });

    // Remove Artno from the List
    $(document).on('click', '.remove-artno-btn', function () {
        const artnoToRemove = $(this).data('artno');

        // Remove from array
        const index = artnoList.indexOf(artnoToRemove);
        if (index > -1) {
            artnoList.splice(index, 1);
        }

        // Remove the item from the DOM
        $(this).closest('.artno-item').remove();
    });

    $(document).on('click', '.arttoggle-icon', function () {
        const targetId = $(this).data('target'); // Get target ID
        const targetElement = $('#' + targetId); // Select the target UL element

        if (targetElement.is(':visible')) {
            targetElement.slideUp(); // Hide the list
            $(this).text('+'); // Change the icon to +
        } else {
            targetElement.slideDown(); // Show the list
            $(this).text('-'); // Change the icon to -
        }
    });

   

    // Handle form submission for category selection
    $('#artseg_form').on('submit', function (event) {
        event.preventDefault();
        console.log('works');

        const artno = $('#art_no').val();
        $('#artloader').show(); // Show artloader
        $('#artseg_results').hide(); // Hide results container during loading

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'get_attr_product_segment_data', // Action hook for PHP function
                art_no: artno,
            },
            success: function (response) {
                if (response.success) {
                    $('#artseg_results').html(response.data.html); // Inject HTML response
                    $('#artloader').hide();
                    $('#artseg_results').show();
                    initartSortable(); // Initialize sortable functionality
                } else {
                    alert(response.data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('XHR Response:', xhr.responseText); // Log full response
                console.error('Error Status:', status, 'Error:', error);
                alert(xhr.responseText || 'Something artno went wrong. Please try again.');
                $('#artloader').hide();
            },
        });
    });

    function initartSortable() {
        new Sortable(document.getElementById('artproduct_features'), { animation: 150 });
        new Sortable(document.getElementById('arttechnical_specifications'), { animation: 150 });
        new Sortable(document.getElementById('artweight_volume'), { animation: 150 });
        new Sortable(document.getElementById('artweight_capacity'), { animation: 150 });

        // Save reordered values
        $('#save_artorder').on('click', function () {
            const productFeaturesOrder = [];
            const technicalSpecificationsOrder = [];
            const weightVolumeOrder = [];
            const weightCapacityOrder = [];

            // Collect the new order of Product Features
            $('#artproduct_features li').each(function () {
                productFeaturesOrder.push({ id: $(this).data('id') }); // Collect only the ID
            });

            // Collect the new order of Technical Specifications
            $('#arttechnical_specifications li').each(function () {
                technicalSpecificationsOrder.push({ id: $(this).data('id') });
            });

            // Collect the new order of Weight Volume
            $('#artweight_volume li').each(function () {
                weightVolumeOrder.push({ id: $(this).data('id') });
            });

            // Collect the new order of Weight Capacity
            $('#artweight_capacity li').each(function () {
                weightCapacityOrder.push({ id: $(this).data('id') });
            });

            // AJAX call to save the new order
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'save_reordered_art_attributes',
                    art_no: $('#art_no').val(),
                    product_features: productFeaturesOrder,
                    technical_specifications: technicalSpecificationsOrder,
                    weight_volume: weightVolumeOrder,
                    weight_capacity: weightCapacityOrder,
                },
                success: function (response) {
                    if (response.success) {
                        alert('Order saved successfully!');
                    } else {
                        alert('Failed to save order: ' + response.data.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Something save went wrong. Please try again.');
                },
            });
        });
    }

    $(document).on('click', '#delete-artbutton', function () {
        console.log('Delete button clicked'); // Debug to check if the event is triggered
    
        const artNo = $('#artheading').text().trim(); // Get the article number from the heading
        
        if (!artNo) {
            alert('No article number found to delete.');
            return;
        }
    
        if (!confirm('Are you sure you want to delete this article and its attributes?')) {
            return;
        }
    
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'delete_art_attributes',
                art_no: artNo
            },
            success: function (response) {
                if (response.success) {
                    alert('Article attributes deleted successfully!');
                    location.reload(); // Reload or update UI
                } else {
                    alert('Failed to delete: ' + response.data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert('Something went wrong. Please try again.');
            },
        });
    });

    $('#custom-uam-input-seg-artno').on('input', function () {
        const searchTerm = $(this).val().trim();
        const $resultsContainer = $('#seg-search-results');
        var remainingChars = 3 - searchTerm.length;

        // Show message if less than 3 characters entered
        if (searchTerm === "") {
            $('#rseg-search-results').hide();
            $resultsContainer.html('<div class="seg-no-results">Please enter 3 or more characters</div>');
            return;
        }else if (remainingChars > 0) {
            $resultsContainer.html('<div class="seg-no-results">Please enter ' + remainingChars + ' or more characters</div>').show();
            return;
        }

        // Show loading spinner
        $resultsContainer.html('<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> Searching...</div>').show();

        // Perform AJAX request to fetch SKUs
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'restrict_get_product_matches_skus', // Corresponding PHP function
                search_query: searchTerm,
            },
            success: function (response) {
                $resultsContainer.empty(); // Clear previous results
                if (response.length) {
                    response.forEach(function (sku) {
                        $resultsContainer.append(
                            '<div class="search-item" data-artno="' + sku + '">' + sku + '</div>'
                        );
                    });
                } else {
                    $resultsContainer.html('<div class="seg-no-results">No results found</div>');
                }
            },
            error: function () {
                $resultsContainer.html('<div class="error-message">Error fetching results. Please try again.</div>');
            },
        });
    });

    // Handle selection of an SKU
    $(document).on('click', '.search-item', function () {
        const selectedArtNo = $(this).data('artno');
        $('#custom-uam-input-seg-artno').val(selectedArtNo); // Set input value
        $('#seg-search-results').hide(); // Hide the search dropdown
    });

    // Submit the List of Artno to the Database
    $('#seg-artno-btn').on('click', function () {
        if (artnoList.length === 0) {
            alert('No Article Numbers to submit.');
            return;
        }

        // Perform AJAX request to submit the data
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'save_artno_to_database', // Hook for your PHP function
                artnos: artnoList,
            },
            success: function (response) {
                if (response.success) {
                    //alert('Article Numbers successfully added to the database.');
                    $('#artno-list-container').empty(); // Clear the list container
                    artnoList.length = 0; // Clear the array
                    tb_remove();
                } else {
                    alert('Failed to save Article Numbers: ' + response.data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert('An error occurred while saving the data. Please try again.');
            },
        });
    });



//Tab

    $('#segroles-tab').on('click', function(e) {
        e.preventDefault();
        $('#segroles-tab').addClass('navdd-tab-active').attr('aria-current', 'page');
        $('#segusers-tab').removeClass('navdd-tab-active').removeAttr('aria-current');

        $('#segroles-content').show();
        $('#segusers-content').hide();
        $('#right-segroles-content').show();
        $('#right-segusers-content').hide();
        $('#seguser-search').hide();

        // Trigger click event on the first role list item and mark it as active
        var firstRoleItem = $(".custom_segrestrict_ls li:first");
        firstRoleItem.addClass('active'); // Add active class
        //custom_uam_update_segrestrictcapabilities(firstRoleItem[0]);
    });

    $('#segusers-tab').on('click', function(e) {
        e.preventDefault();
        $('#segusers-tab').addClass('navdd-tab-active').attr('aria-current', 'page');
        $('#segroles-tab').removeClass('navdd-tab-active').removeAttr('aria-current');

        $('#segroles-content').hide();
        $('#segusers-content').show();
        $('#right-segroles-content').hide();
        $('#right-segusers-content').show();
        $('#seguser-search').show();

        // Remove active class from all user list items
        var userItems = $('.custom-uam-segrestrictproductuser-li');
        userItems.removeClass('active');

        // Add active class to the first user list item
        if (userItems.length > 0) {
            var firstUserItem = $(".custom_segrestrictuser_ls li:first");
            firstUserItem.addClass('active'); // Add active class
            // custom_uam_update_restrictusercapabilities(firstUserItem[0]); // Call the function with the first user item
        }
    });

//Search

    // Roles product Search
    $('#segroleproduct-search').on('input', function() {
        var searchTerm = $(this).val().toLowerCase();
        console.log('rolesearch:', searchTerm);
    
        var noResults = true;
    
        // Loop through each row in the table to check for a match
        $('#c_uam_segcaprole_ul table tbody tr').each(function() {
            var attributeValue = $(this).find('td').eq(0).text().toLowerCase(); // Get the text from the first <td> (the 'attribute' column)
            console.log('attributeValue:', attributeValue);
    
            // Check if the attribute value contains the search term
            if (attributeValue.includes(searchTerm)) {
                console.log('rolesearchattributeValue:', attributeValue);
                $(this).show();  // Show row if it matches
                noResults = false;
            } else {
                $(this).hide();  // Hide row if it doesn't match
            }
        });
    
        // Show "No results found" message if no results were found
        if (noResults) {
            $('#segno-results').show();
            $('#c_uam_segcaprole_ul thead').hide(); 
        } else {
            $('#segno-results').hide();
            $('#c_uam_segcaprole_ul thead').show(); 
        }
    });


    $("body").on("click", ".custom_segrestrict_ls .custom-uam-segrestrictproduct-li", function(event) {
        // Remove active class from all role items
        $(".custom_segrestrict_ls .custom-uam-segrestrictproduct-li").removeClass("active");

        // Add active class to the clicked role item
        $(this).addClass("active");
        // Update the capabilities based on the clicked role
        //custom_uam_update_segrestrictcapabilities(this);

    });

    // Trigger click event on the first role list item and mark it as active
    // var firstRoleItem = $(".custom_segrestrict_ls li:first");
    // firstRoleItem.addClass('active'); // Add active class
    //custom_uam_update_segrestrictcapabilities(firstRoleItem[0]);
    $(document).ready(function() {
        // Trigger click on the first item in the list if it's not already active
        var firstRoleItem = $(".custom_segrestrict_ls li:first");
        if (!firstRoleItem.hasClass('active')) {
            firstRoleItem.addClass('active'); // Add active class
            // Optionally trigger any functionality you need here, like fetching data
            // custom_uam_update_segrestrictcapabilities(firstRoleItem[0]);
        }
    
        // Optional: Simulate the click on the first item (to show content)
        $(".custom_segrestrict_ls .custom-uam-segrestrictproduct-li.active").trigger('click');
    });

    // $("body").on("click", ".custom-uam-segrestrictproduct-li", function () {
    //     // Remove 'active' class from all items and add it to the clicked one
    //     $(".custom-uam-segrestrictproduct-li").removeClass("active");
    //     $(this).addClass("active");
    
    //     // Fetch the selected role/heading
    //     var selectedHeading = $(this).data("role");
    //     console.log("Selected heading: ", selectedHeading);
    
    //     // Clear existing data and show loader
    //     $("#c_uam_segcaprole_ul").empty();
    //     $("#loaderhid").show();
    //     $("#segno-results").hide();
    
    //     // Make AJAX call to fetch attribute values
    //     $.ajax({
    //         url: ajaxurl, // WordPress AJAX handler
    //         method: "POST",
    //         data: {
    //             action: "fetch_attribute_values", // Action name
    //             heading: selectedHeading // Pass the selected heading to the backend
    //         },
    //         success: function (response) {
    //             console.log("Response: ", response);
    
    //             // Hide the loader
    //             $("#loaderhid").hide();
    
    //             if (response.success && response.data.length > 0) {
    //                 let tableHtml = '<div style="overflow-y: auto; max-height: 520px; border: 1px solid #ccc; border-radius: 4px;">';
    //                 tableHtml += '<table style="width: 100%; border-collapse: collapse; background-color: #fff;">';
    //                 tableHtml += '<thead>';
    //                 tableHtml += '<tr style="background-color: #f4f4f4; text-align: left; border-bottom: 1px solid #ccc;">';
    //                 tableHtml += '<th style="padding: 15px; width: 25%; border-right: 1px solid #ccc; font-size:14px;">ATTRIBUTE</th>';
    //                 tableHtml += '<th style="padding: 15px; width: 25%; border-right: 1px solid #ccc; text-align: center;">DATASHEET</th>';
    //                 tableHtml += '<th style="padding: 15px; width: 25%; border-right: 1px solid #ccc; text-align: center;">PRODUCT PAGE</th>';
    //                 tableHtml += '<th style="padding: 15px; width: 25%; border-right: 1px solid #ccc; text-align: center;"></th>';
    //                 tableHtml += '</tr>';
    //                 tableHtml += '</thead>';
    //                 tableHtml += '<tbody>';
    
    //                 response.data.forEach(function (value) {
    //                     tableHtml += '<tr style="border-bottom: 1px solid #ccc;">';
    //                     tableHtml += '<td style="padding: 15px; border-right: 1px solid #ccc; font-weight: medium;">' + value.attribute + '</td>';
    //                     tableHtml += '<td style="padding: 15px; text-align: center;border-right: 1px solid #ccc;">';
    //                     tableHtml += '<img src="' + value.attr_imgurl + '" alt="Datasheet Image" style="width: 40px; height: 40px; cursor: pointer;" />';
    //                     tableHtml += '</td>';
    //                     tableHtml += '<td style="padding: 15px; text-align: center; border-right: 1px solid #ccc;">';
    //                     tableHtml += '<img src="' + value.product_imgurl + '" alt="ProductPage Image" style="width: 40px; height: 40px; cursor: pointer;" />';
    //                     tableHtml += '</td>';
    //                     tableHtml += '<td style="padding: 15px; text-align: center; ">';
    //                     tableHtml += '<a href="#TB_inline?&width=260&height=310&inlineId=custom-uam-alert-diagramadd-edit-dlg" ';
    //                     tableHtml += 'title="Edit Attribute" class="thickbox" style="color: #007bff; text-decoration: underline; margin-right: 10px;">Edit</a>';
    //                     tableHtml += ' | ';
    //                     tableHtml += '<button class="delete-btn" style="background: none; border: none; color: #dc3545; text-decoration: underline;">Delete</button>';
    //                     tableHtml += '</td>';
    //                     tableHtml += '</tr>';
    //                 });
    
    //                 tableHtml += '</tbody>';
    //                 tableHtml += '</table>';
    //                 tableHtml += '</div>';
    //                 $("#c_uam_segcaprole_ul").html(tableHtml);
    //             } else {
    //                 // Show 'No results found' message
    //                 $("#segno-results").show();
    //             }
    //         },
    //         error: function () {
    //             console.error("Failed to fetch data");
    //             $("#loaderhid").hide();
    //             $("#segno-results").show();
    //         }
    //     });
    // });
    $("body").on("click", ".custom-uam-segrestrictproduct-li", function () {
        // Remove 'active' class from all items and add it to the clicked one
        $(".custom-uam-segrestrictproduct-li").removeClass("active");
        $(this).addClass("active");
    
        // Fetch the selected role/heading
        var selectedHeading = $(this).data("role");
        console.log("Selected heading: ", selectedHeading);
    
        // Clear existing data and show loader
        $("#c_uam_segcaprole_ul").empty();
        $("#loaderhid").show();
        $("#segno-results").hide();
    
        // Make AJAX call to fetch attribute values
        $.ajax({
            url: ajaxurl, // WordPress AJAX handler
            method: "POST",
            data: {
                action: "fetch_attribute_values", // Action name
                heading: selectedHeading // Pass the selected heading to the backend
            },
            success: function (response) {
                console.log("Response: ", response);
    
                // Hide the loader
                $("#loaderhid").hide();
    
                if (response.success && response.data.length > 0) {
                    let collapsibleHtml = '';
    
                    response.data.forEach(function (value, index) {
                        collapsibleHtml += `
                        
                            <div class="attribute-row" data-id="${value.id}" style="border: 1px solid #ccc; border-radius: 5px; margin-bottom: 10px; background-color: #fff; position: relative; padding: 10px;">
                                <div class="attribute-header" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                                    <div style="display: flex; align-items: center; flex: 1;">
                                        <!-- Toggle Icon -->
                                        <span class="togglecontent-icon" style="font-size: 20px; margin-right: 10px;">&#9654;</span>

                                        <!-- Attribute Name -->
                                        <span style="font-weight: bold; min-width: 150px; margin-right: 10px;">${value.attribute}</span>

                                        <!-- Datasheet Image -->
                                        <img src="${value.attr_imgurl}" alt="Datasheet Image" style="width: 50px; height: 50px;  border-radius: 5px; margin-right: 70px;">

                                        <!-- Product Page Image -->
                                        <img src="${value.attr_imgurl}" alt="Product Page Image" style="width: 50px; height: 50px;  border-radius: 5px; margin-right:70px;">
                                    </div>

                                    <!-- Action Buttons -->
                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        <!--<button class="add-btn" style="background: none; border: none; color: #007bff; font-size: 16px;">+</button>  -->
                                        <a href="#TB_inline?&width=400&height=400&inlineId=custom-uam-subattribute-add-dlg" title="Add Attribute" class="thickbox subadd add-btn" style="color: #007bff; text-decoration: underline;">+</a>
                                        <a href="#TB_inline?&width=260&height=310&inlineId=custom-uam-alert-diagramadd-edit-dlg" title="Edit Attribute" class="thickbox" style="color: #007bff; text-decoration: underline;">✎</a>
                                        <!-- <button class="delete-btn" style="background: none; border: none; color: #dc3545; font-size: 16px;">🗑️</button> -->
                                    </div>
                                </div>

                                <!-- Collapsible Content -->
                                <div class="attribute-content" style="display: none; padding: 10px; margin-top:20px; background-color: #f9f9f9; border: 1px solid #ccc;">
                                    <!-- Additional dynamic content -->
                                </div>
                            </div>`;
                    });
    
                    $("#c_uam_segcaprole_ul").html(collapsibleHtml);
    
                    // Toggle functionality
                    $(".togglecontent-icon").on("click", function () {
                        const content = $(this).closest(".attribute-row").find(".attribute-content");
                        const icon = $(this);
                        const parentattributeId = $(this).closest(".attribute-row").data("id");

                        if (content.is(":visible")) {
                            content.slideUp(); // Collapse content
                            icon.html("&#9654;"); // Right arrow
                        } else {
                            content.slideDown(); // Expand content
                            icon.html("&#9660;"); // Down arrow
                            fetchAndDisplaySubattributes(content, parentattributeId); // Fetch subattributes
                        }
                    });
                        
                    // Add functionality
                    $(".add-btn").on("click", function () {
                        const content = $(this).closest(".attribute-row").find(".attribute-content");
                        const parentattributeId = $(this).closest(".attribute-row").data("id");

                        // After adding a new subattribute, refresh the list
                        fetchAndDisplaySubattributes(content, parentattributeId);
                    });
                    
                } else {
                    // Show 'No results found' message
                    $("#segno-results").show();
                }
            },
            error: function () {
                console.error("Failed to fetch data");
                $("#loaderhid").hide();
                $("#segno-results").show();
            }
        });
    });

    function fetchAndDisplaySubattributes(content, parentattributeId) {
        content.html("<p>Loading...</p>"); // Show loader while fetching data
    
        $.ajax({
            url: ajaxurl,
            method: "POST",
            data: {
                action: "fetch_subattributes",
                parentattributeId: parentattributeId
            },
            success: function (response) {
                console.log("Full response:", response); // Debugging the response
    
                // Extract data array correctly
                let subattributes = response.data && response.data.data ? response.data.data : [];
    
                if (response.success && subattributes.length > 0) {
                    let subattributesHtml = "";
                    subattributes.forEach(function (subattribute) {
                        // Safely access properties to avoid undefined errors
                        const attrValue = subattribute.attr_value || "No Value";
                        const datasheetImg = subattribute.datasheet_imgurl || "";
                        const productImg = subattribute.product_imgurl || "";
                        const subattrid = subattribute.id || "";
    
                        subattributesHtml += `
                            <div class="subattribute-row" data-id="${subattrid}" style="border: 1px solid #ccc; border-radius: 5px; margin-bottom: 10px; background-color: #fff; position: relative; padding: 10px;">
                                <div class="subattribute-header" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                                    <div style="display: flex; align-items: center; flex: 1;">
                                        <!-- Attribute Name -->
                                        <span style="font-weight: bold; min-width: 150px; margin-right: 10px;">${attrValue}</span>

                                        <!-- Datasheet Image -->
                                        <img src="${datasheetImg}" alt="sub Datasheet Image" style="width: 50px; height: 50px;  border-radius: 5px; margin-right: 70px;">

                                        <!-- Product Page Image -->
                                        <img src="${productImg}" alt="sub Product Page Image" style="width: 50px; height: 50px;  border-radius: 5px; margin-right:70px;">
                                    </div>

                                    <!-- Action Buttons -->
                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        <a href="#TB_inline?&width=260&height=310&inlineId=custom-uam-subdiagram-edit-dlg" title="Edit Sub Attribute" class="thickbox subedit" style="color: #007bff; text-decoration: underline;">✎</a>
                                        <button class="delete-btn" style="background: none; border: none; color: #dc3545; font-size: 16px;">🗑️</button>
                                    </div>
                                </div>

                            </div>

                        `;
                    });
                    content.html(subattributesHtml).slideDown();
                } else {
                    console.error("Invalid response or no data found:", response);
                    content.html("<p>No subattributes found or invalid response format.</p>");
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX error:", status, error);
                content.html("<p>Error fetching subattributes.</p>");
            }
        });
    }

//Attribute add functions;

$("body").on("click", ".addattributed", function () {
    // Reset the modal fields for a new subattribute
    $("#addattribute-name").val("");  // Clear the Attribute Name
    $("#adddatasheet-image-preview").attr("src", "").hide();  // Clear Datasheet Image
    $("#addproductpage-image-preview").attr("src", "").hide();  // Clear Product Page Image
    
    // // Show the modal with empty fields
    // $("#custom-uam-subattribute-add-dlg").show();
});

// Open WordPress Media Library for Datasheet Image
$("#adddatasheet-image-btn").on("click", function (e) {
    e.preventDefault(); // Prevent default action of the button

    // Open the WordPress Media Library directly without custom title or button
    var mediaFrame = wp.media.frames.file_frame = wp.media({
        multiple: false // Allow only one file to be selected
    });

    // When an image is selected in the Media Library
    mediaFrame.on('select', function () {
        var attachment = mediaFrame.state().get('selection').first().toJSON();
        // Update the preview image source
        $("#adddatasheet-image-preview").attr("src", attachment.url).show();
    });

    // Open the media frame
    mediaFrame.open();
});

// Open WordPress Media Library for Product Page Image
$("#addproductpage-image-btn").on("click", function (e) {
    e.preventDefault(); // Prevent default action of the button

    // Open the WordPress Media Library directly without custom title or button
    var mediaFrame = wp.media.frames.file_frame = wp.media({
        multiple: false // Allow only one file to be selected
    });

    // When an image is selected in the Media Library
    mediaFrame.on('select', function () {
        var attachment = mediaFrame.state().get('selection').first().toJSON();
        // Update the preview image source
        $("#addproductpage-image-preview").attr("src", attachment.url).show();
    });

    // Open the media frame
    mediaFrame.open();
});


// Save the addattribute data to the database
$("#save-addattribute").on("click", function () {
    const selectedAttribute = $("#addattribute-select").val(); // Get selected value
    const adddatasheetImage = $("#adddatasheet-image-preview").attr("src");
    const addproductPageImage = $("#addproductpage-image-preview").attr("src");
    const activeTab = $(".custom-uam-segrestrictproduct-li.active").data("role");

    if (!selectedAttribute) {
        alert("Please select an attribute.");
        return;
    }

    console.log('selectedAttribute:', selectedAttribute);
    console.log('adddatasheetImage:', adddatasheetImage);
    console.log('addproductPageImage:', addproductPageImage);
    console.log('activeTab:', activeTab);

    $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'save_addedheadingattribute',
            selectedAttribute: selectedAttribute,
            adddatasheetImage: adddatasheetImage,
            addproductPageImage: addproductPageImage,
            activeTab: activeTab
        },
        success: function (response) {
            if (response.success) {
                alert(response.data.message || 'Attribute saved successfully!');
                tb_remove();
            } else {
                alert(response.data.message || 'Failed to save attribute.');
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", xhr.responseText);
            alert('Something went wrong. Please try again.');
        }
    });
});

// Populate dropdown with attributes
$(document).ready(function () {
    $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'fetch_attributes' // Backend action to fetch attributes
        },
        success: function (response) {
            if (response.success) {
                const attributes = response.data;
                const dropdown = $("#addattribute-select");
                dropdown.empty().append('<option value="" disabled selected>Select Attribute</option>');
                attributes.forEach(attr => {
                    dropdown.append(`<option value="${attr.attribute_label}">${attr.attribute_label}</option>`);
                });
            } else {
                console.error('Failed to fetch attributes:', response.data.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", xhr.responseText);
        }
    });
});









   
    //Sub Attribute add functions;

    $("body").on("click", ".subadd", function () {
        const parentattributeName = $(this)
        .closest(".attribute-row")
        .find(".attribute-header span:not(.togglecontent-icon)")
        .text()
        .trim();

    console.log("Parent Attribute Name:", parentattributeName);     
        console.log("Parent Attribute Name:", parentattributeName); // Debugging
    
        if (!parentattributeName) {
            alert("Parent attribute name is missing.");
            return;
        }
    
        // Reset the modal fields for a new subattribute
        $("#subattribute-name").val(""); // Clear the Attribute Name
        $("#subdatasheet-image-preview").attr("src", "").hide(); // Clear Datasheet Image
        $("#subproductpage-image-preview").attr("src", "").hide(); // Clear Product Page Image
    
        populateSubattributeOptions(parentattributeName);
    
        // Show the modal
        //$("#custom-uam-subattribute-add-dlg").show();
    });

    function populateSubattributeOptions(parentattributeName) {
        console.log("Sending parentattributeName:", parentattributeName); // Debugging



        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'get_subattribute_options', // Backend action to fetch attributes
                parentattributeName: parentattributeName,
            },
            success: function (response) {
                if (response.success) {
                    const attributes = response.data;
                    const dropdown = $("#subattribute-name");
                    dropdown.empty().append('<option value="" disabled selected>Select Attribute</option>');
                    attributes.forEach(attr => {
                        dropdown.append(`<option value="${attr.attrvalue}">${attr.attrvalue}</option>`);
                    });
                } else {
                    console.error('Failed to fetch attributes:', response.data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText);
            }
        });

    
        
    }
    // Open WordPress Media Library for Datasheet Image
    $("#subdatasheet-image-btn").on("click", function (e) {
        e.preventDefault(); // Prevent default action of the button

        // Open the WordPress Media Library directly without custom title or button
        var mediaFrame = wp.media.frames.file_frame = wp.media({
            multiple: false // Allow only one file to be selected
        });

        // When an image is selected in the Media Library
        mediaFrame.on('select', function () {
            var attachment = mediaFrame.state().get('selection').first().toJSON();
            // Update the preview image source
            $("#subdatasheet-image-preview").attr("src", attachment.url).show();
        });

        // Open the media frame
        mediaFrame.open();
    });

    // Open WordPress Media Library for Product Page Image
    $("#subproductpage-image-btn").on("click", function (e) {
        e.preventDefault(); // Prevent default action of the button

        // Open the WordPress Media Library directly without custom title or button
        var mediaFrame = wp.media.frames.file_frame = wp.media({
            multiple: false // Allow only one file to be selected
        });

        // When an image is selected in the Media Library
        mediaFrame.on('select', function () {
            var attachment = mediaFrame.state().get('selection').first().toJSON();
            // Update the preview image source
            $("#subproductpage-image-preview").attr("src", attachment.url).show();
        });

        // Open the media frame
        mediaFrame.open();
    });
    
    
    // Save the subattribute data to the database
    $("#save-subattribute").on("click", function () {
        const subattributeName = $("#subattribute-name").val(); // Get selected value
        const subdatasheetImage = $("#subdatasheet-image-preview").attr("src");
        const subproductPageImage = $("#subproductpage-image-preview").attr("src");
        const parentattributeName = $("#attribute-name").val();
        const parentattributeId = $("#attribute-id").val();
    
        if (!subattributeName) {
            alert("Please select an attribute.");
            return;
        }
    
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'save_subdiagramattribute',
                subattributeName: subattributeName,
                subdatasheetImage: subdatasheetImage,
                subproductPageImage: subproductPageImage,
                parentattributeName: parentattributeName,
                parentattributeId: parentattributeId
            },
            success: function (response) {
                if (response.success) {
                    alert('Subattribute saved successfully!');
                    tb_remove();
                } else {
                    alert('Failed to save subattribute: ' + response.data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert('Something went wrong. Please try again.');
            },
        });
    });
    



// Sub Attribute edit functions:

    $("body").on("click", ".subedit", function () {
        const row = $(this).closest(".subattribute-row");
        const subeditattribute = row.find(".subattribute-header span:nth-child(1)").text().trim();
        const subeditdatasheetImage = row.find(".subattribute-header img:nth-of-type(1)").attr("src");
        const subeditproductPageImage = row.find(".subattribute-header img:nth-of-type(2)").attr("src");
        const subeditattributeId = row.data("id"); // Get the data-id of the row

        console.log("subeditattribute:", subeditattribute);
        console.log("subeditdatasheetImage:", subeditdatasheetImage);
        console.log("subeditproductPageImage:", subeditproductPageImage);
        console.log("subeditattributeId:", subeditattributeId);


        // Set the values in the modal
        $("#subeditattribute-name").val(subeditattribute);
        $("#subeditdatasheet-image-preview").attr("src", subeditdatasheetImage || "").show();
        $("#subeditproductpage-image-preview").attr("src", subeditproductPageImage || "").show();
        $("#subeditattribute-id").val(subeditattributeId);

    });
    $("body").on("click", "#subeditedit-datasheet-image-btn",  function () {
        wp.media.editor.send.attachment = function (props, attachment) {
            $("#subeditdatasheet-image-preview").attr("src", attachment.url).show();
        };
        wp.media.editor.open();
    });
    
    $("body").on("click", "#subeditedit-productpage-image-btn", function () {
        wp.media.editor.send.attachment = function (props, attachment) {
            $("#subeditproductpage-image-preview").attr("src", attachment.url).show();
        };
        wp.media.editor.open();
    });

    $("body").on("click", "#subeditdatasheet-image-preview", function () {
        wp.media.editor.send.attachment = function (props, attachment) {
            $("#subeditdatasheet-image-preview").attr("src", attachment.url).show();
        };
        wp.media.editor.open();
    });
    
    $("body").on("click", "#subeditproductpage-image-preview", function () {
        wp.media.editor.send.attachment = function (props, attachment) {
            $("#subeditproductpage-image-preview").attr("src", attachment.url).show();
        };
        wp.media.editor.open();
    });
    // Save Button Logic
    $("body").on("click", "#save-subeditattribute", function () {
        const subeditattributeName = $("#subeditattribute-name").val();
        const subeditdatasheetImage = $("#subeditdatasheet-image-preview").attr("src");
        const subeditproductPageImage = $("#subeditproductpage-image-preview").attr("src");
    
        console.log("Saving Attribute:", subeditattributeName);
        console.log("Datasheet Image:", subeditdatasheetImage);
        console.log("ProductPage Image:", subeditproductPageImage);

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'save_subediting_attributes',
                subeditattributeName: subeditattributeName,
                subeditdatasheetImage: subeditdatasheetImage,
                subeditproductPageImage: subeditproductPageImage
            },
            success: function (response) {
                if (response.success) {
                    alert('Attribute Image saved successfully!');
                    tb_remove();
                } else {
                    alert('Failed to save attribute images: ' + response.data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert('Something save went wrong. Please try again.');
            },
        });
    });




//Attribute Edit functions;


    $("body").on("click", ".thickbox", function () {
        const row = $(this).closest(".attribute-row");
        const attribute = row.find(".attribute-header span:nth-child(2)").text().trim();
        const datasheetImage = row.find(".attribute-header img:nth-of-type(1)").attr("src");
        const productPageImage = row.find(".attribute-header img:nth-of-type(2)").attr("src");
        const attributeId = row.data("id"); // Get the data-id of the row

        console.log("attribute:", attribute);
        console.log("datasheetImage:", datasheetImage);
        console.log("productPageImage:", productPageImage);
        console.log("attributeId:", attributeId);


        // Set the values in the modal
        $("#attribute-name").val(attribute);
        $("#datasheet-image-preview").attr("src", datasheetImage || "").show();
        $("#productpage-image-preview").attr("src", productPageImage || "").show();
        $("#attribute-id").val(attributeId);

    });
    $("body").on("click", "#edit-datasheet-image-btn",  function () {
        wp.media.editor.send.attachment = function (props, attachment) {
            $("#datasheet-image-preview").attr("src", attachment.url).show();
        };
        wp.media.editor.open();
    });
    
    $("body").on("click", "#edit-productpage-image-btn", function () {
        wp.media.editor.send.attachment = function (props, attachment) {
            $("#productpage-image-preview").attr("src", attachment.url).show();
        };
        wp.media.editor.open();
    });

    $("body").on("click", "#datasheet-image-preview", function () {
        wp.media.editor.send.attachment = function (props, attachment) {
            $("#datasheet-image-preview").attr("src", attachment.url).show();
        };
        wp.media.editor.open();
    });
    
    $("body").on("click", "#productpage-image-preview", function () {
        wp.media.editor.send.attachment = function (props, attachment) {
            $("#productpage-image-preview").attr("src", attachment.url).show();
        };
        wp.media.editor.open();
    });
    
    // Save Button Logic
    $("body").on("click", "#save-attribute", function () {
        const attributeName = $("#attribute-name").val();
        const datasheetImage = $("#datasheet-image-preview").attr("src");
        const productPageImage = $("#productpage-image-preview").attr("src");
    
        console.log("Saving Attribute:", attributeName);
        console.log("Datasheet Image:", datasheetImage);
        console.log("ProductPage Image:", productPageImage);

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'save_edit_attributes',
                attributeName: attributeName,
                datasheetImage: datasheetImage,
                productPageImage: productPageImage
            },
            success: function (response) {
                if (response.success) {
                    alert('Attribute Image saved successfully!');
                    tb_remove();
                } else {
                    alert('Failed to save attribute images: ' + response.data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert('Something save went wrong. Please try again.');
            },
        });
    });

    // $("body").on("click", ".custom-uam-segrestrictproduct-li", function () {
    //     // Remove 'active' class from all items and add it to the clicked one
    //     $(".custom-uam-segrestrictproduct-li").removeClass("active");
    //     $(this).addClass("active");
    
    //     // Fetch the selected role/heading
    //     var selectedHeading = $(this).data("role");
    //     console.log("Selected heading: ", selectedHeading);
    
    //     // Clear existing data and show loader
    //     $("#c_uam_segcaprole_ul").empty();
    //     $("#loaderhid").show();
    //     $("#segno-results").hide();
    
    //     // Make AJAX call to fetch attribute values
    //     $.ajax({
    //         url: ajaxurl, // WordPress AJAX handler
    //         method: "POST",
    //         data: {
    //             action: "fetch_attribute_values", // Action name
    //             heading: selectedHeading // Pass the selected heading to the backend
    //         },
    //         success: function (response) {
    //             console.log("Response: ", response);
    
    //             // Hide the loader
    //             $("#loaderhid").hide();
    
    //             if (response.success && response.data.length > 0) {
    //                 let cardHtml = '';
                    
    //                 response.data.forEach(function (value) {
    //                     cardHtml += '<div class="attribute-card" style="border: 1px solid #ccc; border-radius: 8px; margin-bottom: 15px; padding: 15px; display: flex; align-items: center; background-color: #fff;">';
    //                     cardHtml += '<div style="flex: 1; font-weight: medium; font-size: 14px;">' + value.attribute + '</div>';
    //                     cardHtml += '<div style="flex: 0 0 40px; text-align: center;">';
    //                     cardHtml += '<img src="' + value.attr_imgurl + '" alt="Datasheet Image" style="width: 40px; height: 40px; cursor: pointer;" />';
    //                     cardHtml += '</div>';
    //                     cardHtml += '<div style="flex: 0 0 40px; text-align: center;">';
    //                     cardHtml += '<img src="' + value.product_imgurl + '" alt="ProductPage Image" style="width: 40px; height: 40px; cursor: pointer;" />';
    //                     cardHtml += '</div>';
    //                     cardHtml += '<div style="flex: 0 0 auto; text-align: right;">';
    //                     cardHtml += '<a href="#TB_inline?&width=260&height=310&inlineId=custom-uam-alert-diagramadd-edit-dlg" ';
    //                     cardHtml += 'title="Edit Attribute" class="thickbox" style="color: #007bff; text-decoration: underline; margin-right: 10px;">Edit</a>';
    //                     cardHtml += ' | ';
    //                     cardHtml += '<button class="delete-btn" style="background: none; border: none; color: #dc3545; text-decoration: underline;">Delete</button>';
    //                     cardHtml += '</div>';
    //                     cardHtml += '</div>';
    //                 });
    
    //                 $("#c_uam_segcaprole_ul").html(cardHtml);
    //             } else {
    //                 // Show 'No results found' message
    //                 $("#segno-results").show();
    //             }
    //         },
    //         error: function () {
    //             console.error("Failed to fetch data");
    //             $("#loaderhid").hide();
    //             $("#segno-results").show();
    //         }
    //     });
    // });

    // $("body").on("click", ".delete-btn", function () {
    //     const row = $(this).closest("tr");
    //     const attribute = row.find("td:first").text();
    //     if (confirm("Are you sure you want to delete: " + attribute + "?")) {
    //         row.remove(); // Remove row from table
    //         // Add your delete logic here (e.g., AJAX call to delete from backend)
    //     }
    // });
    // $("body").on("click", ".thickbox", function () {
    //     const row = $(this).closest("tr");
    //     const attribute = row.find("td:first").text();
    //     const datasheetImage = row.find("td:nth-child(2) img").attr("src");
    //     const productPageImage = row.find("td:nth-child(3) img").attr("src");
    
    //     // Set the values in the modal
    //     $("#attribute-name").val(attribute);
    //     $("#datasheet-image-preview").attr("src", datasheetImage || "").show();
    //     $("#productpage-image-preview").attr("src", productPageImage || "").show();
    // });
    // $("body").on("click", "#edit-datasheet-image-btn",  function () {
    //     wp.media.editor.send.attachment = function (props, attachment) {
    //         $("#datasheet-image-preview").attr("src", attachment.url).show();
    //     };
    //     wp.media.editor.open();
    // });
    
    // $("body").on("click", "#edit-productpage-image-btn", function () {
    //     wp.media.editor.send.attachment = function (props, attachment) {
    //         $("#productpage-image-preview").attr("src", attachment.url).show();
    //     };
    //     wp.media.editor.open();
    // });

    // $("body").on("click", "#datasheet-image-preview", function () {
    //     wp.media.editor.send.attachment = function (props, attachment) {
    //         $("#datasheet-image-preview").attr("src", attachment.url).show();
    //     };
    //     wp.media.editor.open();
    // });
    
    // $("body").on("click", "#productpage-image-preview", function () {
    //     wp.media.editor.send.attachment = function (props, attachment) {
    //         $("#productpage-image-preview").attr("src", attachment.url).show();
    //     };
    //     wp.media.editor.open();
    // });
    
    // // Save Button Logic
    // $("body").on("click", "#save-attribute", function () {
    //     const attributeName = $("#attribute-name").val();
    //     const datasheetImage = $("#datasheet-image-preview").attr("src");
    //     const productPageImage = $("#productpage-image-preview").attr("src");
    
    //     // console.log("Saving Attribute:", attributeName);
    //     // console.log("Datasheet Image:", datasheetImage);
    //     // console.log("ProductPage Image:", productPageImage);

    //     $.ajax({
    //         type: 'POST',
    //         url: ajaxurl,
    //         data: {
    //             action: 'save_edit_attributes',
    //             attributeName: attributeName,
    //             datasheetImage: datasheetImage,
    //             productPageImage: productPageImage
    //         },
    //         success: function (response) {
    //             if (response.success) {
    //                 alert('Attribute Image saved successfully!');
    //                 tb_remove();
    //             } else {
    //                 alert('Failed to save attribute images: ' + response.data.message);
    //             }
    //         },
    //         error: function (xhr, status, error) {
    //             console.error(xhr.responseText);
    //             alert('Something save went wrong. Please try again.');
    //         },
    //     });
    // });
        


    // $('.custom-uam-segrestrictproduct-li').on('click', function() {
    //     var selectedHeading = $(this).data('role'); // Get the selected heading
    //     var $ulContainer = $('#c_uam_segcaprole_ul'); // Right-side list container

    //     // Add 'active' class to the clicked item and remove from others
    //     $('.custom-uam-segrestrictproduct-li').removeClass('active');
    //     $(this).addClass('active');

    //     // Clear current content and show loader
    //     $ulContainer.empty();
    //     $('#loaderhid').show();

    //     // Fetch data using AJAX
    //     $.ajax({
    //         url: ajaxurl, // WordPress AJAX handler
    //         method: 'POST',
    //         data: {
    //             action: 'fetch_attribute_values', // Custom AJAX action
    //             heading: selectedHeading
    //         },
    //         success: function(response) {
    //             console.log(response); // Log response to debug
        
    //             $('#loaderhid').hide();
    //             if (response.data.length > 0) {
    //                 response.data.forEach(function(item) {
    //                     console.log('Appending item: ', item); // Move this inside the loop
    //                     $ulContainer.append('<li>' + item.attribute + '</li>');
    //                 });
    //             } else {
    //                 // Show 'No results found' message
    //                 $('#segno-results').show();
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('AJAX Error:', status, error);
    //             $('#loaderhid').hide();
    //             alert('Failed to fetch data. Please try again.');
    //         }
    //     });
    // });

});