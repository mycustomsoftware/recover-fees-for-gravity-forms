<?php

namespace GravityRecoverFees;

use GFForms;
if(!class_exists('GFForms')){
	return;
}
GFForms::include_addon_framework();
class GfRecoveryFees extends \GFAddOn
{
	protected $_version = '1.0.1';
	protected $_min_gravityforms_version = '1.9';
	protected $_slug = 'gravityrecoverfees';
	protected $_path = 'gravityrecoverfees/includes/GfRecoveryFees.php';
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
		$mod = GRAVITYRECOVERFEES_ENV == 'production' ? '.min' : '';
		$default_settings['isDevMod'] = GRAVITYRECOVERFEES_ENV;
		$scripts = array(
			array(
				'handle'  => 'gravityrecoverfees_js',
				'src'     => plugins_url( '',GFCF_FILE ). "/js/gravityrecoverfees-admin{$mod}.js",
				'version' => $this->_version,
				'deps'    => array( 'jquery' ),
				'strings'    => $default_settings,
				'enqueue' => array(
					array( $this, 'should_enqueue_admin_script' ),
				)
			),
			array(
				'handle'  => 'gravityrecoverfees_js_frontend',
				'src'     => plugins_url( '',GFCF_FILE ). "/js/gravityrecoverfees{$mod}.js",
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
					'title'  => esc_html__( 'Recover fee Add-On Settings', 'simpleaddon' ),
					'fields' => array(
						array(
							'name'              => 'fdllabel',
							'tooltip'           => 'Use <b>%RECOVERFEE%</b> in Field Label to display fee total</small>',
							'label'             => esc_html__( 'Set default field label', 'simpleaddon' ),
							'type'              => 'text',
							'class'             => 'small',
							'value'             => 'Help cover our transaction fees %RECOVERFEE% so 100% of your donation get\'s to us.',
							'validation_callback' => function( $field, $value ) {
								return $value;
							}
						),
						array(
							'name'              => 'fdlfixed',
//							'tooltip'           => esc_html__( 'This is the tooltip', 'simpleaddon' ),
							'label'             => esc_html__( 'Default fee fixed amount', 'simpleaddon' ),
							'type'              => 'text',
							'value'             => '0.31',
							'class'             => 'small',
						),
						array(
							'name'              => 'fdlpercent',
//							'tooltip'           => esc_html__( 'This is the tooltip', 'simpleaddon' ),
							'label'             => esc_html__( 'Default fee percent of total amount', 'simpleaddon' ),
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
//				'src'     => plugins_url( '',GFCF_FILE ). '/css/cover_fee_styles.css',
//				'version' => $this->_version,
//				'enqueue' => array(
//					array( $this, 'should_enqueue_admin_script' ),
//				)
//			)
//		);
//		return array_merge( parent::styles(), $styles );
//	}
}
