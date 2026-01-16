<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            font-size: 12px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .no-border td {
            border: none;
            padding: 4px;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .signature {
            margin-top: 40px;
        }
    </style>
</head>

<body>

    <!-- ================= HEADER ================= -->
    <div class="title">DISPATCH REPORT</div>
    <div class="subtitle">Stock Transfer</div>

    <!-- ================= TRANSFER INFO ================= -->
    <table class="no-border">
        <tr>
            <td width="25%"><strong>Transfer No</strong></td>
            <td width="25%">: <?= $header->transfer_no ?></td>

            <td width="25%"><strong>Transfer Date</strong></td>
            <td width="25%">: <?= $header->transfer_date ?></td>
        </tr>

        <tr>
            <td><strong>From Location</strong></td>
            <td>: <?= $header->from_location ?></td>

            <td><strong>To Location</strong></td>
            <td>: <?= $header->to_location ?></td>
        </tr>

        <tr>
            <td><strong>Approved By</strong></td>
            <td>: <?= $header->approved_by_name ?? '-' ?></td>

            <td><strong>Approved Date</strong></td>
            <td>: <?= $header->approved_date ?? '-' ?></td>
        </tr>
    </table>

    <br>

    <!-- ================= ITEM TABLE ================= -->
    <table>
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="20%">Material Code</th>
                <th width="55%">Material</th>
                <th width="20%" class="right">Qty</th>
            </tr>
        </thead>

        <tbody>
            <?php if(!empty($items)): ?>
                <?php $i = 1; foreach ($items as $row): ?>
                    <tr>
                        <td class="center"><?= $i++ ?></td>
                        <td><?= $row->materialinfocode ?></td>
                        <td><?= $row->material ?></td>
                        <td class="right"><?= number_format($row->qty, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="center">No items found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- ================= SIGNATURE ================= -->
    <table class="no-border signature">
        <tr>
            <td class="center">
                ___________________________<br>
                Prepared By
            </td>
            <td class="center">
                ___________________________<br>
                Dispatched By
            </td>
            <td class="center">
                ___________________________<br>
                Received By
            </td>
        </tr>
    </table>

</body>
</html>
