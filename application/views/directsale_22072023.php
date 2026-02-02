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
														<button type="button" class="btn btn-primary w-100 btn-sm"
															data-toggle="modal" data-target="#modaltable"
															<?php if($addcheck==0){echo 'disabled';} ?>><i
																class="fas fa-utensils"></i>&nbsp;Select Table</button>
													</div>
													<div class="col">
														<button type="button" id="orderbtn"
															class="btn btn-secondary btn-sm" data-toggle="modal"
															data-target="#modalorders"
															<?php if($addcheck==0){echo 'disabled';} ?>><i
																class="fas fa-file"></i></i>&nbsp;Online Orders</button>
													</div>
												</div>
											</div>
											<div class="card-body border rounded"
												style="height: 460px; overflow-y:auto;" id="style-2">
												<div id="itemlist"></div>
												<img id="restaurant-image"
													src="<?php echo base_url('images/logo.png') ?>" alt=""
													style="width: 400; height: 300px; padding-top:50px; padding-left:170px;">
											</div>
										</div>
									</div>
									<div class="card shadow-none border-0">
										<div id="collapseFour" class="collapse" aria-labelledby="headingFour"
											data-parent="#accordionExample">
											<div class="card-body border rounded">
												<div class="row">
													<div class="col-12 text-right pb-3">
														<button class="btn btn-danger px-4" id="btnbackthree"><i
																class="fa fa-arrow-left mr-1"></i>Back</button>
													</div>
													<div class="col-12" id="divproductlist">
														<table class="table table-bordered table-striped table-sm"
															id="tableproductpricelist">
															<thead>
																<tr>
																	<th>#</th>
																	<th class="text-right d-none">PRODUCT ID</th>
																	<th>PRODUCT CODE</th>
																	<th>BARCODE</th>
																	<th>STOCK</th>
																	<th class="text-right d-none">UNIT PRICE</th>
																	<th class="text-small">SALE PRICE
																	</th>
																	<th class="d-none">MAX DISCOUNT</th>
																</tr>
															</thead>
															<tbody>
															</tbody>
														</table>
													</div>
												</div>
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
				<div class="card mt-2">
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-right">
								<button tabindex="1" id="paymentbtn" class="btn btn-success btn-sm"><i
										class="fas fa-cash-register fa-3x mr-2"></i>
									<h1 class="font-weight-normal mt-2 text-light">PAYMENT</h1>
								</button>
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
<!-- Modal Payment -->
<div class="modal fade" id="modalpayment" data-bs-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">PAYMENT</h5>
				<button type="button"class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
						<h3 class="display-4 text-right" id="htmlbillamount"></h3>
						<hr class="m-0">
						<form id="paymentform" autocomplete="off">
							<div class="form-group mb-1">
								<label class="small font-weight-bold text-dark">Bill Method</label>
								<select name="billtype" id="billtype" class="form-control">
									<option value="1" selected>Cash</option>
									<option value="2">Credit</option>
									<option value="3">Quotation</option>
								</select>
							</div>
							<div class="form-group mb-1">
								<div class="collapse" id="collapsecustomerinfo">
									<div class="card card-body shadow-none border-0 p-0">
										<div class="form-group mb-1">
											<label class="small font-weight-bold text-dark">Name</label>
											<input type="text" id="cusname" name="cusname" class="form-control">
										</div>
										<div class="form-group mb-1">
											<label class="small font-weight-bold text-dark">NIC</label>
											<input type="text" id="cusnic" name="cusnic" class="form-control">
										</div>
										<div class="form-group mb-1">
											<label class="small font-weight-bold text-dark">Mobile No</label>
											<input type="tel" id="cusmobile" name="cusmobile" class="form-control">
										</div>
									</div>
								</div>
							</div>
							<div class="form-group mb-1">
								<label class="small font-weight-bold text-dark">Payment Method</label>
								<select name="paymentmethod" id="paymentmethod" class="form-control">
									<option value="1" selected>Cash Payment</option>
									<option value="2" disabled>Credit Card Payment</option>
									<option value="3">Cheque Payment</option>
								</select>
							</div>
							<div class="form-group mb-1">
								<label class="small font-weight-bold text-dark">Cash / Cheque</label>
								<input type="tel" id="amount" name="amount" class="form-control" autofocus required>
							</div>
							<div class="form-group mb-1">
								<label class="small font-weight-bold text-dark">Bank Name</label>
								<input type="text" id="bank" name="bank" class="form-control" readonly>
							</div>
							<div class="form-group mb-1">
								<label class="small font-weight-bold text-dark">Branch</label>
								<input type="text" id="branch" name="branch" class="form-control" readonly>
							</div>
							<div class="form-group mb-1">
								<label class="small font-weight-bold text-dark">Cheque No</label>
								<input type="tel" id="chequeno" name="chequeno" class="form-control" readonly>
							</div>
							<div class="form-row mb-1">
								<div class="col">
									<label class="small font-weight-bold text-dark">Cheque Date</label>
									<input type="date" id="chequedate" name="chequedate" class="form-control" readonly>
								</div>
							</div>
							<div class="form-group mt-3 text-right">
								<button type="button" class="btn btn-danger" id="btnpayaddlist">ADD PAYMENT</button>
								<input type="submit" id="btnhidepayaddlist" class="d-none">
								<input type="reset" id="btnhidepayresetlist" class="d-none">
							</div>
						</form>
					</div>
					<div class="col-sm-12 col-md-12 col-lg-8 col-xl-8">
						<table class="table table-striped table-bordered" id="tablepayment">
							<thead>
								<tr>
									<th class="d-none">paymethod</th>
									<th>PAY METHOD</th>
									<th>BANK</th>
									<th>Branch</th>
									<th>CHEQUE NO</th>
									<th>CHEQUE DATE</th>
									<th class="d-none">total</th>
									<th class="text-right">TOTAL</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
						<hr>
						<h1 class="display-4 text-right" id="paynettotal"></h1>
						<h3 class="font-weight-normal text-right text-danger" id="paybalance"></h3>
						<input type="hidden" name="hidepaymenttotal" id="hidepaymenttotal" value="0">
						<input type="hidden" name="hidecustomerID" id="hidecustomerID" value="1">
						<hr>
						<button type="button" class="btn btn-secondary fa-pull-right" id="paymentcomplete" disabled><i
								class="fas fa-save mr-2"></i>DONE</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal Invoice Receipt -->
<div class="modal fade" id="modalinvoicereceipt" data-bs-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel"></h5>
				<button type="button"class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="viewreceiptprint"></div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-danger btn-sm fa-pull-right" id="btnreceiptprint"><i
						class="fas fa-print"></i>&nbsp;Print Receipt</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal Invoice Pos Receipt -->
<div class="modal fade" id="modalinvoicereceiptpos" data-bs-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel"></h5>
				<button type="button"class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="viewreceiptprintpos"></div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-danger btn-sm fa-pull-right" id="btnreceiptprintpos"><i
						class="fas fa-print"></i>&nbsp;Print Receipt</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal Retail Whole Sale -->
<div class="modal fade" id="modaltable" data-bs-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
					<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
						<div class="row row-cols-1 row-cols-md-3" id="btnpro">
							<?php
                                
                                foreach ($table->result() as $rowtable) {                                
                                ?>
							<div class="col mb-4 tablediv" tabindex="0"
								id="<?php echo $rowtable->idtbl_res_reservation_table ?>">
								<div class="card card-material h-100 shadow-none bg-secondary">
									<div class="card-body p-2 text-center pointer">
										<img src="<?php echo base_url('images/Items/table.png'); ?>" alt=""
											style="width: 110px; height: 70px;">
										<hr class="border-light my-1">
										<h4 class="text-light text-uppercase font-weight-bold" style="font-size:20px;">
											<?php echo $rowtable->table ?></h4>
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
<!-- Modal Approve Price Change -->
<div class="modal fade" id="modalapprovebill" data-bs-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Approve Bill</h5>
				<button type="button"class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
						<div id="errormsg"></div>
						<form id="formarrovebill" autocomplete="off">
							<div class="form-group mb-1">
								<label class="small font-weight-bold text-dark">Username*</label>
								<input type="text" name="approveusername" id="approveusername" class="form-control"
									required>
							</div>
							<div class="form-group mb-1">
								<label class="small font-weight-bold text-dark">Password*</label>
								<input type="password" name="approvepassword" id="approvepassword" class="form-control"
									required>
							</div>
							<div class="form-group mt-3 text-right">
								<button type='button' class="btn btn-danger" id="btnbillapprove">Approve Bill</button>
								<input type="submit" id="hideapprovesubmit" class="d-none">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal Retail Whole Sale -->
<div class="modal fade" id="modalorders" data-bs-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="staticBackdropLabel">Online Orders</h3>
				<button type="button"class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
						<table class="table">
							<thead class="table-primary">
								<tr>
									<th scope="col">#</th>
									<th scope="col">Customer</th>
									<th scope="col">Customer Contact</th>
									<th scope="col">Order Date</th>
									<th scope="col">Product</th>
									<th scope="col">Qunatity</th>
									<th scope="col" class="text-center">Actions</th>
								</tr>
							</thead>
							<tbody id="ordertable">
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal Cash Or Credit -->
<div class="modal fade" id="modalcashcredit" data-bs-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel"></h5>
				<button type="button"class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
						<button id="btncash" type="button" class="btn btn-primary btn-sm w-100 mb-3"><i
								class="fas fa-money-bill-alt fa-3x mr-2"></i>
							<h1 class="font-weight-normal mt-2 text-light">CASH</h1>
						</button>
					</div>
					<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
						<button id="btncredit" type="button" class="btn btn-danger btn-sm w-100"><i
								class="fas fa-credit-card fa-3x mr-2"></i>
							<h1 class="font-weight-normal mt-2 text-light">CARD</h1>
						</button>
					</div>
				</div>
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
			url: "<?php echo base_url() ?>Directsale/Getproductdetails",
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
     					url: '<?php echo base_url() ?>Directsale/Getonlineorders',
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
     					url: '<?php echo base_url() ?>Directsale/Getproductlist',
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
                    url: '<?php echo base_url() ?>Directsale/Getproductlistaccobarcode',
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
                url: '<?php echo base_url() ?>Directsale/Getproductlist',
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
        $(document).on("click", ".btnaddtocart", function () {

            $('#carttable > tbody').empty();

        	var productID = $(this).data('productid');
        	var product = $(this).data('product');
        	var qty = $(this).data('qty');
        	var sale = $(this).data('sale');
        	var discount = $(this).data('discount');

        	var finalsaleamount = sale;
        	var total = sale * qty;
        	total = parseFloat(total);
        	var totalwithdis = parseFloat(total - discount);
        	var showtotal = parseFloat(totalwithdis).toFixed(2);
        	var classname = '';
        	var editstatus = '0';

        	$('#carttable > tbody:last').append('<tr class="pointer ' + classname + '"><td>' + product + '</td><td class="text-center">' + qty + '</td><td class="text-right">' + parseFloat(finalsaleamount).toFixed(2) + '</td><td class="text-right"></td><td class="text-right">' + showtotal + '</td><td class="d-none">' + productID + '</td><td class="d-none">' + finalsaleamount + '</td><td class="total d-none">' + total + '</td><td class="d-none">' + discount + '</td><td class="distotal d-none">' + discount + '</td><td class="nettotal d-none">' + totalwithdis + '</td><td class="d-none">' + editstatus + '</td></tr>');

        	var sum = 0;
        	$(".total").each(function () {
        		sum += parseFloat($(this).text());
        	});

        	var showsum = parseFloat(sum).toFixed(2);

        	var dissum = 0;
        	$(".distotal").each(function () {
        		dissum += parseFloat($(this).text());
        	});

        	var showdissum = parseFloat(dissum).toFixed(2);

        	var netsum = 0;
        	$(".nettotal").each(function () {
        		netsum += parseFloat($(this).text());
        	});

        	var shownetsum = parseFloat(netsum).toFixed(2);

        	$('#labeltotal').html('Gross Amount: ' + showsum);
        	$('#labeldistotal').html('Discount: ' + showdissum);
        	$('#labelnettotal').html(shownetsum);
        	$('#htmlbillamount').html('Rs. ' + shownetsum);
        	$('#hiddenfulltotal').val(sum);
        	$('#hiddenfulldistotal').val(dissum);
        	$('#hiddenfullnettotal').val(netsum);
        	$('#amount').val(netsum);
        	$('#btnhideqtyreset').click();
        	$('#modalorders').modal('hide');

        });
        $("#btnaddtolist").click(function () {
        	if (!$("#formqtyadd")[0].checkValidity()) {
        		// If the form is invalid, submit it. The form won't actually submit;
        		// this will just cause the browser to display the native HTML5 error messages.
        		$("#btnhideqtysubmit").click();
        	} else {
        		var productID = $('#hideproductid').val();
                var tableID = $('#hidetableid').val();
        		var product = $('#hideproduct').val();
        		var productcode = $('#hideproductcode').val();
        		var unit = parseFloat($('#hideproductunit').val());
        		var sale = parseFloat($('#hideproductsale').val());
        		var qty = parseFloat($('#qtycount').val());
        		var salepriceedit = parseFloat($('#salepriceedit').val());
        		var discountpresentage = parseFloat($('#discountpresentage').val());

        		if (salepriceedit != sale) {
        			$('#priceeditstatus').val('1');

        			var finalsaleamount = salepriceedit;
        			var total = salepriceedit * qty;
        			var total = parseFloat(total);
        			var discountamount = parseFloat((total * discountpresentage) / 100);
        			var totalwithdis = parseFloat(total - discountamount);
        			var showtotal = parseFloat(totalwithdis).toFixed(2);
        			var classname = 'table-info';
        			var editstatus = '1';
        		} else {
        			var finalsaleamount = sale;
        			var total = sale * qty;
        			var total = parseFloat(total);
        			var discountamount = parseFloat((total * discountpresentage) / 100);
        			var totalwithdis = parseFloat(total - discountamount);
        			var showtotal = parseFloat(totalwithdis).toFixed(2);
        			var classname = '';
        			var editstatus = '0';
        		}

        		$('#carttable > tbody:last').append('<tr class="pointer ' + classname + '"><td>' + productcode + '</td><td class="text-center">' + qty + '</td><td class="text-right">' + parseFloat(finalsaleamount).toFixed(2) + '</td><td class="text-right">' + parseFloat(discountamount).toFixed(2) + '</td><td class="text-right">' + showtotal + '</td><td class="d-none">' + productID + '</td><td class="d-none">' + productcode + '</td><td class="d-none">' + finalsaleamount + '</td><td class="d-none">' + unit + '</td><td class="total d-none">' + total + '</td><td class="d-none">' + discountpresentage + '</td><td class="distotal d-none">' + discountamount + '</td><td class="nettotal d-none">' + totalwithdis + '</td><td class="d-none">' + editstatus + '</td></tr>');

        		var sum = 0;
        		$(".total").each(function () {
        			sum += parseFloat($(this).text());
        		});

        		var showsum = parseFloat(sum).toFixed(2);

        		var dissum = 0;
        		$(".distotal").each(function () {
        			dissum += parseFloat($(this).text());
        		});

        		var showdissum = parseFloat(dissum).toFixed(2);

        		var netsum = 0;
        		$(".nettotal").each(function () {
        			netsum += parseFloat($(this).text());
        		});

        		var shownetsum = parseFloat(netsum).toFixed(2);

        		$('#labeltotal').html('Gross Amount: ' + showsum);
        		$('#labeldistotal').html('Discount: ' + showdissum);
        		$('#labelnettotal').html(shownetsum);
        		$('#htmlbillamount').html('Rs. ' + shownetsum);
        		$('#hiddenfulltotal').val(sum);
        		$('#hiddenfulldistotal').val(dissum);
        		$('#hiddenfullnettotal').val(netsum);
                $('#amount').val(netsum);
        		$('#btnhideqtyreset').click();
        		$('#modalqty').modal('hide');
        		$("#collapseOne").collapse('show');

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
                            tableID: tableID // Add the tableID to the data object
                        },
                        url: '<?php echo base_url() ?>Directsale/Directsaletempinsertupdate',
                        success: function(result) {
                            // Handle the AJAX success response here
                        }
                    });
                }
        	}
        });

        $('.tablediv').click(function () {
        			var table = $(this).attr('id');
        			$('#hidetableid').val(table);
        			$('#modaltable').modal('hide');

        			$.ajax({
        				type: "POST",
        				data: {
        					tableID: table
        				},
        				url: '<?php echo base_url() ?>Directsale/Getaddeditems',
        				success: function (result) {
        					var items = JSON.parse(result);

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
        					for (var i = 0; i < items.length; i++) {
        						var item = items[i];
        						var className = item.billclose != 1 ? 'table-info' : '';
        						var showTotal = parseFloat(item.total).toFixed(2);

        						$('#carttable tbody').append('<tr class="pointer ' + className + '"><td>' + item.itemname + '</td><td class="text-center">' + item.qty + '</td><td class="text-right">' + parseFloat(item.saleprice).toFixed(2) + '</td><td class="text-right"></td><td class="total text-right">' + showTotal + '</td><td class="d-none">' + item.idtbl_res_item + '</td><td class="d-none">' + item.discountamount + '</td><td class="nettotal d-none">' + item.totalwithdis + '</td></tr>');
        					}

        					// Calculate and display the total amounts
        					var sum = 0;
        					$(".total").each(function () {
        						sum += parseFloat($(this).text());
        					});

        					var showsum = parseFloat(sum).toFixed(2);

        					var dissum = 0;
        					$(".distotal").each(function () {
        						dissum += parseFloat($(this).text());
        					});

        					var showdissum = parseFloat(dissum).toFixed(2);

        					var netsum = 0;
        					$(".nettotal").each(function () {
        						netsum += parseFloat($(this).text());
        					});

        					var shownetsum = parseFloat(netsum).toFixed(2);

                            $('#hiddenteminvoiceid').val(item.id);
        					$('#labeltotal').html('Gross Amount: ' + item.totalamount);
        					$('#labeldistotal').html('Discount: ' + item.discountamount);
        					$('#labelnettotal').html(item.totalwithdis);
        					$('#htmlbillamount').html('Rs. ' + item.totalwithdis);
        					$('#hiddenfulltotal').val(item.totalamount);
        					$('#hiddenfulldistotal').val(item.discountamount);
        					$('#hiddenfullnettotal').val(item.totalwithdis);
                            $('#amount').val(item.totalwithdis);
        					$('#btnhideqtyreset').click();
        					$('#modalqty').modal('hide');
        					$("#collapseOne").collapse('show');

        				}
        			});


        });

        $('#cartatble').on('click', 'tr', function () {
            var r = confirm("Are you sure, You want to remove this product ? ");
            if (r == true) {
                $(this).closest('tr').remove();

                var sum = 0;
                $(".total").each(function () {
                    sum += parseFloat($(this).text());
                });

                var showsum = addCommas(parseFloat(sum).toFixed(2));

                $('#labeltotal').html('Rs. ' + showsum);
                $('#hiddenfulltotal').val(sum);
                $('#btnhideqtyreset').click();
                $('#modalqty').modal('hide');
                $("#collapseOne").collapse('show');
            }
        });
        $('#paymentbtn').click(function(){
            $('#modalcashcredit').modal('show');
        });
        $('#btncash').click(function(){
            var fulltotal = $('#hiddenfullnettotal').val();
            $('#hidepaymenttotal').val(fulltotal);
            $('#tablepayment > tbody:last').append('<tr class="pointer"><td class="d-none">1</td><td>Cash</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td class="d-none paytotal">' + fulltotal + '</td><td class="text-right">' + parseFloat(fulltotal).toFixed(2) + '</td></tr>');
            createinvoice();
        });
        $('#btncredit').click(function(){
            $('#modalcashcredit').modal('hide');
            $('#modalpayment').modal('show');
            $('#modalpayment').on('shown.bs.modal', function () {
                $('#amount').focus();
            }) 
        });

        // Payment start
        $('#billtype').change(function() {
            if(this.value == '3'){
                $('#alreadyCustomerModal').modal('show');
                $('#collapsecustomerinfo').collapse('show');
                $('#amount').prop('readonly', true);
                $('#paymentcomplete').prop('disabled', false);
            }
            else if(this.value == '2'){
                $('#alreadyCustomerModal').modal('show');
                $('#collapsecustomerinfo').collapse('show');
            }
            else{
                $('#cusname').val('');
                $('#cusnic').val('');
                $('#cusmobile').val('');
                $('#collapsecustomerinfo').collapse('hide');
                $('#amount').focus();
            }
        });
        $('#paymentmethod').change(function() {
            if (this.value == '1') {
                $('#bank').prop('readonly', true).prop('required',false);
                $('#branch').prop('readonly', true).prop('required',false);
                $('#chequeno').prop('readonly', true).prop('required',false);
                $('#chequedate').prop('readonly', true).prop('required',false);
                $('#amount').focus();
            }
            else if (this.value == '3') {
                $('#bank').prop('readonly', false).prop('required',true);
                $('#branch').prop('readonly', false).prop('required',true);
                $('#chequeno').prop('readonly', false).prop('required',true);
                $('#chequedate').prop('readonly', false).prop('required',true);
            }
        });
        $('#amount').keypress(function (e) {
            var key = e.which;
            if(key == 13){
                var paymentmethod = $("#paymentmethod").val();
                if(paymentmethod<3){
                    $("#btnpayaddlist").click();
                    return false;  
                }
            }
        });
        $('#btnpayaddlist').click(function(){
            if (!$("#paymentform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#btnhidepayaddlist").click();
            } else {
                var amount=$('#amount').val();
                var bank=$('#bank').val();
                var branch=$('#branch').val();
                var chequeno=$('#chequeno').val();
                var chequedate=$('#chequedate').val();
                var paymentmethod = $("#paymentmethod").val();
                var billtype = $('#billtype').val();

                if(paymentmethod==1){var paymethod='Cash';}
                else if(paymentmethod==2){var paymethod='Credit Card';}
                else if(paymentmethod==3){var paymethod='Cheque';}


                $('#tablepayment > tbody:last').append('<tr class="pointer"><td class="d-none">' + paymentmethod + '</td><td>' + paymethod + '</td><td>' + bank + '</td><td>' + branch + '</td><td>' + chequeno + '</td><td>' + chequedate + '</td><td class="d-none paytotal">' + amount + '</td><td class="text-right">' + addCommas(parseFloat(amount).toFixed(2)) + '</td></tr>');

                var sum = 0;
                $(".paytotal").each(function () {
                    sum += parseFloat($(this).text());
                });

                var netbilltotal=parseFloat($('#hiddenfullnettotal').val());
                var showsum = addCommas(parseFloat(sum).toFixed(2));

                var baltotal = netbilltotal-sum;
                baltotal=addCommas(baltotal.toFixed(2));

                if(billtype==2){$('#paymentcomplete').prop('disabled', false);}
                else if(sum>=netbilltotal){$('#paymentcomplete').prop('disabled', false);}
                else{$('#paymentcomplete').prop('disabled', true);}

                $('#paynettotal').html('Rs. ' + showsum);
                $('#paybalance').html('Rs. ' + baltotal);
                $('#hidepaymenttotal').val(sum);
                if(billtype==1){$('#btnhidepayresetlist').click();}
                else if(billtype==2){
                    $('#amount').val('');
                    $('#bank').val('');
                    $('#branch').val('');
                    $('#chequeno').val('');
                    $('#chequedate').val('');
                }
                $("input[type=radio][name='paymentmethod']").prop('checked', false).parent().removeClass('active');
                $('#bank').prop('readonly', true).prop('required',false);
                $('#branch').prop('readonly', true).prop('required',false);
                $('#chequeno').prop('readonly', true).prop('required',false);
                $('#chequedate').prop('readonly', true).prop('required',false);
            }
        });
        $('#paymentcomplete').click(function(){            
                createinvoice();
        });
        
        // Payment end
         
        $('#alreadyCustomerTable tbody').on('click', 'tr', function() { //alert('IN');
            if ($(this).hasClass('table-primary')) {
                $(this).removeClass('table-primary');
            } else {
                dataTable.$('tr.table-primary').removeClass('table-primary');
                $(this).addClass('table-primary');

                var data = $('#alreadyCustomerTable').DataTable().row('.table-primary').data();
                // console.log(data);
                $('#hidecustomerID').val(data.idtbl_customer);
                $('#cusname').val(data.name);
                $('#cusnic').val(data.nicno);
                $('#cusmobile').val(data.contact);
                $('#alreadyCustomerModal').modal('hide');
            }
        });
        $('#btnAddToDB').click(function(){
            $('#alreadyCustomerModal').modal('hide');
            $('#cusname').focus();
        });
        document.getElementById('btnreceiptprint').addEventListener ("click", print);
        document.getElementById('btnreceiptprintpos').addEventListener ("click", printpos);
        $('#modalinvoicereceiptpos').on('hidden.bs.modal', function (e) {
            location.reload();
        });
        $('#modalinvoicereceipt').on('hidden.bs.modal', function (e) {
            location.reload();
        });
        $('#alreadyCustomerModal').on('hidden.bs.modal', function (e) {
            $('#cusname').focus();
        });

        $('#approveusername').keypress(function (e) {
            var key = e.which;
            if(key == 13){
                $('#approvepassword').focus();
                return false;  
            }
        });
        $('#approvepassword').keypress(function (e) {
            var key = e.which;
            if(key == 13){
                $('#btnbillapprove').click();
                return false;  
            }
        });
    });

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

    function createinvoice(){
        var tbody = $('#carttable tbody');
        if (tbody.children().length > 0) {
            jsonObj = []
            $("#carttable tbody tr").each(function () {
                item = {}
                $(this).find('td').each(function (col_idx) {
                    item["col_" + (col_idx + 1)] = $(this).text();
                });
                jsonObj.push(item);
            });
        }        
        console.log(jsonObj);

        var tbodysecond = $('#tablepayment tbody');
        jsonObjPay = []
        if (tbodysecond.children().length > 0) {
            $("#tablepayment tbody tr").each(function () {
                item = {}
                $(this).find('td').each(function (col_idx) {
                    item["col_" + (col_idx + 1)] = $(this).text();
                });
                jsonObjPay.push(item);
            });
        }        
        var total = $('#hiddenfulltotal').val();
        var distotal = $('#hiddenfulldistotal').val();
        var nettotal = $('#hiddenfullnettotal').val();
        var paytotal = $('#hidepaymenttotal').val();
        var billtype = $("#billtype").val();
        var cusname = $('#cusname').val();
        var cusnic = $('#cusnic').val();
        var cusmobile = $('#cusmobile').val();
        var cusID = $('#hidecustomerID').val();
        var saletype = $('#saletype').val();
        var priceeditstatus = $('#priceeditstatus').val();
        var billapproveuser = $('#hideapproveuser').val();
        var productid = $('#hideproductid').val();
        var teminvoiceid = $('#hiddenteminvoiceid').val();
        console.log(jsonObjPay);

        if(tbodysecond.children().length > 0 && billtype==1){var paystatus='1';}
        else if(tbodysecond.children().length > 0 && billtype==2){var paystatus='1';}
        else if(tbodysecond.children().length == 0 && billtype==2){var paystatus='1';}
        else if(tbodysecond.children().length == 0 && billtype==3){var paystatus='1';}
        else{var paystatus='0';}
        
        if(paystatus==1){
            $.ajax({
                type: "POST",
                data: {
                    tableData: jsonObj,
                    tableDataPay: jsonObjPay,
                    total: total,
                    distotal: distotal,
                    nettotal: nettotal,
                    paytotal: paytotal,
                    billtype: billtype,
                    cusname: cusname,
                    cusnic: cusnic,
                    cusmobile: cusmobile,
                    cusID: cusID,
                    saletype: saletype,
                    priceeditstatus: priceeditstatus,
                    productid: productid,
                    teminvoiceID: teminvoiceid,
                    billapproveuser: billapproveuser
                },
                url: '<?php echo base_url() ?>Directsale/Directsaleinsertupdate',
                success: function (result) { //alert(result);
                    // console.log(result);
                    var objfirst = JSON.parse(result);
                    if(objfirst.actiontype==1){
                        $('#modalpayment').modal('hide');
                        action(objfirst.action);
                            if(objfirst.billtype==1){
                                posprintbill(objfirst.invoiceid);
                            }
                            else{
                                creditprintbill(objfirst.invoiceid);
                            }                         
                    }
                    else{
                        action(objfirst.action);
                    }
                }
            });
        }
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

    function posprintbill(invoiceid){
        window.open('<?php echo base_url() ?>Directsale/Getposprintbill/'+invoiceid, "_blank");
        setTimeout(window.location.reload(), 3000);


    }
    function creditprintbill(invoiceid){
        window.open('<?php echo base_url() ?>Directsale/Getcreditprintbill/'+invoiceid, "_blank");
        setTimeout(window.location.reload(), 3000);
    }
    function print() {
        printJS({
            printable: 'viewreceiptprint',
            type: 'html',
            style: '@page { size: A5 portrait; margin:0.25cm; }',
            targetStyles: ['*']
        })
    }
    function printpos() {
        printJS({
            printable: 'viewreceiptprintpos',
            type: 'html',
            // style: '@page { size: A5 portrait; margin:0.25cm; }',
            targetStyles: ['*']
        })
    }
</script>

<?php include "include/footer.php"; ?>
