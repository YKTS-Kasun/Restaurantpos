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
$table = 'tbl_res_kot_detail';

// Table's primary key
$primaryKey = 'idtbl_res_kot_detail';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_res_kot_detail`', 'dt' => 'idtbl_res_kot_detail', 'field' => 'idtbl_res_kot_detail' ),
	array( 'db' => '`u`.`qty`', 'dt' => 'qty', 'field' => 'qty' ),
	array( 'db' => '`u`.`notes`', 'dt' => 'notes', 'field' => 'notes' ),
	array( 'db' => '`u`.`startdatetime`', 'dt' => 'startdatetime', 'field' => 'startdatetime' ),
	array( 'db' => '`u`.`enddatetime`', 'dt' => 'enddatetime', 'field' => 'enddatetime' ),
	array( 'db' => '`u`.`completestatus`', 'dt' => 'completestatus', 'field' => 'completestatus' ),
	array( 'db' => '`ua`.`date`', 'dt' => 'date', 'field' => 'date' ),
	array( 'db' => '`ua`.`tableid`', 'dt' => 'tableid', 'field' => 'tableid' ),
	array( 'db' => '`ua`.`orderid`', 'dt' => 'orderid', 'field' => 'orderid' ),
	array( 'db' => '`ub`.`itemname`', 'dt' => 'itemname', 'field' => 'itemname' ),
	array( 'db' => '`uc`.`table`', 'dt' => 'table', 'field' => 'table' ),
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

$joinQuery = "FROM `tbl_res_kot_detail` AS `u` LEFT JOIN `tbl_res_kot` AS `ua` ON (`ua`.`idtbl_res_kot` = `u`.`tbl_res_kot_idtbl_res_kot`) LEFT JOIN `tbl_res_item` AS `ub` ON (`ub`.`idtbl_res_item` = `u`.`tbl_res_item_idtbl_res_item`) LEFT JOIN `tbl_res_reservation_table` AS `uc` ON (`uc`.`idtbl_res_reservation_table` = `ua`.`tableid`)";
$extraWhere = "`u`.`status` IN (1, 2) AND `ua`.`tableid` !=0 AND `ua`.`orderid`=0 AND `u`.`completestatus`=0";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns,$joinQuery, $extraWhere)
);
