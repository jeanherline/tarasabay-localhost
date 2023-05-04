<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
}

include('db.php');

$userid = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registered Cars</title>
    <link rel="icon" href="assets/img/logo.png" type="images" />
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");

        *,
        *::before,
        *::after {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body,
        input {
            font-family: "Poppins", sans-serif;
            margin: 0 auto;
            max-width: 2000px;
            max-height: 2000px;
            margin-top: 50px;
            margin-bottom: 5px;
            padding: 0px 15px 0 15px;
            background-color: #EAAA00;
        }

        form {
            background-color: #FFFFFF;
            padding: 20px;
            border-radius: 10px;
        }

        input[type=text],
        input[type=password],
        input[type=number] {
            width: 97%;
            padding: 10px;
            margin: 5px 0 22px 0;
            display: inline-block;
            border: none;
            background: #F5F5F5;
        }

        hr {
            border: 1px solid #e6e6e6;
            margin-bottom: 5px;
        }

        .registerbutton {
            background-color: black;
            color: white;
            padding: 15px 20px;
            margin: 10px 0px;
            border: none;
            cursor: pointer;
            width: 70%;
            font-size: 18px;
            margin-left: 15%;
        }

        h1,
        p {
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
            border: 1px solid black;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <form action="" method="post">
        <h1><?php echo $_SESSION['first_name']; ?>'s Car Registration Status</h1>
        <br>
        <hr>
        <br>
        <table>
            <thead>
                <tr>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Color</th>
                    <th>Seat Count</th>
                    <th>License Plate</th>
                    <th>CR Number</th>
                    <th>OR Number</th>
                    <th>Reg Exp Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $db->prepare("SELECT c.car_id, c.owner_id, c.brand, c.model, ci.car_identity_num, ci.cr_number, ci.or_number, ci.reg_exp_date, c.status, c.color, c.seat_count
            FROM car c
            JOIN car_identification ci ON c.car_id = ci.car_id
            JOIN user_profile up ON c.owner_id = up.user_id
            WHERE up.role IN ('user', 'driver')
            AND up.user_id = ?");
                $stmt->bind_param("s", $userid);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['brand'] . "</td>";
                    echo "<td>" . $row['model'] . "</td>";
                    echo "<td>" . $row['color'] . "</td>";
                    echo "<td>" . $row['seat_count'] . "</td>";
                    echo "<td>" . $row['car_identity_num'] . "</td>";
                    echo "<td>" . $row['cr_number'] . "</td>";
                    echo "<td>" . $row['or_number'] . "</td>";
                    echo "<td>" . $row['reg_exp_date'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>


    </form>
    <br><br>
</body