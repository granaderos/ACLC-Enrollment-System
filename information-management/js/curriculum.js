/**
 * Created by Marejean on 10/23/16.
 */

$(document).ready(function() {
    displayCurriculum();
});

function addCurriculum() {
    var name = $("#txtCurriculumName").val();
    var desc = " ";
    desc = $("#txtCurriculumDescription").val();


    if(name.trim() != "") {
        $.ajax({
            type: "POST",
            url: "../php/curriculum/addCurriculum.php",
            data: {"name": name, "desc": desc},
            success: function(data) {
                displayCurriculum();
                showDialog("INFORMATION", "New curriculum added! " + data, {"Okay": function() { $("#divDialog").dialog("close"); }});
            },
            error: function(data) {
                console.log("error adding curriculum " + JSON.stringify(data));
            }
        });
    } else showDialog("ERROR", "Please fill out the fields completely!", {"Okay": function() { $("#divDialog").dialog("close"); }});

}

function displayCurriculum() {
    $.ajax({
        type: "POST",
        url: "../php/curriculum/displayCurriculum.php",
        success: function(data) {
            $("#divDisplayCurriculum").html(data);
        },
        error: function(data) {
            console.log("error displaying curriculum " + JSON.stringify(data));
        }
    });
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