 /**
 * Created by Marejean on 10/23/16.
 */

var alphabet = /^[a-z, A-Z, -]*$/;
var numeric = /^[0-9]*$/;
var regexInt = /^[0-9]+$/;
var curStudentId = -1, curReqId = -1;

$(document).ready(function() {
    var formData = false;
    if(window.FormData) {
        formData = new FormData();
    }
    var reqImage = "";
    $("#reqUploadImage").change(function(data) {
        reqImage = this.files[0];
    });

    $("#btnReqUploadImage").click(function() {
        if(formData) {
            formData.append("reqImage", reqImage);
            formData.append("studentId", curStudentId);
            formData.append("reqId", curReqId);

            $.ajax({
                type: "POST",
                url: "../../php/students/uploadRequirement.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    displaySubmittedReqs();
                    alert("Successfully uploaded!" + data);
                    $("#promptUploadReqDiv").slideUp(200);
                    $("#modal-backdrop").slideUp(200);
                },
                error: function(data) {
                    alert("oopsy Mj! :( error in uploading req image " + JSON.stringify(data));
                }
            });

        }
    });

    var studImage = "";
    $("#studUpPhoto").change(function(data) {
        studImage = this.files[0];
    });

    $("#btnStudUpPhoto").click(function() {
        if(formData) {
            formData.append("studImage", studImage);
            $.ajax({
                type: "POST",
                url: "../../php/students/uploadStudentPhoto.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    alert(data);
                    $("#spanStudPhoto").html("<img src='../../files/profiles/"+data+"' style='width: 180px; height: 200px;' class='image-responsive' />");
                },
                error: function(data) {
                    alert("oopsy Mj! :( error in uploading req image " + JSON.stringify(data));
                }
            });

        }
    });
});

function viewReqFile(imageName) {
    $("#viewReqImageData").html("<img src = '../../files/requirements/"+imageName+"'>");
}

 function displaySubmittedReqs() {
     $.ajax({
         type: "POST",
         url: "../../php/students/displaySubmittedReqs.php",
         success: function(data) {
             $("#stuentReqsDataContainer").html(data);
         },
         error: function(data) {
             alert("error mj displaySubmittedReqs " + JSON.stringify(data));
         }
     });
 }

 function promptUploadReq(studentId, reqId) {
    curStudentId = studentId, curReqId = reqId;
 }

 function submitRequirement(studentId, reqId) {
     var okay = confirm("Student has submitted this requirements?");
     if(okay) {
         $.ajax({
             type: "POST",
             url: "../../php/students/submitRequirement.php",
             data: {studentId: studentId, reqId: reqId},
             success: function(data) {
                 displaySubmittedReqs();
                 alert("Requirement status sucessfully updated! " + data);
             },
             error: function(data) {
                 alert("error mj :( " + JSON.stringify(data));
             }
         });
     } else alert("Update requirements status cancelled;");
 }

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

function showRegistrationForm(studentId) {
    $.ajax({
        type: "POST",
        url: "../php/students/showRegistrationForm.php",
        data: {studentId: studentId},
        success: function(data) {
            if(data != "none")
                window.open("registration-form", "Registration Form", "width=1200%,height=800%");
        },
        error: function(data) {
            alert("Error Mj! in showRegistrationForm " + JSON.stringify(data));
        }
    });
}

function getRecentlyEnrolledStudents() {
    var name = "", program = "", year = "";
    name = $("#regStudSearch").val();
    program = $("#regDisplayProgram").val();
    year = $("#regDisplayYear").val();
    $.ajax({
        type: "POST",
        url: "../php/students/getRecentlyEnrolledStudents.php",
        data: {name: name, program: program, year: year},
        success: function(data) {
            $("#divRecentlyEnrolledStudents").html(data);
        },
        error: function(data) {
            console.log("Error in displaying getRecentlyEnrolledStudents " + JSON.stringify(data));
        }
    });
    return false;
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
            $("#studentNameHere").html(obj.lastname + ", " + obj.firstname + " " + obj.middlename);
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

            $("#gName").html(obj.glName+", "+obj.gfName+" "+obj.gmName);
            $("#gAddress").html(obj.gAddress);
            $("#gContactNumber").html(obj.gContactNumber);
            $("#gRelationship").html(obj.gRelationship);

            $("#spanStudPhoto").html("<img src='../../files/profiles/"+obj.photo+"' style='width: 180px; height: 200px;' class='image-responsive' />");
        },
        error: function(data) {
            console.log("error in getting student info " + JSON.stringify(data));
        }
    });
}