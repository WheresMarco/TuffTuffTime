# TuffTuffTime
WordPress-widget that shows the current and future trainstops at a station.

This documentation is really bad. But I will update it. I promise.

## Functions of the class
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
