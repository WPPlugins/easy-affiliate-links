<?php
/**
 * Template for the Easy Affiliate Links FAQ.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/menu
 */

// Subpage.
$sub = isset( $_GET['sub'] ) ? sanitize_key( wp_unslash( $_GET['sub'] ) ) : ''; // Input var okay.

$tabs = apply_filters( 'eafl_statistics_tabs', array(
	'data' => __( 'Data', 'easy-affiliate-links' ),
) );

if ( ! array_key_exists( $sub, $tabs ) ) {
	$sub = 'data';
}
?>

<div class="wrap eafl-statistics">
	<h2><?php esc_html_e( 'Statistics', 'easy-affiliate-links' ); ?></h2>

	<h2 class="nav-tab-wrapper">
		<?php
		foreach ( $tabs as $tab => $label ) {
			$url = add_query_arg( 'sub', $tab, admin_url( 'admin.php?page=eafl_statistics' ) );
			$active = $sub === $tab ? ' nav-tab-active' : '';

			echo '<a href="' . esc_url( $url ) . '" class="nav-tab' . esc_attr( $active ) . '">' . esc_html( $label ) . '</a>';
		}
		?>
	</h2>

	<?php do_action( 'eafl_statistics_page', $sub ); ?>
</div>