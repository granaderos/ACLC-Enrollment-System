/**
 * Created with JetBrains PhpStorm.
 * User: root
 * Date: 1/23/17
 * Time: 9:39 PM
 * To change this template use File | Settings | File Templates.
 */

$(document).ready(function() {

});

function login() {
    var studentId = $("#studentNum").val();
    var username = $("#userName").val();
    var password = $("#password").val();

    if(studentId.trim().length > 1 && username.trim().length > 1 && password.trim().length > 1) {
        $.ajax({
            type: "POST",
            url: "../php/login.php",
            data: {studentId: studentId, username: username, password: password},
            success: function(data) {
                if(data == "invalid") {
                    alert("Invalid credentials! " + data);
                } else {
                    window.location.assign("../home");
                }
            },
            error: function(data) {
                console.log("error in logging in " + JSON.stringify(data));
            }
        });
    }
}