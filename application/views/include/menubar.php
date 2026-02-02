<?php 
$controllermenu=$this->router->fetch_class();
$functionmenu=uri_string();
$functionmenu2=$this->router->fetch_method();

$menuprivilegearray=$menuaccess;

if($functionmenu2=='Useraccount'){
    $addcheck=checkprivilege($menuprivilegearray, 1, 1);
    $editcheck=checkprivilege($menuprivilegearray, 1, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 1, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 1, 4);
}
else if($functionmenu2=='Usertype'){
    $addcheck=checkprivilege($menuprivilegearray, 2, 1);
    $editcheck=checkprivilege($menuprivilegearray, 2, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 2, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 2, 4);
}
else if($functionmenu2=='Userprivilege'){
    $addcheck=checkprivilege($menuprivilegearray, 3, 1);
    $editcheck=checkprivilege($menuprivilegearray, 3, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 3, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 3, 4);
}
else if($functionmenu=='Customer'){
    $addcheck=checkprivilege($menuprivilegearray, 4, 1);
    $editcheck=checkprivilege($menuprivilegearray, 4, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 4, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 4, 4);
}
else if($functionmenu=='Item'){
    $addcheck=checkprivilege($menuprivilegearray, 5, 1);
    $editcheck=checkprivilege($menuprivilegearray, 5, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 5, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 5, 4);
}
else if($functionmenu=='Itemcategory'){
    $addcheck=checkprivilege($menuprivilegearray, 6, 1);
    $editcheck=checkprivilege($menuprivilegearray, 6, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 6, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 6, 4);
}
else if($functionmenu=='Orders'){
    $addcheck=checkprivilege($menuprivilegearray, 7, 1);
    $editcheck=checkprivilege($menuprivilegearray, 7, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 7, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 7, 4);
}
else if($functionmenu=='Reservation'){
    $addcheck=checkprivilege($menuprivilegearray, 8, 1);
    $editcheck=checkprivilege($menuprivilegearray, 8, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 8, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 8, 4);
}
else if($functionmenu=='Reservationtype'){
    $addcheck=checkprivilege($menuprivilegearray, 9, 1);
    $editcheck=checkprivilege($menuprivilegearray, 9, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 9, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 9, 4);
}
else if($functionmenu=='Transactions'){
    $addcheck=checkprivilege($menuprivilegearray, 10, 1);
    $editcheck=checkprivilege($menuprivilegearray, 10, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 10, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 10, 4);
}
else if($functionmenu=='Reservationtable'){
    $addcheck=checkprivilege($menuprivilegearray, 11, 1);
    $editcheck=checkprivilege($menuprivilegearray, 11, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 11, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 11, 4);
}
else if($functionmenu=='Goodreceive'){
    $addcheck=checkprivilege($menuprivilegearray, 12, 1);
    $editcheck=checkprivilege($menuprivilegearray, 12, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 12, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 12, 4);
}
else if($functionmenu=='Semibom'){
    $addcheck=checkprivilege($menuprivilegearray, 13, 1);
    $editcheck=checkprivilege($menuprivilegearray, 13, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 13, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 13, 4);
}
else if($functionmenu=='Purchaseorder'){
    $addcheck=checkprivilege($menuprivilegearray, 14, 1);
    $editcheck=checkprivilege($menuprivilegearray, 14, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 14, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 14, 4);
}
else if($functionmenu=='Supplier'){
    $addcheck=checkprivilege($menuprivilegearray, 15, 1);
    $editcheck=checkprivilege($menuprivilegearray, 15, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 15, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 15, 4);
}
else if($functionmenu=='Directsale'){
    $addcheck=checkprivilege($menuprivilegearray, 16, 1);
    $editcheck=checkprivilege($menuprivilegearray, 16, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 16, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 16, 4);
}
else if($functionmenu=='Materialcategory'){
    $addcheck=checkprivilege($menuprivilegearray, 17, 1);
    $editcheck=checkprivilege($menuprivilegearray, 17, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 17, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 17, 4);
}
else if($functionmenu=='Materialdetail'){
    $addcheck=checkprivilege($menuprivilegearray, 18, 1);
    $editcheck=checkprivilege($menuprivilegearray, 18, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 18, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 18, 4);
}
else if($functionmenu=='Kitchen'){
    $addcheck=checkprivilege($menuprivilegearray, 19, 1);
    $editcheck=checkprivilege($menuprivilegearray, 19, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 19, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 19, 4);
}
else if($functionmenu=='Materialissue'){
    $addcheck=checkprivilege($menuprivilegearray, 20, 1);
    $editcheck=checkprivilege($menuprivilegearray, 20, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 20, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 20, 4);
}
else if($functionmenu=='Supplierpayment'){
    $addcheck=checkprivilege($menuprivilegearray, 21, 1);
    $editcheck=checkprivilege($menuprivilegearray, 21, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 21, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 21, 4);
}
else if($functionmenu=='RptGRN'){
    $addcheck=checkprivilege($menuprivilegearray, 23, 1);
    $editcheck=checkprivilege($menuprivilegearray, 23, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 23, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 23, 4);
}
else if($functionmenu=='RptStock'){
    $addcheck=checkprivilege($menuprivilegearray, 24, 1);
    $editcheck=checkprivilege($menuprivilegearray, 24, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 24, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 24, 4);
}
else if($functionmenu=='RptProductwise'){
    $addcheck=checkprivilege($menuprivilegearray, 25, 1);
    $editcheck=checkprivilege($menuprivilegearray, 25, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 25, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 25, 4);
}
else if($functionmenu=='RptTablewise'){
    $addcheck=checkprivilege($menuprivilegearray, 26, 1);
    $editcheck=checkprivilege($menuprivilegearray, 26, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 26, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 26, 4);
}
else if($functionmenu=='Tableno'){
    $addcheck=checkprivilege($menuprivilegearray, 27, 1);
    $editcheck=checkprivilege($menuprivilegearray, 27, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 27, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 27, 4);
}
else if($functionmenu=='RptCashsale'){
    $addcheck=checkprivilege($menuprivilegearray, 28, 1);
    $editcheck=checkprivilege($menuprivilegearray, 28, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 28, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 28, 4);
}
else if($functionmenu=='RptDailykitchen'){
    $addcheck=checkprivilege($menuprivilegearray, 29, 1);
    $editcheck=checkprivilege($menuprivilegearray, 29, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 29, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 29, 4);
}
else if($functionmenu=='RptDailyissue'){
    $addcheck=checkprivilege($menuprivilegearray, 30, 1);
    $editcheck=checkprivilege($menuprivilegearray, 30, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 30, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 30, 4);
}
else if($functionmenu=='Waiterorder'){
    $addcheck=checkprivilege($menuprivilegearray, 31, 1);
    $editcheck=checkprivilege($menuprivilegearray, 31, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 31, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 31, 4);
}
else if($functionmenu=='Vieworder'){
    $addcheck=checkprivilege($menuprivilegearray, 32, 1);
    $editcheck=checkprivilege($menuprivilegearray, 32, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 32, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 32, 4);
}
else if($functionmenu=='Invoiceview'){
    $addcheck=checkprivilege($menuprivilegearray, 33, 1);
    $editcheck=checkprivilege($menuprivilegearray, 33, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 33, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 33, 4);
}
else if($functionmenu=='RptRol'){
    $addcheck=checkprivilege($menuprivilegearray, 34, 1);
    $editcheck=checkprivilege($menuprivilegearray, 34, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 34, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 34, 4);
}
else if($functionmenu=='RptTodaysales'){
    $addcheck=checkprivilege($menuprivilegearray, 35, 1);
    $editcheck=checkprivilege($menuprivilegearray, 35, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 35, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 35, 4);
}
else if($functionmenu=='Customerstatus'){
    $addcheck=checkprivilege($menuprivilegearray, 36, 1);
    $editcheck=checkprivilege($menuprivilegearray, 36, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 36, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 36, 4);
}
else if($functionmenu=='Rptcashiersale'){
    $addcheck=checkprivilege($menuprivilegearray, 37, 1);
    $editcheck=checkprivilege($menuprivilegearray, 37, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 37, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 37, 4);
}
else if($functionmenu=='Rptsalesummery'){
    $addcheck=checkprivilege($menuprivilegearray, 38, 1);
    $editcheck=checkprivilege($menuprivilegearray, 38, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 38, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 38, 4);
}
else if($functionmenu=='Rptcancelsales'){
    $addcheck=checkprivilege($menuprivilegearray, 39, 1);
    $editcheck=checkprivilege($menuprivilegearray, 39, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 39, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 39, 4);
}
else if($functionmenu=='Rptitemreject'){
    $addcheck=checkprivilege($menuprivilegearray, 41, 1);
    $editcheck=checkprivilege($menuprivilegearray, 41, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 41, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 41, 4);
}
else if($functionmenu=='Directsalecoffeshop'){
    $addcheck=checkprivilege($menuprivilegearray, 42, 1);
    $editcheck=checkprivilege($menuprivilegearray, 42, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 42, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 42, 4);
}
else if($functionmenu=='Waitorordercoffeeshop'){
    $addcheck=checkprivilege($menuprivilegearray, 43, 1);
    $editcheck=checkprivilege($menuprivilegearray, 43, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 43, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 43, 4);
}
else if($functionmenu=='Paymentmode'){
    $addcheck=checkprivilege($menuprivilegearray, 44, 1);
    $editcheck=checkprivilege($menuprivilegearray, 44, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 44, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 44, 4);
}
else if($functionmenu=='ExpenseCategory'){
    $addcheck=checkprivilege($menuprivilegearray, 45, 1);
    $editcheck=checkprivilege($menuprivilegearray, 45, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 45, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 45, 4);
}
else if($functionmenu=='ExpenseEntry'){
    $addcheck=checkprivilege($menuprivilegearray, 46, 1);
    $editcheck=checkprivilege($menuprivilegearray, 46, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 46, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 46, 4);
}
else if($functionmenu=='StockTransfer'){
    $addcheck    = checkprivilege($menuprivilegearray, 47, 1);
    $editcheck   = checkprivilege($menuprivilegearray, 47, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 47, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 47, 4);
}
// else if($functionmenu=='Location'){
//     $addcheck    = checkprivilege($menuprivilegearray, 48, 1);
//     $editcheck   = checkprivilege($menuprivilegearray, 48, 2);
//     $statuscheck = checkprivilege($menuprivilegearray, 48, 3);
//     $deletecheck = checkprivilege($menuprivilegearray, 48, 4);
// }
else if($functionmenu=='StockReceive'){
    $addcheck    = checkprivilege($menuprivilegearray, 49, 1);
    $editcheck   = checkprivilege($menuprivilegearray, 49, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 49, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 49, 4);
}
else if($functionmenu=='Company'){
    $addcheck    = checkprivilege($menuprivilegearray, 50, 1);
    $editcheck   = checkprivilege($menuprivilegearray, 50, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 50, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 50, 4);
}
function checkprivilege($arraymenu, $menuID, $type){
    foreach($arraymenu as $array){
        if($array->menuid==$menuID){
            if($type==1){
                return $array->add;
            }
            else if($type==2){
                return $array->edit;
            }
            else if($type==3){
                return $array->statuschange;
            }
            else if($type==4){
                return $array->remove;
            }
        }
    }
}
?>
<textarea class="d-none" id="actiontext"><?php if($this->session->flashdata('msg')) {echo $this->session->flashdata('msg');} ?></textarea>

<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">
            <div class="sidenav-menu-heading">Core</div>
            <a class="nav-link p-0 px-3 py-2 text-dark" href="<?php echo base_url().'Welcome/Dashboard'; ?>">
                <div class="nav-link-icon"><i class="fas fa-desktop"></i></div>
                Dashboard
            </a>
            <?php if(menucheck($menuprivilegearray, 17)==1 | menucheck($menuprivilegearray, 18)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsematerialinfo" aria-expanded="false" aria-controls="collapsematerialinfo">
                <div class="nav-link-icon"><i class="fas fa-shopping-basket"></i></div>
                Material Information
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Materialcategory" | $controllermenu=="Materialdetail" ){echo 'show';} ?>" id="collapsematerialinfo" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 17)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Materialcategory'; ?>">Material Category</a>
                    <?php } if(menucheck($menuprivilegearray, 18)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Materialdetail'; ?>">Material Detail</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>
            <?php if(menucheck($menuprivilegearray, 4)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 text-dark" href="<?php echo base_url().'Customer'; ?>">
                <div class="nav-link-icon"><i class="fas fa-users"></i></div>
                Customer
            </a>
            <?php } ?>

            <?php if(menucheck($menuprivilegearray, 14)==1 | menucheck($menuprivilegearray, 12)==1 | menucheck($menuprivilegearray, 15)==1 | menucheck($menuprivilegearray, 21)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsepordergrn" aria-expanded="false" aria-controls="collapsepordergrn">
                <div class="nav-link-icon"><i class="fas fa-truck"></i></div>
                Supplier Management
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Purchaseorder" | $controllermenu=="Goodreceive" | $controllermenu=="Supplier" | $controllermenu=="Supplierpayment"){echo 'show';} ?>" id="collapsepordergrn" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 15)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Supplier'; ?>">Supplier</a>
                    <?php } if(menucheck($menuprivilegearray, 14)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Purchaseorder'; ?>">Purchase Order</a>
                    <?php } if(menucheck($menuprivilegearray, 12)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Goodreceive'; ?>">Good Receive Note</a>
                    <?php } if(menucheck($menuprivilegearray, 21)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Supplierpayment'; ?>">Supplier Payment</a>
                    <?php } if(menucheck($menuprivilegearray, 47)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'StockTransfer'; ?>">Stock Transfer</a>
                    <?php } if(menucheck($menuprivilegearray, 49)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'StockReceive'; ?>">Stock Receive</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>
            <?php if(menucheck($menuprivilegearray, 7)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 text-dark" href="<?php echo base_url().'Orders'; ?>">
                <div class="nav-link-icon"><i class="fas fa-clipboard"></i></div>
                Orders
            </a>
            <?php } if(menucheck($menuprivilegearray, 31)==1 | menucheck($menuprivilegearray, 32)==1 | menucheck($menuprivilegearray, 43)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsewaiter" aria-expanded="false" aria-controls="collapsewaiter">
                <div class="nav-link-icon"><i class="fas fa-user-edit"></i></div>
                Waiter
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Waiterorder" | $controllermenu=="Vieworder" | $controllermenu=="Waitorordercoffeeshop"){echo 'show';} ?>" id="collapsewaiter" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 31)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Waiterorder'; ?>">Create Order</a>
                    <?php }if(menucheck($menuprivilegearray, 43)==1){ ?>
                        <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Waitorordercoffeeshop'; ?>">Coffee Shop</a>
                    <?php }if(menucheck($menuprivilegearray, 32)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Vieworder'; ?>">View Order</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>
            <?php if(menucheck($menuprivilegearray, 16)==1 | menucheck($menuprivilegearray, 33)==1 | menucheck($menuprivilegearray, 42)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseinvoice" aria-expanded="false" aria-controls="collapseinvoice">
                <div class="nav-link-icon"><i class="fas fa-file-invoice"></i></div>
                Invoice
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Directsale" | $controllermenu=="Invoiceview" | $controllermenu=="Directsalecoffeshop"){echo 'show';} ?>" id="collapseinvoice" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 16)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Directsale'; ?>">Outlet Invoice</a>
                    <?php }if(menucheck($menuprivilegearray, 42)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Directsalecoffeshop'; ?>">Coffee Shop</a>
                    <?php }if(menucheck($menuprivilegearray, 33)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Invoiceview'; ?>">View Invoice</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 36)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 text-dark" href="<?php echo base_url().'Customerstatus'; ?>">
                <div class="nav-link-icon"><i class="fas fa-users"></i></div>
                Customer Order Status
            </a>
            <?php } ?>
            <?php if(menucheck($menuprivilegearray, 5)==1 | menucheck($menuprivilegearray, 6)==1 ){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseitem" aria-expanded="false" aria-controls="collapseitem">
                <div class="nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
                 Item
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($functionmenu=="Item" | $functionmenu=="Itemcategory" ){echo 'show';} ?>" id="collapseitem" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 6)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Itemcategory'; ?>">Item Category</a>
                    <?php } if(menucheck($menuprivilegearray, 5)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Item'; ?>">Item</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>
            <?php if(menucheck($menuprivilegearray, 13)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 text-dark" href="<?php echo base_url().'Semibom'; ?>">
                <div class="nav-link-icon"><i class="fas fa-briefcase"></i></div>
                Product BOM
            </a>
            <?php } ?>
            <?php if(menucheck($menuprivilegearray, 8)==1 | menucheck($menuprivilegearray, 9)==1 ){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsereservation" aria-expanded="false" aria-controls="collapsereservation">
                <div class="nav-link-icon"><i class="fas fa-suitcase"></i></div>
                 Reservation
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($functionmenu=="Reservation" | $functionmenu=="Reservationtype" ){echo 'show';} ?>" id="collapsereservation" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 8)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Reservation'; ?>">Reservation</a>
                    <?php } if(menucheck($menuprivilegearray, 9)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Reservationtype'; ?>">Reservation Category</a>
                    <?php } if(menucheck($menuprivilegearray, 11)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Reservationtable'; ?>">Reservation Table</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>
            <?php if(menucheck($menuprivilegearray, 44)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 text-dark" href="<?php echo base_url().'Paymentmode'; ?>">
                <div class="nav-link-icon"><i class="fas fa-money-check"></i></div>
                Payment Mode
            </a>
            <?php } ?>
            <?php if(menucheck($menuprivilegearray, 10)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 text-dark" href="<?php echo base_url().'Transactions'; ?>">
                <div class="nav-link-icon"><i class="fas fa-money-bill"></i></div>
                Transacation
            </a>
            <?php } ?>
            <?php if(menucheck($menuprivilegearray, 20)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 text-dark" href="<?php echo base_url().'Materialissue'; ?>">
                <div class="nav-link-icon"><i class="fas fa-share-square"></i></div>
                Material Issue
            </a>
            <?php } if(menucheck($menuprivilegearray, 19)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsekitchen" aria-expanded="false" aria-controls="collapsekitchen">
                <div class="nav-link-icon"><i class="fas fa-utensils"></i></div>
                 Kitchen
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($functionmenu=="Kitchen"){echo 'show';} ?>" id="collapsekitchen" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 19)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Kitchen'; ?>">Kitchen Process</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>

            <?php if(menucheck($menuprivilegearray, 27)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 text-dark" href="<?php echo base_url().'Tableno'; ?>">
                <div class="nav-link-icon"><i class="fas fa-table"></i></div>
                Table
            </a>
            <?php } ?>

            <?php if(menucheck($menuprivilegearray, 45)==1 | menucheck($menuprivilegearray, 46)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseexpenseinfo" aria-expanded="false" aria-controls="collapseexpenseinfo">
                <div class="nav-link-icon"><i class="fas fa-shopping-basket"></i></div>
                Expenses
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="ExpenseCategory" || $controllermenu=="ExpenseEntry"){echo 'show';} ?>" id="collapseexpenseinfo" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 45)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ExpenseCategory'; ?>">Expense Category</a>
                    <?php } if(menucheck($menuprivilegearray, 46)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ExpenseEntry'; ?>">Expense Entry</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>
            

            <?php if(menucheck($menuprivilegearray, 23)==1 | menucheck($menuprivilegearray, 24)==1 | menucheck($menuprivilegearray, 25)==1 | menucheck($menuprivilegearray, 26)==1| menucheck($menuprivilegearray, 28)==1 | menucheck($menuprivilegearray, 29)==1 | menucheck($menuprivilegearray, 30)==1 | menucheck($menuprivilegearray, 34)==1 | menucheck($menuprivilegearray, 35)==1 | menucheck($menuprivilegearray, 37)==1 | menucheck($menuprivilegearray, 38)==1 | menucheck($menuprivilegearray, 39)==1 | menucheck($menuprivilegearray, 41)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsereport" aria-expanded="false" aria-controls="collapsereport">
                <div class="nav-link-icon"><i class="fas fa-file"></i></div>
                Report
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="RptGRN" | $controllermenu=="RptStock" | $controllermenu=="RptProductwise" | $controllermenu=="RptTablewise" | $controllermenu=="RptCashsale"| $controllermenu=="RptDailykitchen"| $controllermenu=="RptDailyissue"| $controllermenu=="RptRol" | $controllermenu=="RptTodaysales" | $controllermenu=="Rptcashiersale" | $controllermenu=="Rptsalesummery" | $controllermenu=="Rptcancelsales" | $controllermenu=="Rptitemreject"){echo 'show';} ?>" id="collapsereport" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 23)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'RptGRN'; ?>">GRN Report</a>
                    <?php } if(menucheck($menuprivilegearray, 24)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'RptStock'; ?>">Stock Report</a>
                    <?php }if(menucheck($menuprivilegearray, 25)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'RptProductwise'; ?>">Product Wise Sales Report</a>
                    <?php }if(menucheck($menuprivilegearray, 26)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'RptTablewise'; ?>">Table Wise Sales Report</a>
                    <?php }if(menucheck($menuprivilegearray, 28)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'RptCashsale'; ?>">Cash Sales Report</a>
                    <?php }if(menucheck($menuprivilegearray, 29)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'RptDailykitchen'; ?>">Daily Kitchen Report</a>
                    <?php }if(menucheck($menuprivilegearray, 30)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'RptDailyissue'; ?>">Daily Issue Stock Report</a>
                    <?php }if(menucheck($menuprivilegearray, 34)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'RptRol'; ?>">Re Order Level Report</a>
                    <?php }if(menucheck($menuprivilegearray, 35)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'RptTodaysales'; ?>">Today Sales Report</a>
                    <?php }if(menucheck($menuprivilegearray, 37)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Rptcashiersale'; ?>">Cashierwise Sales Report</a>
                    <?php }if(menucheck($menuprivilegearray, 38)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Rptsalesummery'; ?>">Sales Summary Report</a>
                    <?php }if(menucheck($menuprivilegearray, 39)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Rptcancelsales'; ?>">Cancel Invoices Report</a>
                    <?php }if(menucheck($menuprivilegearray, 41)==1){ ?>
                        <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Rptitemreject'; ?>">Item Reject Report</a>
                    <?php } ?>
                </nav>
            </div>
            


          
            <?php } ?>
            <?php if(menucheck($menuprivilegearray, 1)==1 | menucheck($menuprivilegearray, 2)==1 | menucheck($menuprivilegearray, 3)==1 | menucheck($menuprivilegearray, 50)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseUser" aria-expanded="false" aria-controls="collapseUser">
                <div class="nav-link-icon"><i class="fas fa-user"></i></div>
                User Account
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($functionmenu=="Useraccount" | $functionmenu=="Usertype" | $functionmenu=="Userprivilege" | $functionmenu=="Company"){echo 'show';} ?>" id="collapseUser" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 1)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'User/Useraccount'; ?>">User Account</a>
                    <?php } if(menucheck($menuprivilegearray, 2)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'User/Usertype'; ?>">Type</a>
                    <?php } if(menucheck($menuprivilegearray, 3)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'User/Userprivilege'; ?>">Privilege</a>
                    <?php } if(menucheck($menuprivilegearray, 50)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Company'; ?>">Company Master</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="sidenav-footer">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Logged in as:</div>
            <div class="sidenav-footer-title"><?php echo ucfirst($_SESSION['name']); ?></div>
        </div>
    </div>
</nav>
