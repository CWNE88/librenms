<?php
require 'includes/graphs/common.inc.php';
$name = 'powerwall';
$app_id = $app['app_id'];
$scale_min     = 0;
$scale_max     = 100;
$colours       = 'mixed';
$unit_text     = 'Battery Charge';
$unitlen       = 18;
$bigdescrlen   = 18;
$smalldescrlen = 18;
$dostack       = 0;
$printtotal    = 0;
$addarea       = 1;
$transparency  = 33;

$rrd_filename = rrd_name($device['hostname'], array('app', $name, $app_id));

$array = array(
    'battery_charge' => array('descr' => 'Percent','colour' => '2B9220',),
);

$i = 0;
if (rrdtool_check_rrd_exists($rrd_filename)) {
    foreach ($array as $ds => $var) {
        $rrd_list[$i]['filename'] = $rrd_filename;
        $rrd_list[$i]['descr']    = $var['descr'];
        $rrd_list[$i]['ds']       = $ds;
        $rrd_list[$i]['colour']   = $var['colour'];
        $i++;
    }
} else {
    echo "file missing: $rrd_filename";
}

require 'includes/graphs/generic_v3_multiline.inc.php';

