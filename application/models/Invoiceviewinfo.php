<?php
class Invoiceviewinfo extends CI_Model{
    public function Getinvoicedetails(){
        $html = '';

        $recordID=$this->input->post('recordID');


        $sql = "SELECT `tbl_invoice_detail`.`idtbl_invoice_detail`, `tbl_invoice_detail`.`qty`, `tbl_invoice_detail`.`total`,`tbl_res_item`.`code`
        FROM `tbl_invoice_detail`
        LEFT JOIN `tbl_invoice`  ON `tbl_invoice`.`idtbl_invoice` = `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`
        LEFT JOIN `tbl_res_item`  ON `tbl_res_item`.`idtbl_res_item` = `tbl_invoice_detail`.`tbl_res_item_idtbl_res_item` WHERE `tbl_invoice`.`idtbl_invoice`=? AND `tbl_invoice_detail`.`status`=?";
        $respond = $this->db->query($sql, array($recordID, 1)); 

        $html.='
        <table class="table table-bordered table-striped table-sm nowrap" id="tblInvoicelist">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Item</th>
                <th scope="col">Qty.</th>
                <th scope="col">Total</th>
                </tr>
            </thead>
            <tbody>
        ';
        foreach ($respond->result() as $invoicelist) {
            $html .= '
                <tr>
                    <td>'.$invoicelist->idtbl_invoice_detail.'</td>
                    <td>'.$invoicelist->code.'</td>
                    <td>'.$invoicelist->qty.'</td>
                    <td>'.$invoicelist->total.'</td>
                </tr>';
        }
        $html.='</tbody>
        <tfoot>
                <tr>
                    <th colspan="2" class="text-right"></th>
                    <th class="text-left">Total:</th>
                    <th class="text-left"></th>
                </tr>
            </tfoot></table>';

        echo $html;
    }

    public function Invoicestatus($x, $y){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];
        $recordID=$x;
        $type=$y;
        $updatedatetime=date('Y-m-d H:i:s');

        if($type==3){
            $data = array(
                'status' => '3',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

			$this->db->where('idtbl_invoice', $recordID);
            $this->db->update('tbl_invoice', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-trash-alt';
                $actionObj->title='';
                $actionObj->message='Invoice Cancelled';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Invoiceview');                
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
                redirect('Invoiceview');
            }
        }
    }

    public function Invoiceupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $hiddeninvId=$this->input->post('hiddeninvId');
        $discountamount=$this->input->post('discountamount');
        $nettotal=$this->input->post('nettotal');

        $insertdatetime=date('Y-m-d H:i:s');

            $data = array(
                'discount'=> $discountamount, 
				'nettotal'=> $nettotal, 
                'updateuser'=> $userID, 
                'updatedatetime'=> $insertdatetime,
            );

            $this->db->where('idtbl_invoice', $hiddeninvId);
            $this->db->update('tbl_invoice', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-save';
                $actionObj->title='';
                $actionObj->message='Record Update Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='primary';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Invoiceview');                
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
                redirect('Invoiceview');
            }
    }

    public function Invoiceedit()
    {
        $recordID = $this->input->post('recordID');
    
        $this->db->select('idtbl_invoice, nettotal');
        $this->db->from('tbl_invoice');
        $this->db->where('idtbl_invoice', $recordID);
        $this->db->where('status', 1);
    
        $respond = $this->db->get();
    
        if ($respond->num_rows() > 0) {
            $row = $respond->row();
            $obj = new stdClass();
            $obj->id = $row->idtbl_invoice;
            $obj->nettotal = $row->nettotal;
            
            echo json_encode($obj);
        } else {
            echo json_encode(['error' => 'No records found']);
        }
    }    

}