var alphabet = /^[a-z, A-Z, -]*$/;
var numeric = /^[0-9]*$/;

var sem = "Semestral";
var curSem = 1, curYear = 1, coursesCount = 0;;
var preReqs = new Array();
var getPrereq = false;

var progCur="", progSem, progYear;
$(document).ready(function() {
    $("#btnSetProgramDetails").click(function() {
        progCur = $("#selCurYear").val();
        progSem = $("#selSOrT").val();
        progYear = $("#selNoOfYears").val();

        $("#progCur").html(progCur);
        $("#progSem").html(progSem);
        $("#progYear").html(progYear);
        $("#btnProgValue").html("Year: " + curYear + " &nbsp; " + progSem + ": " + curSem)

        $(".modal-backdrop").fadeOut(200);
        $("#setDetails").slideUp(200);

        $("#currentYear").html(1);
        $("#currentSem").html(1);
    });

});


function displayCurriculumOptions() {
    $.ajax({
        type: "POST",
        url: "../../php/curriculum/displayCurriculumOptions.php",
        success: function(data) {
            $("#selCurriculumToDisplay").html(data);
            var curriculum = $("#selCurriculumToDisplay").val();
            if(curriculum != "") {
                displayCourses(curriculum, "Semestral");
            } else alert("No curriculum found;");
        },
        error: function(data) {
            console.log("Error in displaying ccurriculum options " + JSON.stringify(data));
        }
    });
}

function displayCourses(curriculum, sem) {
    $.ajax({
        type: "POST",
        url: "../../php/programs/displayCourses.php",
        data: {curriculum: curriculum, sem: sem},
        success: function(data) {
            $("#divCoursesMainContainer").html(data);
            //$("#spanProg").html("&lt;?php echo $_SESSION['program']; ?&gt;");
        },
        error: function(data) {
            console.log("error in displaying courses " + JSON.stringify(data));
        }
    })
}

function displayProg() {
    var curriculum = $("#selCurriculumToDisplay").val();
    displayCourses(curriculum, sem);
}

function displayCoursesSem() {
    sem = "Semestral";
    var curriculum = $("#selCurriculumToDisplay").val();
    displayCourses(curriculum, sem);
}

function displayCoursesTri() {
    sem = "Trimestral";
    var curriculum = $("#selCurriculumToDisplay").val();
    displayCourses(curriculum, sem);
}


function addTempCourse() {
    if(progCur != "") {
        var code = $("#txtCourseCode").val();
        var description = $("#txtDescription").val();
        var unit = $("#txtUnit").val();
        var labUnit = 0;
        labUnit = $("#txtUnitLab").val();

        if(code.trim() != "" && description.trim() != "" && unit.trim() != "" && numeric.test(unit)) {
            var preqs = "";
            for(var i = 0; i < preReqs.length; i++) {
                if(i != preReqs.length-1) preqs += (preReqs[i]+", ");
                else preqs += (preReqs[i]);
            }
            if(preqs == "") {
                preqs = "none";
                preReqs = "none"
            }

            $.ajax({
                type: "POST",
                url: "../../../php/programs/addTempCourseToProgram.php",
                data: {curYear: curYear, curSem: curSem, progCur: progCur, progYear: progYear, progSem: progSem, code: code,
                        description: description, unit: unit, labUnit: labUnit, preReqs: preReqs},
                success: function(data) {
                    if(data != "") alert(data);
                    else {
                        var curTotal = $("#totalUnits").html();
                        $("#totalUnits").html(parseInt(curTotal)+parseInt(unit));

                        $("#coursesTbody").prepend("<tr><td>"+code+"</td><td>"+description+"</td><td>"+unit+"</td><td>"+preqs+"</td></tr>");
                        $("#txtCourseCode").val("");
                        $("#txtDescription").val("");
                        $("#txtUnit").val("");
                        $("#pPreReqs").html("");
                        preReqs = new Array();}
                        coursesCount++;
                },
                error: function(data) {
                    console.log("error in adding courses to temp " + JSON.stringify(data));
                }
            })
        }
    } else alert("Please set program details first!");
}

function displayPossiblePrerequisites(curriculum, semType, year, sem) {
    alert("called this");
    $.ajax({
        type: "POST",
        url: "../../../php/programs/displayPossiblePrerequisites.php",
        data: {"curriculum": curriculum, "semType": semType, "year": year, "sem": sem},
        success: function(data) {
            $("#selPreReq").html(data);
        },
        error: function(data) {
            console.log("error in displayPossiblePrerequisites " + JSON.stringify(data));
        }
    });
}

function selPreReq() {
    var preR = $("#selPreReq").val();
    if(preReqs.indexOf((preR)) == -1) {
        preReqs.push(preR);
        $("#pPreReqs").append(preR + "<br />");
    }
}

function saveCourses() {
    if(coursesCount == 0) {
        alert("No courses added yet;")
    } else {
        var okay = confirm("Sure to save " + coursesCount + " courses for Year: " + curYear + " - " + sem + ": " + curSem + "?");
        if(okay) {
            $.ajax({
                type: "POST",
                url: "../../../php/programs/saveCourses.php",
                data: {progSem: progSem},
                success: function(data) {
                    $("#tblAddedCourses").append(data);
                    $("#coursesTbody").html("");
                    var maxSem = 3;
                    if(progSem == "Semester") {
                        maxSem = 2;
                    }
                    if(curSem < maxSem) {
                        curSem++;

                        $("#currentSem").html(curSem);
                    } else {
                        curSem = 1;
                        curYear++;
                        if(curYear <= progYear) {
                            $("#btnProgValue").html("Year: " + curYear + " &nbsp; " + progSem + ": " + curSem)

                            $("#currentSem").html(curSem);
                            $("#currentYear").html(curYear);
                        } else {
                            alert("Courses complete!");
                            $("#addingCoursesTbody").remove();
                            $("#addingCoursesHeader").remove();
                        }
                    }
                    $("#totalUnits").html(0);
                    displayPossiblePrerequisites(progCur, progSem, curYear, curSem);
                    //$("#selPreReq").html($("#possiblePrerequisites").val());
                },
                error: function(data) {
                    console.log("error in saving courses " + JSON.stringify(data));
                }
            });
        }

    }
}