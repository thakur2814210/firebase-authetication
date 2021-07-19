<?php require_once 'session_setup.php'; if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
$user_id = $_SESSION['user_id'];
require_once 'includes/database_config.php';
$query = "select admin from user where user_id='$user_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_row($result);
if ($row[0] != 1) {
    header('Location: index.php');
    exit;
}
?>
<?php include 'includes/head.php'; include 'includes/nav_logged_in.php'; ?>
<div class="container">
    <ul class="nav nav-pills">
        <li class="active"> <a href="#tab-no-of-users" data-toggle="tab">No Of Users</a> </li>
        <li> <a href="#tab-no-of-business-cards" data-toggle="tab">No Of Business Cards</a> </li>
    </ul>
</div>
<div class="tab-content container" style="padding-top:0;">
    <!-- CONTENT -->
    <div id="tab-no-of-users" class="tab-pane active">
        <div class="row" style="padding: 50px;">
            <table id="grid_no_of_users" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Surname</th>
                        <th>First Name</th>
                        <th>Email Address</th>
                        <th>Card Number</th>
                        <th>Date Registered</th>
                        <th>Delete account</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="tab-no-of-business-cards" class="tab-pane">
        <div class="row" style="padding: 50px;">
            <table id="grid_no_of_business_cards" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Card Type</th>
                        <th>Surname</th>
                        <th>First Name</th>
                        <th>Email Address</th>
                        <th>Date Registered</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
<!-- Load dependency assets -->
<script src="assets/js/jquery.dataTables.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="assets/css/dataTables.bootstrap.css"><!-- Load page specific assets -->
<script type="text/javascript" src="admin-report.js?123"></script>
</body>

</html>