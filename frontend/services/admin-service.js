let AdminService = {
    // Modal references
    adminProductModal: null,
    adminDeleteModal: null,
    viewOrderModal: null,
    pendingDeleteId: null,

    // Initialize admin panel
    init: function() {
        console.log("Admin service initialized");
        
        // Initialize Bootstrap modals
        const addModalElement = document.getElementById('addProductModal');
        const deleteModalElement = document.getElementById('deleteProductModal');
        const viewOrderModalElement = document.getElementById('viewOrderModal');
        
        this.adminProductModal = new bootstrap.Modal(addModalElement);
        this.adminDeleteModal = new bootstrap.Modal(deleteModalElement);
        this.viewOrderModal = new bootstrap.Modal(viewOrderModalElement);
        
        // Setup event listeners
        this.setupEventListeners();
        
        // Load initial data
        this.loadProducts();
        this.loadOrders();
    },

    setupEventListeners: function() {
        const self = this;
        
        // Delete modal hidden event
        document.getElementById('deleteProductModal').addEventListener('hidden.bs.modal', function () {
            if (self.pendingDeleteId) {
                const idToDelete = self.pendingDeleteId;
                self.pendingDeleteId = null;
                self.executeDelete(idToDelete);
            }
        });
        
        // Add Product Button
        $('#add-product-btn').on('click', function() {
            self.adminProductModal.show();
        });
        
        // Add Product Form
        $('#addProductForm').on('submit', function(e) {
            e.preventDefault();
            const formData = Object.fromEntries(new FormData(this).entries());
            self.addProduct(formData);
        });
        
        // Confirm Delete Button
        $('#confirm-delete-btn').on('click', function() {
            const productId = $('#delete_product_id').val();
            if (!productId) {
                toastr.error("No product selected");
                return;
            }
            self.pendingDeleteId = productId;
            self.adminDeleteModal.hide();
        });
        
        // Tab change event
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            const target = $(e.target).attr('data-bs-target');
            if (target === '#orders') {
                self.loadOrders();
            } else if (target === '#products') {
                self.loadProducts();
            }
        });
    },

    addProduct: function(formData) {
        const self = this;
        ProductService.addProduct(formData, function(response) {
            self.adminProductModal.hide();
            $('#addProductForm')[0].reset();
            setTimeout(function() {
                self.loadProducts();
                toastr.success("Product added successfully");
            }, 300);
        });
    },

    executeDelete: function(productId) {
        const self = this;
        toastr.info("Deleting product...");
        
        ProductService.deleteProduct(productId, function(response) {
            if (response && response.success) {
                toastr.success("Product deleted successfully");
                setTimeout(function() {
                    self.loadProducts();
                }, 500);
            } else {
                toastr.error(response?.message || "Failed to delete product");
            }
        });
    },

    loadProducts: function() {
        ProductService.getAllProducts(function(products) {
            const tbody = $('#admin-products-body');
            tbody.empty();
            
            if (!products || products.length === 0) {
                tbody.append('<tr><td colspan="7" class="text-center">No products available</td></tr>');
                return;
            }
            
            products.forEach(product => {
                const productId = product.ID || product.id;
                const imagePath = product.image || 'frontend/img/default.jpg';
                
                tbody.append(`
                    <tr>
                        <td>${productId}</td>
                        <td><img src="${imagePath}" alt="${product.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;"></td>
                        <td>${product.name}</td>
                        <td>KM ${parseFloat(product.price).toFixed(2)}</td>
                        <td>${product.quantity}</td>
                        <td>${AdminService.getCategoryName(product.category_id)}</td>
                        <td>
                            <button class="btn btn-sm btn-danger delete-product-btn" data-id="${productId}" data-name="${product.name}">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                `);
            });
            
            // Attach delete button handlers
            $('.delete-product-btn').on('click', function() {
                const productId = $(this).data('id');
                const productName = $(this).data('name');
                AdminService.openDeleteModal(productId, productName);
            });
        });
    },

    loadOrders: function() {
        RestClient.get('orders', function(orders) {
            const tbody = $('#admin-orders-body');
            tbody.empty();
            
            if (!orders || orders.length === 0) {
                tbody.append('<tr><td colspan="7" class="text-center">No orders found</td></tr>');
                return;
            }
            
            // Sort by ID descending
            orders.sort((a, b) => (b.ID || b.id) - (a.ID || a.id));
            
            orders.forEach(order => {
                const orderId = order.ID || order.id;
                const customerId = order.customer_id || 'N/A';
                const amount = parseFloat(order.amount || 0).toFixed(2);
                const address = order.address || 'N/A';
                const date = order.date || order.Date || 'N/A';
                const time = order.time || order.Time || 'N/A';
                
                tbody.append(`
                    <tr>
                        <td>${orderId}</td>
                        <td>${customerId}</td>
                        <td>${address.substring(0, 30)}${address.length > 30 ? '...' : ''}</td>
                        <td class="text-primary fw-bold">KM ${amount}</td>
                        <td>${date}</td>
                        <td>${time}</td>
                        <td>
                            <button class="btn btn-sm btn-info view-order-btn" data-id="${orderId}">
                                <i class="fa fa-eye"></i> View
                            </button>
                        </td>
                    </tr>
                `);
            });
            
            // Attach view button handlers
            $('.view-order-btn').on('click', function() {
                const orderId = $(this).data('id');
                AdminService.viewOrderDetails(orderId);
            });
            
        }, function(error) {
            console.error("Error loading orders:", error);
            toastr.error("Failed to load orders");
        });
    },

    viewOrderDetails: function(orderId) {
        const self = this;
        $('#order-items-body').html('<tr><td colspan="4" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>');
        
        RestClient.get('orders', function(orders) {
            const order = orders.find(o => (o.ID || o.id) == orderId);
            
            if (!order) {
                toastr.error("Order not found");
                return;
            }
            
            // Populate order details
            $('#order-detail-id').text(orderId);
            $('#order-detail-customer').text(order.customer_id || 'N/A');
            $('#order-detail-date').text(order.date || order.Date || 'N/A');
            $('#order-detail-time').text(order.time || order.Time || 'N/A');
            $('#order-detail-amount').text('KM ' + parseFloat(order.amount || 0).toFixed(2));
            $('#order-detail-address').text(order.address || 'N/A');
            
            // Parse items
            let items = [];
            if (order.items) {
                try {
                    if (typeof order.items === 'string') {
                        items = JSON.parse(order.items);
                    } else if (Array.isArray(order.items)) {
                        items = order.items;
                    }
                } catch (e) {
                    console.error("Error parsing items:", e);
                }
            }
            
            const itemsBody = $('#order-items-body');
            itemsBody.empty();
            
            if (!items || items.length === 0) {
                itemsBody.append('<tr><td colspan="4" class="text-center text-warning">No items in this order</td></tr>');
            } else {
                let totalAmount = 0;
                
                items.forEach(item => {
                    const price = parseFloat(item.price || 0);
                    const quantity = parseInt(item.quantity || 0);
                    const subtotal = price * quantity;
                    totalAmount += subtotal;
                    
                    itemsBody.append(`
                        <tr>
                            <td>${item.name || 'Unknown Product'}</td>
                            <td>${quantity}</td>
                            <td>KM ${price.toFixed(2)}</td>
                            <td class="text-primary fw-bold">KM ${subtotal.toFixed(2)}</td>
                        </tr>
                    `);
                });
                
                itemsBody.append(`
                    <tr class="table-secondary fw-bold">
                        <td colspan="3" class="text-end">TOTAL:</td>
                        <td class="text-primary">KM ${totalAmount.toFixed(2)}</td>
                    </tr>
                `);
            }
            
            self.viewOrderModal.show();
            
        }, function(error) {
            console.error("Error loading order:", error);
            toastr.error("Failed to load order details");
        });
    },

    openDeleteModal: function(productId, productName) {
        $('#delete_product_id').val(productId);
        $('#delete-product-name').text(productName);
        this.adminDeleteModal.show();
    },

    getCategoryName: function(categoryId) {
        const categories = {
            1: 'Drinks',
            2: 'Fruits',
            4: 'Vegetables',
            5: 'Snacks'
        };
        return categories[categoryId] || 'Unknown';
    }
};