<?php

/**
 * Dashboard widget to show news from locale site.
 *
 * @link       http://chandrapatel.in
 * @since      1.0
 *
 * @package    Wp_Locale_News
 */

/**
 * Class Wpln_Dashboard_Widget to show news from locale site.
 *
 * @package    Wp_Locale_News
 *
 * @author     Chandra Patel <chandra.ddu@gmail.com>
 */
class Wpln_Dashboard_Widget {

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
	 * @return Wpln_Dashboard_Widget instance.
	 */
	final public static function get_instance() {

		if ( ! isset( static::$instance ) ) {

			static::$instance = new Wpln_Dashboard_Widget();

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

		add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widgets' ) );

	}

	/**
	 * Add dashboard widget.
	 *
	 * @since	1.0
	 */
	public function add_dashboard_widgets() {

		$options = get_option( 'wpln_options' );

		if ( empty( $options['locale_site_code'] ) ) {

			$locale_site_title = 'Locale';

		} else {

			$locale_site_title = Wp_Locale_News::get_locale_site_title(
				Wp_Locale_News::get_locale_site_feed_url(
					$options['locale_site_code']
				)
			);

		}

		wp_add_dashboard_widget(
			'wpln_dashboard_widget',
			/* translators: %s contain locale name. */
			sprintf( __( 'WordPress %s News', 'wp-locale-news' ), $locale_site_title ),
			array( $this, 'display_locale_news' )
		);

	}

	/**
	 * Display locale news.
	 *
	 * @since	1.0
	 */
	public function display_locale_news() {

		$options = get_option( 'wpln_options' );

		if ( empty( $options['locale_site_code'] ) ) {

			esc_html_e( 'Please select locale site from settings page. Please go to Settings -> Locale News.', 'wp-locale-news' );

			return null;
		}

		$locale_site_feed_url = Wp_Locale_News::get_locale_site_feed_url( $options['locale_site_code'] );

		// Fetch locale news.
		$locale_news = Wp_Locale_News::locale_news_fetcher( $locale_site_feed_url );

		// See if we have locale news.
		if ( 0 < count( $locale_news ) ) {

			$output = '<ul style="font-size: 14px;">' . "\n";

			// Loop through each news item and display each news as a hyperlink.
			foreach ( $locale_news as $news ) {

				$output .= '<li style="margin-bottom: 10px;">' . "\n";

				$output .= '<a class="wpln_title" target="_blank" href="' . esc_url( $news->get_permalink() ) . '">';

				$output .= esc_html( $news->get_title() ) . "\n";

				$output .= '</a>' . "\n";

				$output .= '<span class="wpln_pubdate" style="color: #72777c; font-size: 13px;"> &mdash; ' . esc_html( $news->get_date( 'j F Y' ) ) . '</span> ' . "\n";

				$output .= '</li>' . "\n";

			}

			$output .= '</ul>' . "\n";

			echo $output; // @codingStandardsIgnoreLine

		} else {

			esc_html_e( 'Error: Unable to fetch news from locale site.', 'wp-locale-news' );
		}
	}

}

Wpln_Dashboard_Widget::get_instance();
