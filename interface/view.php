<form action="" method="post">
    <?php wp_nonce_field( $namespace . "_ plugin", $namespace . '_update_wpnonce' );
  ?>
    <input type="hidden" name="form_action" value="update_options" />
    <div class="wrap">
        <h2><?php echo $page_title; ?></h2>
        <div class="tool-box">
            <h3 class="title">Your PriceCrossing Widget Code</h3>
   <p>To quickly retrieve your menu widget code, log into <a href="http://www.pricecrossing.com/">PriceCrossing</a>, select the <Update Menu> option from your user profile menu, and click the "Publish" button (top right of the "update menu" page). Follow the instructions for publishing to a Website and copy the code from the text area and paste it to the text area below.</p>
   <textarea rows="14" cols="80" name="pc_code"><?php echo $this->get_option( 'pc_code' ); ?></textarea>

   <h4>Once you have pasted the code snippet and clicked the "Create Shortcode" button, once you do that you can start use the [my_menu] shortcode to insert your service menu into your wordpress posts or pages.
            </h4>

        </div>

            <p class="submit">
            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Create Shortcode') ?>" />
        </p>
    </div>
</form>