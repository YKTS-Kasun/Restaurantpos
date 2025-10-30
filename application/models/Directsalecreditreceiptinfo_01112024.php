<?php
class Directsalecreditreceiptinfo extends CI_Model{
    public function Getcreditprintbill($x){

    $recordID=$x;
    $cashier=$_SESSION['name'];

    $sqlinvoiceinfo="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`invdate`, `tbl_invoice`.`grosstotal`, `tbl_invoice`.`discount`, `tbl_invoice`.`nettotal`, `tbl_invoice`.`servicecharge`, `tbl_invoice`.`paymentcomplete`, `tbl_res_customer`.`firstname`, `tbl_res_customer`.`lastname`, `tbl_res_customer`.`address1`, `tbl_res_customer`.`address2` FROM `tbl_invoice` LEFT JOIN `tbl_res_order` ON `tbl_res_order`.`idtbl_res_order`=`tbl_invoice`.`orderid`  LEFT JOIN `tbl_res_customer` ON `tbl_res_customer`.`idtbl_res_customer`=`tbl_res_order`.`tbl_res_customer_idtbl_res_customer` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`idtbl_invoice`=?";
    $invoiceinforespond=$this->db->query($sqlinvoiceinfo, array($recordID));
    $invdate=$invoiceinforespond->row(0)->invdate;
    $invid=$invoiceinforespond->row(0)->idtbl_invoice;
    $name=$invoiceinforespond->row(0)->firstname.$invoiceinforespond->row(0)->lastname;
    $address=$invoiceinforespond->row(0)->address1;
    $grosstotal=$invoiceinforespond->row(0)->grosstotal;
    $discount=$invoiceinforespond->row(0)->discount;
    $nettotal=$invoiceinforespond->row(0)->nettotal;
	$servicecharge=$invoiceinforespond->row(0)->servicecharge;

    $tblinvoice='';

    $sqlproduct="SELECT `tbl_res_item`.`itemname`, `tbl_invoice_detail`.`qty`, `tbl_invoice_detail`.`saleprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_res_item` ON `tbl_res_item`.`idtbl_res_item`=`tbl_invoice_detail`.`tbl_res_item_idtbl_res_item` WHERE `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`=? AND `tbl_invoice_detail`.`status`=?";
    $productrespond=$this->db->query($sqlproduct, array($recordID, 1));

    $qty=$productrespond->row(0)->qty;
    $saleprice=$productrespond->row(0)->saleprice;

    $sqlpayment="SELECT SUM(`nettotal`) AS `sumpayment`, SUM(`payment`) AS `payment`, SUM(`balance`) AS `balance` FROM `tbl_invoice_payment` LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_payment_idtbl_invoice_payment`=`tbl_invoice_payment`.`idtbl_invoice_payment` WHERE `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice`=?";
    $paymentrespond=$this->db->query($sqlpayment, array($recordID));

    if(empty($paymentrespond)){
		$totalpay=0;
		$payment=0;
		$balance=0;
	}
    else{
		$totalpay=$paymentrespond->row(0)->sumpayment;
		$payment=$paymentrespond->row(0)->payment;
		$balance=$paymentrespond->row(0)->balance;
	}


    foreach($productrespond->result() as $rowlist){
        $tblinvoice.='
		<tr>
			<td style="font-size:14px;text-align: left;" colspan="4">'.$rowlist->itemname.'</td>
		</tr>
        <tr>
            <td style="font-size:14px;">&nbsp;</td>
            <td style="font-size:14px;text-align: right;">'.number_format(($rowlist->saleprice), 2).'</td>
            <td style="font-size:14px;text-align: center;">'.$rowlist->qty.'</td>
            <td style="font-size:14px;text-align: right;" class="totalrawcost">'.number_format(($rowlist->qty * $rowlist->saleprice), 2).'</td>             
         </tr>
        
        ';
    }

    $sqlinvoicedetail="SELECT `tbl_res_item`.`itemname`, `tbl_invoice_detail`.`qty`, `tbl_invoice_detail`.`saleprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_res_item` ON `tbl_res_item`.`idtbl_res_item`=`tbl_invoice_detail`.`tbl_res_item_idtbl_res_item` WHERE `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`=? AND `tbl_invoice_detail`.`status`=?";
    $respond2=$this->db->query($sqlinvoicedetail, array($recordID, 1));

$html='';

$html ='

<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">
    <title>Invoice</title>
    <style media="print">
        * {
            font-family: "Fira Sans", sans-serif;
        }
        table,tr,th,td{
            font-family: "Fira Sans", sans-serif;
        }
        img{
            width:200px;
            height:100px;
        }
    </style>
    <style>
        * {
            font-family: "Fira Sans", sans-serif;
        }
        table,tr,th,td{
            font-family: "Fira Sans", sans-serif;
        }
        img{
            width:100px;
            height:100px;
        }
    </style>
</head>

<body>
	<div id="DivIdToPrint">
		<!-- <img src="'.base_url().'images/logo.jpg" alt="" style="width:100%; height:120px;"> -->
		<table style="width:100%;">
			<tr>
				<td colspan="2" style="text-align: center;font-size:12px;">
					<h2 style="margin-top: 0px;margin-bottom: 0px;">CROWN Restaurant</h2>
					No 37, Negombo Road,<br>Kandana, Sri Lanka
				</td>
			</tr>
			<tr>
				<td style="text-align: left; font-size:14px;">Date</td>
				<td style="text-align: right; font-size:14px;">'.$invdate.'</td>
			</tr>
			<tr>
				<td style="text-align: left; font-size:14px;">Invoice No.</td>
				<td style="text-align: right; font-size:14px;">INV/OT-000'.$invid.'</td>
			</tr>
			<tr>
				<td style="text-align: left; font-size:14px;">Cashier</td>
				<td style="text-align: right; font-size:14px;">'.$cashier.'</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center;font-size:12px;border-bottom:1px dotted black;">&nbsp;</td>
			</tr>
			<tr>
				<td style="text-align: center;" colspan="2">
					<table style="width:100%;">
						<tr>
							<td style="text-align: left; font-size:14px;border-bottom:1px dotted black;">Name</td>
							<td style="text-align: right; font-size:14px;border-bottom:1px dotted black;">Price</td>
							<td style="text-align: center; font-size:14px;border-bottom:1px dotted black;">Qty</td>
							<td style="text-align: right; font-size:14px;border-bottom:1px dotted black;">Total</td>
						</tr>
						<tbody>
							'.$tblinvoice.'
						</tbody>

					</table>
				</td>
			</tr>
			<tr>
				<td style="text-align: left; font-size:14px;border-top:1px dotted black;">Total</td>
				<td style="text-align: right; font-size:14px;border-top:1px dotted black;">'.number_format(($grosstotal), 2).'
				</td>
			</tr>
			<tr>
				<td style="text-align: left; font-size:14px;">Total Discount</td>
				<td style="text-align: right; font-size:14px;">'.number_format(($discount), 2).'</td>
			</tr>
			<tr>
				<td style="text-align: left; font-size:14px;">Service Charge</td>
				<td style="text-align: right; font-size:14px;">'.number_format(($servicecharge), 2).'</td>
			</tr>
			<tr>
				<td style="text-align: left; font-size:14px;">Net Total</td>
				<td style="text-align: right; font-size:14px;">'.number_format(($nettotal), 2).'</td>
			</tr>
			<tr>
				<td style="text-align: left; font-size:14px;">Payment</td>
				<td style="text-align: right; font-size:14px;">'.number_format(($payment), 2).'</td>
			</tr>
			<tr>
				<td style="text-align: left; font-size:14px;">Balance</td>
				<td style="text-align: right; font-size:14px;">'.number_format(($balance), 2).'</td>
			</tr>
			<tr>
				<td style="text-align: center; font-size:10px;" colspan="2"><span
						style="text-align: center; font-size:16px;">Thank You. Come again!</span></td>
			</tr>
			<tr>
				<td style="text-align: center; font-size:5px;" colspan="2"><span
						style="text-align: center; font-size:10px;">Copyright © ERav Technology</td>
			</tr>
		</table>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script>
	window.print();
	setTimeout(() => {
		window.close();
	}, 5000);
</script>
</body>

</html>';

echo $html;
    }
}