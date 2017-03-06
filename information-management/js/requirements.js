$(document).ready(function() {
    displayRequirements();
});

function displayRequirements() {
    $.ajax({
        type: "POST",
        url: "../php/requirements/displayRequirements.php",
        success: function(data) {
            $("#tblRequirements").html(data);
        }
    });
}

function addRequirement() {
    var req = $("#txtRequirement").val();
    var forWhom = $("#selFor").val();

    if(req.trim() != "") {
        $.ajax({
            type: "POST",
            url: "../php/requirements/addRequirement.php",
            data: {"req": req, "forWhom": forWhom},
            success: function(data) {
                $("#tblRequirements").html(data);
            }
        });
    }
}

function deleteRequirement(id) {
    var buttons = {
        "YES": function() {
            $.ajax({
                type: "POST",
                url: "../php/requirements/deleteRequirement.php",
                data: {"id":id},
                success: function(data) {
                    $("#tblRequirements").html(data);
                    $("#divDialog").dialog("close");
                }
            });
        },
        "CANCEL": function() {
            $("#divDialog").dialog("close");
        }
    }
    showDialog("You are about to delete the selected requirement. Are you sure to continue?", "Confirmation", buttons);
}

function editRequirement(id) {
    var prevReq = $("#req"+id).html();
    $("#req"+id).html("<form onsubmit='updateRequirement("+id+"); return false;'>"+
                        "<input class='input input-sm' value='"+prevReq+"' id='newReq"+id+"' onblur='updateRequirement("+id+")' />" +
                      "</form>");
    $("#newReq"+id).focus();
}

function updateRequirement(id) {
    var newReq = $("#newReq"+id).val();
    $("#req"+id).html(newReq);
    $.ajax({
        type: "POST",
        url: "../php/requirements/updateRequirement.php",
        data: {"id": id, "newReq":newReq}
    });
}

function editRequirementFor(id) {
    var prevReqFor = $("#reqFor"+id).html();
    $("#reqFor"+id).html("<select class='input input-sm' value='"+prevReqFor+"' id='newReqFor"+id+"' onchange='updateRequirementFor("+id+")' onblur='updateRequirementFor("+id+")'>" +
                        "<option>New Students</option>" +
                        "<option>Transferees</option>" +
                        "<option>Students</option>" +
                      "</select>");
    $("#newReqFor"+id).focus();
}

function updateRequirementFor(id) {
    var newReqFor = $("#newReqFor"+id).val();
    $("#reqFor"+id).html(newReqFor);
    $.ajax({
        type: "POST",
        url: "../php/requirements/updateRequirementFor.php",
        data: {"id": id, "newReqFor":newReqFor}
    });
}

function showDialog(message, title, buttons) {
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