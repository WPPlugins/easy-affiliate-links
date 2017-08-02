<?php
/**
 * Handle the manage import page.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/manage
 */

/**
 * Handle the manage import page.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/manage
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Manage_Import {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_action( 'eafl_manage_page', array( __CLASS__, 'manage_page' ) );
		add_filter( 'eafl_manage_tabs', array( __CLASS__, 'manage_tabs' ), 19 );
	}

	/**
	 * Add import to the manage tabs.
	 *
	 * @since    2.0.0
	 * @param 	 array $tabs Current tabs.
	 */
	public static function manage_tabs( $tabs ) {
		if ( current_user_can( 'manage_options' ) ) {
			$tabs['import'] = __( 'Import Links', 'easy-affiliate-links' );
		}

		return $tabs;
	}

	/**
	 * Output import page.
	 *
	 * @since    2.0.0
	 * @param	 mixed $sub Sub manage page to display.
	 */
	public static function manage_page( $sub ) {
		if ( 'import' === $sub && current_user_can( 'manage_options' ) ) {

			if ( isset( $_POST['eafl_import'] ) && wp_verify_nonce( $_POST['eafl_import'], 'eafl_import' ) ) { // Input var okay.
				$links = simplexml_load_file( $_FILES['xml']['tmp_name'] );

				echo '<p>Links Imported:</p>';

				$i = 1;
				foreach ( $links as $link ) {
					self::import_xml_link( $link, $i );
					$i++;
				}

				if ( $i == 1 ) {
					echo '<p>No links found</p>';
				}
			} else {
				require_once( EAFL_DIR . 'templates/admin/menu/manage/import.php' );
			}
		}
	}

	/**
	 * Import a single link from XML.
	 *
	 * @since    2.0.0
	 * @param	 mixed $xml_link    Link to import from XML.
	 * @param	 int   $link_number Number of the link we're importing.
	 */
	public static function import_xml_link( $xml_link, $link_number ) {
		$name = isset( $xml_link->attributes()->name ) ? trim( (string) $xml_link->attributes()->name ) : '';
		$description = isset( $xml_link->attributes()->description ) ? trim( (string) $xml_link->attributes()->description ) : '';
		$text = isset( $xml_link->attributes()->text ) ? trim( (string) $xml_link->attributes()->text ) : '';
		$url = isset( $xml_link->attributes()->url ) ? trim( (string) $xml_link->attributes()->url ) : '';
		$slug = isset( $xml_link->attributes()->slug ) ? trim( (string) $xml_link->attributes()->slug ) : '';

		$link = array();

		$link['name'] = $name;
		$link['description'] = $description;
		$link['text'] = array( $text );
		$link['url'] = $url;
		$link['slug'] = $slug;

		$link_id = EAFL_Link_Saver::create_link( $link );

		echo esc_html( $link_number ) . '. ' . esc_html( $name ) . '<br/>';
	}
}

EAFL_Manage_Import::init();
