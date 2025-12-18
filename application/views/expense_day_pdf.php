<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 6px; }
        th { background: #f5f5f5; }
        h2 { text-align: center; margin-bottom: 5px; }
        .total-row { background: #e8e8e8; font-weight:bold; }
    </style>
</head>
<body>

    <h2>Expense Report - <?= $date ?></h2>

    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Description</th>
                <th style="text-align:right;">Amount</th>
            </tr>
        </thead>

        <tbody>
            <?php $total = 0; ?>
            <?php foreach ($items as $i): 
                $total += $i->amount;
            ?>
            <tr>
                <td><?= $i->category ?></td>
                <td><?= $i->description ?></td>
                <td style="text-align:right;"><?= number_format($i->amount,2) ?></td>
            </tr>
            <?php endforeach; ?>

            <tr class="total-row">
                <td colspan="2" style="text-align:right;">TOTAL</td>
                <td style="text-align:right;"><?= number_format($total,2) ?></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
