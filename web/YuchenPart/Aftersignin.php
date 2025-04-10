<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Aftersigin.css">
    <title>Square Example</title>
</head>
<body>


    <div class="container">
        <div class="left-section">
            <h1>Memberlist</h1>
            <div class="SPACE01">
                <a href="Content.html">
                    <img src="user_icon_007.jpg" class="Clickanimation" alt="User Icon">
                </a>
            </div>
        </div>

        <div class="right-section">
            <h1>Product</h1>
            <div class="SPACE02">
                <a href="Content.html">
                    <img src="Product_sample_icon_picture.png" class="Clickanimation" alt="Product Image">
                </a>
            </div>
        </div>
    </div>
    <div class="logout-container">
    <form action="logout.php" method="post">
        <button type="submit" class="logout-button">Log Out</button>
    </form>
</div>

    <a href="logout.php" style="color: red;">Logout</a>

</body>
</html>
