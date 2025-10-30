<?php
class Semibominfo extends CI_Model{
    public function Semibominsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $finishgood=$this->input->post('finishgood');
        $materialcategory=$this->input->post('materialcategory');
        $materialinfo=$this->input->post('materialinfo');
        $qty=$this->input->post('qty');

        // print_r($materialinfo);

        $updatedatetime=date('Y-m-d H:i:s');

        $i=0;
        foreach($materialcategory as $materialcate){
            $matcate=$materialcate;
            $matinfo=$materialinfo[$i];
            $qtylist=$qty[$i];

            $data = array(
                'qty'=>$qtylist, 
                'status'=>'1', 
                'insertdatetime'=>$updatedatetime, 
                'tbl_res_item_idtbl_res_item'=>$finishgood, 
                'tbl_res_user_idtbl_res_user'=>$userID, 
                'tbl_res_material_info_idtbl_res_material_info'=>$matinfo
            );
            $this->db->insert('tbl_product_bom', $data);

            $updateData = array(
                'createbomstatus' => '1',
                'updateuser' => $userID,
                'updatedatetime' => $updatedatetime
            );
            $this->db->where('idtbl_res_item', $finishgood);
            $this->db->update('tbl_res_item', $updateData);

            $i++;
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
            
            $this->session->set_flashdata('msg', $actionJSON);
            redirect('Semibom');                
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
            redirect('Semibom');
        }
    }
    public function Semibomstatus($x, $y){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];
        $recordID=$x;
        $type=$y;
        
        $updatedatetime=date('Y-m-d H:i:s');

        if($type==1){
            $data = array(
                'status' => '1',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_product_bom', $recordID);
            $this->db->update('tbl_product_bom', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-check';
                $actionObj->title='';
                $actionObj->message='Record Activate Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='success';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Semibom');                
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
                redirect('Semibom');
            }
        }
        else if($type==2){
            $data = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_product_bom', $recordID);
            $this->db->update('tbl_product_bom', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-times';
                $actionObj->title='';
                $actionObj->message='Record Deactivate Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='warning';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Semibom');                
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
                redirect('Semibom');
            }
        }
        else if($type==3){
            $data = array(
                'status' => '3',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_product_bom', $recordID);
            $this->db->update('tbl_product_bom', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-trash-alt';
                $actionObj->title='';
                $actionObj->message='Record Remove Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Semibom');                
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
                redirect('Semibom');
            }
        }
    }
    public function Semibomedit(){
        $recordID=$this->input->post('recordID');

        $this->db->select('tbl_semi_bom.*, tbl_product.productcode, tbl_product.productname, tbl_res_material_info.materialinfocode');
        $this->db->from('tbl_semi_bom');
        $this->db->join('tbl_product', 'tbl_product.idtbl_product = tbl_semi_bom.tbl_product_idtbl_product', 'left');
        $this->db->join('tbl_res_material_info', 'tbl_res_material_info.idtbl_res_material_info = tbl_semi_bom.tbl_res_material_info_idtbl_res_material_info', 'left');
        $this->db->where('tbl_semi_bom.idtbl_product_bom', $recordID);
        $this->db->where('tbl_semi_bom.status', 1);

        $respond=$this->db->get();

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_product_bom;
        $obj->qty=$respond->row(0)->qty;
        $obj->wastage=$respond->row(0)->wastage;
        $obj->finishgood=$respond->row(0)->tbl_product_idtbl_product;
        $obj->finishgoodtext=$respond->row(0)->productname.' - '.$respond->row(0)->productcode;
        $obj->materialinfo=$respond->row(0)->res__idtbl_res_material_info ;
        $obj->materialinfotext=$respond->row(0)->material.' - '.$respond->row(0)->materialinfocode;

        echo json_encode($obj);
    }
    public function GetSemimateriallist(){
        $searchTerm=$this->input->post('searchTerm');

        if(!isset($searchTerm)){
            $sql="SELECT `tbl_res_item`.`idtbl_res_item`, `tbl_res_item`.`code`, `tbl_res_item`.`itemname` FROM `tbl_res_item` WHERE `tbl_res_item`.`status`=? AND `tbl_res_item`.`createbomstatus`=? LIMIT 5";
            $respond=$this->db->query($sql, array(1, 0));           
        }
        else{            
            if(!empty($searchTerm)){
                $sql="SELECT `tbl_res_item`.`idtbl_res_item`, `tbl_res_item`.`code`, `tbl_res_item`.`itemname` FROM `tbl_res_item` WHERE `tbl_res_item`.`status`=? AND `tbl_res_item`.`createbomstatus`=? AND `tbl_res_item`.`itemname` LIKE '%$searchTerm%'";
                $respond=$this->db->query($sql, array(1, 0));
            }
            else{
                $sql="SELECT `tbl_res_item`.`idtbl_res_item`, `tbl_res_item`.`code`, `tbl_res_item`.`itemname` FROM `tbl_res_item` WHERE `tbl_res_item`.`status`=? AND `tbl_res_item`.`createbomstatus`=? LIMIT 5";
                $respond=$this->db->query($sql, array(1, 0));
            }
        }
        
        $data=array();
        
        foreach ($respond->result() as $row) {
            $data[]=array("id"=>$row->idtbl_res_item, "text"=>$row->itemname.' - '.$row->code);
        }
        
        echo json_encode($data);
    }
    // public function Getmaterialinfolist(){
    //     $searchTerm=$this->input->post('searchTerm');

    //     if(!isset($searchTerm)){
    //         $sql="SELECT `tbl_res_item`.`idtbl_res_item`, `tbl_res_item`.`code`, `tbl_res_item`.`itemname` FROM `tbl_res_item` WHERE `tbl_res_material_info`.`status`=? LIMIT 5";
    //         $respond=$this->db->query($sql, array(1));           
    //     }
    //     else{            
    //         if(!empty($searchTerm)){
    //             $sql="SELECT `tbl_res_item`.`idtbl_res_item`, `tbl_res_item`.`code`, `tbl_res_item`.`itemname` FROM `tbl_res_item` WHERE `tbl_res_material_info`.`status`=? AND `tbl_res_material_info`.`materialinfocode` LIKE '%$searchTerm%'";
    //             $respond=$this->db->query($sql, array(1));
    //         }
    //         else{
    //             $sql="SELECT `tbl_res_item`.`idtbl_res_item`, `tbl_res_item`.`code`, `tbl_res_item`.`itemname` FROM `tbl_res_item` WHERE `tbl_res_material_info`.`status`=? LIMIT 5";
    //             $respond=$this->db->query($sql, array(1));
    //         }
    //     }
        
    //     $data=array();
        
    //     foreach ($respond->result() as $row) {
    //         $data[]=array("id"=>$row->idtbl_res_item, "text"=>$row->itemname.' - '.$row->code);
    //     }
        
    //     echo json_encode($data);
    // }
    public function Getmaterialcategory(){
        $this->db->select('`idtbl_res_material_category`, `category`');
        $this->db->from('tbl_res_material_category');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Getmaterialname(){
        $this->db->select('`idtbl_res_material_info`, `material`, `materialinfocode`');
        $this->db->from('tbl_res_material_info');
        $this->db->where('tbl_res_material_info.status', 1);

        return $respond=$this->db->get();
    }
    public function Getmaterialinfo(){
        $recordID=$this->input->post('recordID');

        $sql="SELECT `tbl_res_material_info`.`idtbl_res_material_info`, `tbl_res_material_info`.`materialinfocode`, `tbl_res_material_info`.`material` FROM `tbl_res_material_info` WHERE `tbl_res_material_info`.`tbl_res_material_category_idtbl_res_material_category`=? AND `tbl_res_material_info`.`status`=?";
        $respond=$this->db->query($sql, array($recordID, 1));

        echo json_encode($respond->result());
    } 
    public function Semibomdetails(){

        $recordID=$this->input->post('recordID');
        $html='';

        $sql="SELECT `tbl_product_bom`.`idtbl_product_bom`, `tbl_product_bom`.`qty`, `tbl_res_material_category`.`category`, `tbl_res_material_info`.`materialinfocode`, `tbl_res_material_info`.`material` FROM `tbl_product_bom` LEFT JOIN `tbl_res_material_info` ON `tbl_res_material_info`.`idtbl_res_material_info`=`tbl_product_bom`.`tbl_res_material_info_idtbl_res_material_info` LEFT JOIN `tbl_res_material_category` ON `tbl_res_material_category`.`idtbl_res_material_category`=`tbl_res_material_info`.`tbl_res_material_category_idtbl_res_material_category` WHERE `tbl_product_bom`.`status`=? AND `tbl_product_bom`.`tbl_res_item_idtbl_res_item`=?";
        $respond=$this->db->query($sql, array(1, $recordID));


        foreach($respond->result() as $rowlist){
            $html.='
            <tr>
            	<td>'.$rowlist->category.'</td>
            	<td>'.$rowlist->material.'</td>
                <td>'.$rowlist->materialinfocode.'</td>
                <td>'.$rowlist->qty.'</td>
            	<td>
            		<div class="row ml-5"><button type="button" id="'.$rowlist->idtbl_product_bom.'"
            				class="btnEditbom btn btn-primary btn-sm float-right" data-toggle="modal"
            				data-target="#exampleModal">
            				<i class="fas fa-pen"></i>
            			</button>
            			<button type="button" id="'.$rowlist->idtbl_product_bom.'"
            				class="btnDeletebom btn btn-danger btn-sm float-left ml-1" onclick="confirmation()">
            				<i class="fas fa-trash-alt"></i>
            			</button>
            		</div>
            	</td>

            </tr>
            
            ';
        }

        echo $html;
    }
    public function Semibomlist(){
        $recordID=$this->input->post('recordID');

        $this->db->select('tbl_product_bom.idtbl_product_bom, tbl_product_bom.tbl_res_material_info_idtbl_res_material_info, tbl_product_bom.qty, tbl_res_material_category.category');
        $this->db->from('tbl_product_bom');
        $this->db->join('tbl_res_material_info', 'tbl_res_material_info.idtbl_res_material_info = tbl_product_bom.tbl_res_material_info_idtbl_res_material_info', 'left');
        $this->db->join('tbl_res_material_category', 'tbl_res_material_category.idtbl_res_material_category = tbl_res_material_info.tbl_res_material_category_idtbl_res_material_category', 'left');
        $this->db->where('tbl_product_bom.idtbl_product_bom', $recordID);
        $this->db->where('tbl_product_bom.status', 1);

        $respond=$this->db->get();

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_product_bom;
        $obj->materialcategory=$respond->row(0)->category;
        $obj->materialinfo=$respond->row(0)->tbl_res_material_info_idtbl_res_material_info ;
        $obj->qty=$respond->row(0)->qty;


        echo json_encode($obj);
    }
    public function Semibomlistedit(){
        $userID=$_SESSION['userid'];

        $materialinfo=$this->input->post('name');
        $qty=$this->input->post('quantity');

        $recordID=$this->input->post('recordID');

        $updatedatetime=date('Y-m-d H:i:s');

        $data = array(
            'qty'=>$qty, 
            'status'=> '1', 
            'updateuser'=> $userID,
            'updatedatetime'=> $updatedatetime, 
            'tbl_res_material_info_idtbl_res_material_info'=>$materialinfo
        );

        $this->db->where('idtbl_product_bom', $recordID);
        $this->db->update('tbl_product_bom', $data);

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
            redirect('Semibom');                
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
            redirect('Semibom');
        }
    }
    public function Semibomdelete(){
        $recordID=$this->input->post('recordID');
        $userID=$_SESSION['userid'];
        $updatedatetime=date('Y-m-d H:i:s');

        $data = array(
            'status'=> '3', 
            'updateuser'=> $userID,
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('idtbl_product_bom', $recordID);
        $this->db->update('tbl_product_bom', $data);
    }
    public function Semibomalllist(){
        $html = '';

        $sql = "SELECT `tbl_res_item`.`idtbl_res_item`, `tbl_res_item`.`code`, `tbl_res_item`.`itemname` FROM `tbl_res_item` WHERE `tbl_res_item`.`status`=?";
        $respond = $this->db->query($sql, array(1)); 

        $bomarray = array();

        foreach ($respond->result() as $rowlist) {
            $productid = $rowlist->idtbl_res_item;

            $sqlbom = "SELECT `tbl_product_bom`.`qty`, `tbl_res_material_info`.`materialinfocode`, `tbl_res_material_info`.`material` FROM `tbl_product_bom` LEFT JOIN `tbl_res_material_info` ON `tbl_res_material_info`.`idtbl_res_material_info`=`tbl_product_bom`.`tbl_res_material_info_idtbl_res_material_info` WHERE `tbl_product_bom`.`tbl_res_item_idtbl_res_item`=? AND `tbl_product_bom`.`status`=?";
            $respondbom = $this->db->query($sqlbom, array($productid, 1)); 

            $obj = new stdClass();
            $obj->id = $rowlist->idtbl_res_item;
            $obj->procode = $rowlist->code;
            $obj->matname = $rowlist->itemname;
            $obj->result = $respondbom->result();

            array_push($bomarray, $obj);
        }
        $html.='
        <table class="table table-bordered table-striped table-sm nowrap" id="tblBOM">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Item/Item Code</th>
                <th scope="col">Item/Item Name</th>
                <th scope="col">Quantity</th>
                </tr>
            </thead>
            <tbody>
        ';
        foreach ($bomarray as $bomitem) {
            $html .= '
                <tr class="table-secondary">
                    <td>'.$bomitem->id.'</td>
                    <td>'.$bomitem->procode.'</td>
                    <td>'.$bomitem->matname.'</td>
                    <td></td>
                </tr>';
            
            foreach ($bomitem->result as $resultitem) {
                $html .= '
                    <tr>
                        <td></td>
                        <td>'.$resultitem->materialinfocode.'</td>
                        <td>'.$resultitem->material.'</td>
                        <td>'.$resultitem->qty.'</td>
                    </tr>';
            }
        }
        $html.='</tbody></table>';

        echo $html;
    }
}

