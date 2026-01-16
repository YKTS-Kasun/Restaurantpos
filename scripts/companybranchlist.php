<?php

// =======================
// DB table
// =======================
$table = 'tbl_company_branch';
$primaryKey = 'idtbl_company_branch';

// =======================
// Columns (MUST MATCH JS)
// =======================
$columns = array(

    array(
        'db'    => '`b`.`idtbl_company_branch`',
        'dt'    => 'idtbl_company_branch',
        'field' => 'idtbl_company_branch'
    ),

    array(
        'db'    => '`c`.`company`',
        'dt'    => 'company',
        'field' => 'company'
    ),

    array(
        'db'    => '`b`.`branch`',
        'dt'    => 'branch',
        'field' => 'branch'
    ),

    array(
        'db'    => '`b`.`code`',
        'dt'    => 'code',
        'field' => 'code'
    ),

    array(
        'db'    => '`b`.`status`',
        'dt'    => 'status',
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
// SSP
// =======================
require('ssp.customized.class.php');

// =======================
// JOIN
// =======================
$joinQuery = "
FROM `tbl_company_branch` AS `b`
JOIN `tbl_company` AS `c`
    ON `c`.`idtbl_company` = `b`.`tbl_company_idtbl_company`
";

// =======================
// WHERE
// =======================
$extraWhere = "`b`.`status` IN (1,2)";

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
