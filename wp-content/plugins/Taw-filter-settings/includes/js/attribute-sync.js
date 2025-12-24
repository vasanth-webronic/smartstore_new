jQuery(document).ready(function($) {
    let isProcessing = false;
    let currentState = {};

    $('#start-sync').on('click', function() {
        if (isProcessing) return;
        
        isProcessing = true;
        $(this).prop('disabled', true);
        $('#sync-progress-container').show();
        $('#sync-details').empty();
        
        currentState = {
            step: 'init',
            offset: 0,
            attr_id: null,
            processed: 0
        };
        
        processBatch();
    });

    function processBatch() {
        if (!isProcessing) return;
        
        $.ajax({
            url: sync_vars.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'process_attribute_sync',
                nonce: sync_vars.nonce,
                step: currentState.step,
                offset: currentState.offset,
                attr_id: currentState.attr_id,
                processed: currentState.processed
            },
            success: function(response) {
                if (response.success) {
                    updateUI(response.data);
                    
                    if (response.data.step === 'process_batch') {
                        currentState = {
                            step: 'process_batch',
                            offset: response.data.offset,
                            attr_id: response.data.attr_id,
                            processed: response.data.processed
                        };
                        setTimeout(processBatch, 300);
                    } else {
                        completeProcess();
                    }
                } else {
                    showError(response.data);
                }
            },
            error: function(xhr) {
                showError(xhr.responseJSON?.data || 'Connection error');
                setTimeout(processBatch, 1000);
            }
        });
    }

    function updateUI(data) {
        $('#sync-progress').css('width', data.progress + '%');
        $('#sync-percentage').text(data.progress + '%');
        $('#sync-message').text(data.message);
        $('#processed-count').text(data.processed);
        
        // Add to log
        $('#sync-details').prepend(`<div class="log-entry">${data.message}</div>`);
    }

    function completeProcess() {
        isProcessing = false;
        $('#start-sync').prop('disabled', false);
        $('#sync-details').prepend(
            '<div class="log-success">Synchronization completed successfully!</div>'
        );
    }

    function showError(message) {
        $('#sync-details').prepend(
            `<div class="log-error">Failed: Retring.........</div>`
        );
    }
});