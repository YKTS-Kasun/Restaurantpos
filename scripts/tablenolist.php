<?php


$table = 'tbl_table_no';


$primaryKey = 'idtbl_table_no';


$columns = array(
	array( 'db' => '`u`.`idtbl_table_no`', 'dt' => 'idtbl_table_no', 'field' => 'idtbl_table_no' ),
	array( 'db' => '`u`.`tableno`', 'dt' => 'tableno', 'field' => 'tableno' ),
    array( 'db' => '`u`.`status`', 'dt' => 'status', 'field' => 'status' )
);


require('config.php');
$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_host
);


require('ssp.customized.class.php' );

$joinQuery = "FROM `tbl_table_no` AS `u` ";

$extraWhere = "`u`.`status` IN (1, 2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
