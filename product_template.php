<template id="product-template">
    <div class="row mb-1 product-row">
        <div class="col-2">
            <input type="hidden" name="OfferProduct[product_id]">
            <input type="hidden" name="OfferProduct[total_price]">
            <input type="hidden" name="OfferProduct[id]">
            <input type="hidden" name="OfferProduct[offer_id]">
            <img class="product-img" src="" alt="" style="width: 30px">
        </div>
        <div class="col-2">
            <p class="product-name"></p>
        </div>
        <div class="col-2">
            <p><span class="product-price"></span> €</p>
        </div>
        <div class="col-4">
            <div class="input-group">
                <span class="input-group-text">ks</span>
                <input type="number" min="1" name="OfferProduct[amount]" value="1" style="max-width: 100%" class="offer-amount form-control" data-unit-price="1">
            </div>
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-danger btn-sm btn-delete-product"><i class="fas fa-trash"></i></button>
        </div>
    </div>
</template>