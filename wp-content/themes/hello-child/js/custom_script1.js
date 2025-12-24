document.addEventListener('DOMContentLoaded', function () {

    if (window.innerWidth <= 1024) {
    
    // For add padding mega menu to visible all menu
    var megaMenuItem = document.getElementById('mega-menu-menu-1');

    if (megaMenuItem) {
        // Add 100px of padding to the bottom
        megaMenuItem.style.paddingBottom = '100px';
    }
    
    // For add padding mega menu to visible all menu -- end

    let productImgMenu = document.querySelector("#mega-menu-440-0 ul");
    if (!productImgMenu) {
      productImgMenu = document.querySelector("#mega-menu-29164-0 ul");
    }
    let liElements = productImgMenu.querySelectorAll('li ul.mega-sub-menu');



    liElements.forEach(function (ul) {
        let liList = ul.querySelectorAll('li');
        let blockLi = liList[2];

        // Initially hide the blockLi
        blockLi.style.display = "none";


        liList.forEach(function (li, index) {
            if (index < 2) {

                
                li.addEventListener('click', function (event) {
                    event.preventDefault();

                    // Toggle the display of the blockLi within this ul
                    if (blockLi.style.display === "none") {
                        blockLi.style.display = "block";
                    } else {
                        blockLi.style.display = "none";
                    }
                    

                    // Hide the blockLi in other liLists
                    liElements.forEach(function (otherUl) {
                        if (otherUl !== ul) {
                            otherUl.querySelectorAll('li')[2].style.display = "none";
                        }
                    });
                });
            }
        });
    });
}

var placeholderElements = document.querySelectorAll('.placeholder-image, .placeholder-edit','#colorCode');

if(placeholderElements){
// Iterate through each element and click its parent
placeholderElements.forEach(function (placeholderElement) {

// Add a click event listener to each placeholder element
placeholderElement.addEventListener('click', function (e) {
    e.stopPropagation();
    // Get the parent node of the clicked placeholder element
    var parentElement = e.currentTarget.parentElement;

    // Find the input element within the parent
    var inputElement = parentElement.querySelector('input');

    // Trigger a click on the parent element
    if (inputElement) {
        inputElement.click();
    }
});
});
}

var colorCodeDisplayInput = document.getElementById('account_company_theme')
if(colorCodeDisplayInput){
var selectedColor = document.querySelector('.selected-color-text').value;
var colorCodeDisplay = document.querySelector('#colorCode');

if(selectedColor){

// Set the value to the input with id 'account_company_theme'
var colorCodeDisplayInput = document.getElementById('account_company_theme').value = selectedColor;
colorCodeDisplay.innerHTML = selectedColor;
//console.log(selectedColor)
var parsedColor = parseColor(selectedColor);
// Calculate contrast ratio
var contrastRatio = getContrastRatio(parsedColor, [0, 0, 0, 0]);





if (!isNaN(contrastRatio)) {
    if (contrastRatio >= 4.5) {
        colorCodeDisplay.style.color = '#000'; // Dark text color
    } else {
        colorCodeDisplay.style.color = '#fff'; // Light text color
    }
}
}

}





var colorThemeEdit = document.querySelector('.placeholder-edit-cl');

if(colorThemeEdit){
    // Add a click event listener to each placeholder element
colorThemeEdit.addEventListener('click', function (e) {
    e.stopPropagation();
     var colorPickerContainer = document.querySelector('.colorPickerContainer');
    colorPickerContainer.style.display = 'flex';
});
}

// Assuming you have a file input element with the id 'account_company_logo'
var fileInput = document.getElementById('account_company_logo');

if(fileInput){
// Find the parent element of the input
var parentElement = fileInput.parentElement;

// Find the .placeholder-edit and trash icon within the parent
var editIcon = parentElement.querySelector('.placeholder-edit');
var trashIcon = parentElement.querySelector('.placeholder-trash');

if ( fileInput.style.backgroundImage && fileInput.style.backgroundImage !== 'url("")' && fileInput.style.backgroundImage !== '' && fileInput.style.backgroundImage !== 'initial' && fileInput.dataset.bg !== '') {
    // Show the edit and trash icons
    if (editIcon) {
        editIcon.style.display = 'block !important';
    }
    if (trashIcon) {
        trashIcon.style.display = 'block !important';
    }
} else {
    // Hide the edit and trash icons
    if (editIcon) {
        editIcon.style.display = 'none !important';
    }
    if (trashIcon) {
        trashIcon.style.display = 'none !important';
    }
}
}


// Assuming you have an element with the id 'account_company_theme'
var companyThemeElement = document.getElementById('account_company_theme');
if(companyThemeElement){
// Check if the color is not #ffffff
if (companyThemeElement.value !== '#FFFFFF' && companyThemeElement.value !== '#ffffff') {
    companyThemeElement.addEventListener('click',(e)=>{
    e.preventDefault()
         })
    // Find the parent element of the company theme element
    var parentElement = companyThemeElement.parentElement;

    // Find the .placeholder-edit icon within the parent
    var editIcon = parentElement.querySelector('.placeholder-edit-cl');
    var colorCode = parentElement.querySelector('#colorCode');

    // Display the edit icon
    if (editIcon) {
        editIcon.style.display = 'block';
    }
    if (colorCode) {
        colorCode.style.display = 'block';
    }
}else {
    companyThemeElement.addEventListener('click',(e)=>{
    e.preventDefault()
    var colorPickerContainer = document.querySelector('.colorPickerContainer');
    colorPickerContainer.style.display = 'flex';
      })

}



}



});


// Function to calculate contrast ratio
function getContrastRatio(color1, color2) {
var lum1 = getRelativeLuminance(color1[0], color1[1], color1[2]);
var lum2 = getRelativeLuminance(color2[0], color2[1], color2[2]);
var contrast = (Math.max(lum1, lum2) + 0.05) / (Math.min(lum1, lum2) + 0.05);
return contrast;
}

// Function to calculate relative luminance
function getRelativeLuminance(R, G, B) {
R = R / 255;
G = G / 255;
B = B / 255;

R = R <= 0.03928 ? R / 12.92 : Math.pow((R + 0.055) / 1.055, 2.4);
G = G <= 0.03928 ? G / 12.92 : Math.pow((G + 0.055) / 1.055, 2.4);
B = B <= 0.03928 ? B / 12.92 : Math.pow((B + 0.055) / 1.055, 2.4);

return 0.2126 * R + 0.7152 * G + 0.0722 * B;
}


// Function to parse color string into rgba values
function parseColor(color) {
    color = color.toString();
    var hexMatch = color.match(/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/);

if (hexMatch) {
    // Hex to RGBA conversion
    var hexValue = hexMatch[1];
    var bigint = parseInt(hexValue, 16);
    var r = (bigint >> 16) & 255;
    var g = (bigint >> 8) & 255;
    var b = bigint & 255;
    var a = 1; // Set alpha to 1 (fully opaque)
    return [r, g, b, a];
}
    var match = color.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*([\d.]+))?\)$/);
    return match ? [parseInt(match[1]), parseInt(match[2]), parseInt(match[3]), parseFloat(match[4] || 1)] : [];
}
function previewImage(input) {
var fileInput = input;
var files = fileInput.files;
// Find the parent element of the input
var parentElement = fileInput.parentElement;

// Find the .placeholder-edit and trash icon within the parent
var editIcon = parentElement.querySelector('.placeholder-edit');
var trashIcon = parentElement.querySelector('.placeholder-trash');

if (files.length > 0) {
    var reader = new FileReader();

    reader.onload = function (e) {
        // Set the background image directly, no need for !important in JS
        fileInput.style.backgroundImage = 'url(' + e.target.result + ')';
        fileInput.style.backgroundSize = 'contain';
        fileInput.style.backgroundPosition = 'center';
        fileInput.style.backgroundRepeat = 'no-repeat';

        // Find the parent element of the input
        var parentElement = fileInput.parentElement;

        // Find the .placeholder-edit icon within the parent
        var editIcon = parentElement.querySelector('.placeholder-edit');
        var trashInput = document.querySelector('#account_company_logo_trash_input');

        trashInput.value = "1";

        // Show the edit icon
        if (editIcon) {
            editIcon.style.display = 'block';

        }
    };

    reader.readAsDataURL(files[0]);
} else {
    if (fileInput.style.backgroundImage && fileInput.style.backgroundImage !== '' && fileInput.style.backgroundImage !== 'initial' || fileInput.dataset.bg) {
        // Show the edit and trash icons
        if (editIcon) {
            editIcon.style.display = 'block';
        }
        if (trashIcon) {
            trashIcon.style.display = 'block';
        }
    } else {
        // Hide the edit and trash icons
        if (editIcon) {
            editIcon.style.display = 'none';
        }
        if (trashIcon) {
            trashIcon.style.display = 'none';
        }
    }
}



// Hide the edit icon
if (editIcon) {
    editIcon.style.display = 'block';
}

// Hide the trash icon
if (trashIcon) {
    trashIcon.style.display = 'block';
}


}
// Function to show the delete alert
function showDeleteAlert() {
var deleteAlert = document.getElementById('company_logo_delete_popup_container');
deleteAlert.style.display = 'flex';
}

// Function to close the delete alert
function closeDeleteAlert() {
var deleteAlert = document.getElementById('company_logo_delete_popup_container');
deleteAlert.style.display = 'none';
}

// Function to close the confirm alert
function confirmDeleteAlert() {
var deleteAlert = document.getElementById('company_logo_delete_popup_container');
// Assuming you have a file input element with the id 'account_company_logo'
var fileInput = document.getElementById('account_company_logo');

// Find the parent element of the input
var parentElement = fileInput.parentElement;

// Find the .placeholder-edit and trash icon within the parent
var editIcon = parentElement.querySelector('.placeholder-edit');
var trashIcon = parentElement.querySelector('.placeholder-trash');
var trashInput = document.querySelector('#account_company_logo_trash_input');

deleteAlert.style.display = 'none';

if (editIcon) {
    editIcon.style.display = 'none';
}
if (trashIcon) {
    trashIcon.style.display = 'none';
}
if (fileInput) {
    fileInput.style.backgroundImage = 'none';
    fileInput.style.backgroundSize = 'contain';
    fileInput.style.backgroundPosition = 'center';
    fileInput.style.backgroundRepeat = 'no-repeat';
}
trashInput.value = "";

}


    

     function setColor() {
    // Get the value from the input field
    var selectedColor = document.querySelector('.selected-color-text').value;
    var colorCodeDisplay = document.querySelector('#colorCode');


    // Set the value to the input with id 'account_company_theme'
    var colorCodeDisplayInput = document.getElementById('account_company_theme').value = selectedColor;
    colorCodeDisplay.innerHTML = selectedColor;
        var parsedColor = parseColor(selectedColor);
        // Calculate contrast ratio
        var contrastRatio = getContrastRatio(parsedColor, [0, 0, 0, 0]);




        if (!isNaN(contrastRatio)) {
            if (contrastRatio >= 4.5) {
                colorCodeDisplay.style.color = '#000'; // Dark text color
            } else {
                colorCodeDisplay.style.color = '#fff'; // Light text color
            }
        }

        if (selectedColor !== '#FFFFFF' || selectedColor !== '#ffffff') {
                
    var companyThemeElement = document.getElementById('account_company_theme');
    var parentElement = companyThemeElement.parentElement;

    // Find the .placeholder-edit icon within the parent
    var editIcon = parentElement.querySelector('.placeholder-edit-cl');
    var placeholder = parentElement.querySelector('#colorpalletIcon');
    var colorCode = parentElement.querySelector('#colorCode');

    if (editIcon) {
        editIcon.style.display = 'block';
    }
    if (colorCode) {
        colorCode.style.display = 'block';
    }
     if (placeholder) {
        placeholder.style.display = 'none';
    }
               
            } 


    closeColorPopUp();
    }

    function closeColorPopUp() {
        // Close the color picker popup by hiding it or removing it from the DOM
        var colorPickerContainer = document.querySelector('.colorPickerContainer');
        colorPickerContainer.style.display = 'none';
    }

                function updateColorPicker(input) {
        var colorPicker = input;
        var colorCodeDisplay = colorPicker.parentElement.querySelector('#colorCode');
        var editIcon = colorPicker.parentElement.querySelector('.placeholder-edit-cl');
        var placeholderImage = colorPicker.parentElement.querySelector('.placeholder-image');


        // Update the color code display

        colorCodeDisplay.innerHTML = colorPicker.value;
        var parsedColor = parseColor(colorPicker.value);
        // Calculate contrast ratio
        var contrastRatio = getContrastRatio(parsedColor, [0, 0, 0, 0]);




        if (!isNaN(contrastRatio)) {
            if (contrastRatio >= 4.5) {
                colorCodeDisplay.style.color = '#000'; // Dark text color
            } else {
                colorCodeDisplay.style.color = '#fff'; // Light text color
            }
        }

        // Show or hide the edit icon based on color change
        if (colorPicker.value !== colorPicker.defaultValue) {
            colorCodeDisplay.style.display = 'block';
            editIcon.style.display = 'block';
            editIcon.style.color = '#fff';
            placeholderImage.style.display = 'none';
        } else {
            editIcon.style.display = 'none';
            editIcon.style.color = '#666';
            colorCodeDisplay.style.display = 'none';
            placeholderImage.style.display = 'block';
        }

    }

 