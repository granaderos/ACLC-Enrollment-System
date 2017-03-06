<html>
    <head>
        <title>ACLC | Cashier</title>
        <link type="text/css" rel="stylesheet" href="../css/misc.css">

    </head>
    <body>
        <?php include_once "../misc/header.php"?>
        <?php include_once "../navs/cashierNav.html"?>
        <div id="adding">
            <form>
                <table>
                    <tr>
                        <td>Miscellaneous Title</td>
                        <td><input type="text" name="miscName" id="miscName"></td>
                    </tr>
                    <tr>
                        <td>Fee</td>
                        <td><input type="text" name="fee" id="fee"> </td>
                    </tr>
                </table>
                <input type="submit" value="Add" name="submit" id="button">
            </form>
        </div>
        <div id="view">
            Dito lagay table ng mg misc
        </div>
    </body>
</html>