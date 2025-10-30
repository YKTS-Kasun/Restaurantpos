<?php
class Supplierpaymentinfo extends CI_Model{
    public function Getcustomerlist(){
        $this->db->select('`idtbl_supplier`, `suppliername`, `suppliercode`');
        $this->db->from('tbl_supplier');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }

    public function Getgrndetails()
    {
        $supplierID = $this->input->post('supplierID');
        $grnID = $this->input->post('grnID');

        $paymentdetailarray = array();

        if (!empty($supplierID)) {
            $this->db->select('`tbl_grn`.`idtbl_grn`, `tbl_grn`.`grndate`, `tbl_grn`.`total`, `tbl_supplier`.`idtbl_supplier`');
            $this->db->from('tbl_grn');
            $this->db->join('tbl_supplier', 'tbl_supplier.idtbl_supplier = tbl_grn.tbl_supplier_idtbl_supplier', 'left');
            $this->db->where('`tbl_grn`.`tbl_supplier_idtbl_supplier`', $supplierID);
            $this->db->where('`tbl_grn`.`approvestatus`', 1);
            $this->db->where('`tbl_grn`.`status`', 1);
            $resultgrndetail = $this->db->get()->result();

            foreach ($resultgrndetail as $rowgrndetail) {
                $grnID = $rowgrndetail->idtbl_grn;

                $this->db->select_sum('total');
                $this->db->from('tbl_supplier_payment');
                $this->db->join('tbl_supplier_payment_has_tbl_grn', 'tbl_supplier_payment.idtbl_supplier_payment = tbl_supplier_payment_has_tbl_grn.tbl_supplier_payment_idtbl_supplier_payment', 'left');
                $this->db->where('tbl_supplier_payment_has_tbl_grn.tbl_grn_idtbl_grn', $grnID);
                $query = $this->db->get();
                $rowgrnpayment = $query->row();

                $hidegrnno = 'GRN-000' . $rowgrndetail->idtbl_grn;

                $grnDate = $rowgrndetail->grndate;
                $grnNet = $rowgrndetail->total;

                $grnPaid = 0;
                $grnBal = $grnNet;

                if (!empty($rowgrnpayment)) {
                    $grnPaid = $rowgrnpayment->total;
                    $grnBal = $grnNet - $grnPaid;
                }

                $grnPaid = round($grnPaid, 2);
                $grnNet = round($grnNet, 2);

                $paymentdetailarray[] = [
                    'grnID' => $rowgrndetail->idtbl_grn,
                    'supplierID' => $rowgrndetail->idtbl_supplier,
                    'grnDate' => $grnDate,
                    'grnNet' => $grnNet,
                    'grnPaid' => $grnPaid,
                    'grnBal' => $grnBal,
                    'hidegrnno' => $hidegrnno
                ];
            }
        } else if (!empty($grnID)) {
            $this->db->select('`tbl_grn`.`idtbl_grn`, `tbl_grn`.`grndate`, `tbl_grn`.`total`, `tbl_supplier`.`idtbl_supplier`');
            $this->db->from('tbl_grn');
            $this->db->join('tbl_supplier', 'tbl_supplier.idtbl_supplier = tbl_grn.tbl_supplier_idtbl_supplier', 'left');
            $this->db->where('`tbl_grn`.`idtbl_grn`', $grnID);
            $this->db->where('`tbl_grn`.`approvestatus`', 1);
            $this->db->where('`tbl_grn`.`status`', 1);
            $resultgrndetail = $this->db->get()->result();

            foreach ($resultgrndetail as $rowgrndetail) {
                $grnID = $rowgrndetail->idtbl_grn;

                $this->db->select_sum('total');
                $this->db->from('tbl_supplier_payment');
                $this->db->join('tbl_supplier_payment_has_tbl_grn', 'tbl_supplier_payment.idtbl_supplier_payment = tbl_supplier_payment_has_tbl_grn.tbl_supplier_payment_idtbl_supplier_payment', 'left');
                $this->db->where('tbl_supplier_payment_has_tbl_grn.tbl_grn_idtbl_grn', $grnID);
                $query = $this->db->get();
                $rowgrnpayment = $query->row();

                $hidegrnno = 'GRN-000' . $rowgrndetail->idtbl_grn;

                $grnDate = $rowgrndetail->grndate;
                $grnNet = $rowgrndetail->total;

                $grnPaid = 0;
                $grnBal = $grnNet;

                if (!empty($rowgrnpayment)) {
                    $grnPaid = $rowgrnpayment->total;
                    $grnBal = $grnNet - $grnPaid;
                }

                $grnPaid = round($grnPaid, 2);
                $grnNet = round($grnNet, 2);

                $paymentdetailarray[] = [
                    'grnID' => $rowgrndetail->idtbl_grn,
                    'supplierID' => $rowgrndetail->idtbl_supplier,
                    'grnDate' => $grnDate,
                    'grnNet' => $grnNet,
                    'grnPaid' => $grnPaid,
                    'grnBal' => $grnBal,
                    'hidegrnno' => $hidegrnno
                ];
            }
        }

        echo json_encode($paymentdetailarray);
    }
    public function Paymentinsertupdate()
    {
        $this->db->trans_begin();
    
        $halfPayment = 0;
        $fullPayment = 0;
    
        $userID = $_SESSION['userid'];
        $grndate = date("Y-m-d");
        $totAmount = $this->input->post('totAmount');
        $payAmount = $this->input->post('payAmount');
        $balAmount = $this->input->post('balAmount');
        $SupplierID = $this->input->post('SupplierID');
        $tblPayData = json_decode($this->input->post('tableData'), true);
        $tblData = json_decode($this->input->post('tblData'));
    
        $updatedatetime = date('Y-m-d H:i:s');
    
        // Prepare the data for insertion
        $data = array(
            'paydate' => $grndate,
            'total' => $balAmount,
            'status' => '1',
            'insertdatetime' => $updatedatetime,
            'tbl_res_user_idtbl_res_user' => $userID,
            'tbl_supplier_idtbl_supplier' => $SupplierID
        );
    
        // Insert the data into tbl_grn table
        $this->db->insert('tbl_supplier_payment', $data);
    
        $supplierpaymentID = $this->db->insert_id();
    
        // Insert the details into tbl_grndetail table
        foreach ($tblPayData as $rowtabledata) {
            if (isset($rowtabledata['another_col_1'])) {
                $grnID = $rowtabledata['another_col_1'];
    
                // Check if 'col_4' index exists before accessing its value
                $Chequenum = isset($rowtabledata['col_4']) ? $rowtabledata['col_4'] : '';
                $Chequedate = isset($rowtabledata['col_5']) ? $rowtabledata['col_5'] : '';
                $Bank = isset($rowtabledata['col_6']) ? $rowtabledata['col_6'] : '';
    
                $dataone = array(
                    'amount' => $payAmount,
                    'chequeno' => $Chequenum,
                    'chequedate' => $Chequedate,
                    'bank' => $Bank,
                    'receiptno' => '0',
                    'status' => '1',
                    'insertdatetime' => $updatedatetime,
                    'tbl_supplier_payment_idtbl_supplier_payment' => $supplierpaymentID
                );
                
                $this->db->insert('tbl_supplier_payment_detail', $dataone);
    
                $dattwo = array(
                    'tbl_supplier_payment_idtbl_supplier_payment' => $supplierpaymentID,
                    'tbl_grn_idtbl_grn' => $grnID
                );
    
                $this->db->insert('tbl_supplier_payment_has_tbl_grn', $dattwo);
            }
        }
    
        $this->db->trans_complete();
    
        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
    
            $actionObj = new stdClass();
            $actionObj->icon = 'fas fa-save';
            $actionObj->title = '';
            $actionObj->message = 'Record Added Successfully';
            $actionObj->url = '';
            $actionObj->target = '_blank';
            $actionObj->type = 'success';
    
            $actionJSON = json_encode($actionObj);
    
            $obj=new stdClass();
            $obj->action=json_encode($actionObj);
            $obj->actiontype='1';
            $obj->grnid=$grnID;
            $obj->supplierid = $SupplierID;
    
            echo json_encode($obj);
        } else {
            $this->db->trans_rollback();
    
            $actionObj = new stdClass();
            $actionObj->icon = 'fas fa-exclamation-triangle';
            $actionObj->title = '';
            $actionObj->message = 'Record Error';
            $actionObj->url = '';
            $actionObj->target = '_blank';
            $actionObj->type = 'danger';
    
            $actionJSON = json_encode($actionObj);
    
            $obj=new stdClass();
            $obj->action=json_encode($actionObj);
            $obj->actiontype='0';
            $obj->grnid='0';
            $obj->supplierid='0';
    
            echo json_encode($obj);
        }
    }
    public function Supplieredit(){
        $recordID=$this->input->post('recordID');

        $this->db->select('*');
        $this->db->from('tbl_supplier');
        $this->db->where('idtbl_supplier', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        $this->db->select('tbl_res_material_category_idtbl_material_category');
        $this->db->from('tbl_supplier_has_tbl_res_material_category');
        $this->db->where('tbl_supplier_idtbl_supplier', $recordID);

        $respondcategory=$this->db->get();

        $categorylistarray=array();
        foreach($respondcategory->result() as $rowcategory){
            $objcategorylist=new stdClass();
            $objcategorylist->categorylistID=$rowcategory->tbl_res_material_category_idtbl_material_category;
            array_push($categorylistarray, $objcategorylist);
        }

        // $this->db->select('tbl_material_info_idtbl_material_info');
        // $this->db->from('tbl_supplier_has_tbl_material_info');
        // $this->db->where('tbl_supplier_idtbl_supplier', $recordID);

        // $respondmaterial=$this->db->get();

        // $materiallistarray=array();
        // foreach($respondmaterial->result() as $rowmaterial){
        //     $objmateriallist=new stdClass();
        //     $objmateriallist->materiallistID=$rowmaterial->tbl_material_info_idtbl_material_info;
        //     array_push($materiallistarray, $objmateriallist);
        // }

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_supplier;
        $obj->suppliername=$respond->row(0)->suppliername;
        $obj->suppliercode=$respond->row(0)->suppliercode;
        $obj->primarycontactno=$respond->row(0)->primarycontactno;
        $obj->secondarycontactno=$respond->row(0)->secondarycontactno;
        $obj->address=$respond->row(0)->address;
        $obj->email=$respond->row(0)->email;
        $obj->remark=$respond->row(0)->remark;
        $obj->categorylist=$categorylistarray;
        // $obj->materiallist=$materiallistarray;

        echo json_encode($obj);
    }
}