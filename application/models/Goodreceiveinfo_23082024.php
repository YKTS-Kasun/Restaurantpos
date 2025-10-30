<?php
class Goodreceiveinfo extends CI_Model{
    // public function Getcostlist(){
    //     $this->db->select('`idtbl_expence_type`, `expencetype`');
    //     $this->db->from('tbl_expence_type');
    //     $this->db->where('status', 1);

    //     return $respond=$this->db->get();
    // }
    public function Getsupplier(){
        $this->db->select('`idtbl_supplier`, `suppliername`');
        $this->db->from('tbl_supplier');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Getporder(){
        $this->db->select('`idtbl_porder`');
        $this->db->from('tbl_porder');
        $this->db->where('status', 1);
        $this->db->where('confirmstatus', 1);
        $this->db->where('grnconfirm', 0);

        return $respond=$this->db->get();
    }
    public function Getproductaccosupplier(){
        $recordID=$this->input->post('recordID');

        $sql="SELECT `tbl_res_material_info`.`idtbl_res_material_info`, `tbl_res_material_info`.`materialinfocode`, `tbl_res_material_info`.`material` FROM `tbl_res_material_info` WHERE `tbl_res_material_info`.`status`=? AND `tbl_res_material_info`.`tbl_res_material_category_idtbl_res_material_category` IN (SELECT `tbl_res_material_category_idtbl_res_material_category` FROM `tbl_supplier_has_tbl_res_material_category` WHERE `tbl_supplier_idtbl_supplier`=?)";
        $respond=$this->db->query($sql, array(1, $recordID));

        echo json_encode($respond->result());
    }
    public function Getgoodreceiveid(){
        $recordID=$this->input->post('recordID');

        $sql="SELECT `idtbl_grn` FROM `tbl_grn` WHERE `idtbl_grn`=? AND `status`=1";
        $respond=$this->db->query($sql, array($recordID));

        echo json_encode($respond->result());
    }
    public function Goodreceiveinsertupdate(){
        $this->db->trans_begin();

        // Get the user ID from the session
        $userID = $_SESSION['userid'];

        // Retrieve the form data
        $tableData = $this->input->post('tableData');
        $grndate = $this->input->post('grndate');
        $total = $this->input->post('total');
        $remark = $this->input->post('remark');
        $supplier = $this->input->post('supplier');
        $batchno = $this->input->post('batchno');
        $invoice = $this->input->post('invoice');
        $dispatch = $this->input->post('dispatch');
        if(!empty($this->input->post('porder'))){$porder=$this->input->post('porder');$grntype=1;}
        else{$porder=1;$grntype=2;}

        $updatedatetime = date('Y-m-d H:i:s');

        // Prepare the data for insertion
        $data = array(
            'batchno' => $batchno,
            'grndate' => $grndate,
            'total' => $total,
            'invoicenum' => $invoice,
            'dispatchnum' => $dispatch,
            'approvestatus' => '0',
            'status' => '1',
            'insertdatetime' => $updatedatetime,
            'tbl_res_user_idtbl_res_user' => $userID,
            'tbl_supplier_idtbl_supplier' => $supplier,
            'tbl_porder_idtbl_porder' => $porder
        );

        // Insert the data into tbl_grn table
        $this->db->insert('tbl_grn', $data);

        $grnID = $this->db->insert_id();

        // Insert the details into tbl_grndetail table
        foreach ($tableData as $rowtabledata) {
            $comment = $rowtabledata['col_2'];
            $materialID = $rowtabledata['col_3'];
            $unit = $rowtabledata['col_4'];
            $qty = $rowtabledata['col_5'];
            $nettotal = $rowtabledata['col_6'];

            $dataone = array(
                'date' => $grndate,
                'qty' => $qty,
                'unitprice'=> $unit, 
                'total'=> $nettotal, 
                'comment'=> $comment,  
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime, 
                'tbl_grn_idtbl_grn'=> $grnID, 
                'tbl_res_material_info_idtbl_res_material_info'=> $materialID
            );

            $this->db->insert('tbl_grndetail', $dataone);

            $stockData = array(
                'batchno' => $batchno,
                'qty' => $qty,
                'status' => '1',
                'insertdatetime' => $updatedatetime,
                'tbl_res_user_idtbl_res_user' => $userID,
                'tbl_res_material_info_idtbl_res_material_info' => $materialID
            );

            $this->db->insert('tbl_stock', $stockData);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-save';
            $actionObj->title='';
            $actionObj->message='Record Added Successfully';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='success';

            $actionJSON=json_encode($actionObj);

            $obj=new stdClass();
            $obj->status=1;          
            $obj->action=$actionJSON;  
            
            echo json_encode($obj);
        } else {
            $this->db->trans_rollback();

            $actionObj=new stdClass();
            $actionObj->icon='fas fa-exclamation-triangle';
            $actionObj->title='';
            $actionObj->message='Record Error';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='danger';

            $actionJSON=json_encode($actionObj);

            $obj=new stdClass();
            $obj->status=0;          
            $obj->action=$actionJSON;  
            
            echo json_encode($obj);
        }
    }
    public function Goodreceiveview(){
        $recordID=$this->input->post('recordID');

        $sql="SELECT `u`.*, `ua`.`suppliername`, `ua`.`primarycontactno`, `ua`.`secondarycontactno`, `ua`.`address`, `ua`.`email` FROM `tbl_grn` AS `u` LEFT JOIN `tbl_supplier` AS `ua` ON (`ua`.`idtbl_supplier` = `u`.`tbl_supplier_idtbl_supplier`) WHERE `u`.`status`=? AND `u`.`idtbl_grn`=?";
        $respond=$this->db->query($sql, array(1, $recordID));

        $this->db->select('tbl_grndetail.*, tbl_res_material_info.materialinfocode, tbl_res_material_info.material');
        $this->db->from('tbl_grndetail');
        $this->db->join('tbl_res_material_info', 'tbl_res_material_info.idtbl_res_material_info = tbl_grndetail.tbl_res_material_info_idtbl_res_material_info', 'left');
        $this->db->where('tbl_grndetail.tbl_grn_idtbl_grn', $recordID);
        $this->db->where('tbl_grndetail.status', 1);

        $responddetail=$this->db->get();
        // print_r($this->db->last_query());

        $html='';
        $html.='
        <div class="row">
            <div class="col-12 text-right">'.$respond->row(0)->suppliername.'<br>'.$respond->row(0)->primarycontactno.' / '.$respond->row(0)->secondarycontactno.'<br>'.$respond->row(0)->address.'<br>'.$respond->row(0)->email.'</div>
            <div class="col-12">
                <hr>
                <h6>Invoice No : '.$respond->row(0)->invoicenum.'</h6>
                <h6>Dispatch No : '.$respond->row(0)->dispatchnum.'</h6>
                <h6>Batch No : '.$respond->row(0)->batchno.'</h6>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <hr>
                <table class="table table-striped table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Material Info</th>
                            <th>Unit Price</th>
                            <th class="text-center">Qty</th>
                            <!--<th class="text-center">Amend Qty</th>-->
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>';
                    foreach($responddetail->result() as $roworderinfo){
                        // if($roworderinfo->amendqty>0){
                        //     $total=number_format(($roworderinfo->amendqty*$roworderinfo->unitprice), 2);
                        // }
                        // else{
                        //     $total=number_format(($roworderinfo->qty*$roworderinfo->unitprice), 2);
                        // }
                        $total=number_format(($roworderinfo->qty*$roworderinfo->unitprice), 2);
                        $html.='<tr>
                            <td>'.$roworderinfo->material.' / '.$roworderinfo->materialinfocode.'</td>
                            <td>'.$roworderinfo->unitprice.'</td>
                            <td class="text-center">'.$roworderinfo->qty.'</td>
                            <!--<td class="text-center"></td>-->
                            <td class="text-right">'.$total.'</td>
                        </tr>';
                    }
                    $html.='</tbody>
                </table>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 text-right"><h3 class="font-weight-normal">Rs. '.number_format(($respond->row(0)->total), 2).'</h3></div>
        </div>
        ';

        echo $html;
    }
    public function Goodreceivestatus($x, $y){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];
        $recordID=$x;
        $type=$y;
        $updatedatetime=date('Y-m-d H:i:s');

        if($type==1){
            $data = array(
                'approvestatus' => '1',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_grn', $recordID);
            $this->db->update('tbl_grn', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-check';
                $actionObj->title='';
                $actionObj->message='Order Confirm Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='success';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Goodreceive');                
            } else {
                $this->db->trans_rollback();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Goodreceive');
            }
        }
        else if($type==3){
            $data = array(
                'status' => '3',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_grn', $recordID);
            $this->db->update('tbl_grn', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-trash-alt';
                $actionObj->title='';
                $actionObj->message='Record Reject Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Goodreceive');                
            } else {
                $this->db->trans_rollback();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Goodreceive');
            }
        }
    }
    public function Getsupplieraccoporder(){
        $recordID=$this->input->post('recordID');

        $this->db->select('`tbl_supplier_idtbl_supplier`');
        $this->db->from('tbl_porder');
        $this->db->where('status', 1);
        $this->db->where('idtbl_porder', $recordID);

        $respond=$this->db->get();

        echo $respond->row(0)->tbl_supplier_idtbl_supplier;
    }
    public function Getproductaccoporder(){
        $recordID=$this->input->post('recordID');

        $sql="SELECT `tbl_res_material_info`.`idtbl_res_material_info`, `tbl_res_material_info`.`materialinfocode`,`tbl_res_material_info`.`material`
         FROM `tbl_porder_detail` LEFT JOIN `tbl_res_material_info` ON `tbl_res_material_info`.`idtbl_res_material_info`=`tbl_porder_detail`.`tbl_res_material_info_idtbl_res_material_info` WHERE `tbl_res_material_info`.`status`=? AND `tbl_porder_detail`.`tbl_porder_idtbl_porder`=?";
        $respond=$this->db->query($sql, array(1, $recordID));

        echo json_encode($respond->result());
    }
    public function Getproductinfoaccoproduct(){
        $recordID=$this->input->post('recordID');

        $this->db->select('`qty`, `unitprice`, `comment`');
        $this->db->from('tbl_porder_detail');
        $this->db->where('status', 1);
        $this->db->where('tbl_res_material_info_idtbl_res_material_info', $recordID);

        $respond=$this->db->get();

        if($respond->num_rows()>0){
            $obj=new stdClass();
            $obj->qty=$respond->row(0)->qty;
            $obj->unitprice=$respond->row(0)->unitprice;
            $obj->comment=$respond->row(0)->comment;
        }

        else{
            $obj=new stdClass();
            $obj->qty=0;
            $obj->unitprice=0;
            $obj->comment='';
        }
        echo json_encode($obj);
    }
    public function Getexpdateaccoquater(){
        $recordID=$this->input->post('recordID');
        $mfdate=$this->input->post('mfdate');

        if($recordID==1){$addmonth=3;}
        else if($recordID==2){$addmonth=6;}
        else if($recordID==3){$addmonth=9;}
        else if($recordID==4){$addmonth=12;}
        else if($recordID==5){$addmonth=18;}
        else if($recordID==6){$addmonth=24;}

        echo date('Y-m-d', strtotime("+$addmonth months", strtotime($mfdate)));
    }
    public function Getbatchnoaccosupplier(){
        $recordID=$this->input->post('recordID');

        if(!empty( $recordID)){
            $this->db->select('tbl_supplier.`suppliercode`');
            $this->db->from('tbl_supplier');
            $this->db->join('tbl_supplier_has_tbl_res_material_category', 'tbl_supplier_has_tbl_res_material_category.tbl_supplier_idtbl_supplier = tbl_supplier.idtbl_supplier', 'left');
            $this->db->join('tbl_res_material_category', 'tbl_res_material_category.idtbl_res_material_category = tbl_supplier_has_tbl_res_material_category.tbl_res_material_category_idtbl_material_category', 'left');
            $this->db->where('tbl_supplier.idtbl_supplier', $recordID);
            $this->db->where('tbl_supplier.status', 1);

            $responddetail=$this->db->get();

            // print_r($this->db->last_query());    
            $suppliercode=$responddetail->row(0)->suppliercode;

            $sql="SELECT COUNT(*) AS `count` FROM `tbl_grn`";
            $respond=$this->db->query($sql);
        
            if($respond->row(0)->count==0){$batchno=date('dmY').'001';}
            else{
                $count='000'.($respond->row(0)->count+1);
                $count=substr($count, -3);
                $batchno=date('dmY').$count;
            }
        
            echo $suppliercode.$batchno;
        }
        else{
            echo '';
        }
    }
}