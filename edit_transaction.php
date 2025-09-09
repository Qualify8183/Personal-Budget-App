<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$transaction_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $transaction_id, 'user_id' => $user_id]);
$transaction = $stmt->fetch();

if (!$transaction) {
    header("Location: dashboard.php");
    exit();
}

$categoriesStmt = $pdo->query("SELECT * FROM categories");
$categories = $categoriesStmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $transaction_date = $_POST['transaction_date'];

    $updateStmt = $pdo->prepare("UPDATE transactions SET type = :type, amount = :amount, category_id = :category_id, description = :description, transaction_date = :transaction_date WHERE id = :id AND user_id = :user_id");
    $updateStmt->execute([
        'type' => $type,
        'amount' => $amount,
        'category_id' => $category_id,
        'description' => $description,
        'transaction_date' => $transaction_date,
        'id' => $transaction_id,
        'user_id' => $user_id
    ]);

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        [data-theme="dark"] select.form-select {
            background-color: #1e1e1e !important;
            color: #e9ecef !important;
            border-color: #495057 !important;
            caret-color: #e9ecef !important;
        }

        [data-theme="dark"] .input-group-text {
            background-color: #495057 !important;
            color: #e9ecef !important;
            border-color: #495057 !important;
        }

        body {
            background: linear-gradient(135deg, var(--bg-color) 0%, #e9ecef 100%);
            color: var(--text-color);
            transition: all 0.3s ease;
            min-height: 100vh;
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
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4" style="width: 30rem;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4"><i class="fas fa-edit"></i> Edit Transaction</h3>
                <form method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="type" class="form-label fw-bold">Transaction Type</label>
                        <div class="btn-group w-100" role="group" aria-label="Transaction Type">
                            <input type="radio" class="btn-check" name="type" id="income" value="income" autocomplete="off" <?php echo $transaction['type'] == 'income' ? 'checked' : ''; ?> required>
                            <label class="btn btn-outline-success" for="income"><i class="bi bi-plus-circle"></i> Income</label>

                            <input type="radio" class="btn-check" name="type" id="expense" value="expense" autocomplete="off" <?php echo $transaction['type'] == 'expense' ? 'checked' : ''; ?> required>
                            <label class="btn btn-outline-danger" for="expense"><i class="bi bi-dash-circle"></i> Expense</label>
                        </div>
                        <div class="invalid-feedback">
                            Please select a transaction type.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label fw-bold">Amount (€)</label>
                        <input type="number" step="0.01" name="amount" class="form-control" id="amount" value="<?php echo $transaction['amount']; ?>" required>
                        <div class="invalid-feedback">
                            Please enter a valid amount.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label fw-bold">Category</label>
                        <select name="category_id" class="form-select" id="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo $transaction['category_id'] == $category['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            Please select a category.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <input type="text" name="description" class="form-control" id="description" value="<?php echo htmlspecialchars($transaction['description']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="transaction_date" class="form-label fw-bold">Date & Time</label>
                        <div class="input-group">
                            <input type="datetime-local" name="transaction_date" class="form-control date-time-picker" id="dateTimePicker" value="<?php echo date('Y-m-d\TH:i', strtotime($transaction['transaction_date'])); ?>" required>
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        </div>
                        <div id="output" class="output mt-2"></div>
                        <div class="invalid-feedback">
                            Please select a valid date and time.
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="dashboard.php" class="btn btn-outline-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-success">Update Transaction</button>
                    </div>
                </form>
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

        document.getElementById('dateTimePicker').addEventListener('change', function() {
            const dateTimeValue = this.value;
            const outputDiv = document.getElementById('output');
        });
    </script>
</body>

</html>