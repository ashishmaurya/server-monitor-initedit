/**
 * Created by home on 4/16/2016.
 */
function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

$(document).ready(initShop);
function initShop() {

    $(".change-enable").on("input", onChangeEnableInput);
    $(".change-enable-grp").each(function () {
        var target = $(this).attr("data-target");
        $(this).find("input,select,textarea").on("input", function () {
            $(target).prop("disabled", false);
        })
    });

    function onChangeEnableInput() {
        $($(this).attr("data-target")).prop("disabled", false);
    }

    $(".submit-jquery-form").on("submit", submitJqueryForm);

}


function login() {
    var email = $("#loginEmailID").val();
    var password = $("#loginPassword").val();
    var $loginBoxButton = $(".loginBoxButton");
    if (email.length == 0)
    {
        $("#loginError").show();
        $("#loginErrorMessage").html("Enter Your Email ID.");
        return;
    } else if (!isEmail(email)) {
        $("#loginError").show();
        $("#loginErrorMessage").html("Enter Valid Email ID.");
        return;
    } else {
        $("#loginError").hide();
    }

    if (password.length == 0)
    {
        $("#loginError").show();
        $("#loginErrorMessage").html("Enter Your Password.");
        return;
    } else if (password.length < 5)
    {
        $("#loginError").show();
        $("#loginErrorMessage").html("Password Should Be Greater Then 6 Character.");
        return;
    } else {
        $("#loginError").hide();
    }

    $.ajax({
        url: "/login/validate",
        type: "post",
        async: true,
        dataType: "json",
        data: {
            email: email,
            password: password
        },
        beforeSend: function (xhr) {
            $loginBoxButton.prop('disabled', true);
        },
        success: function (result) {
            if (result.code == 1)
            {
                console.log(result);
                $("#loginError").hide();
                addMessageBox(0, result.message);
                setTimeout(function () {
                    window.location.href = window.location.href;
                }, 1000);

            } else {
                $("#loginError").show();
                $("#loginErrorMessage").html(result.message);
                return;
            }
        }
    }).always(function () {
        $loginBoxButton.prop('disabled', false);
    });

}
function logout() {



    $.ajax({
        url: "/logout",
        type: "post",
        async: true,
        dataType: "json",
        success: function (result) {
            if (result.code == 1)
            {
                setTimeout(function () {
                    window.location.href = window.location.href;
                }, 1000);
            } else {

                addMessageBox(1, result.message);
                return;
            }
        }
    });
}

function forgotPassword() {

    var email = $("#forgotEmailID").val();
    var $loginBoxButton = $(".loginBoxButton");
    if (email.length == 0)
    {
        $("#forgotPasswordError").show();
        $("#forgotPasswordErrorMessage").html("Email is required");
        return;
    } else if (!isEmail(email)) {
        $("#forgotPasswordError").show();
        $("#forgotPasswordErrorMessage").html("Enter valid email");
        return;
    } else {
        $("#forgotPasswordError").hide();
    }
    //ashishmaurya@outlook.com

    $.ajax({
        url: "/forgot-password/validate",
        type: "post",
        async: true,
        dataType: "json",
        data: {email: email},
        beforeSend: function () {
            $loginBoxButton.prop('disabled', true);
        },
        success: function (result) {
            console.log(result);
            if (result.code == 1)
            {
                $("#forgotPasswordError").hide();
                $("#resetPasswordStepOne").hide();
                $("#resetPasswordStepTwo").show();

                addMessageBox(0, result.message);
                //window.location.href=window.location.href;
            } else {
                $("#forgotPasswordError").show();
                $("#forgotPasswordErrorMessage").html(result.message);
                //addMessageBox(1,result.message);return;
            }
        },
        error: handleAjaxError
    }).always(function () {
        $loginBoxButton.prop('disabled', false);
    });
}
function  forgotPasswordBack() {
    $("#forgotEmailID").val("");
    $("#resetPasswordStepOne").show();
    $("#resetPasswordStepTwo").hide();
}


function  resetPassword()
{
    var email = $("#resetEmailID").val();
    var password = $("#resetPassword").val();
    var confirmPassword = $("#resetConfirmPassword").val();
    var resetMD = $("#resetMessageDigest").val();
    var $loginBoxButton = $(".loginBoxButton");
    $.ajax({
        url: "/reset-password/reset",
        type: "post",
        async: true,
        dataType: "json",
        data: {email: email, password: password, confirmPassword: confirmPassword, resetMD: resetMD},
        beforeSend: function () {
            $loginBoxButton.prop('disabled', true);
        },
        success: function (result) {
            console.log(result);
            if (result.code == 1)
            {
                $("#resetPasswordError").hide();
                $("#resetStepOne").hide();
                $("#resetStepTwo").show();
                addMessageBox(0, result.message);
            } else {
                $("#resetPasswordError").show();
                $("#resetPasswordErrorMessage").html(result.message);
                return;
            }
        },
        error: handleAjaxError
    }).always(function () {
        $loginBoxButton.prop('disabled', false);
    });

}

function signUp()
{
    var name = $("#signupUserName").val();
    var email = $("#signupEmailID").val();
    var password = $("#signupPassword").val();
    var referalCode = $("#referalCode").val();
    var $loginBoxButton = $(".loginBoxButton");
    if (name.length == 0)
    {
        $("#signUpError").show();
        $("#signupErrorMessage").html("Enter your full name.");
        return;
    } else {
        $("#signUpError").hide();
    }

    if (email.length == 0)
    {
        $("#signUpError").show();
        $("#signupErrorMessage").html("Email is required");
        return;
    } else if (!isEmail(email)) {
        $("#signUpError").show();
        $("#signupErrorMessage").html("Enter valid email");
        return;
    } else {
        $("#signUpError").hide();
    }

    if (password.length == 0)
    {
        $("#signUpError").show();
        $("#signupErrorMessage").html("Enter Your Password.");
        return;
    } else if (password.length < 5)
    {
        $("#signUpError").show();
        $("#signupErrorMessage").html("Password Should Be Greater Then 6 Character.");
        return;
    } else {
        $("#signUpError").hide();
    }

    $.ajax({
        url: "/signup/add",
        type: "post",
        async: true,
        dataType: "json",
        data: {
            name: name,
            email: email,
            password: password,
            referal: referalCode
        },
        beforeSend: function () {
            $loginBoxButton.prop('disabled', true);
        },
        success: function (result) {
            if (result.code == 1)
            {
                $("#signUpError").hide();
                addMessageBox(0, result.message);
                setTimeout(function () {
                    window.location.href = "/login";
                }, 1000);

            } else {
                console.log(result);
                $("#signUpError").show();
                $("#signupErrorMessage").html(result.message);
                return;
            }
        }
    }).always(function () {
        $loginBoxButton.prop('disabled', false);
    });
}


function addToCart(id)
{
    var productQuantityID = (($("#product_select_" + id + " option:selected").attr("data-id")));
    var $btn = $(".product-item-" + id + " .productAddToCartButton");
    $.ajax({
        url: "/cart/add/" + productQuantityID,
        dataType: "json",
        type: 'GET',
        data:{id:productQuantityID},
        beforeSend: function () {
            $btn.prop('disabled', true);
        },
        success: function (result) {
            console.log(result);
            if (result.code == 1)
            {
                $("#headerCartCountInput").val(result.cartCount).change();
                $(".cart-count-display").html(result.cartCount);
                addMessageBox(0, result.message);
            } else {
                addMessageBox(1, result.message);
            }
        },
        error: handleAjaxError
    }).always(function () {
        $btn.prop('disabled', false);
    });
}

function  removeProductFromCart(id)
{
    var isConfirmed = confirm("Are You Sure?");
    var $btn = $("#cartProductItem_" + id + " .removeCartItemButton");
    if (isConfirmed) {
        $.ajax({
            url: "/cart/removeFromCart/" + id,
            dataType: "json",
            beforeSend: function () {
                $btn.prop('disabled', true);
            },
            success: function (result) {
                console.log(result);
                if (result.code == 1) {

                    $("#cartProductItem_" + result.id).remove();

                    if (result.cartCount == 0) {
//                        $(".cartItems").hide();
//                        $(".proceedToCheckoutButton").hide();
                        $(".hide-cart-on-empty").hide();
                        $("#cartEmptyMessage").show();
                    }

                    //$("#headerCartCount").html(result.cartCount);
                    $("#headerCartCountInput").val(result.cartCount).change();
                    addMessageBox(0, result.message);
                    $(".total-cart-amount-change").html(result.cartAmount)
                } else {
                    addMessageBox(1, result.message);
                }
            },
            error: handleAjaxError
        }).always(function () {
            $btn.prop('disabled', false);
        });
    }
}


function cartItemSelectChanged(id)
{
    var quantity = ($("#cartItemSelectChanged_" + id).prop("selectedIndex"));
    var $select = $("#cartItemSelectChanged_" + id);
    quantity++;
    $.ajax({
        url: "/cart/updateCart/",
        dataType: "json",
        type: "post",
        data: {id: id, quantity: quantity},
        beforeSend: function () {
            $select.prop('disabled', true);
        },
        success: function (result) {
            console.log(result)
            if (result.code == 1)
            {
                var oldPrice = ($("#cartItemSelectChanged_" + result.id).attr("data-price"));
                var newPrice = result.price;
                var changePrice = parseFloat(newPrice) - parseFloat(oldPrice);
                var oldTotalPrice = parseFloat($("#cartTotalPrice").text());
                var newTotalPrice = parseFloat(changePrice) + oldTotalPrice;
                $("#cartSubtotalProduct_" + result.id).html(newPrice);
                $("#cartTotalPrice").html(newTotalPrice);
                ($("#cartItemSelectChanged_" + result.id).attr("data-price", newPrice));

                //$("#headerCartCount").html(result.cartCount);
                $("#headerCartCountInput").val(result.cartCount).change();
                addMessageBox(0, result.message);
                $(".total-cart-amount-change").html(result.cartAmount)
            } else {
                addMessageBox(1, result.message);
            }
        },
        error: handleAjaxError
    }).always(function () {
        $select.prop('disabled', false);
    });
}


function addAddressButton()
{
    var fullName = $("#checkoutAddressFullName").val();
    var mobileNumber = $("#checkoutMobileNumber").val();
    var addressLineOne = $("#checkoutAddressLineOne").val();
    var addressLineTwo = $("#checkoutAddressLineTwo").val();
    var pincode = $("#checkoutPincode").find(":selected").text();
    var landmark = $("#checkoutAddressLandmark").val();

    var data = {fullName: fullName, mobileNumber: mobileNumber, addressLineOne: addressLineOne, addressLineTwo: addressLineTwo, pincode: pincode, landmark: landmark};
    data.redirect = $(".addAddressButton").attr("data-redirect");

    var $btn = $(".addAddressButton");

    $.ajax({
        url: "/checkout/addaddress/",
        dataType: "json",
        type: "post",
        data: data,
        beforeSend: function () {
            $btn.prop('disabled', true);
        },
        success: function (result) {
            console.log(result)
            if (result.code == 1)
            {
                $("#addNewAddressError").hide();

                addMessageBox(0, result.message);
                setTimeout(function () {
                    window.location.href = result.redirect;
                }, 1000);
            } else {
                $("#addNewAddressError").show();
                $("#addNewAddressErrorMessage").html(result.message);
                addMessageBox(1, result.message);
            }
        },
        error: handleAjaxError
    }).always(function () {
        $btn.prop('disabled', false);
    });
    ;
}

function editAddressButton(id)
{
    var fullName = $("#checkoutAddressFullName").val();
    var mobileNumber = $("#checkoutMobileNumber").val();
    var addressLineOne = $("#checkoutAddressLineOne").val();
    var addressLineTwo = $("#checkoutAddressLineTwo").val();
    var pincode = $("#checkoutPincode").find(":selected").text();
    var landmark = $("#checkoutAddressLandmark").val();
    ;
    var data = {id: id, fullName: fullName, mobileNumber: mobileNumber, addressLineOne: addressLineOne, addressLineTwo: addressLineTwo, pincode: pincode, landmark: landmark};
    data.redirect = $(".addAddressButton").attr("data-redirect");

    var $btn = $(".addAddressButton");

    $.ajax({
        url: "/checkout/updateaddress/",
        dataType: "json",
        type: "post",
        data: data,
        beforeSend: function () {
            $btn.prop('disabled', true);
        },
        success: function (result) {
            console.log(result)
            if (result.code == 1)
            {
                $("#editNewAddressError").hide();
                addMessageBox(0, result.message);
            } else {
                $("#editNewAddressError").show();
                $("#editNewAddressErrorMessage").html(result.message);
                addMessageBox(1, result.message);
            }
        },
        error: handleAjaxError
    }).always(function () {
        $btn.prop('disabled', false);
    });
}


function deleteAddressButton(id)
{
    var isConfirmed = confirm("Are You Sure?");

    var $btn = $("#addressItem_" + id + " .delete-btn");

    if (isConfirmed) {
        $.ajax({
            url: "/checkout/deleteaddress/",
            dataType: "json",
            type: "post",
            data: {id: id},
            beforeSend: function () {
                $btn.prop('disabled', true);
            },
            success: function (result) {
                console.log(result)
                if (result.code == 1) {

                    addMessageBox(0, result.message);
                    $("#addressItem_" + result.id).remove();
                    $(".account-add-new-address").show();
                    if ($(".addressItem").length == 0) {
                        $(".emptyAddressMessage").show();
                    }
                } else {
                    addMessageBox(1, result.message);
                }
            },
            error: handleAjaxError
        }).always(function () {
            $btn.prop('disabled', false);
        });
        ;
    }
}

function placeOrder(id)
{
    var $btns = $(".confirmPlaceOrderButton ,.orderSummary .button-cta");
    $.ajax({
        url: "/checkout/placeorder/",
        dataType: "json",
        type: "post",
        data: {id: id},
        beforeSend: function () {
            $btns.prop('disabled', true);
        },
        success: function (result) {
            console.log(result)
            if (result.code === 1) {
                setTimeout(function () {
                    window.location.href = result.redirect;
                }, 1000);
                addMessageBox(0, result.message);
            } else {
                addMessageBox(1, result.message);
            }
        },
        error: handleAjaxError
    }).always(function () {
        $btns.prop('disabled', false);
    });
}

function orderRequestCancelButton(id)
{
    var $btn = $(".order-product-cancel-btn-" + id);
    var isConfirmed = confirm("Are You Sure?");
    if (isConfirmed) {
        $.ajax({
            url: "/orders/cancleorder/",
            dataType: "json",
            type: "post",
            data: {id: id},
            beforeSend: function (xhr) {
                $btn.prop('disabled', true);
            },
            success: function (result) {
                console.log(result);
                if (result.code == 1) {
                    addMessageBox(0, result.message);
                    $(".order-status-" + id).html(result.status);
                    $btn.html("Canceling").attr("onclick", "");
                } else {
                    $btn.prop('disabled', false);
                    addMessageBox(1, result.message);
                }
            },
            error: handleAjaxError
        });
    }
}

function showMobileHeaderMenu()
{
    $("#mobileHeaderMenu").toggleClass("show");
}


function hideMobileHeaderMenu()
{
    showMobileHeaderMenu();
}
function stopEventPropagation(e) {
    e.stopPropagation();
}

function changePassword()
{
    var oldPassword = $("#accountOldPassword").val();
    var newPassword = $("#accountNewPassword").val();
    var confirmPassword = $("#accountConfirmPassword").val();

    $("#accountSettingPasswordChangedError").hide();
    $.ajax({
        url: "/account/changepassword/",
        dataType: "json",
        type: "post",
        data: {oldPassword: oldPassword, newPassword: newPassword, confirmPassword: confirmPassword},
        success: function (result) {
            if (result.code == 1) {
                addMessageBox(0, result.message);
                $("#accountSettingPasswordChangedError").hide();
            } else {
                $("#accountSettingPasswordChangedError").show();
                $("#accountSettingPasswordChangedErrorMessage").html(result.message);
                //addMessageBox(1, result.message);
            }
        },
        error: handleAjaxError
    });
}


function showHelpAnswer()
{

}


function addMessageBox(type, msg) {

    var boxMsg = '<div class="messageBox animated ' + ((type === 0) ? "messageBoxGreen" : "messageBoxRed") + '">\
                <ul class="hl">\
                    <li>' + msg + '</li>\
                    <li class="close">&times;</li>\
                </ul>\
            </div>';


    var $boxMsg = $(boxMsg);
    $("#rightMessageBox").prepend($boxMsg);
    $boxMsg.click(function () {
        
        $(this).hide(500, function () {
            $(this).remove();
        })
    });
    $boxMsg.addClass("bounceInRight");
    setTimeout(function(){
        $boxMsg.addClass("bounceOutRight");
    },3000);
//    $boxMsg.click(function () {
//        
//        $(this).hide(500, function () {
//            $(this).remove();
//        })
//    });
//    $boxMsg.show(500).delay(3000).hide(500, function () {
//        $(this).remove();
//    });
}

function hasAttributeJquery($input, attr) {
    var attr = $input.attr(attr);
    if (typeof attr !== typeof undefined && attr !== false) {
        return true;
    }
    return false;
}


function validateInputJquery($input) {
    var json = {
        valid: false,
        msg: "Validation error"
    };
    var errorMsgAttr = "data-msg";
    var required = false;
    var attr = hasAttributeJquery($input, 'data-required');
    if (typeof attr !== typeof undefined && attr !== false) {
        required = true;
    }
    var type = $input.attr('type');
    var val = $input.val();

    var return_val = false;

    if (val.length > 0) {
        if (type === "email") {
            if (isEmail(val))
            {
                return_val = true;
            } else {
                return_val = false;
                json.msg = "Email is required";
            }
        } else if (type === "password") {
            if (val.length >= 6) {
                return_val = true;
            } else {
                return_val = false;
                json.msg = "Password is required";
            }
        } else if (type === "number") {
            if (!isNaN(val)) {
                var val_num = parseFloat(val);
                var has_min = hasAttributeJquery($input, "min");
                var has_max = hasAttributeJquery($input, "max");
                return_val = true;
                if (has_min) {
                    var min = $input.attr("min");
                    var min_int = parseFloat(min);
                    if (val_num >= min_int) {

                    } else {
                        return_val = false;
                    }
                }
                if (has_max) {
                    var max = $input.attr("max");
                    var max_int = parseFloat(max);
                    if (val_num <= max_int) {

                    } else {
                        return_val = false;

                    }
                }

            } else {
                return_val = false;

            }
        }
        return_val = true;
    } else {

        if (required) {
            return_val = false;
        } else {
            return_val = true;
        }
    }

    if (hasAttributeJquery($input, errorMsgAttr)) {
        json.msg = $input.attr(errorMsgAttr);
    }
    json.valid = return_val;
    return json;
}

function getFormdata($form) {
    var data = new FormData($form.get(0));

    var data = $form.serializeArray().reduce(function (obj, item) {
        obj[item.name] = item.value;
        return obj;
    }, {});
    console.log(data);
    return data;
}


function validateForm($form) {
    var isValid = true;
    var json = {
        valid: true,
        msg: "Validation error"
    };

    $form.find(":input").each(function () {
        if (isValid) {

            json = validateInputJquery($(this));

            isValid = isValid && json.valid;
        }
    });

    return json;
}


function submitJqueryForm() {
    var $form = $(this);
    var $errorContainer = $form.find(".errorContainer");
    var validationJson = validateForm($form);

    if (validationJson.valid) {
        var formdata = getFormdata($form);

        $.ajax({
            url: $form.attr("action"),
            data: formdata,
            processData: false,
            contentType: false,
            dataType: "json",
            async: true,
            type: 'POST',
            success: function (data) {

                if (data.code === 1) {
                    if ($errorContainer.length > 0) {
                        $errorContainer.hide();
                    }
                    addMessageBox(0, data.message);

                } else {
                    if ($errorContainer.length > 0) {
                        $errorContainer.show().find(".value").html(data.message);
                    } else {
                        addMessageBox(1, data.message);
                    }
                }
            },
            error: handleAjaxError
        });
    } else {

        if ($errorContainer.length > 0) {
            $errorContainer.show().find(".value").html(validationJson.msg);
        } else {
            addMessageBox(1, validationJson.msg);
        }

    }
    return false;
}

function saveAccountBasicDetail() {
    var $form = $(".edit-basic-detail-form");
    alert(validateForm($form));
    return false;
}
