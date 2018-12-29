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

    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->withMeta($this->resolveConfigValues());
    }

    public function height(int $height)
    {
        return $this->withMeta([
            'height' => $height
        ]);
    }

    public function latitude(float $latitude)
    {
        return $this->withMeta([
            'latitude' => $latitude
        ]);
    }

    public function longitude(float $longitude)
    {
        return $this->withMeta([
            'longitude' => $longitude
        ]);
    }

    public function zoom(int $zoom)
    {
        return $this->withMeta([
            'zoom' => $zoom
        ]);
    }

    public function controls(array $controls)
    {
        return $this->withMeta([
            'controls' => $controls
        ]);
    }

    private function resolveConfigValues()
    {
        return array_only(config('nova-map'), [
            'controls',
            'height',
            'latitude',
            'longitude',
            'zoom',
        ]);
    }
}
