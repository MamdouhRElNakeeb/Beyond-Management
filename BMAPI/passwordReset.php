<?php
/**
 * Created by PhpStorm.
 * User: nakeebimac
 * Date: 9/3/17
 * Time: 8:23 AM
 */

require ("secure/access.php");
require ("secure/bmconn.php");

$email = $_GET['key'];
$password = $_GET['reset'];

$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();


$user = $access->verifyApplicant($email, $password);


if ($user) {

    ?>

    <form class="form" method="post" action="passRenew.php">
        <div class="header header-primary text-center">
            <h4 class="card-title">Reset Password</h4>

        </div>

        <div class="card-content">

            <input name="email" type="hidden" value="<?php echo $user["email"];?>" class="form-control">
            <input name="password" type="password" placeholder="New Password..." class="form-control">
            <input type="submit" class="btn btn-primary btn-simple btn-wd btn-lg" value="Change" name="submit_password"/>

    </form>
    <?php


}

?>

