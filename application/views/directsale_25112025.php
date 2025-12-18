<?php 
include "include/header.php"; 
include "include/topnavbar.php"; 
?>

<style>
    :root {
        --primary: #8B4513;
        --secondary: #D2691E;
        --accent: #CD853F;
        --success: #28a745;
        --light: #f8f9fa;
        --dark: #343a40;
        --text-dark: #212529;
        --text-light: #f8f9fa;
        --border: #dee2e6;
        --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    body {
        background-color: #f5f5f5;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .pos-container {
        display: grid;
        grid-template-columns: 250px 1fr 350px;
        grid-template-rows: 1fr auto;
        gap: 15px;
        height: 100vh;
        padding: 15px;
        background: white;
    }

    /* Categories Panel */
    .categories-panel {
        background: white;
        border-radius: 10px;
        box-shadow: var(--shadow);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .categories-header {
        background: var(--primary);
        color: white;
        padding: 15px;
        font-weight: 600;
        text-align: center;
    }

    .search-container {
        padding: 15px;
        border-bottom: 1px solid var(--border);
    }

    .categories-list {
        flex: 1;
        overflow-y: auto;
        padding: 0;
    }

    .category-item {
        padding: 12px 15px;
        border-bottom: 1px solid var(--border);
        cursor: pointer;
        transition: all 0.2s;
    }

    .category-item:hover,
    .category-item.active {
        background-color: var(--secondary);
        color: white;
    }

    /* Products Panel */
    .products-panel {
        background: white;
        border-radius: 10px;
        box-shadow: var(--shadow);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .products-header {
        background: var(--primary);
        color: white;
        padding: 15px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .products-grid {
        /* display: grid; */
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
        padding: 15px;
        flex: 1;
        overflow-y: auto;
    }

    .product-card {
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s;
        cursor: pointer;
    }

    .product-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow);
    }

    .product-image {
        height: 100px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .product-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }

    .product-info {
        padding: 10px;
    }

    .product-name {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .product-price {
        color: var(--success);
        font-weight: 700;
        font-size: 16px;
    }

    /* Cart Panel */
    .cart-panel {
        background: white;
        border-radius: 10px;
        box-shadow: var(--shadow);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .cart-header {
        background: var(--primary);
        color: white;
        padding: 15px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cart-items {
        flex: 1;
        overflow-y: auto;
        padding: 15px;
    }

    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
    }

    .cart-item-details {
        flex: 1;
    }

    .cart-item-name {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .cart-item-price {
        color: var(--success);
        font-weight: 700;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .quantity-btn {
        width: 25px;
        height: 25px;
        border: 1px solid var(--border);
        background: white;
        border-radius: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .cart-summary {
        padding: 15px;
        border-top: 2px solid var(--border);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .total-row {
        font-weight: 700;
        font-size: 18px;
        color: var(--success);
        border-top: 1px solid var(--border);
        padding-top: 10px;
    }

    /* Customer Panel */
    .customer-panel {
        grid-column: 1 / span 2;
        background: white;
        border-radius: 10px;
        box-shadow: var(--shadow);
        padding: 15px;
    }

    .customer-header {
        background: var(--primary);
        color: white;
        padding: 12px 15px;
        margin: -15px -15px 15px -15px;
        border-radius: 10px 10px 0 0;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .customer-fields {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    /* Payment Panel */
    .payment-panel {
        background: white;
        border-radius: 10px;
        box-shadow: var(--shadow);
        padding: 15px;
        display: flex;
        flex-direction: column;
    }

    .payment-header {
        background: var(--primary);
        color: white;
        padding: 12px 15px;
        margin: -15px -15px 15px -15px;
        border-radius: 10px 10px 0 0;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .quick-amounts {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-bottom: 15px;
    }

    .amount-btn {
        padding: 8px;
        background: var(--light);
        border: 1px solid var(--border);
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s;
    }

    .amount-btn:hover {
        background: var(--secondary);
        color: white;
    }

    .payment-methods {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 15px;
    }

    .payment-method {
        padding: 10px;
        border: 2px solid var(--border);
        border-radius: 8px;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s;
    }

    .payment-method.active {
        border-color: var(--secondary);
        background: var(--secondary);
        color: white;
    }

    .checkout-btn {
        background: var(--success);
        color: white;
        border: none;
        padding: 15px;
        font-size: 18px;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        margin-top: auto;
        transition: all 0.3s;
    }

    .checkout-btn:hover {
        background: #218838;
        transform: translateY(-2px);
    }

    /* Utility Classes */
    .text-success {
        color: var(--success);
    }

    .text-accent {
        color: var(--accent);
    }

    .bg-light {
        background: var(--light);
    }

    /* Hide old layout elements */
    .old-layout {
        display: none;
    }
</style>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <div class="row card-deck mt-3">
                    <div class="card col-2 shadow-none">
                    	<img src="..." class="card-img-top" alt="...">
                    	<div class="card-body">
                    		<h5 class="card-title">Card title</h5>
                    		<p class="card-text">This is a longer card with supporting text below as a natural lead-in
                    			to additional content. This content is a little bit longer.</p>
                    		<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                    	</div>
                    </div>
                    <div class="card col-7 shadow-none">
                    	<img src="..." class="card-img-top" alt="...">
                    	<div class="card-body">
                    		<h5 class="card-title">Card title</h5>
                    		<p class="card-text">This card has supporting text below as a natural lead-in to additional
                    			content.</p>
                    		<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                    	</div>
                    </div>
                    <div class="card col-3 shadow-none">
                    	<img src="..." class="card-img-top" alt="...">
                    	<div class="card-body">
                    		<h5 class="card-title">Card title</h5>
                    		<p class="card-text">This is a wider card with supporting text below as a natural lead-in to
                    			additional content. This card has even longer content than the first to show that equal
                    			height action.</p>
                    		<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                    	</div>
                    </div>
                </div>
                <div class="row mt-3 fixed-bottom">
                    <div class="col-12">
                        <div class="card rounded-0">
                            <div class="card-body py-2">
                                <button type="button" id="payment" class="btn btn-sm btn-light shadow-sm rounded-lg mr-2"><img src="<?php echo base_url() ?>images/button/cp.png" alt="" clsaa="img-fluid"></button>
                                <button type="button" id="payment" class="btn btn-sm btn-light shadow-sm rounded-lg mr-2"><img src="<?php echo base_url() ?>images/button/vp.png" alt="" clsaa="img-fluid"></button>
                                <button type="button" id="payment" class="btn btn-sm btn-light shadow-sm rounded-lg mr-2"><img src="<?php echo base_url() ?>images/button/pm.png" alt="" clsaa="img-fluid"></button>
                                <button type="button" id="payment" class="btn btn-sm btn-light shadow-sm rounded-lg mr-2"><img src="<?php echo base_url() ?>images/button/ue.png" alt="" clsaa="img-fluid"></button>
                                <button type="button" id="payment" class="btn btn-sm btn-light shadow-sm rounded-lg mr-2"><img src="<?php echo base_url() ?>images/button/so.png" alt="" clsaa="img-fluid"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Include all your existing modals -->
<!-- Modal Qty -->
<div class="modal fade" id="modalqty" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add to Cart</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-none bg-warning border-warning">
                            <div class="card-body p-2 text-center">
                                <h4 class="text-light font-weight-light" id="selectedProductName">Product Name</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <form id="addToCartForm">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" required>
                            </div>
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Price (Rs.)</label>
                                <input type="number" name="price" id="price" class="form-control" step="0.01" required>
                            </div>
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Discount (%)</label>
                                <input type="number" name="discount" id="discount" class="form-control" value="0" min="0" max="100">
                            </div>
                            <div class="form-group mt-3 text-right">
                                <button type="button" class="btn btn-danger" id="addToCartBtn">Add to Cart</button>
                            </div>
                            <input type="hidden" name="productId" id="productId">
                            <input type="hidden" name="productName" id="productName">
                            <input type="hidden" name="tableID" id="tableID">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Keep all your existing modals -->
<?php include "include/footerscripts.php"; ?>

<script>
$(document).ready(function() {
    let cart = [];
    let currentCategory = 0;
    let currentTable = '';
    let allCategories = [];
    let allProducts = [];

    // Initialize categories and products
    initializeData();

    // Initialize data
    function initializeData() {
        // Store all categories for search
        allCategories = [
            { id: 0, name: 'All Items' },
            <?php foreach ($category->result() as $rowcategory) { ?>
            { id: <?php echo $rowcategory->idtbl_res_item_category; ?>, name: '<?php echo $rowcategory->categoryname; ?>' },
            <?php } ?>
        ];

        // Set "All Items" as active and load products
        $('.category-item').first().addClass('active');
        currentCategory = 0;
        loadProducts(0);
    }

    // Category selection
    $('.category-item').click(function() {
        $('.category-item').removeClass('active');
        $(this).addClass('active');
        
        currentCategory = $(this).data('category');
        console.log('Loading category:', currentCategory); // Debug log
        loadProducts(currentCategory);
    });

    // Category search functionality
    $('#categorySearch').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        filterCategories(searchTerm);
    });

    $('#categorySearchBtn').click(function() {
        const searchTerm = $('#categorySearch').val().toLowerCase();
        filterCategories(searchTerm);
    });

    function filterCategories(searchTerm) {
        const categoriesList = $('.categories-list');
        let html = '';

        if (searchTerm === '') {
            // Show all categories when search is empty
            allCategories.forEach(category => {
                const activeClass = category.id === currentCategory ? 'active' : '';
                html += `<div class="category-item ${activeClass}" data-category="${category.id}">${category.name}</div>`;
            });
        } else {
            // Filter categories based on search term
            const filteredCategories = allCategories.filter(category => 
                category.name.toLowerCase().includes(searchTerm)
            );

            if (filteredCategories.length > 0) {
                filteredCategories.forEach(category => {
                    const activeClass = category.id === currentCategory ? 'active' : '';
                    html += `<div class="category-item ${activeClass}" data-category="${category.id}">${category.name}</div>`;
                });
            } else {
                html = '<div class="category-item text-muted">No categories found</div>';
            }
        }

        categoriesList.html(html);

        // Re-bind click events for filtered categories
        $('.category-item').click(function() {
            $('.category-item').removeClass('active');
            $(this).addClass('active');
            
            currentCategory = $(this).data('category');
            loadProducts(currentCategory);
        });
    }

    // Product search functionality
    $('#productSearch').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        if (searchTerm.length >= 2) { // Start searching after 2 characters
            searchProducts(searchTerm, currentCategory);
        } else if (searchTerm === '') {
            // Reload original products when search is cleared
            loadProducts(currentCategory);
        }
    });

    $('#productSearchBtn').click(function() {
        const searchTerm = $('#productSearch').val().toLowerCase();
        if (searchTerm) {
            searchProducts(searchTerm, currentCategory);
        } else {
            loadProducts(currentCategory);
        }
    });

    // Load products function
    function loadProducts(categoryId) {
        $('#productsGrid').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-muted mb-3"></i><p class="text-muted">Loading products...</p></div>');
        
        $.ajax({
            url: '<?php echo base_url(); ?>Directsale/Getproductlist',
            type: 'POST',
            data: { categoryID: categoryId },
            success: function(response) {
                $('#productsGrid').html(response);
                // Store products for search functionality
                extractProductsFromGrid();
            },
            error: function(xhr, status, error) {
                console.error('Error loading products:', error);
                $('#productsGrid').html('<div class="text-center py-5"><i class="fas fa-exclamation-triangle fa-2x text-danger mb-3"></i><p class="text-danger">Error loading products</p></div>');
            }
        });
    }

    // Extract products from the grid for search functionality
    function extractProductsFromGrid() {
        allProducts = [];
        $('.itemdiv').each(function() {
            const productId = $(this).attr('id');
            const productName = $(this).find('h4').text().trim();
            allProducts.push({
                id: productId,
                name: productName,
                element: $(this).clone() // Clone the element for later use
            });
        });
    }

    // Search products function
    function searchProducts(searchTerm, categoryId) {
        if (searchTerm === '') {
            loadProducts(categoryId);
            return;
        }

        $('#productsGrid').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-muted mb-3"></i><p class="text-muted">Searching products...</p></div>');

        // First, load all products if we haven't already
        if (allProducts.length === 0) {
            $.ajax({
                url: '<?php echo base_url(); ?>Directsale/Getproductlist',
                type: 'POST',
                data: { categoryID: categoryId },
                success: function(response) {
                    $('#productsGrid').html(response);
                    extractProductsFromGrid();
                    performProductSearch(searchTerm);
                }
            });
        } else {
            performProductSearch(searchTerm);
        }
    }

    function performProductSearch(searchTerm) {
        const filteredProducts = allProducts.filter(product => 
            product.name.toLowerCase().includes(searchTerm.toLowerCase())
        );

        if (filteredProducts.length > 0) {
            let html = '<div class="row row-cols-1 row-cols-md-4">';
            let counter = 0;

            filteredProducts.forEach((product, index) => {
                if (counter % 4 === 0 && counter !== 0) {
                    html += '</div><div class="row row-cols-1 row-cols-md-4">';
                }

                html += `
                    <div class="col mb-4">
                        ${product.element[0].outerHTML}
                    </div>
                `;
                counter++;
            });

            html += '</div>';
            $('#productsGrid').html(html);

            // Re-bind click events for searched products
            $(document).off('click', '.itemdiv').on('click', '.itemdiv', function() {
                const productId = $(this).attr('id');
                const productName = $(this).find('h4').text();
                
                // Get product details via AJAX
                $.ajax({
                    url: '<?php echo base_url(); ?>Directsale/Getproductdetails',
                    type: 'POST',
                    data: { recordID: productId },
                    success: function(response) {
                        const product = JSON.parse(response);
                        $('#selectedProductName').text(product.itemname);
                        $('#productId').val(product.id);
                        $('#productName').val(product.itemname);
                        $('#price').val(product.price);
                        $('#tableID').val(currentTable);
                        $('#modalqty').modal('show');
                    }
                });
            });

        } else {
            $('#productsGrid').html(`
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No products found for "${searchTerm}"</p>
                    <button class="btn btn-primary btn-sm" onclick="clearProductSearch()">Show All Products</button>
                </div>
            `);
        }
    }

    // Clear product search and show all products
    window.clearProductSearch = function() {
        $('#productSearch').val('');
        loadProducts(currentCategory);
    };

    // Product click handler
    $(document).on('click', '.itemdiv', function() {
        const productId = $(this).attr('id');
        const productName = $(this).find('h4').text();
        
        // Get product details via AJAX
        $.ajax({
            url: '<?php echo base_url(); ?>Directsale/Getproductdetails',
            type: 'POST',
            data: { recordID: productId },
            success: function(response) {
                const product = JSON.parse(response);
                $('#selectedProductName').text(product.itemname);
                $('#productId').val(product.id);
                $('#productName').val(product.itemname);
                $('#price').val(product.price);
                $('#tableID').val(currentTable);
                $('#modalqty').modal('show');
            }
        });
    });

    // Add to cart functionality
    $('#addToCartBtn').click(function() {
        const productId = $('#productId').val();
        const productName = $('#productName').val();
        const quantity = parseInt($('#quantity').val());
        const price = parseFloat($('#price').val());
        const discount = parseFloat($('#discount').val());
        const tableID = $('#tableID').val();

        // Calculate amounts
        const subtotal = price * quantity;
        const discountAmount = (subtotal * discount) / 100;
        const total = subtotal - discountAmount;

        // Add to cart array
        const cartItem = {
            id: productId,
            name: productName,
            quantity: quantity,
            price: price,
            discount: discount,
            subtotal: subtotal,
            discountAmount: discountAmount,
            total: total
        };

        // Save to database via AJAX
        $.ajax({
            url: '<?php echo base_url(); ?>Directsale/Directsaletempinsertupdate',
            type: 'POST',
            data: {
                productID: productId,
                sale: price,
                qty: quantity,
                tableID: tableID,
                discountpresentage: discount
            },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.actiontype == '1') {
                    cart.push(cartItem);
                    updateCartDisplay();
                    $('#modalqty').modal('hide');
                    $('#addToCartForm')[0].reset();
                    
                    // Show success notification
                    action(result.action);
                } else {
                    action(result.action);
                }
            }
        });
    });

    // Update cart display
    function updateCartDisplay() {
        const cartItems = $('#cartItems');
        const cartCount = $('#cartCount');
        const subtotalAmount = $('#subtotalAmount');
        const taxAmount = $('#taxAmount');
        const discountAmount = $('#discountAmount');
        const totalAmount = $('#totalAmount');

        let subtotal = 0;
        let totalDiscount = 0;
        let html = '';

        cart.forEach((item, index) => {
            subtotal += item.subtotal;
            totalDiscount += item.discountAmount;
            
            html += `
                <div class="cart-item">
                    <div class="cart-item-details">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-price">Rs. ${item.price.toFixed(2)} x ${item.quantity}</div>
                    </div>
                    <div class="quantity-control">
                        <button class="quantity-btn" onclick="updateQuantity(${index}, -1)">-</button>
                        <span>${item.quantity}</span>
                        <button class="quantity-btn" onclick="updateQuantity(${index}, 1)">+</button>
                        <button class="btn btn-sm btn-danger ml-2" onclick="removeFromCart(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        if (cart.length === 0) {
            html = '<div class="text-center py-5"><i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i><p class="text-muted">Your cart is empty</p></div>';
        }

        cartItems.html(html);
        cartCount.text(cart.length + ' items');
        
        const tax = subtotal * 0.1; // 10% tax
        const total = subtotal - totalDiscount + tax;

        subtotalAmount.text('Rs. ' + subtotal.toFixed(2));
        taxAmount.text('Rs. ' + tax.toFixed(2));
        discountAmount.text('Rs. ' + totalDiscount.toFixed(2));
        totalAmount.text('Rs. ' + total.toFixed(2));
    }

    // Global functions for cart operations
    window.updateQuantity = function(index, change) {
        cart[index].quantity += change;
        if (cart[index].quantity < 1) {
            cart[index].quantity = 1;
        }
        cart[index].subtotal = cart[index].price * cart[index].quantity;
        cart[index].discountAmount = (cart[index].subtotal * cart[index].discount) / 100;
        cart[index].total = cart[index].subtotal - cart[index].discountAmount;
        updateCartDisplay();
    };

    window.removeFromCart = function(index) {
        cart.splice(index, 1);
        updateCartDisplay();
    };

    // Table selection
    $('.tablediv').click(function() {
        currentTable = $(this).attr('id');
        $('#tableID').val(currentTable);
        $('#modaltable').modal('hide');

        // Load existing items for this table
        $.ajax({
            type: "POST",
            data: { tableID: currentTable },
            url: '<?php echo base_url() ?>Directsale/Getaddeditems',
            success: function(result) {
                const items = JSON.parse(result);
                cart = [];
                
                items.forEach(item => {
                    cart.push({
                        id: item.itemID,
                        name: item.itemname,
                        quantity: item.qty,
                        price: item.saleprice,
                        discount: 0,
                        subtotal: item.total,
                        discountAmount: 0,
                        total: item.total
                    });
                });
                
                updateCartDisplay();
            }
        });
    });

    // Payment method selection
    $('.payment-method').click(function() {
        $('.payment-method').removeClass('active');
        $(this).addClass('active');
    });

    // Quick amount buttons
    $('.amount-btn').click(function() {
        const amount = $(this).text().replace('₨', '');
        $('#tenderedAmount').val(amount);
        calculateChange();
    });

    // Calculate change
    function calculateChange() {
        const total = parseFloat($('#totalAmount').text().replace('Rs. ', '')) || 0;
        const tendered = parseFloat($('#tenderedAmount').val()) || 0;
        const change = tendered - total;
        $('#changeAmount').val(change >= 0 ? 'Rs. ' + change.toFixed(2) : 'Rs. 0.00');
    }

    $('#tenderedAmount').on('input', calculateChange);

    // Checkout functionality
    $('#checkoutBtn').click(function() {
        if (cart.length === 0) {
            alert('Please add items to cart before checkout');
            return;
        }

        const paymentMethod = $('.payment-method.active').data('method');
        const tenderedAmount = parseFloat($('#tenderedAmount').val()) || 0;
        const totalAmount = parseFloat($('#totalAmount').text().replace('Rs. ', ''));

        if (tenderedAmount < totalAmount && paymentMethod == '1') {
            alert('Tendered amount is less than total amount');
            return;
        }

        processCheckout();
    });

    function processCheckout() {
        // Prepare data for checkout - FIXED DATA STRUCTURE
        const tableData = cart.map(item => ({
            'col_1': item.name,        // Product name
            'col_2': item.quantity.toString(), // Quantity
            'col_3': item.price.toString(), // Price
            'col_5': item.total.toString(), // Total
            'col_6': item.id,          // Product ID
            // Alternative structure for compatibility
            'name': item.name,
            'quantity': item.quantity,
            'price': item.price,
            'total': item.total,
            'id': item.id
        }));

        const paymentMethod = $('.payment-method.active').data('method');
        const tenderedAmount = parseFloat($('#tenderedAmount').val()) || 0;

        const tableDataPay = [{
            'col_1': paymentMethod,    // Payment method
            'col_7': tenderedAmount.toString(), // Amount
            'col_3': '',               // Bank
            'col_4': '',               // Branch  
            'col_5': '',               // Cheque No
            'col_6': '',               // Cheque Date
            // Alternative structure for compatibility
            'method': paymentMethod,
            'amount': tenderedAmount,
            'bank': '',
            'branch': '',
            'chequeno': '',
            'chequedate': ''
        }];

        const total = cart.reduce((sum, item) => sum + item.subtotal, 0);
        const distotal = cart.reduce((sum, item) => sum + item.discountAmount, 0);
        const nettotal = cart.reduce((sum, item) => sum + item.total, 0);
        const paytotal = tenderedAmount;

        $.ajax({
            type: "POST",
            data: {
                tableData: tableData,
                tableDataPay: tableDataPay,
                total: total,
                distotal: distotal,
                nettotal: nettotal,
                paytotal: paytotal,
                billtype: '1',
                teminvoiceID: $('#hiddenteminvoiceid').val() || '0'
            },
            url: '<?php echo base_url() ?>Directsale/Directsaleinsertupdate',
            success: function(result) {
                const objfirst = JSON.parse(result);
                if(objfirst.actiontype == '1'){
                    action(objfirst.action);
                    // Print receipt
                    posprintbill(objfirst.invoiceid);
                    
                    // Reset cart
                    cart = [];
                    updateCartDisplay();
                    $('#tenderedAmount').val('');
                    $('#changeAmount').val('Rs. 0.00');
                } else {
                    action(objfirst.action);
                }
            },
            error: function(xhr, status, error) {
                console.error('Checkout error:', error);
                alert('Error during checkout. Please try again.');
            }
        });
    }

    // Online orders
    $("#orderbtn").on('click', function(e) {
        $('#modalorders').modal('show');
        $.ajax({
            method: "POST",
            data: {},
            url: '<?php echo base_url() ?>Directsale/Getonlineorders',
            success: function(result) {
                $('#ordertable').html(result);
            }
        });
    });

    // Add online order to cart
    $(document).on("click", ".btnaddtocart", function() {
        const productID = $(this).data('productid');
        const product = $(this).data('product');
        const qty = $(this).data('qty');
        const sale = $(this).data('sale');
        const discount = $(this).data('discount');

        const cartItem = {
            id: productID,
            name: product,
            quantity: qty,
            price: sale,
            discount: 0,
            subtotal: sale * qty,
            discountAmount: discount,
            total: (sale * qty) - discount
        };

        cart.push(cartItem);
        updateCartDisplay();
        $('#modalorders').modal('hide');
    });
});

// Keep all your existing utility functions
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

function action(data) {
    var obj = JSON.parse(data);
    $.notify({
        icon: obj.icon,
        title: obj.title,
        message: obj.message,
        url: obj.url,
        target: obj.target
    }, {
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
            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
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
    setTimeout(function() {
        window.location.reload();
    }, 3000);
}

function creditprintbill(invoiceid){
    window.open('<?php echo base_url() ?>Directsale/Getcreditprintbill/'+invoiceid, "_blank");
    setTimeout(function() {
        window.location.reload();
    }, 3000);
}
</script>

<?php include "include/footer.php"; ?>