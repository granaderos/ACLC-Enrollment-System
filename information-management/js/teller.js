/**
 * Created with JetBrains PhpStorm.
 * User: root
 * Date: 1/23/17
 * Time: 9:39 PM
 * To change this template use File | Settings | File Templates.
 */
var numeric = /^[0-9, .]*$/;
var toPay = 0, currentBalance = 0, curStudentId = 0, paymentFor = "";
$(document).ready(function() {
//    $("#datepicker").datepicker().val(new Date().asString()).trigger("change");
    displayMiscFees();
    toggleHome();

    $.ajax({
        type: "POST",
        url: "../php/teller/displayAllProgramsAsOptions.php",
        success: function(data) {
            if(data != "none")
                $("#miscFor").append(data);
        },
        error: function(data) {
            alert("error in displayAllProgramsAsOptions Mj! :( " + JSON.stringify(data));
        }
    });


});

function viewDetailsStudTrans() {
    var studentId = $("#studentId").val();
    var studentName = $("#studentName").html();
    if(studentId.trim().length > 0 &&
        studentName != "______________________") {
        $.ajax({

        });
    }
}

function toggleReports() {
    $("#tellerMainContainerDiv").html($("#reportsContainerDiv").html());
}

function displayMiscFees() {
    $.ajax({
        type: "POST",
        url: "../php/teller/displayMiscFees.php",
        success: function(data) {
            $("#displayMiscContainer").html(data);
        },
        error: function(data) {
            alert("Mj error in displaying misc fees :( " + JSON.stringify(data));
        }
    });
}

function deleteMisc(miscId) {
    var okay = confirm("Are you sure to delete it?");
    if(okay) {
        $.ajax({
            type: "POST",
            url: "../php/teller/deleteMisc.php",
            data: {miscId: miscId},
            success: function(data) {
                $("#miscTR"+miscId).remove();
                alert("Successfully deleted!");
                displayMiscFees();
            }
        });
    }
}

function editMisc(miscId) {
    var newAmount = prompt("Enter new amount for "+$("#miscName"+miscId).html()+": ");
    if(newAmount.trim().length > 1 && numeric.test(newAmount)) {
        var okay = confirm("Are you sure to change the amount for " + $("#miscName"+miscId).html() + " to " + newAmount + "?");
        if(okay) {
            $.ajax({
                type: "POST",
                url: "../php/teller/editMisc.php",
                data: {miscId: miscId, amount: newAmount},
                success: function(data) {
                    $("#miscAmount"+miscId).html(newAmount);
                    alert("Successfully updated!");
                    displayMiscFees;
                },
                error: function(data) {
                    alert("Mj! error in editing amount! :( " + JSON.stringify(data));
                }
            });
        }
    } else alert("Invalid amount; update cancelled!");
}

function displayTuitionFee() {
    $.ajax({
        type: "POST",
        url: "../php/teller/displayTuitionFee.php",
        success: function(data) {
            $("#feePerUnit").val(data);
        },
        error: function(data) {
            alert("error in displayTuitionFee MJ :( " + JSON.stringify(data));
        }
    });
}

function toggleHome() {
    $("#tellerMainContainerDiv").html($("#homeContainerDiv").html());
}

function toggleManageFees() {
    displayTuitionFee();
    getFormula();
    $("#tellerMainContainerDiv").html($("#manageFeesContainerDiv").html());
}

function fDownpaymentEdit() {
    $("#fDownpayment").attr("disabled", false);
    $("#fDownpayment").focus();
}

function fInstallmentEdit() {
    $("#fInstallment").attr("disabled", false);
    $("#fInstallment").focus();
}

function editFdownpayment() {
    var okay = confirm("Are you sure to save this data?");
    if(okay) {
        var dp = $("#fDownpayment").val();
        if(dp.trim().length > 0 && numeric.test(dp) && parseFloat(dp) > 0) {

            $.ajax( {
                type: "POST",
                url: "../php/teller/editFdownpayment.php",
                data: {dp: dp},
                success: function(data) {
                    $("#fDownpayment").attr("disabled", true);
                },
                error: function(data) {
                    alert("error in saving downpayment Mj :( " + JSON.stringify(data));
                }
            })
        } else alert("Invalid data;");
    } else getFormula();
}

function editFinstallment() {
    var okay = confirm("Are you sure to save this data?");
    if(okay) {
        var installment = $("#fInstallment").val();
        if(installment.trim().length > 0 && numeric.test(installment) && parseFloat(installment) > 0) {

            $.ajax( {
                type: "POST",
                url: "../php/teller/editFinstallment.php",
                success: function(data) {
                    $("#fInstallment").attr("disabled", true);
                },
                data: {installment: installment},
                error: function(data) {
                    alert("error in saving installment Mj :( " + JSON.stringify(data));
                }
            })
        } else alert("Invalid data;");
    } else getFormula();
}

function addMisc() {
    var miscName = $("#miscName").val();
    var miscAmount = $("#miscAmount").val();
    var miscFor = $("#miscFor").val();

    if(miscName.trim().length > 1 && miscAmount.trim().length > 0 && numeric.test(miscAmount) && miscAmount > 0) {
        $.ajax({
            type: "POST",
            url: "../php/teller/addMisc.php",
            data: {miscName: miscName, miscAmount: miscAmount, miscFor: miscFor},
            success: function(data) {
                if(data == "done") {
                    alert("Miscellaneous successfully added!");
                    $("#miscName").val("");
                    $("#miscAmount").val("");
                    $("#miscFor").val("");
                    displayMiscFees();
                } else {
                    alert(miscName + " for " + miscFor + " is already on the list!");
                }

            },
            error: function(data) {
                alert("Mj error! :( adding misc " + JSON.stringify(data));
            }
        });
    } alert("Please check your inputs!");
}

function getMisc() {
    var misc = $("#miscName").val();

    if(misc.trim().length > 1) {
        $.ajax({
            type: "POST",
            url: "../php/teller/getMisc.php",
            data: {misc: misc},
            success: function(data) {
                $( "#miscName" ).autocomplete({
                    source: JSON.parse(data)
                });
            },
            error: function(data) {
                alert("Mj, error in getMisc() " + JSON.stringify(data));
            }
        });
    }
}

function enableToPay() {
    $('#toPay').attr('disabled', false);
    $('#toPay').focus();
}

function updateTuitionFee() {
    if($("#btnUpdateTuition").html() == "Update Fee per Unit") {
        $("#feePerUnit").attr("disabled", false);
        $("#feePerUnit").focus();
        $("#btnUpdateTuition").html("Save Changes");
    } else {
        var tuition = $("#feePerUnit").val();
        if(tuition.trim().length > 1 && numeric.test(tuition) && parseFloat(tuition) > 0) {
            var okay = confirm("Are you sure to changes the amount fee per unit?");
            if(okay) {
                $.ajax({
                    type: "POST",
                    url: "../php/teller/updateTuitionFee.php",
                    data: {tuition: tuition},
                    success: function(data) {

                        $("#btnUpdateTuition").html("Update Fee per Unit");
                        $("#feePerUnit").attr("disabled", true);
                        alert("Fee per unit was successfully updated! " + data);
                        return false;

                    },
                    error: function(data) {
                        alert("Mj! :( error un updating tuition fee " + JSON.stringify(data));
                    }
                });
            }
        } else alert("Please enter valid a valid amount;")
    }
}

function getFormula() {
    $.ajax({
        type: "POST",
        url: "../php/teller/getFormula.php",
        success: function(data) {
            var obj = JSON.parse(data);
            $("#fDownpayment").val(obj.downpayment);
            $("#fInstallment").val(obj.installment);
        },
        error: function(data) {
            alert("Hey Mj, error in getFormula() :( " + JSON.stringify(data));
        }
    });
}

function getPaymentFor() {
    paymentFor = $("input[type='radio'][name='paymentFor']:checked").val();
    if(paymentFor == "other") {
        $("#paymentForOther").attr("disabled", false);
        $("#toPay").val("");
        $("#toPay").attr("disabled", false);
        $("#paymentForOther").focus();
    } else {
        $("#paymentForOther").attr("disabled", true);
        $("#paymentForOther").focus();
        $("#paymentForOther").val("");
        $("#toPay").attr("disabled", true);
    }

    if(toPay > 0) {
        var d = toPay*.3;
        var r = toPay-d;
        var b = r/4;
        alert(b);
        if(paymentFor == "downPayment") $("#toPay").val(d);
        else if(paymentFor == "prelim" || paymentFor == "midterm" || paymentFor == "prefinal" || paymentFor == "final") $("#toPay").val(b);
        else if(paymentFor == "fullPayment") $("#toPay").val(toPay);
    }
}

function transGetStudInfo() {
    var studentId = $("#studentId").val();
    if(studentId.trim().length > 1 && numeric.test(studentId)) {
        $.ajax({
            type: "POST",
            url: "../php/teller/transGetStudInfo.php",
            data: {studentId: studentId},
            success: function(data) {
                if(data != "none") {
                    var obj = JSON.parse(data);
                    if(obj.currentBalance <= 0) alert("Student is already full paid!");
                    $("#studentName").html(obj.name);
                    $("#currentBalance").val(obj.currentBalance);
                    $("#studentTransDetailData").html(obj.details);
                    toPay = obj.toPay;
                    currentBalance = obj.currentBalance;
                    curStudentId = studentId;
                } else alert(studentId + " student ID doesn't exit!");
            },
            error: function(data) {
                alert("error in getting stud info Mj! :( " + JSON.stringify(data));
            }
        });
    } else alert("Invalid student ID;");
}

function transact() {
    var needToPay = $("#toPay").val();
    var amountRendered = $("#amountRendered").val();

    if(parseFloat(amountRendered) >= parseFloat(needToPay)) {
        var change = amountRendered - needToPay;
        if(change > 0) $("#change").val(change);
        if(paymentFor == "other") paymentFor = $("#paymentForOther").val();

        if(paymentFor.trim().length > 1) {
            $.ajax({
                type: "POST",
                url: "../php/teller/transact.php",
                data: {studentId: curStudentId,currentBalance: currentBalance, needToPay: needToPay, amountRendered: amountRendered, paymentFor: paymentFor},
                success: function(data) {
                    alert("Transaction saved! " + data);
                    $("#toPay").val("");
                    $("#amountRendered").val("");
                    $("#change").val("00.00");
                    window.open("transaction", "Payment Receipt", "width=1000,height=800");
                },
                error: function(data) {
                    alert("error Mj :( " + JSON.stringify(data));
                }
            });
        } else alert("Please specify other payment;");

    } else alert("Insufficient amount rendered!\n\nTo pay: " + needToPay + "\n\nRendered: " + amountRendered);
}