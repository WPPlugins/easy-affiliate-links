<?php
/**
 * Template for the addons page.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/menu
 */

?>

<div class="wrap eafl-addons">
	<h1><?php echo esc_html_e( 'Add-Ons', 'easy-affiliate-links' ); ?></h1>
	<h2>Easy Affiliate Links - Statistics</h2>
	<?php if ( EAFL_Addons::is_active( 'statistics' ) ) : ?>
	<p>This add-on is active.</p>
	<?php else : ?>
	<ul>
		<li>See the full data for every click</li>
		<li>Analyze clicks over time for any time period</li>
		<li>Compare specific links or link categories for any time period</li>
		<li>Find out which links are performing the best</li>
		<li>Plot the details for any affiliate link</li>
	</ul>
	<a class="button button-primary" href="http://bootstrapped.ventures/downloads/easy-affiliate-links-statistics/" target="_blank">More Information</a>
	<?php endif; // Premium active. ?>
	<h2>Upcoming Add-Ons</h2>
	<p>We have a lot of additional features planned for Easy Affiliate Links.</p>
	<p>Sign up using the form below for more information and to get notified as soon as add-ons are available.</p>
	<?php
	$current_user = wp_get_current_user();
	$email = $current_user->user_email;
	?>
	<form action="https://www.getdrip.com/forms/56279905/submissions" method="post" class="eafl-drip-form" data-drip-embedded-form="56279905" target="_blank">
		<div>
			<label style="font-weight: bold;" for="fields[email]">Email Address</label><br />
			<input type="email" id="fields[email]" name="fields[email]" value="<?php echo esc_attr( $email ); ?>" style="width: 300px; margin: 5px 0 10px;" />
		</div>
		<div>
			<input type="submit" name="submit" value="Keep me in the loop!" class="button button-primary" data-drip-attribute="sign-up-button" />
		</div>
	</form>
</div>
