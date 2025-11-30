function activestatus(viewId) {
    document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
        link.classList.toggle('active', link.getAttribute('href') === `#${viewId}`);
    });
    document.querySelector('.navbar-brand').classList.toggle('active', viewId === 'home');
}

function showActiveSection(viewId) {
    document.querySelectorAll('#spapp section').forEach(sec => {
        sec.classList.toggle('active', sec.id === viewId);
    });
}

let cart = [];
let allProducts = [];
let currentCategory = "all";
let currentSort = "default";

function loadCart() {
    const savedCart = localStorage.getItem('cart');
    if (savedCart) {
        cart = JSON.parse(savedCart);
    }
}

function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

function getCategoryId(categoryName) {
    if (!categoryName) return null;
    
    const categoryMap = {
        'drinks': 1,
        'fruits': 2,
        'snacks': 5,
        'vegetables': 4
    };
    return categoryMap[categoryName.toLowerCase()] || null;
}

function getCategoryNameById(categoryId) {
    const categoryMap = {
        1: 'Drinks',
        2: 'Fruits',
        4: 'Vegetables',
        5: 'Snacks'
    };
    return categoryMap[categoryId] || 'Product';
}

function getProductCategory(product) {
    //check if category_id exists 
    if (product.category_id) {
        return getCategoryNameById(product.category_id);
    }
    //check if category exists as a string
    if (product.category) {
        return product.category;
    }
    return 'Product';
}

function renderProducts(category = "all", sort = "default") {
    console.log("Rendering products. Category:", category, "Sort:", sort);
    console.log("All products:", allProducts);
    
    currentCategory = category;
    currentSort = sort;

    const listContainer = document.getElementById("productList");
    if (!listContainer) {
        console.error("Product list container not found!");
        return;
    }

    listContainer.innerHTML = "";
    
    if (!allProducts || allProducts.length === 0) {
        listContainer.innerHTML = '<div class="col-12 text-center"><p>No products available</p></div>';
        return;
    }
    
    //filter by category
    let filtered = allProducts;
    
    if (category !== "all") {
        const categoryId = getCategoryId(category);
        console.log("Filtering by category:", category, "ID:", categoryId);
        
        filtered = allProducts.filter(p => {
            //match by category_id 
            if (p.category_id && p.category_id == categoryId) {
                return true;
            }
            //match by category name 
            if (p.category && p.category.toLowerCase() === category.toLowerCase()) {
                return true;
            }
            return false;
        });
        
        console.log("Filtered products:", filtered);
    }

    if (sort === 'price-asc') {
        filtered.sort((a, b) => parseFloat(a.price) - parseFloat(b.price));
    } else if (sort === 'price-desc') {
        filtered.sort((a, b) => parseFloat(b.price) - parseFloat(a.price));
    } else {
        filtered.sort((a, b) => {
            const idA = a.ID || a.id;
            const idB = b.ID || b.id;
            return idA - idB;
        });
    }

    if (filtered.length === 0) {
        listContainer.innerHTML = `<div class="col-12 text-center"><p>No products found in ${category === "all" ? "this store" : category}</p></div>`;
        return;
    }

    filtered.forEach(product => {
        const productId = product.ID || product.id;
        const categoryName = getProductCategory(product);
        const imagePath = product.image || 'frontend/img/default.jpg';
        
        const card = `
        <div class="col-md-6 col-lg-4 col-xl-3 product-item" data-category="${categoryName.toLowerCase()}">
          <div class="rounded position-relative fruite-item">
            <div class="fruite-img">
              <img src="${imagePath}" class="img-fluid w-100 rounded-top" alt="${product.name}" style="height: 250px; object-fit: cover;">
            </div>
            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                  style="top: 10px; left: 10px;">${categoryName}</div>
            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
              <h4>${product.name}</h4>
              <div class="d-flex justify-content-between flex-lg-wrap">
                <p class="text-dark fs-5 fw-bold mb-0">KM ${parseFloat(product.price).toFixed(2)} / kg</p>
                <button class="btn border border-secondary rounded-pill px-3 text-primary add-to-cart-btn" data-product-id="${productId}">
                  <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                </button>
              </div>
            </div>
          </div>
        </div>`;
        listContainer.innerHTML += card;
    });
    
   
    setupCartButtonListeners();
}


function loadProducts() {
    console.log("Loading products...");
    
    ProductService.getAllProducts(function(products) {
        console.log("Products loaded successfully:", products);
        allProducts = products;
        renderProducts("all", "default");
    });
}


function addToCart(productId) {
    const id = parseInt(productId);
    console.log("Adding to cart - Product ID:", id);
    console.log("Available products:", allProducts);
    
    const product = allProducts.find(p => (p.ID || p.id) == id);

    if (!product) {
        console.error("Product not found:", id);
        toastr.error("Product not found");
        return;
    }

    console.log("Found product:", product);

    const existingItem = cart.find(item => (item.product.ID || item.product.id) == id);

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({ product: product, quantity: 1 });
    }
    
    saveCart();
    console.log("Cart updated:", cart);
    toastr.success(`${product.name} added to cart`);
}


function removeFromCart(productId) {
    const id = parseInt(productId);
    cart = cart.filter(item => (item.product.ID || item.product.id) != id);
    saveCart();
    renderCart();
}


function updateCartQuantity(productId, newQuantity) {
    const id = parseInt(productId);
    const quantity = parseInt(newQuantity);

    if (quantity <= 0) {
        removeFromCart(id);
    } else {
        const item = cart.find(item => (item.product.ID || item.product.id) == id);
        if (item) {
            item.quantity = quantity;
            saveCart();
            renderCart();
        }
    }
}


function renderCart() {
    const cartBody = document.getElementById("cartItems");
    const total = document.getElementById("total");
    
    if (!cartBody || !total) return;

    cartBody.innerHTML = '';
    let subtotal = 0;

    cart.forEach(item => {
        const productId = item.product.ID || item.product.id;
        const itemTotal = parseFloat(item.product.price) * item.quantity;
        subtotal += itemTotal;

        const imagePath = item.product.image || 'frontend/img/default.jpg';

        const row = `
            <tr data-product-id="${productId}">
                <th scope="row">
                    <div class="d-flex align-items-center">
                        <img src="${imagePath}" class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px; object-fit: cover;" alt="${item.product.name}">
                    </div>
                </th>
                <td><p class="mb-0 mt-4">${item.product.name}</p></td>
                <td><p class="mb-0 mt-4">KM ${parseFloat(item.product.price).toFixed(2)}</p></td>
                <td>
                    <div class="input-group quantity mt-4" style="width: 100px;">
                        <div class="input-group-btn">
                            <button class="btn btn-sm btn-minus rounded-circle bg-light border" data-product-id="${productId}">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <input type="text" class="form-control form-control-sm text-center border-0" value="${item.quantity}" readonly>
                        <div class="input-group-btn">
                            <button class="btn btn-sm btn-plus rounded-circle bg-light border" data-product-id="${productId}">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </td>
                <td>
                    <p class="mb-0 mt-4 cart-item-total">KM ${itemTotal.toFixed(2)}</p>
                </td>
                <td>
                    <button class="btn btn-md rounded-circle bg-light border remove-item-btn mt-4" data-product-id="${productId}">
                        <i class="fa fa-times text-danger"></i>
                    </button>
                </td>
            </tr>
        `;
        cartBody.innerHTML += row;
    });

    total.textContent = `KM ${subtotal.toFixed(2)}`;
    
    if (cart.length === 0) {
        cartBody.innerHTML = '<tr><td colspan="6" class="text-center py-5">Your cart is empty.</td></tr>';
        total.textContent = 'KM 0.00';
    }
}


const setupCartButtonListeners = () => {
    $(document).off('click', '.add-to-cart-btn').on('click', '.add-to-cart-btn', function() {
        const productId = $(this).data('productId');
        console.log("Add to cart button clicked - ID:", productId);
        addToCart(productId);
    });

    $(document).off('click', '.remove-item-btn').on('click', '.remove-item-btn', function() {
        const productId = $(this).data('productId');
        removeFromCart(productId);
    });
    
    $(document).off('click', '.quantity button').on('click', '.quantity button', function() {
        const isMinus = $(this).hasClass('btn-minus');
        const quantityContainer = $(this).closest('.quantity');
        const inputElement = quantityContainer.find('input');
        
        if (inputElement.length) {
            const productId = $(this).data('productId');
            let quantity = parseInt(inputElement.val());
            
            if (isMinus) {
                quantity = Math.max(0, quantity - 1);
            } else {
                quantity += 1;
            }
            
            updateCartQuantity(productId, quantity);
        }
    });
};

function setupFilter() {
    $(document).off('click', '.filter-btn').on('click', '.filter-btn', function() {
        const $activeSection = $("#spapp section.active");
        $activeSection.find(".filter-btn").removeClass("active");
        $(this).addClass("active");
        
        const category = $(this).data('category');
        console.log("Filter clicked - Category:", category);
        renderProducts(category, currentSort);
    });
}

function setupSortControls() {
    $(document).off('click', '.sort-btn').on('click', '.sort-btn', function() {
        const $activeSection = $("#spapp section.active");
        $activeSection.find(".sort-btn").removeClass("active");
        $(this).addClass("active");

        const sortType = $(this).data('sort');
        console.log("Sort clicked - Type:", sortType);
        renderProducts(currentCategory, sortType);
    });
}

$(document).ready(function() {
    console.log("Document ready, initializing app...");
    

    loadCart();
    
    var app = $.spapp({
        defaultView: "#home",
        templateDir: "frontend/views/"
    });

    app.route({
        view: "home",
        load: "home.html",
        onReady: function() {
            console.log("Home view loaded");
            activestatus("home");
            showActiveSection("home");
            loadProducts();
        }
    });

    app.route({
        view: "shop",
        load: "shop.html",
        onReady: function() {
            console.log("Shop view loaded");
            activestatus("shop");
            showActiveSection("shop");
            loadProducts();
        }
    });

    app.route({
        view: "contact",
        load: "contact.html",
        onReady: function() {
            console.log("Contact view loaded");
            activestatus("contact");
            showActiveSection("contact");
        }
    });

    app.route({
        view: "cart",
        load: "cart.html",
        onReady: function() {
            console.log("Cart view loaded");
            activestatus("cart");
            showActiveSection("cart");
            renderCart();
        }
    });

    app.route({
        view: "profile",
        load: "profile.html",
        onReady: function() {
            console.log("Profile view loaded");
            activestatus("profile");
            showActiveSection("profile");
        }
    });

    app.route({
        view: "register",
        load: "register.html",
        onReady: function() {
            console.log("Register view loaded");
            activestatus("register");
            showActiveSection("register");
        }
    });
    
    app.route({
        view: "checkout",
        load: "checkout.html",
        onReady: function() {
            console.log("Checkout view loaded");
            activestatus("checkout");
            showActiveSection("checkout");
        }
    });
    
    app.route({
        view: "admin",
        load: "admin.html",
        onReady: function() {
            console.log("Admin view loaded");
            const user = UserService.getCurrentUser();
            if (!user || user.role !== Constants.ADMIN_ROLE) {
                toastr.error("Access denied");
                window.location.hash = "#home";
                return;
            }
            activestatus("admin");
            showActiveSection("admin");
        }
    });

    app.run();
    
    
    UserService.updateNavigation();
});

document.addEventListener('DOMContentLoaded', () => {
    console.log("DOM loaded, setting up event listeners...");
    setupFilter();
    setupCartButtonListeners();
    setupSortControls();
});