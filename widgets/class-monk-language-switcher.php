<?php

/**
 * Monk Language Switcher.
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      1.0.0
 *
 * @package    Monk
 * @subpackage Monk/Widgets
 */

/**
 * Monk Language Switcher.
 *
 * Defines the class name, description, 
 * outputs the content of the widget, options form on admin
 * and processes widget options to be saved.
 *
 * @package    Monk
 * @subpackage Monk/Widgets
 * @author     Leonardo Onofre <leonardodias.14.ld@gmail.com>
 */
class Monk_Language_Switcher extends WP_Widget {

	/**
	 * Sets up the widgets classname and description.
	 */
	public function __construct() {
		$widget_ops = array( 
			'classname'		=> __( 'Monk_Language_Switcher', 'text_domain' ),
			'description'	=> __( 'The Monk Language Switcher is the best language selector widget', 'text_domain' ),
		);
		parent::__construct( 'monk_language_switcher', 'Monk Language Switcher', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {

		// outputs the options form on admin
		$languages_nat = array(
			'portuguese' => 'Português',
			'english'    => 'English',
			'spanish'    => 'Español',
			'french'     => 'Français',
		);
		$languages_eng = array(
			'portuguese' => 'Portuguese',
			'english'    => 'English',
			'spanish'    => 'Spanish',
			'french'     => 'French',
		);
		$flags = array(
			'portuguese' => 'Portuguese',
			'english'    => 'English',
			'spanish'    => 'Spanish',
			'french'     => 'French',
		);
		require plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/partials/admin-monk-language-switcher.php';
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
	}

}
