<?php
    // session_start();
?>

<header class="flex justify-between items-center p-3 w-full text-white bg-blue-600">
    <h1 class="text-4xl font-semibold">JIBK</h1>
    <nav>
        <ul class="flex gap-4">
            <?php if(!empty($_SESSION["user"])): ?>
                <li><a href="<?= BASE_URL ?>/views/card/index.php">Cards</a></li>
                <li><a href="<?= BASE_URL ?>/views/category/index.php">Limits</a></li>
                <li><a href="<?= BASE_URL ?>/views/transactions/transactions.php">Transactions</a></li>
                <li><a href="<?= BASE_URL ?>/views/transactions/dashboard.php">Dashboard</a></li>
                <li>
                    <form action="<?= BASE_URL ?>/endpoints/auth/logout.php" method="post">
                        <button type="submit">Logout</button> 
                    </form>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</header>