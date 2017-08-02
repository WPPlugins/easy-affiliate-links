<?php
/**
 * Template for the Link Details tab in the modal.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/modal/tabs
 */

?>

<div class="eafl-link-form eafl-link-details-form">
	<div class="eafl-form-section">
		<div class="eafl-form-line">
			<div class="eafl-form-label">
				<label for="eafl-link-name"><?php esc_html_e( 'Name', 'easy-affiliate-links' ); ?></label>
			</div>
			<div class="eafl-form-input">
				<input type="text" id="eafl-link-name" />
			</div>
			<div class="eafl-form-description">
				<?php esc_html_e( 'Use this to identify the link.', 'easy-affiliate-links' ); ?> <?php esc_html_e( 'Not public.', 'easy-affiliate-links' ); ?>
			</div>
		</div>
		<div class="eafl-form-line">
			<div class="eafl-form-label">
				<label for="eafl-link-description"><?php esc_html_e( 'Description', 'easy-affiliate-links' ); ?></label>
			</div>
			<div class="eafl-form-input">
				<textarea id="eafl-link-description" rows="2"></textarea>
			</div>
			<div class="eafl-form-description">
				<?php esc_html_e( 'Use this to store any link specific information.', 'easy-affiliate-links' ); ?> <?php esc_html_e( 'Not public.', 'easy-affiliate-links' ); ?>
			</div>
		</div>
		<div class="eafl-form-line">
			<div class="eafl-form-label">
				<label for="eafl-link-categories"><?php esc_html_e( 'Categories', 'easy-affiliate-links' ); ?></label>
			</div>
			<div class="eafl-form-input">
				<select id="eafl-link-categories" multiple>
					<?php
					$terms = get_terms( array(
							'taxonomy' => 'eafl_category',
							'hide_empty' => false,
					) );

					foreach ( $terms as $term ) {
						echo '<option value="' . esc_attr( $term->term_id ) . '">' . esc_html( $term->name ) . '</option>';
					}
					?>
				</select>
			</div>
			<div class="eafl-form-description">
				<?php esc_html_e( 'Use this to group links.', 'easy-affiliate-links' ); ?> <?php esc_html_e( 'Not public.', 'easy-affiliate-links' ); ?>
			</div>
		</div>
	</div>
	<div class="eafl-form-section">
		<div class="eafl-form-line">
			<div class="eafl-form-label">
				<label for="eafl-link-text"><?php esc_html_e( 'Default Link Text', 'easy-affiliate-links' ); ?></label>
			</div>
			<div class="eafl-form-input">
				<input type="text" class="eafl-link-text" id="eafl-link-text" />
				<a href="#" id="eafl-link-text-add-variant"><?php esc_html_e( 'Add variant', 'easy-affiliate-links' ); ?></a>
			</div>
			<div class="eafl-form-description">
				<?php esc_html_e( 'Text shown to visitors.', 'easy-affiliate-links' ); ?>
			</div>
		</div>
	</div>
	<div class="eafl-form-section">
		<div class="eafl-form-line">
			<div class="eafl-form-label">
				<label for="eafl-link-url"><?php esc_html_e( 'Link URL', 'easy-affiliate-links' ); ?></label>
			</div>
			<div class="eafl-form-input">
				<input type="text" id="eafl-link-url" />
			</div>
			<div class="eafl-form-description">
				<?php esc_html_e( 'Final destination for the link.', 'easy-affiliate-links' ); ?> <?php esc_html_e( 'Starts with http:// or https://', 'easy-affiliate-links' ); ?>
			</div>
		</div>
		<div class="eafl-form-line">
			<div class="eafl-form-label">
				<label for="eafl-link-slug"><?php esc_html_e( 'Shortlink Slug', 'easy-affiliate-links' ); ?></label>
			</div>
			<div class="eafl-form-input">
				<input type="text" id="eafl-link-slug" />
				<div id="eafl-link-shortlink-preview-container"><?php echo esc_url( site_url( '/' . EAFL_Settings::get( 'shortlink_slug' ) . '/' ) ); ?><span id="eafl-link-shortlink-preview"></span></div>
			</div>
			<div class="eafl-form-description">
				<?php esc_html_e( 'Part of the link your visitors will see.', 'easy-affiliate-links' ); ?>
			</div>
		</div>
	</div>
	<div class="eafl-form-section">
		<div class="eafl-form-line">
			<div class="eafl-form-label">
				<?php esc_html_e( 'Target', 'easy-affiliate-links' ); ?>
			</div>
			<div class="eafl-form-input">
				<?php
				$options = array(
					'default' => __( 'Use Default', 'easy-affiliate-links' ),
					'_self' => __( 'Open in same tab', 'easy-affiliate-links' ),
					'_blank' => __( 'Open in new tab', 'easy-affiliate-links' ),
				);
				$default = EAFL_Settings::get( 'default_target' );
				$options['default'] .= ' (' . $options[ $default ] . ')';

				foreach ( $options as $option => $option_label ) {
					echo '<input type="radio" name="eafl-link-target" id="eafl-link-target-' . esc_attr( $option ) . '" value="' . esc_attr( $option ) . '" /><label for="eafl-link-target-' . esc_attr( $option ) . '">' . esc_html( $option_label ) . '</label><br/>';
				}
				?>
			</div>
			<div class="eafl-form-description">
				<?php esc_html_e( 'Target to use for the shortlink.', 'easy-affiliate-links' ); ?>
			</div>
		</div>
		<div class="eafl-form-line">
			<div class="eafl-form-label">
				<?php esc_html_e( 'Redirect Type', 'easy-affiliate-links' ); ?>
			</div>
			<div class="eafl-form-input">
				<?php
				$options = array(
					'default' => __( 'Use Default', 'easy-affiliate-links' ),
					301 => __( '301 Permanent', 'easy-affiliate-links' ),
					302 => __( '302 Temporary', 'easy-affiliate-links' ),
					307 => __( '307 Temporary', 'easy-affiliate-links' ),
				);
				$default = EAFL_Settings::get( 'default_redirect_type' );
				$options['default'] .= ' (' . $options[ $default ] . ')';

				foreach ( $options as $option => $option_label ) {
					echo '<input type="radio" name="eafl-link-redirect-type" id="eafl-link-redirect-type-' . esc_attr( $option ) . '" value="' . esc_attr( $option ) . '" /><label for="eafl-link-redirect-type-' . esc_attr( $option ) . '">' . esc_html( $option_label ) . '</label><br/>';
				}
				?>
			</div>
			<div class="eafl-form-description">
				<?php esc_html_e( 'Redirect type to use for the shortlink.', 'easy-affiliate-links' ); ?>
			</div>
		</div>
		<div class="eafl-form-line">
			<div class="eafl-form-label">
				<?php esc_html_e( 'Nofollow', 'easy-affiliate-links' ); ?>
			</div>
			<div class="eafl-form-input">
				<?php
				$options = array(
					'default' => __( 'Use Default', 'easy-affiliate-links' ),
					'follow' => __( 'Do not add nofollow attribute', 'easy-affiliate-links' ),
					'nofollow' => __( 'Add nofollow attribute', 'easy-affiliate-links' ),
				);
				$default = EAFL_Settings::get( 'default_nofollow' );
				$options['default'] .= ' (' . $options[ $default ] . ')';

				foreach ( $options as $option => $option_label ) {
					echo '<input type="radio" name="eafl-link-nofollow" id="eafl-link-nofollow-' . esc_attr( $option ) . '" value="' . esc_attr( $option ) . '" /><label for="eafl-link-nofollow-' . esc_attr( $option ) . '">' . esc_html( $option_label ) . '</label><br/>';
				}
				?>
			</div>
			<div class="eafl-form-description">
				<?php esc_html_e( 'Should we add the nofollow attribute to links.', 'easy-affiliate-links' ); ?>
			</div>
		</div>
	</div>
</div>
