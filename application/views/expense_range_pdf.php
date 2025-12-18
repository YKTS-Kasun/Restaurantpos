<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size:12px; }
        table { width:100%; border-collapse: collapse; margin-top:10px; }
        table, th, td { border:1px solid #444; padding:6px; }
        th { background:#eee; }
    </style>
</head>
<body>

<h2>Expense Report (<?= $from ?> to <?= $to ?>)</h2>
<?php if($cat != ""): ?>
    <h4>Category: <?= $cat ?></h4>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Category</th>
            <th>Description</th>
            <th style="text-align:right;">Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $total = 0;
        foreach($items as $i):
            $total += $i->amount;
        ?>
        <tr>
            <td><?= $i->expdate ?></td>
            <td><?= $i->category ?></td>
            <td><?= $i->description ?></td>
            <td style="text-align:right;"><?= number_format($i->amount,2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>

    <tfoot>
        <tr>
            <th colspan="3" style="text-align:right;">TOTAL</th>
            <th style="text-align:right;"><?= number_format($total,2) ?></th>
        </tr>
    </tfoot>
</table>

</body>
</html>
