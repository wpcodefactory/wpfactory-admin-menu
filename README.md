# WPFactory Admin Menu

A library that adds a WPFactory menu on WordPress dashboard.

## Methods

### `add_wc_settings_tab_as_submenu_item( array $args = null )`

Adds WooCommerce Settings tab as a WPFactory submenu item.

**Parameters:**

* **`wc_settings_tab_id`** (string) - The WooCommerce tab id from the settings page. Ex: From a settings tab such as `wp-admin/admin.php?page=wc-settings&tab=alg_wc_cost_of_goods`, the id would be `alg_wc_cost_of_goods`.
* **`menu_title`** (string) - The submenu item label displayed on WPFactory menu.
* **`page_title`** (string) - The title displayed above the new settings page. Default value: `'WPFactory plugins settings'`.
* **`capability`** (string) - Capability string used on `add_submenu_page()`. Default value: 'manage_options'.
* **`position`** (int) - Position used on `add_submenu_page()`. Default value: `30`.

## Full example

```php
if ( ! is_admin() ) {
  return;
}
$wpf_admin_menu = WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu::get_instance();
$wpf_admin_menu->add_wc_settings_tab_as_submenu_item( array(
  'wc_settings_tab_id' => 'alg_wc_left_to_free_shipping',
  'menu_title'         => 'Left For Free Shipping',
) );
```
