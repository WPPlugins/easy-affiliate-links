<?php
/**
 * Template for the Easy Affiliate Links FAQ Getting Started page.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/menu/faq
 */

?>

<h3>Adding Links</h3>
<p>
	When creating or editing a regular post or page you should see the "Easy Affiliate Links" button in the post editor. Click that button to insert links.
</p>
<img src="<?php echo esc_attr( $img_dir ); ?>/editor-button.png" />
<h3>Editing Links</h3>
<p>
	Click on an affiliate link in the visual Editor to edit it or go to the <em>Easy Affiliate Links > Manage</em> page to edit existing links.
</p>
<h3>I need more help!</h3>
<p>
	Take a look at the <a href="http://bootstrapped.ventures/easy-affiliate-links/" target="_blank">Easy Affiliate Links website</a> for more documentation and demos. Or just <a href="<?php echo esc_url( add_query_arg( 'sub', 'support', admin_url( 'admin.php?page=eafl_faq' ) ) ); ?>">contact us</a> if you have any questions at all!
</p>
