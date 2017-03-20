/**
 * Created with JetBrains PhpStorm.
 * User: root
 * Date: 2/24/17
 * Time: 10:40 PM
 * To change this template use File | Settings | File Templates.
 */
var alphabet = /^[a-z, A-Z, -]*$/;
var numeric = /^[0-9]*$/;
var email = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
$(document).ready(function() {
    $("#studEditPI").tooltip();

    toggleHome();
    displayStudentInfoAll();
});

function toggleHome() {
    displayPhotofilePhoto();
    displayProfile();
    $("#studMainContainerDiv").html($("#homeContainerDiv").html());
}

function toggleClassSchedule() {
     displayClassSchedule();
    $("#studMainContainerDiv").html($("#studClassScheduleContainerDiv").html());
}

function displayPhotofilePhoto() {
    $.ajax({
        type: "POST",
        url: "../php/displayProfilePhoto.php",
        success: function(data) {
                alert(data);
            $("#studProfilePhoto").html("<img src='../../information-management/files/profiles/"+data+"' style='width: 180px; height: 200px;' class='image-responsive' />");
        },
        error: function(data) {
            alert("error in displaying profile photo Mj! :( " + JSON.stringify(data));
        }
    });
}

function displayClassSchedule() {
    $.ajax({
        type: "POST",
        url: "../php/displayClassSchedule.php",
        success: function(data) {
            $("#studSlassScheduleData").html(data);
        },
        error: function(data) {
            alert("error in displaying schedule Mj! :( " + JSON.stringify(data));
        }
    });
}

function toggleAccountBalance() {
    displayAccountBalance();
    $("#studMainContainerDiv").html($("#accountBalanceContainerDiv").html());
}

function toggleViewGrades() {
    displayGrades();
    $("#studMainContainerDiv").html($("#gradesContainerDiv").html());
}

function toggleAccountSetting() {
    displayStudentInfoAll();
    $("#studMainContainerDiv").html($("#divAccountSettingContainer").html());
}

function togglePreregistration() {
    displayPreregistration();
}

function displayAccountBalance() {
    $.ajax({
        type: "POST",
        url: "../php/displayAccountBalance.php",
        success: function(data) {
            $("#accountBalanceDataContainer").html(data);
        },
        error: function(data) {
            alert("Oh my! Error Mj! :( " + JSON.stringify(data));
        }
    });
}

function displayGrades() {
    $.ajax({
        type: "POST",
        url: "../php/displayGrades.php",
        success: function(data) {
            $("#myGradesContainer").html(data);
        },
        error: function(data) {
            alert("Mj, error in displaying grades " + JSON.stringify(data));
        }
    });
}

function displayStudentInfoAll() {
    $.ajax({
        type: "POST",
        url: "../php/displayStudentInfoAll.php",
        success: function(data) {
            var obj = JSON.parse(data);

            $("#fName").val(obj.firstname);
            $("#mName").val(obj.lastname);
            $("#lName").val(obj.middlename);
            $("#address").val(obj.address);
            $("#emailAddress").val(obj.emailAddress);
            $("#contactNumber").val(obj.contactNumber);
            $("#birthday").val(obj.birthday);
            $("#birthPlace").val(obj.placeOfBirth);
            $("#nationality").val(obj.nationality);

            $("#gfName").val(obj.gfName);
            $("#gmName").val(obj.gmName);
            $("#glName").val(obj.glName);
            $("#gRelationship").val(obj.gRelationship);
            $("#gAddress").val(obj.gAddress);
            $("#gContactNumber").val(obj.gContactNumber);

            $("#sSchool").val(obj.secondarySchool);
            $("#sYearGrad").val(obj.secDateGraduated);

            $("#tSchoolAttended").val(obj.schoolLastAttended);
            $("#tYearAttended").val(obj.schoolLastAttendedDateAttended);

            $("#username").val(obj.username);
        },
        error: function(data) {
            console.log("error in displayStudentInfoAll " + JSON.stringify(data));
        }
    });
}

function saveStudentInfo() {
    var fName = $("#fName").val();
    var mName = $("#mName").val();
    var lName = $("#lName").val();
    var address = $("#address").val();
    var emailAddress = $("#emailAddress").val();
    var contactNumber = $("#contactNumber").val();
    var birthday = $("#birthday").val();
    var gender = $("#gender").val();
    var birthPlace = $("#birthPlace").val();
    var nationality = $("#nationality").val();

    var gfName = $("#gfName").val();
    var gmName = $("#gmName").val();
    var glName = $("#glName").val();
    var gRelationship = $("#gRelationship").val();
    var gAddress = $("#gAddress").val();
    var gContactNumber = $("#gContactNumber").val();

    var sSchool = $("#sSchool").val();
    var sYearGrad = $("#sYearGrad").val();

    var tSchoolAttended = "";
    tSchoolAttended = $("#tSchoolAttended").val();
    var tYearAttended = "";
    tYearAttended = $("#tYearAttended").val();

    var username = $("#username").val();
    var oldPassword = $("#oldPassword").val();
    var newPassword = "";
    newPassword = $("#newPassword").val();
    var confirmPassword = "";
    confirmPassword = $("#confirmPassword").val();

    if(fName.trim().length > 1 && alphabet.test(fName) &&
        mName.trim().length > 1 && alphabet.test(mName) &&
        lName.trim().length > 1 && alphabet.test(lName) &&
        address.trim().length > 1 &&
        emailAddress.trim().length > 1 && email.test(emailAddress) &&
        contactNumber.trim().length == 11 && numeric.test(contactNumber) &&
        birthday.trim().length > 1 &&
        birthPlace.trim().length > 1 &&
        nationality.trim().length > 1 && alphabet.test(nationality) &&
        gfName.trim().length > 1&& alphabet.test(gfName) &&
        gmName.trim().length > 1 && alphabet.test(gmName) &&
        glName.trim().length > 1 && alphabet.test(glName) &&
        gRelationship.trim().length > 1 && alphabet.test(gRelationship) &&
        gAddress.trim().length > 1 &&
        gContactNumber.trim().length == 11 && numeric.test(gContactNumber) &&
        sSchool.trim().length > 1 && alphabet.test(sSchool) &&
        sYearGrad.trim().length > 1 &&
        username.trim().length > 1) {

        if(oldPassword.trim().length > 0) {
            var passwordOkay = true;
            if(newPassword.trim() != "") {
                if(newPassword.length >= 8) {
                    if(newPassword == confirmPassword) {
                        passwordOkay = true;
                    } else {
                        alert("Confirm password mismatched!");
                        passwordOkay = false;
                    }
                } else {
                    alert("New password must be at least 8 characters long;");
                    passwordOkay = false;
                }
            }
            if(passwordOkay) {
                var okay = false;
                if(tSchoolAttended.trim() != "") {
                    if(tYearAttended.trim() != "") {
                        if(alphabet.test(tSchoolAttended) && numeric.test(tYearAttended)) okay = true;
                    } else alert("Please enter the year you last attended " + tSchoolAttended);
                } else if(tYearAttended.trim() != "") {
                    if(tSchoolAttended.trim() != "") {
                        if(alphabet.test(tSchoolAttended) && numeric.test(tYearAttended)) okay = true;
                    } else alert("Please enter the school you last attended on " + tYearAttended)
                } else okay = true;
                if(okay) {
                    $.ajax({
                        type: "POST",
                        url: "../php/saveStudentInfo.php",
                        data: {fName: fName, mName: mName, lName: lName, address: address, emailAddress: emailAddress,
                            contactNumber: contactNumber, birthday: birthday, gender: gender, birthPlace: birthPlace, nationality: nationality,
                            gfName: gfName, gmName: gmName, glName: glName, gRelationship: gRelationship,
                            gAddress: gAddress, gContactNumber: gContactNumber, sSchool: sSchool,
                            sYearGrad: sYearGrad, tSchoolAttended: tSchoolAttended, tYearAttended: tYearAttended,
                            username: username, oldPassword: oldPassword, newPassword: newPassword
                        },
                        success: function(data) {
                            $("#fName").attr("disabled", true);
                            $("#mName").attr("disabled", true);

                            $("#lName").attr("disabled", true);
                            $("#address").attr("disabled", true);
                            $("#emailAddress").attr("disabled", true);
                            $("#contactNumber").attr("disabled", true);
                            $("#birthday").attr("disabled", true);
                            $("#birthPlace").attr("disabled", true);
                            $("#gender").attr("disabled", true);
                            $("#nationality").attr("disabled", true);

                            $("#gfName").attr("disabled", true);
                            $("#gmName").attr("disabled", true);
                            $("#glName").attr("disabled", true);
                            $("#gRelationship").attr("disabled", true);
                            $("#gAddress").attr("disabled", true);
                            $("#gContactNumber").attr("disabled", true);

                            $("#sSchool").attr("disabled", true);
                            $("#sYearGrad").attr("disabled", true);

                            $("#tSchoolAttended").attr("disabled", true);
                            $("#tYearAttended").attr("disabled", true);

                            $("#username").attr("disabled", true);
                            $("#oldPassword").attr("disabled", true);
                            $("#newPassword").attr("disabled", true);
                            $("#confirmPassword").attr("disabled", true);

                            alert("Info was successfully updated!" + data);
                        },
                        error: function(data) {
                            alert("error in saving student info " + JSON.stringify(data));
                        }
                    });
                } else alert("Not okay; Please check inputs;");
            } else alert("Please check inputs;");
        } else alert("Please input your current password;");

    } else {
        alert("Please fill-out the necessary fields and check you inputs!");
    }

}

function editInfoAll() {
    $("#fName").attr("disabled", false);
    $("#fName").focus();

    $("#mName").attr("disabled", false);
    $("#lName").attr("disabled", false);
    $("#address").attr("disabled", false);
    $("#emailAddress").attr("disabled", false);
    $("#contactNumber").attr("disabled", false);
    $("#birthday").attr("disabled", false);
    $("#gender").attr("disabled", false);
    $("#birthPlace").attr("disabled", false);
    $("#nationality").attr("disabled", false);

    $("#gfName").attr("disabled", false);
    $("#gmName").attr("disabled", false);
    $("#glName").attr("disabled", false);
    $("#gRelationship").attr("disabled", false);
    $("#gAddress").attr("disabled", false);
    $("#gContactNumber").attr("disabled", false);

    $("#sSchool").attr("disabled", false);
    $("#sYearGrad").attr("disabled", false);

    $("#tSchoolAttended").attr("disabled", false);
    $("#tYearAttended").attr("disabled", false);

    $("#username").attr("disabled", false);
    $("#oldPassword").attr("disabled", false);
    $("#newPassword").attr("disabled", false);
    $("#confirmPassword").attr("disabled", false);
}

function displayProfile() {
    $.ajax({
        type: "POST",

        url: "../php/displayProfile.php",
        success: function(data) {
            var obj = JSON.parse(data);
            $("#profName").html(obj.lastname+", "+obj.firstname+" "+obj.middlename);
            $("#profProgram").html("(" + obj.progCode + ") " + obj.program);
            $("#profYear").html(obj.year);
            $("#profSem").html(obj.sem);
            $("#profUnits").html(obj.units);

            $("#studTodaySchedData").html(obj.sched);
            $("#sctudSchedDay").html(obj.day);

            $("#currentlyEnrolledCoursesDiv").html(obj.courses);
        },
        error: function(data) {
            alert("error displayProfile " + JSON.stringify(data));
        }
    });
}

var prereg = new Array();

function selectCourse(courseCode, unit) {
    if($("#check"+courseCode).is(':checked')) {
        var curUnitTotal = parseInt($("#totalUnitsPrereg").html()) + unit;
        var maxUnit = parseInt($("#maxUnits").html());

        if(curUnitTotal > maxUnit) alert("You are not allowed to exceed the maximum total number of units to enroll!");
        else {
            $("#totalUnitsPrereg").html(curUnitTotal);
            prereg.push(courseCode);
        }
    } else {
        var curUnitTotal = parseInt($("#totalUnitsPrereg").html()) - unit;
        $("#totalUnitsPrereg").html(curUnitTotal);
        var i = prereg.indexOf(courseCode);
        prereg.splice(i, 1);
    }

}

function savePreregistration() {
    var units = parseInt($("#totalUnitsPrereg").html());
    if(units > 0) {
        $.ajax({
            type:"POST",
            url: "../php/savePreregistration.php",
            data: {prereg: prereg},
            success: function(data) {
                alert("Pre-registration saved!");
                displayPreregistration();
            },
            error: function(data) {
                alert("error in savinf preregistration " + JSON.stringify(data));
            }
        });
    } else alert("Please choose first the courses you want to pre-register;")
}

/*function displayPreregistrationData() {
    $.ajax({
        type: "POST",
        url: "../php/displayPreregistrationData.php",
        success: function(data) {
            $("#pregistrationData").html(data);
        },
        error: function(data) {
            alert("error in displayPreregistrationData " + JSON.stringify(data));
        }
    })
}*/

function displayPreregistration() {
    $.ajax({
        type: "POST",
        url: "../php/displayPreregistrationData.php",
        success: function(data) {
            var obj = JSON.parse(data);
            if(obj.done == "true") {
                $("#preregistrationContainerDiv").html(obj.preregistration);
                $("#studMainContainerDiv").html($("#preregistrationContainerDiv").html());
            } else {
                $("#pregistrationData").html(obj.preregistration);
                $("#studMainContainerDiv").html($("#preregistrationContainerDiv").html());
            }
        },
        error: function(data) {
            alert("error in displaying preregistration " + JSON.stringify(data));
        }
    });
}

function chooseSchedule(courseCode) {
    $("#courseToAssignSched").html(courseCode);
    $.ajax({
        type: "POST",
        url: "../php/chooseSchedule.php",
        data: {courseCode: courseCode},
        success: function(data) {
            $("#chooScheduleContainer").html(data);
        },
        error: function(data) {
            alert("Mj, errr in choosing schedule " + JSON.stringify(data));
        }
    });
}

function saveChosenSection(courseCode, sectionId) {
    $.ajax({
        type: "POST",
        url: "../php/saveChosenSection.php",
        data: {courseCode: courseCode, sectionId: sectionId},
        success: function(data) {
            displayPreregistration();
            var obj = JSON.parse(data);
            alert(obj.message);
            if(obj.conflict == "false") {
                displayPreregistration();
                $("#assignScheduleDiv").slideUp(200);
                $(".modal-backdrop").slideUp(200);
            }
        },
        error: function(data) {
            alert("Mj! :( Error in saving chosen section " + JSON.stringify(data));
        }
    });
}