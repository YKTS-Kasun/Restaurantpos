<?php

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'tbl_waiter_order_detail';

// Table's primary key
$primaryKey = 'idtbl_waiter_order_detail';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_waiter_order_detail`', 'dt' => 'idtbl_waiter_order_detail', 'field' => 'idtbl_waiter_order_detail' ),
    array( 'db' => '`ua`.`date`', 'dt' => 'date', 'field' => 'date' ),
	array( 'db' => '`ub`.`name`', 'dt' => 'name', 'field' => 'name' ),
	array( 'db' => '`uc`.`itemname`', 'dt' => 'itemname', 'field' => 'itemname' ),
	array( 'db' => '`u`.`cancelreason`', 'dt' => 'cancelreason', 'field' => 'cancelreason' )

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

$joinQuery = "FROM `tbl_waiter_order_detail` AS `u` LEFT JOIN `tbl_waiter_order` AS `ua` ON(`u`.`tbl_waiter_order_idtbl_waiter_order` = `ua`.`idtbl_waiter_order`) LEFT JOIN `tbl_res_user` AS `ub` ON(`ua`.`tbl_res_user_idtbl_res_user` = `ub`.`idtbl_res_user`) LEFT JOIN `tbl_res_item` AS `uc` ON(`uc`.`idtbl_res_item` = `u`.`tbl_res_item_idtbl_res_item`)";
$extraWhere = "`u`.`status` IN (3)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns,$joinQuery, $extraWhere)
);
