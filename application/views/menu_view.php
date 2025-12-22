<!DOCTYPE html>
<html>
<head>
    <title>Menu Master</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-4">

<h4 class="mb-3">Menu Master</h4>

<div class="row">

    <!-- LEFT FORM -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <form method="post" action="<?= base_url('MenuList/save'); ?>">

                    <div class="form-group">
                        <label>Menu ID</label>
                        <input type="number" name="idtbl_res_menu_list"
                               id="idtbl_res_menu_list"
                               class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Menu Name</label>
                        <input type="text" name="menu"
                               id="menu_name"
                               class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <button class="btn btn-success btn-block">
                        Save / Update
                    </button>

                </form>
            </div>
        </div>
    </div>

    <!-- RIGHT TABLE -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-0">

                <table class="table table-bordered table-sm mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Menu Name</th>
                            <th>Status</th>
                            <th width="80">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($menus as $row): ?>
                        <tr>
                            <td><?= $row->idtbl_res_menu_list ?></td>
                            <td><?= $row->menu ?></td>
                            <td><?= $row->status ? 'Active' : 'Inactive' ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm"
                                  onclick="editMenu(
                                    '<?= $row->idtbl_res_menu_list ?>',
                                    '<?= htmlspecialchars($row->menu, ENT_QUOTES) ?>',
                                    '<?= $row->status ?>'
                                  )">
                                  Edit
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- PAGINATION -->
                <div class="p-3">
                    <?=  $links ?>
                </div>

            </div>
        </div>
    </div>

</div>
</div>

<script>
function editMenu(id, name, status){
    document.getElementById('idtbl_res_menu_list').value = id;
    document.getElementById('menu').value = name;
    document.getElementById('status').value = status;
}
</script>

</body>
</html>
