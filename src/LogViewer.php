<?php

namespace Shaon;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class LogViewer {

    const EARTH_RADIUS = 6371000; // Earth's radius in meters
    protected $google_map_api_key;

    function __construct() {
        $this->google_map_api_key = Config::get('mapbox.google_map_api_key') ?? env('GOOGLE_MAP_API_KEY', '');
    }

    /**
     * Get latitude and longitude coordinates from an address using the Google Maps Geocoding API.
     *
     * @param string $address The address to geocode.
     * @return array An array containing latitude and longitude coordinates in the format ['latitude', 'longitude'].
     */
    public function getCoordinatesFromAddress($address){
        try{
            $apiUrl = Config::get('mapbox.service.geocoding.url');
            $apiKey = $this->google_map_api_key;
            $queryParams = [
                'address' => urlencode($address),
                'key' => $apiKey,
            ];
            $apiUrl = $apiUrl . '?' . http_build_query($queryParams);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = json_decode(curl_exec($ch), true);
            Log::info("Google Geocoding API Response ::: ",$output ?? "");
            curl_close($ch);
            $latitude = $output['results'][0]['geometry']['location']['lat'];
            $longitude = $output['results'][0]['geometry']['location']['lng'];
            return array($latitude,$longitude);
        }catch(Exception $e){
            Log::error("Google Geocoding API Method --- Failed");
            return [];
        }
    }

    /**
     * Get the address from coordinates using the Google Maps Geocoding API.
     *
     * @param string $latlongstring The latitude and longitude coordinates as a string (e.g., "latitude,longitude").
     * @return string The formatted address corresponding to the given coordinates.
     */
    public function getAddressFromCoordinates($latlongstring){
        try{
            $apiUrl = Config::get('mapbox.service.geocoding.url');
            $apiKey = $this->google_map_api_key;
            $queryParams = [
                'latlng' => $latlongstring,
                'key' => $apiKey,
            ];
            $apiUrl = $apiUrl . '?' . http_build_query($queryParams);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = json_decode(curl_exec($ch),true);
            Log::info("Google Geocoding API Response ::: ",$output ?? "");
            curl_close($ch);
            
            $outputs = $output['results'];
            // avoid address which is a plus_code instead of a local addreess
            foreach($outputs as $out){
                $address_components = $out['address_components'];
                if(is_array($address_components)){
                    foreach($address_components as $component){       
                        if( is_array($component['types']) ){
                            foreach($component['types'] as $typ){
                                if($typ == 'plus_code'){
                                    break 2;
                                }else{
                                    return $out['formatted_address'];
                                }
                            }
                        }
                    }
                }
            }
            return $output["results"][0]["formatted_address"];
        }catch(Exception $e){
            Log::error("Google Geocoding API Method --- Failed");
            return '';
        }
    }

    /**
     * Get path suggestions for waypoints using the Google Maps Directions API.
     *
     * This method calculates path suggestions for a given set of waypoints using the Google Maps
     * Directions API. It returns an object containing information about the path, including total
     * distance, total time, and path coordinates.
     * 
     * Travel Mode : 'driving', 'walking', 'bicycling', or 'transit'
     * Traffic Model "bestguess", "pessimistic", or "optimistic"
     * Waypoints Format : waypoints=via:-37.81223,144.96254|via:-34.92788,138.60008
     *
     * @param array $coordinates_array An array of latitude and longitude coordinate pairs for the waypoints.
     * @return object|null An object containing path suggestions or null if the API request fails.
     */
    public function getWaypointsPathSuggestion($coordinates_array){
        try{
            $apiUrl = Config::get('mapbox.service.directions.url');
            $apiKey = $this->google_map_api_key;

            $start = $coordinates_array[0][0].','.$coordinates_array[0][1] ;
            $end = end($coordinates_array)[0].','.end($coordinates_array)[1];
            foreach($coordinates_array as $coordinates){
                $waypoints_string[] = 'via:'.$coordinates[0].','.$coordinates[1];
            }
            $waypoints_string = implode('|', $waypoints_string);
            $travelMode = 'driving'; 
            $currentDateTime = date('Y-m-d H:i:s');
            $trafficModel = 'best_guess'; 
            $drivingOptions = [
                'departureTime' => $currentDateTime,
                'trafficModel'  => $trafficModel 
            ];
            
            $params = [
                'origin' => $start,
                'destination' => $end,
                'waypoints' => $waypoints_string,
                'mode' => $travelMode,
                // 'drivingOptions' => $drivingOptions,
                'departure_time' => 'now',
                'traffic_model' => $trafficModel,
                'key' => $apiKey
            ];
            
            $requestUrl = $apiUrl . '?' . http_build_query($params);
            
            $ch = curl_init($requestUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                Log::error("Google Map Direction API Response ::: cURL error: " . curl_error($ch));
                throw new Exception();
            } 
            curl_close($ch);

            $response= json_decode($response);
            
            $return_values = $this->computeTotalDistanceTime($response);
            $path_coordinates = $this->getCoordinatesFromGMAPWaypointsResponse($response);
            $return_values['gmap_path_coordinates'] = $path_coordinates;
            $return_values['previous_suggestion'] = false;
            $return_values['new_suggestion'] = true;
            return (object) $return_values;
        }catch(Exception $e){
            Log::error("Google Map Direction API Method --- Failed ".$e->getMessage());
            return null;
        }
    }

    /**
     * Get optimized waypoints order using the Google Maps Directions API.
     *
     * This method calculates the optimized order of waypoints for a given start, end, and array of waypoints
     * using the Google Maps Directions API. It returns an array containing the optimized order of waypoints.
     *
     * @param array $start An array containing the latitude and longitude of the starting point.
     * @param array $end An array containing the latitude and longitude of the ending point.
     * @param array $waypoints_array An array of latitude and longitude coordinate pairs for the waypoints.
     * @return array|null An array containing the optimized order of waypoints or null if the API request fails.
     */
    public function getGMAPWaypointsOrderOptimization($start,$end,$waypoints_array){
        try{
            $apiKey = $this->google_map_api_key;
            $apiUrl = Config::get('mapbox.service.directions.url');
            $start = $start[0].','.$start[1] ;
            $end = $end[0].','.$end[1] ;
           
            foreach ($waypoints_array as $coordinates) {
                $waypoints_string[] = $coordinates[0] . ',' . $coordinates[1];
            }

            $waypoints_string = implode('|', $waypoints_string);
            $travelMode = 'driving'; 
            $currentDateTime = date('Y-m-d H:i:s');
            $trafficModel = 'best_guess'; 
            $drivingOptions = [
                'departureTime' => $currentDateTime,
                'trafficModel'  => $trafficModel 
            ];
            
            $params = [
                'origin' => $start,
                'destination' => $end,
                'waypoints' => 'optimize:true|'.$waypoints_string,
                'mode' => $travelMode,
                // 'drivingOptions' => $drivingOptions,
                'departure_time' => 'now',
                'traffic_model' => $trafficModel,
                'key' => $apiKey
            ];
            
            $requestUrl = $apiUrl . '?' . http_build_query($params);
            
            $ch = curl_init($requestUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                Log::error("Google Map Direction API Response ::: cURL error: " . curl_error($ch));
                throw new Exception();
            } 
            curl_close($ch);

            $response= json_decode($response);
            return (array) $response->routes[0]->waypoint_order;
            
        }catch(Exception $e){
            Log::error("Google Map Direction API Method --- Failed ".$e->getMessage());
            return null;
        }

    }

    /**
     * Response Helper for Google Map
     * 
     * Calculate the total distance and time from a Google Maps Directions API response.
     *
     * This function takes a response object from the Google Maps Directions API and
     * calculates the total distance and time of the route based on the response data.
     *
     * @param object $response The response object from the Google Maps Directions API.
     * @return array An array containing total distance, total time, distance unit, and time unit.
     */
    protected function computeTotalDistanceTime($response) {
        $totalDist = 0;
        $totalTime = 0;
        $myroute = $response->routes[0]->legs;
    
        foreach ($myroute as $leg) {
            $totalDist += $leg->distance->value;
            $totalTime += $leg->duration->value;
        }
    
        $totalDist = $totalDist / 1000;
        $totalTime = round($totalTime / 60, 2); // in minutes
    
        return [
            'total_distance' => $totalDist,
            'total_time' => $totalTime,
            'distance_unit' => 'km',
            'time_unit' => 'minute'
        ];
    }

    /**
     * Response Helper for Google Map
     * 
     * Extract and return a list of coordinates from a Google Maps Directions API response.
     *
     * This function takes a response object from the Google Maps Directions API and
     * extracts a list of latitude and longitude coordinates that make up the path of
     * the route from the response data.
     *
     * @param object $response The response object from the Google Maps Directions API.
     * @return array An array of latitude and longitude coordinate pairs.
     */
    protected function getCoordinatesFromGMAPWaypointsResponse($response){
        $pathCoordinates = [];
        $legs = $response->routes[0]->legs;
            
        foreach ($legs as $leg) {
            foreach ($leg->steps as $step) {
                $polyline_string = $step->polyline->points;
                $polyline_points = $this->decodePolyline($polyline_string);
                // $path = $step['path'];
                foreach ($polyline_points as $point) {
                    $pathCoordinates[] = [
                        'lat' => $point[0],
                        'lng' => $point[1]
                    ];
                }
            }
        }
        return $pathCoordinates;
    }

    /**
     * Response Helper for Google Map
     * 
     * Decode a polyline string into an array of latitude and longitude coordinates.
     *
     * This function decodes a polyline string, which is a compressed representation
     * of a series of geographical coordinates, into individual latitude and longitude
     * coordinate pairs.
     *
     * @param string $polyline_str The encoded polyline string to decode.
     * @return array An array of latitude and longitude coordinate pairs.
     */
    public function decodePolyline($polyline_str) {
        $index = 0;
        $lat = 0;
        $lng = 0;
        $coordinates = [];
        $changes = ['latitude' => 0, 'longitude' => 0];
    
        while ($index < strlen($polyline_str)) {
            foreach (['latitude', 'longitude'] as $unit) {
                $shift = 0;
                $result = 0;
    
                while (true) {
                    $byte = ord($polyline_str[$index]) - 63;
                    $index++;
                    $result |= ($byte & 0x1f) << $shift;
                    $shift += 5;
                    if (!($byte >= 0x20)) {
                        break;
                    }
                }
    
                if ($result & 1) {
                    $changes[$unit] = ~($result >> 1);
                } else {
                    $changes[$unit] = ($result >> 1);
                }
            }
    
            $lat += $changes['latitude'];
            $lng += $changes['longitude'];
    
            $coordinates[] = [$lat / 100000.0, $lng / 100000.0];
        }
    
        return $coordinates;
    }

    
    /**
     * Calculate the Haversine distance between two sets of latitude and longitude coordinates.
     *
     * The Haversine formula is used to calculate the shortest distance between two points on a sphere
     * given their latitude and longitude in decimal degrees.
     *
     * @param float $lat1 Latitude of the first point in decimal degrees.
     * @param float $lon1 Longitude of the first point in decimal degrees.
     * @param float $lat2 Latitude of the second point in decimal degrees.
     * @param float $lon2 Longitude of the second point in decimal degrees.
     *
     * @return float The distance between the two points in meters.
     */
    public function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $R = $this::EARTH_RADIUS;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos($lat1) * cos($lat2) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $R * $c; // Distance in meters
        return $distance;
    }

    /**
     * Simulate realistic driving data
     * a random walk to simulate realistic driving data within a given range
     * Simulate a series of driving data points over a given number of steps.
     *
     * This function generates a series of simulated driving speeds over a specified number of steps.
     * The speed at each step is calculated by adding a random value within the given step size to
     * the current speed. The speed is constrained to stay within the range of 0 to the maximum speed.
     *
     * @param int $count The number of data points to generate.
     * @param int $initialSpeed The initial speed in kilometers per hour (default is 0).
     * @param int $maxSpeed The maximum allowable speed in kilometers per hour (default is 60).
     * @param int $stepSize The maximum change in speed for each step (default is 2).
     *
     * @return array An array of simulated driving speeds over the specified number of steps.
     */
    function simulateDrivingData($count, $initialSpeed = 0, $maxSpeed = 60, $stepSize = 2) {
        $data = [];
        $currentSpeed = $initialSpeed;

        for ($i = 0; $i < $count; $i++) {
            // Add a random value within the step size to the current speed
            $currentSpeed += rand(-$stepSize, $stepSize);

            // Ensure the speed stays within the given range
            $currentSpeed = max(0, min($maxSpeed, $currentSpeed));

            $data[] = $currentSpeed;
        }

        return $data;
    }

    /**
     * Calculate the total distance traveled based on an array of coordinates.
     *
     * This function calculates the total distance traveled along a path defined
     * by an array of coordinates. It uses the Haversine formula to calculate the
     * distance between each pair of consecutive coordinates and sums them up to
     * get the total distance traveled.
     *
     * @param array $coordinates An array of coordinates (latitude, longitude) along the path.
     *
     * @return float The total distance traveled in kilometers.
     */
    public function calculateTotalDistanceFromCoordinates($coordinates)
    {
        $totalDistance = 0;
        for ($i = 0; $i < count($coordinates) - 1; $i++) {
            $point1 = $coordinates[$i];
            $point2 = $coordinates[$i + 1];
            $totalDistance += $this->calculateDistanceOnSphere($point1, $point2);
        }
        return $totalDistance;
    }

    /**
     * Calculate the distance between two points on the Earth's surface using the Haversine formula.
     *
     * @param array $point1 An array containing the latitude and longitude of the first point.
     * @param array $point2 An array containing the latitude and longitude of the second point.
     *
     * @return float The distance between the two points in meters. Returns 0.0 for invalid input.
     */
    public function calculateDistanceOnSphere($point1, $point2)
    {
        list($lat1, $lon1) = $point1;
        list($lat2, $lon2) = $point2;

        if (!is_array($point1) || !is_array($point2) || count($point1) != 2 || count($point2) != 2) {
            return 0.0; // Invalid input, return 0.0 distance
        }

        if(is_numeric($lat1) && is_numeric($lon1) && is_numeric($lat2) && is_numeric($lon2)){
            $distance = $this->haversineDistance($lat1, $lon1, $lat2, $lon2);
            return $distance;
        }else {
            return 0.0;
        }
    }

    /**
     * Function to generate a random adjustment within a certain range
     */
    public function getRandomAdjustment($range)
    {
        return (mt_rand(0, 1) == 0 ? -1 : 1) * $range * mt_rand() / mt_getrandmax();
    }
}
