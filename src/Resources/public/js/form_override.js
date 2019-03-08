(function($) {
    $('#button_override').click(function(){
        AddAmazonData();
    });
})( jQuery );

function AddAmazonData() {
 $('#sylius_checkout_address_customer_email').val('Test@example.com');
 $('#sylius_checkout_address_shippingAddress_firstName').val('Test FirstName');
 $('#sylius_checkout_address_shippingAddress_lastName').val('Test LastName');
 $('#sylius_checkout_address_shippingAddress_company').val('Test Company');
 $('#sylius_checkout_address_shippingAddress_street').val('Test 0/1');
 $('#sylius_checkout_address_shippingAddress_countryCode').val('US');
 $('#sylius_checkout_address_shippingAddress_provinceName').val('Test Province');
 $('#sylius_checkout_address_shippingAddress_city').val('Test City');
 $('#sylius_checkout_address_shippingAddress_postcode').val('48-600');
 $('#sylius_checkout_address_shippingAddress_phoneNumber').val('123456789');
}
