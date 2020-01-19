function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

function adminLogin()
{
    var email = $("#loginEmailID").val();
    var password = $("#loginPassword").val();

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
        url: "/admin/login/validate",
        type: "post",
        async: true,
        dataType: "json",
        data: {
            email: email,
            password: password
        },
        success: function (result) {
            if (result.code == 1)
            {
                console.log(result);
                $("#loginError").hide();
                addMessageBox(0, result.message);
                window.location.href = window.location.href;
            } else {

                $("#loginError").show();
                $("#loginErrorMessage").html(result.message);
                return;
            }
        }
    });
}

function updateOrderStatusAdmin(id)
{

    var status = ($("#orderProductItemSelect_" + id).prop('selectedIndex'));
    $.ajax({
        url: "/admin/neworders/updateOrderStatus",
        type: "post",
        async: true,
        dataType: "json",
        data: {
            id: id,
            status: status
        },
        success: function (result) {
            console.log(result);
            if (result.code == 1)
            {
                console.log(result);
                addMessageBox(0, result.message);
                $("#updateOrderStatusButton_" + result.id).hide();
            } else {
                addMessageBox(1, result.message);
            }
        }
    });
}
function orderProductItemSelectionChanged(id)
{
    $("#updateOrderStatusButton_" + id).show();
}

var productQuantity = [];
function addNewProductQuantityUI()
{
    var unitAmount = $("#productAddUnitAmount").val();
    var unit = $("#productAddUnit").val();
    var unitPrice = $("#productAddUnitPrice").val();
    if (unitAmount.length > 0 && unit.length > 0 && unitPrice.length > 0) {
        productQuantity.push({unitAmount: unitAmount, unit: unit, unitPrice: unitPrice});
        var txt = "<li style='display: block;' class='clearFix adminProductQuantity' id='adminProductQuantity_" + productQuantity.length + "'><ul class='hl'>";
        txt += "<li>" + unitAmount + "</li>";
        txt += "<li>" + unit + "</li>";
        txt += "<li>" + unitPrice + "</li>";
        txt += "<li><i class='fa fa-trash' onclick='removeNewProductQuantityUI(" + productQuantity.length + ")'></i></li>";
        txt += "</ul></li>";
        $("#addNewProductQuantityUIContainer").append(txt);
    } else {
        addMessageBox(1, "All Three field is required.");
    }

}
function removeNewProductQuantityUI(id)
{
    $("#adminProductQuantity_" + id).remove();
    productQuantity = productQuantity.splice(id, 1);
}

function adminAddProduct()
{
    var formData = new FormData();
    var name = $("#productAddName").val();
    var categoryIndex = $("#productAddCategory")[0].selectedIndex;
//    var img = $('#productAddImage')[0].files[0];

    var img = $("#adminAddProductImage").val();
    var metadata = $("#productAddMetaData").val();

    formData.append('name', name);
    formData.append('categoryIndex', categoryIndex);
    formData.append('img', img);
    formData.append('metadata', metadata);
    formData.append('productQuantity', JSON.stringify(productQuantity));
    $.ajax({
        url: "/admin/products/adminAddProduct",
        type: "post",
        async: true,
        //dataType:"json",
        data: formData,
        processData: false,
        contentType: false,
        success: function (result) {
            console.log(result);
            alert(result);
        }
    });
}


function showAdminProductUpdateImageBox()
{
    $("#adminAdminProductUpdateImageBoxButton").show();
}

function adminUpdateProductImage(id)
{
//    var img = $('#adminEditImageFile')[0].files[0];
    var img = $("#adminAddProductImageInput").val();
    var formData = new FormData();
    formData.append('img', img);
    formData.append('id', id);
    $.ajax({
        url: "/admin/products/adminEditProductImage",
        type: "post",
        async: true,
        dataType: "json",
        data: formData,
        processData: false,
        contentType: false,
        success: function (result) {
            console.log(result);
            addMessageBox(result.message);


            if (result.code == 1)
            {
//                $("#adminProductImage").attr("src", result.image);
            }

        }
    });
}


function showUpdateProductQuantityPrice(id)
{
    $("#updateProductQuantityPriceButton_" + id).show();
}

function adminUpdateProductQuantityPrice(id)
{
    var price = $("#adminUpdateProductQuantityPriceText_" + id).val();

    $.ajax({
        url: "/admin/products/adminEditProductQuantityPrice",
        type: "post",
        async: true,
        dataType: "json",
        data: {id: id, price: price},
        success: function (result) {
            console.log(result);
            addMessageBox(0, result.message);

            $("#updateProductQuantityPriceButton_" + result.id).hide();
        }
    });
}

function adminAddNewProductQuantity(id)
{
    var unitAmount = $("#productAddUnitAmount").val();
    var unit = $("#productAddUnit").val();
    var unitPrice = $("#productAddUnitPrice").val();
    if (unitAmount.length > 0 && unit.length > 0 && unitPrice.length > 0) {

        $.ajax({
            url: "/admin/products/adminAddProductQuantity",
            type: "post",
            async: true,
            dataType: "json",
            data: {id: id, unitAmount: unitAmount, unit: unit, unitPrice: unitPrice},
            success: function (result) {
                console.log(result);

                if (result.code == 1) {
                    window.location.href = window.location.href;
                }
            }
            , error: function (result) {
                console.log(result);

            }
        });
    } else {
        addMessageBox(1, "All Three field is required.");
    }
}

function adminUnitSelectChange()
{

    $("#productAddUnit").val($('#adminUnitSelectChange option:selected').html());
}
$(document).ready(function () {

    $(".adminHeaderContainer  li:has(ul)").on("click", function (event) {
        //event.stopPropagation();
        //$(this).children("ul").toggle("fast");
    });
});

function showAdminProductUpdateName()
{
    $("#showAdminProductUpdateName").show();
}
function adminUpdateProductName(id)
{
    var name = $("#showAdminProductUpdateNameInput").val();

    if (name.length > 0) {

        $.ajax({
            url: "/admin/products/adminUpdateProductName",
            type: "post",
            async: true,
            dataType: "json",
            data: {id: id, name: name},
            success: function (result) {
                console.log(result);
                if (result.code == 1) {
                    window.location.href = window.location.href;
                } else {
                    addMessageBox(0, result.message);
                }
            }
            , error: function (result) {
                console.log(result);

            }
        });
    } else {
        addMessageBox(1, "Name is required.");
    }
}
function showAdminProductUpdateStock()
{
    $("#showAdminProductUpdateStock").show();
}
function adminUpdateProductStock(id)
{
    var instock = ($("#showAdminProductUpdateStockCheckbox").is(":checked"));
    $.ajax({
        url: "/admin/products/adminUpdateProductInStock",
        type: "post",
        async: true,
        dataType: "json",
        data: {id: id, instock: instock},
        success: function (result) {
            console.log(result);
            if (result.code == 1) {
                addMessageBox(0, result.message);
            } else {
                addMessageBox(1, result.message);
            }
        }
        , error: function (result) {
            console.log(result);

        }
    });
}
function showAdminProductUpdateMetaData()
{
    $("#showAdminProductUpdateMetaData").show();
}
function adminUpdateProductMetaData(id)
{
    var metadata = ($("#showAdminProductUpdateMetaDataInput").val());
    $.ajax({
        url: "/admin/products/adminUpdateProductMetaData",
        type: "post",
        async: true,
        dataType: "json",
        data: {id: id, metadata: metadata},
        success: function (result) {
            console.log(result);
            if (result.code == 1) {
                addMessageBox(0, result.message);
            } else {
                addMessageBox(1, result.message);
            }
        }
        , error: function (result) {
            console.log(result);

        }
    });
}
function adminrefresh() {
    window.location.href = window.location.href;
}
function adminDeleteProductQuantityPrice(id) {
    var isDelete = confirm("Are You Sure?");
    if (isDelete) {
        $.ajax({
            url: "/admin/products/adminDeleteProduct",
            type: "post",
            async: true,
            dataType: "json",
            data: {id: id},
            success: function (result) {
                console.log(result);
                if (result.code == 1) {
                    addMessageBox(0, result.message);
                    $(".productQuantity_" + id).remove();
                } else {
                    addMessageBox(1, result.message);
                }
            }
            , error: function (result) {
                console.log(result);
            }
        });
    } else {
        addMessageBox(0, "Delete Operation Canceld");
    }
}
function adminDeleteImage(path) {
    var isDelete = confirm("Are You Sure?");
    if (isDelete) {
        $.ajax({
            url: "/admin/images/deleteImage",
            type: "post",
            async: true,
            dataType: "json",
            data: {path: path},
            success: function (result) {
                console.log(result);
                if (result.code == 1) {
                    addMessageBox(0, result.message);
                    $("#" + result.name).remove();
                } else {
                    addMessageBox(1, result.message);
                }
            }
            , error: function (result) {
                console.log(result);
            }
        });
    }
}

function adminUploadProductImage()
{
    var img = $('#adminImageFileInput')[0].files;
    var formData = new FormData();
    for (var i = 0; i < img.length; i++) {
        formData.append('img' + i, img[i]);
    }
    formData.append('type', "product");
    var $this = $(this);
    $this.attr("disabled", "true").html("uploading");
    $.ajax({
        url: "/admin/images/upload",
        type: "post",
        async: true,
        dataType: "json",
        data: formData,
        processData: false,
        contentType: false,
        success: function (result) {
            console.log(result);
            if (result.code == 1)
            {
                addMessageBox(0, result.message);
            } else {
                addMessageBox(1, result.message);
            }
            $this.attr("disabled", "false").html("upload");
            $('#adminImageFileInput').val("");
        }
    });
}

function adminAddNewProductSelectImage() {
    var $this = $(this);
    var $image = $($this.attr("data-img"));
    var $input = $($this.attr("data-input"));


    $.get("/admin/images/paged_images?source=" + $this.attr("data-source") + " #imageList", {source: $this.attr("data-source")}, function (data) {
        var $imageContainer = $(".image-selector-container");
        $imageContainer.find(".images").html(data);
        $imageContainer.show();
        $imageContainer.find(".img-container").each(function () {
            var $this = $(this);
            $this.click(function () {
                console.log($image);
                $image.attr("src", $this.attr("data-img-url"));
                $input.val($this.attr("data-img-name")).change();
                $imageContainer.hide();
            });
        });
    });
}
$(document).ready(function () {
    $(".image-select-view").click(adminAddNewProductSelectImage);
    $(".show-apply-button").change(adminShowApplyButton);
    $(".image-selector-container .close-button").click(function () {
        $(".image-selector-container").hide();
    });
    $(".admin-mobile-menu-button").click(toggleAdminMobileMenu);
    $(".gfc-sortable").sortable();
});
function adminShowApplyButton() {
    var $this = $(this);
    var selector = $this.attr("data-id");
    $(selector).show();
}
function toggleAdminMobileMenu() {
    $(".adminHeaderContainer").toggle();
}
function refresh() {
    window.location.href = window.location.href;
}

function adminAddHomeProduct(productid) {
    $.post("/admin/products/addToHome",
            {id: productid},
            function (data) {
                var json = JSON.parse(data);
                if (json.code == 1) {
                    refresh();
                } else {
                    addMessageBox(1, json.message);
                }
            }
    );
}
function adminReorderFavouritesProduct() {
    var myVals = [];
    var productids = $(".product-list .table-row").each(function () {
        myVals.push($(this).attr('data-productid'));
    });
    productids = myVals.join(",");
    $.post("/admin/products/reorder",
            {ids: productids},
            function (data) {
                var json = JSON.parse(data);
                if (json.code == 1) {
                    refresh();
                } else {
                    addMessageBox(1, json.message);
                }
            }
    );
}
function adminOrderGroupStatusChanged(id) {
    $("#order-group-apply-" + id).show();
}
function adminChangeOrderGroupStatus(id) {
    var status = $("#orderProductGroupSelect_" + id).prop('selectedIndex');
    $.post("/admin/neworders/changeGroupStatus",
            {id: id,
                status: status
            },
            function (data) {
                console.log(data);
                var json = JSON.parse(data);
                if (json.code == 1) {
                    refresh();
                } else {
                    addMessageBox(1, json.message);
                }
            }
    );
}

function adminSaveGeneralInfo() {
    var options = [];
    $(".option").each(function () {
        var $this = $(this);
        options.push([$this.attr("data-option"), $this.val()]);
    });

    $.post("/admin/general/save", {
        options: JSON.stringify(options)
    },
            function (data) {
                var json = JSON.parse(data);
                console.log(json);
                if (json.code == 1) {

                } else {
                    addMessageBox(1, json.status);
                }
            });

}
function adminUpdateUserStatus(userid) {

    var status = $(".admin-user-status-" + userid).val();

    $.post("/admin/users/updateStatus", {
        id: userid,
        status: status
    },
            function (data) {
                var json = JSON.parse(data);

                if (json.code == 1) {
                    $(".update-user-status-" + userid).hide();
                    addMessageBox(0, json.message);
                } else {
                    addMessageBox(1, json.message);
                }
            });

}
function adminUpdateUserType(userid) {

    var type = $(".admin-user-type-" + userid).val();

    $.post("/admin/users/updateAccountType", {
        id: userid,
        type: type
    },
            function (data) {
                var json = JSON.parse(data);

                if (json.code == 1) {
                    $(".update-user-type-" + userid).hide();
                    addMessageBox(0, json.message);
                } else {
                    addMessageBox(1, json.message);
                }
            });

}
function saveSlide(addNewSlide) {

    var options = [];
    $(".slide-row").each(function () {
        var $this = $(this);
        var img = $this.find(".slide-img").attr("data-img");
        var link = $this.find(".slide-link input").val();
        options.push({
            img: img,
            link: link
        });
    });
    if (addNewSlide) {
        var newimg = $(".slide-new-img-input").val();
        if (newimg.length > 0) {
            var newlink = $(".slide-new-link input").val();
            options.push({
                img: newimg,
                link: newlink
            });
        }
    }
    console.log(options)


    $.post("/admin/images/saveSlide", {
        options: JSON.stringify(options),
        key: "home-slides"
    },
            function (data) {
                var json = JSON.parse(data);
                if (json.code == 1) {
                    addMessageBox(0, json.message);
                    refresh();
                } else {
                    addMessageBox(1, json.message);
                }
            });

}
function removeSlideOnClick(id) {
    $(".slide-row-"+id).remove();
}