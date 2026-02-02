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
                            <div class="page-header-icon"><i class="fas fa-credit-card"></i></div>
                            <span>Supplier Payment</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
            	<div class="card">
            		<div class="card-body p-0 p-2">
            			<div class="row mt-2">
            				<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            					<div class="card-deck">
            						<div
            							class="card col-sm-12 border-secondary col-md-12 col-lg-12 col-xl-12 bg-transparent p-0">
            							<div class="card-body p-0 p-3">
            								<div class="row">
            									<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
            										<div class="form-group">
            											<select
            												class="form-control form-control-sm bg-transparent border-secondary"
            												id="formSupplier">
            												<option value="">Select Supplier</option>
            												<?php foreach($supplierlist->result() as $rowsupplierlist){ ?>
                                                            <option value="<?php echo $rowsupplierlist->idtbl_supplier ?>"><?php echo $rowsupplierlist->suppliername.'-'.$rowsupplierlist->suppliercode ?></option>
                                                            <?php } ?>
            											</select>
            										</div>
            									</div>
            									<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
            										<div class="input-group input-group-sm">
            											<input type="text"
            												class="form-control bg-transparent border-secondary"
            												placeholder="GRN Number" aria-label="GRN Number"
            												aria-describedby="button-addon2" id="formGRNNum">
            											<div class="input-group-append">
            												<button class="btn btn-outline-secondary" type="button"
            													id="formSearchBtn"><i
            														class="fas fa-search"></i>&nbsp;Search</button>
            											</div>
            										</div>
            									</div>
            								</div>
            								<div class="row">
            									<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            										<hr class="border-secondary m-0 p-0 mb-3">
            									</div>
            								</div>
            								<div class="row">
            									<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            										<div id="divgrndetail"></div>
            									</div>
            								</div>
            							</div>
            							<div class="card-footer">
            								<div class="row">
            									<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" align="right">
            										<button type="button" class="btn btn-outline-dark btn-sm mr-1"
            											id="invPaymentCheckBtn" disabled><i
            												class="fas fa-tasks"></i>&nbsp;Check Payment</button>
            										<button class="btn btn-outline-danger btn-sm" disabled
            											id="invPaymentCreateBtn">Create Payment Receipt</button>
            									</div>
            								</div>
            							</div>
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
<!--Issue Payment Receipt Modal-->
<div class="modal fade" id="printViewModal" tabindex="-1" role="dialog" aria-labelledby="oLevel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content bg-light">
            <div class="modal-header p-0 p-2">
                <h5 class="modal-title" id="oLevelTitle">Issue Payment Receipt</h5>
                <button type="button"class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                        <form id="formModal">
                            <div class="form-group">
                                <input id="paymentPayAmount" name="paymentPayAmount" type="text" class="form-control form-control-sm bg-transparent border-secondary" placeholder="Total Amount" readonly>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="paymentMethod1" name="paymentMethod" class="custom-control-input" value="1">
                                    <label class="custom-control-label" for="paymentMethod1">Cash Advance</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <input id="paymentCash" name="paymentCash" type="text" class="form-control form-control-sm bg-transparent border-secondary" placeholder="Cash Advance" required readonly>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="paymentMethod2" name="paymentMethod" class="custom-control-input" value="2">
                                    <label class="custom-control-label" for="paymentMethod2">Cheque Advance</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <input id="paymentCheque" name="paymentCheque" type="text" class="form-control form-control-sm bg-transparent border-secondary" placeholder="Cheque Advance" required readonly>
                            </div>
                            <div class="form-group">
                                <input id="paymentChequeNum" name="paymentChequeNum" type="text" class="form-control form-control-sm bg-transparent border-secondary" placeholder="Cheque Number" readonly>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <input type="text" class="form-control bg-transparent border-secondary dpd1a" placeholder="Cheque Date" name="paymentchequeDate" id="paymentchequeDate" readonly>
                                <div class="input-group-append">
                                    <span class="btn btn-outline-secondary"><i class="far fa-calendar"></i></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <input id="paymentBank" name="paymentBank" type="text" class="form-control form-control-sm bg-transparent border-secondary" placeholder="Bank Name" readonly>
                            </div>
                            <div class="form-group" align="right">
                                <button name="submitBtnModal" type="button" id="submitBtnModal" class="btn btn-outline-secondary btn-sm"><i class="fas fa-file-invoice-dollar"></i>&nbsp;Add Payment</button>
                                <input type="submit" class="d-none" id="hideSubmitModal">
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">
                        <table class="table table-bordered table-sm table-striped" id="tblPaymentTypeModal">
                            <thead>
                                <th>Type</th>
                                <th>Cash</th>
                                <th>Cheque</th>
                                <th>Che_Num</th>
                                <th>Che_Date</th>
                                <th>Bank</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="row">
                            <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 text-right">Total Amount :</div>
                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 text-right">
                                <div id="totAmount"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 text-right">Pay Amount :</div>
                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 text-right">
                                <div id="payAmount"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 text-right">&nbsp;</div>
                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 text-right">
                                <hr class="border-secondary">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 text-right">Balance :</div>
                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 text-right">
                                <div id="balanceAmount"></div>
                            </div>
                        </div>
                        <input type="hidden" id="supplierId" name="supplierId">
                        <input type="hidden" id="grnId" name="grnId">
                        <input type="hidden" id="hidePayAmount" value="0"><br>
                        <input type="hidden" id="hideBalAmount" value="0"><br>
                        <input type="hidden" id="hideAllBalAmount" value="0">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" align="right">
                        <button class="btn btn-outline-danger btn-sm" id="btnIssueInv" disabled><i class="fas fa-file-pdf"></i>&nbsp;Issue Payment Receipt</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--view & Print Payment Receipt Modal-->
<div class="modal fade" id="viewPaymentInvModal" tabindex="-1" role="dialog" aria-labelledby="oLevel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content bg-light">
        <div class="modal-header p-0 p-2">
                <h5 class="modal-title" id="titlePaymentView">Payment Receipt / Invoice View & Print</h5>
                <button type="button"class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modal-body-print">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="embed-responsive embed-responsive-16by9 embed-responsive-print">
                            <iframe class="embed-responsive-item  d-flex align-items-center justify-content-center" frameborder="0" id="print"></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="print()" type="button" id="formsubmit" class="btn btn-primary btn-sm ml-3 mt-2 px-4 text-right">
                    <i class="fas fa-save"></i>&nbsp;Print
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Alert -->
<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content bg-danger">
            <div class="modal-body text-white">
                <div class="row">
                    <div class="col" id="bodyAlert"></div>
                </div>
                <button type="button" class="btn btn-outline-light btn-sm fa-pull-right pl-4 pr-4" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
        function print() {
        printJS({
            printable: 'print',
            type: 'html',
            style: '@page { size: A4; margin:0.25cm;}',
            targetStyles: ['*']
        })
    }
    $(document).ready(function() {

        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });
        $('#formSupplier').change(function() {
            var selectedSupplierId = $(this).val();
            $('#supplierId').val(selectedSupplierId);
        });
        $('#formSupplier').change(function() {

            var supplierID = $('#formSupplier').val();

            if (supplierID != '') {
                $('#divgrndetail').html('<div class="card border-0"><div class="card-body text-center"><i class="fas fa-spinner fa-spin fa-4x"></i></div></div>');

                $.ajax({
                    type: "POST",
                    data: {
                        supplierID: supplierID
                    },
                    url: '<?php echo base_url() ?>Supplierpayment/Getgrndetails',
                    success: function(result) { //alert(result);
                        var html = '';

                        html += '<table id="paymentDetailTable" class="table table-secondary border-secondary table-bordered table-sm"><thead><tr><th width="16%">GRN Number</th><th width="12%">Date</th><th width="12%">Amount</th><th width="10%">Paid Amount</th><th width="10%">Balance</th><th width="12%">Full Payment</th><th width="12%">Part Payment</th><th width="16%">Payment</th></tr></thead>';
                        var objTable = JSON.parse(result);
                        $.each(objTable, function(i, item) {
                            var paidAmount = 0;

                            if (objTable[i].grnPaid != '0') {
                                paidAmount = objTable[i].grnPaid;
                            } else {
                                paidAmount = 0;
                            }

                            html += "<tr>";
                            html += "<td>";
                            html += objTable[i].grnID;
                            html += "</td>";
                            html += "<td>";
                            html += objTable[i].grnDate;
                            html += "</td>";
                            html += "<td>";
                            html += parseFloat(objTable[i].grnNet).toFixed(2);
                            html += "</td>";
                            html += "<td>";
                            html += parseFloat(paidAmount).toFixed(2);
                            html += "</td>";
                            html += "<td>";
                            html += parseFloat(objTable[i].grnBal).toFixed(2);
                            html += "</td>";
                            html += '<td><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input fullAmount" name="payCheck1" id="payCheck1' + objTable[i].grnID + '" value="1"';
                            if (paidAmount > 0) {
                                html += 'disabled'
                            }
                            html += '><label class="custom-control-label small" for="payCheck1' + objTable[i].grnID + '">Full Payment</label></div></td>';
                            html += '<td><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input halfAmount" name="payCheck2" id="payCheck2' + objTable[i].grnID + '"><label class="custom-control-label small" for="payCheck2' + objTable[i].grnID + '">Part Payment</label></div></td>';
                            html += "<td class='paidAmount'>0.00</td>";
                            html += "</tr>";

                        });
                        html += '<tbody id="tbodyPaymentDeatil"></tbody></table>';

                        $('#divgrndetail').html(html);
                        tblcheckboxevent();
                        $('#invPaymentCheckBtn').prop("disabled", false);
                    }
                });
            }
        });
        $('#formSearchBtn').click(function(){
            
            var grnID = $('#formGRNNum').val();
            
            $('#divgrndetail').html('<div class="card border-0"><div class="card-body text-center"><i class="fas fa-spinner fa-spin fa-4x"></i></div></div>');

            $.ajax({
                type: "POST",
                data: {
                    grnID: grnID
                },
                url: '<?php echo base_url() ?>Supplierpayment/Getgrndetails',
                success: function(result) { //alert(result);
                    var html = '';

                        html += '<table id="paymentDetailTable" class="table table-secondary border-secondary table-bordered table-sm"><thead><tr><th width="16%">GRN Number</th><th width="12%">Date</th><th width="12%">Amount</th><th width="10%">Paid Amount</th><th width="10%">Balance</th><th width="12%">Full Payment</th><th width="12%">Part Payment</th><th width="16%">Payment</th></tr></thead>';
                        var objTable = JSON.parse(result);
                        $.each(objTable, function(i, item) {
                            var paidAmount = 0;

                            if (objTable[i].grnPaid != '0') {
                                paidAmount = objTable[i].grnPaid;
                            } else {
                                paidAmount = 0;
                            }

                            html += "<tr>";
                            html += "<td>";
                            html += objTable[i].grnID;
                            html += "</td>";
                            html += "<td>";
                            html += objTable[i].grnDate;
                            html += "</td>";
                            html += "<td>";
                            html += parseFloat(objTable[i].grnNet).toFixed(2);
                            html += "</td>";
                            html += "<td>";
                            html += parseFloat(paidAmount).toFixed(2);
                            html += "</td>";
                            html += "<td>";
                            html += parseFloat(objTable[i].grnBal).toFixed(2);
                            html += "</td>";
                            html += '<td><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input fullAmount" name="payCheck1" id="payCheck1' + objTable[i].grnID + '" value="1"';
                            if (paidAmount > 0) {
                                html += 'disabled'
                            }
                            html += '><label class="custom-control-label small" for="payCheck1' + objTable[i].grnID + '">Full Payment</label></div></td>';
                            html += '<td><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input halfAmount" name="payCheck2" id="payCheck2' + objTable[i].grnID + '"><label class="custom-control-label small" for="payCheck2' + objTable[i].grnID + '">Part Payment</label></div></td>';
                            html += "<td class='paidAmount'>0.00</td>";
                            html += "</tr>";

                        });
                        html += '<tbody id="tbodyPaymentDeatil"></tbody></table>';

                        $('#divgrndetail').html(html);
                        tblcheckboxevent();
                        $('#invPaymentCheckBtn').prop("disabled", false);
                }
            });
        });
        $('#invPaymentCreateBtn').click(function() {
            var table = $("#paymentDetailTable tbody");
            var total = '0';
            var invnetBal = '0';
            var invBal = '0';
            table.find('tr').each(function(i, el) {
                var $tds = $(this).find('td');
                var value = parseFloat($tds.eq(7).text());

                total = parseFloat(total);
                total = (total + value);

                var bal = parseFloat($tds.eq(4).text());
                invBal = parseFloat(invBal);
                invBal = (invBal + invBal);
            });

            total = parseFloat(total).toFixed(2);
            invBal = parseFloat(invBal).toFixed(2);
            invnetBal = parseFloat(total + invBal);

            if (invnetBal == '0.00') {
                $('#bodyAlert').html('<i class="fas fa-exclamation-triangle fa-pull-left fa-3x"></i><p>Please enter the payment value and press the create payment button</p>');
                $('#alertModal').modal({
                    keyboard: false,
                    backdrop: 'static'
                });
                $('#invPaymentCreateBtn').prop("disabled", true);
            } else {
                $('#paymentPayAmount').val(invnetBal);
                $('#totAmount').html(invnetBal);
                $('#hideAllBalAmount').val(invnetBal);
                $('#printViewModal').modal({
                    keyboard: false,
                    backdrop: 'static'
                });
            }
        });
        $('#invPaymentCheckBtn').click(function() {
            var promise = tblTextRemove();

            promise.then(function() {
                var alertinfo = 0;
                var table = $("#paymentDetailTable tbody");
                table.find('tr').each(function(i, el) {
                    var row = $(this);
                    var $tds = $(this).find('td');
                    var value = $tds.eq(7).text();

                    if (value == '') {
                        alertinfo = 1;
                    }
                });

                if (alertinfo == 1) {
                    $('#bodyAlert').html('<i class="fas fa-exclamation-triangle fa-pull-left fa-3x"></i><p>Please enter the payment value or uncheck full payment | halfpayment check box.</p>');
                    $('#alertModal').modal({
                        keyboard: false,
                        backdrop: 'static'
                    });
                    $('#invPaymentCreateBtn').prop("disabled", true);
                } else {
                    $('#invPaymentCreateBtn').prop("disabled", false);
                }
            });

        });

        $("#submitBtnModal").click(function() {
            if (!$("#formModal")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hideSubmitModal").click();
            } else {
                var html = '';

                var obj = $('#formModal').serializeJSON();
                var jsonString = JSON.stringify(obj);

                var objfirst = JSON.parse(jsonString);

                html += '<tr>';
                if (objfirst.paymentMethod == '1') {
                    html += '<td>Cash</td>';
                    var paidAmount = parseFloat($('#hidePayAmount').val());
                    var PayAmount = parseFloat(objfirst.paymentCash);
                    var paymentPayAmount = parseFloat($('#hideAllBalAmount').val());

                    paidAmount = (paidAmount + PayAmount);
                    var balance = (paymentPayAmount - paidAmount);
                    $('#hideBalAmount').val(balance);
                    $('#balanceAmount').html((balance).toFixed(2));
                    $('#payAmount').html((paidAmount).toFixed(2));
                    $('#hidePayAmount').val(paidAmount);
                } else {
                    html += '<td>Cheque</td>';
                    var paidAmount = parseFloat($('#hidePayAmount').val());
                    var PayAmount = parseFloat(objfirst.paymentCheque);
                    var paymentPayAmount = parseFloat($('#hideAllBalAmount').val());

                    paidAmount = (paidAmount + PayAmount);
                    var balance = (paymentPayAmount - paidAmount);
                    $('#hideBalAmount').val(balance);
                    $('#balanceAmount').html((balance).toFixed(2));
                    $('#payAmount').html((paidAmount).toFixed(2));
                    $('#hidePayAmount').val(paidAmount);
                }
                html += '<td>';
                html += objfirst.paymentCash;
                html += '</td>';
                html += '<td>';
                html += objfirst.paymentCheque;
                html += '</td>';
                html += '<td>';
                html += objfirst.paymentChequeNum;
                html += '</td>';
                html += '<td>';
                html += objfirst.paymentchequeDate;
                html += '</td>';
                html += '<td>';
                html += objfirst.paymentBank;
                html += '</td>';
                html += '</tr>';

                $('#tblPaymentTypeModal > tbody:last').append(html);
                clearField();
                $('#btnIssueInv').prop("disabled", false);
            }
        });
        $('input[type=radio][name=paymentMethod]').change(function() {
            if (this.value == '1') {
                $('#paymentCheque').prop("readonly", true);
                $('#paymentChequeNum').prop("readonly", true);
                $('#paymentchequeDate').prop("readonly", true);
                $('#paymentBank').prop("readonly", true);
                $('#paymentBank').prop("readonly", true);
                $('#paymentCash').prop("readonly", false);
            } else {
                $('#paymentCheque').prop("readonly", false);
                $('#paymentChequeNum').prop("readonly", false);
                $('#paymentchequeDate').prop("readonly", false);
                $('#paymentBank').prop("readonly", false);
                $('#paymentCash').prop("readonly", true);
            }
        });

        $('#btnIssueInv').click(function () {
    var tbody = $("#tblPaymentTypeModal tbody");

    if (tbody.children().length > 0) {
        jsonObj = [];
        $("#tblPaymentTypeModal tbody tr").each(function () {
            item = {}
            $(this).find('td').each(function (col_idx) {
                item["col_" + (col_idx + 1)] = $(this).text();
            });
            jsonObj.push(item);
        });

        // Retrieve selected data from another table
        var grnTableData = [];
        $("#paymentDetailTable tbody tr").each(function () {
            var checkbox = $(this).find('input[type="checkbox"]');
            if (checkbox.is(":checked")) {
                var item = {};
                $(this).find('td').each(function (col_idx) {
                    item["another_col_" + (col_idx + 1)] = $(this).text();
                });
                grnTableData.push(item);
            }
        });

        // Combine the data into jsonObj
        jsonObj = jsonObj.concat(grnTableData);

        console.log(jsonObj);

        var totAmount = $('#paymentPayAmount').val();
        var payAmount = $('#hidePayAmount').val();
        var balAmount = $('#hideAllBalAmount').val();
        var SupplierID = $('#supplierId').val();
        var grnID = $('#grnId').val();

        $.ajax({
            type: "POST",
            data: {
                tableData: JSON.stringify(jsonObj),
                totAmount: totAmount,
                payAmount: payAmount,
                SupplierID: SupplierID,
                grnID: grnID,
                balAmount: balAmount
            },
            url: '<?php echo base_url() ?>Supplierpayment/Paymentinsertupdate',
            success: function (result) {
                if (result != '0') {
                    var objfirst = JSON.parse(result);
                    if (objfirst.actiontype == 1) {
                        $('#printViewModal').modal('hide');
                        steptwo(objfirst.grnid, objfirst.supplierid); // Pass both grnid and supplierid
                    } else {
                        // Handle the action when actiontype is not 1
                    }
                }
            }
        });
    }
});
        $('#viewPaymentInvModal').on('hidden.bs.modal', function() {
            location.reload();
        });

    });

    function tblpayamount() {
        $('.paidAmount').click(function(e) {
            if (($(this).closest('tr')).find('td:eq(6) .halfAmount').is(':checked')) {
                e.preventDefault();
                e.stopImmediatePropagation();

                $this = $(this);
                if ($this.data('editing')) return;

                var val = $this.text();

                $this.empty();
                $this.data('editing', true);

                $('<input type="Text" class="form-control form-control-sm editfieldpay">').val(val).appendTo($this);
            }
        });
        putOldValueBack = function() {
            $('.editfieldpay').each(function() {
                $this = $(this);
                var val = $this.val();
                var td = $this.closest('td');
                td.empty().html(val).data('editing', false);
            });
        }
        $(document).click(function(e) {
            putOldValueBack();
        });
    }

    function tblcheckboxevent() {
        $('#paymentDetailTable tbody').on('click', '.fullAmount', function() {
            var row = $(this);
            if ((row.closest('.fullAmount')).is(':checked')) {
                var fullAmount = row.closest("tr").find('td:eq(2)').text();
                row.closest("tr").find('td:eq(7)').text(fullAmount);
            } else {
                row.closest("tr").find('td:eq(7)').text('0.00');
            }
        });

        $('#paymentDetailTable tbody').on('click', '.halfAmount', function() {
            var row = $(this);
            if ((row.closest('.halfAmount')).is(':checked')) {
                tblpayamount();
            } else {
                tblTextRemove();
                var grnId = row.find('td:eq(0)').text(); // Get the value from td:eq(0)
                $('#grnId').val(grnId);
                row.closest("tr").find('td:eq(7)').text('0.00');
            }
        });
    }

    function tblTextRemove() {
        $('#paymentDetailTable .editfield').each(function() {
            $this = $(this);
            var val = $this.val();
            var td = $this.closest('td');
            td.empty().html(val).data('editing', false);
        });

        var deferred = $.Deferred();

        setTimeout(function() {
            // completes status
            deferred.resolve();
        }, 1000);

        // returns complete status
        return deferred.promise();
    }

    (function($) {
        'use strict';

        $.fn.tableToJSON = function(opts) {

            // Set options
            var defaults = {
                ignoreColumns: [],
                onlyColumns: null,
                ignoreHiddenRows: true,
                headings: null,
                allowHTML: false
            };
            opts = $.extend(defaults, opts);

            var notNull = function(value) {
                return value !== undefined && value !== null;
            };

            var ignoredColumn = function(index) {
                if (notNull(opts.onlyColumns)) {
                    return $.inArray(index, opts.onlyColumns) === -1;
                }
                return $.inArray(index, opts.ignoreColumns) !== -1;
            };

            var arraysToHash = function(keys, values) {
                var result = {},
                    index = 0;
                $.each(values, function(i, value) {
                    // when ignoring columns, the header option still starts
                    // with the first defined column
                    if (index < keys.length && notNull(value)) {
                        result[keys[index]] = value;
                        index++;
                    }
                });
                return result;
            };

            var cellValues = function(cellIndex, cell) {
                var value, result;
                if (!ignoredColumn(cellIndex)) {
                    var override = $(cell).data('override');
                    if (opts.allowHTML) {
                        value = $.trim($(cell).html());
                    } else {
                        value = $.trim($(cell).text());
                    }
                    result = notNull(override) ? override : value;
                }
                return result;
            };

            var rowValues = function(row) {
                var result = [];
                $(row).children('td,th').each(function(cellIndex, cell) {
                    if (!ignoredColumn(cellIndex)) {
                        result.push(cellValues(cellIndex, cell));
                    }
                });
                return result;
            };

            var getHeadings = function(table) {
                var firstRow = table.find('tr:first').first();
                return notNull(opts.headings) ? opts.headings : rowValues(firstRow);
            };

            var construct = function(table, headings) {
                var i, j, len, len2, txt, $row, $cell,
                    tmpArray = [],
                    cellIndex = 0,
                    result = [];
                table.children('tbody,*').children('tr').each(function(rowIndex, row) {
                    if (rowIndex > 0 || notNull(opts.headings)) {
                        $row = $(row);
                        if ($row.is(':visible') || !opts.ignoreHiddenRows) {
                            if (!tmpArray[rowIndex]) {
                                tmpArray[rowIndex] = [];
                            }
                            cellIndex = 0;
                            $row.children().each(function() {
                                if (!ignoredColumn(cellIndex)) {
                                    $cell = $(this);

                                    // process rowspans
                                    if ($cell.filter('[rowspan]').length) {
                                        len = parseInt($cell.attr('rowspan'), 10) - 1;
                                        txt = cellValues(cellIndex, $cell, []);
                                        for (i = 1; i <= len; i++) {
                                            if (!tmpArray[rowIndex + i]) {
                                                tmpArray[rowIndex + i] = [];
                                            }
                                            tmpArray[rowIndex + i][cellIndex] = txt;
                                        }
                                    }
                                    // process colspans
                                    if ($cell.filter('[colspan]').length) {
                                        len = parseInt($cell.attr('colspan'), 10) - 1;
                                        txt = cellValues(cellIndex, $cell, []);
                                        for (i = 1; i <= len; i++) {
                                            // cell has both col and row spans
                                            if ($cell.filter('[rowspan]').length) {
                                                len2 = parseInt($cell.attr('rowspan'), 10);
                                                for (j = 0; j < len2; j++) {
                                                    tmpArray[rowIndex + j][cellIndex + i] = txt;
                                                }
                                            } else {
                                                tmpArray[rowIndex][cellIndex + i] = txt;
                                            }
                                        }
                                    }
                                    // skip column if already defined
                                    while (tmpArray[rowIndex][cellIndex]) {
                                        cellIndex++;
                                    }
                                    if (!ignoredColumn(cellIndex)) {
                                        txt = tmpArray[rowIndex][cellIndex] || cellValues(cellIndex, $cell, []);
                                        if (notNull(txt)) {
                                            tmpArray[rowIndex][cellIndex] = txt;
                                        }
                                    }
                                }
                                cellIndex++;
                            });
                        }
                    }
                });
                $.each(tmpArray, function(i, row) {
                    if (notNull(row)) {
                        txt = arraysToHash(headings, row);
                        result[result.length] = txt;
                    }
                });
                return result;
            };

            // Run
            var headings = getHeadings(this);
            return construct(this, headings);
        };
    })(jQuery);

    function getInvDetailTableData() {
        var table = $('#paymentDetailTable').tableToJSON();
        console.log(table);
        var json = JSON.stringify(table);
        return json;
    };

    // function getPayDetailTableData() {
    //     var table = $('#tblPaymentTypeModal').tableToJSON();
    //     console.log(table);
    //     var json = JSON.stringify(table);
    //     return json;
    // };

    function clearField() {
        $('#paymentCheque').val('');
        $('#paymentChequeNum').val('');
        $('#paymentchequeDate').val('');
        $('#paymentBank').val('');
        $('#paymentBank').val('');
        $('#paymentCash').val('');
    }

    function steptwo(grnid, supplierid) {
    var recordID = grnid;
    var supplierID = supplierid;
    var src = '<?php echo base_url() ?>Supplierpayment/Getpdf/' + recordID + '/' + supplierID;

        //            alert(src);
        var width = $(this).attr('data-width') || 640; // larghezza dell'iframe se non impostato usa 640
        var height = $(this).attr('data-height') || 360; // altezza dell'iframe se non impostato usa 360

        var allowfullscreen = $(this).attr('data-video-fullscreen'); // impostiamo sul bottone l'attributo allowfullscreen se è un video per permettere di passare alla modalità tutto schermo

        // stampiamo i nostri dati nell'iframe
        $("#viewPaymentInvModal iframe").attr({
            'src': src,
            'height': height,
            'width': width,
            'allowfullscreen': ''
        });

        $('#viewPaymentInvModal').modal({
            keyboard: false,
            backdrop: 'static'
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
