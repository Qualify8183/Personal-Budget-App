<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, category_id, description)
                           VALUES (:user_id, :type, :amount, :category_id, :description)");
    $stmt->execute([
        'user_id' => $user_id,
        'type' => $type,
        'amount' => $amount,
        'category_id' => $category_id,
        'description' => $description
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
</head>

<body class="bg-light">
    <div class="container my-5">
        <h3>Add Transaction</h3>
        <form method="POST">
            <div class="mb-3">
                <label for="type" class="form-label">Transaction Type</label>
                <select class="form-select" name="type" id="type" required>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" name="amount" class="form-control" id="amount" required>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select name="category_id" class="form-select" id="category_id" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" name="description" class="form-control" id="description">
            </div>
            <button type="submit" class="btn btn-primary">Add Transaction</button>
        </form>
        <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>

</html>