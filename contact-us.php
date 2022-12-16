<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
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




    <?php

    // $sql_user = mysqli_query($conn, "SELECT * FROM users WHERE email = '$_SESSION[loggedIn]'");
    // $fetch_user = mysqli_fetch_assoc($sql_user);

    // if (isset($_POST['updateProfile'])) {
    //     $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    //     $username = mysqli_real_escape_string($conn, $_POST['username']);
    //     $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    //     $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    //     $street = mysqli_real_escape_string($conn, $_POST['street']);
    //     $city = mysqli_real_escape_string($conn, $_POST['city']);
    //     $state = mysqli_real_escape_string($conn, $_POST['state']);
    //     $zipcode = mysqli_real_escape_string($conn, $_POST['zipcode']);

    //     $sql = "SELECT * FROM users WHERE username = '$username'";
    //     $result = mysqli_query($conn, $sql);
    //     $count_username = false;
    //     $count_phone = false;

    //     while ($row =  mysqli_fetch_assoc($result)) {
    //         if ($fetch_user['username'] == $row['username']) {
    //             continue;
    //         } else {
    //             $count = mysqli_num_rows($result);
    //         }
    //     }

    //     $sql1 = "SELECT * FROM users WHERE phone = '$phone'";
    //     $result1 = mysqli_query($conn, $sql1);
    //     $count_phone = false;

    //     while ($row1 =  mysqli_fetch_assoc($result1)) {
    //         if ($fetch_user['phone'] == $row1['phone']) {
    //             continue;
    //         } else {
    //             $count_phone = mysqli_num_rows($result1);
    //         }
    //     }

    //     if ($count_username) {
    //         header("Location: user-profile.php?error=username already taken!");
    //     } else {
    //         if ($count_phone) {
    //             header("Location: user-profile.php?error=phone number already taken!");
    //         } else {
    //             $sql_update_user = mysqli_query($conn, "UPDATE users SET username = '$username' , fullname = '$fullname' , phone = '$phone' , bio = '$bio' , street = '$street' , city = '$city' , state = '$state' , zipcode = '$zipcode' WHERE email = '$_SESSION[loggedIn]'");

    //             if ($sql_update_user) {
    //                 header("Location: user-profile.php?success=Profile updated successfully!");
    //             }
    //         }
    //     }
    // }


    ?>









    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validateContactForm()">

        <div class="container mb-5" style="margin-top: 8em;">
            <div class="row gutters">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                    <div class="card h-100">
                        <div class="card-body text-dark" style="background-color: #ae9e62;">
                            <div class="account-settings ">
                                <div class="row text-center display-2">
                                    <i class="bi bi-person-lines-fill"></i>
                                </div>

                                <div class="row text-center mt-5">
                                    <span><i class="bi bi-envelope-fill"></i> contact@preparepakistan.com</span>
                                </div>

                                <div class="row text-center mt-3">
                                    <span><i class="bi bi-whatsapp"></i> +923041234567</span>
                                </div>

                                <div class="row text-center mt-3">
                                    <span><i class="bi bi-telephone-fill"></i> +923041234567</span>
                                </div>

                                <div class="row text-center mt-3">
                                    <span><i class="bi bi-geo-alt-fill"></i> 1775 Washington St, Hanover MA 2339</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                    <div class="card h-100">
                        <div class="card-body text-dark" style="background-color: #ae9e62;">
                            <div class="row gutters ">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <h2 class="mb-3 heading text-center">Contact Us</h2>
                                </div>


                                <!-- Error message here -->
                                <div class="row">
                                    <span class="badge bg-danger m-auto p-2 col-5" id="error"></span>
                                </div>
                                <!-- <p class="text-danger" id="error"></p> -->




                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="fullName">Name</label>
                                        <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter name" value="">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email ID" value="">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-2">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone" maxlength="11" placeholder="Enter phone number" value="">
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                    <div class="form-group">
                                        <label for="message">Message</label>
                                        <textarea type="text" class="form-control" style="resize: none;" name="message" maxlength="300" id="message" rows="6" placeholder="Enter message... ( Max 300 characters )"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="text-right">
                                        <button type="submit" id="contactus" name="contactus" class="btn btn-primary mt-3 float-right">Send Message</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>

    <?php include "includes/footer.php" ?>

    <script>
        function validateContactForm() {
            let fullname = document.getElementById("fullname");
            let email = document.getElementById("email");
            let phone = document.getElementById("phone");
            let message = document.getElementById("message");
            let error = document.getElementById("error");


            if (fullname.value.trim() == "") {
                error.innerText = "Name is required!";
                return false;
            } else {
                if (email.value.trim() == "") {
                    error.innerText = "Email is required!";
                    return false;
                } else {
                    if (phone.value.trim() == "") {
                        error.innerText = "Phone is required!";
                        return false;
                    } else {
                        if (message.value.trim() == "") {
                            error.innerText = "Message is required!";
                            return false;
                        } else {
                            return true;
                        }
                    }
                }
            }
        }
    </script>

</body>

</html>