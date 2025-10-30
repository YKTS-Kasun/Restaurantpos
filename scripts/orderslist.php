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
$table = 'tbl_res_order';

// Table's primary key
$primaryKey = 'idtbl_res_order';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_res_order`', 'dt' => 'idtbl_res_order', 'field' => 'idtbl_res_order' ),
	array( 'db' => '`u`.`orderdate`', 'dt' => 'orderdate', 'field' => 'orderdate' ),
	array( 'db' => '`u`.`total`', 'dt' => 'total', 'field' => 'total' ),
	array( 'db' => '`u`.`discount`', 'dt' => 'discount', 'field' => 'discount' ),
	array( 'db' => '`u`.`nettotal`', 'dt' => 'nettotal', 'field' => 'nettotal' ),
	array( 'db' => '`ua`.`firstname`', 'dt' => 'firstname', 'field' => 'firstname' ),
	array( 'db' => '`ua`.`lastname`', 'dt' => 'lastname', 'field' => 'lastname' ),
	array( 'db' => '`ub`.`idtbl_res_kot`', 'dt' => 'idtbl_res_kot', 'field' => 'idtbl_res_kot' ),
	array( 'db' => '`ub`.`processstatus`', 'dt' => 'processstatus', 'field' => 'processstatus' ),
	array( 'db' => '`ub`.`startdatetime`', 'dt' => 'startdatetime', 'field' => 'startdatetime' ),
	array( 'db' => '`ub`.`enddatetime`', 'dt' => 'enddatetime', 'field' => 'enddatetime' ),
	array( 'db' => '`u`.`paystatus`', 'dt' => 'paystatus', 'field' => 'paystatus' ),
	array( 'db' => '`u`.`acceptstatus`', 'dt' => 'acceptstatus', 'field' => 'acceptstatus' ),
	array( 'db' => '`u`.`status`', 'dt' => 'status', 'field' => 'status' )
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

$joinQuery = "FROM `tbl_res_order` AS `u` LEFT JOIN `tbl_res_customer` AS `ua` ON (`ua`.`idtbl_res_customer` = `u`.`tbl_res_customer_idtbl_res_customer`) LEFT JOIN `tbl_res_kot` AS `ub` ON (`ub`.`orderid` = `u`.`idtbl_res_order`)";
$extraWhere = "`u`.`status` IN (1, 2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns,$joinQuery, $extraWhere)
);
