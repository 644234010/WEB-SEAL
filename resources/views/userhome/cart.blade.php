<div id="cart" style="position: fixed; bottom: 0; right: -2%; margin: 70px;">
    <a href="{{ route('cart.index') }}">
        <i class="bi bi-cart2 cart-icon">
            <span class="cart-count badge badge-pill badge-danger">0</span>
        </i>
    </a>  
</div>

<style>
#cart {
    background-color: #f8f9fa;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 8px;
    box-shadow: 0 2px 9px rgba(0, 0, 0, 0.1);
}

.cart-icon {
    font-size: 2rem; /* ขยายขนาดไอคอน */
    cursor: pointer;
    position: relative; 
}

.cart-count {
    position: absolute;
    top: -20px;
    right: -20px;
    background-color: red;
    color: white;
    padding: 0 6px;
    border-radius: 80%;
}
</style>
