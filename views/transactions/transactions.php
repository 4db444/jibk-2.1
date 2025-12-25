<?php
    include "../../conf.php";
    session_start();
    if (!isset($_SESSION["user"])){
        header("location: " . BASE_URL . "/views/auth/login.php");
        die();
    }
    include BASE_PATH . "/controllers/TransactionController.php";
    include BASE_PATH . "/controllers/CardController.php";

    $cards = CardController::GetAllUserCards($_SESSION["user"]["id"]);

    $incomes_categories = TransactionController::GetCategegories("incomes");
    $expenses_categories = TransactionController::GetCategegories("expenses");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <title>Transactions</title>
</head>

<body class="bg-gray-100 min-h-screen">
    <?php include BASE_PATH . "/components/header.php" ?>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 bg-black/50 flex justify-center items-center hidden">
        <form action="<?= BASE_URL ?>/endpoints/transactions/addTransaction.php" method="post"
              class="bg-white w-[400px] p-6 rounded-lg shadow-xl flex flex-col gap-4">

            <h1 class="text-2xl font-semibold text-green-600 text-center">Add Transaction</h1>

            <div>
                <label class="block font-medium mb-1" for="title">Title</label>
                <input type="text" name="title" id="title" required
                       class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <div>
                <label class="block font-medium mb-1" for="amount">Amount</label>
                <input type="number" name="amount" id="amount" required
                       class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <div>
                <label class="block font-medium mb-1" for="description">Description</label>
                <textarea name="description" id="description"
                          class="w-full border border-gray-300 px-3 py-2 rounded-lg resize-none focus:ring-2 focus:ring-green-500 outline-none"></textarea>
            </div>

            <div>
                <label class="block font-medium mb-1" for="date">Date</label>
                <input type="date" name="date" id="date"
                       class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <div>
                <label class="block font-medium mb-1" for="card">Card</label>
                <select name="card_id" id="card" required
                        class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
                    <option value="" disabled selected>Select transaction card</option>
                    <?php foreach($cards as $card): ?>
                        <option value="<?= $card["id"] ?>" class="capitalize"><?= $card["bank"] ?> - <?= $card["type"] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1" for="type">Type</label>
                <select name="type" id="type" required
                        class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
                    <option value="" disabled selected>Select transaction type</option>
                    <option value="expenses">Expense</option>
                    <option value="incomes">Income</option>
                </select>
            </div>

            <div id="categories-container" class="hidden">
                <label class="block font-medium mb-1" for="category_id">Categories</label>
                <select name="category_id" id="category_id"
                        class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
                        <option value="" disabled selected>Select your transaction category</option>
                </select>
            </div>

            <div class="flex items-center gap-3">
                <input
                    type="checkbox"
                    id="is_reccuring"
                    name="is_reccuring"
                    class="w-5 h-5 rounded border-gray-300 text-green-600"
                >
                <label for="is_reccuring" class="font-medium select-none">
                    Set to monthly transaction
                </label>
            </div>

            <button type="submit" class="bg-green-600 text-white py-2 rounded-lg font-semibold hover:bg-green-700">Submit</button>
        </form>
    </div>

    <!-- Page Header -->
    <div class="max-w-7xl mx-auto p-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold">Transactions</h1>

        <button id="add-modal-btn"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg font-semibold shadow">
            <i class="fa-solid fa-plus mr-2"></i>Add Transaction
        </button>
    </div>

    <!-- Table Container -->
    <div class="max-w-7xl mx-auto p-6 bg-white shadow rounded-lg">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-300 text-left">
                    <th class="py-3 px-2 font-semibold">Title</th>
                    <th class="py-3 px-2 font-semibold">Amount</th>
                    <th class="py-3 px-2 font-semibold">Type</th>
                    <th class="py-3 px-2 font-semibold">Date</th>
                    <th class="py-3 px-2 font-semibold">Description</th>
                    <th class="py-3 px-2 font-semibold">Bank</th>
                    <th class="py-3 px-2 font-semibold">Type</th>
                    <th class="py-3 px-2 font-semibold">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php
                    $transactions = TransactionController::ShowAllTransactions();

                    foreach ($transactions as $transaction) {

                        $isExpense = $transaction["table"] === "expenses";
                        $color = $isExpense ? "red" : "green";
                        $icon = $isExpense ? "fa-arrow-up" : "fa-arrow-down";

                        echo "
                        <tr class='border-b border-gray-200 hover:bg-gray-50'>
                            <td class='py-3 px-2'>{$transaction["title"]}</td>
                            <td class='py-3 px-2'>{$transaction["amount"]}</td>

                            <td class='py-3 px-2'>
                                <span class='w-7 h-7 rounded-full border-2 border-$color-600 text-$color-600 flex justify-center items-center'>
                                    <i class='fa-solid $icon'></i>
                                </span>
                            </td>

                            <td class='py-3 px-2'>{$transaction["date"]}</td>
                            <td class='py-3 px-2'>{$transaction["description"]}</td>
                            <td class='py-3 px-2 uppercase'>{$transaction["bank"]}</td>
                            <td class='py-3 px-2 uppercase'>{$transaction["type"]}</td>

                            <td class='py-3 px-2 flex gap-2'>

                                <form action=\"./editTransaction.php\" method=\"post\">
                                    <input type='hidden' name='id' value='{$transaction['id']}'>
                                    <input type='hidden' name='table' value='{$transaction['table']}'>
                                    <button type='submit'
                                        class='bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-sm'>
                                        Update
                                    </button>
                                </form>

                                <form action=\"../../endpoints/transactions/deleteTransaction.php\" method=\"post\">
                                    <input type='hidden' name='id' value='{$transaction['id']}'>
                                    <input type='hidden' name='table' value='{$transaction['table']}'>
                                    <button type='submit'
                                        class='bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm'>
                                        Delete
                                    </button>
                                </form>

                            </td>
                        </tr>
                        ";
                    }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        const modal = document.getElementById("modal");
        const btn = document.getElementById("add-modal-btn");
        const typeSelect = document.getElementById("type");
        const categoriesContainer = document.getElementById("categories-container");
        const categorySelect = document.getElementById("category_id");
        const expensesCategories = <?= json_encode($expenses_categories) ?>;
        const incomesCategories = <?= json_encode($incomes_categories) ?>;

        typeSelect.addEventListener("change", e => {
            categoriesContainer.classList.remove("hidden");
            categorySelect.innerHTML = `<option value="" disabled selected>Select your transaction category</option>`;
            if (typeSelect.value === "incomes"){
                incomesCategories.map (cat => {
                    categorySelect.innerHTML += `
                        <option value="${cat.id}">${cat.name}</option>
                    `
                })
            }else {
                expensesCategories.map (cat => {
                    categorySelect.innerHTML += `
                        <option value="${cat.id}">${cat.name}</option>
                    `
                })
            }
        })

        btn.addEventListener("click", () => {
            modal.classList.remove("hidden");
        });

        modal.addEventListener("click", e => {
            if (e.target.id === "modal") modal.classList.add("hidden");
        });


    </script>

</body>
</html>
