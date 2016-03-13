<?php
/**
 * Before content Ad widget.
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

/**
 * Ad before content widget class.
 *
 * @since 1.0.0
 */
class Cdn_Ad_Banner extends WP_Widget {

	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Constructor. Set the default widget options and create widget.
	 */
	function __construct() {

		$this->defaults = array(
			'title'   => '',
			'ad_code' => ''
		);

		$widget_ops = array(
			'classname'   => 'cutedecision-ad',
			'description' => __( 'Displays ad banners', 'cutedecision' ),
		);

		parent::__construct( 'cutedecision-ad', __( 'Cutedecision - Ad Banner', 'cutedecision' ), $widget_ops );
	}

	/**
	 * Echo the widget content.
	 *
	 * @param array $args Display arguments including before_title, after_title, before_widget, and after_widget.
	 * @param array $instance The settings for the particular instance of the widget
	 */
	function widget( $args, $instance ) {

		//* Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $args['after_title'];
		}

		if ( ! empty( $instance['ad_code'] ) ) {
			echo $instance['ad_code'];
		}

		echo $args['after_widget'];

	}

	/**
	 * Update a particular instance.
	 *
	 * This function should check that $new_instance is set correctly.
	 * The newly calculated value of $instance should be returned.
	 * If "false" is returned, the instance won't be saved/updated.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via form()
	 * @param array $old_instance Old settings for this instance
	 *
	 * @return array Settings to save or bool false to cancel saving
	 */
	function update( $new_instance, $old_instance ) {

		$new_instance['title']   = strip_tags( $new_instance['title'] );
		$new_instance['ad_code'] = current_user_can( 'unfiltered_html' ) ? $new_instance['ad_code'] : genesis_formatting_kses( $new_instance['ad_code'] );

		return $new_instance;

	}

	/**
	 * Echo the settings update form.
	 *
	 * @param array $instance Current settings
	 */
	function form( $instance ) {

		//* Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		?>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'cutedecision' ); ?>
				:</label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
			       value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat"/>
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'ad_code' ) ); ?>"><?php _e( 'Ad Code', 'cutedecision' ); ?>
				:</label>
			<textarea class="widefat" rows="16" cols="20"
			          id="<?php echo esc_attr( $this->get_field_id( 'ad_code' ) ); ?>"
			          name="<?php echo esc_attr( $this->get_field_name( 'ad_code' ) ); ?>"><?php echo htmlspecialchars( $instance['ad_code'] ); ?></textarea>
		</p>
		<?php

	}

}
