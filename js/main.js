
const products = [
    { id: 1, name: "Grapes", category: "fruits", price: 12, img: "/img/fruite-item-5.jpg" },
    { id: 2, name: "Raspberries", category: "fruits", price: 7, img: "/img/fruite-item-2.jpg" },
    { id: 3, name: "Banana", category: "fruits", price: 4, img: "/img/fruite-item-3.jpg" },
    { id: 4, name: "Oranges", category: "fruits", price: 4, img: "/img/fruite-item-1.jpg" },
    { id: 5, name: "Tomato", category: "vegetables", price: 6, img: "/img/vegetable-item-1.jpg" },
    { id: 6, name: "Potato", category: "vegetables", price: 3, img: "/img/vegetable-item-5.jpg" },
    { id: 7, name: "Kinder Bueno", category: "snacks", price: 1.50, img: "/img/kinderbueno.jpg" },
    { id: 8, name: "Fanta Orange", category: "drinks", price: 1.35, img: "/img/fanta.png" }
];


let cart = []; 


let currentCategory = "all";
let currentSort = "default"; 

//render products 
function renderProducts(category = currentCategory, sort = currentSort) {
    currentCategory = category;
    currentSort = sort;

   
    const listContainer = document.getElementById("productList");
    if (!listContainer) return;

    listContainer.innerHTML = "";
    //sort category
    let filtered = category === "all" ? products : products.filter(p => p.category === category);

    //sort price
    if (sort === 'price-asc') {
        filtered.sort((a, b) => a.price - b.price);
    } else if (sort === 'price-desc') {
        filtered.sort((a, b) => b.price - a.price);
    } else {
        // Default sort by ID
        filtered.sort((a, b) => a.id - b.id);
    }
   

    filtered.forEach(product => {
      const card = `
        <div class="col-md-6 col-lg-4 col-xl-3 product-item" data-category="${product.category}">
          <div class="rounded position-relative fruite-item">
            <div class="fruite-img">
              <img src="${product.img}" class="img-fluid w-100 rounded-top" alt="${product.name}">
            </div>
            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                  style="top: 10px; left: 10px;">${product.category}</div>
            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
              <h4>${product.name}</h4>
              <div class="d-flex justify-content-between flex-lg-wrap">
                <p class="text-dark fs-5 fw-bold mb-0">KM ${product.price.toFixed(2)} / kg</p>
                <button class="btn border border-secondary rounded-pill px-3 text-primary add-to-cart-btn" data-product-id="${product.id}">
                  <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                </button>
              </div>
            </div>
          </div>
        </div>`;
      listContainer.innerHTML += card;
    });
}

//add a product to the cart 
function addToCart(productId) {
    const id = parseInt(productId);
    const product = products.find(p => p.id === id);

    if (!product) return;

    const existingItem = cart.find(item => item.product.id === id);

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({ product: product, quantity: 1 });
    }
}

//remove a product from the cart 
function removeFromCart(productId) {
    const id = parseInt(productId);
    const initialLength = cart.length;
    cart = cart.filter(item => item.product.id !== id);

    if (cart.length !== initialLength) {
        
        renderCart(); 
    }
}

//update cart quantity 
function updateCartQuantity(productId, newQuantity) {
    const id = parseInt(productId);
    const quantity = parseInt(newQuantity);

    if (quantity <= 0) {
        removeFromCart(id);
    } else {
        const item = cart.find(item => item.product.id === id);
        if (item) {
            item.quantity = quantity;
            renderCart();
        }
    }
}


//render 
function renderCart() {

    const cartBody = document.getElementById("cartItems");
    const total = document.getElementById("total");
    
    
    if (!cartBody || !total) return;

    cartBody.innerHTML = '';
    let subtotal = 0;

    cart.forEach(item => {
        const total = item.product.price * item.quantity;
        subtotal += total;

        const row = `
            <tr data-product-id="${item.product.id}">
                <th scope="row">
                    <div class="d-flex align-items-center">
                        <img src="${item.product.img}" class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;" alt="${item.product.name}">
                    </div>
                </th>
                <td><p class="mb-0 mt-4">${item.product.name}</p></td>
                <td><p class="mb-0 mt-4">KM ${item.product.price.toFixed(2)}</p></td>
                <td>
                    <div class="input-group quantity mt-4" style="width: 100px;">
                        <div class="input-group-btn">
                            <button class="btn btn-sm btn-minus rounded-circle bg-light border" data-product-id="${item.product.id}">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <input type="text" class="form-control form-control-sm text-center border-0" value="${item.quantity}" readonly>
                        <div class="input-group-btn">
                            <button class="btn btn-sm btn-plus rounded-circle bg-light border" data-product-id="${item.product.id}">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </td>
                <td>
                    <p class="mb-0 mt-4 cart-item-total">KM ${total.toFixed(2)}</p>
                </td>
                <td>
                    <button class="btn btn-md rounded-circle bg-light border remove-item-btn mt-4" data-product-id="${item.product.id}">
                        <i class="fa fa-times text-danger"></i>
                    </button>
                </td>
            </tr>
        `;
        cartBody.innerHTML += row;
    });

   
    const finalTotal = subtotal; 
   
    total.textContent = `KM ${finalTotal.toFixed(2)}`;
    
   
    if (cart.length === 0) {
        cartBody.innerHTML = '<tr><td colspan="6" class="text-center py-5">Your cart is empty.</td></tr>';
       
        total.textContent = 'KM 0.00';
    }
}



const setupCartButtonListeners = () => {
    document.addEventListener("click", function(event) {
        
       
        const addToCartBtn = event.target.closest(".add-to-cart-btn");
        if (addToCartBtn) {
            const productId = addToCartBtn.dataset.productId;
            addToCart(productId);
            return;
        }

      
        const removeItemBtn = event.target.closest(".remove-item-btn");
        if (removeItemBtn) {
            const productId = removeItemBtn.dataset.productId;
            removeFromCart(productId);
            return;
        }
        
        
        const quantityButton = event.target.closest('.quantity button');
        if (quantityButton) {
            const isMinus = quantityButton.classList.contains('btn-minus');
            const quantityContainer = quantityButton.closest('.quantity');
            const inputElement = quantityContainer ? quantityContainer.querySelector('input') : null;
            
            if (inputElement) {
                const productId = quantityButton.dataset.productId;
                let quantity = parseInt(inputElement.value);
                
                if (isMinus) {
                    quantity = Math.max(0, quantity - 1);
                } else {
                    quantity += 1;
                }
                
                updateCartQuantity(productId, quantity); 
            }
        }
    });
};


function setupFilter() {
    document.addEventListener("click", function(event) {
        const clickedElement = event.target.closest(".filter-btn");

        if (clickedElement) {
            document.querySelectorAll("#spapp section.active .filter-btn").forEach(button => {
                button.classList.remove("active");
            });

            clickedElement.classList.add("active");

            const category = clickedElement.dataset.category;
     
            renderProducts(category, currentSort); 
        }
    });
};


//price sorting function 
function setupSortControls() {
    document.addEventListener("click", function(event) {
        const clickedElement = event.target.closest(".sort-btn");

        if (clickedElement) {
            document.querySelectorAll("#spapp section.active .sort-btn").forEach(button => {
                button.classList.remove("active");
            });
            clickedElement.classList.add("active");

            const sortType = clickedElement.dataset.sort;
            
            renderProducts(currentCategory, sortType); 
        }
    });
}



document.addEventListener('DOMContentLoaded', () => {
    setupFilter();
    setupCartButtonListeners();
    setupSortControls();
});