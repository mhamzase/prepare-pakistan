<?php
session_start();
include "database/connection.php";

if (isset($_SESSION['loggedIn'])) {

    $session_email = $_SESSION['loggedIn'];

    $user_query = "SELECT * FROM users WHERE email = '$session_email'";
    $user_result = mysqli_query($conn, $user_query);

    $user_data = mysqli_fetch_assoc($user_result);
    $db_type = $user_data['type'];

    if ($db_type == 1) {

?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>User - Profile</title>
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <link rel="stylesheet" href="css/userDashboard.css">
            <link rel="stylesheet" href="css/userProfile.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

            <style>
                .container-fluid a button {
                    width: 30%;
                    height: 150px;
                    margin: 20px;
                    font-size: 30px;
                    font-weight: bold;
                }

                body {
                    overflow-x: hidden;
                }
            </style>
        </head>

        <body style="overflow-x:hidden">

            <?php include "includes/header.php"; ?>


            <a href="user-dashboard.php"><button type="button" class="btn btn-light m-2" data-bs-toggle="tooltip" title="Back to Dashboard"><i class="bi bi-speedometer"></i> Dashboard</button></a>


            <?php

            $sql_user = mysqli_query($conn, "SELECT * FROM users WHERE email = '$_SESSION[loggedIn]'");
            $fetch_user = mysqli_fetch_assoc($sql_user);

            if (isset($_POST['updateProfile'])) {
                $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
                $username = mysqli_real_escape_string($conn, $_POST['username']);
                $phone = mysqli_real_escape_string($conn, $_POST['phone']);
                $bio = mysqli_real_escape_string($conn, $_POST['bio']);
                $street = mysqli_real_escape_string($conn, $_POST['street']);
                $city = mysqli_real_escape_string($conn, $_POST['city']);
                $state = mysqli_real_escape_string($conn, $_POST['state']);
                $zipcode = mysqli_real_escape_string($conn, $_POST['zipcode']);

                $sql = "SELECT * FROM users WHERE username = '$username'";
                $result = mysqli_query($conn, $sql);
                $count_username = false;
                $count_phone = false;

                while ($row =  mysqli_fetch_assoc($result)) {
                    if ($fetch_user['username'] == $row['username']) {
                        continue;
                    } else {
                        $count = mysqli_num_rows($result);
                    }
                }

                $sql1 = "SELECT * FROM users WHERE phone = '$phone'";
                $result1 = mysqli_query($conn, $sql1);
                $count_phone = false;

                while ($row1 =  mysqli_fetch_assoc($result1)) {
                    if ($fetch_user['phone'] == $row1['phone']) {
                        continue;
                    } else {
                        $count_phone = mysqli_num_rows($result1);
                    }
                }

                if ($count_username) {
                    header("Location: user-profile.php?error=username already taken!");
                } else {
                    if ($count_phone) {
                        header("Location: user-profile.php?error=phone number already taken!");
                    } else {
                        $sql_update_user = mysqli_query($conn, "UPDATE users SET username = '$username' , fullname = '$fullname' , phone = '$phone' , bio = '$bio' , street = '$street' , city = '$city' , state = '$state' , zipcode = '$zipcode' WHERE email = '$_SESSION[loggedIn]'");

                        if ($sql_update_user) {
                            header("Location: user-profile.php?success=Profile updated successfully!");
                        }
                    }
                }
            }




            if (isset($_POST['changePassword'])) {

                $email = $_SESSION['loggedIn'];
                $currentPassword = mysqli_real_escape_string($conn, $_POST['current_password']);
                $newPassword = mysqli_real_escape_string($conn, $_POST['new_password']);

                $sql = "SELECT password FROM users WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);
                $user_db_password = mysqli_fetch_assoc($result);

                $db_password = $user_db_password['password'];
                $password_check = password_verify($currentPassword, $db_password);


                if ($currentPassword == $newPassword) {
                    header("Location: user-profile.php?error_password=Your new password is already in use!");
                } else if ($password_check) {
                     $store_password = password_hash($newPassword,PASSWORD_BCRYPT);;
                    
                    $result2 = mysqli_query($conn, "UPDATE users SET password = '$store_password' WHERE email = '$email'");
                    header("Location: user-profile.php?success_password=Password Changed Successfully!");
                } else {
                    header("Location: user-profile.php?error_password=Current password is wrong!");
                }
            }

            ?>









            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validateUpdateProfile()">

                <div class="row">
                    <?php

                    if (isset($_GET['success'])) {
                    ?>
                        <span class="badge bg-success" style="padding:15px;width:83%;margin:auto"><?php echo $_GET['success']; ?></span>
                    <?php
                    }

                    if (isset($_GET['error'])) {
                    ?>
                        <span class="badge bg-danger" style="padding:15px;width:83%;margin:auto"><?php echo $_GET['error']; ?></span>
                    <?php
                    }

                    ?>
                </div>


                <div class="container">
                    <div class="row gutters mt-2">
                        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="account-settings">
                                        <div class="user-profile">
                                            <div class="user-avatar">
                                                <!-- <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="Maxwell Admin"> -->
                                                <div class="profile-logo bg-dark"><?php echo strtoupper(substr($fetch_user['username'], 0, 1)); ?></div>
                                            </div>
                                            <h5 class="user-name"><?php echo $fetch_user['username']; ?></h5>
                                            <h6 class="user-email"><?php echo $fetch_user['email']; ?></h6>
                                        </div>
                                        <div class="about">
                                            <h3 class="mb-2 heading">Bio</h3>
                                            <p><?php echo $fetch_user['bio']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="row gutters">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                            <h3 class="mb-3 heading">Personal Details</h3>
                                        </div>


                                        <!-- Error message here -->
                                        <span class="badge bg-danger m-auto" id="error"></span>
                                        <!-- <p class="text-danger" id="error"></p> -->




                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="fullName">Full Name</label>
                                                <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter full name" value="<?php echo $fetch_user['fullname']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="eMail">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email ID" value="<?php echo $fetch_user['email']; ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-2">
                                            <div class="form-group">
                                                <label for="username">Username</label>
                                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" value="<?php echo $fetch_user['username']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-2">
                                            <div class="form-group">
                                                <label for="phone">Phone</label>
                                                <input type="text" class="form-control" id="phone" name="phone" maxlength="11" placeholder="Enter phone number Example-[ 0301123456789 ]" value="<?php echo $fetch_user['phone']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                            <div class="form-group">
                                                <label for="bio">Bio</label>
                                                <textarea type="text" class="form-control" style="resize: none;" name="bio" maxlength="300" id="bio" rows="4" placeholder="Enter Bio Detials ( Max Length : 300 characters )"><?php echo $fetch_user['bio']; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row gutters">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                            <h3 class="mb-3 mt-3 heading">Address</h3>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="Street">Street</label>
                                                <input type="name" class="form-control" id="street" name="street" placeholder="Enter Street" maxlength="90" value="<?php echo $fetch_user['street']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="ciTy">City</label>
                                                <input type="name" class="form-control" id="city" name="city" placeholder="Enter City" value="<?php echo $fetch_user['city']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-2">
                                            <div class="form-group">
                                                <label for="sTate">State</label>
                                                <input type="text" class="form-control" id="state" name="state" placeholder="Enter State" value="<?php echo $fetch_user['state']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-2">
                                            <div class="form-group">
                                                <label for="zIp">Zip Code</label>
                                                <input type="number" class="form-control" id="zipcode" name="zipcode" placeholder="Zip Code" value="<?php echo $fetch_user['zipcode']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row gutters">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                            <div class="text-right">
                                                <button type="submit" id="updateProfile" name="updateProfile" class="btn btn-primary mt-3 float-right">Update Profile</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>


            <form action="" method="post" onsubmit="return validateChangePassword()">
                <div class="conatiner mt-4">
                    <div class="col-xl-10 col-lg-10 col-md-10 col-sm-10 col-10 m-auto ">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="row gutters">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <h3 class="mb-3 heading">Change Password</h3>
                                    </div>


                                    <?php

                                    if (isset($_GET['success_password'])) {
                                    ?>
                                        <span class="badge bg-success" style="padding:15px;width:83%;margin:auto"><?php echo $_GET['success_password']; ?></span>
                                    <?php
                                    }

                                    if (isset($_GET['error_password'])) {
                                    ?>
                                        <span class="badge bg-danger" style="padding:15px;width:83%;margin:auto"><?php echo $_GET['error_password']; ?></span>
                                    <?php
                                    }

                                    ?>

                                    <!-- Error message here -->
                                    <span class="badge bg-danger m-auto" id="password_error"></span>
                                    <!-- <p class="text-danger" id="error"></p> -->

                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="form-group">
                                            <label for="current_password">Current password</label>
                                            <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Enter current password" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-2">
                                        <div class="form-group">
                                            <label for="new_password">New password</label>
                                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter new password" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-2">
                                        <div class="form-group">
                                            <label for="confirm_password">Confirm password</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Enter confirm password" required>
                                        </div>
                                    </div>


                                </div>

                                <div class="row gutters">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="text-right">
                                            <button type="submit" id="changePassword" name="changePassword" class="btn btn-primary mt-3 float-right">Change Password</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- <script src="js/update-profile.js"></script> -->
        </body>


        <script>
            function validateChangePassword() {
                var newPassword = document.getElementById("new_password");
                var confirmPassword = document.getElementById("confirm_password");
                var password_error = document.getElementById("password_error");


                if (newPassword.value != confirmPassword.value) {
                    password_error.innerText = "new password & confirm password don't matching... !";
                    return false;
                } else {
                    return true;
                }
            }
        </script>

        </html>

<?php
    } else {
        header("Location: admin-dashboard.php");
    }
} else {
    header("Location: login-form.php");
}
mysqli_close($conn);
?>