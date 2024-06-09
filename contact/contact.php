<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="../style/contact.css">
    <?php include '../pages/boot.php'; ?>
    <?php include '../pages/nav.php'; ?>

</head>
<body>
<?php include '../pages/navbar.php'; ?>
    <?php include '../pages/side.php'; ?>

    <footer>
        <div class="footer-contact">
            <h2>Contact Us</h2>
            <form action="send_contact.php" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="message">Message:</label>
                <textarea id="message" name="message" required></textarea>
                
                <button type="submit">Send</button>
            </form>
        </div>
    </footer>
    <?php include '../pages/scboot.php'; ?>
    <?php include '../pages/footer.php'; ?>
</body>
</html>
