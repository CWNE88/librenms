<?php
$name = 'powerwall';
$app_id = $app['app_id'];
$unit_text     = 'Energy (Wh)';
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
        'descr'    => 'Solar Produced',
        'ds'       => 'solar_exported',

    );
    $rrd_list[]=array(
        'colour' => '0066ff',
        'filename' => $rrd_filename,
        'descr'    => 'House Consumed',
        'ds'       => 'load_imported',
    );
    $rrd_list[]=array(
        'colour' => '2B9220',
        'filename' => $rrd_filename,
        'descr'    => 'Grid Exported',
        'ds'       => 'grid_exported',
    );

    $rrd_list[]=array(
        'colour' => '888888',
        'filename' => $rrd_filename,
        'descr'    => 'Grid Imported',
        'ds'       => 'grid_imported',

    );
} else {
    d_echo('RRD "'.$rrd_filename.'" not found');
}

require 'includes/graphs/generic_multi_line.inc.php';

