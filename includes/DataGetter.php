<?php
namespace FlightFareSearch;

/**
 * Data Getter Class to handle API calls and data preparation.
 *
 * @package    FlightFareSearch
 */
class DataGetter {
    /**
     * This function extracts data from the API response.
     *
     * @param boolean $mock True to use mock JSON data, false to use API call.
     * @return void
     */
    public function extract_data_for_frontend($mock = false) {
        if ($mock) {
            $data = $this->mock_get_api_data();
        } else {
            $data = $this->get_api_data();
        }

        if (is_array($data) && !empty($data)) {
            $return_data = array();

            foreach($data as $data_row) {
                array_push(
                    $return_data,
                    array(
                        'departure_date' => $data_row->flight->departure->date,
                        'departure_airport' => $data_row->flight->departure->name,
                        'destination_airport' => $data_row->flight->destination->name,
                        'airline_code' => $data_row->flight->airline->code,
                        'price' => $data_row->localPrice->amount, 2,
                        'price_currency' => $data_row->localPrice->currency
                    )
                );
            }

            $dep_date = array_column($return_data, 'departure_date');
            $price = array_column($return_data, 'price');
            array_multisort($dep_date, SORT_ASC, $price, SORT_ASC, $return_data);
            return $return_data;
        } else {
            return false;
        }
    }

    /**
     * Mocks the API Call functionality.
     *
     * This method takes JSON from ./flights.json
     *
     * @return void
     */
    public function mock_get_api_data() {
        if (file_exists(dirname(__DIR__) . '/flights.json')) {
            $data = array();
            $json = file_get_contents(dirname(__DIR__) . '/flights.json');
            $data = json_decode($json);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $data;
            } else {
                return false;
            }

            return $data;
        }
    }

    /**
     * This method gets the real API data of the flights.
     *
     * @return array $data | boolean Returns $data if everything went well. Returns false if something went wrong. Error logs are written to ./logs/ folder.
     */
    public function get_api_data() {
        // ```/api/fares/{agentId}/{departures}/{destinations}/{periodFromDate}/{periodToDate}?airlineCodes={airlineCodes}```
        $api_url = ''; // Travactory: Fill the API URL here, just the main part without trailing slash.

        $options = get_option('flight_fare_search_options');
        $agentId = $options['flight_fare_search_field_agentID'];
        $departures = $options['flight_fare_search_field_departures'];
        $destinations = $options['flight_fare_search_field_destinations'];
        $periodFromDate = $options['flight_fare_search_field_periodFromDate'];
        $periodToDate = $options['flight_fare_search_field_periodToDate'];
        $airlineCodes = $options['flight_fare_search_field_airlineCodes'];

        $request_url = sprintf(
            $api_url . '/api/fares/%s/%s/%s/%s/%s?airlineCodes=%s',
            urlencode($agentId),
            urlencode($departures),
            urlencode($destinations),
            urlencode($periodFromDate),
            urlencode($periodToDate),
            urlencode($airlineCodes)
        );

        $curl = curl_init($request_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Travactory: Fill the actual headers required by your API.
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer YOUR_ACCESS_TOKEN',
            'Accept: application/json'
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        if (curl_errno($curl)) {
            error_log('cURL error: ' . curl_error($curl), 3, dirname(__DIR__) . '/logs/error.log');
            return false;
        } else {
            $data = json_decode($response, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $data;
            } else {
                error_log('JSON decode error: ' . json_last_error_msg(), 3, dirname(__DIR__) . '/logs/error.log');
                return false;
            }
        }
    }
}