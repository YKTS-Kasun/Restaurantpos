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
	array( 'db' => '`ua`.`name`', 'dt' => 'name', 'field' => 'name' ),
	array( 'db' => '`u`.`invdate`', 'dt' => 'invdate', 'field' => 'invdate' ),
    array( 'db' => '`u`.`nettotal`', 'dt' => 'nettotal', 'field' => 'nettotal' )  
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

$formdate=$_POST['formdate'];
$todate=$_POST['todate'];

$joinQuery = "FROM `tbl_invoice` AS `u` LEFT JOIN `tbl_res_user` AS `ua` ON(`ua`.`idtbl_res_user` = `u`.`tbl_res_user_idtbl_res_user`)";

$extraWhere = "`u`.`status`=3 AND `u`.`invdate` BETWEEN '$formdate' AND '$todate'";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery,$extraWhere)
);