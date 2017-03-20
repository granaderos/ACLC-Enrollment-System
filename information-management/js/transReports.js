/**
 * Created with JetBrains PhpStorm.
 * User: root
 * Date: 1/23/17
 * Time: 9:39 PM
 * To change this template use File | Settings | File Templates.
 */
var numeric = /^[0-9, .]*$/;
$(document).ready(function() {
    displayRecentTransaction();

    $.ajax({
        type: "POST",
        url: "../php/teller/displayAllProgramsAsOptions.php",
        success: function(data) {
            if(data != "none")
                $("#miscFor").append(data);
        },
        error: function(data) {
            alert("error in displayAllProgramsAsOptions Mj! :( " + JSON.stringify(data));
        }
    });
});

function printTransaction() {
    window.open("print", "Transaction Report", "width=1000,height=800");
}

function displayRecentTransaction() {
    var name = "", from = "", to = "";
    name = $("#studTransReport").val();
    from = $("#displayFrom").val();
    to = $("#displayTo").val();

    $.ajax({
        type: "POST",
        url: "../php/teller/displayTransactions.php",
        data: {name: name, from: from, to: to},
        success: function(data) {
            $("#transactionsContainerDiv").html(data);
        },
        error: function(data) {
            alert("Oh Mj! error in displaying transactions" +  JSON.stringify(data));
        }
    });
}

function displayTransByDate() {
    if($("#displayFrom").val() != "" && $("#displayTo").val())
      displayRecentTransaction()
    else alert("Please specify date range;");
}