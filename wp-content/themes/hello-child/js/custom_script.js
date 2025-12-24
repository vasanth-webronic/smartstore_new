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
});



