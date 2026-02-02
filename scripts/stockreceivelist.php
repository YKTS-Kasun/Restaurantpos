<?php
error_reporting(0);
ini_set('display_errors', 0);

$table = 'tbl_stock_transfer';
$primaryKey = 'idtbl_stock_transfer';

$columns = [
    ['db'=>'st.idtbl_stock_transfer','dt'=>'idtbl_stock_transfer','field'=>'idtbl_stock_transfer'],
    ['db'=>'st.transfer_date','dt'=>'transfer_date','field'=>'transfer_date'],
    ['db'=>'st.transfer_no','dt'=>'transfer_no','field'=>'transfer_no'],
    [
        'db'=>"CONCAT(fc.company,' - ',fb.branch) AS from_loc",
        'dt'=>'from_loc',
        'field'=>'from_loc'
    ],
    ['db'=>'st.status','dt'=>'status','field'=>'status']
];

require('config.php');
require('ssp.customized.class.php');

/* ==============================
   GET FROM POST (NOT SESSION)
============================== */
$company_id = isset($_POST['company_id']) ? (int)$_POST['company_id'] : 0;
$branch_id  = isset($_POST['branch_id']) && $_POST['branch_id'] !== ''
    ? (int)$_POST['branch_id']
    : null;

/* ==============================
   JOIN QUERY
============================== */
$joinQuery = "
FROM tbl_stock_transfer st
LEFT JOIN tbl_company fc ON fc.idtbl_company = st.from_company_id
LEFT JOIN tbl_company_branch fb ON fb.idtbl_company_branch = st.from_branch_id
";

/* ==============================
   APPROVED ONLY (PENDING RECEIVE)
============================== */
$extraWhere = "
    st.status IN ('APPROVED','RECEIVED')
    AND st.to_company_id = {$company_id}
";

/* ==============================
   BRANCH FILTER (CRITICAL)
============================== */
if ($branch_id !== null) {
    $extraWhere .= " AND st.to_branch_id = {$branch_id}";
}

echo json_encode(
    SSP::simple(
        $_POST,
        [
            'user'=>$db_username,
            'pass'=>$db_password,
            'db'=>$db_name,
            'host'=>$db_host
        ],
        $table,
        $primaryKey,
        $columns,
        $joinQuery,
        $extraWhere
    )
);
exit;
