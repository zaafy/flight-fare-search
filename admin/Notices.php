<?php
namespace FlightFareSearch\Admin;

/**
 * Static class to handle admin notices display.
 *
 * @package    FlightFareSearch
 */
class Notices {
    /**
     * Disallow instatiating.
     */
    private function __construct() {}

    /**
     * Display an admin notice
     *
     * Display a notice of given 'type' with given 'message' in admin panel.
     * The notice is dismissible.
     *
     * @param string $type
     * @param string $message
     * @return void
     */
    public static function display_notice($type ='', $message) {
        echo <<<HTML
        <div class="notice notice-$type is-dismissible">
            $message
        </div>
HTML;
    }
}