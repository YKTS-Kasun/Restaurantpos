<?php
class Directsaleinfo extends CI_Model{
    public function Getcategory(){
        $this->db->select('`idtbl_res_item_category`, `categoryname`');
        $this->db->from('tbl_res_item_category');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Getmaterial(){
        $this->db->select('`idtbl_res_item`, `itemname`,`image`');
        $this->db->from('tbl_res_item');
        $this->db->where('tbl_res_item.status', 1);

        return $respond=$this->db->get();
    }

    public function Gettable(){
        $this->db->select('`idtbl_res_reservation_table`, `table`');
        $this->db->from('tbl_res_reservation_table');
        $this->db->where('tbl_res_reservation_table.status', 1);

        return $respond=$this->db->get();
    }


    public function Getproductlist() {
        $html = '';
    
        $hiddenmaterialID = $this->input->post('categoryID');
    
        $sql = "SELECT `tbl_res_item`.`idtbl_res_item`, `tbl_res_item`.`itemname`, `tbl_res_item`.`image` FROM `tbl_res_item` WHERE `tbl_res_item`.`tbl_res_item_category_idtbl_res_item_category` = $hiddenmaterialID AND `tbl_res_item`.`status` = 1";
        $respond = $this->db->query($sql);
    
        if ($respond->num_rows() > 0) {
            $html .= '<div class="row row-cols-1 row-cols-md-4">';
            $counter = 0;
    
            foreach ($respond->result() as $rowmaterial) {
                if ($counter % 3 == 0) {
                    $html .= '</div><div class="row row-cols-1 row-cols-md-3">
                    ';
                }
    
                $html .= '
                    <div class="col mb-4 itemdiv" tabindex="0" id="' . $rowmaterial->idtbl_res_item . '">
                        <div class="card card-material h-100 shadow-none" style="background-color: #FFD700; border-color:#FFD700">
                            <div class="card-body p-2 text-center pointer">
                ';
    
                if (!empty($rowmaterial->image) && file_exists("images/Items/" . $rowmaterial->image)) {
                    $imagePath = "images/Items/" . $rowmaterial->image;
                } else {
                    // Default image path in case the file doesn't exist or is empty
                    $imagePath = "images/Items/restaurant2.png";
                }
    
                $html .= '
                    <img src="' . base_url($imagePath) . '" alt="" style="width: 170px; height: 100px;">
                    <hr class="border-dark my-1">
                    <h4 class="text-dark font-weight-bold" style="font-size:20px;">' . $rowmaterial->itemname . '</h4>
                            </div>
                        </div>
                    </div>
                ';
    
                $counter++;
            }
    
            $html .= '</div>';
        } else {
            $html .= '
                <div class="row row-cols-1">
                </div>
            ';
        }
    
        echo $html;
    }

    public function Getproductdetails(){
        $recordID=$this->input->post('recordID');

        $this->db->select('`idtbl_res_item`, `itemname`, `price`');
        $this->db->from('tbl_res_item');
        $this->db->where('status', 1);
        $this->db->where('idtbl_res_item', $recordID);

        $respond=$this->db->get();

        if($respond->num_rows()>0){
            $obj=new stdClass();
            $obj->id=$respond->row(0)->idtbl_res_item;
            $obj->itemname=$respond->row(0)->itemname;
            $obj->price=$respond->row(0)->price;
        }
        echo json_encode($obj);
    }

    public function Getproductlistaccobarcode(){
        $html='';

        $barcode=$this->input->post('barcode');
        // $saletype=$this->input->post('saletype');


        $sql="SELECT `tbl_product`.`idtbl_product`, `tbl_product`.`productcode`,  `tbl_product`.`barcode`, `tbl_product`.`retailprice`, `tbl_product`.`wholesaleprice` 
        FROM `tbl_product` 
        WHERE `tbl_product`.`barcode`= '$barcode' AND `tbl_product`.`status`=1";
        $respond=$this->db->query($sql);

        if($respond->num_rows() > 0){ 
            foreach($respond->result() as $rowlist){
                $productID=$rowlist->idtbl_product;
                $sqlstockcheck="SELECT SUM(`qty`) AS sumqty FROM `tbl_product_stock` WHERE `tbl_product_idtbl_product`='$productID'"; 
                $respondstockcheck=$this->db->query($sqlstockcheck);
                

                if(!empty($respondstockcheck->row(0)->sumqty)){$stockcount=$respondstockcheck->row(0)->sumqty;}
                else{$stockcount=0;}  

                // $saleprice;
                // if($saletype==1){
                //     $saleprice=$rowlist->retailprice;
                // }else{
                //     $saleprice=$rowlist->wholesaleprice;
                // }

                $html.='

                    <tr class=';if($stockcount==0){$html.='table-danger';}else{$html.='pointer';}$html.=' id="'.$rowlist->idtbl_product.'">
                        <td>'.$rowlist->idtbl_product.'</td>
                        <td>'.$rowlist->productcode.'</td>
                        <td>'.$rowlist->barcode.'</td>
                        <td>'.$stockcount.'</td>
                        <td class="text-right">'.$rowlist->retailprice.'</td>

                    </tr>
                ';            
            }
        }else{
            $html.='

            <tr>
                <td colspan="3">No Product To Show</td>
            </tr>
            ';
        }
        echo $html;
    }


    public function Directsaletempinsertupdate(){
    $this->db->trans_begin();

    $userID = $_SESSION['userid'];
    $productID = $this->input->post('productID');
    $product = $this->input->post('product');
    $productcode = $this->input->post('productcode');
    // $billtype=$this->input->post('billtype');
    $unit = $this->input->post('unit');
    $sale = $this->input->post('sale');
    $qty = $this->input->post('qty');
    $table = $this->input->post('tableID');
    $salepriceedit = $this->input->post('salepriceedit');
    $discountpercentage = $this->input->post('discountpresentage');
    $total = $sale * $qty;
    $discountamount = ($total * $discountpercentage) / 100; // Calculate the discount amount
    $nettotal = $total - $discountamount; // Calculate the net total
    $insertdatetime = date('Y-m-d H:i:s');
    $invdate = date('Y-m-d');
    $time = date('H:i:s');

    // Check if an invoice with the same ID already exists for the current table
    $existingInvoice = $this->db->get_where('tbl_res_temp_invoice', array('tbl_res_user_idtbl_res_user' => $userID, 'tbl_res_reservation_table_idtbl_res_reservation_table' => $table))->row();

    if ($existingInvoice && $existingInvoice->billclose == 1) {
        // Add another record for the same table ID
        $data = array(
            'date' => $invdate,
            'time' => $time,
            'grosstoral' => $existingInvoice->grosstoral + $total,
            'discount' => $existingInvoice->discount + $discountamount,
            'nettotal' => $existingInvoice->nettotal + $nettotal,
            'billclose' => '0',
            'insertdatetime' => $insertdatetime,
            'tbl_res_user_idtbl_res_user' => $userID,
            'tbl_res_reservation_table_idtbl_res_reservation_table' => $table
        );

        $this->db->insert('tbl_res_temp_invoice', $data);
        $invoiceID = $this->db->insert_id();
    } elseif ($existingInvoice && $existingInvoice->billclose == 0) {
        $invoiceID = $existingInvoice->idtbl_res_temp_invoice;
        // Update the existing invoice with added values
        $updateData = array(
            'grosstoral' => $existingInvoice->grosstoral + $total,
            'discount' => $existingInvoice->discount + $discountamount,
            'nettotal' => $existingInvoice->nettotal + $nettotal
        );
        $this->db->where('idtbl_res_temp_invoice', $invoiceID);
        $this->db->update('tbl_res_temp_invoice', $updateData);
    } else {
        $data = array(
            'date' => $invdate,
            'time' => $time,
            'grosstoral' => $total,
            'discount' => $discountamount,
            'nettotal' => $nettotal,
            'billclose' => '0',
            'insertdatetime' => $insertdatetime,
            'tbl_res_user_idtbl_res_user' => $userID,
            'tbl_res_reservation_table_idtbl_res_reservation_table' => $table
        );

        $this->db->insert('tbl_res_temp_invoice', $data);
        $invoiceID = $this->db->insert_id();
        }
    
        $data = array(
        'qty' => $qty,
        'saleprice' => $sale,
        'total' => $nettotal,
        'status' => '1',
        'insertdatetime' => $insertdatetime,
        'tbl_res_temp_invoice_idtbl_res_temp_invoice' => $invoiceID,
        'tbl_res_item_idtbl_res_item' => $productID
    );

    $this->db->insert('tbl_res_temp_invoice_detail', $data);

    $datakot = array(
        'date' => $invdate,
        'tableid' => $table,
        'orderid' => '0',
        'processstatus' => '0',
        'startdatetime' => '',
        'tableorderclose' => '0',
        'enddatetime' => '',
        'status' => '1',
        'insertdatetime' => $insertdatetime,
        'tbl_res_user_idtbl_res_user' => $userID
    );

    $this->db->insert('tbl_res_kot', $datakot);
    $kotID = $this->db->insert_id();
    
    $datakotdetail = array(
        'qty' => $qty,
        'notes' => '',
        'startdatetime' => '',
        'enddatetime' => '',
        'completestatus' => '0',
        'status' => '1',
        'insertdatetime' => $insertdatetime,
        'tbl_res_user_idtbl_res_user' => $userID,
        'tbl_res_kot_idtbl_res_kot' => $kotID,
        'tbl_res_item_idtbl_res_item' => $productID
    );
    
    $this->db->insert('tbl_res_kot_detail', $datakotdetail);



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
            $obj->action=json_encode($actionObj);
            $obj->actiontype='1';
            $obj->invoiceid=$invoiceID;
            // $obj->billtype=$billtype;
            // $obj->saletype=$salestype;  
            
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
            $obj->action=json_encode($actionObj);
            $obj->actiontype='0';
            $obj->invoiceid='0';
            // $obj->billtype=$billtype;
            // $obj->saletype=$salestype; 
            
            echo json_encode($obj);
        }

    }
    
    public function Directsaleinsertupdate(){

        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $tableData=$this->input->post('tableData');
        $tableDataPay=$this->input->post('tableDataPay');
        $total=$this->input->post('total');
        $distotal=$this->input->post('distotal');
        $nettotal=$this->input->post('nettotal');
        // $billtype=$this->input->post('billtype');
        $paytotal=$this->input->post('paytotal');
        $location=$this->input->post('location');
        $orderid=$this->input->post('orderid');
        $teminvoiceID=$this->input->post('teminvoiceID');

        $balance=$nettotal-$paytotal;

        $insertdatetime=date('Y-m-d H:i:s');
        $invdate=date('Y-m-d H:i:s');

        $data = array(
            'invdate'=> $invdate, 
            'grosstotal'=> $total, 
            'discount'=> $distotal, 
            'nettotal'=> $nettotal, 
            'invtype'=> '1', 
            'paycomplete'=> '0', 
            'orderid'=> '0', 
            'status'=> '1', 
            'insertdatetime'=> $insertdatetime, 
            'tbl_res_user_idtbl_res_user'=> $userID
        );

        $this->db->insert('tbl_invoice', $data);

        $invoiceID=$this->db->insert_id();

        foreach($tableData as $rowtabledata){
            $data = array(
                'qty'=> $rowtabledata['col_2'], 
                'saleprice'=> $rowtabledata['col_3'], 
                'total'=> $rowtabledata['col_5'],  
                'status'=> '1', 
                'insertdatetime'=> $insertdatetime,
                'tbl_invoice_idtbl_invoice'=> $invoiceID, 
                'tbl_res_item_idtbl_res_item'=> $rowtabledata['col_6']
            );

            $this->db->insert('tbl_invoice_detail', $data);

        }

            $data = array(
                'paydate'=> $invdate, 
                'nettotal'=> $paytotal, 
                'balance'=> $balance, 
                'status'=> '1', 
                'insertdatetime'=> $insertdatetime, 
                'tbl_res_user_idtbl_res_user'=> $userID
            );
    
            $this->db->insert('tbl_invoice_payment', $data);

            $invoicepayID=$this->db->insert_id();

            foreach($tableDataPay as $rowtableDataPay){
                $dataone = array(
                    'method'=> $rowtableDataPay['col_1'], 
                    'amount'=> $rowtableDataPay['col_7'], 
                    'bank'=> $rowtableDataPay['col_3'],  
                    'branch'=> $rowtableDataPay['col_4'], 
                    'chequeno'=> $rowtableDataPay['col_5'], 
                    'chequedate'=> $rowtableDataPay['col_6'],  
                    'status'=> '1', 
                    'insertdatetime'=> $insertdatetime,
                    'tbl_res_user_idtbl_res_user'=> $userID,
                    'tbl_invoice_payment_idtbl_invoice_payment'=> $invoicepayID, 
                );
    
                $this->db->insert('tbl_invoice_payment_detail', $dataone);

                $datatwo = array(
                    'tbl_invoice_payment_idtbl_invoice_payment'=> $invoicepayID, 
                    'tbl_invoice_idtbl_invoice'=> $invoiceID, 
                );
        
                $this->db->insert('tbl_invoice_payment_has_tbl_invoice', $datatwo);
            }
            $updateData = array(
                'billclose' => '1'
            );
            $this->db->where('idtbl_res_temp_invoice', $teminvoiceID);
            $this->db->update('tbl_res_temp_invoice', $updateData);

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
            $obj->action=json_encode($actionObj);
            $obj->actiontype='1';
            $obj->invoiceid=$invoiceID;
            // $obj->billtype=$billtype;
            // $obj->saletype=$salestype;  
            
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
            $obj->action=json_encode($actionObj);
            $obj->actiontype='0';
            $obj->invoiceid='0';
            // $obj->billtype=$billtype;
            // $obj->saletype=$salestype; 
            
            echo json_encode($obj);
        }

    }

    public function Getproductavalaibleqty(){

        $product=$this->input->post('product');
        $inserted_qty=$this->input->post('qty');

        $this->db->select('qty');
        $this->db->from('tbl_product_stock');
        $this->db->where('tbl_product_idtbl_product', $product);
        $this->db->where('status', 1);
        $respond=$this->db->get();
        
        $availableqty=$respond->row(0)->qty;
    
        $obj=new stdClass();
        
        if($inserted_qty >= $availableqty){
            $qtyresult = 1;
            
        }
        else{
            $qtyresult = 0;
        }
        $obj->checkqty = $qtyresult;

        echo json_encode($obj);

    }

    public function Getaddeditems(){
        $tableID = $this->input->post('tableID');
    
        $this->db->select('tbl_res_temp_invoice_detail.idtbl_res_temp_invoice_detail, tbl_res_temp_invoice.idtbl_res_temp_invoice, tbl_res_temp_invoice_detail.qty, tbl_res_temp_invoice.grosstoral,tbl_res_temp_invoice.discount, tbl_res_temp_invoice.nettotal, tbl_res_item.itemname, tbl_res_item.idtbl_res_item, tbl_res_item.idtbl_res_item, tbl_res_temp_invoice_detail.saleprice, tbl_res_temp_invoice_detail.total, tbl_res_temp_invoice.tbl_res_reservation_table_idtbl_res_reservation_table, tbl_res_temp_invoice.billclose');
        $this->db->from('tbl_res_temp_invoice_detail');
        $this->db->join('tbl_res_temp_invoice', 'tbl_res_temp_invoice.idtbl_res_temp_invoice = tbl_res_temp_invoice_detail.tbl_res_temp_invoice_idtbl_res_temp_invoice', 'left');
        $this->db->join('tbl_res_item', 'tbl_res_item.idtbl_res_item = tbl_res_temp_invoice_detail.tbl_res_item_idtbl_res_item', 'left');
        $this->db->where('tbl_res_temp_invoice_detail.status', 1);
        $this->db->where('tbl_res_temp_invoice.tbl_res_reservation_table_idtbl_res_reservation_table', $tableID);
        $this->db->where('tbl_res_temp_invoice.billclose', 0);
    
        $respond = $this->db->get();
    
        $items = array(); // Array to store the items
    
        foreach ($respond->result() as $row) {
            $item = new stdClass();
            $item->id = $row->idtbl_res_temp_invoice;
            $item->itemname = $row->itemname;
            $item->itemID = $row->idtbl_res_item;
            $item->qty = $row->qty;
            $item->saleprice = $row->saleprice;
            $item->total = $row->total;
            $item->idtbl_res_item = $row->idtbl_res_item;
            $item->totalamount = $row->grosstoral;
            $item->discountamount = $row->discount;
            $item->totalwithdis = $row->nettotal; // Store the discount value directly
    
            $items[] = $item;
        }
    
        echo json_encode($items);
    }

    public function Getonlineorders()
    {
        $html = '';
        $sql = "SELECT `tbl_res_order`.`idtbl_res_order`, `tbl_res_order`.`orderdate`, `tbl_res_order`.`total` AS fulltotal, `tbl_res_order`.`discount`, `tbl_res_order`.`nettotal`,  `tbl_res_orderdetail`.`qty`, `tbl_res_orderdetail`.`price`, `tbl_res_orderdetail`.`total`, `tbl_res_customer`.`firstname`, `tbl_res_customer`.`lastname`, `tbl_res_customer`.`contact`, `tbl_res_item`.`itemname`, `tbl_res_item`.`idtbl_res_item` FROM `tbl_res_order` LEFT JOIN `tbl_res_orderdetail` ON `tbl_res_order`.`idtbl_res_order`=`tbl_res_orderdetail`.`tbl_res_order_idtbl_res_order` LEFT JOIN `tbl_res_customer` ON `tbl_res_customer`.`idtbl_res_customer`=`tbl_res_order`.`tbl_res_customer_idtbl_res_customer` LEFT JOIN `tbl_res_item` ON `tbl_res_item`.`idtbl_res_item`=`tbl_res_orderdetail`.`tbl_res_item_idtbl_res_item` WHERE `tbl_res_order`.`status`=?";
        $respond = $this->db->query($sql, array(1));
    
        foreach ($respond->result() as $rowlist) {
            $html .= '
                <tr>
                    <td>' . $rowlist->idtbl_res_order . '</td>
                    <td>' . $rowlist->firstname . ' '. $rowlist->lastname . '</td>
                    <td>' . $rowlist->contact . '</td>
                    <td>' . $rowlist->orderdate . '</td>
                    <td>' . $rowlist->itemname . '</td>
                    <td>' . $rowlist->qty . '</td>
                    <td>
                        <div class="row ml-5">
                            <button type="button" class="btnaddtocart btn btn-primary btn-sm" data-productid="' . $rowlist->idtbl_res_item . '" data-product="' . $rowlist->itemname . '" data-qty="' . $rowlist->qty . '" data-sale="' . $rowlist->price . '" data-discount="' . $rowlist->discount . '">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </td>
                </tr>';
        }
    
        echo $html;
    }

}
