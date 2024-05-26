<?php
namespace FlightFareSearch\Admin;

/**
 * The admin specific functionality of the plugin.
 *
 * Contains all neccessary calls to make admin side of the plugin work.
 *
 * @package    FlightFareSearch
 */
class Core {
    /**
     * A list of fields created by the plugin in settings page.
     *
     * @var array
     */
    public $admin_fields = array(
        array('name' => 'agentID', 'nicename' => 'Agent ID'),
        array('name' => 'departures', 'nicename' => 'Departures'),
        array('name' => 'destinations', 'nicename' => 'Destinations'),
        array('name' => 'periodFromDate', 'nicename' => 'Period date from'),
        array('name' => 'periodToDate', 'nicename' => 'Period date to'),
        array('name' => 'airlineCodes', 'nicename' => 'Airline codes'),
    );

    /**
	 * Constructor
	 *
	 * Constructor adds actions to instantiate back-end field creation.
	 *
	 * @return void
     */
	public function __construct() {
        add_action('admin_menu', array($this,'flight_fare_search_create_options_page'));
        add_action('admin_init', array($this,'flight_fare_search_admin_fields_init'));
	}

    /**
     * Creates the plugin settings page
     *
     * @return void
     */
    public function flight_fare_search_create_options_page() {
        add_options_page(
            __('Flight Fare Search Settings', 'flight-fare-search'),
            __('Flight Fare Search Settings', 'flight-fare-search'),
            'manage_options',
            'flight-fare-search',
            array($this, 'flight_fare_search_add_options_page_content')
        );
    }

    /**
     * This function generates plugin options page content, including fields.
     *
     * @return void
     */
    public function flight_fare_search_add_options_page_content() {
        ?>
        <div class="flight-fare-search-options-page">
            <h2>
                <?php _e('Flight Fare Search Settings', 'flight-fare-search'); ?>
            </h2>
            <p>
                <?php _e('Configuration page for Flight Fare Search plugin.', 'flight-fare-search'); ?>
            </p>
            <form action="options.php" method="post">
                <?php settings_fields('flight_fare_search_options'); ?>
                <?php do_settings_sections('flight-fare-search'); ?>
                <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e('Save Changes'); ?>">
            </form>
        </div>
        <?php
    }

    /**
     * This function creates custom fields as WP options.
     *
     * These fields are used to make API calls to get flights data later on.
     *
     * @return void
     */
    public function flight_fare_search_admin_fields_init() {
        register_setting('flight_fare_search_options', 'flight_fare_search_options');
        add_settings_section('flight_fare_search_main', '', '', 'flight-fare-search');

        foreach ($this->admin_fields as $field) {
            add_settings_field(
                'flight_fare_search_field_' . $field['name'],
                $field['nicename'],
                function () use ($field) {
                    $options = get_option('flight_fare_search_options');
                    if (!isset($options['flight_fare_search_field_' . $field['name']])) {
                        $options['flight_fare_search_field_' . $field['name']] = '';
                    }
                    echo "<input id='flight_fare_search_field_'" . $field['name'] . " name='flight_fare_search_options[flight_fare_search_field_" . $field['name'] . "]' size='40' type='text' value='" . $options['flight_fare_search_field_' . $field['name']] . "' />";
                },
                'flight-fare-search',
                'flight_fare_search_main'
            );
        }
    }
}
