<?php
    session_start();
    include "../../conf.php";
    
    if($_SERVER["REQUEST_METHOD"] != "POST") header("location: " . BASE_URL . "/views/category");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Category limit</title>
</head>

<body class="bg-gray-100 min-h-screen">
    <?php include BASE_PATH . "/components/header.php"; ?>

    <div class="max-w-7xl mx-auto p-6">

        <form action="<?= BASE_URL ?>/endpoints/category/createLimit.php" method="post"
              class="bg-white w-full max-w-md p-6 mx-auto mt-10 rounded-lg shadow-lg flex flex-col gap-4">

            <h1 class="text-center text-2xl font-semibold text-green-600 mb-4 capitalize">
                Set Limit: <?= htmlspecialchars($_POST["name"]) ?>
            </h1>

            <!-- Amount -->
            <div>
                <label for="limit" class="block font-medium mb-1 capitalize">Limit</label>
                <input type="number" name="limit" id="limit"
                       value="<?= $_POST["limit"] ?>"
                       class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <!-- Hidden fields -->
            <input type="hidden" name="expense_category_id" value="<?= $_POST["id"] ?>">

            <!-- Submit -->
            <button type="submit"
                    class="mt-4 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg">
                Update
            </button>

        </form>

    </div>

</body>
</html>
