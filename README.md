# Personal Budget App (PBA)

A web-based personal budget tracking application built with PHP, PDO for database interaction, Bootstrap for responsive UI, and Chart.js for data visualization.

## Features

- **User Authentication**: Secure login and registration system.
- **Dashboard**: Overview of current month's budget, income, expenses, and remaining budget.
- **Transaction Management**: Add, edit, and delete transactions with categories.
- **Charts**: Pie chart for expenses by category and bar chart for income vs expenses.
- **Dark/Light Theme**: Toggle between themes for better user experience.
- **Responsive Design**: Works on desktop and mobile devices.

## Technologies Used

- **Backend**: PHP 7+ with PDO for MySQL database.
- **Frontend**: HTML, CSS, JavaScript, Bootstrap 5, Chart.js.
- **Database**: MySQL.

## Installation

1. **Prerequisites**:
   - PHP 7.0 or higher
   - MySQL database
   - Web server (e.g., Apache)

2. **Clone or Download** the project files to your web server's root directory.

3. **Database Setup**:
   - Create a MySQL database.
   - Run the `import.sql` script to create tables and insert sample data.
   - Update `db.php` with your database credentials:
     ```php
     $host = 'localhost';
     $dbname = 'your_database_name';
     $username = 'your_username';
     $password = 'your_password';
     ```

4. **File Permissions**:
   - Ensure the web server has read/write permissions for the project directory.

5. **Access the App**:
   - Open your browser and navigate to the project URL (e.g., `http://localhost/personal-budget-app/`).

## Usage

1. **Register**: Create a new account on the registration page.
2. **Login**: Use your credentials to log in.
3. **Dashboard**: View your budget summary, transactions, and charts.
4. **Add Transaction**: Click "Add Transaction" to record new income or expense.
5. **Edit/Delete**: Use the actions in the transactions table to modify or remove entries.
6. **Theme Toggle**: Switch between light and dark themes using the button in the navbar.

## Sample Data

The `import.sql` includes sample users and transactions for testing:
- Username: `testuser`, Password: `password123`
- Username: `john_doe`, Password: `password123`

## File Structure

- `index.php`: Home page
- `login.php`: Login page
- `register.php`: Registration page
- `dashboard.php`: Main dashboard
- `add_transaction.php`: Add new transaction
- `edit_transaction.php`: Edit transaction
- `delete_transaction.php`: Delete transaction
- `transactions.php`: List all transactions
- `logout.php`: Logout
- `db.php`: Database connection
- `import.sql`: Database schema and sample data

## Contributing

Feel free to fork the project and submit pull requests for improvements.

## License

This project is open-source and available under the MIT License.
