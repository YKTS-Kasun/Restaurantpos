<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

$table = 'tbl_stock_transfer';
$primaryKey = 'idtbl_stock_transfer';

/* ==============================
   COLUMNS
============================== */
$columns = array(
    array(
        'db'    => 'st.idtbl_stock_transfer',
        'dt'    => 'idtbl_stock_transfer',
        'field' => 'idtbl_stock_transfer'
    ),
    array(
        'db'    => 'st.transfer_date',
        'dt'    => 'transfer_date',
        'field' => 'transfer_date'
    ),
    array(
        'db'    => 'st.transfer_no',
        'dt'    => 'transfer_no',
        'field' => 'transfer_no'
    ),

    // ✅ FROM (IMPORTANT: UNIQUE FIELD NAME)
    array(
        'db'    => 'lf.location_name AS from_loc',
        'dt'    => 'from_loc',
        'field' => 'from_loc'
    ),

    // ✅ TO (IMPORTANT: UNIQUE FIELD NAME)
    array(
        'db'    => 'lt.location_name AS to_loc',
        'dt'    => 'to_loc',
        'field' => 'to_loc'
    ),

    array(
        'db'    => 'st.status',
        'dt'    => 'status',
        'field' => 'status'
    )
);


require('config.php');

$sql_details = array(
    'user' => $db_username,
    'pass' => $db_password,
    'db'   => $db_name,
    'host' => $db_host
);

require('ssp.customized.class.php');

/* ==============================
   SESSION FILTER
============================== */
$user_location = $_SESSION['location_id'] ?? 0;

/* ==============================
   JOIN QUERY (IMPORTANT)
============================== */
$joinQuery = "
FROM tbl_stock_transfer AS st
LEFT JOIN tbl_location AS lf
    ON lf.idtbl_location = st.from_location_id
LEFT JOIN tbl_location AS lt
    ON lt.idtbl_location = st.to_location_id
";

/* ==============================
   WHERE
============================== */
$extraWhere = "st.status IN ('PENDING','APPROVED','REJECTED')";

/* Branch login → only own transfers */
if ($user_location > 0) {
    $extraWhere .= " AND st.from_location_id = {$user_location}";
}

/* ==============================
   OUTPUT
============================== */
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
