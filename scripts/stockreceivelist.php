<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

$table = 'tbl_stock_transfer';
$primaryKey = 'idtbl_stock_transfer';

$columns = [
    [
        'db'    => 'st.idtbl_stock_transfer',
        'dt'    => 'idtbl_stock_transfer',
        'field' => 'idtbl_stock_transfer'
    ],
    [
        'db'    => 'st.transfer_date',
        'dt'    => 'transfer_date',
        'field' => 'transfer_date'
    ],
    [
        'db'    => 'st.transfer_no',
        'dt'    => 'transfer_no',
        'field' => 'transfer_no'
    ],

    // 🔥 THIS LINE FIXES "From" COLUMN
    [
        'db'    => 'lf.location_name AS from_loc',
        'dt'    => 'from_loc',
        'field' => 'from_loc'
    ],

    [
        'db'    => 'st.status',
        'dt'    => 'status',
        'field' => 'status'
    ]
];


require('config.php');
require('ssp.customized.class.php');

$sql_details = [
    'user'=>$db_username,
    'pass'=>$db_password,
    'db'=>$db_name,
    'host'=>$db_host
];

$user_location      = (int)($_SESSION['location_id'] ?? 0);
$user_location_type = $_SESSION['location_type'] ?? '';

$joinQuery = "
FROM tbl_stock_transfer st
LEFT JOIN tbl_location lf ON lf.idtbl_location = st.from_location_id
LEFT JOIN tbl_location lt ON lt.idtbl_location = st.to_location_id
";


$extraWhere = "st.status IN ('APPROVED','RECEIVED')";

/* Branch → only received transfers */
if ($user_location_type === 'BRANCH') {
    $extraWhere .= "
        AND st.to_location_type = 'BRANCH'
        AND st.to_location_id = {$user_location}
    ";
}

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
exit;
