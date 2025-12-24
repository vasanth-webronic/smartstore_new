jQuery(document).ready(function ($) {
    let processing = false;

    function updateProgress(log, percent) {
        $('#avp_log').text(log);
        $('#avp_progress_bar').css('width', percent + '%');
        // Scroll to bottom
        let logBox = $('#avp_log');
        logBox.scrollTop(logBox[0].scrollHeight);
    }

    function processBatch() {
        $.ajax({
            url: avp_ajax.ajax_url,
            method: 'POST',
            data: {
                action: 'avp_process_batch',
                nonce: avp_ajax.nonce,
            },
            success: function (response) {
                if (response.success) {
                    updateProgress(response.data.log, response.data.progress_percent);

                    if (!response.data.done) {
                        // Continue processing next batch after small delay
                        setTimeout(processBatch, 300);
                    } else {
                        updateProgress(response.data.log + "\n\nProcess completed successfully!", 100);
                        processing = false;
                        $('#avp_start_process').prop('disabled', false);
                    }
                } else {
                    alert('AJAX error: ' + response.data);
                    processing = false;
                    $('#avp_start_process').prop('disabled', false);
                }
            },
            error: function (xhr, status, error) {
                alert('AJAX request failed: ' + error);
                processing = false;
                $('#avp_start_process').prop('disabled', false);
            }
        });
    }

    $('#avp_start_process').on('click', function () {
        if (processing) return;
        processing = true;
        $('#avp_start_process').prop('disabled', true);
        $('#avp_log').text('');
        $('#avp_progress_bar').css('width', '0%');

        // Clear previous option data (start fresh)
        $.post(avp_ajax.ajax_url, {
            action: 'avp_reset_progress',
            nonce: avp_ajax.nonce,
        }, function () {
            // Start processing batches
            processBatch();
        });
    });
});
