<?php
/**
 * Template for the manage pages.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/menu
 */

// Subpage.
$sub = isset( $_GET['sub'] ) ? sanitize_key( wp_unslash( $_GET['sub'] ) ) : ''; // Input var okay.

$tabs = apply_filters( 'eafl_manage_tabs', array(
	'links' => __( 'Affiliate Links', 'easy-affiliate-links' ),
) );

if ( ! array_key_exists( $sub, $tabs ) ) {
	$sub = 'links';
}
?>

<div class="wrap eafl-manage">
	<h2><?php esc_html_e( 'Manage', 'easy-affiliate-links' ); ?></h2>

	<h2 class="nav-tab-wrapper">
		<?php
		foreach ( $tabs as $tab => $label ) {
			$url = add_query_arg( 'sub', $tab, admin_url( 'admin.php?page=easyaffiliatelinks' ) );
			$active = $sub === $tab ? ' nav-tab-active' : '';

			echo '<a href="' . esc_url( $url ) . '" class="nav-tab' . esc_attr( $active ) . '">' . esc_html( $label ) . '</a>';
		}
		?>
	</h2>

	<?php do_action( 'eafl_manage_page', $sub ); ?>
</div>
