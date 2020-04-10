# nova-map
Laravel Nova Map Field
======================

Thanks for the initial work https://github.com/pavinthan

How to install
==============

1. Download package using composer:

    ```
    composer require pavinthan/nova-map
    ```

2. Publish the configuration:
    ```
    php artisan vendor:publish --tag=config --provider=nova-map
    ```

3. Insert the map into your form:

```
<?php

.....
use Pavinthan\NovaMap\Map;

......

    public function fields(Request $request)
    {

        ......
        Map::make('location'),

        ....
    }

```


