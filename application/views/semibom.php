<?php 
include "include/header.php";  
include "include/topnavbar.php"; 
?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3">
                        <h1 class="page-header-title font-weight-light">
                            <div class="page-header-icon"><i class="fas fa-briefcase"></i></div>
                            <span>Product BOM</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-dark btn-sm" id="btnviewallbom"><i class="fas fa-list mr-2"></i>All BOM List</button>
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#staticBackdrop" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus mr-2"></i>Create BOM</button>
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Item Code</th>
                                            <th>Item Name</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<!-- Modal Bom Detail -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
	aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle">BOM Details of <label id="procode"></label> </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-hover">
					<thead>
						<tr>
							<th scope="col">Material Category</th>
							<th scope="col">Material Name</th>
							<th scope="col">Material Code</th>
							<th scope="col">Quantity</th>
							<th class="text-right">Actions</th>
						</tr>
					</thead>
					<tbody id="bomrecords">
					</tbody>
				</table>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-primary btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Bom Detail Edit -->
<div class="modal fade" id="bomDetailsEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
	aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<form action="<?php echo base_url() ?>Semibom/Semibomlistedit" method="post"
					autocomplete="off">
					<div class="form-group mb-1">
						<label class="small font-weight-bold">Material Category</label>
						<input type="text" class="form-control form-control-sm" name="category" id="category" readonly>
					</div>
					<div class="form-group mb-1">
						<label class="small font-weight-bold">Material Name</label>
                        <select class="form-control form-control-sm" name="name" id="name">
                            <option value="">Select</option>
                            <?php foreach($materialname->result() as $rowmaterialname){ ?>
                            <option value="<?php echo $rowmaterialname->idtbl_res_material_info ?>">
                                <?php echo $rowmaterialname->material.'-'.$rowmaterialname->materialinfocode?>
                            </option>
                            <?php } ?>
                        </select>
					</div>
					<div class="form-group mb-1">
						<label class="small font-weight-bold">Quantity</label>
						<div class="input-group mb-3">
							<input type="text" class="form-control form-control-sm" name="quantity" id="quantity" required>
						</div>
					</div>
                    <div class="form-group mt-3 text-left">
                        <button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-4"><i class="far fa-save"></i>&nbsp;Update</button>
                    </div>
			        <input type="hidden" name="recordID" id="recordID" value="">
                </form>
                </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-primary btn-sm" data-dismiss="modal"><i
						class="fas fa-times-circle"></i>&nbsp;Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Create BOM</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form action="<?php echo base_url() ?>Semibom/Semibominsertupdate" method="post" autocomplete="off">
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold">Item*</label><br>
                                    <select class="form-control form-control-sm" style="width: 100%;" name="finishgood" id="finishgood" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="dynamic-field" id="dynamic-field-1">
                                <div class="form-row mb-1">
                                    <div class="col-4">
                                        <label class="small font-weight-bold">Material Category*</label><br>
                                        <select class="form-control form-control-sm materialcate" name="materialcategory[]" id="materialcategory" required>
                                            <option value="">Select</option>
                                            <?php foreach($materialcategory->result() as $rowmaterialcategory){ ?>
                                            <option value="<?php echo $rowmaterialcategory->idtbl_res_material_category ?>"><?php echo $rowmaterialcategory->category ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold">Material*</label><br>
                                        <select class="form-control form-control-sm materialinfo" style="width: 100%;" name="materialinfo[]" id="materialinfo" required>
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                    	<label class="small font-weight-bold">Quantity*</label>
                                    		<input type="text" class="form-control form-control-sm" name="qty[]"
                                    			id="qty" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row mt-3">
                                <div class="col text-right">
                                    <button type="button" class="btn btn-outline-danger btn-sm" id="add-button">Add New Row</button>
                                    <button type="button" class="btn btn-outline-dark btn-sm" id="remove-button">Remove Row</button>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <hr>
                                <button type="submit" class="btn btn-outline-primary btn-sm fa-pull-right"><i class="fas fa-save"></i>&nbsp;Save All</button>
                            </div>
                        </form>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalviewalldata" data-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="modalviewalldataLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalviewalldataLabel">View All BOM</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div id="viewdata"></div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
function confirmation() {
	var result = confirm("Are you sure to delete?");
	if (result) {
		console.log("Deleted")
	}
}

$(document).on("click", ".btnEditbom", function () {
	var id = $(this).attr('id');
	// alert(id);
	$.ajax({
		type: "POST",
		data: {
			recordID: id
		},
		url: '<?php echo base_url() ?>Semibom/Semibomlist',
		success: function (result) { //alert(result);

			var obj = JSON.parse(result);

			$('#bomDetailsEdit').modal('show');

			$('#recordID').val(obj.id);
			$('#category').val(obj.materialcategory);
			$('#name').val(obj.materialinfo);
			$('#quantity').val(obj.qty);

			var unit = $("#name").find("option:selected").text().split('/');
			$("#unitedit").val(unit[1]);


		}
	});
});

$(document).on("click", ".btnDeletebom", function () {
	var id = $(this).attr('id');
	// alert(id);
	$.ajax({
		type: "POST",
		data: {
			recordID: id
		},
		url: '<?php echo base_url() ?>Semibom/Semibomdelete',
		success: function (result) { //alert(result);
			$('#exampleModalCenter').modal('hide');
			// alert("Record Delete Successfully");

		}
	});
});

$(document).ready(function () {
	var addcheck = '<?php echo $addcheck; ?>';
	var editcheck = '<?php echo $editcheck; ?>';
	var statuscheck = '<?php echo $statuscheck; ?>';
	var deletecheck = '<?php echo $deletecheck; ?>';

	$('.materialinfo').select2({
		dropdownParent: $('#staticBackdrop'),
	});
	$("#finishgood").select2({
		dropdownParent: $('#staticBackdrop'),
		// placeholder: 'Select supplier',
		ajax: {
			url: "<?php echo base_url() ?>Semibom/GetSemimateriallist",
			type: "post",
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					searchTerm: params.term // search term
				};
			},
			processResults: function (response) {
				return {
					results: response
				};
			},
			cache: true
		}
	});

	$(function () {
		$("#materialinfo").on('change', function () {
			var unit = $(this).find("option:selected").text().split('/');
			$("#unit").val(unit[1]);
		});
	});

	$(function () {
		$("#name").on('change', function () {
			var unit = $(this).find("option:selected").text().split('/');
			$("#unitedit").val(unit[1]);
		});
	});

	$('#dataTable').DataTable({
		"destroy": true,
		"processing": true,
		"serverSide": true,
		dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
		responsive: true,
		lengthMenu: [
			[10, 25, 50, -1],
			[10, 25, 50, 'All'],
		],
		"buttons": [{
				extend: 'csv',
				className: 'btn btn-success btn-sm',
				title: 'Brand Information',
				text: '<i class="fas fa-file-csv mr-2"></i> CSV',
			},
			{
				extend: 'pdf',
				className: 'btn btn-danger btn-sm',
				title: 'Brand Information',
				text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
			},
			{
				extend: 'print',
				title: 'Brand Information',
				className: 'btn btn-primary btn-sm',
				text: '<i class="fas fa-print mr-2"></i> Print',
				customize: function (win) {
					$(win.document.body).find('table')
						.addClass('compact')
						.css('font-size', 'inherit');
				},
			},
			// 'copy', 'csv', 'excel', 'pdf', 'print'
		],
		ajax: {
			url: "<?php echo base_url() ?>scripts/semimaterialbomlist.php",
			type: "POST", // you can use GET
			// data: function(d) {}
		},
		"order": [
			[0, "desc"]
		],
		"columns": [{
				"data": "idtbl_res_item"
			},
			{
				"data": "code"
			},
			{
				"data": "itemname"
			},
			{
				"targets": -1,
				"className": 'text-right',
				"data": null,
				"render": function (data, type, full) {
					var button = '';
					button += '<button class="btn btn-dark btn-sm btnModal mr-1" id="' + full['idtbl_res_item'] + '"><i class="fas fa-eye"></i></button>';

					// if (full['status'] == 1) {
					// 	button += '<a href="<?php echo base_url() ?>Semibom/Semibomstatus/' + full['idtbl_product_bom'] + '/2" onclick="return deactive_confirm()" target="_self" class="btn btn-success btn-sm mr-1 ';
					// 	if (statuscheck != 1) {
					// 		button += 'd-none';
					// 	}
					// 	button += '"><i class="fas fa-check"></i></a>';
					// } else {
					// 	button += '<a href="<?php echo base_url() ?>Semibom/Semibomstatus/' + full['idtbl_product_bom'] + '/1" onclick="return active_confirm()" target="_self" class="btn btn-warning btn-sm mr-1 ';
					// 	if (statuscheck != 1) {
					// 		button += 'd-none';
					// 	}
					// 	button += '"><i class="fas fa-times"></i></a>';
					// }
					// button += '<a href="<?php echo base_url() ?>Semibom/Semibomstatus/' + full['idtbl_product_bom'] + '/3" onclick="return delete_confirm()" target="_self" class="btn btn-danger btn-sm ';
					// if (deletecheck != 1) {
					// 	button += 'd-none';
					// }
					// button += '"><i class="fas fa-trash-alt"></i></a>';

					return button;
				}
			}
		],
		drawCallback: function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	$('#dataTable tbody').on('click', '.btnModal', function () {
		var id = $(this).attr('id');
		var p = $(this).parent().parent();
		var td = p.children("td:nth-child(2)").html();
		$.ajax({
			type: "POST",
			data: {
				recordID: id
			},
			url: '<?php echo base_url() ?>Semibom/Semibomdetails',
			success: function (result) { //alert(result);
				$('#bomrecords').html(result);
				$('#procode').html(td);
				$('#exampleModalCenter').modal('show');
			}
		});
	});

	$('#dataTable tbody').on('click', '.btnEdit', function () {
		var r = confirm("Are you sure, You want to Edit this ? ");
		if (r == true) {
			var id = $(this).attr('id');
			$.ajax({
				type: "POST",
				data: {
					recordID: id
				},
				url: '<?php echo base_url() ?>Semibom/Semibomedit',
				success: function (result) { //alert(result);
					var obj = JSON.parse(result);
					$('#recordID').val(obj.id);

					var html1 = '';
					html1 += '<option value="' + obj.finishgood + '">' + obj.finishgoodtext + '</option>';
					$('#finishgood').empty().append(html1);
					$('#finishgood').trigger('change');

					var html2 = '';
					html2 += '<option value="' + obj.materialinfo + '">' + obj.materialinfotext + '</option>';
					$('#materialinfo').empty().append(html2);
					$('#materialinfo').trigger('change');

					$('#qty').val(obj.qty);
					$('#wastagepresentage').val(obj.wastage);

					$('#recordOption').val('2');
					$('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
				}
			});
		}
	});

	dynamicfieldone();
	matecatechange();

	$('#btnviewallbom').click(function(){
		$('#modalviewalldata').modal('show');
		$('#viewdata').html('<div class="card border-0 shadow-none"><div class="card-body text-center"><img src="images/spinner.gif" class="img-fluid" /></div></div>');

		$.ajax({
			type: "POST",
			data: {},
			url: '<?php echo base_url() ?>Semibom/Semibomalllist',
			success: function (result) { //alert(result);
				$('#viewdata').html(result);
				$('#dataTableview').DataTable({
					"ordering": false,
					dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
					responsive: true,
					paging: false,
					"buttons": [{
							extend: 'csv',
							className: 'btn btn-success btn-sm',
							title: 'Semi BOM Information',
							text: '<i class="fas fa-file-csv mr-2"></i> CSV',
						},
						{
							extend: 'pdf',
							className: 'btn btn-danger btn-sm',
							title: 'Semi BOM Information',
							text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
						},
						{
							extend: 'print',
							title: 'Semi BOM Information',
							className: 'btn btn-primary btn-sm',
							text: '<i class="fas fa-print mr-2"></i> Print',
							customize: function (win) {
								$(win.document.body).find('table')
									.addClass('compact')
									.css('font-size', 'inherit');
							},
						},
						// 'copy', 'csv', 'excel', 'pdf', 'print'
					],
				});
			}
		});
	});
});

function dynamicfieldone() {
	var buttonAdd = $("#add-button");
	var buttonRemove = $("#remove-button");
	var className = ".dynamic-field";
	var count = 0;
	var field = "";
	var maxFields = 25;

	function totalFields() {
		return $(className).length;
	}

	function addNewField() {
		count = totalFields() + 1;
		field = $("#dynamic-field-1").clone();
		field.attr("id", "dynamic-field-" + count);
		field.children("label").text("Field " + count);
		field.find("input").val("");
		field.find("span").remove();
		field.find('.materialcate').prop('id', 'materialcategory' + count);
		field.find('.materialinfo').prop('id', 'materialinfo' + count);
		field.find('.materialinfo').select2({ //apply select2 to my element
			dropdownParent: $('#staticBackdrop'),
		});
		$(className + ":last").after($(field));
	}

	function removeLastField() {
		if (totalFields() > 1) {
			$(className + ":last").remove();
		}
	}

	function enableButtonRemove() {
		if (totalFields() === 2) {
			buttonRemove.removeAttr("disabled");
			buttonRemove.addClass("shadow-sm");
		}
	}

	function disableButtonRemove() {
		if (totalFields() === 1) {
			buttonRemove.attr("disabled", "disabled");
			buttonRemove.removeClass("shadow-sm");
		}
	}

	function disableButtonAdd() {
		if (totalFields() === maxFields) {
			buttonAdd.attr("disabled", "disabled");
			buttonAdd.removeClass("shadow-sm");
		}
	}

	function enableButtonAdd() {
		if (totalFields() === (maxFields - 1)) {
			buttonAdd.removeAttr("disabled");
			buttonAdd.addClass("shadow-sm");
		}
	}

	buttonAdd.click(function () {
		addNewField();
		enableButtonRemove();
		disableButtonAdd();
		matecatechange();
	});

	buttonRemove.click(function () {
		removeLastField();
		disableButtonRemove();
		enableButtonAdd();
		matecatechange();
	});
}

function matecatechange() {
	$('.materialcate').change(function () {
		var id = $(this).val();
		var element = $(this).attr('id');

		const myArray = element.split("materialcategory");
		const ele = myArray[1];

		$.ajax({
			type: "POST",
			data: {
				recordID: id
			},
			url: '<?php echo base_url() ?>Semibom/Getmaterialinfo',
			success: function (result) { //alert(result);
				var obj = JSON.parse(result);
				var html2 = '';
				html2 += '<option value="">Select</option>';
				$.each(obj, function (i, item) {
					html2 += '<option value="' + obj[i].idtbl_res_material_info + '">';
					html2 += obj[i].material + ' - ' + obj[i].materialinfocode;
					html2 += '</option>';
				});
				$('#materialinfo' + ele).empty().append(html2).trigger('change');
			}
		});
	});
}

function deactive_confirm() {
	return confirm("Are you sure you want to deactive this?");
}

function active_confirm() {
	return confirm("Are you sure you want to active this?");
}

function delete_confirm() {
	return confirm("Are you sure you want to remove this?");
}
</script>
<?php include "include/footer.php"; ?>
