<?php

// =======================
// DB table
// =======================
$table = 'tbl_res_user';

// Primary key
$primaryKey = 'idtbl_res_user';

// =======================
// Columns for DataTables
// =======================
$columns = array(
    array(
        'db' => '`u`.`idtbl_res_user`',
        'dt' => 'idtbl_user',
        'field' => 'idtbl_res_user'
    ),
    array(
        'db' => '`u`.`name`',
        'dt' => 'name',
        'field' => 'name'
    ),
    array(
        'db' => '`u`.`username`',
        'dt' => 'username',
        'field' => 'username'
    ),
    array(
        'db' => '`ut`.`usertype`',
        'dt' => 'type',
        'field' => 'usertype'
    ),
    array(
        'db' => '`l`.`location_name`',
        'dt' => 'location',
        'field' => 'location_name'
    ),
    array(
        'db' => '`u`.`status`',
        'dt' => 'status',
        'field' => 'status'
    )
);

// =======================
// DB connection
// =======================
require('config.php');

$sql_details = array(
    'user' => $db_username,
    'pass' => $db_password,
    'db'   => $db_name,
    'host' => $db_host
);

// =======================
// SSP class
// =======================
require('ssp.customized.class.php');

// =======================
// JOINs
// =======================
$joinQuery = "
FROM `tbl_res_user` AS `u`
JOIN `tbl_res_user_type` AS `ut`
    ON `ut`.`idtbl_res_user_type` = `u`.`tbl_res_user_type_idtbl_res_user_type`
LEFT JOIN `tbl_location` AS `l`
    ON `l`.`idtbl_location` = `u`.`idtbl_location`
";

// =======================
// WHERE condition
// =======================
if (isset($_POST['userID']) && $_POST['userID'] == 1) {
    $extraWhere = "`u`.`status` IN (1,2)";
} else {
    $extraWhere = "`u`.`status` IN (1,2) AND `u`.`idtbl_res_user` > 1";
}

// =======================
// OUTPUT
// =======================
echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
