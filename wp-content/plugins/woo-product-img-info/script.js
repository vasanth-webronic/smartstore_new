jQuery(function($){
  var pointerEve={};
  // Set all variables to be used in scope
  var frame,
      metaBox = $('#_wpii_prod_img_pointer_info_hld'), // Your meta box id here
      addImgLink = metaBox.find('#_wpii_prod_img_add_bg'),
      delImgLink = metaBox.find( '#_wpii_prod_img_remove_bg'),
      imgContainer = metaBox.find( '#_wpii_prod_img_drop_hld'),
      imgId='';

  
  // ADD IMAGE LINK
  addImgLink.on( 'click', function( event ){
    
    event.preventDefault();
    
    // If the media frame already exists, reopen it.
    if ( frame ) {
      frame.open();
      return;
    }
    
    // Create a new media frame
    frame = window.wp.media({
      title: 'Select or Upload Media Of Your Chosen Persuasion',
      button: {
        text: 'Use this media'
      },
      multiple: false  // Set to true to allow multiple files to be selected
    });

    
    
    // When an image is selected in the media frame...
    frame.on( 'select', function() {
      
      // Get media attachment details from the frame state
      var attachment = frame.state().get('selection').first().toJSON();


      // Send the attachment URL to our custom image input field.
      imgContainer.css({"display":"block","background-image":'url("'+attachment.url+'")'});

      imgId=attachment.id;

       // Un-hide the add image link
       addImgLink.css("display","none");

       // Hide the delete image link
        delImgLink.css("display","block");
updateSave();
        // $(".after_dragged_point").css("display","none");
        loadPointer();

      });

    // Finally, open the modal on click
    frame.open();
  });
  
  
  
// DELETE IMAGE LINK
delImgLink.on('click', function (event) {
    event.preventDefault();

    imgId = "";

    // Clear out the preview image
    imgContainer.css({ "background-image": 'none' });

    // Un-hide the add image link
    addImgLink.css("display", "block");

    // Hide the delete image link
    delImgLink.css("display", "none");

    // $(".after_dragged_point").css("display", "none");

  // Reset top and left values when image is deleted
  // var points = $("#_wpii_prod_img_drop_hld .after_dragged_point");
  // points.each(function (index) {
  //     // Calculate the initial top and left values correctly
  //     var newTop = 30 + index * 30;
  //     var newLeft = 30 + index * 30;

  //     $(this).css({ "top": newTop, "left": newLeft });

  //     // Add a console log to check the values
  //     console.log("Index:", index, "New Top:", newTop, "New Left:", newLeft);
  // });

    // Delete the image id from the hidden input
    //imgIdInput.val( '' );
    updateSave();

  });

  function loadPointer(){
    $("#_wpii_prod_img_drag_hld").css("display","block");

    $( "#_wpii_prod_img_drag_hld .drag-point" ).draggable({ 
      revert: "invalid", 
      helper: "clone",
      cursor: "move"
    });  

    $( "#_wpii_prod_img_drop_hld" ).droppable({
        accept: "#_wpii_prod_img_drag_hld .drag-point, .after_dragged_point",
        activeClass: "ui-state-highlight",
        drop: function( event, ui ) {          

          // var count=$("#_wpii_prod_img_drop_hld .badgee").length-1;

           if(!$(ui.draggable).hasClass("after_dragged_point")){
              var randomId="_wpii_info_"+Math.round(Math.random()*10000000);
              var pos = ui.draggable.offset(), dPos = $(this).offset();         
              var left = pos.left - dPos.left;

            // Increment maxKeyCount only for new pointers
            maxKey++;
            
              $(ui.draggable).clone().css({
                 position: 'absolute',
                 top: ui.position.top+10,
                 left: left + ui.position.left-15
              }).addClass('after_dragged_point').text(maxKey).attr("id",randomId).appendTo(this);

              initInnerDraggable();

              $("#"+randomId).trigger("click");
           }
          
        }
    });

  }

   $( "#_wpii_prod_img_delete_hld" ).droppable({
        accept: ".after_dragged_point",
        activeClass: "ui-state-highlight",
        drop: function( event, ui ) { 
            ui.draggable.remove();      
            updateSave();          
        }
    });

  function initInnerDraggable(){
    $( "#_wpii_prod_img_drop_hld .after_dragged_point" ).draggable({
      cursor: "move",
      revert: "invalid",
      stop:function(e,u){
        updateSave();
      }
    });    
  }

  var info_item=$("#_wpii_prod_img_pointer_info");

  $("#_wpii_prod_img_pointer_info #_wpii_prod_img_pointer_info_save").click(function(){
      $("#_wpii_prod_img_pointer_info").css("display","none");
      var v=info_item.find('#_wpii_prod_img_pointer_input').val();     
      $("#"+info_item.attr("item-id")).attr("data-val",v);  

      var t = getSpareTitle(v)
      $("#"+info_item.attr("item-id")).attr("data-title",t);  
      
      var v=info_item.find('#_wpii_prod_img_pointer_min_qty').val();     
      $("#"+info_item.attr("item-id")).attr("data-qty",v);   

      //info_item.find('#_wpii_prod_img_pointer_input').val(""); 
      //info_item.find('#_wpii_prod_img_pointer_min_qty').val(""); 
      updateSave();
  })

  $("#_wpii_prod_img_pointer_info #_wpii_prod_img_pointer_info_remove").click(function(){
      $("#_wpii_prod_img_pointer_info").css("display","none");    
      $("#"+info_item.attr("item-id")).remove();
      updateSave();
  })

  $("#_wpii_prod_img_pointer_info #_wpii_prod_img_pointer_info_close").click(function(){
    $("#_wpii_prod_img_pointer_info").css("display","none");
    var v=info_item.find('#_wpii_prod_img_pointer_input').val();
    var qty=info_item.find('#_wpii_prod_img_pointer_min_qty').val();
    if(v==''){
      $("#"+info_item.attr("item-id")).remove();      
    }else{
      $("#"+info_item.attr("item-id")).attr("data-val",v);   
      $("#"+info_item.attr("item-id")).attr("data-qty",qty);   

      var t = getSpareTitle(v)
      $("#"+info_item.attr("item-id")).attr("data-title",t);  

      // info_item.find('#_wpii_prod_img_pointer_input').val(""); 
      // info_item.find('#_wpii_prod_img_pointer_min_qty').val(""); 
    }
info_item.find('#prod_title').html(""); 
    updateSave();
  })
  
  $(document).on('click', function (event) {
    if (!$(event.target).closest('#_wpii_prod_img_pointer_info').length) {
        // Check if the click is not within the pointer info box or its children
        $('#_wpii_prod_img_pointer_info').hide();
    }
info_item.find('#prod_title').html(""); 
  });
  var selectedPoint;  // Declare the variable outside the click event

  $( "#_wpii_prod_img_drop_hld").undelegate('.after_dragged_point','click').delegate('.after_dragged_point','click',function(){
     event.stopPropagation();
    var pos=$(this).position();
    var id=$(this).attr("id");
    info_item.attr("item-id",id);
    info_item.find('#_wpii_prod_img_pointer_input').val($(this).attr("data-val"));
var artValue =info_item.find('#_wpii_prod_img_pointer_input').val();
    info_item.find('#_wpii_prod_img_pointer_min_qty').val($(this).attr("data-qty"));
    info_item.find('#prod_title').html($(this).attr("data-title"));
// Show or hide the copy button based on whether a point is selected
  
    // var selectedPoint = $("#_wpii_prod_img_drop_hld .after_dragged_point.selected");
    // console.log(selectedPoint);

    if($('#prod_title').html() == '' && artValue){
       var t = getSpareTitle(artValue);
       $("#"+info_item.attr("item-id")).attr("data-title",t);  
       info_item.find('#prod_title').html(t);
    }


    selectedPoint = $(this).hasClass('selected');
    //console.log(selectedPoint);
    if (!selectedPoint) {
        $("#_wpii_prod_img_pointer_info_copy").hide();
    } else {
        $("#_wpii_prod_img_pointer_info_copy").show();
    }

    info_item.css({'top': pos.top - 242, 'left': pos.left - 50, 'display': 'block'});



    
});
// Prevent hiding the pointer info box when clicking on it or its children
$(document).on('click', '#_wpii_prod_img_pointer_info, #_wpii_prod_img_pointer_info *', function (event) {
    event.stopPropagation();
});
// Event listener for quantity input change
  $(document).on('change', '#_wpii_prod_img_pointer_info_min_qty', function () {
    var newQty = $(this).val();

    // Update the quantity for the selected point
    $("#_wpii_prod_img_drop_hld .after_dragged_point.selected").attr("data-qty", newQty);

    // Propagate the quantity change to other points with the same "txt"
    var txt = $("#_wpii_prod_img_drop_hld .after_dragged_point.selected").attr("data-val");
    $("#_wpii_prod_img_drop_hld .after_dragged_point[data-val='" + txt + "']").not(".selected").attr("data-qty", newQty);
    
    // Update the save data
    updateSave();
  });

  function updateSave(){

    var v = [];
    var newTop = 30;
    var newLeft = 0;
var pointerTopIncrement = 30;
    var pointerLeftIncrement = 30; // Set the increment value for left when imgId is empty
    var pointerCount = 19; // Set the number of pointers for each increment
    $("#_wpii_prod_img_drop_hld .after_dragged_point").each(function (index) {
      var txt = $(this).attr("data-val");
      var qty = $(this).attr("data-qty");
var title = $(this).attr("data-title");
    //  console.log(title,'jjjj')
      // Check if imgId is empty and set top and left accordingly
    var top, left;
    if (imgId === "") {
      top = newTop;
      left = Math.floor(index / pointerCount) * pointerLeftIncrement;
      newTop += pointerTopIncrement; // Increment top for the next entry

      // Increment left every 17 pointers
      if (index % pointerCount === pointerCount - 1) {
        newLeft += pointerLeftIncrement;
        newTop = 30; // Reset top for the next row
      }
    } else {
      top = $(this).position().top;
      left = $(this).position().left;
    }
        // // If the image is deleted, set top and left to the calculated values
        // var top = imgId === "" ? newTop + (Math.floor(index / pointerCount) * 30) : $(this).position().top;
      // var left = imgId === "" ? (Math.floor(index / pointerCount) * pointerLeftIncrement) : $(this).position().left;
      
      v.push({ "txt": txt, "top": top, 'left': left, 'qty': qty, 'title':title });
    });

    // Group the items by 'txt' and get the maximum quantity for each 'txt'
    var groupedData = {};
    v.forEach(function (item) {
      console.log(item.qty);
       if (!groupedData[item.txt] ) {
        groupedData[item.txt] = item.qty;
       }
    });
   
    // Update the quantity for each item with the maximum quantity for its 'txt'
    v.forEach(function (item) {
       console.log( groupedData[item.txt]);
      item.qty = groupedData[item.txt];
    });


 //console.log(v);
     var r = { 'imgId': imgId, 'data': v };
if (v.length === 0 && imgId !== "") {
      // If 'v' is empty but imgId has a value, update the 'imgId' and save empty data
      r = { 'imgId': imgId, 'data': [] };
    }

    $("#_wpii_prod_img_pointer_info_tosave").val(JSON.stringify(r));
  }

  function getSpareTitle(artValue) {
    var title = '';
    $.ajax({
        url: ajaxurl, // WordPress AJAX handler
        type: 'POST',
        async: false, // Set to false to make the request synchronous
        data: {
            'action': 'get_article_title',
            'art_no': artValue,
        },
        success: function(response) {
            if (artValue.trim() && response) {
                title = response;
            }else{
              title = 'no product available'
            }
        }
    });
    return title;
}


  var items=imgContainer.closest('.custom-img-container').attr('data-item-img-lst');

  if(typeof items != "undefined"){
    items=JSON.parse(items);
    console.log(items);
    loadPointer();
    
    initInnerDraggable();
  }

  imgId=imgContainer.closest('.custom-img-container').attr('data-item-img');

$("#_wpii_prod_img_drop_hld .after_dragged_point").click(function () {
    // Remove the 'selected' class from all points
    $("#_wpii_prod_img_drop_hld .after_dragged_point").removeClass("selected");

    // Add the 'selected' class to the clicked point
    $(this).addClass("selected");
});

$("#_wpii_prod_img_pointer_info #_wpii_prod_img_pointer_info_copy").click(function () {
  // Get the data-key attribute of the selected point
  var selectedPoint = $("#_wpii_prod_img_drop_hld .after_dragged_point.selected");
  // console.log('selectedPoint:', selectedPoint);

  var selectedKey = selectedPoint.attr("data-key");
  // console.log('selectedKey:', selectedKey);

  if (selectedKey) {
      var container = $("#_wpii_prod_img_drop_hld");

      // Clone the selected point
      var clonedPoint = selectedPoint.clone();

      // Increment the maxKey variable for a consistent and unique ID
      maxKey++;

      var newId = "_wpii_prod_img_drop" + maxKey;

      // Change the ID of the cloned point
      clonedPoint.attr("id", newId);

      // Reset the position of the cloned point
      clonedPoint.css({ top: "+=20px", left: "+=20px" });

      // Remove the 'selected' class from all points
      $("#_wpii_prod_img_drop_hld .after_dragged_point").removeClass("selected");

      // Remove the 'selected' class from the cloned point
      clonedPoint.removeClass("selected");

      // Check if the cloned point has the same 'txt' value as the selected point
      var clonedTxt = clonedPoint.attr("data-val");
      var selectedTxt = selectedPoint.attr("data-val");

      if (clonedTxt === selectedTxt) {
          // Append the cloned point to the drop container
          container.append(clonedPoint);

          // Initialize inner draggable for the cloned point
          initInnerDraggable();

          // Trigger a click on the last added point in the cloned set
          clonedPoint.trigger("click");

          // Update your logic here if needed
          updateSave();
      } else {
          console.log("Cannot copy points with different 'txt' values");
      }
  } else {
      console.log("No element selected");
  }
});
});
