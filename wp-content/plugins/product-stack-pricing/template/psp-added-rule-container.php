<?php
global $wpdb;
$table_name = $wpdb->prefix . 'product_stack_pricing';
$art_no = isset($_POST['artNo']) ? sanitize_text_field($_POST['artNo']) : '';
$status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';


if ($art_no) {
    // Fetch records from the database for the given art_no
    $query = "SELECT * FROM $table_name WHERE art_no = %s";

    if ($status === 'inactive') {
        $query .= " AND status = 0";
    } elseif ($status === 'active') {
        $query .= " AND (status = '1' OR status = '')";
    }

    $rules = $wpdb->get_results(
        $wpdb->prepare($query, $art_no),
        ARRAY_A
    );

    if (!empty($rules)) {
        $filtered_rules = array_filter($rules, function ($rule) {
            return !($rule['qty'] == 0 && $rule['rule_price'] == 0 && empty($rule['users']));
        });
        if (count($filtered_rules) == 0) {
        ?>
            <div class="flex justify-center p-2">No Rule Available</div>
        <?php
        } else {
        ?>
        <div class=" text-center p-2 statusMessage" style="display: none;">No Rule Available for this  <span class="statustxtpsp"></span></div>
            <div class="space-y-4">

                <?php foreach ($filtered_rules as $rule) :
                            $status_text = $rule['status'] == 0 ? 'inactive' : 'active';
                            ?>

                    <div class="border p-4 rounded bg-white prodaddedrull <?php echo $status_text;?>">
                        <div class="flex justify-between items-center mb-2">
                            <div class="font-extrabold text-lg flex gap-4 text-black">
                                <div class="border-[#F7F7F7] border  rounded-md overflow-hidden"><span class="p-2 px-4 bg-[#F7F7F7]">Minimum QTY</span> <span class="psp-rule-qty px-2 p-2 text-psp-blue"><?php echo esc_html($rule['qty']); ?></span></div>
                                <div class="border-[#F7F7F7] border  rounded-md overflow-hidden">
                                    <span class="p-2 px-2 bg-[#F7F7F7]">Special Unit Price </span><span class="psp-rule-price px-2 p-2 text-psp-blue"><?php echo esc_html($rule['rule_price']); ?></span>
                                </div>
                            </div>
                            <div>
                                <button class="text-psp-blue mr-2 edit-rule-btn" data-id="<?php echo esc_html($rule['id']); ?>"><img class="w-7 rounded-full p-1 bg-psp-blue h-7" src="<?php echo PRODUCT_STACK_PRICING_URL; ?>img/MaterialSymbolsEditOutline.svg" alt=""></button>
                                <button class="text-psp-red delete-rule-btn" data-artno="<?php echo esc_html($art_no); ?>" data-description="<span class=' text-black'> Minimum QTY </span><span class=' text-psp-red'><?php echo esc_html($rule['qty']); ?></span> <span class=' text-psp-red'><span class=' text-black'> Special Unit Price </span> <?php echo esc_html($rule['rule_price']); ?></span><span class=' text-black'> for </span>(<?php echo esc_html($rule['role']); ?>)" data-id="<?php echo esc_html($rule['id']); ?>"><img class="w-7 rounded-full p-1 bg-psp-red h-7" src="<?php echo PRODUCT_STACK_PRICING_URL; ?>img/MaterialSymbolsCancelRounded.svg" alt=""></button>
                            </div>
                        </div>
                        <div class="flex gap-4 my-4 justify-between items-center">
                            <?php
                            $roles = get_editable_roles();
                            $role_id = $rule['role'];
                            $role_name = isset($roles[$role_id]['name']) ? $roles[$role_id]['name'] : '';
                            ?>
                            <div class="flex gap-4 my-4  items-center"><div class="text-black font-bold text-md">Role</div><span class="psp-rule-role font-extrabold bg-[#F7F7F7] px-4 py-2 rounded-xl text-black"><?php echo esc_html($role_name); ?></span></div>
                            <?php
                            $status_org = $rule['status'];
                            $background_color = $status_org == 1 ? 'green' : 'red';
                            ?>
                            <div class="p-2 px-4 text-white rounded-xl" style="background:<?php echo $background_color; ?>;">
                                <?php echo $status_text; ?>
                            </div>
                        </div>
                        <div class="p-2 px-4 bg-[#F7F7F7] flex flex-col gap-4">
                            <div>
                                <div class="text-black font-bold text-md">Users</div>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <?php
                                $enable_all_users = isset($rule['enable_all_users']) && $rule['enable_all_users'] == '1' ? 1 : 0;

                                if ($enable_all_users) {
                                    // Fetch all users for the specified role
                                    $args = array(
                                        'role' => $role_id,
                                        'orderby' => 'user_nicename',
                                        'order' => 'ASC',
                                    );
                                    $users = get_users($args);
                                } else {
                                    // Fetch only the selected users
                                    $user_ids = explode(',', $rule['users']);
                                    $users = array();
                                    foreach ($user_ids as $user_id) {
                                        $user_info = get_userdata($user_id);
                                        if ($user_info) {
                                            $users[] = $user_info;
                                        }
                                    }
                                }

                                // Display users
                                foreach ($users as $user) :
                                    $customer_no = get_user_meta($user->ID, 'customer_no', true);
                                    if (empty($customer_no)) {
                                        $customer_no = get_user_meta($user->ID, 'subcustomer_no', true);
                                    }
                                    if (empty($customer_no)) {
                                        $customer_no = "No Customer Number"; // Fallback to user ID if no customer number
                                    }
                                ?>
                                    <div class="bg-white p-2 rounded border cursor-pointer hover:bg-slate-50">
                                        <h4 class="font-semibold"><?php echo esc_html($user->display_name); ?></h4>
                                        <p><?php echo esc_html($customer_no); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>


                    </div>
                <?php endforeach; ?>
            </div>
        <?php
        }
    } else {
        ?>
        <div class="flex justify-center p-2">Add some Rule</div>
    <?php
    }
} else {
    ?>
    <div class="flex justify-center items-center h-full w-full p-2">Click on a product to view the stack rules</div>
<?php
}
?>