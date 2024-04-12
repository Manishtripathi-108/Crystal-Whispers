<?php
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    $sql = "SELECT 
        p.*,
        c.CategoryName
        FROM products p
        JOIN Categories c ON p.CategoryID = c.CategoryID
        WHERE ProductID = $product_id";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); ?>
        <div class="row m-0 p-2">
            <input type="hidden" name="product_id" value="<?php echo $row['ProductID'] ?>">

            <div id="add_product_img" class="row mb-2 justify-content-center">
                <!-- Image Box 1 -->
                <div class="imgBox col-sm-5 col-md-4 p-4">
                    <div class="d-flex justify-content-center mb-4">
                        <img id="updateSelectedImage1" src="../assets/images/products/<?php echo isset($row['ProImg1']) ? $row['ProImg1'] : "add-image.png"; ?>" alt="Image 1">
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="simple-btn">
                            <label class="form-label m-1" for="update_productImage1">Add Image 1
                                (Required)</label>
                            <input type="file" class="form-control d-none" id="update_productImage1" name="update_productImage1" accept="image/*" <?php echo isset($row['ProImg1']) ? 'value="' . $row['ProImg1'] . '"' : 'required'; ?> onchange="displaySelectedImage(event, 'updateSelectedImage1')" />
                        </div>
                    </div>
                </div>

                <!-- Image Box 2 -->
                <div class="imgBox col-sm-5 mt-3 mt-md-auto col-md-4 p-4">
                    <div class="d-flex justify-content-center mb-4">
                        <img id="updateSelectedImage2" src="../assets/images/products/<?php echo isset($row['ProImg2']) ? $row['ProImg2'] : "add-image.png"; ?>" alt="Image 2">
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="simple-btn">
                            <label class="form-label m-1" for="update_productImage2">Add Image 2</label>
                            <input type="file" class="form-control d-none" id="update_productImage2" name="update_productImage2" accept="image/*" onchange="displaySelectedImage(event, 'updateSelectedImage2')" />
                        </div>
                    </div>
                </div>

                <!-- Image Box 3 -->
                <div class="imgBox col-sm-5 mt-3 mt-md-auto col-md-4 p-4">
                    <div class="d-flex justify-content-center mb-4">
                        <img id="updateSelectedImage3" src="../assets/images/products/<?php echo isset($row['ProImg3']) ? $row['ProImg3'] : "add-image.png"; ?>" alt="Image 3">
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="simple-btn">
                            <label class="form-label m-1" for="update_productImage3">Add Image 3</label>
                            <input type="file" class="form-control d-none" id="update_productImage3" name="update_productImage3" accept="image/*" onchange="displaySelectedImage(event, 'updateSelectedImage3')" />
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="heading_container text-start">
                <h4>
                    Product Details :
                </h4>
            </div>
            <div class="col-md-4 form-floating mb-3">
                <input type="text" class="form-control" id="update_product_name" name="update_product_name" placeholder="Product Name" required value="<?php echo $row['ProductName']; ?>">
                <label for="update_product_name" class="form-label">Product Name</label>
            </div>

            <div class="col-md-4 form-floating mb-3">
                <select name="update_category" class="form-select" required>
                    <option value="">Select Category</option>
                    <?php
                    $sql = "SELECT * FROM categories";
                    $cat_result = $conn->query($sql);

                    if ($cat_result->num_rows > 0) {
                        while ($cat_row = $cat_result->fetch_assoc()) {
                            $selected = ($cat_row['CategoryID'] == $row['CategoryID']) ? 'selected' : '';
                            echo '<option value="' . $cat_row['CategoryID'] . '" ' . $selected . '>' . $cat_row['CategoryName'] . '</option>';
                        }
                    }

                    $cat_result->close();
                    ?>
                </select>
                <label for="update_category">Category</label>
            </div>

            <div class="col-md-4 form-floating mb-3">
                <input type="number" class="form-control" id="update_price" name="update_price" placeholder="Price" required value="<?php echo $row['ProductPrice']; ?>">
                <label for="update_price" class="form-label">Price</label>
            </div>
            <div class="col-md-4 form-floating mb-3">
                <input type="tel" class="form-control" id="update_discount" name="update_discount" maxlength="2" pattern="\d{1,2}" placeholder="Discount" required value="<?php echo $row['ProductDiscount']; ?>">
                <label for="update_discount" class="form-label">Discount (1-99)</label>
            </div>
            <div class="col-md-4 form-floating mb-3">
                <input type="number" step="any" class="form-control" id="update_weight" name="update_weight" placeholder="Weight" required value="<?php echo $row['ProductWeight']; ?>">
                <label for="update_weight" class="form-label">Weight</label>
            </div>
            <div class="col-md-4 form-floating mb-3">
                <input type="text" class="form-control" id="update_color" name="update_color" placeholder="Color" required pattern="^[a-zA-Z]+$" value="<?php echo $row['ProductColor']; ?>">
                <label for="update_color" class="form-label">Color</label>
            </div>
            <div class="col-md-4 form-floating mb-3">
                <select name="update_gender" class="form-select" required>
                    <option value="">Select Gender</option>
                    <option value="Male" <?php echo ($row['ProductTargetGender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo ($row['ProductTargetGender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                </select>
                <label for="update_gender">Gender:</label>
            </div>
            <div class="col-md-4 form-floating mb-3">
                <select name="update_material" class="form-select" required>
                    <option value="">Select Material</option>
                    <option value="Gold" <?php echo ($row['ProductMaterial'] == 'Gold') ? 'selected' : ''; ?>>Gold</option>
                    <option value="Silver" <?php echo ($row['ProductMaterial'] == 'Silver') ? 'selected' : ''; ?>>Silver</option>
                    <option value="Platinum" <?php echo ($row['ProductMaterial'] == 'Platinum') ? 'selected' : ''; ?>>Platinum</option>
                </select>
                <label for="update_material">Material</label>
            </div>
            <div class="col-md-4 form-floating mb-3">
                <select name="update_occasion" class="form-select" required>
                    <option value="">Occasion</option>
                    <?php
                    $sql = "SELECT * FROM occasions";
                    $occ_result = $conn->query($sql);
                    if ($occ_result->num_rows > 0) {
                        while ($occ_row = $occ_result->fetch_assoc()) {
                            $selected = ($occ_row['OccasionID'] == $row['OccasionID']) ? 'selected' : '';
                            echo '<option value="' . $occ_row['OccasionID'] . '" ' . $selected . '>' . $occ_row['OccasionName'] . '</option>';
                        }
                    }
                    $occ_result->close();
                    ?>
                </select>
                <label for="update_occasion">Occasion</label>
            </div>

            <div class="col-md-4 form-floating mb-3">
                <input type="number" class="form-control" id="update_stock_quantity" name="update_stock_quantity" placeholder="Stock Quantity" required value="<?php echo $row['ProductStock']; ?>">
                <label for="update_stock_quantity" class="form-label">Stock Quantity</label>
            </div>
        </div>

<?php } else {
        echo 'Product details not found.';
    }
} else {
    echo 'Invalid request.';
}

$conn->close();
?>