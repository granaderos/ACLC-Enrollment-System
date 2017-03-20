/**
 * Created with JetBrains PhpStorm.
 * User: root
 * Date: 2/28/17
 * Time: 5:56 AM
 * To change this template use File | Settings | File Templates.
 */

$(document).ready(function() {

});

function toggleHome() {
    displayTodaySched();
    $("#divProfMainContainer").html($("#divHomeContainer").html());
}

function toggleClassList() {
    $("#divProfMainContainer").html($("#divClassListContainer").html());
}

function toggleEncodeGrades() {
    $("#divProfMainContainer").html($("#divEncodeGradesContainer").html());
}

function toggleSchedule() {
    $("#divProfMainContainer").html($("#divScheduleContainer").html());
}

function getSectionToEncode() {
    $.ajax({
        type: "POST",
        url: "../php/faculty/getSectionsToEncode.php",
        success: function(data) {
            $("#encodeGradeSectionsContainer").html(data);
        },
        error: function(data) {
            alert("erro in getting sections to encode " + JSON.stringify(data));
        }
    });
}

function setSessionForEncode(sectionId, courseCode) {
    $.ajax({
        type: "POST",
        url: "../php/faculty/setSessionForEncode.php",
        data: {sectionId: sectionId, courseCode: courseCode},
        success: function() {
            window.location = "grades";
        },
        error: function(data) {
            alert("error in displaying encoding form " + JSON.stringify(data));
        }
    });
}

function displayClassList(sectionId, courseCode) {
    $.ajax({
        type: "POST",
        url: "../php/faculty/displayClassList.php",
        data: {sectionId: sectionId, courseCode: courseCode},
        success: function(data) {
            if(data != "none")
                window.open("classList", "_blank");
            else alert("No enrolled students in " + courseCode + " in this section;");
        },
        error: function(data) {
            alert("error in displaying class list " + JSON.stringify(data));
        }
    })
}

function getClassList() {
    $.ajax({
        type: "POST",
        url: "../php/faculty/getClassList.php",
        success: function(data) {
            $("#classListContainer").html(data);
        },
        error: function(data) {
            alert("getting class list error " + JSON.stringify(data));
        }
    });
}

function displayTodaySched() {
    $.ajax({
        type: "POST",
        url: "../php/faculty/displayTodaySched.php",
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

function displaySchedule() {
    $.ajax({
        type: "POST",
        url: "../php/faculty/displaySchedule.php",
        success: function(data) {
            $("#schedScheduleContainer").html(data);
        },
        error: function(data) {
            alert("error in displaying schedule " + JSON.stringify(data));
        }
    });
}