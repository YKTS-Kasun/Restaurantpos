<?php
error_reporting(0);
ini_set('display_errors', 0);

$table = 'tbl_stock_transfer';
$primaryKey = 'idtbl_stock_transfer';

/* ==============================
   COLUMNS (ALIAS MUST MATCH!)
============================== */
$columns = [
    ['db'=>'st.idtbl_stock_transfer', 'dt'=>'idtbl_stock_transfer', 'field'=>'idtbl_stock_transfer'],
    ['db'=>'st.transfer_date',        'dt'=>'transfer_date',        'field'=>'transfer_date'],
    ['db'=>'st.transfer_no',          'dt'=>'transfer_no',          'field'=>'transfer_no'],
    ['db'=>'lf.location_name AS from_loc', 'dt'=>'from_loc', 'field'=>'from_loc'],
    ['db'=>'lt.location_name AS to_loc',   'dt'=>'to_loc',   'field'=>'to_loc'],
    ['db'=>'st.status',               'dt'=>'status',               'field'=>'status'],
];

require('config.php');
require('ssp.customized.class.php');

$sql_details = [
    'user'=>$db_username,
    'pass'=>$db_password,
    'db'=>$db_name,
    'host'=>$db_host
];

/* ==============================
   AJAX FILTER (NOT SESSION)
============================== */
$user_location      = isset($_POST['location_id']) ? (int)$_POST['location_id'] : 0;
$user_location_type = $_POST['location_type'] ?? '';

/* ==============================
   JOIN QUERY
============================== */
$joinQuery = "
FROM tbl_stock_transfer st
LEFT JOIN tbl_location lf ON lf.idtbl_location = st.from_location_id
LEFT JOIN tbl_location lt ON lt.idtbl_location = st.to_location_id
";

/* ==============================
   WHERE BASE
============================== */
$extraWhere = "st.status IN ('PENDING','APPROVED','REJECTED')";

/* ==============================
   🔐 ACCESS RULES
============================== */

/**
 * HO USER
 * - Can see ALL transfers
 */
if ($user_location_type === 'HO') {
    // no filter
}

/**
 * BRANCH USER
 * - ONLY transfers CREATED FROM own branch
 */
elseif ($user_location_type === 'BRANCH' && $user_location > 0) {

    $extraWhere .= "
        AND st.from_location_type = 'BRANCH'
        AND st.from_location_id   = {$user_location}
    ";
}


/* HO → no filter */

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
