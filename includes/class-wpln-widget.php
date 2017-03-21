<?php

/**
 * Widget to show news from locale site in theme.
 *
 * @link       http://chandrapatel.in
 * @since      1.0
 *
 * @package    Wp_Locale_News
 */

/**
 * Class Wpln_Widget to show news from locale site in theme.
 *
 * @package    Wp_Locale_News
 *
 * @author     Chandra Patel <chandra.ddu@gmail.com>
 */
class Wpln_Widget extends WP_Widget {

	/**
	 * Set up Widget.
	 *
	 * @since    1.0
	 */
	public function __construct() {

		parent::__construct(
			'wp-locale-news-widget',
			__( 'WP Locale News', 'wp-locale-news' ),
			array(
				'description' => __( 'Display news from locale\'s Rosetta site.', 'wp-locale-news' ),
			)
		);

	}

	/**
	 * Widget form.
	 *
	 * @since	1.0
	 *
	 * @param array $instance Previously saved values from database.
	 *
	 * @return void
	 */
	public function form( $instance ) {

		if ( empty( $instance['title'] ) ) {
			$instance['title'] = '';
		}

		if ( empty( $instance['locale_site_code'] ) ) {
			$instance['locale_site_code'] = '';
		}

		if ( empty( $instance['show_date'] ) ) {
			$instance['show_date'] = 0;
		}
	?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title:', 'wp-locale_news' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'locale_site_code' ) ); ?>">
				<?php esc_html_e( 'Select locale:', 'wp-locale_news' ); ?>
			</label>

			<?php
			wp_dropdown_languages( array(
				'id' => $this->get_field_id( 'locale_site_code' ),
				'name' => $this->get_field_name( 'locale_site_code' ),
				'selected' => $instance['locale_site_code'],
			) );
			?>
		</p>

		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $instance['show_date'], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>">
				<?php esc_html_e( '	Display date:', 'wp-locale_news' ); ?>
			</label>
		</p>

	<?php

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @since	1.0
	 *
	 * @param  array $new_instance Values just sent to be saved.
	 * @param  array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		// Set up current instance to hold old_instance data.
		$instance = $old_instance;

		// Set instance to hold new instance data.
		$instance['title']              = sanitize_text_field( $new_instance['title'] );
		$instance['locale_site_code']   = sanitize_text_field( $new_instance['locale_site_code'] );
		$instance['show_date']          = ( ! empty( $new_instance['show_date'] ) ) ? 1 : 0;

		return $instance;
	}

	/**
	 * Front-end display of widget.
	 *
	 * @since	1.0
	 *
	 * @param array $args	  Widget arguments.
	 * @param array $instance Saved values of widget.
	 */
	public function widget( $args, $instance ) {

		if ( empty( $instance['locale_site_code'] ) ) {
			return;
		}

		echo wp_kses_post( $args['before_widget'] );

		if ( ! empty( $instance['title'] ) ) {
			echo wp_kses_post( $args['before_title'] ) . esc_html( $instance['title'] ) . wp_kses_post( $args['after_title'] );
		}

		$locale_site_feed_url = Wp_Locale_News::get_locale_site_feed_url( $instance['locale_site_code'] );

		// Fetch locale news.
		$this->locale_news_data = Wp_Locale_News::locale_news_fetcher( $locale_site_feed_url );

		// See if we have locale news.
		if ( 0 < count( $this->locale_news_data ) ) {

			$output = '<ul class="wp-locale-news" >' . "\n";

			// Loop through each news item and display each news as a hyperlink.
			foreach ( $this->locale_news_data as $news ) {

				$output .= '<li>' . "\n";

				$output .= '<a class="wpln_title" target="_blank" href="' . esc_url( $news->get_permalink() ) . '">';

				$output .= esc_html( $news->get_title() ) . "\n";

				$output .= '</a>' . "\n";

				if ( ! empty( $instance['show_date'] ) ) {
					$output .= '<span class="wpln_date post-date" style="display: block;">' . esc_html( $news->get_date( 'j F Y' ) ) . '</span> ' . "\n";
				}

				$output .= '</li>' . "\n";

			}

			$output .= '</ul>' . "\n";

			echo $output; // @codingStandardsIgnoreLine

		} else {

			esc_html_e( 'No news found.', 'wp-locale-news' );

		}

		echo wp_kses_post( $args['after_widget'] );
	}

}

/**
 * Register Wpln_Widget widget.
 *
 * @since	1.0
 */
function register_wpln_widget() {

	register_widget( 'Wpln_Widget' );

}

add_action( 'widgets_init', 'register_wpln_widget' );
