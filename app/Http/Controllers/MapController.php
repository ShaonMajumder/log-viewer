<?php

namespace App\Http\Controllers;
// use MapBox\MapBox;
use Shaon\Facades\MapBox;

class MapController extends Controller
{
    public function index(){
        dd( 
            $this->example1(),
            $this->example2(),
            $this->example3(),
            $this->example4(),
            $this->example5(),
            $this->example6(),
        );
    }


    public function example1(){
        // Example 1: Get coordinates from an address
        $address = '39/2 Kalachandpur (North baridhara) Gulshan, Dhaka-1212';
        $coordinates = MapBox::getCoordinatesFromAddress($address);
        return $coordinates;
    }

    public function example2(){
        // Example 2: Get an address from coordinates
        $latlongString = '23.726533,90.424630';
        $address = MapBox::getAddressFromCoordinates($latlongString);
        return $address;
    }

    public function example3(){
        // Example 3: Get waypoints path suggestion
        $waypointsArray = [
            [ 23.810052 , 90.416229  ],
            [ 23.9535742, 90.1494988 ],
            [ 23.810052 , 90.416229  ],
            // Add more waypoints as needed
        ];

        $suggestion = MapBox::getWaypointsPathSuggestion($waypointsArray);
        return $suggestion;
    }

    public function example4(){
        // Example 4: Get waypoints Order Optimization - a sorted order of waypoints for best traffic condition
        $start = [ 23.726533, 90.424630 ];
        $end   = [ 23.726533, 90.424630 ];
        $waypointsArray = [
            [ 23.810052 , 90.416229  ],
            [ 23.9535742, 90.1494988 ],
            [ 23.810052 , 90.416229  ],
            // Add more waypoints as needed
        ];
        $order = MapBox::getGMAPWaypointsOrderOptimization($start, $end, $waypointsArray);
        return $order;
    }

    public function example5(){
        // Example 5: Calculate haversine distance between two points
        $lat1 = 37.423021;
        $lon1 = -122.083739;
        $lat2 = 37.421999;
        $lon2 = -122.084057;
        $distance = MapBox::haversineDistance($lat1, $lon1, $lat2, $lon2);
        return $distance;
    }
    
    public function example6(){
        // Example 6: Calculate total distance from coordinates of waypoints
        $waypointsArray = [
            [ 23.810052 , 90.416229  ],
            [ 23.9535742, 90.1494988 ],
            [ 23.810052 , 90.416229  ],
            // Add more waypoints as needed
        ];
        $totalDistance = MapBox::calculateTotalDistanceFromCoordinates($waypointsArray);
        return $totalDistance;
    }

}