jQuery(document).ready(function ($) {
    // var cate_no = $('#cate_no').val();
    //var cate_no = $('#cate_no option:selected').text();
    // console.log('CAtegory'+ cate_no);

    // if(b!=='att_check'){

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
});


