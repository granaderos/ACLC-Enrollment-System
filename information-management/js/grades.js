/**
 * Created with JetBrains PhpStorm.
 * User: root
 * Date: 3/2/17
 * Time: 12:49 AM
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function() {
    displayEncodingForm();
});

function displayEncodingForm() {
    $.ajax({
        type: "POST",
        url: "../../php/faculty/displayEncodingForm.php",
        success: function(data) {
            $("#divGradeMainContainer").html(data);
        },
        error: function(data) {
            alert("error in displaying encoding form " + JSON.stringify(data));
        }
    });
}

var validGrade = /^[0-9, ., D]*$/;

function editPrelimGrade() {
    $(".pGradeInput").attr("disabled", false);
}

function editMidtermGrade() {
    $(".mGradeInput").attr("disabled", false);
}

function editPreFinalGrade() {
    $(".pfGradeInput").attr("disabled", false);
}

function editFinalGrade() {
    $(".fGradeInput").attr("disabled", false);
}

function savepGrade(studentId, courseCode) {
    var grade = $("#pGrade"+studentId).val();
    if(validGrade.test(grade)) {
        $.ajax({
            type: "POST",
            url: "../../php/faculty/savepGrade.php",
            data: {studentId: studentId, courseCode: courseCode, grade: grade},
            success: function(data) {
                $("#pGrade"+studentId).val(grade);
                $("#pGrade"+studentId).attr("disabled", true);
            },
            error: function(data) {
                alert("error in saving grade " + JSON.stringify(data));
            }
        });
    } else alert("Please check input!");
}

function savemGrade(studentId, courseCode) {
    var grade = $("#mGrade"+studentId).val();
    if(validGrade.test(grade)) {
        $.ajax({
            type: "POST",
            url: "../../php/faculty/savemGrade.php",
            data: {studentId: studentId, courseCode: courseCode, grade: grade},
            success: function(data) {
                $("#mGrade"+studentId).val(grade);
                $("#mGrade"+studentId).attr("disabled", true);
            },
            error: function(data) {
                alert("error in saving grade " + JSON.stringify(data));
            }
        });
    } else alert("Please check input!");
}

function savepfGrade(studentId, courseCode) {
    var grade = $("#pfGrade"+studentId).val();
    if(validGrade.test(grade)) {
        $.ajax({
            type: "POST",
            url: "../../php/faculty/savepfGrade.php",
            data: {studentId: studentId, courseCode: courseCode, grade: grade},
            success: function(data) {
                $("#pfGrade"+studentId).val(grade);
                $("#pfGrade"+studentId).attr("disabled", true);
            },
            error: function(data) {
                alert("error in saving grade " + JSON.stringify(data));
            }
        });
    } else alert("Please check input!");
}

function savefGrade(studentId, courseCode) {
    var grade = $("#fGrade"+studentId).val();
    if(validGrade.test(grade)) {
        $.ajax({
            type: "POST",
            url: "../../php/faculty/savefGrade.php",
            data: {studentId: studentId, courseCode: courseCode, grade: grade},
            success: function(data) {
                $("#fGrade"+studentId).val(grade);
                $("#fGrade"+studentId).attr("disabled", true);
            },
            error: function(data) {
                alert("error in saving grade " + JSON.stringify(data));
            }
        });
    } else alert("Please check input!");
}

function toggleHome() {
    window.location = "../";
    displayTodaySched();
    $("#divProfMainContainer").html($("#divHomeContainer").html());
}

function toggleClassList() {
    window.location = "../";
    $("#divProfMainContainer").html($("#divClassListContainer").html());
}

function toggleEncodeGrades() {
    window.location = "../";
    $("#divProfMainContainer").html($("#divEncodeGradesContainer").html());
}

function toggleSchedule() {
    window.location = "../";
    $("#divProfMainContainer").html($("#divScheduleContainer").html());
}


function displayTodaySched() {
    $.ajax({
        type: "POST",
        url: "../../php/faculty/displayTodaySched.php",
        success: function(data) {
            var obj = JSON.parse(data);
            $("#homeSchedContainer").html(obj.sched);
            $("#schedDay").html("("+obj.day+")");
        },
        error: function(data) {
            alert("error in display today sched " + JSON.stringify(data));
        }
    });
}
