<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT username FROM users WHERE id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$username_logged = $user['username'];


$budgetStmt = $pdo->prepare("SELECT * FROM budgets WHERE user_id = :user_id ORDER BY month DESC LIMIT 1");
$budgetStmt->execute(['user_id' => $user_id]);
$budget = $budgetStmt->fetch();

$month_start = $budget ? $budget['month'] : date('Y-m-01');

$transactionsStmt = $pdo->prepare("SELECT t.*, c.name AS category_name FROM transactions t
                                  JOIN categories c ON t.category_id = c.id
                                  WHERE t.user_id = :user_id AND t.transaction_date >= :month_start
                                  ORDER BY t.transaction_date DESC");
$transactionsStmt->execute(['user_id' => $user_id, 'month_start' => $month_start]);
$transactions = $transactionsStmt->fetchAll();

$totalIncome = 0;
$totalExpense = 0;
foreach ($transactions as $transaction) {
    if ($transaction['type'] == 'income') {
        $totalIncome += $transaction['amount'];
    } else {
        $totalExpense += $transaction['amount'];
    }
}

$remainingBudget = ($budget['amount'] ?? 0) + $totalIncome - $totalExpense;

$expenseByCategory = [];
foreach ($transactions as $transaction) {
    if ($transaction['type'] == 'expense') {
        $category = $transaction['category_name'];
        if (!isset($expenseByCategory[$category])) {
            $expenseByCategory[$category] = 0;
        }
        $expenseByCategory[$category] += $transaction['amount'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --bg-color: #f8f9fa;
            --text-color: #212529;
            --card-bg: #ffffff;
            --border-color: #dee2e6;
            --navbar-bg: #ffffff;
            --navbar-text: #000000;
            --primary: #007bff;
            --secondary: #6c757d;
        }

        [data-theme="dark"] {
            --bg-color: #0d0d0d;
            --text-color: #e9ecef;
            --card-bg: #1e1e1e;
            --border-color: #495057;
            --navbar-bg: #212529;
            --navbar-text: #ffffff;
            --primary: #0d6efd;
            --secondary: #6c757d;
        }

        body {
            background: var(--bg-color);
            color: var(--text-color);
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        .navbar {
            background-color: var(--navbar-bg) !important;
            color: var(--navbar-text) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand,
        .navbar-nav .nav-link {
            color: var(--navbar-text) !important;
        }

        .card {
            background-color: var(--card-bg);
            border-color: var(--border-color);
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .table {
            color: var(--text-color);
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead th {
            background-color: var(--primary);
            color: white;
            border: none;
        }

        .btn {
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .income-amount,
        .income-type {
            color: #198754 !important;
        }

        .expense-amount,
        .expense-type {
            color: #dc3545 !important;
        }

        [data-theme="dark"] .income-amount,
        [data-theme="dark"] .income-type {
            color: #198754 !important;
        }

        [data-theme="dark"] .expense-amount,
        [data-theme="dark"] .expense-type {
            color: #dc3545 !important;
        }

        .table-striped tbody tr {
            background-color: var(--card-bg) !important;
        }
    </style>
</head>

<body class="fade-in" data-theme="light">
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fas fa-wallet"></i> Personal Budget</a>
            <div class="d-flex align-items-center">
                <button id="theme-toggle" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-moon"></i> Dark Mode
                </button>
                <a href="add_transaction.php" class="btn btn-primary me-2">
                    <i class="fas fa-plus"></i> Add Transaction
                </a>
                <div class="dropdown me-2">
                    <a href="#" class="btn btn-dark dropdown-toggle" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle"></i> <?= htmlspecialchars($username_logged) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div class="container">
        <h3 class="mb-4"><i class="fas fa-chart-line"></i> Dashboard</h3>
        <div class="mb-4">
            <p><strong>Current Month Budget: </strong> €<?php echo number_format($budget['amount'] ?? 0, 2); ?></p>
            <p><strong>Total Earnings: </strong> <span class="income-amount">€<?php echo number_format($totalIncome, 2); ?></span></p>
            <p><strong>Total Spending: </strong> <span class="expense-amount">€<?php echo number_format($totalExpense, 2); ?></span></p>
            <p><strong>Budget Left: </strong> €<?php echo number_format($remainingBudget, 2); ?></p>
        </div>

        <h4><i class="fas fa-list"></i> Transactions</h4>
        <table class="table table mb-4">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction): ?>
                    <tr class="<?php echo $transaction['type'] == 'income' ? 'income-type' : 'expense-type'; ?>">
                        <td><?php echo date('Y-m-d H:i', strtotime($transaction['transaction_date'])); ?></td>
                        <td><?php echo ucfirst($transaction['type']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['category_name']); ?></td>
                        <td><?php echo number_format($transaction['amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($transaction['description']); ?></td>
                        <td>
                            <a href="edit_transaction.php?id=<?php echo $transaction['id']; ?>" class="btn btn-sm btn-warning me-1">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="delete_transaction.php?id=<?php echo $transaction['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this transaction?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-pie"></i> Expenses by Category</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="expenseChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-bar"></i> Income vs Expenses</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="budgetChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

    <script>
        const themeToggleBtn = document.getElementById('theme-toggle');
        const currentTheme = localStorage.getItem('theme') || 'light';
        document.body.setAttribute('data-theme', currentTheme);
        updateToggleIcon(currentTheme);

        themeToggleBtn.addEventListener('click', () => {
            let theme = document.body.getAttribute('data-theme');
            if (theme === 'light') {
                theme = 'dark';
            } else {
                theme = 'light';
            }
            document.body.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            updateToggleIcon(theme);
        });

        function updateToggleIcon(theme) {
            if (theme === 'dark') {
                themeToggleBtn.innerHTML = '<i class="fas fa-sun"></i> Light Mode';
            } else {
                themeToggleBtn.innerHTML = '<i class="fas fa-moon"></i> Dark Mode';
            }
        }

        const expenseCtx = document.getElementById('expenseChart').getContext('2d');
        const budgetCtx = document.getElementById('budgetChart').getContext('2d');

        const expenseData = {
            labels: <?php echo json_encode(array_keys($expenseByCategory)); ?>,
            datasets: [{
                label: 'Expenses',
                data: <?php echo json_encode(array_values($expenseByCategory)); ?>,
                backgroundColor: [
                    '#dc3545', '#fd7e14', '#ffc107', '#198754', '#0d6efd', '#6f42c1', '#20c997', '#0dcaf0'
                ],
                borderWidth: 1
            }]
        };

        const isDark = currentTheme === 'dark';
        const budgetData = {
            labels: ['Income', 'Expenses'],
            datasets: [{
                label: 'Amount',
                data: [<?php echo $totalIncome; ?>, <?php echo $totalExpense; ?>],
                backgroundColor: isDark ? ['#e9ecef', '#e9ecef'] : ['#198754', '#dc3545'],
                borderWidth: 1
            }]
        };

        const expenseChart = new Chart(expenseCtx, {
            type: 'pie',
            data: expenseData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });

        const budgetChart = new Chart(budgetCtx, {
            type: 'bar',
            data: budgetData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    </script>
</body>

</html>