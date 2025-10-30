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
$table = 'tbl_res_customer';

// Table's primary key
$primaryKey = 'idtbl_res_customer';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_res_customer`', 'dt' => 'idtbl_res_customer', 'field' => 'idtbl_res_customer' ),
	array( 'db' => '`u`.`firstname`', 'dt' => 'firstname', 'field' => 'firstname' ),
	array( 'db' => '`u`.`lastname`', 'dt' => 'lastname', 'field' => 'lastname' ),
	array( 'db' => '`u`.`contact`', 'dt' => 'contact', 'field' => 'contact' ),
	array( 'db' => '`u`.`email`', 'dt' => 'email', 'field' => 'email' ),
	array( 'db' => '`u`.`address1`', 'dt' => 'address1', 'field' => 'address1' ),
	array( 'db' => '`u`.`address2`', 'dt' => 'address2', 'field' => 'address2' ),
	array( 'db' => '`u`.`city`', 'dt' => 'city', 'field' => 'city' ),
	array( 'db' => '`u`.`remark`', 'dt' => 'remark', 'field' => 'remark' ),
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

$joinQuery = "FROM `tbl_res_customer` AS `u` ";
$extraWhere = "`u`.`status` IN (1, 2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns,$joinQuery, $extraWhere)
);
