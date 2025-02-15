<?php
include './connection.php';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['action'])) {
    $id = $con->real_escape_string($_POST['id']);
    $action = $con->real_escape_string($_POST['action']);

    $status = $action == 'accept' ? 'Accepted' : 'Rejected';
    $sql = "UPDATE complaints SET status='$status' WHERE id=$id";

    if ($con->query($sql) === TRUE) {
        $message = "Complaint status updated successfully!";
    } else {
        $error = "Error updating status: " . $con->error;
    }
}

$sql = "SELECT * FROM complaints";
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            max-width: 1000px;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #ddd;
            margin: 20px 0;
        }
        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f4f4f9;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        button {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .accept {
            background-color: #28a745;
        }
        .accept:hover {
            background-color: #218838;
        }
        .reject {
            background-color: #dc3545;
        }
        .reject:hover {
            background-color: #c82333;
        }
        img {
            max-width: 150px;
            height: auto;
            border-radius: 5px;
        }
        .message,
        .error {
            margin: 10px 0;
            padding: 15px;
            border-radius: 8px;
            font-weight: bold;
            width: 100%;
            max-width: 1000px;
            box-sizing: border-box;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .message {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }
        .error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <h2>Admin Dashboard</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Date</th>
                <th>Status</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <?php if (!empty($row['image_path'])) { ?>
                                <img src="<?php echo $row['image_path']; ?>" alt="Complaint Image">
                            <?php } ?>
                        </td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="action" value="accept" class="accept">Accept</button>
                                <button type="submit" name="action" value="reject" class="reject">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="7">No complaints registered</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php if (isset($message)) { echo "<div class='message'>$message</div>"; } ?>
    <?php if (isset($error)) { echo "<div class='error'>$error</div>"; } ?>
</body>
</html>
<?php
$con->close();
?>
