<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      1.0.0
 *
 * @package    Monk
 * @subpackage Monk/Includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Monk
 * @subpackage Monk/Includes
 * @author     Breno Alves <email@example.com>
 */
class Monk {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Monk_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $monk    The string used to uniquely identify this plugin.
	 */
	protected $monk;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'Monk';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_global_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_widget_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Monk_Loader. Orchestrates the hooks of the plugin.
	 * - Monk_i18n. Defines internationalization functionality.
	 * - Monk_Admin. Defines all hooks for the admin area.
	 * - Monk_Public. Defines all hooks for the public side of the site.
	 * - Monk_Language_Switcher. Defines all functions related to Monk_Language_Switcher widget.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-monk-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-monk-i18n.php';

		/**
		 * Imports a global array with the translatable and native language names.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/monk-available-languages.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-monk-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-monk-public.php';

		/**
		 * Class responsible for create Monk_Language_Switcher widget.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/class-monk-language-switcher.php';

		/**
		 * Imports the metadata functionality for taxonomies
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/monk-term-metadata.php';

		$this->loader = new Monk_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Monk_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$monk_i18n = new Monk_i18n();

		$this->loader->add_action( 'plugins_loaded', $monk_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_global_hooks() {

		$this->loader->add_filter( 'query_vars', $this, 'monk_query_vars', 10, 1 );		
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Monk_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'monk_add_menu_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'monk_options_init' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'monk_post_meta_box' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'monk_save_post_meta_box', 10, 2 );
		$this->loader->add_action( 'customize_register', $plugin_admin, 'monk_language_customizer' );
		$this->loader->add_action( 'wp_head', $plugin_admin, 'monk_customize_css' );
		$this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'monk_admin_languages_selector' );
		
		$this->loader->add_filter( 'pre_get_posts', $plugin_admin, 'monk_admin_languages_filter' );
		$this->loader->add_filter( 'the_content', $plugin_admin, 'monk_add_term_metadata' );

		$args = array(
		   'public'   => true,
		   '_builtin' => true
		);

		$post_types = get_post_types( $args );
		$post_types['posts'] = 'posts';
		$post_types['pages'] = 'pages';
		foreach ( $post_types  as $post_type ) {
			$this->loader->add_action( 'manage_' . $post_type . '_custom_column', $plugin_admin, 'monk_language_column_content', 10, 2 );
			$this->loader->add_filter( 'manage_' . $post_type . '_columns', $plugin_admin, 'monk_language_column_head' );
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Monk_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}

	/**
	 * Register all of the hooks related to Monk Widgets
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_widget_hooks() {

		$this->loader->add_action( 'widgets_init', $this, 'register_widgets' );
	}

	/**
	 * Registrate the query vars to generate the custom urls
	 *
	 * @param    $vars
	 *
	 * @since    1.0.0
	*/
	public function monk_query_vars( $vars ) {
		$vars[] = 'lang';
		$vars[] = 'monk_id';
		return $vars;
	}

	/**
	 * Register all the widgets
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function register_widgets() {

		/**
		 * Register the Monk_Language_Switcher widget.
		 */
		register_widget( 'Monk_Language_Switcher' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Monk_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
