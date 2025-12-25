<?php
    include "../../conf.php";
    session_start();

    if(!empty($_SESSION["user"])) header ("location: " . BASE_URL . "/views/transactions/transactions.php");

    $error = $_SESSION["error"] ?? [];

    unset($_SESSION["error"]);
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
                Login
            </h1>

            <form action="<?= BASE_URL ?>/endpoints/auth/login.php" method="post" class="flex flex-col gap-4">

                <!-- Username -->
                <div>
                    <label class="block font-medium mb-1">Email</label>
                    <input
                        type="text"
                        name="email"
                        placeholder="Enter email"
                        required
                        class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none"
                    >
                </div>

                <!-- Password -->
                <div>
                    <label class="block font-medium mb-1">Password</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Enter password"
                        required
                        class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none"
                    >
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold mt-2 transition"
                >
                    Login
                </button>

                <?php if (!empty($error)): ?>
                    <p class="text-red-500 text-sm mt-1 text-center">
                        <?= $error ?>
                    </p>
                <?php endif; ?>

                <!-- Footer -->
                <p class="text-center text-gray-600 text-sm mt-4">
                    Don’t have an account?
                    <a href="<?= BASE_URL ?>/views/auth/signup.php" class="text-green-600 hover:underline">
                        Sign up
                    </a>
                </p>

            </form>
        </div>
    </div>

</body>
</html>
