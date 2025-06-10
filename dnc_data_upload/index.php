<!DOCTYPE html>
<html>
<head>
    <title>File Upload Form</title>
    <style type="text/css">
        .download-link {
            display: block;
            margin-top: 5px;
            font-size: 17px;
            color: green;
        }
    </style>
</head>
<body>
    <?php
        $user_name = md5('admin');
             base64_encode($user_name);
     ?>
    <form action="submit.php" method="post" enctype="multipart/form-data">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="file">Select file:</label>
        <input type="file" id="file" name="file" required><br>
        <a href="/excel_work_new/file_templete.csv" download="file_templete.csv" class="download-link">click here to download</a><br>
        <input type="submit" value="Upload File">
    </form>

    <!-- <p><a href="images/pdf.pdf" target="_blank" class="btn btn-primary py-3 px-4">Download PDF</a></p> -->

    <?php
    session_start();
    if (isset($_SESSION['success_message'])) {
        echo '<script>alert("file uploaded.");</script>';
        unset($_SESSION['success_message']); // Clear session variable
    }
    ?>
</body>
</html>
