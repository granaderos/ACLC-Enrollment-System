 /**
 * Created by Marejean on 10/23/16.
 */

var alphabet = /^[a-z, A-Z, -]*$/;
var numeric = /^[0-9]*$/;

$(document).ready(function() {

});
var regexInt = /^[0-9]+$/;

function displayAvailableSections() {
    var program = $("#selProgram").val();

    $.ajax({
        type: "POST",
        url: "../php/students/displayAvailableSections.php",
        data: {program: program},
        success: function(data) {
            $("#selSection").html(data);
        },
        error: function(data) {
            console.log("displaying available section error " + JSON.stringify(data));
        }
    });
}

function displayNewStudentID() {
    $.ajax({
        type: "POST",
        url: "../php/students/displayNewStudentID.php",
        success: function(data) {
            $("#txtNewStudentID").val(data);
        }
    });
}

function displayAvailableCurriculum() {
    $.ajax({
        type: "POST",
        url: "../php/students/displayAvailableCurriculum.php",
        success: function(data) {
            $("#selCurriculum").html(data);
        },
        error: function(data) {
            console.log("Error in displaying AVAILABLE ccurriculum " + JSON.stringify(data));
        }
    });
}


function displayAvailablePrograms() {
    $.ajax({
        type: "POST",
        url: "../php/students/displayAvailablePrograms.php",
        success: function(data) {
            $("#selProgram").html(data);
        },
        error: function(data) {
            alert("error  " + JSON.stringify(data))
        }
    })
}

function displayStudentRequirements() {
    $.ajax({
        type: "POST",
        url: "../php/students/displayStudentRequirements.php",
        success: function(data) {
            $("#tblReqs").html(data);
        }
    });
}

function enrollStudent() {
    var firstName = $("#firstName").val();
    var middleName = $("#middleName").val();
    var lastName = $("#lastName").val();
    var id = $("#txtNewStudentID").val();
    var type = $("#selType").val();
    var program = $("#selProgram").val();
    var curriculum = $("#selCurriculum").val();
    var year = $("#selYear").val();

    var section = $("#selSection").val();

    if(firstName.trim().length > 1 && alphabet.test(firstName) &&
        lastName.trim().length > 1 && alphabet.test(lastName) &&
        middleName.trim().length > 1 && alphabet.test(middleName) &&
        program != "") {
        var reqsIds = new Array();
        var tblReqs = /*$("#formReqs")*/ document.getElementById("tblReqs");
        var tblRows = tblReqs.getElementsByTagName("tr");
        var counter = 0;
        while(counter < tblRows.length) {
            var checkReq = document.getElementById('checkReq' + tblRows[counter].id);
            if(checkReq.checked) {
                reqsIds.push(tblRows[counter].id);
            }
            counter++;
        }

        if(reqsIds.length <= 0) reqsIds = "none";

        $.ajax({
            type: "POST",
            url: "../php/students/enrollStudent.php",
            data: {id: id, type: type, program: program, curriculum: curriculum, year: year, firstName: firstName,
                middleName: middleName, lastName: lastName, type: type, reqsIds: reqsIds, section: section},
            success: function(data) {
                alert(data);

                $("#firstName").val("");
                $("#middleName").val("");
                $("#lastName").val("");
                displayNewStudentID();
                window.location.reload();
            },
            error: function(data) {
                console.log("error in enrolling student " + JSON.stringify(data));
            }
        });
    } else alert("Please check your inputs!");
}

function getRecentlyEnrolledStudents() {
    $.ajax({
        type: "POST",
        url: "../php/students/getRecentlyEnrolledStudents.php",
        success: function(data) {
            $("#divRecentlyEnrolledStudents").html(data);
        },
        error: function(data) {
            console.log("Error in displaying getRecentlyEnrolledStudents " + JSON.stringify(data));
        }
    });
}

function setStudentSession(studentId) {
    $.ajax({
        type: "POST",
        url: "../php/students/setStudentSession.php",
        data: {studentId: studentId},
        success: function() {
            window.location.assign("info");
        },
        error: function(data) {
            console.log("Error in setting student session " + JSON.stringify(data));
        }

    });
}

function getStudentInfo() {
    $.ajax({
        type: "POST",
        url: "../../php/students/getStudentInfo.php",
        success: function(data) {
            //$("#tblStudInfo").html(data);
            console.log("data = " + data);

            var obj = JSON.parse(data);
            $("#studStudNo").html(obj.studentId);
            $("#studProg").html("(" + obj.progCode + ") " + obj.program);
            $("#studCur").html(obj.curriculum);
            $("#studYear").html(obj.yearLevel);
            $("#studName").html(obj.lastname + ", " + obj.firstname + " " + obj.middlename);
            $("#studGender").html(obj.gender);
            $("#studAddress").html(obj.address);
            $("#studContact").html(obj.contactNumber);
            $("#studBirthDate").html(obj.birthday);
            $("#studAge").html(obj.age);
            $("#studBirthPlace").html(obj.birthOfPlace);
            $("#studNationality").html(obj.nationality);
            $("#studSecSchool").html(obj.secondarySchool);
            $("#studSecDate").html(obj.secDateGraduated);
            $("#studSchoolLastAttended").html(obj.schoolLastAttended);
            $("#studLastAttendedDate").html(obj.schoolLastAttendedDateAttended);

            $("#gName").html(obj.glName+", "+gfName+" "+gmName);
            $("#gAddress").html(obj.gAddress);
            $("#gContactNumber").html(obj.gContactNumber);
            $("#gRelationship").html(obj.gRelationship);



        },
        error: function(data) {
            console.log("error in getting student info " + JSON.stringify(data));
        }
    });
}