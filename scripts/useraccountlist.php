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
        'db' => '`c`.`company`',
        'dt' => 'company',
        'field' => 'company'
    ),
    array(
        'db' => '`b`.`branch`',
        'dt' => 'branch',
        'field' => 'branch'
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
// JOINs (UPDATED)
// =======================
$joinQuery = "
FROM `tbl_res_user` AS `u`
JOIN `tbl_res_user_type` AS `ut`
    ON `ut`.`idtbl_res_user_type` = `u`.`tbl_res_user_type_idtbl_res_user_type`
JOIN `tbl_company` AS `c`
    ON `c`.`idtbl_company` = `u`.`tbl_company_idtbl_company`
LEFT JOIN `tbl_company_branch` AS `b`
    ON `b`.`idtbl_company_branch` = `u`.`tbl_company_branch_idtbl_company_branch`
";

// =======================
// WHERE condition (SECURE)
// =======================
// Super admin (userID = 1) → see all
if (isset($_POST['userID']) && $_POST['userID'] == 1) {
    $extraWhere = "`u`.`status` IN (1,2)";
} else {
    // Normal users → hide super admin
    $extraWhere = "`u`.`status` IN (1,2) AND `u`.`idtbl_res_user` > 1";
}

// =======================
// OUTPUT
// =======================
echo json_encode(
    SSP::simple(
        $_POST,
        $sql_details,
        $table,
        $primaryKey,
        $columns,
        $joinQuery,
        $extraWhere
    )
);
