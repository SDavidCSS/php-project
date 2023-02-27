<?php
    include_once './classes/Product.php';

    $products = Product::findAll();
?>

<form method="POST" action="save_offer.php" id="offer-form">
    <input type="hidden" name="is_update" value="0">
    <input type="hidden" name="Offer[id]">

    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="mb-3">
                <label for="offer-valid-until" class="form-label">Valid until</label>
                <div class="input-group date">
                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                    <input type="text" class="form-control" id="offer-valid-until" name="Offer[valid_until]" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="offer-customer-name" class="form-label">Customer name</label>
                <input type="text" class="form-control" id="offer-customer-name" name="Offer[customer_name]" required>
            </div>

            <label for="offer-discount-percent" class="form-label">Discount</label>
            <div class="mb-3 input-group">
                <span class="input-group-text">%</span>
                <input type="number" min='0' class="form-control" value="0" id="offer-discount-percent" name="Offer[discount_percent]" required>
            </div>
            <div class="mb-3">
                <label for="offer-comment" class="form-label">Comment</label>
                <textarea class="form-control" id="offer-comment" rows="3" name="Offer[comment]"></textarea>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="mb-3">
                <label for="offer_product" class="form-label">Product</label>
                <select class="form-control form-select" id="offer_product" name="offer_product">
                    <?php /** @var Product $product */
                    foreach ($products as $product): ?>
                        <option value="<?= $product->id ?>" data-name="<?= $product->name ?>" data-price="<?= $product->price ?>" data-img="<?= $product->image ?>"><?= $product->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button id="add-product-btn" class="btn btn-primary btn-sm" type="button"><i class="fas fa-plus me-2"></i>Add product</button>
            <div class="added-products p-2">

            </div>
        </div>
    </div>
    <div class="row hidden price-row">
        <p><strong>Amount:</strong> <span id="amount-placeholder">0</span> ks</p>
        <input type="hidden" name="Offer[product_amount]">
        <p><strong>Total price:</strong> <span id="price-placeholder">0.00</span> €</p>
        <input type="hidden" name="Offer[offer_price]">

        <div class="discount-price-row">
            <p><strong>Discount price:</strong> <span id="discount-price-placeholder">0.00</span> €</p>
            <input type="hidden" name="Offer[discount_price]">
        </div>
    </div>
</form>

<?php

include 'product_template.php';

