<?php
namespace FlightFareSearch;
/**
 * Plugin Name: Flight Fare Search
 * Plugin URI: #!
 * Description: This plugin adds the Flight Fare Search functionality to WordPress pages. It allows to list a list of flights based on admin or user submitted criteria.
 * Version: 1.0.0
 * Author: Daniel Okoń
 * Author URI: #!
 * Developer: Daniel Okoń
 * Text Domain: flight-fare-search
 * Domain Path: /languages
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('FLIGHT_FARE_SEARCH_VERSION', "1.0.0");

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Main plugin class.
 *
 * @package FlightFareSearch
 */
class Init {
    /**
     * Constructor
     *
     * Show activation message when plugin is activated.
     */
    public function __construct() {
        $this->setup_activation_notice();
        $this->loadPlugin();
        add_filter('plugin_action_links_'.plugin_basename(__FILE__), array(get_called_class(), 'setup_settings_button'));
    }

    /**
     * Loads the plugin.
     *
     * @return void
     */
    public function loadPlugin() {
        $plugin = new \FlightFareSearch\Core;
        $admin = new \FlightFareSearch\Admin\Core;
    }

    /**
     * Adds 'Settings' link to plugin page entry.
     *
     * Settings link leads to a page where the criteria for fares can be set.
     *
     * @param array $links List of links present in a plugin entry.
     * @return void
     */
    public static function setup_settings_button($links) {
        $settings_link = '<a href="' . get_bloginfo('url') . '/wp-admin/options-general.php?page=flight-fare-search">'. __('Settings', 'flight-fare-search') .'</a>';
        $links[] = $settings_link;
        return $links;
    }

    /**
     * Sets up activation notice in admin panel.
     *
     * Set up transient, then checks the transient to display a message once.
     *
     * @return void
     */
    public function setup_activation_notice() {
        register_activation_hook(__FILE__, array($this, 'activate_transient'));
        add_action('admin_notices', array($this, 'call_plugin_activated_notice'));
    }

    /**
     * Sets up transient on plugin activation.
     *
     * @return void
     */
    public function activate_transient() {
        set_transient( 'flight_fare_search_activation_notice', true, 5 );
    }

    /**
     * Prepares the notice content and calls the display method if transient is present.
     *
     * @return void
     */
    function call_plugin_activated_notice() {
        if (get_transient('flight_fare_search_activation_notice')) {
            ob_start();
            ?>
            <p>
                <?php _e('Thank you for using Flight Fare Search!', 'flight-fare-search'); ?> <strong><?php _e('You are awesome!', 'flight-fare-search'); ?></strong>
            </p>
            <p>
                <?php _e('You can set up the search criteria here:', 'flight-fare-search'); ?>
                <a href="<?php echo get_bloginfo('url'); ?>/wp-admin/options-general.php?page=flight-fare-search">
                    <?php _e('Set up criteria.', 'flight-fare-search'); ?>
                </a>
            </p>
            <?php
            $message = ob_get_clean();
            \FlightFareSearch\Admin\Notices::display_notice('success', $message);
            delete_transient('flight_fare_search_activation_notice');
        }
    }
}

$init = new Init();