<?php

// =======================
// DB table
// =======================
$table = 'tbl_company';
$primaryKey = 'idtbl_company';

// =======================
// Columns (MATCH JS)
// =======================
$columns = array(

    array(
        'db'    => '`c`.`idtbl_company`',
        'dt'    => 'idtbl_company',
        'field' => 'idtbl_company'
    ),

    array(
        'db'    => '`c`.`company`',
        'dt'    => 'company',
        'field' => 'company'
    ),

    array(
        'db'    => '`c`.`code`',
        'dt'    => 'code',
        'field' => 'code'
    ),

    array(
        'db'    => '`c`.`status`',
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
FROM `tbl_company` AS `c`
";

// =======================
// WHERE
// =======================
$extraWhere = "`c`.`status` IN (1,2)";

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
