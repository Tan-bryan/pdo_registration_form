<?php
// update.php

require 'db.php'; // Include the database connection

// Initialize variables
$id = $_GET['id'] ?? null;
$firstname = $lastname = $address = $email = $date_of_birth = $gender = $citizenship = $position_applied = $years_experience = $about = '';
$successMessage = $errorMessage = '';

// Check if ID is provided
if (!$id) {
    echo "Invalid User ID.";
    exit;
}

// Fetch user data
try {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if (!$user) {
        echo "User not found.";
        exit;
    }

    // Populate variables with existing data
    $firstname        = $user['firstname'];
    $lastname         = $user['lastname'];
    $address          = $user['address'];
    $email            = $user['email'];
    $date_of_birth    = $user['date_of_birth'];
    $gender           = $user['gender'];
    $citizenship      = $user['citizenship'];
    $position_applied = $user['position_applied'];
    $years_experience = $user['years_experience'];
    $about            = $user['about'];
} catch (PDOException $e) {
    echo "Error fetching user: " . $e->getMessage();
    exit;
}

// Handle form submission for update
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

    // Basic validation
    if (
        empty($firstname) || empty($lastname) || empty($address) || empty($email) ||
        empty($date_of_birth) || empty($gender) || empty($citizenship) ||
        empty($position_applied) || empty($years_experience) || empty($about)
    ) {
        $errorMessage = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format.";
    } elseif (!is_numeric($years_experience) || $years_experience < 0) {
        $errorMessage = "Please enter a valid number for years of experience.";
    } else {
        try {
            // Check if email already exists for another user
            $checkStmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ? AND id != ?');
            $checkStmt->execute([$email, $id]);
            $count = $checkStmt->fetchColumn();

            if ($count > 0) {
                $errorMessage = "Email already exists.";
            } else {
                // Update the user
                $updateStmt = $pdo->prepare('UPDATE users SET 
                    firstname = ?, 
                    lastname = ?, 
                    address = ?, 
                    email = ?, 
                    date_of_birth = ?, 
                    gender = ?, 
                    citizenship = ?, 
                    position_applied = ?, 
                    years_experience = ?, 
                    about = ? 
                    WHERE id = ?');
                $updateStmt->execute([
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
                    $id
                ]);

                $successMessage = "User details updated successfully!";
            }
        } catch (PDOException $e) {
            $errorMessage = "Error updating user: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update User</title>
    <style>
        /* Basic styling */
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { max-width: 600px; }
        input[type="text"], input[type="email"], input[type="date"], select, textarea, input[type="number"] {
            width: 100%; padding: 8px; margin: 5px 0 15px 0; box-sizing: border-box;
        }
        button { padding: 10px 20px; }
        .message { margin-bottom: 20px; padding: 10px; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

    <h1>Update User</h1>

    <!-- Display Success or Error Messages -->
    <?php if ($successMessage): ?>
        <div class="message success"><?= htmlspecialchars($successMessage) ?></div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <div class="message error"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <!-- Update Form -->
    <form method="post" action="update.php?id=<?= htmlspecialchars($id) ?>">
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

        <button type="submit">Update</button>
    </form>

    <p><a href="index.php">Back to User List</a></p>

</body>
</html>
