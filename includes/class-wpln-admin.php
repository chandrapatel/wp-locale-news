<?php

/**
 * Admin Settings for wp locale news.
 *
 * @link       http://chandrapatel.in
 * @since      1.0
 *
 * @package    Wp_Locale_News
 */

/**
 * Class Wpln_Admin for settings.
 *
 * @package    Wp_Locale_News
 *
 * @author     Chandra Patel <chandra.ddu@gmail.com>
 */
class Wpln_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0
	 * @access   protected
	 * @var      object    $instance    Instance of this class.
	 */
	protected static $instance;

	/**
	 * Returns new or existing instance.
	 *
	 * @since    1.0
	 *
	 * @return Wpln_Admin instance.
	 */
	final public static function get_instance() {

		if ( ! isset( static::$instance ) ) {

			static::$instance = new Wpln_Admin();

			static::$instance->setup();

		}

		return self::$instance;
	}

	/**
	 * Setup hooks.
	 *
	 * @since    1.0
	 */
	protected function setup() {

		add_action( 'admin_menu', array( $this, 'wpln_options_page' ) );

		add_action( 'admin_init', array( $this, 'wpln_settings' ) );

	}

	/**
	 * Settings for locale news.
	 */
	public function wpln_settings() {

		register_setting( 'wpln', 'wpln_options' );

		add_settings_section(
			'wpln_settings_section',
			'',
			'',
			'wpln'
		);

		add_settings_field(
			'wpln_locale_site_code',
			__( 'Select locale', 'wporg' ),
			array( $this, 'wpln_locale_site_code_cb' ),
			'wpln',
			'wpln_settings_section'
		);
	}

	/**
	 * Display setting to select locale code.
	 *
	 * @since	1.0
	 *
	 * @param array $args Setting arguments.
	 */
	public function wpln_locale_site_code_cb( $args ) {

		$options = get_option( 'wpln_options' );

		wp_dropdown_languages( array(
			'id' => 'locale_site_code',
			'name' => 'wpln_options[locale_site_code]',
			'selected' => ( ! empty( $options['locale_site_code'] ) ) ? $options['locale_site_code'] : '',
		) );

		echo '<p class="description">';

		esc_html_e(
			'Please select language to display news on dashboard. It will fetch news from Rosetta site of selected language.',
			'wp-locale-news'
		);

		echo '</p>';
	?>

	<?php
	}

	/**
	 * Add menu under Setting menu.
	 *
	 * @since	1.0
	 */
	public function wpln_options_page() {

		add_options_page(
			__( 'WP Locale News Settings', 'wp-locale-news' ),
			__( 'Locale News', 'wp-locale-news' ),
			'manage_options',
			'wp-locale-news',
			array( $this, 'wpln_options_page_html' )
		);
	}

	/**
	 * Display locale news settings.
	 *
	 * @since	1.0
	 */
	public function wpln_options_page_html() {

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Show error/update messages.
		settings_errors( 'wpln_messages' );

	?>
		<div class="wrap">

			<h1>
				<?php echo esc_html( get_admin_page_title() ); ?>
			</h1>

			<form action="options.php" method="post">

				<?php

				// Output security fields for the registered setting "wpln".
				settings_fields( 'wpln' );

				// Output setting sections and their fields.
				do_settings_sections( 'wpln' );

				// Output save settings button.
				submit_button( 'Save Settings' );

				?>

			</form>

		</div>

	<?php
	}

}

Wpln_Admin::get_instance();
