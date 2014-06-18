<?php

class Event_Shortcodes {
	protected static $instance = NULL;

	private $params;
	private $events;

	public static function get_instance() {
		// create an object
		NULL === self::$instance and self::$instance = new self;

		return self::$instance; // return the object
	}

	private function attributes() {
		return shortcode_atts( array(
			'posts_per_page' => 5,
			'orderby'        => 'post_date',
			'order'          => 'DESC',
		), $this->params );
	}

	private function query() {
		$atts = $this->attributes();

		FB::log( $atts, '$atts' );

		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		$args = array(
			'posts_per_page'   => $atts['posts_per_page'],
			'paged'            => $paged,
			'offset'           => 0,
			'orderby'          => $atts['orderby'],
			'order'            => $atts['order'],
			'post_type'        => 'event',
			'post_status'      => 'publish',
			'suppress_filters' => true
		);

		$this->events = new WP_Query( $args );
	}

	private function pagination() {
		if ( $this->events->max_num_pages > 1 ) {
			$big = 999999999;
			echo '<nav class="pagination" style="margin-bottom: 20px;">';

			echo paginate_links( array(
				'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'  => '?paged=%#%',
				'current' => max( 1, get_query_var( 'paged' ) ),
				'total'   => $this->events->max_num_pages,
			) );

			echo '</nav>';
		}
	}

	public function event_list( $atts ) {
		$this->params = $atts;
		$this->query( $atts );

		if ( $this->events->have_posts() ) {
			ob_start();

			echo '<div class="event-list">';

			while ( $this->events->have_posts() ) :
				$this->events->the_post();
				?>

				<div class="event-item">
					<a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>

					<div class="thumb">
						<?php the_post_thumbnail(); ?>
					</div>

					<div class="excerpt">
						<?php the_excerpt(); ?>
					</div>
				</div>

			<?php
			endwhile;

			echo '</div>';

			$this->pagination();

			$content = ob_get_contents();
			ob_end_clean();
		} else {
			$content = 'Post not found!';
		}

		wp_reset_postdata();

		return $content;
	}

	public function event_grid( $atts ) {
		$this->params = $atts;
		$this->query( $atts );

		if ( $this->events->have_posts() ) {
			ob_start();

			echo '<div class="event-grid">';

			while ( $this->events->have_posts() ) :
				$this->events->the_post();
				?>

				<div class="event-item">
					<a href="<?php the_permalink(); ?>">
						<div class="thumb">
							<?php the_post_thumbnail( 'medium' ); ?>
						</div>
						<h3><?php the_title(); ?></h3>
					</a>
				</div>

			<?php
			endwhile;

			echo '</div>';

			$this->pagination();

			$content = ob_get_contents();
			ob_end_clean();
		} else {
			$content = 'Post not found!';
		}

		wp_reset_postdata();

		return $content;
	}
}

add_shortcode( 'eventlist', array( Event_Shortcodes::get_instance(), 'event_list' ) );
add_shortcode( 'eventgrid', array( Event_Shortcodes::get_instance(), 'event_grid' ) );