/**
 * Created with JetBrains PhpStorm.
 * User: root
 * Date: 1/23/17
 * Time: 8:24 PM
 * To change this template use File | Settings | File Templates.
 */

var alphabet = /^[a-z, A-Z, -]*$/;
var numeric = /^[0-9]*$/;

$(document).ready(function() {
    $("#addStaff").click(function() {
        var lastName = $("#lastName").val();
        var middleName = $("#middleName").val();
        var firstName = $("#firstName").val();
        var type = $("#type").val();
        var username = $("#username").val();

        if(lastName.trim() != "" && lastName.length > 1 && alphabet.test(lastName) &&
           firstName.trim() != "" && firstName.length > 1 && alphabet.test(firstName) &&
            middleName.trim() != "" && middleName.length > 1 && alphabet.test(middleName) &&
            username.trim() != "") {
                if(username.length >= 8) {
                    $.ajax({
                        type: "POST",
                        url: "../php/admin/addStaff.php",
                        data: {lastName: lastName, firstName: firstName, middleName: middleName, username: username, type: type},
                        success: function() {
                            alert("New employee added successfully!");
                        },
                        error: function(data) {
                            console.log("error in adding staff " + JSON.stringify(data));
                        }
                    });
                } else {
                    alert("For security purposes, username must be at least 8 characters");
                }

        } else {
            alert("Please check your inputs!");
        }

        $("#lastName").val("");
        $("#middleName").val("");
        $("#firstName").val("");
        $("#type").val("");
        $("#username").val("");
        $("#password").val("");
        $("#confirmPassword").val("");

        return false;
    });
});

function adminViewStaff() {
    $.ajax({
        type: "POST",
        url: "../php/admin/adminViewStaff.php",
        success: function(data) {
            $("#tblAdminViewStaff").html(data);
        },
        error: function(data) {
            console.log("error in aadminViewStaff " + JSON.stringify(data));
        }
    });
}

function hrViewStaff() {
    $.ajax({
        type: "POST",
        url: "../php/admin/hrViewStaff.php",
        success: function(data) {
            $("#tblHrViewStaff").html(data);
            $("#hrMainContainer").html($("#divHrViewStaff").html());
        },
        error: function(data) {
            console.log("error in hrViewStaff() " + JSON.stringify(data));
        }

    });
}

function hrEditStaff(staffId) {
    $.ajax({
        type: "POST",
        url: "../php/admin/hrEditStaff.php",
        data: {staffId: staffId},
        success: function(data) {
            if(data != "none"){
                var obj = JSON.parse(data);
                $("#staffId").val(staffId);
                $("#newLname").val(obj.lastname);
                $("#newFname").val(obj.firstname);
                $("#newMname").val(obj.middlename);
            } else alert("data " + data);
        },
        error: function(data) {
            console.log("Error in hrEditStaff " + JSON.stringify(data));
        }
    });
}

function hrSaveStaff() {
    var fName = $("#newFname").val();
    var lName = $("#newLname").val();
    var mName = $("#newMname").val();

    if(fName.trim().length > 1 && alphabet.test(fName) &&
        lName.trim().length > 1 && alphabet.test(lName) &&
        mName.trim().length > 1 && alphabet.test(mName)) {
        var staffId = $("#staffId").val();
        $.ajax({
            type: "POST",
            url: "../php/admin/hrSaveStaff.php",
            data: {fName: fName, lName: lName, mName: mName, staffId: staffId},
            success: function(data) {
                $("#hrStaffName"+staffId).html(lName + ", " + fName + " " + mName);
                $("#divHrEditStaff").toggle();
                $(".modal-backdrop").hide();
            },
            error: function(data) {
                console.log("Eror in saving staff " + JSON.stringify(data));
            }
        });
    } else alert("Please check your  inputs.");
}

function hrDeleteStaff(staffId) {
    var okay = confirm("Are you sure to remove the " + $("#hrStaffName"+staffId).html() + "?")
    if(okay) {
        $.ajax({
            type: "POST",
            url: "../php/admin/hrDeleteStaff.php",
            data: {staffId: staffId},
            success: function(data) {
                $("#hrStaffId"+staffId).remove();
                alert("Successfully deleted!");
            },
            error: function(data) {
                console.log("error in deleting staff " + JSON.stringify(data));
            }
        })
    }
}