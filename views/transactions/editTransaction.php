<?php
    session_start();
    include "../../conf.php";
    include BASE_PATH . "/controllers/CardController.php";
    include BASE_PATH . "/controllers/TransactionController.php";

    $transaction = TransactionController::ShowTransaction($_POST["table"], $_POST["id"])->fetch(PDO::FETCH_ASSOC);
    $categories = TransactionController::GetCategegories($_POST["table"]);
    $cards = CardController::GetAllUserCards($_SESSION["user"]["id"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Update Transaction</title>
</head>

<body class="bg-gray-100 min-h-screen">
    <?php include BASE_PATH . "/components/header.php"; ?>

    <div class="max-w-7xl mx-auto p-6">

        <form action="../../endpoints/transactions/updateTransaction.php" method="post"
              class="bg-white w-full max-w-md p-6 mx-auto mt-10 rounded-lg shadow-lg flex flex-col gap-4">

            <h1 class="text-center text-2xl font-semibold text-green-600 mb-4 capitalize">
                Update <?= htmlspecialchars($_POST["table"]) ?>
            </h1>

            <!-- Title -->
            <div>
                <label for="title" class="block font-medium mb-1 capitalize">Title</label>
                <input type="text" name="title" id="title" required
                       value="<?= htmlspecialchars($transaction["title"]) ?>"
                       class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <!-- Amount -->
            <div>
                <label for="amount" class="block font-medium mb-1 capitalize">Amount</label>
                <input type="number" name="amount" id="amount" required
                       value="<?= htmlspecialchars($transaction["amount"]) ?>"
                       class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block font-medium mb-1 capitalize">Description</label>
                <textarea name="description" id="description"
                          class="w-full border border-gray-300 px-3 py-2 rounded-lg resize-none h-24 focus:ring-2 focus:ring-green-500 outline-none"><?= htmlspecialchars($transaction["description"]) ?></textarea>
            </div>

            <!-- Date -->
            <div>
                <label for="date" class="block font-medium mb-1 capitalize">Date</label>
                <input type="date" name="date" id="date"
                       value="<?= htmlspecialchars($transaction["date"]) ?>"
                       class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <div>
                <label class="block font-medium mb-1" for="card">Card</label>
                <select name="card_id" id="card" required
                        class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
                    <?php foreach($cards as $card): ?>
                        <option 
                            value="<?= $card["id"] ?>" 
                            <?= $card["id"] === $transaction["card_id"] ? "selected " : ""?> 
                            class="capitalize">
                                <?= $card["bank"] ?> - <?= $card["type"] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="categories-container">
                <label class="block font-medium mb-1" for="category_id">Categories</label>
                <select name="category_id" id="category_id"
                        class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
                        <option value="" disabled <?= empty($transaction["category_id"]) ? "selected" : "" ?>>Select your transaction category</option>
                        <?php foreach($categories as $category): ?>
                            <option 
                                value="<?= $category["id"] ?>"
                                <?= $category["id"] === $transaction["category_id"] ? "selected " : ""?>     
                            >
                                <?= $category["name"] ?>
                            </option>
                        <?php endforeach; ?>
                </select>
            </div>

            <!-- Hidden fields -->
            <input type="hidden" name="id" value="<?= $transaction["id"] ?>">
            <input type="hidden" name="type" value="<?= htmlspecialchars($_POST["table"]) ?>">

            <!-- Submit -->
            <button type="submit"
                    class="mt-4 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg">
                Update
            </button>

        </form>

    </div>

</body>
</html>
