<?php

return [


    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | Will be used for all web services,
    | unless overwritten bellow using 'google_map_api_key' parameter
    |
    |
    */
    'google_map_api_key' => env('GOOGLE_MAP_API_KEY', ''),
    'earth_radius' => 6371000,
    
    /*
    |--------------------------------------------------------------------------
    | Service URL
    |--------------------------------------------------------------------------
    | url - web service URL
    | type - request type POST or GET
    | key - API key, if different to API key above
    | endpoint - boolean, indicates whenever output parameter to be used in the request or not
    | responseDefaultKey - specify default field value to be returned when calling getByKey()
    | param - accepted request parameters
    |
    */

    'service' => [
        'geocoding' => [
            'url' => 'https://maps.googleapis.com/maps/api/geocode/json',
            'type' => 'GET',
            'key' => null,
            'endpoint' => true,
            'responseDefaultKey' => 'place_id',
            'param' => [
                'address' => null,
                'bounds' => null,
                'key' => null,
                'region' => null,
                'language' => null,
                'result_type' => null,
                'location_type' => null,
                'latlng' => null,
                'place_id' => null,
                'components' => [
                    'route' => null,
                    'locality' => null,
                    'administrative_area' => null,
                    'postal_code' => null,
                    'country' => null,
                ]
            ]
                ],

        'directions' => [
            'url'                   => 'https://maps.googleapis.com/maps/api/directions/json',
            'type'                  => 'GET',
            'key'                   =>  null,
            'endpoint'              =>  true,
            'responseDefaultKey'    =>  'geocoded_waypoints',
            'decodePolyline'        =>  true, // true = decode overview_polyline.points to an array of points
            'param'                 => [
                                        'origin'          => null, // required
                                        'destination'     => null, //required
                                        'mode'            => null,
                                        'waypoints'       => null,
                                        'place_id'        => null,
                                        'alternatives'    => null,
                                        'avoid'           => null,
                                        'language'        => null,
                                        'units'           => null,
                                        'region'          => null,
                                        'departure_time'  => null,
                                        'arrival_time'    => null,
                                        'transit_mode'    => null,
                                        'transit_routing_preference' => null,
                                        ]
        ],
    ],
    


    /*
    |--------------------------------------------------------------------------
    | Verify SSL Peer
    |--------------------------------------------------------------------------
    |
    | Will be used for all web services to verify
    | SSL peer (SSL certificate validation)
    |
     */
    'ssl_verify_peer' => FALSE,

    /*
     |--------------------------------------------------------------------------
     | CURL's connection timeout
     |--------------------------------------------------------------------------
     |
     | Will be used for all web services to limit
     | the maximum time tha connection can take in seconds
     |
      */
    'connection_timeout' => 5,

    /*
     |--------------------------------------------------------------------------
     | CURL's request timeout
     |--------------------------------------------------------------------------
     |
     | Will be used for all web services to limit
     | the maximum time a request can take
     |
      */
    'request_timeout' => 30,

    /*
     |--------------------------------------------------------------------------
     | CURL's CURLOPT_ENCODING
     |--------------------------------------------------------------------------
     |
     | Will be used for all web services to use compression on requests.
     |
     | Sets the contents of the "Accept-Encoding:" header a containing all
     | supported encoding types.
     |
      */
    'request_use_compression' => false,

    




    /*
    |--------------------------------------------------------------------------
    | End point
    |--------------------------------------------------------------------------
    |
    |
    */

    'endpoint' => [
        'xml'           => 'xml?',
        'json'          => 'json?',
    ],



];
