<?php

/**
 * Fetch news from given locale site.
 *
 * @link       http://chandrapatel.in
 * @since      1.0
 *
 * @package    Wp_Locale_News
 */

/**
 * Class Wp_Locale_News to fetch news from given locale site.
 *
 * @package    Wp_Locale_News
 *
 * @author     Chandra Patel <chandra.ddu@gmail.com>
 */
class Wp_Locale_News {

	/**
	 * Limit to display locale news.
	 *
	 * @since    1.0
	 * @access   protected
	 * @var      int    $locale_news_limit    Limit to display locale news.
	 */
	protected static $locale_news_limit = 5;

	/**
	 * Return locale site feed url based on selected locale in settings.
	 *
	 * @param string $locale_code A locale code.
	 *
	 * @since	1.0
	 *
	 * @return null|string $locale_site_feed_url URL of locale site feed.
	 */
	public static function get_locale_site_feed_url( $locale_code ) {

		if ( empty( $locale_code ) ) {

			return null;
		}

		$locale_parts = explode( '_', $locale_code );

		$rosetta_site_code = $locale_parts[0];

		$locale_site_feed_url = is_ssl() ? 'https://' : 'http://';

		$locale_site_feed_url .= $rosetta_site_code . '.wordpress.org/feed/';

		return $locale_site_feed_url;

	}

	/**
	 * Fetch locale site title.
	 *
	 * @since	1.0
	 *
	 * @param string $locale_site_feed URL of locale site feed.
	 *
	 * @return null|string
	 */
	public static function get_locale_site_title( $locale_site_feed = '' ) {

		if ( empty( $locale_site_feed ) ) {
			return null;
		}

		$news_feed = fetch_feed( $locale_site_feed );

		if ( is_wp_error( $news_feed ) ) {
			return null;
		}

		// Return locale site title.
		return $news_feed->get_title();
	}

	/**
	 * Fetch news from locale site. Caching built in.
	 *
	 * @param string $locale_site_feed URL of locale site feed.
	 *
	 * @since	1.0
	 *
	 * @return array|null List of {@see SimplePie_Item} objects
	 */
	public static function locale_news_fetcher( $locale_site_feed = '' ) {

		if ( empty( $locale_site_feed ) ) {
			return null;
		}

		$news_feed = fetch_feed( $locale_site_feed );

		/**
		 * Allow to change locale news display limit.
		 *
		 * @since	1.0
		 */
		$wpln_limit = apply_filters( 'wp_locale_news_limit', static::$locale_news_limit );

		if ( is_wp_error( $news_feed ) ) {
			return null;
		}

		// Return an array of all the news, starting with element 0 (first element).
		return $news_feed->get_items(
			0,
			$news_feed->get_item_quantity( absint( $wpln_limit ) )
		);

	}

}
