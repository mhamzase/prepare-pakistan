<?php
session_start();
include "database/connection.php";

// assign role to user
if (isset($_POST['assign_role'])) {
    $role = $_POST['role'];
    $id = $_POST['user_id'];

    mysqli_query($conn, "UPDATE users SET type='$role' WHERE id = '$id'");

    header("Location: users-list.php");
}


// delete user
if (isset($_POST['delete-user'])) {
    $id = $_POST['user_id'];

    mysqli_query($conn, "DELETE FROM users WHERE id = '$id'");

    header("Location: users-list.php");
}


if (isset($_SESSION['loggedIn'])) {

    $session_email = $_SESSION['loggedIn'];

    $user_query = "SELECT * FROM users WHERE email = '$session_email'";
    $user_result = mysqli_query($conn, $user_query);

    $user_data = mysqli_fetch_assoc($user_result);
    $db_type = $user_data['type'];

    if ($db_type == 0 || $db_type == 2) {

?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Users List</title>
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <link rel="stylesheet" href="css/adminDashboard.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
            <style>
                .container-fluid a button {
                    width: 15%;
                    height: 100px;
                    margin: 20px;
                    font-size: 20px;
                    font-weight: bold;
                }

                body {
                    overflow-x: hidden;
                }
            </style>
        </head>

        <body>

            <?php include "includes/header.php"; ?>
            <a href="admin-dashboard.php"><button type="button" class="btn btn-light m-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Back to Dashboard"><i class="bi bi-speedometer"></i> Dashboard</button></a>

            <div class="container">
                <table class="table table-hover mt-5 text-center">
                    <thead class="bg-primary">
                        <tr>
                            <th scope="col">SR#</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">View details</th>
                            <th scope="col">Role</th>
                            <?php
                            if ($db_type == 0) {
                            ?>
                                <th scope="col">Assign Role</th>
                                <th scope="col">Action</th>
                            <?php
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_users = mysqli_query($conn, "SELECT * FROM users WHERE type = 1 or type = 2");
                        $count = 0;
                        while ($row = mysqli_fetch_assoc($sql_users)) {
                            $count++;
                        ?>
                            <tr class="bg-dark text-light text-center">
                                <th scope="row"><?php echo $count; ?></th>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td>
                                    <button onclick="fetchUserDetails.call(this)" type='button' id="<?php echo $row['id']; ?>" value="<?php echo "$row[fullname]0@#$%&*$row[email]0@#$%&*$row[username]0@#$%&*$row[phone]0@#$%&*$row[bio]0@#$%&*$row[street]0@#$%&*$row[city]0@#$%&*$row[state]0@#$%&*$row[zipcode]"; ?>" class='btn btn-success actions' data-toggle='modal' data-target='#userDetails'>
                                        <i class='bi bi-eye-fill' style="padding:20px"></i>
                                    </button>
                                </td>
                                <td><?php if ($row['type'] == 1) {
                                        echo "User";
                                    } else {
                                        echo "Sub Admin";
                                    } ?>
                                </td>

                                <?php if ($db_type == 0) { ?>
                                    <td>
                                        <form action="" method="post">
                                            <input type="hidden" name="user_id" value="<?php echo $row['id'] ?>">
                                            <div class="row">
                                                <div class="col-8">
                                                    <select name="role" id="role" class="form-control" required>
                                                        <option value="">Select Role</option>
                                                        <option value="2">Sub Admin</option>
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <button class="btn btn-primary col-12" name="assign_role">Assign</button>
                                                </div>

                                            </div>
                                        </form>
                                    </td>

                                    <td>
                                        <form action="" method="post" onsubmit="return confirmAlert()">
                                            <input type="hidden" name="user_id" value="<?php echo $row['id'] ?>">

                                            <button class="btn btn-danger" name="delete-user">Delete</button>
                                        </form>
                                    </td>
                                <?php
                                }
                                ?>
                            </tr>
                        <?php
                        }
                        ?>


                    </tbody>
                </table>
            </div>




            <!-- Confirmation model to view user detials -->
            <div class="modal fade" id="userDetails" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-info" id="exampleModalLabel"><i class="bi bi-person-circle"></i> User Details</h5>
                        </div>
                        <div class="modal-body">
                            <span class="text-warning">Full Name : </span>
                            <small id="fullname"></small><br />
                            <span class="text-warning">Email : </span>
                            <small id="email"></small><br />
                            <span class="text-warning">Username : </span>
                            <small id="username"></small><br />
                            <span class="text-warning">Phone # : </span>
                            <small id="phone"></small><br />
                            <hr>
                            <span class="text-warning">Bio : </span>
                            <small id="bio"></small><br />
                            <hr>
                            <span class="text-warning">Address : </span>
                            <small id="street"></small><br />
                            <span class="text-warning">City : </span>
                            <small id="city"></small><br />
                            <span class="text-warning">State : </span>
                            <small id="state"></small><br />
                            <span class="text-warning">Zipcode : </span>
                            <small id="zipcode"></small><br />
                        </div>
                    </div>
                </div>
            </div>



            <script>
                // delete confirm function
                function confirmAlert() {
                    if (window.confirm("Are you sure?")) {
                        return true;
                    } else {
                        return false;
                    }
                }
                // fetching user details
                function fetchUserDetails() {


                    let fullname = document.getElementById("fullname");
                    let email = document.getElementById("email");
                    let username = document.getElementById("username");
                    let phone = document.getElementById("phone");
                    let bio = document.getElementById("bio");
                    let street = document.getElementById("street");
                    let city = document.getElementById("city");
                    let state = document.getElementById("state");
                    let zipcode = document.getElementById("zipcode");



                    let data = this.value.split("0@#$%&*");

                    fullname.innerText = data[0];
                    email.innerText = data[1];
                    username.innerText = data[2];
                    phone.innerText = data[3];
                    bio.innerText = data[4];
                    street.innerText = data[5];
                    city.innerText = data[6];
                    state.innerText = data[7];
                    zipcode.innerText = data[8];


                }
            </script>



        </body>

        </html>

<?php
    } else {
        header("Location: user-dashboard.php");
    }
} else {
    header("Location: login-form.php");
}
mysqli_close($conn);
?>