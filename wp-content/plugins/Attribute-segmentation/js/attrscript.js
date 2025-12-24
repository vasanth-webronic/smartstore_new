jQuery(document).ready(function ($) {

    $('#main_select').on('change', function () {
        const selectedValue = $(this).val();

        if (selectedValue === 'category') {
            $('#seg').show(); // Show category section
            $('#artseg').hide(); // Hide product section
            $('#seg_form')[0].reset(); // Reset category form
            $('#seg_results').empty().hide(); // Clear and hide category results
            //$('#custom-uam-alert-add-edit-dlg').hide(); // Hide Add Article Number form
        } else if (selectedValue === 'product') {
            $('#seg').hide(); // Hide category section
            $('#artseg').show(); // Show product section
            $('#artseg_form')[0].reset(); // Reset product form
            $('#artseg_results').empty().hide(); // Clear and hide product results
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
                    $('#artno-list-container').empty(); // Clear the list container
                    artnoList.length = 0; // Clear the array
                    tb_remove();
                    location.reload();
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
    
        // Loop through each attribute row to check for a match
        $('.attribute-row').each(function() {
            var attributeValue = $(this).find('.attribute-header span:nth-child(2)').text().toLowerCase(); // Get the text from the second span in the header
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
        } else {
            $('#segno-results').hide();
        }
    });


    $("body").on("click", ".custom_segrestrict_ls .custom-uam-segrestrictproduct-li", function(event) {
        // Remove active class from all role items
        $(".custom_segrestrict_ls .custom-uam-segrestrictproduct-li").removeClass("active");

        // Add active class to the clicked role item
        $(this).addClass("active");
    });


    $(document).ready(function() {
        // Trigger click on the first item in the list if it's not already active
        var firstRoleItem = $(".custom_segrestrict_ls li:first");
        if (!firstRoleItem.hasClass('active')) {
            firstRoleItem.addClass('active'); // Add active class
        }
    
        // Optional: Simulate the click on the first item (to show content)
        $(".custom_segrestrict_ls .custom-uam-segrestrictproduct-li.active").trigger('click');
    });
    

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
        $('#segroleproduct-search').val(''); // Clear search input

    
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
    
                    response.data.forEach(function (value) {
                        const datasheetImg = value.attr_imgurl
                            ? `<div style="position: relative; border: 1px solid #ccc; border-radius: 4px; width: 70px; height: 60px; overflow: hidden; display: flex; justify-content: center; align-items: center; margin-right: 40px;">
                                <img src="${value.attr_imgurl}" alt="Datasheet Image" style="width: 60%; height: 60%; object-fit: contain;" />
                            </div>`
                            : `<img src="${customSegmentData.emptyIconUrl}" alt="Datasheet Image" style="width: 50px; height: 50px; border-radius: 5px; margin-right: 70px;" />`;

                        const productImg = value.product_imgurl
                            ? `<div style="position: relative; border: 1px solid #ccc; border-radius: 4px; width: 70px; height: 60px; overflow: hidden; display: flex; justify-content: center; align-items: center; margin-right: 40px;">
                                <img src="${value.product_imgurl}" alt="Product Page Image" style="width: 60%; height: 60%; object-fit: contain;" />
                            </div>`
                            : `<img src="${customSegmentData.emptyIconUrl}" alt="Datasheet Image" style="width: 50px; height: 50px; border-radius: 5px; margin-right: 70px;" />`;

                        const toggleIcon = value.attribute_count > 0
                            ? `<span class="togglecontent-icon" style="font-size: 20px; margin-right: 10px;">&#9654;</span>`
                            : `<span class="togglecontent-icon" style="font-size: 20px; margin-right: 10px; visibility: hidden;">&#9654;</span>`;
        
                        collapsibleHtml += `
                            <div class="attribute-row" data-id="${value.id}" style="border: 1px solid #ccc; border-radius: 5px; margin-bottom: 10px; background-color: #fff; position: relative; padding: 10px;">
                                <div class="attribute-header" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                                    <div style="display: flex; align-items: center; flex: 1;">
                                        ${toggleIcon}                                        
                                        <span style="font-weight: bold; min-width: 150px; max-width: 200px; width: 190px; margin-right: 10px;">${value.attribute}</span>
                                        ${datasheetImg}
                                        ${productImg}
                                        <input type="hidden" class="attribute-translation" value="${value.attribute_translation}">
                                        <input type="hidden" class="editdatasheet-width" value="${value.datasheet_width}">
                                        <input type="hidden" class="editdatasheet-height" value="${value.datasheet_height}">
                                        <input type="hidden" class="editproduct-width" value="${value.product_width}">
                                        <input type="hidden" class="editproduct-height" value="${value.product_height}">
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        <a href="#TB_inline?&width=400&height=400&inlineId=custom-uam-subattribute-add-dlg"
                                           title="Add Attribute"
                                           class="thickbox subadd add-btn"
                                           style="text-decoration: none;">
                                           <img src="${customSegmentData.addIconUrl}" alt="Add Attribute Icon" style="width: 16px; height: 16px;">
                                        </a>
                                        <a href="#TB_inline?&width=350&height=350&inlineId=custom-uam-alert-diagramadd-edit-dlg"
                                           title="Edit Attribute"
                                           class="thickbox"
                                           style="color: #007bff; text-decoration: underline;">
                                           <img src="${customSegmentData.editIconUrl}" alt="Add Attribute Icon" style="width: 16px; height: 16px;">
                                        </a>
                                        <button class="attributedelete-btn" style="background: none; border: none; color: #dc3545; font-size: 16px;">
                                            <img src="${customSegmentData.deleteIconUrl}" alt="delete Attribute Icon" style="width: 16px; height: 16px;">
                                        </button>
                                    </div>
                                </div>
                                <div class="attribute-content" style="display: none; padding: 10px; margin-top:20px; background-color: #f9f9f9; border: 1px solid #ccc;"></div>
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
                        // const datasheetImg = subattribute.datasheet_imgurl || "";
                        // const productImg = subattribute.product_imgurl || "";
                        
                        const subattrid = subattribute.id || "";

                        const datasheetImg = subattribute.datasheet_imgurl
                            ? `<div style="position: relative; border: 1px solid #ccc; border-radius: 4px; width: 70px; height: 60px; overflow: hidden; display: flex; justify-content: center; align-items: center; margin-right: 40px;">
                                <img src="${subattribute.datasheet_imgurl}" alt="sub Datasheet Image" style="width: 60%; height: 60%; object-fit: contain;" />
                            </div>`
                            : `<img src="${customSegmentData.emptyIconUrl}" alt="sub Datasheet Image" style="width: 50px; height: 50px; border-radius: 5px; margin-right: 70px;" />`;

                        const productImg = subattribute.product_imgurl
                            ? `<div style="position: relative; border: 1px solid #ccc; border-radius: 4px; width: 70px; height: 60px; overflow: hidden; display: flex; justify-content: center; align-items: center; margin-right: 40px;">
                                <img src="${subattribute.product_imgurl}" alt="sub Product Page Image" style="width: 60%; height: 60%; object-fit: contain;" />
                            </div>`
                            : `<img src="${customSegmentData.emptyIconUrl}" alt="sub Product Page Image" style="width: 50px; height: 50px; border-radius: 5px; margin-right: 70px;" />`;

    
                        subattributesHtml += `
                            <div class="subattribute-row" data-id="${subattrid}" style="border: 1px solid #ccc; border-radius: 5px; margin-bottom: 10px; background-color: #fff; position: relative; padding: 10px;">
                                <div class="subattribute-header" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                                    <div style="display: flex; align-items: center; flex: 1;">
                                        <!-- Attribute Name -->
                                        <span style="font-weight: bold; min-width: 150px; width:100px; margin-right: 10px;">${attrValue}</span>
                                        ${datasheetImg}
                                        ${productImg}
                                        <input type="hidden" class="subeditdatasheet-width" value="${subattribute.datasheet_width}">
                                        <input type="hidden" class="subeditdatasheet-height" value="${subattribute.datasheet_height}">
                                        <input type="hidden" class="subeditproduct-width" value="${subattribute.product_width}">
                                        <input type="hidden" class="subeditproduct-height" value="${subattribute.product_height}">
                                    </div>

                                    <!-- Action Buttons -->
                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        <a href="#TB_inline?&width=350&height=325&inlineId=custom-uam-subdiagram-edit-dlg" title="Edit Sub Attribute" class="thickbox subedit" style="color: #007bff; text-decoration: underline;">
                                            <img src="${customSegmentData.editIconUrl}" alt="Add Attribute Icon" style="width: 16px; height: 16px;">
                                        </a>
                                        <button class="subattributedelete-btn" style="background: none; border: none; color: #dc3545; font-size: 16px;">
                                            <img src="${customSegmentData.deleteIconUrl}" alt="delete Attribute Icon" style="width: 16px; height: 16px;">
                                        </button>
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
        const Attributetranslation = $("#translationaddattribute-name").val();
        const adddatasheetImage = $("#adddatasheet-image-preview").attr("src");
        const addproductPageImage = $("#addproductpage-image-preview").attr("src");
        const activeTab = $(".custom-uam-segrestrictproduct-li.active").data("role");
        const datasheetWidth = $("#datasheet-width").val();
        const datasheetheight = $("#datasheet-height").val();
        const productPageWidth = $("#productpage-width").val();
        const productPageheight = $("#productpage-height").val();
        
        console.log('Attributetranslation',Attributetranslation);

        if (!selectedAttribute) {
            alert("Please select an 'Attribute'.");
            return;
        }
        if (!Attributetranslation) {
            alert("Please Entered 'Swedish Translation'.");
            return;
        }

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'save_addedheadingattribute',
                selectedAttribute: selectedAttribute,
                Attributetranslation: Attributetranslation,
                adddatasheetImage: adddatasheetImage,
                addproductPageImage: addproductPageImage,
                datasheetWidth: datasheetWidth,
                datasheetheight: datasheetheight,
                productPageWidth: productPageWidth,
                productPageheight: productPageheight,
                activeTab: activeTab
            },
            success: function (response) {
                if (response.success) {
                    alert(response.data.message || 'Attribute saved successfully!');
                    tb_remove();
                    location.reload();
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

    //Attribute Edit functions;


    $("body").on("click", ".thickbox", function () {
        const row = $(this).closest(".attribute-row");
        const attribute = row.find(".attribute-header span:nth-child(2)").text().trim();
        const datasheetImage = row.find(".attribute-header img:nth-of-type(1)").attr("src");
        const productPageImage = row.find(".attribute-header img").eq(1).attr("src"); // Use index to select second image
        // const productPageImage = row.find(".attribute-header img:nth-of-type(2)").attr("src");
        const attributeId = row.data("id"); // Get the data-id of the row

        // Fetch width and height values using class selectors
        const attributetranslation = row.find(".attribute-translation").val(); // Datasheet width value
        const datasheetWidth = row.find(".editdatasheet-width").val(); // Datasheet width value
        const datasheetHeight = row.find(".editdatasheet-height").val(); // Datasheet height value
        const productPageWidth = row.find(".editproduct-width").val(); // Product width value
        const productPageHeight = row.find(".editproduct-height").val(); // Product height value

        // Set the values in the modal
        $("#attribute-name").val(attribute);
        $("#datasheet-image-preview").attr("src", datasheetImage || "").show();
        $("#productpage-image-preview").attr("src", productPageImage || "").show();
        $("#translationeditattribute-name").val(attributetranslation);
        $("#editdatasheet-width").val(datasheetWidth || 0);
        $("#editdatasheet-height").val(datasheetHeight || 0);
        $("#editproductpage-width").val(productPageWidth || 0);
        $("#editproductpage-height").val(productPageHeight || 0);
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
        const attributetranslation = $("#translationeditattribute-name").val();
        const datasheetWidth = $("#editdatasheet-width").val();
        const datasheetHeight = $("#editdatasheet-height").val();
        const productPageWidth = $("#editproductpage-width").val();
        const productPageHeight = $("#editproductpage-height").val();
        const attributeId = $("#attribute-id").val();
        console.log({
            attributeName,
            attributetranslation,
            datasheetImage,
            productPageImage,
            datasheetWidth,
            datasheetHeight,
            productPageWidth,
            productPageHeight,
        });
    
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'save_edit_attributes',
                attributeName: attributeName,
                attributetranslation:attributetranslation,
                datasheetImage: datasheetImage,
                productPageImage: productPageImage,
                datasheetWidth: datasheetWidth,
                datasheetHeight: datasheetHeight,
                productPageWidth: productPageWidth,
                productPageHeight: productPageHeight,
                attributeId: attributeId,
            },
            success: function (response) {
                if (response.success) {
                    alert('Attribute saved successfully!');
                    tb_remove();
                    location.reload();
                } else {
                    alert(response.data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert('Something went wrong. Please try again.');
            },
        });
    });


    //Sub Attribute add functions;

    $("body").on("click", ".subadd", function () {
        const parentattributeName = $(this)
        .closest(".attribute-row")
        .find(".attribute-header span:not(.togglecontent-icon)")
        .text()
        .trim();
    
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

    $("body").on("click", ".subattributedelete-btn", function () {
        const row = $(this).closest(".subattribute-row");
        const subattrid = row.data("id"); // Extract the data-id attribute
    
        console.log('subattrid:', subattrid);
    
        if (confirm("Are you sure you want to delete this subattribute?")) {
            $.ajax({
                url: ajaxurl, // WordPress global ajaxurl variable
                type: 'POST',
                data: { 
                    action: "delete_subattribute",
                    subattrid: subattrid 
                },
                success: function (response) {
                    if (response.success) {
                        row.remove(); // Remove the row from DOM
                        console.log('Subattribute deleted successfully:', response.data.message);
                    } else {
                        console.error('Error deleting subattribute:', response.data.message);
                        alert(response.data.message || 'Failed to delete subattribute.');
                    }
                },
                error: function (error) {
                    console.error('Error deleting subattribute:', error);
                    alert('An error occurred. Please try again.');
                }
            });
        }
    });

    $("body").on("click", ".attributedelete-btn", function () {
        const row = $(this).closest(".attribute-row");
        const attrid = row.data("id"); // Extract the data-id attribute
    
        console.log('attrid:', attrid);
    
        if (confirm("Are you sure you want to delete this attribute?")) {
            $.ajax({
                url: ajaxurl, // WordPress global ajaxurl variable
                type: 'POST',
                data: { 
                    action: "delete_attributerow",
                    attrid: attrid 
                },
                success: function (response) {
                    if (response.success) {
                        row.remove(); // Remove the row from DOM
                        alert(response.data.message);
                    } else {
                        console.error('Error deleting subattribute:', response.data.message);
                        alert(response.data.message || 'Failed to delete subattribute.');
                    }
                },
                error: function (error) {
                    console.error('Error deleting subattribute:', error);
                    alert('An error occurred. Please try again.');
                }
            });
        }
    });

    function populateSubattributeOptions(parentattributeName) {
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: "get_subattribute_options",
                parentattributeName: parentattributeName,
            },
            success: function (response) {
                if (response.success) {
                    const attributes = response.data;
                    const dropdown = $("#subattribute-name");
                    dropdown.empty().append('<option value="" disabled selected>Select Attribute Value</option>');
                    attributes.forEach(attr => {
                        dropdown.append(
                            `<option value="${attr.attrvalue}" data-translation="${attr.translation_attrvalue}">${attr.attrvalue}</option>`
                        );
                    });
                } else {
                    console.error("Failed to fetch attributes:", response.data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText);
            },
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
        const translationAttrValue = $("#subattribute-name option:selected").data("translation"); // Get translation value
        const subdatasheetImage = $("#subdatasheet-image-preview").attr("src");
        const subproductPageImage = $("#subproductpage-image-preview").attr("src");
        const parentattributeName = $("#attribute-name").val();
        const parentattributeId = $("#attribute-id").val();
        const datasheetWidth = $("#subadddatasheet-width").val();
        const datasheetheight = $("#subadddatasheet-height").val();
        const productPageWidth = $("#subaddproductpage-width").val();
        const productPageheight = $("#subaddproductpage-height").val();
    
        if (!subattributeName) {
            alert("Please select an 'Attribute Value'.");
            return;
        }
        if (!subdatasheetImage) {
            alert("Please Upload 'Datasheet Image'.");
            return;
        }
        if (!subproductPageImage) {
            alert("Please Upload 'Product Page Image'.");
            return;
        }
    
    
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: "save_subdiagramattribute",
                subattributeName: subattributeName,
                translationAttrValue: translationAttrValue, // Pass translation value
                subdatasheetImage: subdatasheetImage,
                subproductPageImage: subproductPageImage,
                parentattributeName: parentattributeName,
                parentattributeId: parentattributeId,
                datasheetWidth: datasheetWidth,
                datasheetheight: datasheetheight,
                productPageWidth: productPageWidth,
                productPageheight: productPageheight
            },
            success: function (response) {
                if (response.success) {
                    alert("Subattribute saved successfully!");
                    tb_remove();
                    location.reload();
                } else {
                    alert(response.data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert("Something went wrong. Please try again.");
            },
        });
    });
    

    // Sub Attribute edit functions:

    $("body").on("click", ".subedit", function () {
        const row = $(this).closest(".subattribute-row");
        const subeditattribute = row.find(".subattribute-header span:nth-child(1)").text().trim();
        const subeditdatasheetImage = row.find(".subattribute-header img:nth-of-type(1)").attr("src");
        // const subeditproductPageImage = row.find(".subattribute-header img:nth-of-type(2)").attr("src");
        const subeditproductPageImage = row.find(".subattribute-header img").eq(1).attr("src"); // Use index to select second image

        const subeditattributeId = row.data("id"); // Get the data-id of the row
        const datasheetWidth = row.find(".subeditdatasheet-width").val(); // Datasheet width value
        const datasheetHeight = row.find(".subeditdatasheet-height").val(); // Datasheet height value
        const productPageWidth = row.find(".subeditproduct-width").val(); // Product width value
        const productPageHeight = row.find(".subeditproduct-height").val(); // Product height value

        // Set the values in the modal
        $("#subeditattribute-name").val(subeditattribute);
        $("#subeditdatasheet-image-preview").attr("src", subeditdatasheetImage || "").show();
        $("#subeditproductpage-image-preview").attr("src", subeditproductPageImage || "").show();
        $("#subeditattribute-id").val(subeditattributeId);
        $("#subeditdatasheet-width").val(datasheetWidth || 0);
        $("#subeditdatasheet-height").val(datasheetHeight || 0);
        $("#subeditproductpage-width").val(productPageWidth || 0);
        $("#subeditproductpage-height").val(productPageHeight || 0);

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
        const datasheetWidth = $("#subeditdatasheet-width").val();
        const datasheetHeight = $("#subeditdatasheet-height").val();
        const productPageWidth = $("#subeditproductpage-width").val();
        const productPageHeight = $("#subeditproductpage-height").val();

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'save_subediting_attributes',
                subeditattributeName: subeditattributeName,
                subeditdatasheetImage: subeditdatasheetImage,
                subeditproductPageImage: subeditproductPageImage,
                datasheetWidth: datasheetWidth,
                datasheetHeight: datasheetHeight,
                productPageWidth: productPageWidth,
                productPageHeight: productPageHeight
            },
            success: function (response) {
                if (response.success) {
                    alert('Attribute Image saved successfully!');
                    tb_remove();
                    location.reload();
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
});