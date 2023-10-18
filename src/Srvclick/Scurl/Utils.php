<?php
namespace Srvclick\Scurl;
class Utils{
    public function generateRandomPoint($centre, $radius) {
        $radius_earth = 3959;
        $distance = lcg_value()*$radius;
        $centre_rads = array_map( 'deg2rad', $centre );
        $lat_rads = (pi()/2) -  $distance/$radius_earth;
        $lng_rads = lcg_value()*2*pi();
        $x1 = cos( $lat_rads ) * sin( $lng_rads );
        $y1 = cos( $lat_rads ) * cos( $lng_rads );
        $z1 = sin( $lat_rads );
        $rot = (pi()/2) - $centre_rads[0];
        $x2 = $x1;
        $y2 = $y1 * cos( $rot ) + $z1 * sin( $rot );
        $z2 = -$y1 * sin( $rot ) + $z1 * cos( $rot );
        $rot = $centre_rads[1];
        $x3 = $x2 * cos( $rot ) + $y2 * sin( $rot );
        $y3 = -$x2 * sin( $rot ) + $y2 * cos( $rot );
        $z3 = $z2;
        $lng_rads = atan2( $x3, $y3 );
        $lat_rads = asin( $z3 );
        return array_map( 'rad2deg', array( $lat_rads, $lng_rads ) );
    }

}