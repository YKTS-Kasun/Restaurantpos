<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #444;
            padding: 6px;
        }
        th {
            background: #eee;
        }
        h2 {
            text-align: center;
            margin-bottom: 4px;
        }
        h4 {
            margin: 4px 0;
        }
        .sub {
            text-align: center;
            font-size: 11px;
            color: #555;
        }
        .text-right {
            text-align: right;
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
        tfoot tr {
            background: #f0f0f0;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Expense Report</h2>
<div class="sub">From <?= $from ?> to <?= $to ?></div>

<?php if ($cat != ""): ?>
    <h4>Category : <?= $cat ?></h4>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>Date</th>
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
            <td><?= $i->expdate ?></td>
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
    </tbody>

    <tfoot>
        <tr>
            <td colspan="4" class="text-right">TOTAL</td>
            <td class="text-right"><?= number_format($total, 2) ?></td>
        </tr>
    </tfoot>
</table>

</body>
</html>
