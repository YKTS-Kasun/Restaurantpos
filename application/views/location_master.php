<?php include "include/header.php"; ?>
<?php include "include/topnavbar.php"; ?>

<div id="layoutSidenav">
<div id="layoutSidenav_nav">
    <?php include "include/menubar.php"; ?>
</div>

<div id="layoutSidenav_content">
<main class="p-3">

<h4 class="mb-3">📍 Location Master</h4>

<div class="row">

<!-- LEFT FORM -->
<div class="col-md-4">
<div class="card">
<div class="card-body">

<form id="locationForm">

<input type="hidden" name="record_id" id="record_id" value="">
<input type="hidden" name="record_option" id="record_option" value="1">

<div class="form-group">
<label>Location Code</label>
<input type="text" class="form-control form-control-sm" name="location_code" id="location_code" required>
</div>

<div class="form-group">
<label>Location Name</label>
<input type="text" class="form-control form-control-sm" name="location_name" id="location_name" required>
</div>

<div class="form-group">
<label>Location Type</label>
<select class="form-control form-control-sm" name="location_type" id="location_type">
<option value="HO">Head Office</option>
<option value="BRANCH">Branch</option>
</select>
</div>

<div class="form-group d-none" id="parentBlock">
<label>Parent Head Office</label>
<select class="form-control form-control-sm" name="parent_location_id" id="parent_location_id">
<option value="">Select HO</option>
<?php foreach($holist->result() as $ho){ ?>
<option value="<?= $ho->idtbl_location ?>"><?= $ho->location_name ?></option>
<?php } ?>
</select>
</div>

<button class="btn btn-primary btn-sm" id="submitBtn">
<i class="far fa-save"></i> Save Location
</button>

</form>


</div>
</div>
</div>

<!-- RIGHT TABLE -->
<div class="col-md-8">
<table class="table table-bordered table-sm">
<thead>
<tr>
<th>#</th>
<th>Code</th>
<th>Name</th>
<th>Type</th>
<th>Parent</th>
<th class="text-right">Action</th>
</tr>
</thead>
<tbody>
<?php foreach($locations->result() as $row){ ?>
<tr>
<td><?= $row->idtbl_location ?></td>
<td><?= $row->location_code ?></td>
<td><?= $row->location_name ?></td>
<td><?= $row->location_type ?></td>
<td><?= $row->parent_name ?? '-' ?></td>
<td class="text-right">

<button class="btn btn-sm btn-primary btnEdit"
data-id="<?= $row->idtbl_location ?>"
data-code="<?= $row->location_code ?>"
data-name="<?= $row->location_name ?>"
data-type="<?= $row->location_type ?>"
data-parent="<?= $row->parent_location_id ?>">
<i class="fas fa-pen"></i>
</button>

<a href="<?= base_url('Location/delete/'.$row->idtbl_location) ?>"
onclick="return confirm('Delete this location?')"
class="btn btn-sm btn-danger">
<i class="fas fa-trash"></i>
</a>

</td>
</tr>
<?php } ?>
</tbody>

</table>
</div>

</div>
</main>
</div>
</div>

<?php include "include/footerscripts.php"; ?>

<script>
$('#location_type').change(function(){
    if($(this).val() === 'BRANCH'){
        $('#parentBlock').removeClass('d-none');
    }else{
        $('#parentBlock').addClass('d-none');
        $('#parent_location_id').val('');
    }
});

/* EDIT */
$('.btnEdit').click(function(){

    $('#record_option').val(2);
    $('#record_id').val($(this).data('id'));

    $('#location_code').val($(this).data('code'));
    $('#location_name').val($(this).data('name'));
    $('#location_type').val($(this).data('type')).trigger('change');
    $('#parent_location_id').val($(this).data('parent'));

    $('#submitBtn').html('<i class="far fa-save"></i> Update Location');
});

/* SAVE / UPDATE */
$('#locationForm').submit(function(e){
    e.preventDefault();

    $.post("<?= base_url('Location/save') ?>", $(this).serialize(), function(res){
        let obj = JSON.parse(res);
        alert(obj.message);
        if(obj.status == 1){
            location.reload();
        }
    });
});

</script>

<?php include "include/footer.php"; ?>
