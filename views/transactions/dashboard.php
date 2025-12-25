<?php
    include "../../conf.php";
    session_start();
    if (!isset($_SESSION["user"])){
        header("location: " . BASE_URL . "/views/auth/login.php");
        die();
    }
    include "../../controllers/TransactionController.php";

    $total_incomes = TransactionController::GetTotoalTransactions("incomes", $_SESSION["user"]["id"]) ?? 0;
    $total_expenses = TransactionController::GetTotoalTransactions("expenses", $_SESSION["user"]["id"]) ?? 0;

    $total_expenses_per_month = TransactionController::GetTotoalTransactionsPerMonth("expenses", $_SESSION["user"]["id"]) ?? [];
    $total_incomes_per_month = TransactionController::GetTotoalTransactionsPerMonth("incomes", $_SESSION["user"]["id"]) ?? [];

    $current_month_expenses = TransactionController::GetCurrentMonthTransactions("expenses", $_SESSION["user"]["id"]) ?? 0;
    $current_month_incomes = TransactionController::GetCurrentMonthTransactions("incomes", $_SESSION["user"]["id"]) ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <?php include "../../components/header.php" ?>

    <div class="max-w-7xl mx-auto p-6">

        <h1 class="text-3xl font-bold mb-10">Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-14">

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-gray-600 text-sm">Net Balance</h3>
                <p class="text-3xl font-bold text-blue-600 mt-2">
                    <?= $total_incomes - $total_expenses ?>
                </p>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-gray-600 text-sm">Total Incomes</h3>
                <p class="text-3xl font-bold text-green-600 mt-2">
                    <?= $total_incomes ?>
                </p>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-gray-600 text-sm">Total Expenses</h3>
                <p class="text-3xl font-bold text-red-600 mt-2">
                    <?= $total_expenses ?>
                </p>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <div class="bg-white shadow rounded-lg p-6 h-[550px]">
                <h2 class="text-xl font-semibold mb-4">Monthly Overview</h2>
                <canvas id="bar-chart" class="w-full h-full"></canvas>
            </div>
            <div class="bg-white shadow rounded-lg p-6 h-[500px]">
                <h2 class="text-xl font-semibold mb-4">Current Month</h2>
                <canvas id="pie-chart" class="w-full h-full"></canvas>
            </div>

        </div>
    </div>

    <script>
        const barContext = document.getElementById('bar-chart').getContext('2d');
        const pieContext = document.getElementById('pie-chart').getContext('2d');

        const totalExpensesPerMonth = <?= json_encode($total_expenses_per_month) ?>;
        const totalIncomesPerMonth = <?= json_encode($total_incomes_per_month) ?>;

        new Chart(barContext, {
            type: 'bar',
            data: {
                labels: totalExpensesPerMonth.map(e => e.month),
                datasets: [
                    {
                        label: 'Expenses',
                        backgroundColor: '#ef4444',
                        data: totalExpensesPerMonth.map(e => e.total),
                        borderWidth: 1
                    },
                    {
                        label: 'Incomes',
                        backgroundColor: '#22c55e',
                        data: totalIncomesPerMonth.map(e => e.total),
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,

                layout: {
                    padding: 20
                },

                plugins: {
                    legend: {
                        position: "bottom",
                        labels: { padding: 20 }
                    }
                },

                scales: {
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 0,
                            autoSkip: false
                        }
                    },
                    y: { beginAtZero: true }
                }
            }
        });

        new Chart(pieContext, {
            type: 'pie',
            data: {
                labels: ["Expenses", "Incomes"],
                datasets: [{
                    data: [<?= $current_month_expenses ?>, <?= $current_month_incomes ?>],
                    backgroundColor: ["#ef4444", "#22c55e"]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,

                layout: {
                    padding: 20
                },

                plugins: {
                    legend: {
                        position: "bottom",
                        labels: {
                            padding: 15,
                            font: { size: 14 }
                        }
                    }
                }
            }
        });
    </script>

</body>
</html>
