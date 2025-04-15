@extends('layout.app')

@section('content')
<section style="background-color: #eee;">
    <div class="container py-5">
        <div class="row justify-content-center mb-3">
            <div class="col-md-12 col-xl-10">
                <div class="card shadow-0 border rounded-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 col-lg-3 col-xl-3 mb-4 mb-lg-0">
                                <div class="bg-image hover-zoom ripple rounded ripple-surface">
                                    <img src="{{ asset($product->image_url) }}" class="w-100" />
                                    <a href="#">
                                        <div class="hover-overlay">
                                            <div class="mask" style="background-color: rgba(253, 253, 253, 0.15);"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-xl-6">
                                <h5>{{ $product->name }}</h5>
                                <p class="mb-4">{{ $product->description }}</p>
                                <p class="text-muted">Số lượng:</p>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="changeQuantity(-1)">−</button>
                                    <input type="number" id="quantityInput" class="form-control text-center mx-2" value="1" min="1" style="width: 60px;" readonly>
                                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="changeQuantity(1)">+</button>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3 col-xl-3 border-sm-start-none border-start">
                                <div class="d-flex flex-row align-items-center mb-1">
                                    <h4 class="mb-1 me-1" id="totalPrice">${{ $product->price }}</h4>
                                </div>
                                <h6 class="text-success">Free shipping</h6>
                                <div class="d-flex flex-column mt-4">
                                    <button class="btn btn-primary btn-sm" type="button">Buy Now</button>
                                    <button class="btn btn-outline-primary btn-sm mt-2" type="button">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    let productPrice = {{ $product->price }};

    function changeQuantity(amount) {
        let quantityInput = document.getElementById("quantityInput");
        let totalPrice = document.getElementById("totalPrice");
        let currentQuantity = parseInt(quantityInput.value);

        if (!isNaN(currentQuantity)) {
            let newQuantity = currentQuantity + amount;

            if (newQuantity < 1) {
                newQuantity = 1; // Không cho giảm xuống dưới 1
            }

            quantityInput.value = newQuantity;
            totalPrice.textContent = "$" + (productPrice * newQuantity).toFixed(2);
        }
    }
</script>
@endsection
