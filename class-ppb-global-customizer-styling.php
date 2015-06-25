<?php

class Pootle_PB_Global_Customizer_Styling{

	/**
	 * Pootle_PB_Global_Customizer_Styling Instance of main plugin class.
	 *
	 * @var 	object Pootle_PB_Global_Customizer_Styling
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public static $token;
	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public static $version;

	/**
	 * Pootle PB Global Customizer Styling Addon plugin directory URL.
	 *
	 * @var 	string Plugin directory
	 * @access  private
	 * @since 	1.0.0
	 */
	public static $url;

	/**
	 * Pootle PB Global Customizer Styling Addon plugin directory Path.
	 *
	 * @var 	string Plugin directory
	 * @access  private
	 * @since 	1.0.0
	 */
	public static $path;

	/**
	private $options;

	/**
	 * Main Pootle PB Global Customizer Styling Addon Instance
	 *
	 * Ensures only one instance of Storefront_Extension_Boilerplate is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @return Pootle_PB_Global_Customizer_Styling instance
	 */
	public static function instance() {
		if ( null == self::$_instance ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Constructor function.
	 *
	 * @access  private
	 * @since   1.0.0
	 */
	private function __construct() {

		self::$token =     'ppb-global-customizer-styling';
		self::$url =       plugin_dir_url( __FILE__ );
		self::$path =      plugin_dir_path( __FILE__ );
		self::$version =   '1.0.0';

		add_action( 'init', array( $this, 'init' ) );
	} // End __construct()

	public function init() {

		if ( defined( 'POOTLEPAGE_VERSION' ) && 0 < version_compare( POOTLEPAGE_VERSION, '2.5.0' ) ) {

			$this->init_options();

			$this->output = new Pootle_PB_Global_Customizer_Styling_Output( $this->options );

			//Multi field settings
			$this->multi_fields = array(
				'border'  => 'PootlePage_Border_Control',
				'padding' => 'PootlePage_Padding_Control',
				'font'    => 'PootlePage_Font_Control',
			);

			//Single field settings
			$this->single_fields = array(
				'color' => 'WP_Customize_Color_Control',
			);

			$this->add_actions();
		}
	} // End init()

	private function add_actions() {

		//Add customize fields, settings and section
		add_action( 'customize_register', array( $this, 'register' ) );

		//Enqueue script and styles
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue' ) );

		//Output CSS in head
		add_action( 'wp_head', array( $this->output, 'output_css' ), 50 );

		//Call google font if required
		add_action( 'wp_head', array( $this->output, 'google_webfonts' ) );

	} // End add_actions()

	public function init_options() {

		$choices = array();
		for ( $i = 0; $i <= 20; ++ $i ) {
			$choices[ $i ] = $i . 'px';
		}

		$this->options = array(
			'siteorigin_panels_display[margin-bottom]' => array(
				'id'       => 'siteorigin_panels_display[margin-bottom]',
				'type'     => 'number',
				'label'    => __( 'Row bottom margin', 'scratch' ),
				'section'  => 'pootlepage_section',
				'default'  => '0',
				'priority' => 10
			),
			'pp_widget_bg_color'                       => array(
				'id'       => 'pp_widget_bg_color',
				'type'     => 'color',
				'label'    => __( 'Content Block Background Color', 'scratch' ),
				'section'  => 'pootlepage_section',
				'default'  => '',
				'priority' => 10
			),
			'pp_widget_border_width'                   => array(
				'id'       => 'pp_widget_border_width',
				'type'     => 'number',
				'label'    => __( 'Content block border width', 'scratch' ),
				'section'  => 'pootlepage_section',
				'default'  => '0',
				'priority' => 10
			),
			'pp_widget_border_color'                   => array(
				'id'       => 'pp_widget_border_color',
				'type'     => 'color',
				'label'    => __( 'Content block border color', 'scratch' ),
				'section'  => 'pootlepage_section',
				'default'  => '',
				'priority' => 10
			),
			'pp_widget_border_radius'                  => array(
				'id'       => 'pp_widget_border_radius',
				'type'     => 'select',
				'label'    => __( 'Content Block Rounded Corners', 'scratch' ),
				'section'  => 'pootlepage_section',
				'default'  => '0',
				'choices'  => $choices,
				'priority' => 16
			),
		);
	}

	public function register( WP_Customize_Manager $customizeManager ) {

		// sections
		$customizeManager->add_section( 'pootlepage_section', array(
			'title'    => 'Page Builder',
			'priority' => 10
		) );

		foreach ( $this->options as $k => $option ) {
			if ( array_key_exists( $option['type'], $this->multi_fields ) ) {

				$this->multi_field_register( $option, $customizeManager );

			} else {

				$this->single_field_register( $option, $customizeManager );

			}
		}

	}

	/**
	 * Adds single field option
	 *
	 * @param array $option Current option
	 * @param WP_Customize_Manager $customizeManager
	 */
	private function single_field_register( $option, $customizeManager ) {

		$customizeManager->add_setting( $option['id'], array(
			'default' => $option['default'],
			'type'    => 'option',
		) );

		$option['settings'] = $option['id'];

		if ( $option['type'] == 'color' ) {

			$customizeManager->add_control(
				new WP_Customize_Color_Control(
					$customizeManager,
					$option['id'],
					$option
				)
			);

		} else {

			$customizeManager->add_control(
				new WP_Customize_Control(
					$customizeManager,
					$option['id'],
					$option
				)
			);
		}
	}

	/**
	 * Adds multi field option
	 *
	 * @param array $option Current option
	 * @param WP_Customize_Manager $customizeManager
	 */
	private function multi_field_register( $option, $customizeManager ) {

		foreach ( $option['settings'] as $key => $settingID ) {

			//Init default
			$defaultValue = '';

			//Get default if set
			if ( ! empty ( $option['defaults'][ $key ] ) ) {
				$defaultValue = $option['defaults'][ $key ];
			}

			//Add setting
			$customizeManager->add_setting(
				$settingID,
				array(
					'default' => $defaultValue,
					'type'    => 'option',
				)
			);
		}

		$className = $this->multi_fields[ $option['type'] ];

		$customizeManager->add_control(
			new $className(
				$customizeManager,
				$option['id'],
				$option
			)
		);
	}

	public function enqueue() {

		wp_enqueue_style( self::$token . '-customizer', plugin_dir_url( __FILE__ ) . 'assets/customizer-controls.css' );

	}

}