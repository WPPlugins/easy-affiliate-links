<?php
/**
 * Template for the Easy Affiliate Links FAQ Welcome page.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/menu/faq
 */

?>

<h3>We would love to learn from you!</h3>
<p>
	We want Easy Affiliate Links to become the best affiliate link management plugin it can be and to do so we need your help.
</p>
<p>
    Leave your email address here and you'll receive sporadic surveys (less than 1 per month) that help us improve the plugin.
</p>
<p>
    Your input will help shape the plugin!
</p>
<?php
$current_user = wp_get_current_user();
$email = $current_user->user_email;
$website = get_site_url();
?>
<form action="https://www.getdrip.com/forms/86110294/submissions" method="post" class="eafl-drip-form" data-drip-embedded-form="86110294" target="_blank">
		<div>
				<label for="fields[email]">Email Address</label><br />
				<input type="email" id="fields[email]" name="fields[email]" value="<?php echo esc_attr( $email ); ?>" />
		</div>
		<div>
				<label for="fields[website]">Website</label><br />
				<input type="text" id="fields[website]" name="fields[website]" value="<?php echo esc_attr( $website ); ?>" />
		</div>
	<div>
		<input type="submit" name="submit" value="I want you to help improve the plugin!" class="button button-primary" data-drip-attribute="sign-up-button" />
	</div>
</form>
