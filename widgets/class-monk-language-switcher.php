<?php
/**
 * Monk Language Switcher.
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Widgets
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class responsible for create Monk_Language_Switcher widget.
 *
 * @package    Monk
 * @subpackage Monk/Widgets
 * @author     Leonardo Onofre <leoonofre.oliversoft@gmail.com>
 */
class Monk_Language_Switcher extends WP_Widget {

	/**
	 * Sets up the widgets classname and description.
	 */
	public function __construct() {
		$widget_options = array(
			'classname'   => 'monk_language_switcher',
			'description' => __( 'Switch between site translations.', 'monk' ),
			);
		parent::__construct( 'monk_language_switcher', 'Monk Language Switcher', $widget_options );
	}

	/**
	 * Outputs the content of the front-end side
	 *
	 * @param array $args     Args.
	 * @param array $instance The widget options.
	 */
	public function widget( $args, $instance ) {
		global $monk_languages;
		$switchable_languages = array();
		$title                = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Languages', 'monk' );
		$flag                 = ! empty( $instance['flag'] ) ? true : false;
		$active_languages     = get_option( 'monk_active_languages' );
		$current_language     = isset( $_GET['lang'] ) ? sanitize_text_field( wp_unslash( $_GET['lang'] ) ) : get_option( 'monk_default_language' );

		if ( is_front_page() || is_post_type_archive() ) {
			$current_url = monk_get_current_url();

			foreach ( $active_languages as $lang_code ) {
				if ( $lang_code !== $current_language ) {
					$switchable_languages[ $lang_code ] = add_query_arg( 'lang', esc_attr( $lang_code, 'monk' ), $current_url );
				}
			}
		}

		if ( is_singular() ) {
			$current_language          = get_post_meta( get_the_id(), '_monk_post_language', true );
			$monk_post_translations_id = get_post_meta( get_the_id(), '_monk_post_translations_id', true );
			$monk_translations         = get_option( 'monk_post_translations_' . $monk_post_translations_id, false );

			foreach ( $monk_translations as $lang_code => $post_id ) {
				if ( $lang_code !== $current_language ) {
					$switchable_languages[ $lang_code ] = get_permalink( $post_id );
				}
			}
		}

		if ( is_archive() && ( is_category() || is_tag() ) ) {
			$current_language          = get_term_meta( get_queried_object_id(), '_monk_term_language', true );
			$monk_term_translations_id = get_term_meta( get_queried_object_id(), '_monk_term_translations_id', true );
			$monk_translations         = get_option( 'monk_term_translations_' . $monk_term_translations_id, false );

			foreach ( $monk_translations as $lang_code => $term_id ) {
				if ( $lang_code !== $current_language ) {
					$switchable_languages[ $lang_code ] = get_term_link( $term_id );
				}
			}
		}

		require plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/partials/public-monk-language-switcher.php';
	}

	/**
	 * Outputs the options form on admin side
	 *
	 * @param array $instance The widget options.
	 */
	public function form( $instance ) {
		require plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/partials/admin-monk-language-switcher.php';
	}

	/**
	 * Process widget options on save
	 *
	 * @param array $new_instance The new options.
	 * @param array $old_instance The previous options.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                = $old_instance;
		$instance['title']       = ! empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['flag']        = ! empty( $new_instance['flag'] ) ? true : false;

		return $instance;
	}
}
