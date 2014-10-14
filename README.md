# TuffTuffTime
WordPress-widget that shows the current and future trainstops at a station.

This documentation is really bad. But I will update it. I promise.

## Requirements
* You need to generate a API-key from [Trafiklab](http://www.trafiklab.se/) and
input it into *Settings.php* (rename *Settings-sample.php*).

## Functions of the class / Ex. use
```php
// Require the class
require_once("classes/TuffTuffTime.php");

// Create a new TuffTuffTime-object
$station = "Kalmar C";
$tufftufftime = new \TuffTuffTime\TuffTuffTime($station);

// Functions
$tufftufftime->getDeparting();
$tufftufftime->getArriving();
$tufftufftime->getStationName("Bg");
```

## Todo
* Write real documentation.
* Make WordPress-plugin.
* Move API-key input somewhere else (will probably be in WP plugin settingspage).
* Get timetable for a train (based on train id).
* Get a stations messages - delays, cancellations, etc. 
