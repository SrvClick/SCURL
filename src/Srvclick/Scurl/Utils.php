<?php
namespace Srvclick\Scurl;

trait Utils {

    public function getRange($id, $divider): array
    {
        $amount = ($id + 1) * $divider;
        $start = $id * $divider + 1;
        $end = ($id + 1) * $divider;
        if ($end > $amount) $end = $amount;
        return range($start, $end);
    }

    public function randomCoords($centre, $radius): array
    {
        $radius_earth = 3959;
        $distance = lcg_value() * $radius;
        $centre_rads = array_map('deg2rad', $centre);
        $lat_rads = (pi() / 2) - $distance / $radius_earth;
        $lng_rads = lcg_value() * 2 * pi();
        $x1 = cos($lat_rads) * sin($lng_rads);
        $y1 = cos($lat_rads) * cos($lng_rads);
        $z1 = sin($lat_rads);
        $rot_lat = (pi() / 2) - $centre_rads[0];
        $x2 = $x1;
        $y2 = $y1 * cos($rot_lat) + $z1 * sin($rot_lat);
        $z2 = -$y1 * sin($rot_lat) + $z1 * cos($rot_lat);
        $rot_lng = $centre_rads[1];
        $x3 = $x2 * cos($rot_lng) + $y2 * sin($rot_lng);
        $y3 = -$x2 * sin($rot_lng) + $y2 * cos($rot_lng);
        $z3 = $z2;
        $lng_rads = atan2($x3, $y3);
        $lat_rads = asin($z3);
        return array_map('rad2deg', array($lat_rads, $lng_rads));

    }

}