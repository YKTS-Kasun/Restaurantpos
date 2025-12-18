<?php

$table = 'tbl_expense';
$primaryKey = 'idtbl_expense';

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

$extraWhere = "e.status = 1";

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
