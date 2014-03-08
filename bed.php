<?php
$stringObj = ('{
   "results" : [
      {
         "address_components" : [
            {
               "long_name" : "22616",
               "short_name" : "22616",
               "types" : [ "street_number" ]
            },
            {
               "long_name" : "Bothell Everett Highway",
               "short_name" : "Bothell Everett Hwy",
               "types" : [ "route" ]
            },
            {
               "long_name" : "Canyon Park",
               "short_name" : "Canyon Park",
               "types" : [ "neighborhood", "political" ]
            },
            {
               "long_name" : "Bothell",
               "short_name" : "Bothell",
               "types" : [ "locality", "political" ]
            },
            {
               "long_name" : "Snohomish County",
               "short_name" : "Snohomish County",
               "types" : [ "administrative_area_level_2", "political" ]
            },
            {
               "long_name" : "Washington",
               "short_name" : "WA",
               "types" : [ "administrative_area_level_1", "political" ]
            },
            {
               "long_name" : "United States",
               "short_name" : "US",
               "types" : [ "country", "political" ]
            },
            {
               "long_name" : "98021",
               "short_name" : "98021",
               "types" : [ "postal_code" ]
            }
         ],
         "formatted_address" : "22616 Bothell Everett Highway, Bothell, WA 98021, USA",
         "geometry" : {
            "location" : {
               "lat" : 47.7932594,
               "lng" : -122.2161318
            },
            "location_type" : "ROOFTOP",
            "viewport" : {
               "northeast" : {
                  "lat" : 47.79460838029151,
                  "lng" : -122.2147828197085
               },
               "southwest" : {
                  "lat" : 47.79191041970851,
                  "lng" : -122.2174807802915
               }
            }
         },
         "types" : [ "street_address" ]
      }
   ],
   "status" : "OK"
}'
);

function __CMP__ ( $a, $b ) {

    return strcmp( $a, $b );
}

$content = file_get_contents( 'citiesList.txt' );

$arrayCities = preg_split ( '/$\R?^/m', $content );

usort( $arrayCities, '__CMP__' );

//echo '<pre>'; print_r( $arrayCities ); echo '</pre>';
echo json_encode( $arrayCities );
?>