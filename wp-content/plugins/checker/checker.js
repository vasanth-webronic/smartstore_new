jQuery(function($){
    let productIds = [];
    let currentIndex = 0;
    let checking = false;

    $('#checker-start-btn').on('click', function(){
        if(checking) return;
        checking = true;
        $('#checker-progress').show();
        $('#checker-results-table tbody').empty();
        $('#checker-status').text('Fetching product IDs...');

        $.post(checker_ajax_obj.ajax_url, {
            action: 'checker_get_product_ids',
            nonce: checker_ajax_obj.nonce,
        }, function(response){
            if(response.success){
                productIds = response.data;
                $('#checker-status').text(`Found ${productIds.length} products. Starting comparison...`);
                currentIndex = 0;
                compareNextProduct();
            } else {
                $('#checker-status').text('Error fetching product IDs.');
                checking = false;
            }
        });
    });

    function compareNextProduct(){
        if(currentIndex >= productIds.length){
            $('#checker-status').text('Comparison complete.');
            checking = false;
            return;
        }

        let pid = productIds[currentIndex];
        $('#checker-status').text(`Comparing product ID: ${pid} (${currentIndex+1} of ${productIds.length})`);

        $.post(checker_ajax_obj.ajax_url, {
            action: 'checker_compare_product',
            nonce: checker_ajax_obj.nonce,
            product_id: pid
        }, function(response){
            if(response.success){
                let diffs = response.data;
                if(diffs.length > 0){
                    for(let diff of diffs){
                        let row = '<tr>';
                        row += `<td>${diff.product_id}</td>`;
                        row += `<td>${diff.category_name}</td>`;
                        row += `<td>${diff.attribute_name}</td>`;
                        row += `<td>${diff.prod_value}</td>`;
                        row += `<td>${diff.pdf_value}</td>`;
                        row += `<td>${diff.difference}</td>`;
                        row += '</tr>';
                        $('#checker-results-table tbody').append(row);
                    }
                }
            } else {
                $('#checker-status').append(`<br>Error comparing product ID: ${pid}`);
            }

            currentIndex++;
            setTimeout(compareNextProduct, 100);
        });
    }
});
