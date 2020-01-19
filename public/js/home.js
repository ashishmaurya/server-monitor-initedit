function handleAjaxError(jqXHR, testStatus, exception) {
    if (true) {
        console.log(testStatus);
        $("body").append("<div class='box error'>" + jqXHR.responseText + "</div>");
    }
}


function hasAttributeJquery($input, attr) {
    var attr = $input.attr(attr);
    if (typeof attr !== typeof undefined && attr !== false) {
        return true;
    }
    return false;
}
function log(msg) {
    console.log(msg);
}

function validateInputJquery($input) {
    var json = {
        valid: false,
        msg: "Validation error"
    };
    var errorMsgAttr = "data-msg";
    var errorEmptyAttr = "data-empty";
    var required = false;
    var required = hasAttributeJquery($input, 'data-required');
    var shouldCompareInput = hasAttributeJquery($input, 'data-compare');

    var type = $input.attr('type');
    var val = $input.val();

    var return_val = true;
    if (shouldCompareInput) {
        var $parent = $input.closest("form");
        if ($parent.length == 0) {
            $parent = $("body");
        }
        var compareVal = $parent.find($input.attr("data-compare")).val();
        if (val == compareVal) {
            return_val = true;
        } else {
            return_val = false;
            json.msg = "Didn't matched";
        }
    } else if (val.length > 0) {
        if (type === "email") {
            if (isEmail(val))
            {
                return_val = true;
            } else {
                return_val = false;
                json.msg = "Email is required";
            }
        } else if (type === "password") {
            if (val.length >= 5) {
                return_val = true;
            } else {
                return_val = false;
                json.msg = "Password is required";
            }
        } else if (type === "url") {
            var re = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/;
            if (!re.test(val)) {
                return_val = false;
                json.msg = "Invalid URL";
            } else {
                return_val = true;
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
    } else {

    }
    if (!required) {
        return_val = true;
    }
    if (val.length > 0) {
        if (hasAttributeJquery($input, errorMsgAttr)) {
            json.msg = $input.attr(errorMsgAttr);
        }
    } else {
        if (hasAttributeJquery($input, errorEmptyAttr)) {
            json.msg = $input.attr(errorEmptyAttr);
        }
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
            if (!json.valid) {
                $(this).focus();
            }
            isValid = isValid && json.valid;
        }
    });

    return json;
}

function scrollToJquery($element) {
    window.scrollTo(0, $element.offset().top);
}

function submitJqueryForm() {
    var $form = $(this);
    var $errorContainer = $form.find(".errorContainer");
    var validationJson = validateForm($form);
    var confirmFunction = $form.data("confirm");
    var $action = $form.find("[type='submit']");
    if (confirmFunction != undefined || confirmFunction != null) {
        var confirm_return = window[confirmFunction]();
        if (!confirm_return) {
            return false;
        }
    }

    if (validationJson.valid) {
        var formdata = getFormdata($form);
        $.ajax({
            url: $form.attr("action"),
            data: formdata,
            //            processData: false,
            //            contentType: false,
            dataType: "json",
            async: true,
            type: 'POST',
            beforeSend: function (xhr) {
                $errorContainer.hide();
                $action.attr("disabled", true);
            },
            success: function (data) {
                if (!hasAttributeJquery($form, "data-process")) {
                    if (data.code === 1) {
                        if ($errorContainer.length > 0) {
                            $errorContainer.hide();
                        }
                        addMessageBox(0, data.message);

                    } else {
                        if ($errorContainer.length > 0) {
                            $errorContainer.show().find(".value").html(data.message);
                            // scrollToJquery($errorContainer);
                        } else {
                            addMessageBox(1, data.message);
                        }
                    }
                }
                var s = $form.attr("data-success");
                window[s](data);
            },
            error: handleAjaxError
        }).always(function () {
            $action.attr("disabled", false);
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
function refresh() {
    window.location.href = window.location.href;
}
function redirect(url) {
    window.location.href = url;
}

function addeduser(data) {
    if (data.code === 1) {
        redirect("/login/");
    }
}

function addedwebsite(data) {
    if (data.code === 1) {
        redirect("/host/detail?id=" + data.id);
    }
}
function deletedwebsite(data) {
    if (data.code === 1) {
        $(".domain_" + data.postid).remove();
    }
}
//
// function addedservice(data) {
//   if(data.code===1){
//     redirect(window.location.href+"#service-list");
//   }
// }
function deletedservice(data) {
    if (data.code === 1) {
        $(".service_" + data.postid).remove();
    }
}
function deletedurl(data) {
    if (data.code === 1) {
        $(".url-monitoring_" + data.postid).remove();
    }
}

function confirmDeleteHost() {
    return confirm("Are you sure?");
}
function confirmDeleteService() {
    return confirm("Are you sure?");
}

function addedhostgroup(data) {
    if (data.code === 1) {
        window.location.href = "/host-group";
    }
}


$(document).ready(function () {
    $("select").change(function () {
        $(this).find("option").each(function () {
            var target = $(this).attr("data-target");
            $(target).hide();
        });
        var $selected = $(this).find(":selected").index() + 1;
        var $option = $(this).find("option:nth-child(" + $selected + ")");
        $($option.attr("data-target")).show();
    });
    var icons = {
        time: "fa fa-clock-o",
        date: "fa fa-calendar",
        up: "fa fa-arrow-up",
        down: "fa fa-arrow-down"
    };
    $(".date").datetimepicker({
        format: 'YYYY-MM-DD',
        pickTime: false,
    });
    $(".time").datetimepicker({
        pickDate: false,
        format: 'hh:mm A',
        autoclose: true,
        icons: icons
    });

    $(".datetime").datetimepicker({
        format: "YYYY-MM-DD hh:mm A",
        inline: true,
        maxViewMode: 0,
        // viewMode: 'weeks',
        autoclose: false,
        icons: icons
    });
    $(".datetime-year").datetimepicker({
        format: "YYYY-MM-DD hh:mm A",
        maxViewMode: 0,
        viewMode: 'months',
        autoclose: false,
        icons: icons
    });
    var $timeUpdated = $(".time-updated");
    var timeUpdatedInterval = null;
    if ($timeUpdated.length > 0) {
        timeUpdatedInterval = setInterval(function () {
            $timeUpdated.each(function () {
                var current = parseInt($(this).attr("data-current"));
                var next = parseInt($(this).attr("data-time"));
                if (next - current >= 0) {
                    $(this).find(".value").text("After " + (next - current) + " seconds");
                    $(this).attr("data-current", current + 1);
                } else {
                    $(this).find(".value").text("refresh page");
                }
            });
        }, 1000);
    }
    $(".prevent-propagation").click(function (e) {
        e.stopPropagation();
    });

});
function refreshConditional(data) {
    if (data.code == 1) {
        setTimeout(function () {
            if (data.url != undefined) {
                redirect(data.url);
            } else {
                refresh();
            }
        }, 300);
    }
}
function showNotification() {
    $(".notification-container").animate({
        left: "0px"
    }, 200);
    $("body").addClass("body-scroll");
}
function hideNotification() {
    $(".notification-container").animate({
        left: "100%"
    }, 200);
    $("body").removeClass("body-scroll");
}
function clearedNotification(data) {
    if (data.code == 1) {
        $(".notification-body .notification-box").remove();
        $(".clear-notification").hide();
        $(".notification-count .count").removeClass("show").addClass("hide");
        $(".notification-empty").show();
    }
}
function forgotPassowrd(data) {
    if (data.code == 1) {
        $("#resetPasswordStepOne").hide();
        $("#resetPasswordStepTwo").show();
        var email = $(".forgot-email").val();
        var domain = email.split("@").pop();
        $(".goto-mail").attr("href", "//" + domain).html("Go to " + domain);
    }
}
function resetPassword(data) {
    if (data.code == 1) {
        $("#resetStepOne").hide();
        $("#resetStepTwo").show();
    }
}
function searchResult(data) {
    if (data.code == 1) {
        $(".search-result").html(data.html);
    }
}
