
jQuery(document).ready(function ($) {
   // var cate_no = $('#cate_no').val();
    //var cate_no = $('#cate_no option:selected').text();
   // console.log('CAtegory'+ cate_no);
  
 // if(b!=='att_check'){
    // Attach a change event handler to the checkboxes
    $('input[type="checkbox"]').on('change', function () {
        var isChecked = $(this).is(':checked');
        var attValue = $(this).val();
        var attribute = $('#hiddenAttribute').val();
        var selectedCateNo = $('#hiddenSelectedCateNo').val();
        var checkboxName = $(this).attr('name');
        
        console.log(isChecked);
        console.log(attValue);
        console.log('CAtegoryasdf'+ attribute);
        console.log('CAtegoryasdf'+ selectedCateNo);
        console.log('CAtegoryasdf'+ checkboxName);
        if(checkboxName!=='att_check'){
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
                
            },
            success: function (response) {
                // Handle the response if needed
                console.log(response);
            }
        });
    }else{

       // var checkboxes = $('.'+attribute);
        var selectatt=$(this).val();
        console.log(selectatt);
        var isChec = $(this).is(':checked');
        //var classNameToFind = 'input.' + selectatt;

        var checkboxes = $('.' + selectatt).filter(function() {
            return $(this).prop('className').includes(selectatt);
        });
        
       console.log(checkboxes);
      
            checkboxes.each(function() {
                var $checkbox = $(this);
   // $checkbox.prop('checked', !$checkbox.prop('checked')).trigger('change');
   $checkbox.prop('checked', isChec).trigger('change');
              
            });
            
        }
   

    });
//}
});


