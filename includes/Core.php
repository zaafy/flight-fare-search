<?php
namespace FlightFareSearch;

/**
 * The front-end specific functionality of the plugin.
 *
 * Contains all neccessary calls to make front-end of the plugin work.
 *
 * @package    FlightFareSearch
 */
class Core {
	/**
	 * Constructor
	 *
	 * Constructor calls add_hooks() method to instantiate front-end work.
	 *
	 * @return void
	 */
    public function __construct() {
        $this->add_hooks();
	}

	/**
     * Add hooks to WordPress schedule.
     *
     * @return void
     */
	public function add_hooks() {
		add_action('init', array($this, 'load_plugin_textdomain'));
        add_filter('wp_footer', array( get_called_class(), 'generate_frontend'), 1); // Render the element in footer. Explanation in /includes/js/scripts.js.
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_and_styles'));
	}

	/**
	 * Loads plugin text domain
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'flight-fare-search',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}

	/**
     * Enqueues the scripts and styles used by plugin on front-end.
     *
     * @return void
     */
	public function enqueue_scripts_and_styles() {
        wp_enqueue_script(
            'flight-fare-search-scripts-front',
            get_bloginfo('url') . '/wp-content/plugins/flight-fare-search/includes/js/scripts.js',
            false,
            FLIGHT_FARE_SEARCH_VERSION
        );

		wp_enqueue_style(
            'flight-fare-search-styles-front',
            get_bloginfo('url') . '/wp-content/plugins/flight-fare-search/includes/css/style.css',
            false,
            FLIGHT_FARE_SEARCH_VERSION
        );
    }

	/**
	 * Generates front-end code of the plugin
	 *
	 * @return void
	 */
	public static function generate_frontend() {
		$data_getter = new DataGetter();
		$frontend_data = $data_getter->extract_data_for_frontend(); // Travactory: Remove this argument to use real data. Remember to fill URL in DataGetter->get_api_data();
		if ($frontend_data) {
			Renderer::render_frontend($frontend_data);
		}
	}
}