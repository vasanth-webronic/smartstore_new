jQuery(document).ready(function ($) {
    var appliedQty = {};
    var remainingQty = 0;

    // Event listener for when the quantity changes
    $('input[name="quantity"]').on('change', function () {
        var quantity = parseInt($(this).val());
        var wholepackageCount = $('#wholepackageCount').val()

        if (wholepackageCount) {
            let wholePackageSelectedCount = Math.ceil(quantity / wholepackageCount)
            let wholePackageRemains = parseInt(quantity - (wholepackageCount * wholePackageSelectedCount))

        $('#wholePackageSelected').html(`
        <small class="text-right" style="
            width: 40px;
        ">X${wholePackageSelectedCount}<br/><span style="font-size:7px;"> (${quantity} units)<span></small>
        `);

        $('#onlyPspUnits').html('X' + wholePackageRemains)
        }
        $('#totalQtyDply').html(quantity)
        var highestRulePrice = 0;
        var totalOfferPrice = 0;  // To store the total offer price
        var originalPrice = parseFloat($('#orginalPricePsp').text().trim().replace('€', '')); // Assuming the original price is in euros or another currency

        var showPrice = originalPrice * quantity



        // Reset remainingQty to the new quantity
        remainingQty = quantity;

        // Loop through all rules to check for matching quantities (sorting by rule quantity descending)
        var rules = [];
        $('.product-rules .rule').each(function () {
            var ruleQty = parseInt($(this).data('rule-qty'));
            var rulePrice = parseFloat($(this).data('rule-price'));
            rules.push({ ruleQty: ruleQty, rulePrice: rulePrice, element: $(this) });
        });

        // Sort rules by quantity in Ascending order to apply larger smaller first
        rules.sort(function (a, b) {
            return a.ruleQty - b.ruleQty; // Ascending order
        });





        // Remove highlight from all rules once
        rules.forEach(function (rule) {
            rule.element.removeClass('highlighted-rule');
        });

        let ruleToHighlight = null;

        // Find the highest rule that fits the quantity
        rules.forEach(function (rule) {
            if (quantity >= rule.ruleQty) {
                ruleToHighlight = rule; // overwrite to get highest rule
            }
        });

        console.log('ruleToHighlight', ruleToHighlight);


        // Highlight only that rule
        if (ruleToHighlight) {
            ruleToHighlight.element.addClass('highlighted-rule');
        }


    });

    // Trigger the event on page load to handle any initial quantity value
    var initialQuantity = parseInt($('input[name="quantity"]').val());
    $('input[name="quantity"]').trigger('change');
});
