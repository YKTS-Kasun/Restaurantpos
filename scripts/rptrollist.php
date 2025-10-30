<?php

$table = 'tbl_res_material_info';

// Table's primary key
$primaryKey = 'idtbl_res_material_info';


$columns = array(
	array( 'db' => '`u`.`idtbl_res_material_info`', 'dt' => 'idtbl_res_material_info', 'field' => 'idtbl_res_material_info' ),
	array( 'db' => '`u`.`material`', 'dt' => 'material', 'field' => 'material' ),
	array( 'db' => '`u`.`reorderlevel`', 'dt' => 'reorderlevel', 'field' => 'reorderlevel' ),
	array( 'db' => '`ua`.`qty`', 'dt' => 'qty', 'field' => 'qty' )

);

// SQL server connection information
require('config.php');
$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_host
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );
require('ssp.customized.class.php' );

$joinQuery = "FROM `tbl_res_material_info` AS `u` LEFT JOIN `tbl_stock` AS `ua` ON (`u`.`idtbl_res_material_info` = `ua`.`idtbl_stock`)";

$extraWhere = "`u`.`status` IN (1, 2)";

// Modify SQL query to conditionally include columns based on quantity and reorder level
$extraWhere .= " AND ((`ua`.`qty` <= `u`.`reorderlevel`) OR (`ua`.`qty` IS NULL))";


echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns,$joinQuery, $extraWhere)
);
