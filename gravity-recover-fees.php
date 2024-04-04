<?php
/**
 * Plugin Name: Gravity Recover Fees
 * Plugin URI: https://wordpress.org/plugins/gravity-recover-fees
 * Description: Let users cover credit card fees in Gravity Forms. Adds a new field type with a checkbox to cover fees, simple setup, customizable fees, and seamless integration boost your payment or fundraising efforts.
 * Version: 1.0.1
 * Author:      My Custom Software
 * Author URI: https://github.com/mycustomsoftware
 *  License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Requires PHP: 8.0
**/
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

define('GFCF_FILE', __FILE__);

require_once __DIR__.'/vendor/autoload.php';

use GravityRecoverFees\GF_AdminLabel;
use GravityRecoverFees\GF_Recover_Fees_In_Order;
use GravityRecoverFees\GF_Cerover_Fees_Settings;
use GravityRecoverFees\GF_Field_RecoverFees;
use GravityRecoverFees\GF_SearchFilter;
use GravityRecoverFees\GfRecoveryFees;

if(!defined('GRAVITYRECOVERFEES_ENV')){
	define('GRAVITYRECOVERFEES_ENV','production');
}
class GravityRecoverFeesMain{
    public $slug = 'gravityrecoverfees';
	private static $boot_classes = array(
		GF_AdminLabel::class,
		GF_SearchFilter::class,
		GF_Recover_Fees_In_Order::class,
		GF_Cerover_Fees_Settings::class,
	);
	function __construct(){
		add_action( 'install_plugins_pre_plugin-information', array( $this, 'add_plugin_info_popup_content' ), 9 );
		foreach (self::$boot_classes as $boot_class) {
			new $boot_class();
		}
        add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 5 );
		add_action( 'gform_loaded', array( $this, 'load' ), 5 );
		add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this,'add_settings_link'));
		add_filter('plugin_row_meta', array($this,'add_view_details_link'), 10, 2);
	}
	function plugins_loaded() {
		if(!class_exists('GFForms')){
		    add_action( 'admin_notices', array( $this, 'admin_notices' ), 5 );
		}
	}
	function add_plugin_info_popup_content() {
		if ( $_REQUEST['plugin'] != $this->slug ) {
            return;
        }
		require_once __DIR__ . '/README.html';
		exit;
	}
	function add_view_details_link($links, $file) {
		if ($file == plugin_basename(__FILE__)) {
			$plugin_link = '<a href="'.self_admin_url('plugin-install.php?tab=plugin-information&plugin=gravityrecoverfees&TB_iframe=true&width=772&height=450').'" class="thickbox open-plugin-details-modal" title="'.__("More Information about Gravity Forms Fee").'">'.__("View details").'</a>';
			array_push($links, $plugin_link);
		}
		return $links;
	}
	function add_settings_link($links) {
		array_unshift($links, '<a href="admin.php?page=gf_settings&subview=gravityrecoverfees">'.__("Settings").'</a>');
		return $links;
	}
	public static function admin_notices() {
		?>
		<div class="notice notice-error">
			<p><?php printf("__( '<strong>%s</strong> plugin requires \"Gravity Forms\" to be installed and activated!' )","Gravity Forms Fee"); ?></p>
		</div>
	<?php
	}
	public static function load() {
		if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
			return;
		}
		GFAddOn::register( GfRecoveryFees::class );
		if ( ! method_exists( 'GF_Fields', 'register' ) ) {
			return;
		}
		GF_Fields::register(new GF_Field_RecoverFees());
	}
}
new GravityRecoverFeesMain();
