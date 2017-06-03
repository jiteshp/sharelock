<?php
/**
 * Plugin name: 	Sharelock - Facebook Share Content Locker
 * Plugin URI: 		https://github.com/jiteshp/sharelock/
 * Description: 	Locks your valuable content behind a Facebook share button.
 * Version: 		1.0.0
 * Author: 			Jitesh Patil
 * Author URI: 		https://www.jiteshpatil.com/
 * License: 		GPL3+
 * License URI: 	https://www.gnu.org/licenses/gpl-3.0.html/
 * Text domain: 	sharelock
 */

/**
 * Makes the plugin ready for translation.
 *
 * @since 1.0.0
 */
function sharelock_load_plugin_textdomain() {
	load_plugin_textdomain( 'sharelock', false, plugin_dir_path( __FILE__ ) . 'languages' );
}

add_action( 'plugins_loaded', 'sharelock_load_plugin_textdomain' );

/**
 * Enqueus the plugin's styles & scripts.
 *
 * @since 1.0.0
 */
function sharelock_scripts() {
	wp_enqueue_style( 'sharelock', plugin_dir_url( __FILE__ ) . 'css/style.css', array( 'dashicons' ) );

	wp_enqueue_script( 'sharelock', plugin_dir_url( __FILE__ ) . 'js/sharelock.js', array( 'jquery' ), null, true );

	$settings = get_option( 'sharelock_settings' );
	wp_localize_script( 'sharelock', 'sharelock', array(
		'appId'			=> $settings['sharelock_facebook_app_id'],
		'error_message'	=> esc_html__( 'Error sharing on Facebook. Please try again.', 'sharelock' ),
	) );
}

add_action( 'wp_enqueue_scripts', 'sharelock_scripts' );

/**
 * Adds the plugin's shortcode. The shortcode takes the following attributes.
 * 	- message: 	The message to display asking to unlock the content.
 * 	- url: 		The URL to share. Defaults to the current page's URL.
 *
 * @param 	array 	$atts 		The shortcode attributes
 * @param 	string 	$content 	The shortcode content
 * @return 	string
 * @since 1.0.0
 */
function sharelock_shortcode( $atts, $content ) {
	global $post;

	$atts = shortcode_atts( array(
		'message' => esc_html__( 'Share on Facebook to unlock.', 'sharelock' ),
		'url'	  => get_permalink( $post->ID ),
	), $atts );

	ob_start();
	include plugin_dir_path( __FILE__ ) . 'partials/shortcode.php';
	return ob_get_clean();
}

add_shortcode( 'sharelock', 'sharelock_shortcode' );

/**
 * Adds a settings page for plugin options.
 *
 * @since 1.0.0
 */
function sharelock_admin_menu() {
	add_options_page( esc_html__( 'Sharelock Settings', 'sharelock' ), esc_html__( 'Sharelock', 'sharelock' ), 'manage_options', 'sharelock', 'sharelock_settings_page_html' );
}

add_action( 'admin_menu', 'sharelock_admin_menu' );

/**
 * Outputs the HTML for the settings page.
 *
 * @since 1.0.0
 */
function sharelock_settings_page_html() {
	// Check user capability
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>

	<div class="wrap">
		<h1><?php esc_html_e( 'Sharelock Settings', 'sharelock' ); ?></h1>

		<form action="options.php" method="post">
			<?php
			settings_fields( 'sharelock' );
			do_settings_sections( 'sharelock' );
			submit_button( esc_html__( 'Save Settings', 'sharelock' ) );
			?>
		</form>
	</div>

	<?php
}

/**
 * Registers the settings.
 *
 * @since 1.0.0
 */
function sharelock_settings_init() {
	register_setting( 'sharelock', 'sharelock_settings' );

	add_settings_section(
		'sharelock_facebook_section',
		esc_html__( 'Facebook Settings', 'sharelock' ),
		'sharelock_facebook_section_html',
		'sharelock'
	);

	add_settings_field(
		'sharelock_facebook_app_id',
		esc_html__( 'Facebook App ID', 'sharelock' ),
		'sharelock_facebook_app_id_html',
		'sharelock',
		'sharelock_facebook_section'
	);
}

add_action( 'admin_init', 'sharelock_settings_init' );

/**
 * Outputs the Facebook section HTML.
 *
 * @since 1.0.0
 */
function sharelock_facebook_section_html() {
	echo '<p>' . __( 'Create a <a href="https://developers.facebook.com/" target="_blank">Facebook app</a> &amp; add it&rsquo;s App ID below.', 'sharelock' ) . '</p>';
}

/**
 * Outputs the Facebook App ID field HTML.
 *
 * @since 1.0.0
 */
function sharelock_facebook_app_id_html() {
	$settings = get_option( 'sharelock_settings' );
	printf( '<input type="text" id="sharelock_settings[sharelock_facebook_app_id]" name="sharelock_settings[sharelock_facebook_app_id]" class="regular-text" value="%s" />', esc_attr( $settings['sharelock_facebook_app_id'] ) );
}
