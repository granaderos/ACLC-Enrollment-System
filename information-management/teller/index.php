
<?php
session_start();

if(!isset($_SESSION["type"]) OR $_SESSION["type"] != "cashier")
    header("Location: ../login");
?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ACLC | Teller</title>
        <?php include_once "../misc/imports.html"; ?>
        <script src="../js/teller.js" type="text/javascript"></script>

        <script type="text/javascript">
            $(function () {
                $("#displayFrom").datepicker();
                $("#displayTo").datepicker();
            })
        </script>
    </head>
    <body>
        <?php include_once "../misc/header.php"; ?>
        <?php include_once "../navs/cashierNav.html"; ?>
        <div id="tellerMainContainerDiv" class="mainContainer"></div>

        <div class="modal fade" id="studentTransDetailsDiv">
            <div class="modal-dialog text-center">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <p>
                            <h2 class="modal-title" id="studentNamePrereg">Transaction Details</h2>
                        </p>
                    </div>
                    <div class="modal-body">
                        <div class="panel-body text-left">
                            <div id="studentTransDetailData"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="manageFeesContainerDiv" class="container-fluid hidden">
            <h2 style="margin-top: 130px !important;">Fees Management</h2>
            <div class="row">
                <div class="col-lg-7">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#manageMisc">Manage Misc. Fees</a></li>
                        <li><a data-toggle="tab" href="#displayMisc">Miscellaneous Fees</a></li>
<!--                        <li><a data-toggle="tab" href="#feesPerProgram">Fees per Program</a></li>-->
                    </ul>

                    <div class="tab-content">
                        <div id="displayMisc" class="tab-pane fade">
                            <h3>Miscellaneous Fees</h3>
                            <div id="displayMiscContainer"></div>
                        </div>

                        <div id="manageMisc" class="tab-pane fade in active">
                            <h3>Miscellaneous Fees Management</h3>
                            <div>
                                <label>Misc. Name: </label>
                                <input type="text" class="ui-widget form-control" id="miscName" onkeyup="getMisc()" />
                                <br />
                                <label>Amount: </label>
                                <input type="text" class="form-control" id="miscAmount" />
                                <br />
                                <label>Applicable For: </label>
                                <select class="form-control" id="miscFor">
                                    <option value="all">All Programs</option>
                                </select>
                                <br />
                                <button class="btn btn-primary btn-block" onclick="addMisc()">Add Miscellaneous</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 alert alert-danger">
                    <label>Fee per Unit:</label><br/>
                    <form onsubmit="updateTuitionFee(); return false;" style="text-align: center; margin-left: 5em;">
                        <div class="input-group">
                            <span class="input-group-addon" id="addonToPay">Php</span>
                            <input type="text" style="" class="form-control" id="feePerUnit" aria-describedby="addonToPay" disabled />
                            <span class="input-group-addon" id="addonToPay">/unit</span>
                        </div>
                        <br/>
                        <button class="btn btn-primary btn-block" id="btnUpdateTuition" style="">Update Fee per Unit</button>
                    </form>
                </div>
            </div>
        </div>

        <div id="homeContainerDiv" class="container-fluid hidden">
            <div class="form-inline container-fluid">
                <table class="table" style="margin-top: 30px;">
                    <tr class='alert alert-danger'>
                        <th colspan="3">
                            Transaction
                        </th>
                    </tr>
                    <tr>
                        <td><label>Student ID </label</td>
                        <td>:</td>
                        <td><form onsubmit="transGetStudInfo(); return false;"><input type="text" id="studentId" class="form-control" /></form></td>
                    </tr>
                    <tr>
                        <td><label>Student Name </label></td>
                        <td>:</td>
                        <td><span style="text-decoration: underline" id="studentName">______________________</span></td>
                    </tr>
                    <tr>
                        <td>Current Balance</td>
                        <td>:</td>
                        <td>
                            <input type="text" class="form-control" id="currentBalance" disabled/>
                            &nbsp;
                            <a href="#studentTransDetailsDiv" data-toggle="modal">view details</a>
                        </td>
                    </tr>
                    <tr><td></td><td></td><td></td></tr>
                </table>
                <div class="row">
                    <div class="col-lg-3">
                        <label>Payment For:</label><br/>
                        <p style="margin-left: 5em;">
                            <input type="radio" name="paymentFor" onchange="getPaymentFor()" value="downPayment" id="paymentForDownPayment"/> Downpayment <br />
                            <input type="radio" name="paymentFor" onchange="getPaymentFor()" value="prelim" id="paymentForPrelim" /> Prelim <br />
                            <input type="radio" name="paymentFor" onchange="getPaymentFor()" value="midterm" id="paymentForMidterm" /> Midterm <br />
                            <input type="radio" name="paymentFor" onchange="getPaymentFor()" value="prefinal" id="paymentForPrefinal" /> Pre-final <br />
                            <input type="radio" name="paymentFor" onchange="getPaymentFor()" value="final" id="paymentForFinal" /> Final <br />
                            <input type="radio" name="paymentFor" onchange="getPaymentFor()" value="fullPayment" id="paymentForFullPayment" /> Full Payment <br />
                            <input type="radio" name="paymentFor" onchange="getPaymentFor()" value="other" id="paymentForOthers"/> Others: <br />
                            <input type="text" class="form-control" placeholder="Please specify" id="paymentForOther" disabled>
                        </p>
                    </div>
                    <div class="col-lg-3">
                        <label>To Pay: </label><br />
                        <div style="margin-left:  5em;">
                            <div class="input-group">
                                <span class="input-group-addon" id="addonToPay">Php</span>
                                <input type="text" class="form-control" id="toPay" aria-describedby="addonToPay" disabled />
                            </div>
                            <a onclick="enableToPay()">specify amount</a>

                        </div>
                        <br />
                        <label>Amount Rendered: </label><br />
                        <form onsubmit="transact(); return false;">
                            <div style="margin-left:  5em;" class="input-group">
                                <span class="input-group-addon" id="sizing-addon2">Php</span>
                                <input type="text" class="form-control" id="amountRendered" aria-describedby="sizing-addon2" />
                            </div>
                        </form>
                        <button class="btn btn-block btn-primary" onclick="transact()">Submit</button>
                    </div>
                    <div class="col-lg-5">
                        <label>Change: </label><br />
                        <div style="margin-left: 5em;" class="input-group">
                            <span style='height: 185px;' class="input-group-addon" id="addonChange">Php</span>
                            <input style='height: 185px; font-size: 50px;' value="00.00" type="text" class="form-control" id="change" aria-describedby="addonChange" disabled />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>