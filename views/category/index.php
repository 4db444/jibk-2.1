<?php
    session_start();
    include "../../conf.php";
    include BASE_PATH . "/controllers/TransactionController.php";

    $expenses_categories = TransactionController::GetExpensesCategories();
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

        <!-- Table Container -->
    <div class="max-w-7xl mx-auto p-6 bg-white shadow rounded-lg">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-300 text-left">
                    <th class="py-3 px-2 font-semibold">Expense category</th>
                    <th class="py-3 px-2 font-semibold">Limit</th>
                    <th class="py-3 px-2 font-semibold">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php
                    foreach ($expenses_categories as $expense_category) {
                        $expense_category_limit = TransactionController::GetExpenseCategorieLimit($expense_category["id"], $_SESSION["user"]["id"]);
                        echo "
                        <tr class='border-b border-gray-200 hover:bg-gray-50'>
                            <td class='py-3 px-2 capitalize'>{$expense_category["name"]}</td>
                            <td class='py-3 px-2 capitalize'>" . ($expense_category_limit ?? 'none') . "</td>

                            <td class='py-3 px-2 flex gap-2'>
                                <form action=\"./edit.php\" method=\"post\">
                                    <input type='hidden' name='id' value='{$expense_category['id']}'>
                                    <input type='hidden' name='name' value='{$expense_category['name']}'>
                                    <input type='hidden' name='limit' value='{$expense_category_limit}'>
                                    <button type='submit'
                                        class='bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-sm'>
                                        Update Limit
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
</body>
</html>
