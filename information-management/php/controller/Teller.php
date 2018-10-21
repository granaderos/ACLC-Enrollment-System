<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 3/7/17
 * Time: 3:36 AM
 * To change this template use File | Settings | File Templates.
 */
session_start();
include_once "DatabaseConnector.php";

class Teller extends DatabaseConnector {

    function updateTuitionFee($tuition) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("UPDATE tuitionFee SET feePerUnit = ?;");
        $sql->execute(array($tuition));

        $this->closeConnection();
    }

    function transact($studentId, $currentBalance, $needToPay, $amountRendered, $paymentFor) {
        $this->openConnection();

        $sql1 = $this->dbHolder->query("SELECT * FROM config;");
        $config = $sql1->fetch();

        $sql = $this->dbHolder->prepare("INSERT INTO transactions VALUES (null, ?, ?, ?, ?, ?, ?);");
        $sql->execute(array($studentId, $paymentFor, $needToPay, $this->getDate(), $config[6], $config[5]));

        if($paymentFor == "prelim" OR $paymentFor == "midterm" OR $paymentFor == "prefinal" OR $paymentFor == "final" OR $paymentFor == "fullPayment" OR $paymentFor == "downPayment") {
            $currentBalance = $currentBalance-$needToPay;
            if($currentBalance < 0) $currentBalance = 0;
            $sql3 = $this->dbHolder->prepare("UPDATE studentBalance SET balance = ?
                                            WHERE studentId = ?
                                            AND sy = ?
                                            AND sem = ?;");
            $sql3->execute(array($currentBalance, $studentId, $config[6], $config[5]));
        }

        $sql2 = $this->dbHolder->prepare("SELECT s.lastname, s.firstname, s.middlename, s.yearLevel, p.progCode, p.description
                                            FROM  students s, programs p
                                            WHERE s.progCode = p.progCode
                                            AND s.studentId = ?;");
        $sql2->execute(array($studentId));
        $studInfo = $sql2->fetch();
        $curType = "Trimesral";
        if($config[1] == "Semestral") $curType = "Semester";

        $amountToWordFormatter = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $amountInWords = $amountToWordFormatter->format($needToPay);
        $receipt =
            "<div style='margin: auto;'>
                <h4 class='text-center'>ACLC College of Gapan</h4>
                <p class='text-center'>
                    <label >School Year ".$config[6]."</label><br />
                    <label>".$this->getOrder($config[5])." ".$curType."</label><br />
                </p>
                <h4 class='text-center'>OFFICIAL RECEIPT</h4>
                <table class='table'>
                    <tr>
                        <th>Student ID:</th>
                        <td>".$studentId."</td>
                        <th>Program:</th>
                        <td>".$studInfo[4]." - ".$studInfo[5]."</td>
                    </tr>
                    <tr>
                        <th>Name:</th>
                        <td>".$studInfo[0].", ".$studInfo[1]." ".$studInfo[2]."</td>
                        <th>Curriculum:</th>
                        <td>".$config[0]."</td>
                    </tr>
                </table>
                <table class='table table-striped'>
                    <tr>
                        <th>Payment For: </th><th>".$paymentFor."</th>
                    </tr>
                    <tr>
                        <th>Amount Paid: </th><th>".number_format($needToPay, 2)."</th>
                    </tr>
                    <tr>
                        <th>Amount in Words: </th><th>".$amountInWords." pesos only</th>
                    </tr>
                    <tr>
                        <th>Amount Rendered: </th><th>".number_format($amountRendered, 2)."</th>
                    </tr>
                    <tr>
                        <th>Change: </th><th>".number_format(($amountRendered-$needToPay), 2)."</th>
                    </tr>
                    <tr>
                        <th>Date of Transaction: </th><th>".$this->getDate()."</th>
                    </tr>
                </table>
                <br /><br />
                <p class='text-center'>
                    <label style='text-decoration: underline;'>This serves as your official receipt.</label>
                </p>
            </div>";
        $this->closeConnection();

        $_SESSION["receipt"] = $receipt;

    }

    function getOrder($num) {
        $order = array("1st", "2nd", "3rd", "4th", "5th", "6th", "7th", "8th", "9th", "10th");
        return $order[$num-1];
    }

    function getDate() {
        $sql = $this->dbHolder->query("SELECT CURDATE();");
        return $sql->fetch()[0];
    }

    function transGetStudInfo($studentId) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT DISTINCT s.lastname, s.firstname, s.middlename, sb.balance, sb.amountToPay FROM students s, studentBalance sb, config c
                                            WHERE s.studentId = sb.studentId
                                            AND c.sy = sb.sy
                                            AND c.sem = sb.sem
                                            AND s.studentId = ?;");
        $sql->execute(array($studentId));

        if($con = $sql->fetch()) {
            $sql1 = $this->dbHolder->prepare("SELECT t.* FROM transactions t, config c
                                            WHERE t.sy = c.sy
                                            AND t.sem = c.sem
                                            AND t.studentId = ?
                                            ORDER BY t.datePaid;");
            $sql1->execute(array($studentId));

            $sql2 = $this->dbHolder->query("SELECT * FROM config;");
            $config = $sql2->fetch();

            $curBal = $con[3];
            if($con[3] <= 0) $curBal = 00.00;


            $balData = "<label>Total Fee for S.Y. ".$config[6]." | ".$this->getOrder($config[5])." Year: </label>
                    <span style='font-size: 15px;'>Php ".number_format($con[4], 2)."</span><br />
                    <label>Current Balance: </label>
                    <span style='font-size: 15px;'>Php ".number_format($curBal, 2)."</span><br />";

            $data = "";
            $totalPaid = 0;
            while($trans = $sql1->fetch()) {
                $totalPaid += $trans[3];
                $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                $tranWord = $f->format($trans[3]);
                $data .= "<tr>
                        <th>".$trans[4]."</th>
                        <th>".$trans[2]."</th>
                        <th>Php ".number_format($trans[3], 2)." (".$tranWord.")</th>
                      </tr>";
            }
            if($data == "") $data = "<h4>No transactions retrieved;</h4>";
            else $data = $balData."<table class='table table-striped'>
                        <tr class='alert alert-danger'>
                            <th colspan='3'>
                                <h3>Previous Transaction</h3>
                            </th>
                        </tr>
                        <tr class='alert alert-info'>
                            <th>Date</th>
                            <th>Payment Description</th>
                            <th>Amount Paid</th>
                        </tr>
                        ".$data."
                        <tr class='alert alert-danger'>
                            <th></th>
                            <th>Total Paid:</th>
                            <th>Php ".number_format($totalPaid, 2)."</th>
                        </tr>
                      </table>";


            $data = array("details"=>$data, "name"=>$con[0].", ".$con[1]." ".$con[2], "currentBalance"=>number_format($curBal, 2), "toPay"=>number_format($con[4], 2));
            echo json_encode($data);


        } else echo "none";

        $this->closeConnection();
    }

    function editMisc($amount, $miscId) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("UPDATE miscellaneous SET amount = ? WHERE miscId = ?;");
        $sql->execute(array($amount, $miscId));

        $this->closeConnection();
    }

    function displayMiscFees() {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT * FROM miscellaneous ORDER BY toWhom;");

        $data = "";
        $miscTotal = 0;
        while($misc = $sql->fetch()) {
            $miscTotal += $misc[2];
            $data .= "<tr id='miscTR".$misc[0]."'>
                        <td id='miscName".$misc[0]."'>".$misc[1]."</td>
                        <td id='miscAmount".$misc[0]."'>".number_format($misc[2], 2)."</td>
                        <td>".$misc[3]."</td>
                        <td><a onclick='editMisc(".$misc[0].")'><span class='glyphicon glyphicon-edit'>edit</a> | <a onclick='deleteMisc(".$misc[0].")'><span class='glyphicon glyphicon-trash'>delete</a></td>
                      </tr>";
        }
        if($data == "") $data = "<h4>No records retrieved;</h4>";
        else $data = "<table class='table table-striped'><tr class='alert alert-info'><th>Miscellaneous Title</th><th>Amount</th><th>Applicable</th><th>Actions</th></tr>".$data."
                        <tr class='alert alert-danger'><th>Total Fees For All Programs: </th><th colspan='3'>Php ".number_format($miscTotal, 2)."</th></tr>
                       </table>";
        echo $data;
        $this->closeConnection();
    }

    function deleteMisc($id) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("DELETE FROM miscellaneous WHERE miscId = ?;");
        $sql->execute(array($id));

        $this->closeConnection();
    }

    function displayAllProgramsAsOptions() {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT * FROM programs;");
        $data = "";
        while($con = $sql->fetch()) {
            $data .= "<option value='".$con[0]."'>".$con[0]." - ".$con[1]."</option>";
        }
        if($data == "") $data = "none";
        echo $data;

        $this->closeConnection();
    }

    function displayTuitionFee() {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT feePerUnit FROM tuitionFee;");
        $data = "00.00";
        if($con = $sql->fetch()) $data = number_format($con[0], 2);
        echo $data;

        $this->closeConnection();
    }

    function addMisc($name, $amount, $for) {
        $this->openConnection();

        $sql1 = $this->dbHolder->prepare("SELECT * FROM miscellaneous WHERE misc = ? AND toWhom = ?;");
        $sql1->execute(array($name, $amount));

        if($sql1->fetch()) {
            echo "exist";
        } else {
            $sql = $this->dbHolder->prepare("INSERT INTO miscellaneous VALUES (null, ?, ?, ?);");
            $sql->execute(array($name, $amount, $for));
            echo "done";
        }

        $this->closeConnection();
    }

    function getMisc($misc) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT misc FROM miscellaneous WHERE misc LIKE ?;");
        $sql->execute(array("%".$misc."%"));
        $data = array();
        while($con = $sql->fetch()) {
            array_push($data, $con[0]);
        }
        echo json_encode($data);

        $this->closeConnection();
    }

    function displayTransactionReports($name, $from, $to) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare();

        $this->closeConnection();
    }

    function displayTransactions($name, $from, $to) {
        $this->openConnection();

        if(trim($name) != "") {
            $sql = $this->dbHolder->prepare("SELECT s.lastname, s.firstname, s.middlename, s.progCode, s.yearLevel, t.*
                                                FROM transactions t, students s
                                                WHERE s.studentId = t.studentId
                                                AND (s.lastname LIKE ? OR s.firstname LIKE ? OR s.middlename LIKE ? OR s.studentId = ?)
                                                ORDER BY t.datePaid DESC, s.lastname, s.firstname, s.middlename
                                                LIMIT 10;");
            $sql->execute(array("%".$name."%", "%".$name."%", "%".$name."%", $name));

            $data = "";
            $counter = 1;
            while($con = $sql->fetch()) {
                $data .= "<tr>
                            <td>".$counter.") ".$con[9]."</td>
                            <td>".$con[0].", ".$con[1]." ".$con[2]."</td>
                            <td>".$con[3]." (".$this->getOrder($con[4])." Year)</td>
                            <td>".$con[7]."</td>
                            <td>".number_format($con[8], 2)."</td>
                          </tr>";
                $counter++;
            }

            if($data == "") $data = "<h4 class='alert alert-danger'>No results for ".$name.";</h4>";
            else {
                $data = "<table class='table table-hover table-striped'>
                            <tr class='alert alert-info'>
                                <th>Date</th>
                                <th>Student Name</th>
                                <th>Program & Year</th>
                                <th>Payment Description</th>
                                <th>Amount</th>
                            </tr>
                            ".$data."
                          </table>";
                $_SESSION["transaction"] = $data;
                $_SESSION["transTitle"] = "<h2 class='text-center'>Transactions of <span style='text-decoration: underline'>".$name."</span></h2>";
                $data = "<p>
                            <h2 class='pull-left'>Transactions of <span style='text-decoration: underline'>".$name."</span></h2>
                            <button onclick='printTransaction()' class='btn btn-warning btn-lg pull-right'><span class='glyphicon glyphicon-print'></span>&nbsp; PRINT REPORT</button>
                          </p>".$data;
            }

            echo $data;
        } else if(trim($from) != "" && trim($to) != "") {
            $sql = $this->dbHolder->prepare("SELECT s.lastname, s.firstname, s.middlename, s.progCode, s.yearLevel, t.*
                                                FROM transactions t, students s
                                                WHERE s.studentId = t.studentId
                                                AND t.datePaid >= ? AND t.datePaid <= ?
                                                ORDER BY t.datePaid DESC, s.lastname, s.firstname, s.middlename
                                                LIMIT 10;");
            $sql->execute(array($from, $to));

            $data = "";
            $counter = 1;
            while($con = $sql->fetch()) {
                $data .= "<tr>
                            <td>".$counter.") ".$con[9]."</td>
                            <td>".$con[0].", ".$con[1]." ".$con[2]."</td>
                            <td>".$con[3]." (".$this->getOrder($con[4])." Year)</td>
                            <td>".$con[7]."</td>
                            <td>".number_format($con[8], 2)."</td>
                          </tr>";
                $counter++;
            }

            if($data == "") $data = "<h4 class='alert alert-danger'>No transactions from ".$from." to ".$to.";</h4>";
            else {
                $data = "<table class='table table-hover table-striped'>
                            <tr class='alert alert-info'>
                                <th>Date</th>
                                <th>Student Name</th>
                                <th>Program & Year</th>
                                <th>Payment Description</th>
                                <th>Amount</th>
                            </tr>
                            ".$data."
                          </table>";
                $_SESSION["transaction"] = $data;
                $_SESSION["transTitle"] = "<h2 class='text-center'>Transactions from <span style='text-decoration: underline'>".$from."</span> to <span style='text-decoration: underline'>".$to."</span></h2>";
                $data = "<p>
                            <h2 class='pull-left'>Transactions from <span style='text-decoration: underline'>".$from."</span> to <span style='text-decoration: underline'>".$to."</span></h2>
                            <button onclick='printTransaction()' class='btn btn-warning btn-lg pull-right'><span class='glyphicon glyphicon-print'></span>&nbsp; PRINT REPORT</button>
                         </p>".$data;
            }

            echo $data;
        } else {
            $sql = $this->dbHolder->query("SELECT s.lastname, s.firstname, s.middlename, s.progCode, s.yearLevel, t.*
                                                FROM transactions t, students s
                                                WHERE s.studentId = t.studentId
                                                ORDER BY t.datePaid DESC
                                                LIMIT 10;");
            $data = "";
            $counter = 1;
            while($con = $sql->fetch()) {
                $data .= "<tr>
                            <td>".$counter.") ".$con[9]."</td>
                            <td>".$con[0].", ".$con[1]." ".$con[2]."</td>
                            <td>".$con[3]." (".$this->getOrder($con[4])." Year)</td>
                            <td>".$con[7]."</td>
                            <td>".number_format($con[8], 2)."</td>
                          </tr>";
                $counter++;
            }

            if($data == "") $data = "<h4>No records retrieved;</h4>";
            else {
                $data = "<table class='table table-hover table-striped'>
                            <tr class='alert alert-info'>
                                <th>Date</th>
                                <th>Student Name</th>
                                <th>Program & Year</th>
                                <th>Payment Description</th>
                                <th>Amount</th>
                            </tr>
                            ".$data."
                          </table>";
                $_SESSION["transaction"] = $data;
                $_SESSION["transTitle"] = "<h2 class='text-center'>Recent Transactions</h2>";
            $data = "<p>
                        <h2 class='pull-left'>Recent Transactions</h2>
                        <button onclick='printTransaction()' class='btn btn-warning btn-lg pull-right'><span class='glyphicon glyphicon-print'></span>&nbsp; PRINT REPORT</button>
                     </p>".$data;
            }

            echo $data;
        }
        $this->closeConnection();
    }

    function getFormula() {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT downpayment, installment FROM tuitionFee;");
        $f = $sql->fetch();

        echo json_encode(array("downpayment"=>$f[0], "installment"=>$f[1]));

        $this->closeConnection();
    }

    function editFdownpayment($dp) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("UPDATE tuitionFee SET downpayment = ?;");
        $sql->execute(array($dp));

        $this->closeConnection();
    }

    function editFinstallment($installment) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("UPDATE tuitionFee SET installment = ?;");
        $sql->execute(array($installment));

        $this->closeConnection();
    }
}