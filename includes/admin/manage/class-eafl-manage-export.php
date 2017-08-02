<?php
/**
 * Handle the manage export page.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/manage
 */

/**
 * Handle the manage export page.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/manage
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Manage_Export {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_action( 'eafl_manage_page', array( __CLASS__, 'manage_page' ) );
		add_filter( 'eafl_manage_tabs', array( __CLASS__, 'manage_tabs' ), 20 );
	}

	/**
	 * Add export to the manage tabs.
	 *
	 * @since    2.0.0
	 * @param 	 array $tabs Current tabs.
	 */
	public static function manage_tabs( $tabs ) {
		if ( current_user_can( 'manage_options' ) ) {
			$tabs['export'] = __( 'Export Links', 'easy-affiliate-links' );
		}

		return $tabs;
	}

	/**
	 * Output export page.
	 *
	 * @since    2.0.0
	 * @param	 mixed $sub Sub manage page to display.
	 */
	public static function manage_page( $sub ) {
		if ( 'export' === $sub && current_user_can( 'manage_options' ) ) {
			$links = EAFL_Link_Manager::get_links();

			if ( 0 === count( $links ) ) {
				esc_html_e( 'There are no links to export.', 'easy-affiliate-links' );
			} else {
				$xml_data = array(
					'name' => 'links',
				);

				foreach ( $links as $link ) {
					$xml_data[] = self::export_xml_link( $link );
				}

				$doc = new DOMDocument();
				$child = self::generate_xml_element( $doc, $xml_data );
				if ( $child ) {
					$doc->appendChild( $child );
				}
				$doc->formatOutput = true; // Add whitespace to make easier to read XML.
				$xml = $doc->saveXML();

				echo '<form id="exportLinks" action="' . EAFL_URL . 'templates/admin/menu/manage/export.php" method="post">';
				echo '<input type="hidden" name="exportLinks" value="' . base64_encode( $xml ) . '"/>';
				submit_button( __( 'Download XML', 'easy-affiliate-links' ) );
				echo '</form>';
			}
		}
	}

	/**
	 * Export a single link to XML.
	 *
	 * @since    2.0.0
	 * @param	 mixed $link Link to export to XML.
	 */
	public static function export_xml_link( $link ) {
		$xml = array(
			'name' => 'link',
			'attributes' => array(
				'name' => isset( $link['name'] ) ? $link['name'] : '',
				'description' => isset( $link['description'] )   ? $link['description'] : '',
				'text' => isset( $link['text'] ) ? $link['text'][0] : '',
				'url' => isset( $link['url'] ) ? $link['url'] : '',
				'slug' => isset( $link['slug'] ) ? $link['slug'] : '',
			),
		);

		return $xml;
	}

	/**
	 * Generate an XML element.
	 *
	 * @since    2.0.0
	 * @param	 mixed $dom  Dom element.
	 * @param	 mixed $data XML data.
	 */
	private static function generate_xml_element( $dom, $data ) {
		if ( empty( $data['name'] ) ) {
			return false;
		}

		// Create the element.
		$element_value = ( ! empty( $data['value'] ) ) ? $data['value'] : null;
		$element = $dom->createElement( $data['name'] );
		$element->appendChild( $dom->createTextNode( $element_value ) );

		// Add any attributes.
		if ( ! empty( $data['attributes'] ) && is_array( $data['attributes'] ) ) {
			foreach ( $data['attributes'] as $attribute_key => $attribute_value ) {
				$element->setAttribute( $attribute_key, $attribute_value );
			}
		}

		// Any other items in the data array should be child elements.
		foreach ( $data as $data_key => $child_data ) {
			if ( ! is_numeric( $data_key ) ) {
				continue;
			}

			$child = self::generate_xml_element( $dom, $child_data );
			if ( $child ) {
				$element->appendChild( $child );
			}
		}

		return $element;
	}
}

EAFL_Manage_Export::init();
