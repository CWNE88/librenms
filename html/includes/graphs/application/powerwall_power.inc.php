<?php
$name = 'powerwall';
$app_id = $app['app_id'];
$unit_text     = 'Power (W)';
$colours       = 'psychedelic';
#$scale_min     = -6000;
#$scale_max     = 6000;
$dostack       = 0;
$printtotal    = 0;
$addarea       = 1;
$transparency  = 15;

$rrd_filename = rrd_name($device['hostname'], array('app', 'powerwall', $app['app_id'],));

$rrd_list=array();
if (rrdtool_check_rrd_exists($rrd_filename)) {
    $rrd_list[]=array(
        'colour' => 'ffcc00',
        'filename' => $rrd_filename,
        'descr'    => 'Solar',
        'ds'       => 'solar_power',

    );
    $rrd_list[]=array(
        'colour' => '0066ff',
        'filename' => $rrd_filename,
        'descr'    => 'House',
        'ds'       => 'load_power',
    );
    $rrd_list[]=array(
        'colour' => 'ff0000',
        'filename' => $rrd_filename,
        'descr'    => 'Battery',
        'ds'       => 'battery_power',
    );
    $rrd_list[]=array(
        'colour' => '888888',
        'filename' => $rrd_filename,
        'descr'    => 'Grid',
        'ds'       => 'grid_power',
    );

} else {
    d_echo('RRD "'.$rrd_filename.'" not found');
}

require 'includes/graphs/generic_multi_line.inc.php';

