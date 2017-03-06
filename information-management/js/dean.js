/**
 * Created with JetBrains PhpStorm.
 * User: root
 * Date: 2/13/17
 * Time: 8:03 PM
 * To change this template use File | Settings | File Templates.
 */

var progCode = "", program = "", curSectionId = "", curCourse = "";
var curYear = 0, curSectionId = 0;
$(document).ready(function() {
    getYear();
    displayProgramsForDean();
    $("#progSectionsDiv").hide();

    $(".makeTooltip").tooltip();
});

function togglePreregistration() {
    displayPreregStatus();
    $("#divDeanMainContainer").html($("#preregContainerDiv").html());
}

function toggleApprovePreregistration() {
    $("#divDeanMainContainer").html($("#approvePreregContainerDiv").html());
}

function approveStudentPrereg() {
    $.ajax({
        type: "POST",
        url: "../php/dean/approveStudentPrereg.php",
        success: function(data) {
            alert("Preregistration successfully approved!");
            getFilteredPreregistrations();
        },
        error: function(data) {
            alert("Mj, error in approveStudentPrereg " + JSON.stringify(data));
        }
    })
}

function showStudentPreregistration(studentId) {
    $.ajax({
        type: "POST",
        url: "../php/dean/showStudentPreregistration.php",
        data: {studentId: studentId},
        success: function(data) {
            var obj = JSON.parse(data);
            $("#studentRegistrationContainer").html(obj.data);
            $("#studentNamePrereg").html(obj.name);
        },
        error: function(data) {
            alert("Mj error! :( showStudentPreregistration " + JSON.stringify(data));
        }
    })
}

function setProgramSessionForApprovePrereg(progCode) {
    $.ajax({
        type: "POST",
        url: "../php/dean/setProgramSessionForApprovePrereg.php",
        data: {progCode: progCode},
        success: function(data) {
            $("#approveDeafultDataContainer").html(data);
            toggleApprovePreregistration();
            $("#approvePreregProgramContainerDiv").slideUp(200);
            $(".modal-backdrop").slideUp(200);

        },
        error: function(data) {
            alert("Mj! :( error in setProgramSessionForApprovePrereg() " + JSON.stringify(data));
        }
    });
}

function getFilteredPreregistrations() {
    var name = "", year = "";
    name = $("#studNameSearch").val();
    year = $("#selYearLevel").val();
    $.ajax({
        type: "POST",
        url: "../php/dean/getFilteredPreregistrations.php",
        data: {name: name, year:  year},
        success: function(data) {
            $("#approveDeafultDataContainer").html(data);
        },
        error: function(data) {
            alert("Mj! :( error in getFilteredPreregistrations() " + JSON.stringify(data));
        }
    });
}

function displayProgramsForApprovePrereg() {
    $.ajax({
        type: "POST",
        url: "../php/dean/displayProgramsForApprovePrereg.php",
        success: function(data) {
            $("#approvePreregProgramContainer").html(data);
        },
        error: function(data) {
            alert("Mj! :( error in displayProgramsForApprovePrereg " + JSON.stringify(data));
        }
    });
}

function displayPreregStatus() {
    $.ajax({
        type: "POST",
        url: "../php/dean/displayPreregStatus.php",
        success: function(data) {
            var obj = JSON.parse(data);
            $("#preregStatus").html(obj.status);
            $("#statusOption").html(obj.option);
        },
        error: function(data) {
            alert("error in displauing reregstatus " + JSON.stringify(data));
        }
    });
}

function displayProgramsForDean() {
    $.ajax({
        type: "POST",
        url: "../php/programs/displayProgramsForDean.php",
        success: function(data) {
            $("#deanProgramContainer").html($("#divProgContainer").html(data))
        },
        error: function(data) {
            console.log("error in displaying programs for dean" + JSON.stringify(data));
            alert("error display " + JSON.stringify(data));
        }
    });
}

function updatePreregStatus(curStatus) {
    var toUpdate = 0;
    var ques = "Are you sure to close the pre-registration of students?";
    if(curStatus == 0) {
        ques = "Are you sure to open the pre-registration of students?";
        toUpdate = 1;
    }
    var okay = confirm(ques);
    if(okay) {
        $.ajax({
            type: "POST",
            url: "../php/dean/updatePreregStatus.php",
            data: {toUpdate: toUpdate},
            success: function() {
                displayPreregStatus();
                alert("Status successfully updated!");
            },
            error: function(data) {
                alert("error in updatePreregStatus " + JSON.stringify(data));
            }
        });
    } else alert("Alright; cancelled.");
}

function displaySectionDiv(progId, prog) {
    $("#divDeanMainContainer").html($("#progSectionsDiv").html())
    $("#deanProg").html(prog);
    progCode = progId;
    program = prog;
    displaySections();
    getYear();
}

function addSection() {
    var sectionCode = $("#sectionCode").val();
    var sy = $("#sectionSY").val();
    var year = $("#sectionYear").val();
    var sem = $("#sectionSem").val();
    var type = $("#sectionType").val();

    if(sectionCode.trim().length > 1) {
        $.ajax({
            type: "POST",
            url: "../php/dean/addSection.php",
            data: {sectionCode: sectionCode, sy: sy, year: year, sem: sem, progCode: progCode, type: type},
            success: function(data) {
                if(data == "exist") {
                    alert("Section code " + sectionCode + " already exist!");
                } else {
                    $("#addSectionDiv").toggle();
                    $(".modal-backdrop").hide();
                    alert("Successfully added section " + sectionCode + " for " + progCode + ".");
                    displaySections();
                }
            },
            error: function(data) {
                console.log("Error in adding section " + JSON.stringify(data));
            }

        });
    } else alert("Please check your inputs.");
}

function assignProfessor(sectionId, courseCode) {
    curSectionId = sectionId;
    curCourse = courseCode;
    $("#assignProfessorCourse").html(courseCode);

    $.ajax({
        type: "POST",
        url: "../php/dean/retrieveProfsAndSched.php",
        data: {sectionId: sectionId, courseCode: courseCode},
        success: function(data) {
            var obj = JSON.parse(data);
            $("#divAssignProfSched").html(obj.sched);
            $("#selAssignProf").html(obj.profs);
        },
        error: function(data) {
            alert("assignProfessor error " + JSON.stringify(data));
        }
    });
}

function assignProf() {
    var prof = $("#selAssignProf").val();
    $.ajax({
        type: "POST",
        url: "../php/dean/assignProf.php",
        data: {prof: prof, sectionId: curSectionId, courseCode: curCourse},
        success: function(data) {
            if(data == "conflict") {
                alert("Conflict detected! Can't assign schedule to this professor;");
            } else {
                alert(data);

            }
        },
        error: function(data) {
            alert("Error in assignnign faculty Mj " + JSON.stringify(data));
        }
    });
}

function displaySYs() {
    var year = curYear-1;
    var sy = "";
    for(var i = 0; i < 10; i++) {
        sy += ("<option>"+year+"-"+(year+1)+"</option>");
        $("#sectionSY").append("<option>"+year+"-"+(year+1)+"</option>")

        year++;
    }
    $("#sectionSY").html(sy);
}

function getYear() {
    $.ajax({
        type: "POST",
        url: "../php/misc/getYear.php",
        success: function(data) {
            curYear = data;
            console.log("d = " + data);
            displaySYs();

        },
        error: function(data) {
            console.log("error in getting cur year " + JSON.stringify(data));
        }
    })
}

function displaySections() {
    $.ajax({
        type: "POST",
        url: "../php/dean/displaySections.php",
        data: {progCode: progCode},
        success: function(data) {
            $("#displaySectionsDiv").html(data);
        },
        error: function(data) {
            alert("In displaying sections " + JSON.stringify(data));
        }
    });
}

function openSection(sectionCode, sy, sem) {
    var okay = confirm("Open " + sectionCode + "? Students can view this section and they may register.");
    if(okay) {
        $.ajax({
            type: "POST",
            url: "../php/dean/openSection.php",
            data: {sectionCode: sectionCode, sy: sy, sem:sem},
            success: function(data) {
                if(data != "full") {
                    alert("Section opened!");
                    displaySections();
                } else alert("Cannot open section; " + sectionCode + " has reached the maximum enrollees.");
            }
        });
    }
}

function closeSection(sectionCode, sy, sem) {
    var okay = confirm("Close " + sectionCode + "? Students won't be able to view this anymore.");
    if(okay) {
        $.ajax({
            type: "POST",
            url: "../php/dean/closeSection.php",
            data: {sectionCode: sectionCode, sy: sy, sem:sem},
            success: function() {
                    alert("Section closed!");
                    displaySections();
            }
        });
    }
}

function viewSchedule(sectionId, sectionCode, sy, sem) {
    $.ajax({
        type: "POST",
        url: "../php/dean/viewSchedule.php",
        data: {sectionId: sectionId, sectionCode: sectionCode, sy: sy, sem: sem, progCode: progCode},
        success: function(data) {
            curSectionId = sectionId;


            retrieveCoursesForSchedule();
            $("#viewScheduleSection").html(sectionCode);
            $("#scheduleContainer").html(data);


            $("#divDeanMainContainer").html($("#divViewScheduleContainer").html());
        },
        error: function(data) {
            alert("error in viewing schedule " + JSON.stringify(data));
        }

    });
}

function retrieveCoursesForSchedule() {
    $.ajax({
        type: "POST",
        url: "../php/dean/retrieveCoursesForSchedule.php",
        data: {sectionId: curSectionId},
        success: function(data) {
            $("#divCoursesToSetUpSched").html(data);
        } ,
       error: function(data) {
           alert("error in retrieveCoursesForSchedule " + JSON.stringify(data));
       }
    });
}

function viewSectionSchedule(sectionId, course) {
    $.ajax({
        type: "POST",
        url: "../php/dean/viewCourseSchedule.php",
        data: {sectionId: sectionId, course: course},
        success: function(data) {
            $("#tableTempSched").html(data);
        },
        error: function(data) {
            console.log("error in viewSectionSchedule " + JSON.stringify(data));
        }
    });
}

function deleteCourseSchedule(schedId) {
    $.ajax({
        type: "POST",
        url: "../php/dean/deleteCourseSchedule.php",
        data: {schedId: schedId},
        success: function(data) {
            $("#courseSched"+schedId).remove();
            alert("Schedule deleted! " + data)
        },
        error: function(data) {
            console.log("error in deleteCourseSchedule " + JSON.stringify(data));
        }
    });
}

function setCourseSectionSched(sectionId, courseCode) {
    curSectionId = sectionId;
    curCourse = courseCode;
        $("#setScheduleSection").html(curCourse);
    viewSectionSchedule(curSectionId, curCourse);
}

function addTempSched() {
    var day = $("#schedDay").val();
    var startTime = getTimeValue($("#schedStartTime").val());
    var endTime = getTimeValue($("#schedEndTime").val());
    var room = $("#schedRoom").val();

    $.ajax({
        type: "POST",
        url: "../php/dean/addSchedule.php",
        data: {sectionId: curSectionId, course: curCourse, day: day, startTime: startTime, endTime: endTime, room: room},
        success: function(data) {
            if(data == "exist") alert("Schedule already exist!");
            else if(data == " conflict") alert("Schedule already conflicts with other schedule!");
            else $("#tableTempSched").append(data);
        },
        error: function(data) {
            alert("error in addind sched " + JSON.stringify(data));
        }
    });

}

/*var tempSchedCount = 0;
function addTempSched(){
    var day = getDayName($("#schedDay").val());
    var startTime = getTimeValue($("#schedStartTime").val());
    var endTime = getTimeValue($("#schedEndTime").val());

    var tblSched = document.getElementById("tableTempSched");
    var tblRows = tblSched.getElementsByTagName("tr");
    var counter = 0;
    var size = tblRows.length;
    var notDup = 1;
    var output = "<td>"+day+"</td><td>"+startTime+"</td><td class=\"text-center\">-</td><td>"+endTime+"</td>";
    var prevOut = "";
    var innerVal = "";
    while(counter < tblRows.length-1) {
        alert("size = " + size + "\nlength = " + (tblRows.length-1) + "\ncounter = " + counter);
        innerVal = $(tblRows[counter]).html();
        alert("c now = " + counter);

        if(innerVal == "" || innerVal == null) {
            alert("innerval empty");
        } else {
            startTime = parseInt(getCalculableTime(startTime));
            endTime = parseInt(getCalculableTime(endTime));
            //var innerVal = document.getElementById("tempSched"+tblRows[counter].id).innerHTML;
            //var innerVal = $("#tempSched"+(tblRows[counter].id)).html();
            var td = tblRows[counter].getElementsByTagName("td");
            var prevD = $(td[0]).html();

            var prevS = parseInt(getCalculableTime($(td[1]).html()));
            var prevE = parseInt(getCalculableTime($(td[3]).html()));

            prevOut += $(tblRows[counter]).html();

            if(prevD == day) {
                if((prevS == startTime) || (startTime >= prevS && startTime < prevE)) {
                    alert("Conflicting schedule! Please check inputs.");
                    notDup = 0;
                    break;
                }
            } else if(output == innerVal) {
                alert("Schedule duplicate!");
                notDup = 0;
                break;
            }
        }
        counter++;
    }
    if(size <= 0 || notDup == 1) {
        if(prevOut != "") {
            $("#tableTempSched").html(prevOut + "<tr id='tempSched"+tempSchedCount+"'>"+output+"<tr>");
        } else {
            $("#tableTempSched").append("<tr id='tempSched"+tempSchedCount+"'>"+output+"<tr>");
        }
        tempSchedCount++;
    }
}*/

function getDayName(value) {
    var days = 0;
}

function displayEndTime() {
    var opts = ["<option value=1>07:00 AM</option>",
                "<option value=2>07:30 AM</option>",
                "<option value=3>08:00 AM</option>",
                "<option value=4>08:00 AM</option>",
                "<option value=5>08:30 AM</option>",
                "<option value=6>09:00 AM</option>",
                "<option value=7>09:30 AM</option>",
                "<option value=8>10:00 AM</option>",
                "<option value=9>10:30 AM</option>",
                "<option value=10>11:00 AM</option>",
                "<option value=11>11:30 AM</option>",
                "<option value=12>12:00 PM</option>",
                "<option value=13>12:30 PM</option>",
                "<option value=14>01:00 PM</option>",
                "<option value=15>01:30 PM</option>",
                "<option value=16>02:00 PM</option>",
                "<option value=17>02:30 PM</option>",
                "<option value=18>03:00 PM</option>",
                "<option value=19>03:30 PM</option>",
                "<option value=20>04:00 PM</option>",
                "<option value=21>04:30 PM</option>",
                "<option value=22>05:00 PM</option>",
                "<option value=23>05:30 PM</option>",
                "<option value=24>06:00 PM</option>",
                "<option value=25>06:30 PM</option>",
                "<option value=26>07:00 PM</option>",
                "<option value=27>07:30 PM</option>",
                "<option value=28>08:00 PM</option>",
                "<option value=29>08:30 PM</option>",
                "<option value=30>09:00 PM</option>",
                "<option value=31>09:30 PM</option>"];
    var s = parseInt($("#schedStartTime").val());
    var output = "";
    for(var i = s; i < 31; i++) {
        output += opts[i];
    }
    $("#schedEndTime").html(output);
}

function getTimeValue(value) {
    var opts = ["07:00 AM",
                "07:30 AM",
                "08:00 AM",
                "08:00 AM",
                "08:30 AM",
                "09:00 AM",
                "09:30 AM",
                "10:00 AM",
                "10:30 AM",
                "11:00 AM",
                "11:30 AM",
                "12:00 PM",
                "12:30 PM",
                "01:00 PM",
                "01:30 PM",
                "02:00 PM",
                "02:30 PM",
                "03:00 PM",
                "03:30 PM",
                "04:00 PM",
                "04:30 PM",
                "05:00 PM",
                "05:30 PM",
                "06:00 PM",
                "06:30 PM",
                "07:00 PM",
                "07:30 PM",
                "08:00 PM",
                "08:30 PM",
                "09:00 PM",
                "09:30 PM"];

    return opts[value-1];
}

function getCalculableTime(timeVal) {
    var ap = timeVal.substr(timeVal.indexOf(" ")+1, 2);
    var hour = timeVal.substr(0, 2);
    var half = timeVal.substr(timeVal.indexOf(":")+1, 2);
    if(half == "30") hour = parseInt(hour) + .50;
    if(ap == "PM") hour = parseInt(hour) + 12;

    return hour;
}

function getDayName(day) {
    var d = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
    return d[day-1];
}