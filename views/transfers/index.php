<?php
    include "../../conf.php";
    session_start();
    if (!isset($_SESSION["user"])){
        header("location: " . BASE_URL . "/views/auth/login.php");
        die();
    }

    // include BASE_PATH . "/controllers/CardController.php";

    // $errors = $_SESSION["errors"] ?? [];
    // unset($_SESSION["errors"]);
    // $cards = CardController::GetAllUserCards($_SESSION["user"]["id"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <title>Transfers</title>
</head>

<body class="bg-gray-100 min-h-screen">
    <?php include BASE_PATH . "/components/header.php" ?>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 bg-black/50 flex justify-center items-center hidden">
        <form action="<?= BASE_URL ?>/endpoints/card/store.php" method="post"
              class="bg-white w-[400px] p-6 rounded-lg shadow-xl flex flex-col gap-4">

            <h1 class="text-2xl font-semibold text-green-600 text-center">Add Transfer</h1>

            <div>
                <label class="block font-medium mb-1" for="title">bank</label>
                <input type="text" name="bank" id="bank" required
                       class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <div>
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
            </div>
                    
            <div>
                <label class="block font-medium mb-1" for="description">Initial balance</label>
                <input type="number" name="initial_balance" id="initial_balance"
                        class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <button type="submit" class="bg-green-600 text-white py-2 rounded-lg font-semibold hover:bg-green-700">Submit</button>
        </form>
    </div>

    <!-- Page Header -->
    <div class="max-w-7xl mx-auto p-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold">Cards</h1>

        <button id="add-modal-btn"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg font-semibold shadow">
            <i class="fa-solid fa-plus mr-2"></i>Add Card
        </button>
    </div>

    <!-- Table Container -->
    <div class="max-w-7xl mx-auto p-6 bg-white shadow rounded-lg">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-300 text-left">
                    <th class="py-3 px-2 font-semibold">Bank</th>
                    <th class="py-3 px-2 font-semibold">Type</th>
                    <th class="py-3 px-2 font-semibold">main</th>
                    <th class="py-3 px-2 font-semibold">Current Balance</th>
                    <th class="py-3 px-2 font-semibold">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php
                    foreach ($cards as $card) {
                        $total_expenses = CardController::GetTotalExpenses($card["id"]);
                        $total_incomes = CardController::GetTotalIncomes($card["id"]);
                        $current_balance = $total_incomes - $total_expenses;
                        echo "
                        <tr class='border-b border-gray-200 hover:bg-gray-50'>
                            <td class='py-3 px-2 capitalize'>{$card["bank"]}</td>
                            <td class='py-3 px-2 capitalize'>{$card["type"]}</td>
                            <td class='py-3 px-2'>main</td>
                            <td class='py-3 px-2'>{$current_balance}</td>

                            <td class='py-3 px-2 flex gap-2'>
                                <form action=\"../../endpoints/card/delete.php\" method=\"post\">
                                    <input type='hidden' name='id' value='{$card['id']}'>
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

        btn.addEventListener("click", () => {
            modal.classList.remove("hidden");
        });

        modal.addEventListener("click", e => {
            if (e.target.id === "modal") modal.classList.add("hidden");
        });


    </script>

</body>
</html>
