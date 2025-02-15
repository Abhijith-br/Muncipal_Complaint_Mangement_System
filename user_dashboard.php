<?php
include './connection.php';

// Assuming you want to show only the logged-in user's complaints
$user_name = $_SESSION['user_name'];

$sql = "SELECT * FROM complaints WHERE user_name='$user_name'";
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
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
        .button {
            margin: 20px;
        }
        .button a {
            text-decoration: none;
            color: white;
            background-color: #007BFF;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .button a:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            max-width: 900px;
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
        img {
            max-width: 150px;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h2>User Dashboard</h2>
    <div class="button">
        <a href="complaint_register.php">Register New Complaint</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Date</th>
                <th>Status</th>
                <th>Image</th>
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
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="6">No complaints registered</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
<?php
$con->close();
?>
