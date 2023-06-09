<?php
include('../db.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];
}

$sql_delete = "DELETE FROM user_temp WHERE token=?";
$stmt = $db->prepare($sql_delete);
$stmt->bind_param("s", $token);
$stmt->execute();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=============== BOXICONS ===============-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="icon" href="assets/img/logo.png" type="images" />

    <title>Failed</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 0;
            margin: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .modal__content {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .modal__img {
            width: 80px;
            margin-bottom: 20px;
        }

        .modal__title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 0 0 10px;
        }

        .modal__description {
            font-size: 16px;
            color: #777;
            margin: 0 0 20px;
        }

        .modal__button {
            padding: 10px 20px;
            background-color: #0072C6;
            color: #fff;
            font-weight: bold;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .modal__button:hover {
            background-color: #005fba;
        }

        .modal__button-width {
            width: 100%;
        }

        .modal__close {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #777;
            font-size: 24px;
            cursor: pointer;
        }

        .modal__close:hover {
            color: #333;
        }
    </style>
</head>

<body>
    <section class="modal container">
        <div class="modal__content">
            <a href="http://localhost:8080/tarasabay-localhost/index.php">
                <div class="modal__close close-modal" title="Close">
                    <i class='bx bx-x'></i>
                </div>
            </a>
            <img src="assets/img/notif-logo.png" alt="" class="modal__img">
            <h1 class="modal__title">Expired!</h1>
            <p class="modal__description">Invalid verification link or the link has expired.</p>

            <a href="http://localhost:8080/tarasabay-localhost/index.php">
                <button class="modal__button modal__button-width">
                    Sign Up
                </button>
            </a>
        </div>
    </section>

    <!--=============== MAIN JS ===============-->
    <script src="assets/js/main.js"></script>
</body>

</html>