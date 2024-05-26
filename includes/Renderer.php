<?php
namespace FlightFareSearch;

/**
 * Renderer class to handle front-end HTML rendering
 *
 * @package FlightFareSearch
 */
class Renderer {
    /**
     * Disallow instatiating.
     */
    private function __construct() {}

    /**
     * This function renders the front-end result of our API call.
     *
     * It renders a table with the flights data.
     *
     * @param array $data
     * @return void
     */
    public static function render_frontend($data) {
    ?>
        <div class="flight-fare-search-render">
            <h2 class="flight-fare-search-heading">
                Here are the flights!
            </h2>
            <p class="flight-fare-search-subheading">
                The flights are sorted by date (ascending) and then by price (also ascending).
            </p>
            <table class="flight-fare-search-minimal-table">
                <thead>
                    <tr>
                        <th>Departure date</th>
                        <th>Departure airport</th>
                        <th>Destination airport</th>
                        <th>Airline code</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($data as $row) : ?>
                    <tr>
                        <td><?php echo $row['departure_date']; ?></td>
                        <td><?php echo $row['departure_airport']; ?></td>
                        <td><?php echo $row['destination_airport']; ?></td>
                        <td><?php echo $row['airline_code']; ?></td>
                        <td><?php echo $row['price'] . $row['price_currency']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php
    }
}