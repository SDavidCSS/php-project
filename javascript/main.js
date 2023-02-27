$('.btn-delete').on('click', (e) => {
    e.preventDefault();
    const button = $(e.target);

    Swal.fire({
        title: 'Do you really want to delete this offer ?',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'Delete',
        denyButtonText: `Don't delete`,
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        } else if (result.isDenied) {
            Swal.fire('Offer was not deleted', '', 'info');
        }
    });
});

$('.btn-save').click(function(e) {
    const form = $('#offer-form');
    e.preventDefault();

    const numberOfAddedProducts = getNumberOfProducts(getProductContainer());

    if(numberOfAddedProducts === 0) {
        Swal.fire('Please add at least one product', '', 'warning');
    } else if(numberOfAddedProducts > 5) {
        Swal.fire('Max number of products is 5', '', 'warning');
    } else {
        const hasValidUntil = $('#offer-valid-until').val();
        const hasName = $('#offer-customer-name').val();

        if(!hasValidUntil || !hasName) {
            Swal.fire('Valid until and name fields are required', '', 'warning');
        } else {
            form.submit();
        }

    }
})

$('.btn-update').on('click', (e) => {
    const button = $(e.target);
    const modal = $('#edit-modal');

    $.get('get-info.php', {id: button.attr('data-id')}, function(response) {
        const res = JSON.parse(response);
        const { offer, offer_product} = res.data;

        if (res.success) {
            const productSelect = $('#offer_product')
            $('input[name="is_update"]').val(1);
            $('input[name="Offer[id]"]').val(offer.id);
            $('input[name="Offer[valid_until]"]').val(offer.valid_until).trigger('change');
            $('input[name="Offer[customer_name]"]').val(offer.customer_name);
            $('input[name="Offer[discount_percent]"]').val(offer.discount_percent);
            $('textarea[name="Offer[comment]"]').val(offer.comment);

            offer_product.forEach((offerProduct) => {
                const { product_id, total_price: discountPrice, amount, offer_id, id, product } = offerProduct;
                const { name, image, price } = product;
                const total_price = (parseFloat(price) * parseInt(amount)).toFixed(2);

                $('#offer_product').val(offerProduct.product_id).trigger('change');
                addProduct(product_id, name, image, total_price, parseFloat(discountPrice).toFixed(2), productSelect, amount, offer_id, id);
            });

            modal.modal('show');
        }
    });

});

$('#add-product-btn').on('click', function() {
    const productSelect = $('#offer_product');
    const selectedProduct = productSelect.find(":selected");

    const productPrice = parseFloat(selectedProduct.attr('data-price'));
    const discountPrice = calculateDiscountPrice(productPrice);
    const id = selectedProduct.val();
    const name = selectedProduct.attr('data-name');
    const image = selectedProduct.attr('data-img');

    addProduct(id, name, image, productPrice, discountPrice, productSelect);
});

$('#offer_product').change(function() {
   toggleAddProductButton();
});

$(document).on('click', '.btn-delete-product', function() {
    const target = $(this);
    resetProduct($(this).attr('data-id'));
    target.closest('.product-row').remove();

    setPrice();
});

$(document).on('change', '.offer-amount', function() {
    setProductPrice($(this));
});

$('input[name="Offer[discount_percent]"]').on('change', function() {
    if(getNumberOfProducts(getProductContainer())) {
        $('.product-row input[name$="[amount]"]').each(function(index, element) {
            setProductPrice($(element));
        });
    }
});

// Reset modal fields to default
$('#edit-modal').on('hidden.bs.modal', function () {
    getProductContainer().innerHTML = '';
    $('.price-row').addClass('hidden');
    resetOptions();

    $('input[name="is_update"]').val(0);
    $('input[name="Offer[id]"]').val(0);
    $('input[name="Offer[valid_until]"]').val(null).trigger('change');
    $('input[name="Offer[customer_name]"]').val(null);
    $('input[name="Offer[discount_percent]"]').val(0);
    $('input[name="Offer[offer_price]"]').val(null);
    $('textarea[name="Offer[comment]"]').val(null);
});

document.querySelector('#edit-modal').addEventListener('show.bs.modal', function(e) {
    const modal = e.target;
    const button = e.relatedTarget;

    modal.querySelector('.modal-title').textContent = button.getAttribute('data-title');
    toggleAddProductButton();
});

function resetOptions() {
    $('#offer_product').find('option').removeAttr('disabled');
}

function addProduct(id, name, image, price, discountPrice, productSelect, amount = 1, offerID = null, offerProductId = null) {
    const selectedProduct = productSelect.find(":selected");
    const productContainer = getProductContainer();
    // const productCounter = getNumberOfProducts(productContainer);
    const productCounter = Date.now();

    const template = document.querySelector('#product-template');
    let contentToBeInserted = template.content.cloneNode(true);

    contentToBeInserted.querySelector('.product-img').setAttribute('src', `.${image}`);
    contentToBeInserted.querySelector('.product-name').textContent = name;
    contentToBeInserted.querySelector('.product-price').textContent = price;
    contentToBeInserted.querySelector('.btn-delete-product').setAttribute('data-id', id);

    const productIdField = contentToBeInserted.querySelector('input[name="OfferProduct[product_id]"]');
    const priceField = contentToBeInserted.querySelector('input[name="OfferProduct[total_price]"]');
    const amountField = contentToBeInserted.querySelector('input[name="OfferProduct[amount]"]');
    const offerIdField = contentToBeInserted.querySelector('input[name="OfferProduct[offer_id]"]');
    const idField = contentToBeInserted.querySelector('input[name="OfferProduct[id]"]');

    productIdField.value = id;
    priceField.value = discountPrice;
    amountField.value = amount;
    productIdField.setAttribute('name', `OfferProduct[${productCounter}][product_id]`);
    priceField.setAttribute('name', `OfferProduct[${productCounter}][total_price]`);
    offerIdField.setAttribute('name', `OfferProduct[${productCounter}][offer_id]`);
    idField.setAttribute('name', `OfferProduct[${productCounter}][id]`);
    amountField.setAttribute('name', `OfferProduct[${productCounter}][amount]`);
    amountField.setAttribute('data-unit-price', price / amount);

    if(offerID) offerIdField.value = offerID;
    if(offerProductId) idField.value = offerProductId;

    productContainer.append(contentToBeInserted);
    selectedProduct.attr('disabled', 'true');
    productSelect.val(null);

    setPrice();
    toggleAddProductButton();
}

function calculateDiscountPrice(price) {
    const discountField = $('input[name="Offer[discount_percent]"]').val();
    const discount = parseInt(discountField === '' ? 0 : discountField);
    const newPrice = parseFloat(price);
    return (newPrice - (newPrice * (discount/100))).toFixed(2);
}
function toggleAddProductButton() {
    const addProductButton = $('#add-product-btn');
    const productSelect = $('#offer_product');
    addProductButton.toggleClass('disabled', !productSelect.val());
}

function resetProduct(id) {
    const productSelect = $('#offer_product');
    productSelect.find(`option[value="${id}"]`).removeAttr('disabled');
}

function getNumberOfProducts(productContainer) {
    return [...productContainer.querySelectorAll('.product-row')].length;
}

function getProductContainer() {
    return document.querySelector('.added-products');
}

function setProductPrice(productRow) {
    const target = productRow;
    const priceRow = target.closest('.product-row');
    const priceField = priceRow.find('input[name$="[total_price]"]');
    const unitPrice = priceRow.find('input[name$="[amount]"]').attr('data-unit-price');
    const newPrice = parseFloat(target.val()) * parseFloat(unitPrice);
    const discountPrice = calculateDiscountPrice(newPrice);

    priceField.val(discountPrice);
    priceRow.find('.product-price').text(newPrice);

    setPrice();
}

function setPrice() {
    const productContainer = getProductContainer();
    const pricePlaceHolder = getPricePlaceholder();
    const discountPricePlaceHolder = getDiscountPricePlaceholder();
    const amountPlaceHolder = getAmountPlaceholder();
    const addedProducts = productContainer.querySelectorAll('.product-row');

    const price = calculatePrice(addedProducts);
    const discountPrice = calculateDiscountPrice(price);

    const amount = calculateAmount(addedProducts);
    pricePlaceHolder.textContent = price;
    discountPricePlaceHolder.textContent = discountPrice;
    amountPlaceHolder.textContent = amount;
    $('input[name="Offer[offer_price]"]').val(price);
    $('input[name="Offer[discount_price]"]').val(discountPrice);
    $('input[name="Offer[product_amount]"]').val(amount);
    togglePriceRow(productContainer);
}

function togglePriceRow(productContainer) {
    document.querySelector('.price-row').classList.toggle('hidden', !getNumberOfProducts(productContainer));
    document.querySelector('.discount-price-row').classList.toggle('hidden', !parseInt(document.querySelector('input[name="Offer[discount_percent]"]').value) > 0);
}

function getPricePlaceholder() {
    return document.querySelector('#price-placeholder');
}

function getDiscountPricePlaceholder() {
    return document.querySelector('#discount-price-placeholder');
}

function getAmountPlaceholder() {
    return document.querySelector('#amount-placeholder');
}

function calculatePrice(addedProducts) {
    const products = [...addedProducts]

    if(products.length) {
        return products.reduce((acc, val) => {
            const product = val.querySelector('input[name$="[amount]"]');
            return acc += parseFloat(product.getAttribute('data-unit-price') * parseInt(product.value));
        }, 0.00).toFixed(2);
    }
    return 0.00;
}

function calculateAmount(addedProducts) {
    const products = [...addedProducts]

    if(products.length) {
        return products.reduce((acc, val) => {
            const product = val.querySelector('input[name$="[amount]"]');
            return acc += parseInt(product.value);
        }, 0);
    }
    return 0;
}

// Init datepicker widget
$('input[name="Offer[valid_until]"]').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    todayHighlight: true
});