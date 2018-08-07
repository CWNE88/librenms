<?php
use LibreNMS\RRD\RrdDefinition;

# This is the name that will appear in .Apps. for the host. It will also be the RRD file name
$name = 'powerwall';

$app_id = $app['app_id'];
$options = '-O qv';
$mib = 'NET-SNMP-EXTEND-MIB';

# This is the OID that corresponds to the extend command in snmpd, found with snmptranslate
$oid = '.1.3.6.1.4.1.8072.1.3.2.4.1.2.9.112.111.119.101.114.119.97.108.108';

$powerwall = snmp_walk($device, $oid, $options, $mib);

list(

$battery_charge,

$solar_power,
$solar_reactive,
$solar_apparent,
$solar_voltage,
$solar_current,
$solar_frequency,
$solar_exported,
$solar_imported,

$load_power,
$load_reactive,
$load_apparent,
$load_voltage,
$load_current,
$load_frequency,
$load_exported,
$load_imported,

$battery_power,
$battery_reactive,
$battery_apparent,
$battery_voltage,
$battery_current,
$battery_frequency,
$battery_exported,
$battery_imported,

$grid_power,
$grid_reactive,
$grid_apparent,
$grid_voltage,
$grid_current,
$grid_frequency,
$grid_exported,
$grid_imported,

) = explode("\n", $powerwall);

$rrd_name = array(
    'app',
    $name,
    $app_id
);

$rrd_def = RrdDefinition::make()


    ->addDataset('battery_charge', 'GAUGE' )

    ->addDataset('solar_power', 'GAUGE' )
    ->addDataset('solar_reactive', 'GAUGE' )
    ->addDataset('solar_apparent', 'GAUGE' )
    ->addDataset('solar_voltage', 'GAUGE' )
    ->addDataset('solar_current', 'GAUGE' )
    ->addDataset('solar_frequency', 'GAUGE' )
    ->addDataset('solar_exported', 'GAUGE' )
    ->addDataset('solar_imported', 'GAUGE' )

    ->addDataset('load_power', 'GAUGE' )
    ->addDataset('load_reactive', 'GAUGE' )
    ->addDataset('load_apparent', 'GAUGE' )
    ->addDataset('load_voltage', 'GAUGE' )
    ->addDataset('load_current', 'GAUGE' )
    ->addDataset('load_frequency', 'GAUGE' )
    ->addDataset('load_exported', 'GAUGE' )
    ->addDataset('load_imported', 'GAUGE' )

    ->addDataset('battery_power', 'GAUGE' )
    ->addDataset('battery_reactive', 'GAUGE' )
    ->addDataset('battery_apparent', 'GAUGE' )
    ->addDataset('battery_voltage', 'GAUGE' )
    ->addDataset('battery_current', 'GAUGE' )
    ->addDataset('battery_frequency', 'GAUGE' )
    ->addDataset('battery_exported', 'GAUGE' )
    ->addDataset('battery_imported', 'GAUGE' )

    ->addDataset('grid_power', 'GAUGE' )
    ->addDataset('grid_reactive', 'GAUGE' )
    ->addDataset('grid_apparent', 'GAUGE' )
    ->addDataset('grid_voltage', 'GAUGE' )
    ->addDataset('grid_current', 'GAUGE' )
    ->addDataset('grid_frequency', 'GAUGE' )
    ->addDataset('grid_exported', 'GAUGE' )
    ->addDataset('grid_imported', 'GAUGE' )

;


$fields = array(

        'battery_charge' => $battery_charge,

        'solar_power' => $solar_power,
        'solar_reactive' => $solar_reactive,
        'solar_apparent' => $solar_apparent,
        'solar_voltage' => $solar_voltage,
        'solar_current' => $solar_current,
        'solar_frequency' => $solar_frequency,
        'solar_exported' => $solar_exported,
        'solar_imported' => $solar_imported,

        'load_power' => $load_power,
        'load_reactive' => $load_reactive,
        'load_apparent' => $load_apparent,
        'load_voltage' => $load_voltage,
        'load_current' => $load_current,
        'load_frequency' => $load_frequency,
        'load_exported' => $load_exported,
        'load_imported' => $load_imported,

        'battery_power' => $battery_power,
        'battery_reactive' => $battery_reactive,
        'battery_apparent' => $battery_apparent,
        'battery_voltage' => $battery_voltage,
        'battery_current' => $battery_current,
        'battery_frequency' => $battery_frequency,
        'battery_exported' => $battery_exported,
        'battery_imported' => $battery_imported,

        'grid_power' => $grid_power,
        'grid_reactive' => $grid_reactive,
        'grid_apparent' => $grid_apparent,
        'grid_voltage' => $grid_voltage,
        'grid_current' => $grid_current,
        'grid_frequency' => $grid_frequency,
        'grid_exported' => $grid_exported,
        'grid_imported' => $grid_imported,

);

$tags = array(
    'name' => $name,
    'app_id' => $app_id,
    'rrd_def' => $rrd_def,
    'rrd_name' => $rrd_name
);
data_update($device, 'app', $tags, $fields);
update_application($app, $powerwall, $fields);

