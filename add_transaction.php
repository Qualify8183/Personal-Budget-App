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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $transaction_date = date('Y-m-d H:i:s');

    $userCheckStmt = $pdo->prepare("SELECT id FROM users WHERE id = :user_id");
    $userCheckStmt->execute(['user_id' => $user_id]);
    if (!$userCheckStmt->fetch()) {
        die("Error: Invalid user session. Please log in again.");
    }

    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, category_id, description, transaction_date) VALUES (:user_id, :type, :amount, :category_id, :description, :transaction_date)");
    $stmt->execute([
        'user_id' => $user_id,
        'type' => $type,
        'amount' => $amount,
        'category_id' => $category_id,
        'description' => $description,
        'transaction_date' => $transaction_date
    ]);

    header("Location: dashboard.php");
    exit();
}


$categoriesStmt = $pdo->query("SELECT * FROM categories");
$categories = $categoriesStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <style>
        :root {
            --bg-color: #f8f9fa;
            --text-color: #212529;
            --card-bg: #ffffff;
            --border-color: #dee2e6;
            --primary: #007bff;
            --secondary: #6c757d;
        }

        [data-theme="dark"] {
            --bg-color: #0d0d0d;
            --text-color: #e9ecef;
            --card-bg: #1e1e1e;
            --border-color: #495057;
            --primary: #0d6efd;
            --secondary: #6c757d;
        }

        [data-theme="dark"] input.form-control,
        [data-theme="dark"] select.form-select,
        [data-theme="dark"] textarea.form-control {
            background-color: #1e1e1e !important;
            color: #e9ecef !important;
            border-color: #495057 !important;
            caret-color: #e9ecef !important;
        }

        body {
            background: var(--bg-color);
            color: var(--text-color);
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        input,
        select,
        textarea {
            background-color: var(--card-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            background-color: var(--bg-color);
            color: var(--text-color);
            outline: none;
            box-shadow: 0 0 5px var(--primary);
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
    </style>
</head>

<body class="fade-in" data-theme="<?php echo isset($_COOKIE['theme']) ? htmlspecialchars($_COOKIE['theme']) : 'light'; ?>">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">Add New Transaction</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="type" class="form-label fw-bold">Transaction Type</label>
                                <div class="btn-group w-100" role="group" aria-label="Transaction Type">
                                    <input type="radio" class="btn-check" name="type" id="income" value="income" autocomplete="off" required>
                                    <label class="btn btn-outline-success" for="income"><i class="bi bi-plus-circle"></i> Income</label>

                                    <input type="radio" class="btn-check" name="type" id="expense" value="expense" autocomplete="off" required>
                                    <label class="btn btn-outline-danger" for="expense"><i class="bi bi-dash-circle"></i> Expense</label>
                                </div>
                                <div class="invalid-feedback">
                                    Please select a transaction type.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label fw-bold">Amount (€)</label>
                                <input type="number" step="0.01" name="amount" class="form-control mt-2" id="amount" placeholder="0.00" required>
                                <div class="invalid-feedback">
                                    Please enter a valid amount.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-bold">Category</label>
                                <select name="category_id" class="form-select" id="category_id" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">
                                    Please select a category.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Description</label>
                                <input type="text" name="description" class="form-control" id="description" placeholder="Optional description">
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="dashboard.php" class="btn btn-outline-secondary me-md-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Add Transaction</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

    <script>
        const currentTheme = localStorage.getItem('theme') || 'light';
        document.body.setAttribute('data-theme', currentTheme);


        (function() {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>

</html>