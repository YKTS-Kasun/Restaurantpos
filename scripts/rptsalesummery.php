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
	array( 'db' => '`dmain`.`invdate`', 'dt' => 'invdate', 'field' => 'invdate' ),
	array( 'db' => '`dmain`.`totalsum`', 'dt' => 'totalsum', 'field' => 'totalsum' ),
	array( 'db' => '`dcash`.`cashtotal`', 'dt' => 'cashtotal', 'field' => 'cashtotal' ),
	array( 'db' => '`dcredit`.`credittotal`', 'dt' => 'credittotal', 'field' => 'credittotal' ),
	array( 'db' => '`duber`.`ubertotal`', 'dt' => 'ubertotal', 'field' => 'ubertotal' ),
	array( 'db' => '`dpickme`.`pickmetotal`', 'dt' => 'pickmetotal', 'field' => 'pickmetotal' ),
	array( 'db' => '`dfree`.`freetotal`', 'dt' => 'freetotal', 'field' => 'freetotal' )
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
 
// $cashier=$_POST['cashier'];
$formdate=$_POST['formdate'];
$todate=$_POST['todate'];

$joinQuery = " FROM (SELECT SUM(IFNULL(`nettotal`, 0)) AS `totalsum`, `invdate` FROM `tbl_invoice` WHERE `status`=1 AND `invdate` BETWEEN '$formdate' AND '$todate' GROUP BY `invdate`) AS `dmain`LEFT JOIN (SELECT SUM(IFNULL(`nettotal`, 0)) AS `cashtotal`, `invdate` FROM `tbl_invoice` WHERE `status`=1 AND `invtype`=1 AND `invdate` BETWEEN '$formdate' AND '$todate' GROUP BY `invdate`) AS `dcash` ON `dcash`.`invdate`=`dmain`.`invdate`LEFT JOIN (SELECT SUM(IFNULL(`nettotal`, 0)) AS `credittotal`, `invdate` FROM `tbl_invoice` WHERE `status`=1 AND `invtype`=2 AND `invdate` BETWEEN '$formdate' AND '$todate' GROUP BY `invdate`) AS `dcredit` ON `dcredit`.`invdate`=`dmain`.`invdate` LEFT JOIN (SELECT SUM(IFNULL(`grosstotal`, 0)) AS `ubertotal`, `invdate` FROM `tbl_invoice` WHERE `status`=1 AND `invtype`=4 AND `invdate` BETWEEN '$formdate' AND '$todate' GROUP BY `invdate`) AS `duber` ON `duber`.`invdate`=`dmain`.`invdate` LEFT JOIN (SELECT SUM(IFNULL(`grosstotal`, 0)) AS `pickmetotal`, `invdate` FROM `tbl_invoice` WHERE `status`=1 AND `invtype`=4 AND `invdate` BETWEEN '$formdate' AND '$todate' GROUP BY `invdate`) AS `dpickme` ON `dpickme`.`invdate`=`dmain`.`invdate` LEFT JOIN (SELECT SUM(IFNULL(`grosstotal`, 0)) AS `freetotal`, `invdate` FROM `tbl_invoice` WHERE `status`=1 AND `invtype`=6 AND `invdate` BETWEEN '$formdate' AND '$todate' GROUP BY `invdate`) AS `dfree` ON `dfree`.`invdate`=`dmain`.`invdate`";

$extraWhere = "";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery,$extraWhere)
);