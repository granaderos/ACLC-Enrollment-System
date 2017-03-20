/**
 * Created with JetBrains PhpStorm.
 * User: root
 * Date: 3/9/17
 * Time: 2:10 AM
 * To change this template use File | Settings | File Templates.
 */


function generateTOR() {
    var studentId = $("#torStudentId").val();

    if(studentId.trim().length > 1) {
        $.ajax({
            type: "POST",
            url: "../php/students/generateTOR.php",
            data: {studentId: studentId},
            success: function(data) {
                window.open("data", "Transcript Of Record", "width=1500px,height=2000px");
            },
            error: function(data) {
                alert("error Mj! in generating tor " + JSON.stringify(data));
            }
        });
    } else {
        alert("Please enter student's ID;");
    }
}