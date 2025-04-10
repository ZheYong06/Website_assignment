<?php
require __DIR__ . '/config/db.php'; 

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT username, email FROM user_profile WHERE username LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%{$search}%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Members List</title>
</head>
<body>
    <h2>Registered Members</h2>
    
    <form method="GET">
        <input type="text" name="search" placeholder="Search by username" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>

    <table border="1">
        <tr>
            <th>Username</th>
            <th>Email</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row["username"]); ?></td>
                <td><?php echo htmlspecialchars($row["email"]); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
