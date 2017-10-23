<?php
/**
 * Provide the view for the monk_set_elements_language_render function
 *
 * @since      0.4.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
$monk_translate        = get_option( 'monk_translate', false );
$monk_translate_fields = get_option( 'monk_translate_fields', false );
$hidden                = $monk_translate ? '' : 'hidden';

if ( $monk_translate_fields ) {
	$monk_translate_fields = json_decode( $monk_translate_fields );
}
var_dump( $monk_translate_fields );
?>
<input type="checkbox" name="monk_set_language_to_elements" id="monk-set-language-to-elements">

<tr>
	<th scope="row"><?php esc_html_e( 'Use Google Translate API', 'monk' ); ?></th>
	<td>
		<input type="checkbox" name="monk_translate" id="monk-translate" <?php checked( $monk_translate, true ); ?>>
	</td>
</tr>
<tr class="monk_translate_option">
	<th scope="row"><?php esc_html_e( 'Type', 'monk' ); ?></th>
	<td>
		<input type="text" name="monk_translate_type" id="monk-translate-type" value="<?php if ( $monk_translate_fields ) { echo esc_attr( $monk_translate_fields->type ); } ?>">
	</td>
</tr>
<tr class="monk_translate_option">
	<th scope="row"><?php esc_html_e( 'Project id', 'monk' ); ?></th>
	<td>
		<input type="text" name="monk_translate_project_id" id="monk-translate-project-id" value="<?php if ( $monk_translate_fields ) { echo esc_attr( $monk_translate_fields->project_id ); } ?>">
	</td>
</tr>
<tr class="monk_translate_option">
	<th scope="row"><?php esc_html_e( 'Private key id', 'monk' ); ?></th>
	<td>
		<input type="text" name="monk_translate_private_key_id" id="monk-translate-private-key-id" value="<?php if ( $monk_translate_fields ) { echo esc_attr( $monk_translate_fields->private_key_id ); } ?>">
	</td>
</tr>
<tr class="monk_translate_option">
	<th scope="row"><?php esc_html_e( 'Private key', 'monk' ); ?></th>
	<td>
		<textarea name="monk_translate_private_key" id="monk-translate-private-key" cols="100" rows="10"><?php if ( $monk_translate_fields ) { echo esc_attr( $monk_translate_fields->private_key ); } ?></textarea>
	</td>
</tr>
<tr class="monk_translate_option">
	<th scope="row"><?php esc_html_e( 'Client email', 'monk' ); ?></th>
	<td>
		<input type="text" name="monk_translate_client_email" id="monk-translate-client-email" value="<?php if ( $monk_translate_fields ) { echo esc_attr( $monk_translate_fields->client_email ); } ?>">
	</td>
</tr>
<tr class="monk_translate_option">
	<th scope="row"><?php esc_html_e( 'Client id', 'monk' ); ?></th>
	<td>
		<input type="text" name="monk_translate_client_id" id="monk-translate-client-id" value="<?php if ( $monk_translate_fields ) { echo esc_attr( $monk_translate_fields->client_id ); } ?>">
	</td>
</tr>
<tr class="monk_translate_option">
	<th scope="row"><?php esc_html_e( 'Auth URI', 'monk' ); ?></th>
	<td>
		<input type="text" name="monk_translate_auth_uri" id="monk-translate-auth-uri" value="<?php if ( $monk_translate_fields ) { echo esc_attr( $monk_translate_fields->auth_uri ); } ?>">
	</td>
</tr>
<tr class="monk_translate_option">
	<th scope="row"><?php esc_html_e( 'Token URI', 'monk' ); ?></th>
	<td>
		<input type="text" name="monk_translate_token_uri" id="monk-translate-token-uri" value="<?php if ( $monk_translate_fields ) { echo esc_attr( $monk_translate_fields->token_uri ); } ?>">
	</td>
</tr>
<tr class="monk_translate_option">
	<th scope="row"><?php esc_html_e( 'Auth provider x509 cert URL', 'monk' ); ?></th>
	<td>
		<input type="text" name="monk_translate_auth_provider_x509_cert_url" id="monk-translate-auth-provider-x509-cert-url" value="<?php if ( $monk_translate_fields ) { echo esc_attr( $monk_translate_fields->auth_provider_x509_cert_url ); } ?>">
	</td>
</tr>
<tr class="monk_translate_option">
	<th scope="row"><?php esc_html_e( 'Client x509 cert URL', 'monk' ); ?></th>
	<td>
		<input type="text" name="monk_translate_client_x509_cert_url" id="monk-translate-client-x509-cert-url" value="<?php if ( $monk_translate_fields ) { echo esc_attr( $monk_translate_fields->client_x509_cert_url ); } ?>">
	</td>
</tr>
