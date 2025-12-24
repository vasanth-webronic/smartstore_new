jQuery(document).ready(function ($) {
    function init_taw_filter_js(){
     $('body').append('<div class="modal-container" style="display:none;"></div>');
 
     $(document).on('click', '.plus', function() {
         // Get the term data of the clicked row
         var termName = $(this).closest('tr').find('.term-name').text();
         var termSlug = $(this).closest('tr').find('.term-slug').text();
         var termDescription = $(this).closest('tr').find('.term-description').text();
         var termtaxonomy = $(this).closest('tr').find('.term-taxonomy').text();
         var termtrid = $(this).closest('tr').find('.term-trid').text();
         var termlangcode = $(this).closest('tr').find('.term-langcode').text();
 
         // Populate the modal container with the term data
         $('.modal-container').html(`
             <!-- Modal HTML structure -->
             <div tabindex="-1" role="dialog" class="ui-dialog ui-corner-all ui-widget ui-widget-content ui-front dialog-fixed otgs-ui-dialog" aria-describedby="icl_tt_form_5260_sv" aria-labelledby="ui-id-6" style="height: auto; width: 800px; top: 222.262px; left: 320px;">
                 <!-- Modal content -->
                 <div class="ui-dialog-titlebar ui-corner-all ui-widget-header ui-helper-clearfix">
                     <span id="ui-id-6" class="ui-dialog-title">Term translation</span>
                     <button id="modal-close-btn" type="button" class="ui-button ui-corner-all ui-widget ui-button-icon-only ui-dialog-titlebar-close" title="close">
                         <span class="ui-button-icon ui-icon ui-icon-closethick"></span>
                         <span class="ui-button-icon-space"></span>
                     </button>
                 </div>
                 <div class="wpml-dialog-body wpml-dialog-translate">
                     <!-- Populate term data here -->
                     <header class="wpml-term-translation-header">
                         <h3 class="wpml-header-original">Original: <img src="${imageflag}" alt="English Flag"> ${langname} </h3>
                         <h3 class="wpml-header-translation">Translation to: <img src="${translateflag}" alt="English Flag"> ${translatelang}</h3>
                     </header>
                     <div class="wpml-form-row">
                         <label for="term_name">Name</label>
                         <input readonly id="term-name-original" value="${termName}" type="text">
                         <button class="button-copy button-secondary js-button-copy otgs-ico-copy" data-target="term-name" title="Copy from original"></button>
                         <input id="term-name"  type="text">
                     </div>
                     <div class="wpml-form-row">
                         <label for="term-slug">Slug</label>
                         <input readonly id="term-slug-original" value="${termSlug}" type="text">
                         <button class="button-copy button-secondary js-button-copy otgs-ico-copy" data-target="term-slug" title="Copy from original"></button>
                         <input id="term-slug"  type="text">
                     </div>
                     <div class="wpml-form-row">
                         <label for="term-description">Description</label>
                         <textarea readonly id="term-description-original" cols="22" rows="4">${termDescription}</textarea>
                         <button class="button-copy button-secondary js-button-copy otgs-ico-copy" title="Copy from original"></button>
                         <textarea id="term-description" cols="22" rows="4"></textarea>
                     </div>
                     <!-- Add hidden input field for termtaxonomy -->
                      <input type="hidden" id="term-taxonomy" value="${termtaxonomy}">
                      <input type="hidden" id="term-trid" value="${termtrid}">
                      <input type="hidden" id="term-langcode" value="${termlangcode}">
                      <input type="hidden" id="term-oldname" value="${termName}">
 
 
 
                 </div>
                 <div class="wpml-dialog-footer">
                     <span class="errors icl_error_text"></span>
                     <input class="cancel wpml-dialog-close-button alignleft" value="Cancel" type="button">
                     <input class="button-primary term-save alignright" value="Save" type="submit">
                     <span class="spinner alignright"></span>
                 </div>
             </div>
             <!-- Overlay -->
             <div class="ui-widget-overlay ui-front" style="z-index: 100101;"></div>
         `);
         
         // Show the modal container
         $('.modal-container').show();
     });
     
     
     $(document).on('click', '.js-button-copy', function() {
         // Get the value of the input field in the same row as the clicked button
         var originaltermname = $(this).closest('.wpml-form-row').find('#term-name-original').val();
         var originalslugValue = $(this).closest('.wpml-form-row').find('#term-slug-original').val();
         var originaldescription = $(this).closest('.wpml-form-row').find('#term-description-original').val();
 
         $(this).closest('.wpml-form-row').find('#term-name').val(originaltermname);
         $(this).closest('.wpml-form-row').find('#term-slug').val(originalslugValue+ '-sv');
         $(this).closest('.wpml-form-row').find('#term-description').val(originaldescription);
     });
 
     // Delegate click event for the modal close button
     $(document).on('click', '#modal-close-btn', function() {
         // Hide the modal container
         $(this).closest('.modal-container').hide();
         // Hide the overlay
     $('.ui-widget-overlay').hide();
         
     });
 
  // Delegate click event for the modal cancel button
    $(document).on('click', '.cancel', function() {
     // Hide the modal container
     $(this).closest('.modal-container').hide();
     
     // Hide the overlay
     $('.ui-widget-overlay').hide();
 });

 $('#sync_button').off('click').on('click', function() {
    var cate_no = $('#cate_no').val();
    console.log('cate_no', cate_no);
    if (!cate_no) {
        alert('Please select a category.');
        return;
    }

    $('#loader').show();
    $('#sync_button').prop('disabled', true); // Disable the button

    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'sync_category',
            cate_no: cate_no
        },
        success: function(response) {
            $('#loader').hide();
            $('#sync_button').prop('disabled', false); // Re-enable the button
            if (response.success) {
                alert(response.data.message);
            } else {
                alert(response.data.message || 'An error occurred.');
            }
        },
        error: function(xhr, status, error) {
            $('#loader').hide();
            $('#sync_button').prop('disabled', false); // Re-enable the button
            alert('An error occurred: ' + error);
        }
    });
});
 
 $(document).on('click', '.term-save', function() {
     // Get the term data
     var termName = $('#term-name').val();
     var termSlug = $('#term-slug').val();
     var termDescription = $('#term-description').val();
     var termtaxonomy = $('#term-taxonomy').val();
     var termtrid = $('#term-trid').val();
     var termlangcode = $('#term-langcode').val();
     var termoldname = $('#term-oldname').val();
 
     $.ajax({
         url: ajaxurl,
         method: 'POST',
         data: {
             action: 'save_term_data', // Action to trigger the PHP function
             termName: termName,
             termSlug: termSlug,
             termDescription: termDescription,
             termtaxonomy:termtaxonomy,
             termtrid:termtrid,
             termlangcode:termlangcode,
             termoldname:termoldname
             
         },
 
         success: function(response) {
             // Optionally handle the response here
             location.reload(true);
         },
         error: function(xhr, status, error) {
             // Optionally handle errors here
             console.error(error);
         }
     });
     
     // Hide the modal container
     $('.modal-container').hide();
     // Hide the overlay
     $('.ui-widget-overlay').hide();
 });
 
 $(document).on('click', '.add', function() {
     var catold = $(this).closest('tr').find('.cat-old').text();
     var catnew = $(this).closest('tr').find('.cat-new').text();
     
     $.ajax({
         url: ajaxurl,
         method: 'POST',
         data: {
             action: 'save_cat_data', // Action to trigger the PHP function
             catold: catold,
             catnew: catnew
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
     
          $('.select').click(function() {
              var butval = $(this).val();
              var butname = $(this).attr('name');
              var selectedCateNo = $('#hiddenSelectedCateNo').val();
              var chbox = $('.' + butname).filter(function() {
                  return $(this).prop('className').includes(butname);
              });
              chbox.each(function() {
                  var $chx = $(this);
                  $chx.prop('checked', true);
              });
  
              $.ajax({
                  type: 'POST',
                  url: ajaxurl, // WordPress AJAX URL
                  data: {
                      action: 'change_display_value',
                      
                      cate_no: selectedCateNo,
                      attribute: butname
                      
                      
                  },
                  success: function (response) {
                      
                      console.log(response);
                  }
              });
  
          });
  
  
  
  
          $('.deselect').click(function() {
              var butval = $(this).val();
              var butname = $(this).attr('name');
              var selectedCateNo = $('#hiddenSelectedCateNo').val();
              var chbox = $('.' + butname).filter(function() {
                  return $(this).prop('className').includes(butname);
              });
              chbox.each(function() {
                  var $chx = $(this);
                  $chx.prop('checked', false);
              });
  
              $.ajax({
                  type: 'POST',
                  url: ajaxurl, // WordPress AJAX URL
                  data: {
                      action: 'deselect_display_value',
                      
                      cate_no: selectedCateNo,
                      attribute: butname
                      
                      
                  },
                  success: function (response) {
                      
                      console.log(response);
                  }
              });
  
  
          });
  
  
  
      // Attach a change event handler to the checkboxes
      $('#filters input[type="checkbox"]').on('change', function () {
          var isChecked = $(this).is(':checked');
          var attValue = $(this).val();
          var attribute = $('#hiddenAttribute').val();
          var selectedCateNo = $('#hiddenSelectedCateNo').val();
          var checkboxName = $(this).attr('name');
         var checking=$(this).attr('class');
         
          console.log(isChecked);
          console.log(attValue);
          console.log('CAtegoryasdf'+ attribute);
          console.log('CAtegoryasdf'+ selectedCateNo);
          console.log('CAtegoryasdf'+ checking);
          if(checking!=='att_check'){
          // Send an AJAX request when the checkbox changes
          $.ajax({
              type: 'POST',
              url: ajaxurl, // WordPress AJAX URL
              data: {
                  action: 'update_display_value',
                  attValue: attValue,
                  isChecked: isChecked,
                  cate_no: selectedCateNo,
                  attribute: attribute,
                  check: checkboxName,
                  attr: isChecked
              },
              success: function (response) {
                  // Handle the response if needed
                  //$('.' + checking).prop('checked', true);
                  if (isChecked) {
                      $('[name="' + checkboxName + '"]').prop('checked', true);
                      console.log("checked");
                      console.log(checking);
                  } else {
                      $('[name="' + checkboxName + '"]').prop('checked', false);
                      console.log("unchecked");
                      console.log(checking);
  
                  }
                  var checkboxes = $('.' + checking).filter(function() {
                      return $(this).prop('className').includes(checking);
                  });
   var f = 0; // Initialize f as 0 (unchecked)
  
  checkboxes.each(function() {
      var $checkbox = $(this);    
      // Check if the current checkbox is checked
      if ($checkbox.prop('checked')) {
          f = 1; // Set f to 1 if at least one checkbox is checked
         $('[name="' + checking + '"]').prop('checked', true);
         return false; // Exit the loop early since we found a checked checkbox
      }
  if(f===0)
      {
          $('[name="' + checking + '"]').prop('checked', false);
      }
  });
                 
                //  $('.att_check').prop('checked', true);
                  console.log(response);
              }
          });
      }else{
  
         // var checkboxes = $('.'+attribute);
          //var selectatt=$(this).val();
        //  var selectatt= $('input[name="att_check"]').attr('class');
          var selectatt = $(this).attr('name');
          console.log('final'+selectatt);
          var jk= $(this).prop('checked');
          var isChec = $(this).is(':checked');
          //var classNameToFind = 'input.' + selectatt;
  
          var checkboxes = $('.' + selectatt).filter(function() {
              return $(this).prop('className').includes(selectatt);
          });
  
         
          
         console.log(checkboxes);
        
              checkboxes.each(function() {
                  var $checkbox = $(this);
     // $checkbox.prop('checked', !$checkbox.prop('checked')).trigger('change');
    // $checkbox.prop('checked', isChec).trigger('change');
    if(jk)
    $checkbox.prop('disabled', false);          
  else
  $checkbox.prop('disabled', true);
              });
  
  
              $.ajax({
                  type: 'POST',
                  url: ajaxurl, // WordPress AJAX URL
                  data: {
                      action: 'block_display_value',
                      attValue: attValue,
                      isChecked: isChecked,
                      cate_no: selectedCateNo,
                      attribute: selectatt,
                      check: checkboxName,
                      attr: jk
                  },
                  success: function (response) {
                      
                      console.log(response);
                  }
              });
  
  
  
              
          }
     
  
      });
 
 
     $('.datasheetselect').click(function() {
         var butval = $(this).val();
         var butname = $(this).attr('name');
         var selectedCateNo = $('#hiddenSelectedCateNo').val();
         var chbox = $('.' + butname).filter(function() {
             return $(this).prop('className').includes(butname);
         });
         chbox.each(function() {
             var $chx = $(this);
             $chx.prop('checked', true);
         });
 
         $.ajax({
             type: 'POST',
             url: ajaxurl, // WordPress AJAX URL
             data: {
                 action: 'datasheetchange_display_value',
                 
                 cate_no: selectedCateNo,
                 attribute: butname
                 
                 
             },
             success: function (response) {
                 
                 console.log(response);
             }
         });
 
     });
 
 
 
 
     $('.datasheetdeselect').click(function() {
         var butval = $(this).val();
         var butname = $(this).attr('name');
         var selectedCateNo = $('#hiddenSelectedCateNo').val();
         var chbox = $('.' + butname).filter(function() {
             return $(this).prop('className').includes(butname);
         });
         chbox.each(function() {
             var $chx = $(this);
             $chx.prop('checked', false);
         });
 
         $.ajax({
             type: 'POST',
             url: ajaxurl, // WordPress AJAX URL
             data: {
                 action: 'datasheetdeselect_display_value',
                 
                 cate_no: selectedCateNo,
                 attribute: butname
                 
                 
             },
             success: function (response) {
                 
                 console.log(response);
             }
         });
 
 
     });
 
 
 
     $('#filtersdatasheet input[type="checkbox"]').on('change', function () {
         var isChecked = $(this).is(':checked');
         var attValue = $(this).val();
         var attribute = $('#hiddenAttribute').val();
         var selectedCateNo = $('#hiddenSelectedCateNo').val();
         var checkboxName = $(this).attr('name');
         var checking = $(this).attr('class');
 
         console.log(isChecked);
         console.log(attValue);
         console.log('attri' + attribute);
         console.log('selectcatno' + selectedCateNo);
         console.log('check' + checking);
         if (checking !== 'att_check') {
             // Send an AJAX request when the checkbox changes
             $.ajax({
                 type: 'POST',
                 url: ajaxurl, // WordPress AJAX URL
                 data: {
                     action: 'update_datasheet_value',
                     attValue: attValue,
                     isChecked: isChecked,
                     cate_no: selectedCateNo,
                     attribute: attribute,
                     check: checkboxName,
                     attr: isChecked
                 },
                 success: function (response) {
                     // Handle the response if needed
                     //$('.' + checking).prop('checked', true);
                     if (isChecked) {
                         $('[name="' + checkboxName + '"]').prop('checked', true);
                         console.log("checked");
                         console.log(checking);
                     } else {
                         $('[name="' + checkboxName + '"]').prop('checked', false);
                         console.log("unchecked");
                         console.log(checking);
 
                     }
                     var checkboxes = $('.' + checking).filter(function () {
                         return $(this).prop('className').includes(checking);
                     });
                     var f = 0; // Initialize f as 0 (unchecked)
 
                     checkboxes.each(function () {
                         var $checkbox = $(this);
                         // Check if the current checkbox is checked
                         if ($checkbox.prop('checked')) {
                             f = 1; // Set f to 1 if at least one checkbox is checked
                             $('[name="' + checking + '"]').prop('checked', true);
                             return false; // Exit the loop early since we found a checked checkbox
                         }
                         if (f === 0) {
                             $('[name="' + checking + '"]').prop('checked', false);
                         }
                     });
 
                     //  $('.att_check').prop('checked', true);
                     console.log(response);
                 }
             });
         } else {
 
             // var checkboxes = $('.'+attribute);
             //var selectatt=$(this).val();
             //  var selectatt= $('input[name="att_check"]').attr('class');
             var selectatt = $(this).attr('name');
             console.log('final' + selectatt);
             var jk = $(this).prop('checked');
             var isChec = $(this).is(':checked');
             //var classNameToFind = 'input.' + selectatt;
 
             var checkboxes = $('.' + selectatt).filter(function () {
                 return $(this).prop('className').includes(selectatt);
             });
 
 
 
             console.log(checkboxes);
 
             checkboxes.each(function () {
                 var $checkbox = $(this);
                 // $checkbox.prop('checked', !$checkbox.prop('checked')).trigger('change');
                 // $checkbox.prop('checked', isChec).trigger('change');
                 if (jk)
                     $checkbox.prop('disabled', false);
                 else
                     $checkbox.prop('disabled', true);
             });
 
 
             $.ajax({
                 type: 'POST',
                 url: ajaxurl, // WordPress AJAX URL
                 data: {
                     action: 'block_displayproduct_value',
                     attValue: attValue,
                     isChecked: isChecked,
                     cate_no: selectedCateNo,
                     attribute: selectatt,
                     check: checkboxName,
                     attr: jk
                 },
                 success: function (response) {
 
                     console.log(response);
                 }
             });
 
 
 
 
         }
 
 
     });
 
    
  //}
    }
    init_taw_filter_js();
    if($('#category_form')){
     $('#category_form').on('submit', function(event) {
         event.preventDefault();
         var formData = $(this).serialize();
         $('#loader').show(); // Show loader
         $('#category_results').hide();
 
         $.ajax({
             type: 'POST',
             url: ajaxurl, // WordPress AJAX URL
             data: {
                 action: 'get_category_data', // Action hook for PHP function
                 cate_no: $('#cate_no').val()
             },
             success: function(response) {
                 $('#category_results').html(response);
                 $('#loader').hide(); // Hide loader on success
                 $('#category_results').show();
                 init_taw_filter_js();
             },
             error: function(xhr, status, error) {
                 console.error(xhr.responseText);
                 // Handle errors if needed
                 $('#loader').hide(); // Hide loader on error
 $('#category_results').html('<p style="color:red;"> something went wrong</p>');
         $('#category_results').show();
              }
          });
      });
     }
 
     if($('#filtersdatasheetform')){
         console.log('plolllll')
         $('#filtersdatasheetform').on('submit', function(e) {
         e.preventDefault();
         $('#datasheet_results').hide();
 
         var cate_no = $('#cate_no').val();
         $('#loader').show();
 
         $.ajax({
             url: ajaxurl,
             type: 'POST',
             data: {
                 action: 'get_datasheet_option',
                 cate_no: cate_no
             },
             success: function(response) {
                 $('#loader').hide();
                  console.log(response)
                 if (response) {
                     console.log(response)
                     $('#datasheet_results').html(response);
 
                     init_taw_filter_js(); // Reinitialize any necessary plugin
         $('#datasheet_results').show();
 
                 } else {
                     $('#datasheet_results').html('<p style="color:red;"> something went wrong</p>');
         $('#datasheet_results').show();
 
                 }
             }
         });
     });
    }
  }); 