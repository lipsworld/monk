<?php
/**
 * The test class for the translate post processes
 *
 * @package    Monk
 * @subpackage Monk/Post Translation Tests
 * @since      0.4.0
 */

/**
 * Tests the methods related to the post creation and translation
 *
 * @since      0.4.0
 *
 * @package    Monk
 * @subpackage Monk/Post Translation Tests
 */
class Test_Translate_Post extends WP_UnitTestCase {

	/**
	 * The WordPress test factory object.
	 *
	 * @since    0.4.0
	 *
	 * @access   private
	 * @var      class    $factory    A reference for the WP_UnitTest_Factory class.
	 */
	private $factory;

	/**
	 * Initializes the test and handles the class instances.
	 *
	 * @since    0.4.0
	 *
	 * @return void
	 */
	function setUp() {
		require_once( '../../includes/class-monk-post-translation.php' );
		require_once( 'wptests/lib/factory.php' );

		parent::setUp();
		$this->factory = new WP_UnitTest_Factory;

	} // end setUp

	/**
	 * Tests the creation process of a post with its language.
	 *
	 * @since    0.4.0
	 *
	 * @return void
	 */
	function test_add_language_to_post() {

		// Use the factory to create a new post and then test it.
		$post_object = new Monk_Post_Translation( $this->factory->post->create() );
		$this->assertNotEmpty( $post_object );

		// Simulates the language from a form.
		$_POST['monk_post_language'] = 'en_US';

		// Set a language for this post.
		$post_object->set_language( $_POST['monk_post_language'] );

		// get and test if the language was set correctly.
		$language = $post_object->get_language();
		$this->assertEquals( 'en_US', $language );

		// inserts the monk_id into the post.
		$post_object->set_translation_group_id( '' );

		// tests if the monk_id is the object post_id, in this case.
		$monk_id = $post_object->get_translation_group_id();
		$this->assertEquals( $post_object->get_the_post_id(), $monk_id );

		// saves the translations option.
		$post_object->save_translation_group( $language );

		// tests the translations option.
		$option = $post_object->get_translation_group( $monk_id );
		$this->assertArrayHasKey( $language, $option );
		$this->assertContains( $monk_id, $option );

	} // end test_add_language_to_post.

	/**
	 * Tests the single post translation process.
	 *
	 * @since    0.4.0
	 *
	 * @return void
	 */
	function test_post_translation() {
		// Creates the original post.
		$original_post_id = new Monk_Post_Translation( $this->factory->post->create() );
		$original_post_id->set_language( 'en_US' );
		$original_post_id->set_translation_group_id( '' );
		$original_post_id->save_translation_group( 'en_US' );

		// tests the translations option before adding a new post.
		$option = $original_post_id->get_translation_group( $original_post_id->get_the_post_id() );
		$this->assertArrayHasKey( 'en_US', $option );

		$new_post_id = new Monk_Post_Translation( $this->factory->post->create() );
		$this->assertNotEmpty( $new_post_id );

		// The language comes from the POST variable.
		$_POST['monk_post_language'] = 'pt_BR';

		$new_post_id->set_language( $_POST['monk_post_language'] );

		// Test the new post language.
		$language = $new_post_id->get_language();
		$this->assertEquals( 'pt_BR', $language );

		// Gets the translation group id from the original post.
		$original_monk_id = $original_post_id->get_translation_group_id();

		// Adds the meta_value to the new post.
		$new_post_id->set_translation_group_id( $original_monk_id );

		// Tests if the new meta is equals to the $original_post_id meta.
		$monk_id = $new_post_id->get_translation_group_id();
		$this->assertEquals( $monk_id, $original_monk_id );

		// Adds the new entry in the option.
		$new_post_id->save_translation_group( $language );
		$option = $new_post_id->get_translation_group( $monk_id );

		$this->assertArrayHasKey( $language, $option );
		$this->assertContains( $monk_id, $option );

	} // end test_post_translation.

} // end class.