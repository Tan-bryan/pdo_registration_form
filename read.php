<?php
require 'db.php';

$stmt = $pdo->query('SELECT * FROM users');
$users = $stmt->fetchAll();
?>

<h2>User List</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Username</th>
        <th>Phone Number</th>
        <th>Date Added</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= htmlspecialchars($user['id']) ?></td>
        <td><?= htmlspecialchars($user['first_name']) ?></td>
        <td><?= htmlspecialchars($user['last_name']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= htmlspecialchars($user['username']) ?></td>
        <td><?= htmlspecialchars($user['phone_number']) ?></td>
        <td><?= htmlspecialchars($user['date_added']) ?></td>
        <td>
            <a href="update.php?id=<?= $user['id'] ?>">Edit</a> |
            <a href="delete.php?id=<?= $user['id'] ?>">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
