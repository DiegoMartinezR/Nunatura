<?php

/**
 * Headwall WP Tutorials WooCommerce User Registration (WPTWCUR)
 *
 * https://wp-tutorials.tech/refine-wordpress/user-login-and-registration-with-custom-fields/
 *
 * NOTE: This is based on the user-registration part of the strandard
 *       WooCommerce login form. You can find the original in the WooCommerce
 *       plugin.
 *       wp-content/plugins/woocommerce/templates/myaccount/form-login.php
 */

// Block direct access
defined('WPINC') || die();

// Add the title of your registration form here, or comment it out.
printf(
   '<h2>%s</h2>',
   esc_html__('Crear mi cuenta', 'woocommerce')
);

do_action('woocommerce_before_customer_login_form');

?>
<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag');?> >

   <?php do_action('woocommerce_register_form_start');?>

   <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
      <label for="reg_email"><?php esc_html_e('Email address', 'woocommerce');?>&nbsp;<span class="required">*</span></label>
      <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
   </p>

   <?php if ('no' === get_option('woocommerce_registration_generate_password')): ?>

      <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
         <label for="reg_password"><?php esc_html_e('Password', 'woocommerce');?>&nbsp;<span class="required">*</span></label>
         <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
      </p>

   <?php else: ?>

      <p><?php esc_html_e('A link to set a new password will be sent to your email address.', 'woocommerce');?></p>

   <?php endif;?>

   <?php do_action('woocommerce_register_form');?>

   <p class="woocommerce-form-row form-row">
      <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce');?>
      <button type="submit" class="woocommerce-Button woocommerce-button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e('Register', 'woocommerce');?>"><?php esc_html_e('Register', 'woocommerce');?></button>
   </p>

   <?php do_action('woocommerce_register_form_end');?>

</form>
<?php
do_action('woocommerce_after_customer_login_form');