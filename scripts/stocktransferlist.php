<?php
error_reporting(0);
ini_set('display_errors', 0);

$table = 'tbl_stock_transfer';
$primaryKey = 'idtbl_stock_transfer';

/* ==============================
   COLUMNS
============================== */
$columns = [
    ['db'=>'st.idtbl_stock_transfer', 'dt'=>'idtbl_stock_transfer', 'field'=>'idtbl_stock_transfer'],
    ['db'=>'st.transfer_date',        'dt'=>'transfer_date',        'field'=>'transfer_date'],
    ['db'=>'st.transfer_no',          'dt'=>'transfer_no',          'field'=>'transfer_no'],
    ['db'=>'cb_from.branch AS from_loc', 'dt'=>'from_loc', 'field'=>'from_loc'],
    ['db'=>'cb_to.branch AS to_loc',     'dt'=>'to_loc',   'field'=>'to_loc'],
    ['db'=>'st.status',               'dt'=>'status',     'field'=>'status'],
];

require('config.php');
require('ssp.customized.class.php');

$sql_details = [
    'user' => $db_username,
    'pass' => $db_password,
    'db'   => $db_name,
    'host' => $db_host
];

/* ==============================
   USER CONTEXT (FROM POST)
============================== */
$user_company_id = isset($_POST['company_id']) ? (int)$_POST['company_id'] : 0;
$user_branch_id  = isset($_POST['branch_id']) && $_POST['branch_id'] !== ''
    ? (int)$_POST['branch_id']
    : null;

/* ==============================
   JOIN QUERY
============================== */
$joinQuery = "
FROM tbl_stock_transfer st
LEFT JOIN tbl_company_branch cb_from
    ON cb_from.idtbl_company_branch = st.from_branch_id
LEFT JOIN tbl_company_branch cb_to
    ON cb_to.idtbl_company_branch   = st.to_branch_id
";

/* ==============================
   BASE WHERE
============================== */
$extraWhere = "
    st.status IN ('PENDING','APPROVED','REJECTED','RECEIVED')
";

/* ==============================
   🔐 ACCESS RULES (FINAL)
============================== */

/**
 * 🏢 HEAD OFFICE USER
 * - branch_id = NULL
 * - Can approve / reject
 * - See ONLY company-origin transfers
 */
if ($user_branch_id === null) {

    if ($user_company_id > 0) {
        $extraWhere .= "
            AND st.from_company_id = {$user_company_id}
        ";
    }
}


/**
 * 🏬 BRANCH USER
 * - See ONLY transfers FROM own branch
 */
else {

    $extraWhere .= "
        AND st.from_branch_id = {$user_branch_id}
    ";
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
exit;
