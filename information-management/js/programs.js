/**
 * Created by Marejean on 10/23/16.
 */
var alphabet = /^[a-z, A-Z, -]*$/;
var numeric = /^[0-9]*$/;
var preReqs = new Array();
$(document).ready(function() {

    $("#tblForCourseEncoding").hide();
    $("#divAddCourses").hide();

    $("#btnEditProgram").click(function() {
        var newProgCode = $("#newProgCode").val();
        var description = $("#newProgDescription").val();
        var code = $("#prevProgCode").val();

        if(newProgCode.trim() != "" && alphabet.test(newProgCode) && newProgCode.length > 1 &&
           description.trim() != "" && alphabet.test(description) && description.length > 5) {
            $.ajax({
                type: "POST",
                url: "../php/programs/editProgram.php",
                data: {code: code, newProgCode: newProgCode, description: description},
                success: function() {
                    $("#prog"+code).html(description);
                    $("#editProgram").toggle();
                    $(".modal-backdrop").hide();
                },
                error: function(data) {
                    console.log("error in editing programs " + JSON.stringify(data));
                }
            });
        } else alert("Please check your inputs");

    });

});

function btnSaveCurrentSemestralCourses() {

    var program = $("#programOnSetUp").html();
    var year = $("#currentYearOnSetUp").html();
    var sem = $("#currentSemOnSetUp").html();
    var curriculum = $("#curriculumOnSetUp").html();

    var curYearOnSetUp = $("#currentYearOnSetUp").html();
    var curSemOnSetUp = $("#currentSemOnSetUp").html();

    if(curSemOnSetUp == 1) $("#currentSemOnSetUp").html(++curSemOnSetUp);
    else  if(curYearOnSetUp < year) {
        $("#currentYearOnSetUp").html(++curYearOnSetUp);
        $("#currentSemOnSetUp").html(1)
    }  else {
        alert("Done na!");

    }
    $("#tbodyCoursesOnSetUp").html("");

}

function btnSaveCurrentTrimestralCourses() {
    var program = $("#programOnSetUp").html();
    var year = $("#currentYearOnSetUp").html();
    var sem = $("#currentSemOnSetUp").html();
    var curriculum = $("#curriculumOnSetUp").html();

    var curYearOnSetUp = $("#currentYearOnSetUp").html();
    var curSemOnSetUp = $("#currentSemOnSetUp").html();

    if(curSemOnSetUp < 3) $("#currentSemOnSetUp").html(++curSemOnSetUp);
    else if(curYearOnSetUp < year) {
        $("#currentYearOnSetUp").html(++curYearOnSetUp);
        $("#currentSemOnSetUp").html(1)
    } else {
        alert("Done na");
    }

    $("#tbodyCoursesOnSetUp").html("");
}


function submitAndSaveFinalCourses() {
    var courseDivision = $("#selSOrT").val();
    var curriculum = $("#selCurYear").val();
    var year = $("#selNoOfYears").val();
    var program = $("#curProgram").val();
    if(courseDivision == "Semester") {
        showDialog("Message", "Courses for " + $("#h4NewPrograms").html() + " were already saved.\n " +
            "Please set Trimestral Curriculum for this program.",
            {
                 "OKAY": function() {
                    //alert("will do that");
                     $.ajax({
                        type: "POST",
                         url: "../php/programs/retrieveCourses.php",
                         data: {curriculum: curriculum, courseDivision: courseDivision, program: program},
                         success: function(data) {

                             //window.location.assign("setCoursesForTrimestral");
                             $("#programMainContainer").html($("#coursesForTrimestralMainContainer").html());
                             $("#curriculumOnSetUp").html(curriculum);
                             $("#programOnSetUp").html(program);
                             $("#yearOnSetUp").html(year);
                             $("#tblCoursesForSetUp").html(data);
                             $("#curCourseDivision").val("Trimester");
                             $("#divDialog").dialog("close");
                         },
                         error: function(data) {
                             console.log("error in retrieveing courses " + JSON.stringify(data));
                         }
                     });

                }
            })
    } else {
        showDialog("Message", "Courses for " + $("#h4NewPrograms").html() + " were already saved.\n " +
            "Please set Semestral Curriculum for this program.",
            {
                "OKAY": function() {
                    $.ajax({
                        type: "POST",
                        url: "../php/programs/retrieveCourses.php",
                        data: {curriculum: curriculum, courseDivision: courseDivision, program: $("#curProgram").val()},
                        success: function(data) {
                            //window.location.assign("setCoursesForSemestral");
                            $("#programMainContainer").html($("#coursesForSemestralMainContainer").html());
                            $("#curriculumOnSetUp").html(curriculum);
                            $("#programOnSetUp").html(program);
                            $("#yearOnSetUp").html(year);
                            $("#tblCoursesForSetUp").html(data);
                            $("#curCourseDivision").val("Semester");
                            $("#divDialog").dialog("close");

                        },
                        error: function(data) {
                            console.log("error in retrieveing courses " + JSON.stringify(data));
                        }
                    });
                }
            })
    }
}

function saveCourseToNewCourseDivision(courseCode) {
    var program = $("#programOnSetUp").html();
    var year = $("#currentYearOnSetUp").html();
    var sem = $("#currentSemOnSetUp").html();
    var curriculum = $("#curriculumOnSetUp").html();

    $.ajax({
        type: "POST",
        url: "../php/programs/addCourseToProgram.php",
        data: {courseCode: courseCode, program: program, year: year, sem: sem, curriculum: curriculum},
        success: function(data) {
            var rowData = $("#"+courseCode).html();
            alert("row data = " + rowData);
            alert("data = " +data);
            $("#tbodyCoursesOnSetUp").append("<tr>" +
                "<td>"+$("#courseCode"+courseCode).html()+"</td>" +
                "<td>"+$("#description"+courseCode).html()+"</td>" +
                "<td>"+$("#units"+courseCode).html()+"</td>" +
                "<td>Will display pre-requisites soon</td>" +
                "</tr>");
        },
        error: function(data) {
            console.log("error in adding course to program " + JSON.stringify(data));
        }
    });
}

function saveCourseForNewCourseDivision() {

}

function saveCourse1stYear1st() {
    var code = $("#txtCourse1stYear1st").val();
    var description = $("#txtDescription1stYear1st").val();
    var unit = $("#txtUnit1stYear1st").val();
    var curYear = $("#selCurYear").val();
    var cur = $("#selSOrT").val();

    if(code.trim != "" && description != "" && unit != "") {
        var preqs = "";
        for(var i = 0; i < preReqs.length; i++) {
            if(i != preReqs.length-1) preqs += (preReqs[i]+", ");
            else preqs += (preReqs[i]);
        }
        if(preqs == "") preqs = "none";

        $("#courses1stYear1st").prepend("<tr><td>"+code+"</td><td>"+description+"</td><td>"+unit+"</td><td>"+preqs+"</td></tr>");
        $("#txtCourse1stYear1st").val("");
        $("#txtDescription1stYear1st").val("");
        $("#txtUnit1stYear1st").val("");
        //$("#selPreReq1stYear1st").html("");
        preReqs = new Array();


        $.ajax({
            type: "POST",
            url: "../php/programs/addCourse.php",
            data: {"year": 1, "sem": 1, "curYear": curYear, "cur":cur, "code": code, "description": description, "unit": unit, "preReq": preqs},
            success: function(data) {
                console.log("save " + data);
                var curTotal = $("#totalUnits1st1st").html();
                $("#totalUnits1st1st").html(parseInt(curTotal)+parseInt(unit));
                displayPossiblePrerequisites(curYear, $("#curProgram").val(), cur, 1, 1);
                $("#selPreReq1stYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq1stYear3rd").html($("#possiblePrerequisites").val());
                $("#selPreReq2ndYear1st").html($("#possiblePrerequisites").val());
                $("#selPreReq2ndYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq2ndYear3rd").html($("#possiblePrerequisites").val());
                $("#selPreReq3rdYear1st").html($("#possiblePrerequisites").val());
                $("#selPreReq3rdYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq3rdYear3rd").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear1st").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear3rd").html($("#possiblePrerequisites").val());
                console.log("poss val = " + $("#possiblePrerequisites").val());

            }
        })
    }
}


function showDialog(title, message, buttons) {
    $("#divDialog").html(message);
    $("#divDialog").dialog({
        title: title,
        show: {effect: "slide", direction: "up"},
        hide: {effect: "slide", direction: "up"},
        modal: true,
        resizable: false,
        draggable: false,
        buttons: buttons
    });
}

function selPreReq1stYear1st() {
    var preR = $("#selPreReq1stYear1st").val();
    if(preReqs.indexOf((preR)) == -1) {
        preReqs.push(preR);
        $("#pPreReqs1stYear1st").append(preR + "<br />");
    }
}

function saveCourse1stYear2nd() {
    var code = $("#txtCourse1stYear2nd").val();
    var description = $("#txtDescription1stYear2nd").val();
    var unit = $("#txtUnit1stYear2nd").val();
    var curYear = $("#selCurYear").val();
    var cur = $("#selSOrT").val();

    if(code.trim != "" && description != "" && unit != "") {
        var preqs = "";
        for(var i = 0; i < preReqs.length; i++) {
            if(i != preReqs.length-1) preqs += (preReqs[i]+", ");
            else preqs += (preReqs[i]);
        }
        if(preqs == "") preqs = "none";

        $("#courses1stYear2nd").prepend("<tr><td>"+code+"</td><td>"+description+"</td><td>"+unit+"</td><td>"+preqs+"</td></tr>");
        $("#txtCourse1stYear2nd").val("");
        $("#txtDescription1stYear2nd").val("");
        $("#txtUnit1stYear2nd").val("");
        //$("#selPreReq1stYear2nd").html("");
        preReqs = new Array();

        $.ajax({
            type: "POST",
            url: "../php/programs/addCourse.php",
            data: {"year": 1, "sem": 2, "curYear": curYear, "cur":cur, "code": code, "description": description, "unit": unit, "preReq": preqs},
            success: function(data) {
                console.log("save " + data);
                var curTotal = $("#totalUnits1st2nd").html();
                $("#totalUnits1st2nd").html(parseInt(curTotal)+parseInt(unit));
                displayPossiblePrerequisites(curYear, $("#curProgram").val(), cur, 1, 2);
                $("#selPreReq1stYear3rd").html($("#possiblePrerequisites").val());
                $("#selPreReq2ndYear1st").html($("#possiblePrerequisites").val());
                $("#selPreReq2ndYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq2ndYear3rd").html($("#possiblePrerequisites").val());
                $("#selPreReq3rdYear1st").html($("#possiblePrerequisites").val());
                $("#selPreReq3rdYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq3rdYear3rd").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear1st").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear3rd").html($("#possiblePrerequisites").val());
            }
        })
    }
}

function selPreReq1stYear2nd() {
    var preR = $("#selPreReq1stYear2nd").val();
    if(preReqs.indexOf((preR)) == -1) {
        preReqs.push(preR);
        $("#pPreReqs1stYear2nd").append(preR + "<br />");
    }
}

function saveCourse1stYear3rd() {
    var code = $("#txtCourse1stYear3rd").val();
    var description = $("#txtDescription1stYear3rd").val();
    var unit = $("#txtUnit1stYear3rd").val();
    var curYear = $("#selCurYear").val();
    var cur = $("#selSOrT").val();

    if(code.trim != "" && description != "" && unit != "") {
        var preqs = "";
        for(var i = 0; i < preReqs.length; i++) {
            if(i != preReqs.length-1) preqs += (preReqs[i]+", ");
            else preqs += (preReqs[i]);
        }
        if(preqs == "") preqs = "none";

        $("#courses1stYear3rd").prepend("<tr><td>"+code+"</td><td>"+description+"</td><td>"+unit+"</td><td>"+preqs+"</td></tr>");
        $("#txtCourse1stYear3rd").val("");
        $("#txtDescription1stYear3rd").val("");
        $("#txtUnit1stYear3rd").val("");
        $("#selPreReq1stYear3rd").html("");
        preReqs = new Array();

        $.ajax({
            type: "POST",
            url: "../php/programs/addCourse.php",
            data: {"year": 1, "sem": 3, "curYear": curYear, "cur":cur, "code": code, "description": description, "unit": unit, "preReq": preqs},
            success: function(data) {
                console.log("save " + data);
                var curTotal = $("#totalUnits1st3rd").html();
                $("#totalUnits1st3rd").html(parseInt(curTotal)+parseInt(unit));
                displayPossiblePrerequisites(curYear, $("#curProgram").val(), cur, 1, 3);
                $("#selPreReq2ndYear1st").html($("#possiblePrerequisites").val());
                $("#selPreReq2ndYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq2ndYear3rd").html($("#possiblePrerequisites").val());
                $("#selPreReq3rdYear1st").html($("#possiblePrerequisites").val());
                $("#selPreReq3rdYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq3rdYear3rd").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear1st").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear3rd").html($("#possiblePrerequisites").val());
            }
        })
    }
}

function selPreReq1stYear3rd() {
    var preR = $("#selPreReq1stYear3rd").val();
    if(preReqs.indexOf((preR)) == -1) {
        preReqs.push(preR);
        $("#pPreReqs1stYear3rd").append(preR + "<br />");
    }
}

function saveCourse2ndYear1st() {
    var code = $("#txtCourse2ndYear1st").val();
    var description = $("#txtDescription2ndYear1st").val();
    var unit = $("#txtUnit2ndYear1st").val();
    var curYear = $("#selCurYear").val();
    var cur = $("#selSOrT").val();

    if(code.trim != "" && description != "" && unit != "") {
        var preqs = "";
        for(var i = 0; i < preReqs.length; i++) {
            if(i != preReqs.length-1) preqs += (preReqs[i]+", ");
            else preqs += (preReqs[i]);
        }
        if(preqs == "") preqs = "none";

        $("#courses2ndYear1st").prepend("<tr><td>"+code+"</td><td>"+description+"</td><td>"+unit+"</td><td>"+preqs+"</td></tr>");
        $("#txtCourse2ndYear1st").val("");
        $("#txtDescription2ndYear1st").val("");
        $("#txtUnit2ndYear1st").val("");
        $("#selPreReq2ndYear1st").html("");
        preReqs = new Array();

        $.ajax({
            type: "POST",
            url: "../php/programs/addCourse.php",
            data: {"year": 2, "sem": 1, "curYear": curYear, "cur":cur, "code": code, "description": description, "unit": unit, "preReq": preqs},
            success: function(data) {
                console.log("save " + data);
                var curTotal = $("#totalUnits2nd1st").html();
                $("#totalUnits2nd1st").html(parseInt(curTotal)+parseInt(unit));
                displayPossiblePrerequisites(curYear, $("#curProgram").val(), cur, 2, 1);
                $("#selPreReq2ndYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq2ndYear3rd").html($("#possiblePrerequisites").val());
                $("#selPreReq3rdYear1st").html($("#possiblePrerequisites").val());
                $("#selPreReq3rdYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq3rdYear3rd").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear1st").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear3rd").html($("#possiblePrerequisites").val());
            }
        })
    }
}

function selPreReq2ndYear1st() {
    var preR = $("#selPreReq2ndYear1st").val();
    if(preReqs.indexOf((preR)) == -1) {
        preReqs.push(preR);
        $("#pPreReqs2ndYear1st").append(preR + "<br />");
    }
}

function saveCourse2ndYear2nd() {
    var code = $("#txtCourse2ndYear2nd").val();
    var description = $("#txtDescription2ndYear2nd").val();
    var unit = $("#txtUnit2ndYear2nd").val();
    var curYear = $("#selCurYear").val();
    var cur = $("#selSOrT").val();

    if(code.trim != "" && description != "" && unit != "") {
        var preqs = "";
        for(var i = 0; i < preReqs.length; i++) {
            if(i != preReqs.length-1) preqs += (preReqs[i]+", ");
            else preqs += (preReqs[i]);
        }
        if(preqs == "") preqs = "none";

        $("#courses2ndYear2nd").prepend("<tr><td>"+code+"</td><td>"+description+"</td><td>"+unit+"</td><td>"+preqs+"</td></tr>");
        $("#txtCourse2ndYear2nd").val("");
        $("#txtDescription2ndYear2nd").val("");
        $("#txtUnit2ndYear2nd").val("");
        $("#selPreReq2ndYear2nd").html("");
        preReqs = new Array();

        $.ajax({
            type: "POST",
            url: "../php/programs/addCourse.php",
            data: {"year": 2, "sem": 2, "curYear": curYear, "cur":cur, "code": code, "description": description, "unit": unit, "preReq": preqs},
            success: function(data) {
                console.log("save " + data);
                var curTotal = $("#totalUnits2nd2nd").html();
                $("#totalUnits2nd2nd").html(parseInt(curTotal)+parseInt(unit));
                displayPossiblePrerequisites(curYear, $("#curProgram").val(), cur, 2, 2);
                $("#selPreReq2ndYear3rd").html($("#possiblePrerequisites").val());
                $("#selPreReq3rdYear1st").html($("#possiblePrerequisites").val());
                $("#selPreReq3rdYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq3rdYear3rd").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear1st").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear3rd").html($("#possiblePrerequisites").val());
            }
        })
    }
}

function selPreReq2ndYear2nd() {
    var preR = $("#selPreReq2ndYear2nd").val();
    if(preReqs.indexOf((preR)) == -1) {
        preReqs.push(preR);
        $("#pPreReqs2ndYear2nd").append(preR + "<br />");
    }
}

function saveCourse2ndYear3rd() {
    var code = $("#txtCourse2ndYear3rd").val();
    var description = $("#txtDescription2ndYear3rd").val();
    var unit = $("#txtUnit2ndYear3rd").val();
    var curYear = $("#selCurYear").val();
    var cur = $("#selSOrT").val();

    if(code.trim != "" && description != "" && unit != "") {
        var preqs = "";
        for(var i = 0; i < preReqs.length; i++) {
            if(i != preReqs.length-1) preqs += (preReqs[i]+", ");
            else preqs += (preReqs[i]);
        }
        if(preqs == "") preqs = "none";

        $("#courses2ndYear3rd").prepend("<tr><td>"+code+"</td><td>"+description+"</td><td>"+unit+"</td><td>"+preqs+"</td></tr>");
        $("#txtCourse2ndYear3rd").val("");
        $("#txtDescription2ndYear3rd").val("");
        $("#txtUnit2ndYear3rd").val("");
        $("#selPreReq2ndYear3rd").html("");
        preReqs = new Array();

        $.ajax({
            type: "POST",
            url: "../php/programs/addCourse.php",
            data: {"year": 2, "sem": 3, "curYear": curYear, "cur":cur, "code": code, "description": description, "unit": unit, "preReq": preqs},
            success: function(data) {
                console.log("save " + data);
                var curTotal = $("#totalUnits2nd3rd").html();
                $("#totalUnits2nd3rd").html(parseInt(curTotal)+parseInt(unit));
                displayPossiblePrerequisites(curYear, $("#curProgram").val(), cur, 2, 3);
                $("#selPreReq3rdYear1st").html($("#possiblePrerequisites").val());
                $("#selPreReq3rdYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq3rdYear3rd").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear1st").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear3rd").html($("#possiblePrerequisites").val());
            }
        })
    }
}

function selPreReq2ndYear3rd() {
    var preR = $("#selPreReq2ndYear3rd").val();
    if(preReqs.indexOf((preR)) == -1) {
        preReqs.push(preR);
        $("#pPreReqs2ndYear3rd").append(preR + "<br />");
    }
}

function saveCourse3rdYear1st() {
    var code = $("#txtCourse3rdYear1st").val();
    var description = $("#txtDescription3rdYear1st").val();
    var unit = $("#txtUnit3rdYear1st").val();
    var curYear = $("#selCurYear").val();
    var cur = $("#selSOrT").val();

    if(code.trim != "" && description != "" && unit != "") {
        var preqs = "";
        for(var i = 0; i < preReqs.length; i++) {
            if(i != preReqs.length-1) preqs += (preReqs[i]+", ");
            else preqs += (preReqs[i]);
        }
        if(preqs == "") preqs = "none";

        $("#courses3rdYear1st").prepend("<tr><td>"+code+"</td><td>"+description+"</td><td>"+unit+"</td><td>"+preqs+"</td></tr>");
        $("#txtCourse3rdYear1st").val("");
        $("#txtDescription3rdYear1st").val("");
        $("#txtUnit3rdYear1st").val("");
        $("#selPreReq3rdYear1st").html("");
        preReqs = new Array();

        $.ajax({
            type: "POST",
            url: "../php/programs/addCourse.php",
            data: {"year": 3, "sem": 1, "curYear": curYear, "cur":cur, "code": code, "description": description, "unit": unit, "preReq": preqs},
            success: function(data) {
                console.log("save " + data);
                var curTotal = $("#totalUnits3rd1st").html();
                $("#totalUnits3rd1st").html(parseInt(curTotal)+parseInt(unit));
                displayPossiblePrerequisites(curYear, $("#curProgram").val(), cur, 3, 1);
                $("#selPreReq3rdYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq3rdYear3rd").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear1st").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear3rd").html($("#possiblePrerequisites").val());
            }
        })
    }
}

function selPreReq3rdYear1st() {
    var preR = $("#selPreReq3rdYear1st").val();
    if(preReqs.indexOf((preR)) == -1) {
        preReqs.push(preR);
        $("#pPreReqs3rdYear1st").append(preR + "<br />");
    }
}

function saveCourse3rdYear2nd() {
    var code = $("#txtCourse3rdYear2nd").val();
    var description = $("#txtDescription3rdYear2nd").val();
    var unit = $("#txtUnit3rdYear2nd").val();
    var curYear = $("#selCurYear").val();
    var cur = $("#selSOrT").val();

    if(code.trim != "" && description != "" && unit != "") {
        var preqs = "";
        for(var i = 0; i < preReqs.length; i++) {
            if(i != preReqs.length-1) preqs += (preReqs[i]+", ");
            else preqs += (preReqs[i]);
        }
        if(preqs == "") preqs = "none";

        $("#courses3rdYear2nd").prepend("<tr><td>"+code+"</td><td>"+description+"</td><td>"+unit+"</td><td>"+preqs+"</td></tr>");
        $("#txtCourse3rdYear2nd").val("");
        $("#txtDescription3rdYear2nd").val("");
        $("#txtUnit3rdYear2nd").val("");
        $("#selPreReq3rdYear2nd").html("");
        preReqs = new Array();

        $.ajax({
            type: "POST",
            url: "../php/programs/addCourse.php",
            data: {"year": 3, "sem": 2, "curYear": curYear, "cur":cur, "code": code, "description": description, "unit": unit, "preReq": preqs},
            success: function(data) {
                console.log("save " + data);
                var curTotal = $("#totalUnits3rd2nd").html();
                $("#totalUnits3rd2nd").html(parseInt(curTotal)+parseInt(unit));
                displayPossiblePrerequisites(curYear, $("#curProgram").val(), cur, 3, 2);
                $("#selPreReq3rdYear3rd").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear1st").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear3rd").html($("#possiblePrerequisites").val());
            }
        })
    }
}

function selPreReq3rdYear2nd() {
    var preR = $("#selPreReq3rdYear2nd").val();
    if(preReqs.indexOf((preR)) == -1) {
        preReqs.push(preR);
        $("#pPreReqs3rdYear2nd").append(preR + "<br />");
    }
}

function saveCourse3rdYear3rd() {
    var code = $("#txtCourse3rdYear3rd").val();
    var description = $("#txtDescription3rdYear3rd").val();
    var unit = $("#txtUnit3rdYear3rd").val();
    var curYear = $("#selCurYear").val();
    var cur = $("#selSOrT").val();

    if(code.trim != "" && description != "" && unit != "") {
        var preqs = "";
        for(var i = 0; i < preReqs.length; i++) {
            if(i != preReqs.length-1) preqs += (preReqs[i]+", ");
            else preqs += (preReqs[i]);
        }
        if(preqs == "") preqs = "none";

        $("#courses3rdYear3rd").prepend("<tr><td>"+code+"</td><td>"+description+"</td><td>"+unit+"</td><td>"+preqs+"</td></tr>");
        $("#txtCourse3rdYear3rd").val("");
        $("#txtDescription3rdYear3rd").val("");
        $("#txtUnit3rdYear3rd").val("");
        $("#selPreReq3rdYear3rd").html("");
        preReqs = new Array();

        $.ajax({
            type: "POST",
            url: "../php/programs/addCourse.php",
            data: {"year": 3, "sem": 3, "curYear": curYear, "cur":cur, "code": code, "description": description, "unit": unit, "preReq": preqs},
            success: function(data) {
                console.log("save " + data);
                var curTotal = $("#totalUnits3rd3rd").html();
                $("#totalUnits3rd3rd").html(parseInt(curTotal)+parseInt(unit));
                displayPossiblePrerequisites(curYear, $("#curProgram").val(), cur, 3, 3);
                $("#selPreReq4thYear1st").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear3rd").html($("#possiblePrerequisites").val());
            }
        })
    }
}

function selPreReq3rdYear3rd() {
    var preR = $("#selPreReq3rdYear3rd").val();
    if(preReqs.indexOf((preR)) == -1) {
        preReqs.push(preR);
        $("#pPreReqs3rdYear3rd").append(preR + "<br />");
    }
}

function saveCourse4thYear1st() {
    var code = $("#txtCourse4thYear1st").val();
    var description = $("#txtDescription4thYear1st").val();
    var unit = $("#txtUnit4thYear1st").val();
    var curYear = $("#selCurYear").val();
    var cur = $("#selSOrT").val();

    if(code.trim != "" && description != "" && unit != "") {
        var preqs = "";
        for(var i = 0; i < preReqs.length; i++) {
            if(i != preReqs.length-1) preqs += (preReqs[i]+", ");
            else preqs += (preReqs[i]);
        }
        if(preqs == "") preqs = "none";

        $("#courses4thYear1st").prepend("<tr><td>"+code+"</td><td>"+description+"</td><td>"+unit+"</td><td>"+preqs+"</td></tr>");
        $("#txtCourse4thYear1st").val("");
        $("#txtDescription4thYear1st").val("");
        $("#txtUnit4thYear1st").val("");
        $("#selPreReq4thYear1st").html("");
        preReqs = new Array();

        $.ajax({
            type: "POST",
            url: "../php/programs/addCourse.php",
            data: {"year": 4, "sem": 1, "curYear": curYear, "cur":cur, "code": code, "description": description, "unit": unit, "preReq": preqs},
            success: function(data) {
                console.log("save " + data);
                var curTotal = $("#totalUnits4th1st").html();
                $("#totalUnits4th1st").html(parseInt(curTotal)+parseInt(unit));
                displayPossiblePrerequisites(curYear, $("#curProgram").val(), cur, 4, 1);
                $("#selPreReq4thYear2nd").html($("#possiblePrerequisites").val());
                $("#selPreReq4thYear3rd").html($("#possiblePrerequisites").val());
            }
        })
    }
}

function selPreReq4thYear1st() {
    var preR = $("#selPreReq4thYear1st").val();
    if(preReqs.indexOf((preR)) == -1) {
        preReqs.push(preR);
        $("#pPreReqs4thYear1st").append(preR + "<br />");
    }
}

function saveCourse4thYear2nd() {
    var code = $("#txtCourse4thYear2nd").val();
    var description = $("#txtDescription4thYear2nd").val();
    var unit = $("#txtUnit4thYear2nd").val();
    var curYear = $("#selCurYear").val();
    var cur = $("#selSOrT").val();

    if(code.trim != "" && description != "" && unit != "") {
        var preqs = "";
        for(var i = 0; i < preReqs.length; i++) {
            if(i != preReqs.length-1) preqs += (preReqs[i]+", ");
            else preqs += (preReqs[i]);
        }
        if(preqs == "") preqs = "none";

        $("#courses4thYear2nd").prepend("<tr><td>"+code+"</td><td>"+description+"</td><td>"+unit+"</td><td>"+preqs+"</td></tr>");
        $("#txtCourse4thYear2nd").val("");
        $("#txtDescription4thYear2nd").val("");
        $("#txtUnit4thYear2nd").val("");
        $("#selPreReq4thYear2nd").html("");
        preReqs = new Array();

        $.ajax({
            type: "POST",
            url: "../php/programs/addCourse.php",
            data: {"year": 4, "sem": 2, "curYear": curYear, "cur":cur, "code": code, "description": description, "unit": unit, "preReq": preqs},
            success: function(data) {
                console.log("save " + data);
                var curTotal = $("#totalUnits4th2nd").html();
                $("#totalUnits4th2nd").html(parseInt(curTotal)+parseInt(unit));
                displayPossiblePrerequisites(curYear, $("#curProgram").val(), cur, 4, 2);
                $("#selPreReq4thYear3rd").html($("#possiblePrerequisites").val());
            }
        })
    }
}

function selPreReq4thYear2nd() {
    var preR = $("#selPreReq4thYear2nd").val();
    if(preReqs.indexOf((preR)) == -1) {
        preReqs.push(preR);
        $("#pPreReqs4thYear2nd").append(preR + "<br />");
    }
}

function saveCourse4thYear3rd() {
    var code = $("#txtCourse4thYear3rd").val();
    var description = $("#txtDescription4thYear3rd").val();
    var unit = $("#txtUnit4thYear3rd").val();
    var curYear = $("#selCurYear").val();
    var cur = $("#selSOrT").val();

    if(code.trim != "" && description != "" && unit != "") {
        var preqs = "";
        for(var i = 0; i < preReqs.length; i++) {
            if(i != preReqs.length-1) preqs += (preReqs[i]+", ");
            else preqs += (preReqs[i]);
        }
        if(preqs == "") preqs = "none";

        $("#courses4thYear3rd").prepend("<tr><td>"+code+"</td><td>"+description+"</td><td>"+unit+"</td><td>"+preqs+"</td></tr>");
        $("#txtCourse4thYear3rd").val("");
        $("#txtDescription4thYear3rd").val("");
        $("#txtUnit4thYear3rd").val("");
        $("#selPreReq4thYear3rd").html("");
        preReqs = new Array();

        $.ajax({
            type: "POST",
            url: "../php/programs/addCourse.php",
            data: {"year": 4, "sem": 3, "curYear": curYear, "cur":cur, "code": code, "description": description, "unit": unit, "preReq": preqs},
            success: function(data) {
                console.log("save " + data);
                var curTotal = $("#totalUnits4th3rd").html();
                $("#totalUnits4th3rd").html(parseInt(curTotal)+parseInt(unit));
            }
        })
    }
}

function selPreReq4thYear3rd() {
    var preR = $("#selPreReq4thYear3rd").val();
    if(preReqs.indexOf((preR)) == -1) {
        preReqs.push(preR);
        $("#pPreReqs4thYear3rd").append(preR + "<br />");
    }
}


function changeCoursesDivision() {
    var cur = $("#selSOrT").val();
    var year = $("#selNoOfYears").val();

    if(cur == "Semester") {
        $("#tblForCourseEncoding").show();

        $(".spanCur").html(cur);

        $("#courses1stYear3rd").hide();
        $("#1stYear3rdBody").hide();

        $("#2ndYear3rdtbody").hide();
        $("#courses2ndYear3rd").hide();

        $("#3rdYear3rdtbody").hide();
        $("#courses3rdYear3rd").hide();

        $("#4thYear3rdtbody").hide();
        $("#courses4thYear3rd").hide();

    } else if(cur == "Trimester") {
        $("#tblForCourseEncoding").show();

        $(".spanCur").html(cur);

        $("#courses1stYear3rd").show();
        $("#1stYear3rdBody").show();

        $("#2ndYear3rdtbody").show();
        $("#courses2ndYear3rd").show();

        if(year == 3) {
            $("#3rdYear3rdtbody").show();
            $("#courses3rdYear3rd").show();
        } else if(year == 4) {
            $("#4thYear3rdtbody").show();
            $("#courses4thYear3rd").show();
        }
    } else {
        $("#tblForCourseEncoding").hide();
    }
}

function changeNoOfYears() {
    var year = $("#selNoOfYears").val();
    if(year == "4") {
        if($("#selSOrT").val() == "Trimester") {
            $("#3rdYear3rdtbody").show();
            $("#courses3rdYear3rd").show();
            $("#4thYear3rdtbody").show();
            $("#courses4thYear3rd").show();
        } else {
            $("#3rdYear3rdtbody").hide();
            $("#courses3rdYear3rd").hide();
            $("#4thYear3rdtbody").hide();
            $("#courses4thYear3rd").hide();
        }

        $("#tbody3rdYear1st").show();
        $("#courses3rdtYear1st").show();

        $("#tbody3rdYear2nd").show();
        $("#courses3rdYear2nd").show();


        $("#tbody4thYear1st").show();
        $("#courses4thYear1st").show()

        $("#tbody4thYear2nd").show();
        $("#courses4thYear2nd").show();

    } else if(year == "3") {
        if($("#selSOrT").val() == "Trimester") {
            $("#3rdYear3rdtbody").show();
            $("#courses3rdYear3rd").show();
        } else {
            $("#3rdYear3rdtbody").hide();
            $("#courses3rdYear3rd").hide();
        }

        $("#tbody3rdYear1st").show();
        $("#courses3rdtYear1st").show();

        $("#tbody3rdYear2nd").show();
        $("#courses3rdYear2nd").show();

        $("#tbody4thYear1st").hide();
        $("#courses4thYear1st").hide();

        $("#tbody4thYear2nd").hide();
        $("#courses4thYear2nd").hide();

        $("#4thYear3rdtbody").hide();
        $("#courses4thYear3rd").hide();

    } else if(year == "2") {
        if($("#selSOrT").val() == "Trimester") {
            $("#2ndYear3rdtbody").show();
            $("#courses2ndYear3rd").show();
        } else {
            $("#2ndYear3rdtbody").hide();
            $("#courses2ndYear3rd").hide();
        }

        $("#tbody3rdYear1st").hide();
        $("#courses3rdtYear1st").hide();

        $("#tbody3rdYear2nd").hide();
        $("#courses3rdYear2nd").hide();

        $("#3rdYear3rdtbody").hide();
        $("#courses3rdYear3rd").hide();

        $("#tbody4thYear1st").hide();
        $("#courses4thYear1st").hide()

        $("#tbody4thYear2nd").hide();
        $("#courses4thYear2nd").hide();

        $("#4thYear3rdtbody").hide();
        $("#courses4thYear3rd").hide();
    }
};

function addProgram() {
    var code = $("#txtProgramCode").val();
    var description = $("#txtProgramDescription").val();

    if(code.trim() != "" && description.trim() != "") {
        $.ajax({
            type: "POST",
            url: "../php/programs/addProgram.php",
            data: {"code": code, "description": description},
            success: function(data) {
                $("#curProgram").val(code);
                $("#formAddProgram")[0].reset();
                $("#divAddCourses").show();
                $("#h4NewPrograms").html(data);
                alert(code + " successfully added!");
                alert("You may now set courses for " + code + ".");
            }
        });
    }
}


function displayPrograms(curriculum) {
    $.ajax({
        type: "POST",
        url: "../php/programs/displayPrograms.php",
        data: {curriculum: curriculum},
        success: function(data) {
            //alert("curr = " + curriculum);
            if(data != "") {
                $("#divDisplayProgramsContainer").html(data);
                $("#divProgramsMainContainer").html($("#divProgramsSubContainer").html());
                //$("#selCurriculumToDisplay").val(curriculum);
                //alert("display programs " + data);
            } else {
                alert("No data retrieved");
            }
        },
        error: function(data) {
            console.log("error in displaying programs " + JSON.stringify(data));
            alert("error display " + JSON.stringify(data));
        }
    });
}
function setEditProgram(progCode) {
    $("#newProgCode").val(progCode);
    $("#newProgDescription").val($("#prog"+progCode).html());
    $("#prevProgCode").val(progCode);
}

function deleteProgram(code) {
    var cofirm = confirm("Are you sure to delete "+code+" from your program offers?");
    if(cofirm) {
        $.ajax({
            type: "POST",
            url: "../php/programs/deleteProgram.php",
            data: {code: code},
            success: function(data) {
                $("#tr"+code).remove();
            },
            error: function(data) {
                console.log("error in deleting program " + JSON.stringify(data));
            }
        });
    }
}

function setProgramSession(code, program) {
    $.ajax({
        type: "POST",
        url: "../php/programs/setProgramSession.php",
        data: {code: code, program: program},
        error: function(data) {
            console.log("error in setting program session " + JSON.stringify(data));
        }
    });
}

function displayCourses(progCode) {
    $.ajax({
        type: "POST",
        url: "../php/programs/displayCourses.php",
        success: function(data) {

        },
        error: function(data) {
            console.log("error in displaying courses " + JSON.stringify(data));
        }
    });
}

function displayProgramsTrimestral() {
    $.ajax({
        type: "POST",
        url: "../php/programs/displayProgramsTrimestral.php",
        data: {curriculum: $("#selCurriculumTDisplay").val()},
        success: function(data) {
            $("#divDisplayProgramsContainer").html(data);
            $("#divProgramsMainContainer").html($("#divProgramsSubContainer").html());
            //alert("display programs " + data);
        },
        error: function(data) {
            console.log("error in displaying programs " + JSON.stringify(data));
            alert("error displayProgramsTrimestral " + JSON.stringify(data));
        }
    })
}

function addPrograms() {
    $("#divProgramsMainContainer").html($("#divAddProgramContent").html());
}