<?php 
include "include/header.php"; 

include "include/topnavbar.php"; 

?>
<style>
    .pointer {cursor: pointer;}

    .card-material{
    border-radius: 4px;
    transition: .5s;
}

    .card-material:hover{
        transform:scale(1.1);
    box-shadow: 0 10px 20px rgba(0,0,0,.12), 0 4px 8px rgba(0,0,0,.06);
    }

</style>
<div id="layoutSidenav">
	<div id="layoutSidenav_nav">
		<?php include "include/menubar.php"; ?>
	</div>
	<div id="layoutSidenav_content">
		<main>
			<div class="container-fluid p-0 p-2">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<input type="hidden" id="hiddenmaterialID" name="hiddenmaterialID">
							<input type="hidden" id="saletype" name="saletype">
							<div id="maindiv" class="col-sm-12 col-md-12 col-lg-2 col-xl-2"
								style="border-right: 1px dotted #000;">
								<div class="accordion" id="accordionExample">
									<div class="card shadow-none border-0">
										<div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
											data-parent="#accordionExample">
											<h6 class="title-style mb-3"><span>Category</span></h6>
											<div class="card-body border rounded"
												style="height: 550px; overflow-y:auto; background-color:#FAF7DB;"
												id="style-2">
												<div class="row row-cols-1 row-cols-md-1">
													<?php foreach ($category->result() as $rowcategory) { ?>
													<div class="col mb-4 categorydiv" tabindex="0"
														id="<?php echo $rowcategory->idtbl_res_item_category ?>">
														<div
															class="card card-material h-100 shadow-none bg-warning border-warning">
															<div class="card-body p-2 text-center pointer">
																<h4 class="text-light font-weight-bold"
																	style="font-size:20px;">
																	<?php echo $rowcategory->categoryname ?></h4>
															</div>
														</div>
													</div>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>

								</div>
							</div>
							<div id="maindiv" class="col-sm-12 col-md-12 col-lg-5 col-xl-5"
								style="border-right: 1px dotted #000;">
								<div class="accordion" id="accordionExample">
									<div class="card shadow-none border-0">
										<div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
											data-parent="#accordionExample">
											<h6 class="title-style mb-3"><span>Items</span></h6>
											<div class="card-body border rounded mb-2" id="style-2">
												<div class="row">
													<div class="col-3 ">
														<button type="button" class="btn btn-primary  w-100 btn-sm"
															data-toggle="modal" data-target="#modaltablecat"
															<?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-chair"></i>&nbsp;TABLE</button>
													</div>
												</div>
											</div>
											<div class="card-body border rounded" style="height: 460px; overflow-y:auto;" id="style-2">
												<div id="itemlist"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-md-12 col-lg-5 col-xl-5">
								<h6 class="title-style mb-3"><span>Cart Information</span></h6>
								<div id="style-17" style="height: 295px; overflow-y:auto;">
									<table class="table table-striped table-sm" id="carttable">
										<thead>
											<tr>
												<th>PRODUCT</th>
												<th class="text-center">QTY</th>
												<th class="text-right">SALE</th>
												<th class="text-right">DISCOUNT</th>
												<th class="text-right">TOTAL</th>
												<th class="d-none">productid</th>
												<th class="d-none">productcode</th>
												<th class="d-none">sale</th>
												<th class="d-none">unit</th>
												<th class="d-none">total</th>
												<th class="d-none">dispre</th>
												<th class="d-none">distotal</th>
												<th class="d-none">nettotal</th>
												<th class="d-none">editstatus</th>
												<th class="d-none">Table</th>
											</tr>
										</thead>
										<tbody>

										</tbody>
									</table>
								</div>
								<div class="row">
									<div class="col-12 text-right">
										<h5 id="labeltotal">Gross Amount: 0.00</h5>
										<h5 id="labeldistotal">Discount: 0.00</h5>
										<hr class="my-1">
										<div id="labelnettotal" class="display-4">0.00</div>
										<input type="hidden" id="hiddenfulltotal">
										<input type="hidden" id="hiddenfulldistotal">
										<input type="hidden" id="hiddenteminvoiceid">
										<input type="hidden" id="hiddenfullnettotal">
										<input type="hidden" id="priceeditstatus">
										<input type="hidden" name="hideapproveuser" id="hideapproveuser" value="0">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</main>
		<?php include "include/footerbar.php"; ?>
	</div>
</div>
<!-- Modal Qty -->
<div class="modal fade" id="modalqty" data-bs-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">ADD TO LIST</h5>
				<button type="button"class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
						<div class="card shadow-none bg-warning border-warning">
							<div class="card-body p-2 text-center pointer">
								<h4 class="text-light font-weight-light" id="selectproduct">Test</h4>
								<hr class="border-light my-1">
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
						<div class="row">
							<div class="col-12">
								<hr>
								<form id="formqtyadd">
									<div class="form-group mb-1">
										<label class="small font-weight-bold text-dark">Sale Price*</label>
										<input type="tel" name="salepriceedit" id="salepriceedit" class="form-control"
											required>
									</div>
									<div class="form-group mb-1">
										<label class="small font-weight-bold text-dark">Qty</label>
										<input type="tel" name="qtycount" id="qtycount" class="form-control" required>
									</div>
									<div class="form-group mb-1">
										<label class="small font-weight-bold text-dark">Discount*</label>
										<div class="input-group flex-nowrap">
											<input type="number" name="discountpresentage" id="discountpresentage"
												class="form-control" value="0" required>
											<div class="input-group-append">
												<span class="input-group-text" id="addon-wrapping">%</span>
											</div>
										</div>
									</div>
									<div class="form-group mt-3 text-right">
										<button type='button' class="btn btn-danger" id="btnaddtolist">ADD TO
											LIST</button>
									</div>
									<input type="submit" class="d-none" id="btnhideqtysubmit">
									<input type="reset" class="d-none" id="btnhideqtyreset">
									<input type="hidden" name="hideproductid" id="hideproductid">
									<input type="hidden" name="hideproduct" id="hideproduct">
									<input type="hidden" name="hideproductcode" id="hideproductcode">
									<input type="hidden" name="hideproductsale" id="hideproductsale">
									<input type="hidden" name="hidetableid" id="hidetableid">
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal Table -->
<div class="modal fade" id="modaltable" data-bs-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-l">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="staticBackdropLabel">Select the Table</h3>
                <button type="button"class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row row-cols-1 row-cols-md-3" id="btnpro">
                            <!-- Tables will be appended here via AJAX -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Table Category -->
<div class="modal fade" id="modaltablecat" data-bs-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-l">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="staticBackdropLabel">Select the Table Category</h3>
				<button type="button"class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
						<div class="row row-cols-1 row-cols-md-2" id="btnpro">
							<?php
                                
                                foreach ($tablecat->result() as $rowtable) {                                
                                ?>
							<div class="col-2 mb-4 tablecatdiv" tabindex="0"
								id="<?php echo $rowtable->idtbl_res_reservation_category ?>">
								<div class="card card-material h-100 shadow-none bg-primary">
									<div class="card-body p-2 text-center pointer">
										<img src="<?php echo base_url('images/Items/table.png'); ?>" alt=""
											style="width: 110px; height: 70px;">
										<hr class="border-light my-1">
										<h4 class="text-light text-uppercase font-weight-bold" style="font-size:20px;">
											<?php echo $rowtable->reservation_category ?></h4>
									</div>
								</div>
							</div>
							<?php
                                }
                            ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Reject Reason Modal -->
<div class="modal fade" id="rejectReasonModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="rejectReasonModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectReasonModalLabel">Cancel Reason</h5>
                <button type="button"class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="rejectReasonForm">
                    <div class="form-group">
                        <label for="rejectReason">Please provide a reason for rejecting this item:</label>
                        <textarea class="form-control" id="rejectReason" name="rejectReason" required></textarea>
                    </div>
                    <input type="hidden" id="removeProductRowIndex">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmReject">Submit</button>
            </div>
        </div>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>

<script type="text/javascript">
$(document).ready(function () {
	var btnCash = $('#btncash');
	var btnCredit = $('#btncredit');

	// set initial focus on btncash
	btnCash.focus();

	$(document).keydown(function (e) {
		var arrowKey = e.which;

		// move focus to btncredit when right arrow key is pressed
		// move focus to btnCredit when up arrow key is pressed
		if (arrowKey === 38) {
			e.preventDefault(); // prevent default behavior of arrow keys
			btnCredit.focus();
		}

		// move focus to btnCash when down arrow key is pressed
		if (arrowKey === 40) {
			e.preventDefault(); // prevent default behavior of arrow keys
			btnCash.focus();
		}
	});
});

$(document).ready(function () {
	var currentRow = null;

	$(document).on("click", ".itemdiv", function (e) {
		var id = $(this).attr('id');
		// alert(id);

		e.preventDefault();

		$.ajax({
			type: "POST",
			data: {
				recordID: id
			},
			url: "<?php echo base_url() ?>Waiterorder/Getproductdetails",
			success: function (result) {
				//alert(result);
				var obj = JSON.parse(result);
				$('#modalqty').modal('show');

				$('#hideproductid').val(obj.id);
				$('#hideproduct').val(obj.itemname);
				$('#hideproductcode').val(obj.itemname);
				$('#hideproductsale').val(obj.price);
				$('#salepriceedit').val(obj.price);

				$('#selectproduct').html(obj.itemname);
			},
			error: function (xhr, status, error) {
				alert("An error occurred while fetching product details.");
				console.log(xhr.responseText);
			}
		});
	});

	$('#modalqty').on('shown.bs.modal', function () {
		$('#qtycount').focus();
	});


	// Do something with the modal data
	$("#modalqty").on('hide.bs.modal', function () {
		$('#qtycount').focus();
	});
});
     $(document).ready(function () {
     			// $('#modalretailwholesale').modal('show');
     			// $('#qtycount').keyboard();
     			// $('#salepriceedit').keyboard();
     			// $('#discountpresentage').keyboard();
     			// $('#amount').keyboard();
     			// $('#bank').keyboard();
     			// $('#chequeno').keyboard();
     			// $('#externalsearch').keyboard();
     			// $('#cusname').keyboard();
     			// $('#cusnic').keyboard();
     			// $('#cusmobile').keyboard();

     			$("#orderbtn").on('click', function (e) {
     				$('#modalorders').modal('show');

     				$.ajax({
     					method: "POST",
     					data: {

     					},
     					url: '<?php echo base_url() ?>Waiterorder/Getonlineorders',
     					success: function (result) {
     						$('#ordertable').html(result);
     					}
     				});
     			});

     			$('#btnretailsale').click(function () {
     				$('#saletype').val('1');
     				$('#modalretailwholesale').modal('hide');
     			});
     			$('#btnwholesale').click(function () {
     				$('#saletype').val('2');
     				$('#modalretailwholesale').modal('hide');
     			});


     			$('#customersearchsubmit').click(function () {
     				if (!$("#alreadycusform")[0].checkValidity()) {
     					// If the form is invalid, submit it. The form won't actually submit;
     					// this will just cause the browser to display the native HTML5 error messages.
     					$("#hidecustomersearchsubmit").click();
     				} else {
     					dataTable.draw();
     				}
     			});
     			$('.categorydiv').click(function () {
     				var categoryID = $(this).attr('id');
     				$('#hiddenmaterialID').val(categoryID);

     				$.ajax({
     					method: "POST",
     					data: {
     						categoryID: categoryID
     					},
     					url: '<?php echo base_url() ?>Waiterorder/Getproductlist',
     					success: function (result) {
     						$('#itemlist').html(result);
     						$('#restaurant-image').hide(); // Hide the restaurant image
     					}
     				});
     			});

        $("#barcode").keypress(function (event) {
            if (event.keyCode === 13) { // check if the pressed key is enter key
                event.preventDefault(); // prevent form submission
                var barcode = $(this).val();
                if (barcode !== '') { // check if barcode field is not empty
                $("#collapseFour").collapse('show');
                $.ajax({
                    method: "POST",
                    data: {
                    barcode: barcode
                    },
                    url: '<?php echo base_url() ?>Waiterorder/Getproductlistaccobarcode',
                    success: function (result) {
                    $('#tableproductpricelist > tbody').empty().append(result);
                    productlistoption();
                    }
                });
                }
            }
            }); 

        $('#btnbackthree').click(function(){
            $("#collapseOne").collapse('show');
        });  

        $('#btnaddtolist').click(function(e) {
            if (e.which == 13) {
                e.preventDefault(); // prevent form submission
                $('#paymentbtn').trigger('click'); // trigger click event on #paymentbtn
            }
        });
        $('#salepriceedit').keypress(function (e) {
            var key = e.which;
            if(key == 13){
                $('#qtycount').focus();
                return false;  
            }
        });
        $('#qtycount').keypress(function (e) {
            var key = e.which;
            if(key == 13){
                $('#discountpresentage').focus();
                return false;  
            }
        });
        $('#barcode').keydown(function (e) {
            var key = e.which;
            var current = $('.categorydiv:focus');
            var next, prev;
            if (key == 40) { // Down arrow key
                next = current.next('.categorydiv');
                if (next.length) {
                    next.focus();
                }
                return false;
            } else if (key == 38) { // Up arrow key
                prev = current.prev('.categorydiv');
                if (prev.length) {
                    prev.focus();
                }
                return false;
            } else if (key == 39) { // Right arrow key
                next = current.next('.categorydiv');
                if (next.length) {
                    next.focus();
                } else {
                    $('.categorydiv:first').focus();
                }
                return false;
            } else if (key == 37) { // Left arrow key
                prev = current.prev('.categorydiv');
                if (prev.length) {
                    prev.focus();
                } else {
                    $('.categorydiv:last').focus();
                }
                return false;
            }
        });
        $('.categorydiv').keypress(function (e) {
            var categoryID=$(this).attr('id');
            var saletype = $('#saletype').val();
            $('#hiddenmaterialID').val(categoryID);

            $("#collapseFour").collapse('show');
            var key = e.which;
            if(key == 13){
                $.ajax({
                method: "POST",
                data: {
                    categoryID: categoryID,
                    saletype: saletype
                },
                url: '<?php echo base_url() ?>Waiterorder/Getproductlist',
                success: function (result) { //alert(result)
                    $('#tableproductpricelist > tbody').empty().append(result);
                    productlistoption();
                }
            });

            }
        });
        $('#tableproductpricelist').on('click', 'tr', function (e) {
            var key = e.which;
            if(key == 13){
                $("#modalqty").modal('show');
                return false;  
            }
        });
        $('#discountpresentage').keypress(function (e) {
            var key = e.which;
            if(key == 13){
                $("#btnaddtolist").click();
                return false;  
            }
        });
        $("#btnaddtolist").click(function () {
			// Check if a table is selected
			var tableID = $('#hidetableid').val();
			if (tableID === '' || tableID === null) {
				alert('Select a Table First');
				$('#modalqty').modal('hide');
				return; // Exit the function if no table is selected
			}

			// Check if the form is valid
			if (!$("#formqtyadd")[0].checkValidity()) {
				// If the form is invalid, submit it to trigger native HTML5 validation messages
				$("#btnhideqtysubmit").click();
			} else {
				// Variables related to the product and calculation
				var productID = $('#hideproductid').val();
				var product = $('#hideproduct').val();
				var productcode = $('#hideproductcode').val();
				var unit = parseFloat($('#hideproductunit').val());
				var sale = parseFloat($('#hideproductsale').val());
				var qty = parseFloat($('#qtycount').val());
				var salepriceedit = parseFloat($('#salepriceedit').val());
				var discountpresentage = parseFloat($('#discountpresentage').val());

				// Calculate total price, discount, and net total
				if (salepriceedit != sale) {
					$('#priceeditstatus').val('1');
					var finalsaleamount = salepriceedit;
				} else {
					var finalsaleamount = sale;
				}

				var total = finalsaleamount * qty;
				var discountamount = (total * discountpresentage) / 100;
				var totalwithdis = total - discountamount;
				var showtotal = totalwithdis.toFixed(2);
				var classname = (salepriceedit != sale) ? 'table-info' : '';
				var editstatus = (salepriceedit != sale) ? '1' : '0';

				// Append the item to the cart table
				$('#carttable > tbody:last').append(
					'<tr class="pointer ' + classname + '"><td>' + productcode + '</td><td class="text-center">' + qty +
					'</td><td class="text-right">' + finalsaleamount.toFixed(2) + '</td><td class="text-right">' +
					discountamount.toFixed(2) + '</td><td class="text-right">' + showtotal + '</td><td class="d-none">' +
					productID + '</td><td class="d-none">' + productcode + '</td><td class="d-none">' + finalsaleamount +
					'</td><td class="d-none">' + unit + '</td><td class="total d-none">' + total +
					'</td><td class="d-none">' + discountpresentage + '</td><td class="distotal d-none">' +
					discountamount + '</td><td class="nettotal d-none">' + totalwithdis +
					'</td><td class="d-none">' + editstatus + '</td><td class="d-none">' + tableID + '</td></tr>'
				);

				// Calculate and display the total amounts
				var sum = 0;
				$(".total").each(function () {
					sum += parseFloat($(this).text());
				});
				var showsum = sum.toFixed(2);

				var dissum = 0;
				$(".distotal").each(function () {
					dissum += parseFloat($(this).text());
				});
				var showdissum = dissum.toFixed(2);

				var netsum = 0;
				$(".nettotal").each(function () {
					netsum += parseFloat($(this).text());
				});
				var shownetsum = netsum.toFixed(2);

				$('#labeltotal').html('Gross Amount: ' + showsum);
				$('#labeldistotal').html('Discount: ' + showdissum);
				$('#labelnettotal').html(shownetsum);
				$('#htmlbillamount').html('Rs. ' + shownetsum);
				$('#hiddenfulltotal').val(sum);
				$('#hiddenfulldistotal').val(dissum);
				$('#hiddenfullnettotal').val(netsum);
				$('#amount').val(netsum);

				// Reset and hide the modal
				$('#btnhideqtyreset').click();
				$('#modalqty').modal('hide');
				$("#collapseOne").collapse('show');

				// Send data to the server if a table is selected
				if (tableID !== '') {
					$.ajax({
						type: "POST",
						data: {
							productID: productID,
							product: product,
							productcode: productcode,
							unit: unit,
							sale: sale,
							qty: qty,
							salepriceedit: salepriceedit,
							discountpresentage: discountpresentage,
							tableID: tableID
						},
						url: '<?php echo base_url() ?>Waiterorder/Waiterorderinsertupdate',
						success: function (result) {
							// Handle the AJAX success response here
						}
					});
				}
			}
		});

		$('.tablecatdiv').on('click', function () {
			var selectedCategoryId = $(this).attr('id');

			$.ajax({
				url: '<?php echo base_url('Waiterorder/getTablesByCategory'); ?>',
				type: 'POST',
				data: {
					category_id: selectedCategoryId
				},
				dataType: 'json',
				success: function (response) {
					$('#btnpro').empty(); // Clear the current tables

					// Check if response has tables
					if (response.length > 0) {
						// Append new tables based on response
						$.each(response, function (index, row) {
							$('#btnpro').append(`
                            <div class="col mb-4 tablediv" tabindex="0" id="${row.idtbl_res_reservation_table}">
                                <div class="card card-material h-100 shadow-none bg-secondary">
                                    <div class="card-body p-2 text-center pointer">
                                        <img src="<?php echo base_url('images/Items/table.png'); ?>" alt="" style="width: 110px; height: 70px;">
                                        <hr class="border-light my-1">
                                        <h4 class="text-light text-uppercase font-weight-bold" style="font-size:20px;">
                                            ${row.table}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        `);
						});
					} else {
						$('#btnpro').append('<p class="text-dark">No tables available for this category.</p>');
					}

					// Show the table modal
					$('#modaltable').modal('show');
				},
				error: function (xhr, status, error) {
					console.error('Error fetching tables:', error);
					alert('An error occurred while fetching tables. Please try again.');
				}
			});

			// Optionally, close the category modal
			$('#modaltablecat').modal('hide');
		});


		$(document).on('click', '.tablediv', function () {
			var table = $(this).attr('id');
			$('#hidetableid').val(table); // Set hidden field

			// Close the modal
			$('#modaltable').modal('hide');

			// Fetch items related to the selected table
			$.ajax({
				type: "POST",
				data: {
					tableID: table
				},
				url: '<?php echo base_url() ?>Waiterorder/Getaddeditems',
				success: function (result) {
					var response = JSON.parse(result);
					var items = response.items;
					var total_sum = parseFloat(response.total_sum) || 0;

					// Clear the cart information table
					$('#carttable tbody').empty();
					$('#labeltotal').text('Gross Amount: 0.00');
					$('#labeldistotal').text('Discount: 0.00');
					$('#labelnettotal').text('0.00');
					$('#hiddenfulltotal').val('');
					$('#hiddenfulldistotal').val('');
					$('#hiddenfullnettotal').val('');
					$('#priceeditstatus').val('');

					// Populate the cart information table with retrieved items
					items.forEach(function (item) {
						var className = item.billclose != 1 ? 'table-info' : '';
						var showTotal = parseFloat(item.total).toFixed(2);

						$('#carttable tbody').append(`
                    <tr class="pointer ${className}">
                        <td>${item.itemname}</td>
                        <td class="text-center">${item.qty}</td>
                        <td class="text-right">${parseFloat(item.saleprice).toFixed(2)}</td>
                        <td class="text-right"></td>
                        <td class="total text-right">${showTotal}</td>
                        <td class="d-none">${item.itemID}</td>
                        <td class="d-none">${item.discountamount}</td>
                        <td class="nettotal d-none">${item.total}</td>
                        <td class="d-none">${table}</td>
                    </tr>
                `);
					});

					// Display total amounts
					var sum = 0;
					$(".total").each(function () {
						sum += parseFloat($(this).text());
					});

					var showsum = parseFloat(sum).toFixed(2);
					var dissum = parseFloat($('#labeldistotal').text().replace('Discount: ', '')) || 0;
					var showdissum = parseFloat(dissum).toFixed(2);
					var netsum = parseFloat(total_sum).toFixed(2);

					$('#labeltotal').html('Gross Amount: ' + showsum);
					$('#labeldistotal').html('Discount: ' + showdissum);
					$('#labelnettotal').html('Net Total: ' + netsum);
					$('#htmlbillamount').html('Rs. ' + netsum);

					$('#hiddenfulltotal').val(showsum);
					$('#hiddenfulldistotal').val(showdissum);
					$('#hiddenfullnettotal').val(netsum);
					$('#amount').val(netsum);

					$('#btnhideqtyreset').click();
					$('#modalqty').modal('hide');
					$("#collapseOne").collapse('show');
				}
			});
		});



        var productRow;

		$('#carttable').on('click', 'tr', function () {
			productRow = $(this);
			var r = confirm("Are you sure, You want to remove this Item?");
			if (r == true) {
				$('#rejectReasonModal').modal('show');
			}
		});

		$('#confirmReject').click(function () {
			var rejectReason = $('#rejectReason').val();

			if (rejectReason.trim() === '') {
				alert('Please enter a reason for rejection.');
				return;
			}

			var productID = productRow.find('td').eq(5).text();
			var tableID = productRow.find('td').eq(8).text();
			var orderID = $('#hiddenteminvoiceid').val();

			productRow.remove();

			updateTotals();

			$('#rejectReasonModal').modal('hide');
			$('#rejectReason').val('');

			$.ajax({
				type: "POST",
				url: '<?php echo base_url() ?>Waiterorder/RemoveProduct',
				data: {
					productID: productID,
					tableID: tableID,
					orderID: orderID,
					rejectReason: rejectReason
				},
				success: function (response) {
					
				},
			});
		});



    });

	function updateTotals() {
        var sum = 0;
        $(".total").each(function () {
            sum += parseFloat($(this).text());
        });

        var showsum = addCommas(parseFloat(sum).toFixed(2));
        $('#labeltotal').html('Rs. ' + showsum);
        $('#labelnettotal').html('Rs. ' + showsum);
        $('#hiddenfulltotal').val(sum);
        $('#hiddenfullnettotal').val(sum);
        $('#btnhideqtyreset').click();
        $('#modalqty').modal('hide');
        $("#collapseOne").collapse('show');
    }

    function productlistoption(){
        $("#tableproductpricelist").delegate("tr.pointer", "click", function(){
            var productID = $(this).children("td:eq(0)").text();
            var productcode = $(this).children("td:eq(1)").text();
            var product = $(this).children("td:eq(2)").text();
            // var unit = $(this).children("td:eq(4)").text();
            var sale = $(this).children("td:eq(4)").text();
            var maxdiscount = $(this).children("td:eq(6)").text();

            // var unit = unit.replace(",", ""); 
            // var sale = sale.replace(",", ""); 

            $("#discountpresentage").attr('max', maxdiscount);

            $('#hideproductid').val(productID);
            $('#hideproduct').val(product);
            $('#hideproductcode').val(productcode);
            // $('#hideproductunit').val(unit);
            $('#hideproductsale').val(sale);
            $('#salepriceedit').val(sale);

            $('#selectproduct').html(productcode);

            $('#modalqty').modal('show');
            $('#modalqty').on('shown.bs.modal', function () {
                $('#qtycount').focus();
            })  
        });
    }

    function addCommas(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

    function action(data) { //alert(data);
        var obj = JSON.parse(data);
        $.notify({
            // options
            icon: obj.icon,
            title: obj.title,
            message: obj.message,
            url: obj.url,
            target: obj.target
        }, {
            // settings
            element: 'body',
            position: null,
            type: obj.type,
            allow_dismiss: true,
            newest_on_top: false,
            showProgressbar: false,
            placement: {
                from: "top",
                align: "center"
            },
            offset: 100,
            spacing: 10,
            z_index: 1031,
            delay: 5000,
            timer: 1000,
            url_target: '_blank',
            mouse_over: null,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
            onShow: null,
            onShown: null,
            onClose: null,
            onClosed: null,
            icon_type: 'class',
            template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true"class="btn-close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title">{1}</span> ' +
                '<span data-notify="message">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                '</div>' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                '</div>'
        });
    }
	
	// function removeItem(){      
    //     var productid = $('#productID').val();
	// 	var tableID = $('#hidetableid').val();

	// 	alert (productid);
        
    //         // $.ajax({
    //         //     type: "POST",
    //         //     data: {
    //         //         productid: productid,
	// 		// 		tableID: tableID
    //         //     },
    //         //     url: '<?php echo base_url() ?>Waiterorder/Removeitem',
    //         //     success: function (result) { //alert(result);
    //         //     }
    //         // });
    // }
</script>

<?php include "include/footer.php"; ?>
