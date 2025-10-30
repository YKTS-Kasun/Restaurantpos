<?php
class Supplierpaymentreportinfo extends CI_Model{
    public function Getpdf($x, $y){

        $recordID=$x;
        $supplierID=$y;

        $table = '';
        $chequeInfo = '';
        $i = 1;
        $invPayAmount = 0;
        $invNetTotal = 0;

        $paymentInvID = $recordID;

        $this->db->select('`tbl_grn`.`idtbl_grn`, `tbl_grn`.`grndate`, `tbl_grn`.`total`, `tbl_supplier`.`idtbl_supplier`');
        $this->db->from('tbl_grn');
        $this->db->join('tbl_supplier', 'tbl_supplier.idtbl_supplier = tbl_grn.tbl_supplier_idtbl_supplier', 'left');
        $this->db->where('`tbl_grn`.`tbl_supplier_idtbl_supplier`', $supplierID);
        $this->db->where('`tbl_grn`.`idtbl_grn`', $recordID);
        $this->db->where('`tbl_grn`.`approvestatus`', 1);
        $this->db->where('`tbl_grn`.`status`', 1);
        $resultpaymentdetail = $this->db->get();
        $rowpaymentdetail = $resultpaymentdetail->row();


                $this->db->select('`tbl_supplier`.`suppliername`');
                $this->db->from('tbl_grn');
                $this->db->join('tbl_supplier', 'tbl_supplier.idtbl_supplier = tbl_grn.tbl_supplier_idtbl_supplier', 'left');
                $this->db->where('`tbl_grn`.`tbl_supplier_idtbl_supplier`', $supplierID);
                $this->db->where('`tbl_grn`.`approvestatus`', 1);
                $this->db->where('`tbl_grn`.`status`', 1);
                $resultCusDetail = $this->db->get();
                $rowCusDetail = $resultCusDetail->row();

                $this->db->select_sum('total');
                $this->db->from('tbl_supplier_payment');
                $this->db->join('tbl_supplier_payment_has_tbl_grn', 'tbl_supplier_payment.idtbl_supplier_payment = tbl_supplier_payment_has_tbl_grn.tbl_supplier_payment_idtbl_supplier_payment', 'left');
                $this->db->where('tbl_supplier_payment_has_tbl_grn.tbl_grn_idtbl_grn', $recordID);
                $query = $this->db->get();
                $rowgrnpayment = $query->row();

                $this->db->select('idtbl_supplier_payment,total');
                $this->db->from('tbl_supplier_payment');
                $this->db->join('tbl_supplier_payment_has_tbl_grn', 'tbl_supplier_payment.idtbl_supplier_payment = tbl_supplier_payment_has_tbl_grn.tbl_supplier_payment_idtbl_supplier_payment', 'left');
                $this->db->where('tbl_supplier_payment_has_tbl_grn.tbl_grn_idtbl_grn', $recordID);
                $this->db->order_by('tbl_supplier_payment.idtbl_supplier_payment', 'DESC');
                $this->db->limit(1);
                $query2 = $this->db->get();
                $rowgrncurrentpayment = $query2->row();

                $supplierpayID=$rowgrncurrentpayment->idtbl_supplier_payment;



        $table .= "<tr><td>" . $i . "</td><td>" . $rowCusDetail->suppliername . "</td><td></td><td align='right'>" . number_format((float)$rowgrnpayment->total, "2", ".", "") . "</td></tr>";                
        $i++;

        $this->db->select('chequeno,chequedate,bank');
        $this->db->from('tbl_supplier_payment_detail');
        $this->db->where('tbl_supplier_payment_idtbl_supplier_payment', $supplierpayID);
        $this->db->where('`status', 1);
        $resultChequeDetail = $this->db->get();

        foreach ($resultChequeDetail->result() as $rowChequeDetail) {
            $chequeInfo .= '<div class="row" style="margin-top: 30px;"><div class="col-xs-4" style="padding:0;font-family: cursive;font-size:15px;">Cheque Num : ' . $rowChequeDetail->chequeno . '</div><div class="col-xs-3" style="padding:0;font-family: cursive;font-size:15px;">Date: ' . $rowChequeDetail->chequedate . '</div><div class="col-xs-5" style="padding:0;font-family: cursive;font-size:15px;">Bank: ' . $rowChequeDetail->bank . '</div></div>';
        }

$html = '
<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>
<style>
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:5px 10px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:5px 10px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
.tg .tg-btmp{font-weight:bold;color:#000;text-align:left;vertical-align:top}
.tg .tg-0lax{text-align:left;vertical-align:top}
</style>
</head>
<body>
<div class="container">
    <table style="padding:5px 5px;width: 100%;">
        <tr>
            <td width="70%" rowspan="5"><img src="'.base_url().'images/logo.png" class="img-fluid"></td>
            <td width="20%" style="color: black;font-family: cursive;font-size:20px;font-weight: bold; padding:0;">PAYMENT RECEIPT</td>
        </tr>
        <tr>
            <td width="20%" style="font-family: cursive;font-size:14px;padding-top: 8px;padding:0;">Pannipitiya-Kottawa Road, Pannipitiya, Sri Lanka</td>
        </tr>
        <tr>
            <td width="20%" style="font-family: cursive;font-size:15px;padding-top: 5px;padding:0;">071 874 7471</td>
        </tr>
        <tr>
            <td width="20%" style="font-family: cursive;font-size:14px;padding-top: 5px;padding:0;">Gryffindorrestaurant@gmail.com</td>
        </tr>
        <tr style="padding:0px;">
            <td width="20%" style="padding:0px;">
                <table style="padding:0px;">
                    <tr style="padding:0px;">
                        <td width="50%" style="font-family:cursive;font-size:14px;padding:0px;">Date</td>
                        <td width="5%">:</td>
                        <td width="45%" style="font-family: cursive; font-size: 14px;">'.$rowpaymentdetail->grndate.'</td>
                    </tr>
                    <tr style="padding:0px;">
                        <td width="50%" style="font-family:cursive;font-size:14px;padding:0px;">Receipt Num</td>
                        <td width="5%">:</td>
                        <td width="45%" style="font-family:cursive;font-size:14px;">GRNV0'.$rowpaymentdetail->idtbl_grn.'</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div class="row" style="margin-top: 30px;">
        <div class="col-xs-12" style="padding:0;">
            <table class="tg" style="undefined;table-layout: fixed; width: 100%">
                <thead>
                    <tr>
                        <th class="tg-btmp" width="10%">No.</th>
                        <th class="tg-btmp" width="25%">Supplier</th>
                        <th class="tg-btmp" width="50%">Description</th>
                        <th class="tg-btmp" width="15%" align="right">Total</th>
                    </tr>
                </thead>
                <tbody>
                     '.$table.'
                </tbody>
            </table>
        </div>
    </div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-xs-12" style="padding:0;">
            <table style="padding:5px 5px;width: 100%;">
                <tr>
                    <td width="70%" style="font-family:cursive;font-size:14px;text-align:right;">Net Total</td>
                    <td width="5%">:</td>
                    <td width="25%" style="font-family:cursive;font-size:14px;text-align:right;">LKR '.$rowpaymentdetail->total.'</td>
                </tr>
                <tr>
                    <td width="70%" style="font-family:cursive;font-size:14px;text-align:right;">Paid Total</td>
                    <td width="5%">:</td>
                    <td width="25%" style="font-family:cursive;font-size:14px;text-align:right;">LKR '.$rowgrnpayment->total.'</td>
                </tr>
                <tr>
                    <td width="70%" style="font-family:cursive;font-size:14px;text-align:right;">Amount</td>
                    <td width="5%">:</td>
                    <td width="25%" style="font-family:cursive;font-size:14px;text-align:right;">LKR '.$rowgrncurrentpayment->total.'</td>
                </tr>
                <tr>
                    <td width="70%" style="font-family:cursive;font-size:14px;text-align:right;">Balance</td>
                    <td width="5%">:</td>
                    <td width="25%" style="font-family:cursive;font-size:18px;text-align:right;font-weight: bold;">LKR '.((float)$rowpaymentdetail->total - (float)$rowgrnpayment->total).'</td>               
                </tr>
            </table>
        </div>
    </div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-xs-12" style="padding:0;font-family: cursive;font-size:15px;">Cheque Detail</div> 
    </div>'.$chequeInfo.'
    <div class="row" style="margin-top: 20px;">
        <div class="col-xs-3">&nbsp;</div>
        <div class="col-xs-2" style="padding:0;font-family: cursive;font-size:12px;border-top: 1px dotted;text-align:center;">Check By</div> 
        <div class="col-xs-1">&nbsp;</div>
        <div class="col-xs-3" style="padding:0;font-family: cursive;font-size:12px;border-top: 1px dotted;text-align:center;">Customer Signature</div> 
    </div>
</div>
</body>
</html>
';

echo $html;

    }
}