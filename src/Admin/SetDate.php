<?php
/**
 * This file contains the logic for Settings.
 *
 * @package AchttienVijftien\Plugin\Republish\Admin
 */

namespace AchttienVijftien\Plugin\Republish\Admin;

/**
 * Admin settings page.
 *
 * @package AchttienVijftien\Plugin\Republish\Admin
 */
class SetDate {

	/**
	 * SetDate constructor.
	 */
	public function __construct() {
		add_action( 'post_submitbox_misc_actions', [ $this, 'republish_date_meta_box' ] );
	}

	/**
	 * Adds the republish date picker
	 */
	public function republish_date_meta_box( $post ) {
		global $action;
		$date = '';

		$post_id = (int) $post->ID;

		// bail early if no post ID
		if ( 0 === $post_id ) {
			return '';
		}

		// bail early if not published or private
		if ( 'publish' !== $post->post_status && 'private' !== $post->post_status ) {
			return '';
		}

		$post_type        = $post->post_type;
		$post_type_object = get_post_type_object( $post_type );
		$can_publish      = current_user_can( $post_type_object->cap->publish_posts );

		// bail early if incorrect user privileges
		if ( ! $can_publish ) { // Contributors don't get to choose the date of publish.
			return '';
		}

		/* translators: Publish box date string. 1: Date, 2: Time. See https://www.php.net/manual/datetime.format.php */
		$date_string = __( '%1$s at %2$s' );
		/* translators: Publish box date format, see https://www.php.net/manual/datetime.format.php */
		$date_format = _x( 'M j, Y', 'publish box date format' );
		/* translators: Publish box time format, see https://www.php.net/manual/datetime.format.php */
		$time_format = _x( 'H:i', 'publish box time format' );

		?>
		<div class="misc-pub-section repub-time misc-pub-curtime">
			<span id="repub-timestamp">
				<?php echo __( 'Republish on:', 'wp-republish' ) . '<b>' . $date . '</b>'; ?>
			</span>
			<a href="#edit-repub-timestamp" > <!-- class="edit-timestamp hide-if-no-js" role="button"> -->
				<span aria-hidden="true"><?php _e( 'Edit' ); ?></span>
				<span class="screen-reader-text"><?php _e( 'Edit date and time' ); ?></span>
			</a>
			<fieldset id="repub-timestampdiv" >
				<legend class="screen-reader-text"><?php _e( 'Date and time' ); ?></legend>
				<?php touch_time( 0, 1 ); ?>
			</fieldset>
		</div>
		<?php

	}
}