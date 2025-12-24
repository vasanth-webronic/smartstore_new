document.addEventListener('DOMContentLoaded', function () {
    // Set the maximum character count per line
    var maxCharCountPerLine = 139; // Change this to your desired character limit per line
    var maxLineCount = 16; // Change this to your desired line limit

    // Get the product description textarea element using the WordPress editor
    var productDescription = document.getElementById('content'); // 'content' is the ID of the WordPress editor textarea

    // Flag to track whether the modal has been displayed
    var modalDisplayed = false;

    // Function to update character count and line count
    function updateCounts() {
        // Get the current text
        var text = productDescription.value;

        // Calculate total character count and consider new lines as 139 characters each
        var totalCharCount = text.replace(/\n$/, '').length + (text.split('\n').length - 1) * maxCharCountPerLine;
        var totalLineCount = Math.ceil(totalCharCount / maxCharCountPerLine);

        // Update the character count and line count in the countContainer
        var countContainer = document.getElementById('wp-word-count');
        countContainer.innerHTML = `Lines count: ${totalLineCount}`;
    }

    // Initial line count
    updateCounts();

    // Add input event listener
    productDescription.addEventListener('input', function () {
        // Get the current text
        var text = productDescription.value;

        // Calculate total character count and consider new lines as 139 characters each
        var totalCharCount = text.replace(/\n$/, '').length + (text.split('\n').length - 1) * maxCharCountPerLine;
        var totalLineCount = Math.ceil(totalCharCount / maxCharCountPerLine);

        // Check if the total line count exceeds the limit and the modal has not been displayed yet
        if (totalLineCount > maxLineCount && !modalDisplayed) {
            // Set the flag to indicate that the modal has been displayed
            modalDisplayed = true;

// Display the error message
            displayErrorMessage();
        } else if (totalLineCount <= maxLineCount && modalDisplayed) {
            // Reset the flag if the line count is within the limit
            modalDisplayed = false;
        }

        // Update the character count and line count in the countContainer
        updateCounts();
    });

    // Function to display the error message modal
    function displayErrorMessage() {
            // Create a custom overlay
            var overlay = document.createElement('div');
            overlay.className = 'custom-overlay';
            document.body.appendChild(overlay);

            // Create a custom modal
            var modal = document.createElement('div');
            modal.className = 'custom-modal';
            modal.innerHTML = `
                <style>
                .custom-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.5);
                    z-index: 1000;
                }

                .custom-modal {
                    width: 300px;
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background-color: white;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
                    padding: 20px;
                    z-index: 1001;
                }

                .modal-content {
                    display: flex;
                    flex-direction: column;
                }

                .modal-body {
                    margin-bottom: 20px;
                }

                .modal-footer {
                    display: flex;
                    justify-content: flex-end;
                }

                .btn {
                    cursor: pointer;
                    padding: 10px;
                    background-color: #007bff;
                    color: white;
                    border: none;
                    border-radius: 5px;
                }

                .btn:hover {
                    background-color: #0056b3;
                }
                </style>
                <div class="custom-modal">
                    <div class="modal-content">
                        <div class="modal-body">
                            Line count exceeds the limit. Maximum allowed lines: ${maxLineCount}. Do you want to continue typing?
                        </div>
                        <div class "modal-footer">
                            <button class="btn btn-primary">OK</button>
                        </div>
                    </div>
                </div>
            `;

            // Append the modal to the document body
            document.body.appendChild(modal);

            // Disable the textarea
            productDescription.setAttribute('readonly', 'true');

            // Add event listener to OK button
            modal.querySelector('.btn').addEventListener('click', function () {

                // Remove the overlay and modal
                overlay.parentNode.removeChild(overlay);
                modal.parentNode.removeChild(modal);

                // Enable the textarea
                productDescription.removeAttribute('readonly');
            });
        }
    });
