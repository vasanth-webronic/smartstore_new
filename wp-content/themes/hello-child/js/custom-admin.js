jQuery(document).ready(function($) {
    $('a.delete-tag').on('click', function(event) {
        // Prevent the default action of the delete link
        event.preventDefault();
    
        // Extract the tag_ID from the delete link's URL
        var url = $(this).attr('href');
        var tag_ID = getUrlParameter(url, 'tag_ID');
    
        // AJAX request to delete data from taw_filter_setting table
        $.ajax({
            url: ajaxurl, // WordPress AJAX URL
            type: 'POST',
            data: {
                action: 'delete_filter_setting',
                tag_ID: tag_ID
            },
            success: function(response) {
                // Handle success
                console.log('Data deleted successfully');
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(error);
            }
        });
    });
    
    // Function to extract query parameters from URL
    function getUrlParameter(url, name) {
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }
});