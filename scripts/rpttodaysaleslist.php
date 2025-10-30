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
$table = 'tbl_invoice';

// Table's primary key
$primaryKey = 'idtbl_invoice';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_invoice`', 'dt' => 'idtbl_invoice', 'field' => 'idtbl_invoice' ),
	array( 'db' => '`ua`.`itemname`', 'dt' => 'itemname', 'field' => 'itemname' ),
	array( 'db' => '`u`.`invdate`', 'dt' => 'invdate', 'field' => 'invdate' ),
	array( 'db' => '`u`.`discount`', 'dt' => 'discount', 'field' => 'discount' ),
	array( 'db' => '`u`.`grosstotal`', 'dt' => 'grosstotal', 'field' => 'grosstotal' ),
    array( 'db' => '`u`.`nettotal`', 'dt' => 'nettotal', 'field' => 'nettotal' ),
	array( 'db' => '`u`.`orderid`', 'dt' => 'orderid', 'field' => 'orderid' ),
	array( 'db' => '`u`.`tableid`', 'dt' => 'tableid', 'field' => 'tableid' )

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

$joinQuery = "FROM `tbl_invoice` AS `u` LEFT JOIN `tbl_res_item` AS `ua` ON(`u`.`idtbl_invoice` = `ua`.`idtbl_res_item`)";
$extraWhere = "`u`.`status` IN (1, 2) AND DATE(NOW()) = `u`.`invdate`";



echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns,$joinQuery, $extraWhere)
);
