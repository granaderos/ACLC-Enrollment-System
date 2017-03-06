/**
 * Created with JetBrains PhpStorm.
 * User: root
 * Date: 1/23/17
 * Time: 9:39 PM
 * To change this template use File | Settings | File Templates.
 */

$(document).ready(function() {

   $("#employeeLogin").click(function() {
      var username = $("#userName").val();
       var password = $("#password").val();
       if(username.trim() != "" && password.trim() != "") {
           $.ajax({
               type: "POST",
               url: "../php/admin/loginStaff.php",
               data: {username: username, password: password},
               success: function(data) {
                   if(data != "invalid") {
                       if(data.toUpperCase() == "INSTRUCTOR")
                           window.location.assign("../faculty");
                       else if(data.toUpperCase() == "DEAN")
                           window.location.assign("../dean");
                       else if(data.toUpperCase() == "CASHIER")
                           window.location.assign("../cashier");
                       else if(data.toUpperCase() == "REGISTRAR")
                           window.location.assign("../registrar");
                       else if(data.toUpperCase() == "HR")
                           window.location.assign("../hr");
                       else if(data.toUpperCase() == "ADMIN")
                           window.location.assign("../admin");
                   } else alert("Invalid Credentials!");
               },
               error: function(data) {
                   console.log("Error in logging in employee " + JSON.stringify(data));
                   alert("Error! " + JSON.stringify(data));
               }
           });
       } else alert("Invalid Credentials!")

       return false;
   });

    $("#btnLoginStudent").click(function() {
        $.ajax({
            type: "POST",
            url: "",
            data: {},
            success: function(data) {

            } ,
            error: function(data) {

            }

        });
    });
});