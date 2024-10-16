<?php
// index.php

require 'db.php'; // Include the database connection

// Initialize variables for form data and messages
$firstname = $lastname = $address = $email = $date_of_birth = $gender = $citizenship = $position_applied = $years_experience = $about = $password = '';
$successMessage = $errorMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and assign input data
    $firstname        = trim($_POST['firstname']);
    $lastname         = trim($_POST['lastname']);
    $address          = trim($_POST['address']);
    $email            = trim($_POST['email']);
    $date_of_birth    = trim($_POST['date_of_birth']);
    $gender           = trim($_POST['gender']);
    $citizenship      = trim($_POST['citizenship']);
    $position_applied = trim($_POST['position_applied']);
    $years_experience = trim($_POST['years_experience']);
    $about            = trim($_POST['about']);
    $password_input   = $_POST['password'];

    // Basic validation
    if (
        empty($firstname) || empty($lastname) || empty($address) || empty($email) ||
        empty($date_of_birth) || empty($gender) || empty($citizenship) ||
        empty($position_applied) || empty($years_experience) || empty($about) || empty($password_input)
    ) {
        $errorMessage = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format.";
    } elseif (!is_numeric($years_experience) || $years_experience < 0) {
        $errorMessage = "Please enter a valid number for years of experience.";
    } else {
        try {
            // Check if email already exists
            $checkStmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
            $checkStmt->execute([$email]);
            $count = $checkStmt->fetchColumn();

            if ($count > 0) {
                $errorMessage = "Email already exists.";
            } else {
                // Hash the password
                $hashedPassword = password_hash($password_input, PASSWORD_DEFAULT);

                // Insert the new user
                $stmt = $pdo->prepare('INSERT INTO users 
                    (firstname, lastname, address, email, date_of_birth, gender, citizenship, position_applied, years_experience, about, password) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([
                    $firstname,
                    $lastname,
                    $address,
                    $email,
                    $date_of_birth,
                    $gender,
                    $citizenship,
                    $position_applied,
                    $years_experience,
                    $about,
                    $hashedPassword
                ]);

                $successMessage = "User successfully registered!";

                // Clear form data after successful registration
                $firstname = $lastname = $address = $email = $date_of_birth = $gender = $citizenship = $position_applied = $years_experience = $about = $password = '';
            }
        } catch (PDOException $e) {
            $errorMessage = "Error: " . $e->getMessage();
        }
    }
}

// Fetch all users to display
try {
    $stmt = $pdo->query('SELECT * FROM users ORDER BY date_added DESC');
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $errorMessage = "Error fetching users: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration System</title>
    <style>
        /* Basic styling for better appearance */
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #a7c7e7;}
        form { margin-bottom: 40px; }
        input[type="text"], input[type="email"], input[type="password"], input[type="date"], select, textarea, input[type="number"] {
            width: 100%; padding: 8px; margin: 5px 0 15px 0; box-sizing: border-box;
        }
        button { padding: 10px 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; }
        .message { margin-bottom: 20px; padding: 10px; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

    <h1>Online job application</h1>

    <!-- Display Success or Error Messages -->
    <?php if ($successMessage): ?>
        <div class="message success"><?= htmlspecialchars($successMessage) ?></div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <div class="message error"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <!-- Registration Form -->
    <form method="post" action="index.php">
        <label for="firstname">First Name *</label>
        <input type="text" id="firstname" name="firstname" placeholder="First Name" value="<?= htmlspecialchars($firstname) ?>" required>

        <label for="lastname">Last Name *</label>
        <input type="text" id="lastname" name="lastname" placeholder="Last Name" value="<?= htmlspecialchars($lastname) ?>" required>

        <label for="address">Address *</label>
        <input type="text" id="address" name="address" placeholder="Address" value="<?= htmlspecialchars($address) ?>" required>

        <label for="email">Email *</label>
        <input type="email" id="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required>

        <label for="date_of_birth">Date of Birth *</label>
        <input type="date" id="date_of_birth" name="date_of_birth" value="<?= htmlspecialchars($date_of_birth) ?>" required>

        <label for="gender">Gender *</label>
        <select id="gender" name="gender" required>
            <option value="">--Select Gender--</option>
            <option value="Male" <?= ($gender === 'Male') ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= ($gender === 'Female') ? 'selected' : '' ?>>Female</option>
            <option value="Other" <?= ($gender === 'Other') ? 'selected' : '' ?>>Other</option>
        </select>

        <label for="citizenship">Citizenship *</label>
        <input type="text" id="citizenship" name="citizenship" placeholder="Citizenship" value="<?= htmlspecialchars($citizenship) ?>" required>

        <label for="position_applied">Position Applying For *</label>
        <input type="text" id="position_applied" name="position_applied" placeholder="Position Applying For" value="<?= htmlspecialchars($position_applied) ?>" required>

        <label for="years_experience">Years of Experience *</label>
        <input type="number" id="years_experience" name="years_experience" min="0" max="100" placeholder="Years of Experience" value="<?= htmlspecialchars($years_experience) ?>" required>

        <label for="about">Tell Us About Yourself *</label>
        <textarea id="about" name="about" rows="5" placeholder="Tell us about yourself..." required><?= htmlspecialchars($about) ?></textarea>

        <label for="password">Password *</label>
        <input type="password" id="password" name="password" placeholder="Password" required>

        <button type="submit">Register</button>
    </form>

    <!-- Users Table -->
    <h2>Registered users</h2>
    <?php if ($users): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Address</th>
                <th>Email</th>
                <th>Date of Birth</th>
                <th>Gender</th>
                <th>Citizenship</th>
                <th>Position Applied</th>
                <th>Years of Experience</th>
                <th>About</th>
                <th>Date Added</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['firstname']) ?></td>
                <td><?= htmlspecialchars($user['lastname']) ?></td>
                <td><?= htmlspecialchars($user['address']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['date_of_birth']) ?></td>
                <td><?= htmlspecialchars($user['gender']) ?></td>
                <td><?= htmlspecialchars($user['citizenship']) ?></td>
                <td><?= htmlspecialchars($user['position_applied']) ?></td>
                <td><?= htmlspecialchars($user['years_experience']) ?></td>
                <td><?= nl2br(htmlspecialchars($user['about'])) ?></td>
                <td><?= htmlspecialchars($user['date_added']) ?></td>
                <td>
                    <a href="update.php?id=<?= $user['id'] ?>">Edit</a> |
                    <a href="delete.php?id=<?= $user['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No users registered yet.</p>
    <?php endif; ?>

</body>
</html>
