<?php
    session_start();
    include "../../conf.php";
    
    if (!isset($_SESSION["user_id"])){
        header("location: " . BASE_URL . "/views/auth/login.php");
        die();
    }
    require_once BASE_PATH . "/controllers/Transfer.php";
    require_once BASE_PATH . "/controllers/User.php";

    $transfers = Transfer::GetTransfers($_SESSION["user_id"]);
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
        <form action="<?= BASE_URL ?>/endpoints/transfers/store.php" method="post"
              class="bg-white w-[400px] p-6 rounded-lg shadow-xl flex flex-col gap-4">

            <h1 class="text-2xl font-semibold text-green-600 text-center">Add Transfer</h1>

            <div>
                <label class="block font-medium mb-1" for="title">Receiver Email</label>
                <input type="email" name="receiver_email" id="receiver_email" required
                       class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
            </div>

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

            <button type="submit" class="bg-green-600 text-white py-2 rounded-lg font-semibold hover:bg-green-700">Submit</button>
        </form>
    </div>

    <!-- Page Header -->
    <div class="max-w-7xl mx-auto p-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold">Transfers</h1>

        <button id="add-modal-btn"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg font-semibold shadow">
            <i class="fa-solid fa-plus mr-2"></i>Add Transfer
        </button>
    </div>

    <!-- Table Container -->
    <div class="max-w-7xl mx-auto p-6 bg-white shadow rounded-lg">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-300 text-left">
                    <th class="py-3 px-2 font-semibold capitalize">Sender name</th>
                    <th class="py-3 px-2 font-semibold capitalize">Sender email</th>
                    <th class="py-3 px-2 font-semibold capitalize">receiver name</th>
                    <th class="py-3 px-2 font-semibold capitalize">receiver email</th>
                    <th class="py-3 px-2 font-semibold capitalize">title</th>
                    <th class="py-3 px-2 font-semibold capitalize">amount</th>
                    <th class="py-3 px-2 font-semibold capitalize">description</th>
                    <th class="py-3 px-2 font-semibold capitalize">date</th>
                </tr>
            </thead>

            <tbody>
                <?php
                    foreach ($transfers as $transfer) {
                        $sender = User::FindByCardId($transfer["card_sender_id"]);
                        $receiver = User::FindByCardId($transfer["card_receiver_id"]);
                        echo "
                        <tr class='border-b border-gray-200 hover:bg-gray-50'>
                            <td class='py-3 px-2 capitalize'>{$sender["username"]}</td>
                            <td class='py-3 px-2 capitalize'>{$sender["email"]}</td>
                            <td class='py-3 px-2 capitalize'>{$receiver["username"]}</td>
                            <td class='py-3 px-2 capitalize'>{$receiver["email"]}</td>
                            <td class='py-3 px-2 capitalize'>{$transfer["title"]}</td>
                            <td class='py-3 px-2 capitalize'>{$transfer["amount"]}</td>
                            <td class='py-3 px-2 capitalize'>{$transfer["description"]}</td>
                            <td class='py-3 px-2 capitalize'>{$transfer["date"]}</td>
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
