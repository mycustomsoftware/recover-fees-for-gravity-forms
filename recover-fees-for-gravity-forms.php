<?php
/**
 * Plugin Name: Recover Fees for Gravity Forms
 * Plugin URI: https://wordpress.org/plugins/recover-fees-for-gravity-forms
 * Description: Let users cover credit card fees in Gravity Forms. Adds a new field type with a checkbox to cover fees, simple setup, customizable fees, and seamless integration boost your payment or fundraising efforts.
 * Version: 2.1.4
 * Author:      My Custom Software
 * Requires at least: 6.7.1
 * Author URI: https://github.com/mycustomsoftware
 *  License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Requires PHP: 7.4
**/
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
if(!defined("RECOVER_FEES_FOR_GRAVITY_FORMS_VER")){
    define("RECOVER_FEES_FOR_GRAVITY_FORMS_VER",'2.1.4');
}
require_once __DIR__.'/vendor/autoload.php';
use RecoverFeesForGravityForms\GF_Admin_Label;
use RecoverFeesForGravityForms\GF_Recover_Fees_In_Order;
use RecoverFeesForGravityForms\GF_Cerover_Fees_Settings;
use RecoverFeesForGravityForms\GF_Field_Recover_Fees;
use RecoverFeesForGravityForms\GF_Search_Filter;
use RecoverFeesForGravityForms\Gf_Recovery_Fees;
class RecoverFeesForGravityFormsMain{
    public $slug = 'recover-fees-for-gravity-forms';
	private static $boot_classes = array(
		GF_Admin_Label::class,
		GF_Search_Filter::class,
		GF_Recover_Fees_In_Order::class,
		GF_Cerover_Fees_Settings::class,
	);
	function __construct(){
		add_filter('init', array($this,'define_env'), 10, 2);
		add_action( 'install_plugins_pre_plugin-information', array( $this, 'add_plugin_info_popup_content' ), 9 );
		foreach (self::$boot_classes as $boot_class) {
			new $boot_class();
		}
        add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 5 );
		add_action( 'gform_loaded', array( $this, 'load' ), 5 );
		add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this,'add_settings_link'));
		add_filter('plugin_row_meta', array($this,'add_view_details_link'), 10, 2);
	}
	function define_env() {
		if(!defined('RECOVER_FEES_FOR_GRAVITY_FORMS_ENV')){
			define('RECOVER_FEES_FOR_GRAVITY_FORMS_ENV','production');
		}
		if(!defined('RECOVER_FEES_FOR_GRAVITY_FORMS_MAIN_FILE')){
			define('RECOVER_FEES_FOR_GRAVITY_FORMS_MAIN_FILE', __FILE__);
		}
	}
	function plugins_loaded() {
		if(!class_exists('GFForms')){
		    add_action( 'admin_notices', array( $this, 'admin_notices' ), 5 );
		}
	}
	function add_plugin_info_popup_content() {
		if ( sanitize_key($_REQUEST['plugin']) != $this->slug ) {
            return;
        }
		require_once __DIR__ . '/README.html';
		exit;
	}
	function add_view_details_link($links, $file) {
		if ($file == plugin_basename(__FILE__)) {
			$plugin_link = wp_kses_post(
                    sprintf(
                    '<a href="%s" class="thickbox open-plugin-details-modal" title="%s">%s</a>',
                        self_admin_url('plugin-install.php?tab=plugin-information&plugin=recover-fees-for-gravity-forms&TB_iframe=true&width=772&height=450'),
                        __("View details", 'recover-fees-for-gravity-forms'),
                        __("More Information", 'recover-fees-for-gravity-forms')
                    )
            );
			array_push($links, $plugin_link);
		}
		return $links;
	}
	function add_settings_link($links) {
		array_unshift($links, '<a href="admin.php?page=gf_settings&subview=recover-fees-for-gravity-forms">'.__("Settings", 'recover-fees-for-gravity-forms').'</a>');
		return $links;
	}
	public static function admin_notices() {
		?>
		<div class="notice notice-error">
			<p><?php esc_html_e( 'Gravity Forms Fee plugin requires "Gravity Forms" to be installed and activated!', 'recover-fees-for-gravity-forms' ); ?></p>
		</div>
	<?php
	}
	public static function load() {
		if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
			return;
		}
		GFAddOn::register( Gf_Recovery_Fees::class );
		if ( ! method_exists( 'GF_Fields', 'register' ) ) {
			return;
		}
		GF_Fields::register(new GF_Field_Recover_Fees());
	}
}
new RecoverFeesForGravityFormsMain();
