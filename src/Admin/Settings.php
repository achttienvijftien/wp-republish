<?php
/**
 * This file contains the logic for Settings.
 *
 * @package AchttienVijftien\Plugin\Republish\Admin
 */

namespace AchttienVijftien\Plugin\Republish\Admin;

use AchttienVijftien\Plugin\Republish\Config;

/**
 * Admin settings page.
 *
 * @package AchttienVijftien\Plugin\Republish\Admin
 */
class Settings {

	/**
	 * Prefix used to namespace settings.
	 */
	private const SETTINGS_PREFIX = '1815_republish_setting_';

	/**
	 * Page slug for general page.
	 */
	private const GENERAL_PAGE_SLUG = 'wp-republish-general';

	/**
	 * Settings constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_menu_page' ] );
		add_action( 'admin_init', [ $this, 'add_settings' ] );
		add_filter(
			'plugin_action_links_wp-republish/wp-republish.php',
			[ $this, 'plugin_settings_link' ]
		);
	}

	/**
	 * Adds plugin menu item(s).
	 */
	public function add_menu_page(): void {
		// Add general plugin settings page to options menu.
		add_options_page(
			__( 'WordPress Republish', 'wp-republish' ),
			__( 'Republish', 'wp-republish' ),
			'manage_options',
			self::GENERAL_PAGE_SLUG,
			[ $this, 'show_general_page' ]
		);
	}

	/**
	 * Renders HTML of general page.
	 */
	public function show_general_page(): void {
		?>
		<div class="wrap">
			<h1>
				<?php esc_html_e( 'WordPress Republish', 'wp-republish' ); ?>
			</h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( self::SETTINGS_PREFIX . 'general' );
				do_settings_sections( self::GENERAL_PAGE_SLUG );
				?>
				<input name="submit" class="button button-primary" type="submit"
					   value="<?php esc_attr_e( 'Save' ); ?>"/>
			</form>
		</div>
		<?php
	}

	/**
	 * Adds settings.
	 */
	public function add_settings(): void {
		add_settings_section(
			self::SETTINGS_PREFIX . 'general',
			__( 'General Settings', 'wp-republish' ),
			[
				$this,
				'general_section_text',
			],
			self::GENERAL_PAGE_SLUG
		);

		$this->add_amount_setting();
	}

	/**
	 * Description of general section.
	 */
	public function general_section_text(): void {
		echo '<p>';
		esc_html_e(
			'Default settings used by shortcodes, widgets and Gutenberg blocks.',
			'wp-republish'
		);
		echo '</p>';
	}

	/**
	 * Default amount setting.
	 */
	public function add_amount_setting(): void {
		register_setting(
			self::SETTINGS_PREFIX . 'general',
			Config::get_option_name( 'amount' ),
			[
				'type'              => 'integer',
				'sanitize_callback' => 'is_int',
				'default'           => 5,
			]
		);

		add_settings_field(
			self::SETTINGS_PREFIX . 'amount',
			__( 'Default amount', 'wp-republish' ),
			[
				$this,
				'post_type_setting_field',
			],
			self::GENERAL_PAGE_SLUG,
			self::SETTINGS_PREFIX . 'general'
		);
	}

	/**
	 * Multi-select post types to republish.
	 */
	public function post_type_setting_field(): void {
		$post_types = Config::get_instance()->get( 'post_types' );

		echo '<p class="description">';
		esc_html_e( 'Choose which post type can be republished.', 'wp-republish' );
		echo '</p>';
	}

	/**
	 * Adds link to general settings page on plugin list page.
	 *
	 * @param array $links Links of this plugin to show on plugin list page.
	 *
	 * @return array
	 */
	public function plugin_settings_link( array $links ): array {
		// Get link to general settings page.
		$url = esc_url(
			add_query_arg(
				'page',
				self::GENERAL_PAGE_SLUG,
				get_admin_url() . 'admin.php'
			)
		);

		// Create the link.
		$links[] = '<a href="' . $url . '">' . esc_html__( 'Settings' ) . '</a>';

		return $links;
	}
}
