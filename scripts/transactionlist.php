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
$table = 'tbl_res_transaction';

// Table's primary key
$primaryKey = 'idtbl_res_transaction';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_res_transaction`', 'dt' => 'idtbl_res_transaction', 'field' => 'idtbl_res_transaction' ),
	array( 'db' => '`u`.`subtotal`', 'dt' => 'subtotal', 'field' => 'subtotal' ),
	array( 'db' => '`u`.`discount`', 'dt' => 'discount', 'field' => 'discount' ),
	array( 'db' => '`u`.`nettotal`', 'dt' => 'nettotal', 'field' => 'nettotal' ),
	array( 'db' => '`u`.`paycomplete`', 'dt' => 'paycomplete', 'field' => 'paycomplete' )
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

$joinQuery = "FROM `tbl_res_transaction` AS `u` ";

	$rpttype = $_POST['search_report_type'];

	if($rpttype == 1){
        $extraWhere = "`u`.`status` IN (1, 2) AND `u`.`orderid` != 0  ";
	}elseif($rpttype == 2){
        $extraWhere = "`u`.`status` IN (1, 2) AND `u`.`reservationid` != 0 ";

	}else{
		
		$extraWhere = "`u`.`status` IN (1, 2)";
	}



echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns,$joinQuery, $extraWhere)
);
