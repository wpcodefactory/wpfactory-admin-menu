<?php
/**
 * WPFactory Admin Menu - WooCommerce Settings Menu Item Swapper.
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  WPFactory
 */

namespace WPFactory\WPFactory_Admin_Menu;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WPFactory_Admin_Menu\WC_Settings_Menu_Item_Swapper' ) ) {

	/**
	 * WPFactory Admin Menu.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	class WC_Settings_Menu_Item_Swapper {

		protected $args = array();

		protected $initialized = false;

		function swap( $args = null ) {
			$args                                              = wp_parse_args( $args, array(
				'wc_settings_tab_id'         => '',
				'replacement_menu_item_slug' => ''
			) );
			$this->args[ $args['replacement_menu_item_slug'] ] = $args;
		}

		function init() {
			if ( $this->initialized ) {
				return;
			}
			$this->initialized = true;

			// Replaces WC Settings Menu Item.
			add_filter( 'parent_file', array( $this, 'replace_wc_settings_menu_item' ) );

			// Hide Current plugin Settings tab.
			add_action( 'admin_head', array( $this, 'hide_plugin_settings_tab' ) );

			// Hides WC Settings tabs.
			add_action( 'admin_head', array( $this, 'hide_wc_settings_tabs' ) );
		}

		function replace_wc_settings_menu_item( $file ) {
			global $plugin_page;
			if (
				'wc-settings' === $plugin_page &&
				isset( $_GET['tab'] ) &&
				false !== ( $replacement_menu_item_slug = array_search( $_GET['tab'], array_column( $this->args, 'wc_settings_tab_id' ) ) )
			) {
				$plugin_page = $replacement_menu_item_slug;
			}

			return $file;
		}

		function hide_plugin_settings_tab() {
			global $plugin_page;
			if (
				'wc-settings' === $plugin_page &&
				(
					! isset( $_GET['tab'] ) ||
					( isset( $_GET['tab'] ) && ! in_array( $_GET['tab'], array_column( $this->args, 'wc_settings_tab_id' ) ) )
				)
			) {
				$tab_ids          = array_column( $this->args, 'wc_settings_tab_id' );
				$css_selector_arr = array();
				foreach ( $tab_ids as $tab ) {
					$css_selector_arr[] = '.wrap.woocommerce a[href*="tab=' . $tab . '"]';
				}

				?>
				<style>
					<?php echo implode(', ', $css_selector_arr)?>
					{
						display: none
					}
				</style>
				<?php
			}
		}

		function hide_wc_settings_tabs() {
			global $plugin_page;
			if (
				'wc-settings' === $plugin_page &&
				isset( $_GET['tab'] ) &&
				false !== ( $replacement_menu_item_slug = array_search( $_GET['tab'], array_column( $this->args, 'wc_settings_tab_id' ) ) )
			) {
				$wc_settings_tab_id = $this->args[ $replacement_menu_item_slug ]['wc_settings_tab_id'];
				?>
				<style>
					.wrap.woocommerce a:not([href*="tab=<?php echo $wc_settings_tab_id; ?>"]) {
						display: none
					}

					#wpbody {
						margin-top: 0
					}

					.woocommerce-layout {
						display: none
					}
				</style>
				<?php
			}
		}
	}
}