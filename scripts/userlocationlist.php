<?php
/*
 * DataTables server-side processing script
 * Table : tbl_user_location_access
 */

$table = 'tbl_user_location_access';
$primaryKey = 'idtbl_user_location_access';

/*
 |-------------------------------------------------------
 | Columns
 |-------------------------------------------------------
 | dt = DataTables column name
 | db = SQL column
 */
$columns = array(

    array(
        'db'    => '`ula`.`idtbl_user_location_access`',
        'dt'    => 'id',
        'field' => 'idtbl_user_location_access'
    ),

    array(
        'db'    => '`u`.`name`',
        'dt'    => 'user',
        'field' => 'name'
    ),

    array(
        'db'    => '`c`.`company`',
        'dt'    => 'company',
        'field' => 'company'
    ),

array(
    'db'    => 'IFNULL(`b`.`branch`, "Head Office") AS branch',
    'dt'    => 'branch',
    'field' => 'branch'
),

array(
    'db'    => '`ula`.`status` AS ula_status',
    'dt'    => 'status',
    'field' => 'ula_status'
)

);

/*
 |-------------------------------------------------------
 | DB Connection
 |-------------------------------------------------------
 */
require('config.php');

$sql_details = array(
    'user' => $db_username,
    'pass' => $db_password,
    'db'   => $db_name,
    'host' => $db_host
);

/*
 |-------------------------------------------------------
 | Server-side class
 |-------------------------------------------------------
 */
require('ssp.customized.class.php');

/*
 |-------------------------------------------------------
 | JOIN QUERY
 |-------------------------------------------------------
 */
$joinQuery = "
FROM `tbl_user_location_access` AS `ula`
JOIN `tbl_res_user` AS `u`
    ON `u`.`idtbl_res_user` = `ula`.`tbl_res_user_idtbl_res_user`
JOIN `tbl_company` AS `c`
    ON `c`.`idtbl_company` = `ula`.`tbl_company_idtbl_company`
LEFT JOIN `tbl_company_branch` AS `b`
    ON `b`.`idtbl_company_branch` = `ula`.`tbl_company_branch_idtbl_company_branch`
";

/*
 |-------------------------------------------------------
 | WHERE CONDITION
 |-------------------------------------------------------
 | 1 = Active
 | 0 = Inactive
 | 3 = Deleted (ignored)
 */
$extraWhere = "`ula`.`status` IN (1,2)";

/*
 |-------------------------------------------------------
 | OUTPUT
 |-------------------------------------------------------
 */
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
