<?php
session_start();

$table = 'tbl_expense';
$primaryKey = 'idtbl_expense';

if (!isset($_SESSION['idtbl_location'])) {
    die(json_encode([
        "data" => [],
        "error" => "Unauthorized"
    ]));
}

$idtbl_location = (int) $_SESSION['idtbl_location'];

$columns = array(
    array(
        'db'    => 'e.expdate',
        'dt'    => 'expdate',
        'field' => 'expdate'
    ),
    array(
        'db'    => 'c.category',
        'dt'    => 'category',
        'field' => 'category'
    ),
    array(
        'db'    => 'e.description',
        'dt'    => 'description',
        'field' => 'description'
    ),
    array(
        'db'    => 'e.amount',
        'dt'    => 'amount',
        'field' => 'amount'
    ),
    array(
        'db'    => 'e.idtbl_expense',
        'dt'    => 'id',
        'field' => 'idtbl_expense'
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

$joinQuery = "
    FROM tbl_expense AS e
    JOIN tbl_expense_category AS c
      ON c.idtbl_expense_category = e.categoryid
";

$extraWhere = "e.status = 1 AND e.idtbl_location = ".$idtbl_location;

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
