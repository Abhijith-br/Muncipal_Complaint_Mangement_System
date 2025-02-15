<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './connection.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $con->real_escape_string($_POST['title']);
    $description = $con->real_escape_string($_POST['description']);
    $user_name = $_SESSION['user_name'];

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $error = '';

    // Check if image file is a valid image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        $error = "File is not an image.";
    } else {
        // Validate file size (e.g., limit to 5MB)
        if ($_FILES["image"]["size"] > 5000000) {
            $error = "Sorry, your file is too large.";
        } else {
            // Allow certain file formats (e.g., JPG, JPEG, PNG)
            $allowed_types = ['jpg', 'jpeg', 'png'];
            if (!in_array($imageFileType, $allowed_types)) {
                $error = "Sorry, only JPG, JPEG, & PNG files are allowed.";
            } else {
                // Check if file already exists
                if (file_exists($target_file)) {
                    $error = "Sorry, file already exists.";
                } else {
                    // Try to upload file
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        // Check if file was actually uploaded
                        if (!file_exists($target_file)) {
                            $error = "File was not uploaded correctly.";
                        } else {
                            // Save the complaint details along with image path to the database
                            $sql = "INSERT INTO complaints (title, description, user_name, image_path, created_at) VALUES ('$title', '$description', '$user_name', '$target_file', NOW())";
                            
                            if ($con->query($sql) === TRUE) {
                                $message = "Complaint registered successfully!";
                            } else {
                                $error = "Error: " . $con->error;
                            }
                        }
                    } else {
                        $error = "Sorry, there was an error uploading your file. Please check the permissions of the uploads directory.";
                        // Additional debugging info
                        $error .= " Upload error code: " . $_FILES["image"]["error"];
                    }
                }
            }
        }
    }

    $con->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register Complaint</title>
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
        form {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            margin: 0 20px;
        }
        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus,
        textarea:focus,
        input[type="file"]:focus {
            border-color: #007BFF;
            outline: none;
        }
        textarea {
            resize: vertical;
            height: 150px;
            padding-top: 10px;
        }
        button {
            width: 100%;
            padding: 15px;
            background-color: #007BFF;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message,
        .error {
            margin: 10px 0;
            padding: 15px;
            border-radius: 8px;
            font-weight: bold;
            width: 100%;
            max-width: 600px;
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
    <h2>Register Complaint</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Complaint Title" required>
        <textarea name="description" placeholder="Complaint Description" required></textarea>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit">Register</button>
    </form>
    <?php if (isset($message)) { echo "<div class='message'>$message</div>"; } ?>
    <?php if (isset($error)) { echo "<div class='error'>$error</div>"; } ?>
</body>
</html>
