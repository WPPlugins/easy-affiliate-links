<?php
/**
 * Template for the modal.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/modal
 */

?>

<div class="eafl-modal-container">
	<div class="eafl-modal wp-core-ui">
		<button type="button" class="button-link eafl-modal-close"><span class="eafl-modal-icon"><span class="screen-reader-text"><?php esc_html_e( 'Close Modal', 'easy-affiliate-links' ); ?></span></span></button>

		<div class="eafl-modal-content">
			<div class="eafl-frame wp-core-ui">
				<div class="eafl-frame-menu">
					<div class="eafl-menu">
						<?php
						$active_menu = '';
						foreach ( $menu as $menu_item => $options ) {
							$active = isset( $options['default'] ) && $options['default'];
							$active_class = $active ? ' active' : '';
							$label = isset( $options['label'] ) ? $options['label'] : '';
							$default_tab = isset( $options['default_tab'] ) ? $options['default_tab'] : '';

							if ( $active ) {
								$active_menu = $label;
							}

							echo '<a href="#" class="eafl-menu-item' . esc_attr( $active_class ) . '" data-menu="' . esc_attr( $menu_item ) . '" data-tab="' . esc_attr( $menu_item ) . '-' . esc_attr( $default_tab ) . '">' . esc_html( $label ) . '</a>';
						}
						?>
						<div class="eafl-menu-hidden">
							<?php echo esc_html__( "You're currently editing a link.", 'easy-affiliate-links' ) . ' ' . esc_html__( 'Use the "Easy Affiliate Links" button to access other features.', 'easy-affiliate-links' ); ?>
						</div>
					</div>
				</div>
				<div class="eafl-frame-title">
					<h1><?php echo esc_html( $active_menu ); ?><span class="dashicons dashicons-arrow-down"></span></h1>
				</div>

				<div class="eafl-frame-router">
					<?php
					foreach ( $menu as $menu_item => $options ) {
						$active = isset( $options['default'] ) && $options['default'];
						$active_class = $active ? ' active' : '';
						$default_tab = $active && isset( $options['default_tab'] ) ? $options['default_tab'] : '';

						echo '<div id="eafl-menu-' . esc_attr( $menu_item ) . '" class="eafl-router' . esc_attr( $active_class ) . '">';

						foreach ( $options['tabs'] as $tab => $tab_options ) {
							$tab_uid = $menu_item . '-' . $tab;
							$tab_class = $default_tab === $tab ? ' active' : '';
							$label = isset( $tab_options['label'] ) ? $tab_options['label'] : '';
							$callback = isset( $tab_options['callback'] ) ? $tab_options['callback'] : '';
							$init = isset( $tab_options['init'] ) ? $tab_options['init'] : '';
							$button = isset( $tab_options['button'] ) ? $tab_options['button'] : __( 'Insert', 'easy-affiliate-links' );

							echo '<a href="#" class="eafl-menu-item' . esc_attr( $tab_class ) . '" data-tab="' . esc_attr( $tab_uid ) . '" data-callback="' . esc_attr( $callback ) . '" data-init="' . esc_attr( $init ) . '" data-button="' . esc_attr( $button ) . '">' . esc_html( $label ) . '</a>';
						}

						echo '</div>';
					}
					?>
				</div>
				<div class="eafl-frame-content">
					<?php
					foreach ( $menu as $menu_item => $options ) {
						$active = isset( $options['default'] ) && $options['default'];
						$default_tab = $active && isset( $options['default_tab'] ) ? $options['default_tab'] : '';

						foreach ( $options['tabs'] as $tab => $tab_options ) {
							$tab_uid = $menu_item . '-' . $tab;
							$tab_class = $default_tab === $tab ? ' active' : '';
							$label = isset( $tab_options['label'] ) ? $tab_options['label'] : '';
							$template = isset( $tab_options['template'] ) ? $tab_options['template'] : '';

							echo '<div id="eafl-tab-' . esc_attr( $tab_uid ) . '" class="eafl-frame-content-tab' . esc_attr( $tab_class ) . '">';

							if ( file_exists( $template ) ) {
								include( $template );
							}

							echo '</div>';
						}
					}
					?>
				</div>

				<div class="eafl-frame-toolbar">
					<div class="eafl-toolbar">
						<div class="eafl-toolbar-primary search-form">
							<button type="button" class="button eafl-button button-primary button-large eafl-button-action"><?php esc_html_e( 'Insert', 'easy-affiliate-links' ); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="eafl-modal-backdrop"></div>

</div>
