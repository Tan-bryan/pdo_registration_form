<?php
require 'db.php'; // Include the database connection

$errors = [];
$success = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $address = $_POST['address'] ?? '';
    $email = $_POST['email'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $citizenship = $_POST['citizenship'] ?? '';
    $position = $_POST['position'] ?? '';
    $experience = $_POST['experience'] ?? '';
    $about = $_POST['about'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validation
    if (empty($firstname)) $errors[] = 'First name is required';
    if (empty($lastname)) $errors[] = 'Last name is required';
    if (empty($address)) $errors[] = 'Address is required';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
    if (empty($dob)) $errors[] = 'Date of birth is required';
    if (empty($gender)) $errors[] = 'Gender is required';
    if (empty($citizenship)) $errors[] = 'Citizenship is required';
    if (empty($position)) $errors[] = 'Position applied for is required';
    if (empty($experience) || !is_numeric($experience)) $errors[] = 'Years of experience is required and must be a number';
    if (empty($about)) $errors[] = 'Tell us about yourself is required';
    if (empty($password)) $errors[] = 'Password is required';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters long';

    // If no errors, insert the data into the database
    if (empty($errors)) {
        try {
            // Hash the password before storing it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert into the database
            $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, address, email, dob, gender, citizenship, position, experience, about, password, date_added) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$firstname, $lastname, $address, $email, $dob, $gender, $citizenship, $position, $experience, $about, $hashed_password]);

            $success = 'User registered successfully!';
        } catch (PDOException $e) {
            $errors[] = 'Error adding user: ' . $e->getMessage();
        }
    }
}
?>
