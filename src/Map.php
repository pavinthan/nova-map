<?php

namespace Pavinthan\NovaMap;

use Laravel\Nova\Fields\Field;

class Map extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'map-field';

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = false;

    /**
     * Indicates the element height.
     *
     * @var int
     */
    public $height = 500;

    /**
     * Indicates the element zoom.
     *
     * @var int
     */
    public $zoom = 8;

    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->withMeta([
            'height' => $this->height,
            'zoom' => $this->zoom,
        ]);
    }

    public function height($height)
    {
        if ($height) {
            return $this->withMeta([
                'height' => $height
            ]);
        }
    }

    public function zoom($zoom)
    {
        if ($zoom) {
            return $this->withMeta([
                'zoom' => $zoom
            ]);
        }
    }

    public function spatialType($type)
    {
        return $this->withMeta([
            'spatialType' => $type
        ]);
    }

    public function latitude($latitude_field)
    {
        $this->attribute = null;

        return $this->withMeta([
            'latitude_field' => $latitude_field
        ]);
    }

    public function longitude($longitude_field)
    {
        $this->attribute = null;

        return $this->withMeta([
            'longitude_field' => $longitude_field
        ]);
    }

    public function geoJson($geoJsonField)
    {
        $this->attribute = $geoJsonField;

        return $this->withMeta([
            'geoJsonField' => $geoJsonField
        ]);
    }

    public function resolveAttribute($resource, $attribute = null)
    {
        switch ($this->meta['spatialType'] ?? 'Polygon') {
            case 'LatLon':
                return [
                    'lat' => $resource->{$this->meta['latitude_field']},
                    'lon' => $resource->{$this->meta['longitude_field']},
                ];
            case 'LatLonField':
                $parts = collect(explode(',', $resource->{$attribute}))->map(function ($item) {
                    return trim($item);
                });
                return [
                    'lat' => $parts[0],
                    'lon' => $parts[1],
                ];
            case 'GeoJSON':
            default:
                return $resource->{$attribute};
        }
    }
}
