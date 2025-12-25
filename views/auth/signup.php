<?php
    include "../../conf.php";

    session_start();

    if(!empty($_SESSION["user"])) header ("location: " . BASE_URL . "/views/transactions/transactions.php");

    $errors = $_SESSION["errors"] ?? [];
    $old_values = $_SESSION["old_values"] ?? [];

    unset($_SESSION["errors"]);
    unset($_SESSION["old_values"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <?php include "../../components/header.php" ?>

    <!-- Page Container -->
    <div class="flex justify-center items-center mt-20 px-4">
        <div class="bg-white w-full max-w-md p-8 rounded-xl shadow-lg">

            <h1 class="text-3xl font-bold text-center text-green-600 mb-6">
                Create Account
            </h1>

            <form action="<?= BASE_URL ?>/endpoints/auth/signup.php" method="post" class="flex flex-col gap-4">

                <!-- Username -->
                <div>
                    <label class="block font-medium mb-1">Username</label>
                    <input
                        type="text"
                        name="username"
                        placeholder="Enter username"
                        required
                        value="<?= $old_values["username"] ?? "" ?>"
                        class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none"
                    >
                    <?php if (!empty($errors["username"])): ?>
                        <p class="text-red-500 text-sm mt-1">
                            <?= $errors["username"] ?>
                        </p>
                    <?php endif; ?>
                </div>
                <!-- email -->
                <div>
                    <label class="block font-medium mb-1">Email</label>
                    <input
                        type="text"
                        name="email"
                        placeholder="Enter email"
                        required
                        value="<?= $old_values["email"] ?? "" ?>"
                        class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none"
                    >
                    <?php if (!empty($errors["email"])): ?>
                        <p class="text-red-500 text-sm mt-1">
                            <?= $errors["email"] ?>
                        </p>
                    <?php endif; ?>
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
                    <?php if (!empty($errors["password"])): ?>
                        <p class="text-red-500 text-sm mt-1">
                            <?= $errors["password"] ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label class="block font-medium mb-1">Confirm Password</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        placeholder="Confirm password"
                        required
                        class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none"
                    >
                    <?php if (!empty($errors["password_confirmation"])): ?>
                        <p class="text-red-500 text-sm mt-1">
                            <?= $errors["password_confirmation"] ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Bank Name -->
                <div>
                    <label class="block font-medium mb-1">Bank Name</label>
                    <input
                        type="text"
                        name="bank"
                        placeholder="Enter bank name"
                        required
                        value="<?= $old_values["bank"] ?? "" ?>"
                        class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none"
                    >
                    <?php if (!empty($errors["bank"])): ?>
                        <p class="text-red-500 text-sm mt-1">
                            <?= $errors["bank"] ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Card Type -->
                <div>
                    <label class="block font-medium mb-1">Card Type</label>
                    <select
                        name="type"
                        required
                        class="w-full border border-gray-300 px-3 py-2 rounded-lg bg-white focus:ring-2 focus:ring-green-500 outline-none"
                    >
                        <option value="" disabled selected>Select your card type</option>
                        <option value="mastercard" <?= ($old_values["type"] ?? "") === "mastercard" ? "selected" : "" ?>>
                            Mastercard
                        </option>
                        <option value="visa" <?= ($old_values["type"] ?? "") === "visa" ? "selected" : "" ?>>
                            Visa
                        </option>
                    </select>
                    <?php if (!empty($errors["type"])): ?>
                        <p class="text-red-500 text-sm mt-1">
                            <?= $errors["type"] ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Initial Balance -->
                <div>
                    <label class="block font-medium mb-1">Initial Balance</label>
                    <input
                        type="number"
                        name="initial_balance"
                        placeholder="Enter initial balance"
                        required
                        value="<?= $old_values["initial_balance"] ?? "" ?>"
                        class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none"
                    >
                    <?php if (!empty($errors["initial_balance"])): ?>
                        <p class="text-red-500 text-sm mt-1">
                            <?= $errors["initial_balance"] ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold mt-2 transition"
                >
                    Sign Up
                </button>

                <p class="text-center text-gray-600 text-sm mt-4">
                    Already have an account?
                    <a href="<?= BASE_URL ?>/views/auth/login.php" class="text-green-600 hover:underline">
                        Login
                    </a>
                </p>

            </form>
        </div>
    </div>

</body>
</html>
