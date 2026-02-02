<?php
error_reporting(0);
ini_set('display_errors', 0);

/* ==============================
   BASE TABLE
============================== */
$table      = 'tbl_expense';
$primaryKey = 'idtbl_expense';

/* ==============================
   COLUMNS (MUST MATCH JS)
============================== */
$columns = [
    ['db'=>'e.expdate', 'dt'=>'expdate', 'field'=>'expdate'],
    ['db'=>'IFNULL(b.branch,"Head Office")', 'dt'=>'location', 'field'=>'location'],
    ['db'=>'c.category', 'dt'=>'category', 'field'=>'category'],
    ['db'=>'e.categoryid', 'dt'=>'categoryid', 'field'=>'categoryid'],
    ['db'=>'e.description', 'dt'=>'description', 'field'=>'description'],
    ['db'=>'e.amount', 'dt'=>'amount', 'field'=>'amount'],
    ['db'=>'e.idtbl_expense', 'dt'=>'id', 'field'=>'idtbl_expense'],
];

/* ==============================
   DB CONFIG
============================== */
require('config.php');
require('ssp.customized.class.php');

$sql_details = [
    'user' => $db_username,
    'pass' => $db_password,
    'db'   => $db_name,
    'host' => $db_host
];

/* ==============================
   USER CONTEXT (FROM AJAX)
============================== */
$user_company_id = isset($_POST['company_id']) ? (int)$_POST['company_id'] : 0;
$user_branch_id  = ($_POST['branch_id'] !== '') ? (int)$_POST['branch_id'] : null;

/* ==============================
   JOIN QUERY
============================== */
$joinQuery = "
FROM tbl_expense e
JOIN tbl_expense_category c
    ON c.idtbl_expense_category = e.categoryid
LEFT JOIN tbl_company_branch b
    ON b.idtbl_company_branch = e.tbl_company_branch_idtbl_company_branch
";

/* ==============================
   BASE WHERE (SAFE)
============================== */
$extraWhere = "1=1";

$extraWhere .= "
    AND e.status = 1
    AND e.tbl_company_idtbl_company = {$user_company_id}
";

/* ==============================
   ACCESS RULES
============================== */
/**
 * HO USER (branch = NULL)
 * → see ALL branches + HO
 */
if ($user_branch_id !== null) {
    // Branch user → only own branch
    $extraWhere .= "
        AND e.tbl_company_branch_idtbl_company_branch = {$user_branch_id}
    ";
}

/* ==============================
   OPTIONAL FILTERS
============================== */
if (!empty($_POST['date_from'])) {
    $extraWhere .= " AND e.expdate >= '" . $_POST['date_from'] . "'";
}

if (!empty($_POST['date_to'])) {
    $extraWhere .= " AND e.expdate <= '" . $_POST['date_to'] . "'";
}

if (!empty($_POST['category'])) {
    $extraWhere .= " AND e.categoryid = " . (int)$_POST['category'];
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
