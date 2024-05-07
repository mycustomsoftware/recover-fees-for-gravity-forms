<?php

namespace RecoverFeesForGravityForms;

use GFForms;
if(!class_exists('GFForms')){
	return;
}
GFForms::include_addon_framework();
class Gf_Recovery_Fees extends \GFAddOn
{
	protected $_version = '1.0.1';
	protected $_min_gravityforms_version = '1.9';
	protected $_slug = 'recover-fees-for-gravity-forms';
	protected $_path = 'recover-fees-for-gravity-forms/recover-fees-for-gravity-forms.php';
	protected $_full_path = __FILE__;
	protected $_title = 'Gravity Forms Recover Fees Add-On';
	protected $_short_title = 'Recover Fees Add-On';
	private static $_instance = null;
	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	public function pre_init() {
		parent::pre_init();
		// add tasks or filters here that you want to perform during the class constructor - before WordPress has been completely initialized
	}

	public function init() {
		parent::init();
		// add tasks or filters here that you want to perform both in the backend and frontend and for ajax requests
	}

	public function init_admin() {
		parent::init_admin();
//		add_action( 'gform_field_appearance_settings', array( $this, 'field_appearance_settings' ), 10, 2 );
		// add tasks or filters here that you want to perform only in admin
	}

	public function init_frontend() {
		parent::init_frontend();
		// add tasks or filters here that you want to perform only in the front end
	}

	public function init_ajax() {
		parent::init_ajax();
		// add tasks or filters here that you want to perform only during ajax requests
	}
	public function scripts() {
		$default_settings = get_option('gravityformsaddon_gravityrecoverfees_settings');
		$default_settings['fdllabel'] = !empty($default_settings['fdllabel']) ? $default_settings['fdllabel'] : 'Help cover our transaction fees %RECOVERFEE% so 100% of your donation get\'s to us.';
		$default_settings['fdlfixed'] = !empty($default_settings['fdlfixed']) ? $default_settings['fdlfixed'] : '0.31';
		$mod_defined = 'production';
		if (defined('RECOVER_FEES_FOR_GRAVITY_FORMS_ENV') && RECOVER_FEES_FOR_GRAVITY_FORMS_ENV) {
			$mod_defined = RECOVER_FEES_FOR_GRAVITY_FORMS_ENV;
		}
		$mod = $mod_defined == 'production' ? '.min' : '';
		$default_settings['isDevMod'] = $mod_defined;
		$defined_path = dirname(__DIR__) . '/recover-fees-for-gravity-forms.php';
		if (defined('RECOVER_FEES_FOR_GRAVITY_FORMS_ENV') && RECOVER_FEES_FOR_GRAVITY_FORMS_ENV) {
			$defined_path = RECOVER_FEES_FOR_GRAVITY_FORMS_MAIN_FILE;
		}
		$scripts = array(
			array(
				'handle'  => 'recover_fees_for_gravity_forms_js',
				'src'     => plugin_dir_url( $defined_path ). "/js/recover-fees-for-gravity-forms-admin{$mod}.js",
				'version' => $this->_version,
				'deps'    => array( 'jquery' ),
				'strings'    => $default_settings,
				'enqueue' => array(
					array( $this, 'should_enqueue_admin_script' ),
				)
			),
			array(
				'handle'  => 'recover_fees_for_gravity_forms_js_frontend',
				'src'     => plugins_url( '',$defined_path ). "/js/recover-fees-for-gravity-forms{$mod}.js",
				'version' => $this->_version,
				'deps'    => array( 'jquery' ),
				'enqueue' => array(
					array( $this, 'should_enqueue_frontend_script' ),
				)
			),
		);
		return array_merge( parent::scripts(), $scripts );
	}
	public function should_enqueue_admin_script() {
		return (rgget( 'page' ) == 'gf_edit_forms');
	}
	public function should_enqueue_frontend_script() {
		return !GFForms::get_page();
	}
	public function plugin_settings_fields() {
			return array(
				array(
					'title'  => esc_html__( 'Recover fee Add-On Settings', 'recover-fees-for-gravity-forms' ),
					'fields' => array(
						array(
							'name'              => 'fdllabel',
							'tooltip'           => sprintf('<small>%s</small>', esc_html__( 'Use %RECOVERFEE% in Field Label to display fee total', 'recover-fees-for-gravity-forms' ) ),
							'label'             => esc_html__( 'Set default field label', 'recover-fees-for-gravity-forms' ),
							'type'              => 'text',
							'class'             => 'small',
							'value'             => 'Help cover our transaction fees %RECOVERFEE% so 100% of your donation get\'s to us.',
							'validation_callback' => function( $field, $value ) {
								return $value;
							}
						),
						array(
							'name'              => 'fdlfixed',
//							'tooltip'           => esc_html__( 'This is the tooltip', 'recover-fees-for-gravity-forms' ),
							'label'             => esc_html__( 'Default fee fixed amount', 'recover-fees-for-gravity-forms' ),
							'type'              => 'text',
							'value'             => '0.31',
							'class'             => 'small',
						),
						array(
							'name'              => 'fdlpercent',
//							'tooltip'           => esc_html__( 'This is the tooltip', 'recover-fees-for-gravity-forms' ),
							'label'             => esc_html__( 'Default fee percent of total amount', 'recover-fees-for-gravity-forms' ),
							'type'              => 'text',
							'class'             => 'small',
						)
					)
				)
			);
	}
//	public function styles() {
//		$styles = array(
//			array(
//				'handle'  => 'cover_fee_css',
//				'src'     => plugins_url( '',RECOVER_FEES_FOR_GRAVITY_FORMS_MAIN_FILE ). '/css/cover_fee_styles.css',
//				'version' => $this->_version,
//				'enqueue' => array(
//					array( $this, 'should_enqueue_admin_script' ),
//				)
//			)
//		);
//		return array_merge( parent::styles(), $styles );
//	}
}
