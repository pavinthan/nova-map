<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Indicates the element height
    |--------------------------------------------------------------------------
    |
    | This option may be used to change height of the map
    | It's work in detail and form view
    | must be a pixels value
    |
    */

    'height' => 500,

    /*
    |--------------------------------------------------------------------------
    | Indicates the element zoom
    |--------------------------------------------------------------------------
    |
    | This option may be used to change zoom of the map
    | It's work in detail and form view
    | must be a integer value
    |
    */

    'zoom' => 0,

    /*
    |--------------------------------------------------------------------------
    | Indicates the map view latitude
    |--------------------------------------------------------------------------
    |
    | This option may be used to change default latitude of the map
    | It's work only in form view
    | must be a float value
    |
    */

    'latitude' => 0,

    /*
    |--------------------------------------------------------------------------
    | Indicates the map view longitude
    |--------------------------------------------------------------------------
    |
    | This option may be used to change default longitude of the map
    | It's work only in form view
    | must be a float value
    |
    */

    'longitude' => 0,

    /*
    |--------------------------------------------------------------------------
    | Indicates the controls of the map edit
    |--------------------------------------------------------------------------
    |
    | This option may be used to change default controls of the map
    | It's work only in form view
    | must be an array value
    |
    */

    'controls' => [
        "position" => 'topleft', // toolbar position, options are 'topleft', 'topright', 'bottomleft', 'bottomright'
        "useFontAwesome" => false, // use fontawesome instead of geomanIcons (you need to include fontawesome yourself)
        "drawMarker" => true, // adds button to draw markers
        "drawPolyline" => true, // adds button to draw a polyline
        "drawRectangle" => true, // adds button to draw a rectangle
        "drawPolygon" => true, // adds button to draw a polygon
        "drawCircle" => true, // adds button to draw a cricle
        "cutPolygon" => true, // adds button to cut a hole in a polygon
        "editMode" => true, // adds button to toggle edit mode for all layers
        "removalMode" => true, // adds a button to remove layers
    ],
];
