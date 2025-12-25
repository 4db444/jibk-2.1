<?php
    include "../../conf.php";
    include BASE_PATH . "/controllers/UserController.php";
    session_start();

    $error = $_SESSION["error"] ?? [];
    unset($_SESSION["error"]);

    if ($_SERVER["REQUEST_METHODE"] = "POST"){
        $email = trim($_POST["email"]);
        $password = $_POST["password"];

        $result = UserController::login($email, $password);

        if(!$result["success"]){
            $_SESSION["error"] = $result["error"];
            header("location: " . BASE_URL . "/views/auth/login.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <?php include "../../components/header.php" ?>

    <!-- Page Container -->
    <div class="flex justify-center items-center mt-24 px-4">
        <div class="bg-white w-full max-w-md p-8 rounded-xl shadow-lg">

            <h1 class="text-3xl font-bold text-center text-green-600 mb-6">
                OTP Authentication
            </h1>

            <form action="<?= BASE_URL ?>/endpoints/auth/otp.php" method="post" class="flex flex-col gap-4">

                <!-- OtP -->
                <div>
                    <label class="block font-medium mb-1">Check your email </label>
                    <input
                        type="text"
                        name="otp"
                        placeholder="OTP password"
                        required
                        class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none"
                    >
                </div>

                <input type="hidden" name="user_id" value="<?= $result["user_id"] ?>" />

                <!-- Submit -->
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold mt-2 transition"
                >
                    Submit
                </button>

                <?php if (!empty($error)): ?>
                    <p class="text-red-500 text-sm mt-1 text-center">
                        <?= $error ?>
                    </p>
                <?php endif; ?>

                <!-- Footer -->
                <p class="text-center text-gray-600 text-sm mt-4">
                    Try again ?
                    <a href="<?= BASE_URL ?>/views/auth/login.php" class="text-green-600 hover:underline">
                        Log In
                    </a>
                </p>

            </form>
        </div>
    </div>

</body>
</html>

