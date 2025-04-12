<?php
@include 'sql.php';
session_start();

$id = $_GET['edit'] ?? null;

$select = $conn->prepare("SELECT * FROM products WHERE id = ?");
$select->bind_param("i", $id);
$select->execute();
$result = $select->get_result();
$row = $result->fetch_assoc();

$category = $row['category'] ?? '';
$description = $row['description'] ?? ''; // Make sure description is set from the database
$errors = [];

if (isset($_POST['update_product'])) {
    $product_name = trim($_POST['product_name']);
    $product_price = trim($_POST['product_price']);
    $category = trim($_POST['category']);
    $quantity = trim($_POST['quantity']);
    $description = $_POST['description'] ?? $row['description'] ?? ''; // Override with POST data if submitted
    $product_image = $_FILES['product_image']['name'] ?? '';
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'] ?? '';
    $product_image_folder = 'W1Demo/image/' . $product_image;
    $colors = json_decode($row['colors'], true);

    // Handle validations
    if (empty($product_name) || strlen($product_name) < 3) {
        $errors['product_name'] = 'Product name must be at least 3 characters long!';
    }

    if (empty($product_price) || !is_numeric($product_price) || $product_price <= 0 || $product_price > 1000) {
        $errors['product_price'] = 'Valid product price is required!';
    }

    $validCategories = ['men', 'women', 'kids 2-8y', 'kids 9-14y'];
    if (empty($category) || !in_array(strtolower($category), $validCategories)) {
        $errors['category'] = 'Valid product category is required!';
    }

    if (empty($description)) {
        $errors['description'] = 'Product description is required!';
    }

    if (empty($quantity) || !filter_var($quantity, FILTER_VALIDATE_INT) || $quantity <= 0 || $quantity > 100) {
        $errors['quantity'] = 'Valid product quantity is required!';
    }

    // Handle image upload
    if (!empty($product_image)) {
        // Check for upload errors
        $upload_error = $_FILES['product_image']['error'];
        if ($upload_error === UPLOAD_ERR_OK) {
            move_uploaded_file($product_image_tmp_name, $product_image_folder);
        } else {
            $errors['image'] = 'Error uploading the image.';
        }
    } else {
        $product_image = $row['image'];  // Use the existing image if none is uploaded
    }
    

    // Handle color variants
    $colorData = [];
    foreach ($_POST['color_names'] as $index => $colorName) {
        $colorName = trim($colorName);
        if ($colorName) {
            $colorFile = $_FILES['color_images']['name'][$index];
            $colorTmp = $_FILES['color_images']['tmp_name'][$index];
            if ($colorFile) {
                $colorPath = '/W1Demo/image/' . $colorFile;
                move_uploaded_file($colorTmp, $colorPath);
            } else {
                $colorPath = ''; // Optional fallback
            }
            $colorData[] = ['color' => $colorName, 'image' => $colorPath];
        }
    }

    $colorJson = json_encode($colorData);

    // If no errors, update
    if (empty($errors)) {
        $update = $conn->prepare("UPDATE products SET name = ?, price = ?, category = ?, stock = ?, image = ?, description = ?, colors = ? WHERE id = ?");
        $update->bind_param("sssssssi", $product_name, $product_price, $category, $quantity, $product_image, $description, $colorJson, $id);

        if ($update->execute()) {
            $_SESSION['message'] = 'Product updated successfully!';
            header("Location: admin.php");
            exit();
        } else {
            $errors['general'] = 'Could not update the product!';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php
    if (isset($_SESSION['message'])) {
        echo '<span class="message">' . htmlspecialchars($_SESSION['message']) . '</span>';
        unset($_SESSION['message']); // Remove message after displaying
    }
    ?>

    <div class="container">
        <div class="admin-product-form-container centered">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . "?edit=$id"; ?>" method="post" enctype="multipart/form-data">
                <h3>Update Product</h3>

                <label for="product_name" class=size>Product Name: </label>
                <input type="text" placeholder="Enter product name" value="<?php echo htmlspecialchars($row['name']); ?>" name="product_name" class="box">
                <span class="error"><?php echo $errors['product_name'] ?? ''; ?></span>
                <br>


                <label for="product_price" class=size>Product Price: </label>
                <input type="number" placeholder="Enter product price" value="<?php echo htmlspecialchars($row['price']); ?>" name="product_price" class="box">
                <span class="error"><?php echo $errors['product_price'] ?? ''; ?></span>
                <br>

                <label for="quantity" class=size>Product Quantity: </label>
                <input type="number" placeholder="Enter product quantity" value="<?php echo htmlspecialchars($row['stock']); ?>" name="quantity" class="box">
                <span class="error"><?php echo $errors['quantity'] ?? ''; ?></span>
                <br>


                <label for="category" class="size">Category: </label>
                <input type="text" placeholder="Enter product category" name="category" class="box" value="<?php echo htmlspecialchars($category); ?>">
                <span class="error"><?php echo $errors['category'] ?? ''; ?></span>
                <br>

                <!-- New Color Field (JSON) -->
                 <script src ="color.js"></script>
                <label for=color class=size>Color Variants: </label><br>
                <div id="color-inputs">
                    <?php if (!empty($colors)) : ?>
                        <?php foreach ($colors as $index => $color): ?>
                            <div class="color-input">
                                <input type="text" name="color_names[]" value="<?php echo htmlspecialchars($color['color']); ?>" class="box">
                                <img src="<?php echo htmlspecialchars($color['image']); ?>" width="60">
                                <input type="file" name="color_images[]" accept="image/*" class="box">
                                <button type="button" class="remove-color-btn" onclick="removeColorInput(this)">Remove</button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="color-input">
                            <input type="text" name="color_names[]" placeholder="Enter color name (e.g., Red)" class="box">
                            <input type="file" name="color_images[]" accept="image/*" class="box">
                            <button type="button" class="remove-color-btn" onclick="removeColorInput(this)">Remove</button>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="button" onclick="addColorInput()">Add Another Color</button><br>

                <label for="description" class="size">Product Description: </label>
                <textarea name="description" class="box" placeholder="Enter product description"><?php echo htmlspecialchars($description); ?></textarea>
                <span class="error"><?php echo $errors['description'] ?? ''; ?></span>
                <br>

                <script src="script.js"></script>
                <div class="upload">
                    <!-- Hidden File Input -->

                    <input type="file" accept="image/*" id="input-file" name="product_image" hidden>

                    <!-- Clickable Upload Area -->

                    <label class=size>Product Image: </label>
                    <label for="input-file" id="drop-area" style="display: block; width: 100%; padding: 20px; border: 2px dashed #ccc; text-align: center; cursor: pointer;">
                        <div id="img-view" style="width: 100%; height: 200px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <!-- Show Current Image -->
                            <img id="current-img" src="image/<?php echo htmlspecialchars($row['image']); ?>" alt="Current Image" width="100">
                            <p>Drag and drop image here<br>or click to upload</p>
                            <span>Upload any image from desktop</span>
                        </div>
                    </label>
                </div>

                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($row['image']); ?>">
                <span class="error"><?php echo $errors['image'] ?? ''; ?></span>

                <input type="submit" class="btn" name="update_product" value="Update Product">
                <a href="admin.php" class="btn">Go Back</a>
            </form>
        </div>
    </div>
</body>

</html>