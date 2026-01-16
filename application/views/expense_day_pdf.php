<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px;
        }
        th {
            background: #f5f5f5;
        }
        h2 {
            text-align: center;
            margin-bottom: 2px;
        }
        .sub {
            text-align: center;
            font-size: 11px;
            color: #555;
        }
        .total-row {
            background: #e8e8e8;
            font-weight: bold;
        }
        .badge {
            font-size: 10px;
            padding: 2px 5px;
            border-radius: 3px;
            color: #fff;
        }
        .badge-ho {
            background: #007bff;
        }
        .badge-branch {
            background: #6c757d;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>

    <h2>Expense Report</h2>
    <div class="sub">Date : <?= $date ?></div>

    <table>
        <thead>
            <tr>
                <th>Location</th>
                <th>Category</th>
                <th>Description</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>

        <tbody>
            <?php $total = 0; ?>
            <?php foreach ($items as $i): 
                $total += $i->amount;
            ?>
            <tr>
                <td>
                    <?= $i->location_name ?>
                    <?php if ($i->location_type === 'HO'): ?>
                        <span class="badge badge-ho">HO</span>
                    <?php else: ?>
                        <span class="badge badge-branch">Branch</span>
                    <?php endif; ?>
                </td>
                <td><?= $i->category ?></td>
                <td><?= $i->description ?></td>
                <td class="text-right"><?= number_format($i->amount, 2) ?></td>
            </tr>
            <?php endforeach; ?>

            <tr class="total-row">
                <td colspan="3" class="text-right">TOTAL</td>
                <td class="text-right"><?= number_format($total, 2) ?></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
