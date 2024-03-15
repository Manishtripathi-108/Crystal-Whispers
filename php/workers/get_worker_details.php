<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['worker_id'])) {
    $worker_id = $_POST['worker_id'];

    $sql = "SELECT * FROM workers WHERE worker_id = $worker_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <input type="hidden" name="update_worker_id" value="<?php echo $row['worker_id']; ?>">
        <input type="hidden" name="do" value="updateWorker">
        <div class="row p-2">

            <div class="workerProfilePhoto col-12 mb-3">
                <div class="d-flex justify-content-center mb-4">
                    <img id="updateWorkerAvatar" src="images/workers/<?php echo $row['image']; ?>" class="rounded-circle"
                        style="width: 150px; height: 150px; object-fit: cover;" />
                </div>

                <div class="d-flex justify-content-center">
                    <div class="simple-btn">
                        <label class="form-label m-1" for="update_profilePhoto">
                            Change Profile Photo
                        </label>
                        <input type="file" class="form-control d-none" id="update_profilePhoto" name="update_profilePhoto"
                            accept="image/*" value="<?php echo $row['image']; ?>"
                            onchange="displaySelectedImage(event, 'updateWorkerAvatar')" />
                    </div>
                </div>
            </div>

            <?php $name = explode(" ", $row['name']) ?>
            <div class="col-md-6 form-floating mb-3">
                <input type="text" class="form-control" id="update_f_name" name="update_f_name" value="<?php echo $name[0]; ?>"
                    placeholder="First Name" pattern="[A-Za-z]+" required>
                <label for="update_f_name" class="form-label">First Name:</label>
            </div>

            <div class="col-md-6 form-floating mb-3">
                <input type="text" class="form-control" id="update_l_name" name="update_l_name" value="<?php echo $name[1]; ?>"
                    placeholder="Last Name" pattern="[A-Za-z]+" required>
                <label for="update_l_name" class="form-label">Last Name:</label>
            </div>

            <div class="col-md-6 form-floating mb-3">
                <input type="text" class="form-control" id="update_phone" name="update_phone"
                    value="<?php echo substr(preg_replace("/[^\d]/", "", $row['phone_number']), 2); ?>" placeholder="Phone"
                    maxlength="10" pattern="[0-9]{10}" required>
                <label for="update_phone" class="form-label">Phone:</label>
            </div>

            <div class="col-md-6 form-floating mb-3">
                <input type="email" class="form-control" id="update_email" name="update_email"
                    value="<?php echo $row['email']; ?>" placeholder="Email" required>
                <label for="update_email" class="form-label">Email:</label>
            </div>

            <div class="col-md-3 form-floating mb-3">
                <select name="update_gender" class="form-select" required>
                    <option>Gender</option>
                    <option value="Male" <?php echo ($row['gender'] == "Male") ? "selected" : "" ?>>Male
                    </option>
                    <option value="Female" <?php echo ($row['gender'] == "Female") ? "selected" : "" ?>>
                        Female</option>
                </select>
                <label for="update_gender">Gender:</label>
            </div>

            <div class="col-md-4 form-floating mb-3">
                <select name="update_position" class="form-select" required>
                    <option value="">Position</option>
                    <option value="Appraiser" <?php echo ($row['position'] == "Appraiser") ? "selected" : ""; ?>>Appraiser
                    </option>
                    <option value="Engraver" <?php echo ($row['position'] == "Engraver") ? "selected" : ""; ?>>Engraver</option>
                    <option value="Gemologist" <?php echo ($row['position'] == "Gemologist") ? "selected" : ""; ?>>Gemologist
                    </option>
                    <option value="Goldsmith" <?php echo ($row['position'] == "Goldsmith") ? "selected" : ""; ?>>Goldsmith
                    </option>
                    <option value="Jeweler" <?php echo ($row['position'] == "Jeweler") ? "selected" : ""; ?>>Jeweler</option>
                    <option value="Jewelry Designer" <?php echo ($row['position'] == "Jewelry Designer") ? "selected" : ""; ?>>
                        Jewelry Designer</option>
                    <option value="Manager" <?php echo ($row['position'] == "Manager") ? "selected" : ""; ?>>Manager</option>
                    <option value="Sales Associate" <?php echo ($row['position'] == "Sales Associate") ? "selected" : ""; ?>>Sales
                        Associate</option>
                </select>
                <label for="update_position" class="form-label">Position:</label>
            </div>

            <div class="col-md-4 form-floating mb-3">
                <?php
                $salary = preg_replace("/[^0-9.]/", "", $row['salary']);
                ?>
                <input type="number" class="form-control" id="update_salary" name="update_salary" value="<?php echo $salary; ?>"
                    placeholder="Salary" maxlength="10" pattern="[0-9]{10}" step="any" required>
                <label for="update_salary" class="form-label">Salary:</label>
            </div>

            <hr>

            <?php $workerAddress = explode("/", $row['address']); ?>
            <div class="col-12 form-floating mb-3">
                <input type="text" class="form-control" id="update_userAddress" name="update_userAddress"
                    value="<?php echo $workerAddress[0]; ?>" placeholder="Address" required>
                <label for="update_userAddress" class="form-label">Address:</label>
            </div>

            <div class="col-8 form-floating mb-3">
                <input type="text" class="form-control" id="update_userAddress_2" name="update_userAddress_2"
                    value="<?php echo $workerAddress[1]; ?>" placeholder="Apartment, studio, or floor">
                <label for="update_userAddress_2" class="form-label">Apartment, studio, or
                    floor:</label>
            </div>

            <div class="col-md-4 form-floating mb-3">
                <input required type="text" class="form-control" id="update_userCity" name="update_userCity"
                    value="<?php echo substr($workerAddress[2], 1); ?>" placeholder="City">
                <label for="update_userCity" class="form-label">City:</label>
            </div>

            <div class="col-md-3 form-floating mb-3">
                <select required name="update_userState" class="form-select" id="update_userState" placeholder="State">
                    <option value="">Select State</option>
                    <?php
                    $states = array(
                        "Andaman and Nicobar Islands",
                        "Andhra Pradesh",
                        "Arunachal Pradesh",
                        "Assam",
                        "Bihar",
                        "Chandigarh",
                        "Chhattisgarh",
                        "Dadra and Nagar Haveli and Daman and Diu",
                        "Delhi",
                        "Goa",
                        "Gujarat",
                        "Haryana",
                        "Himachal Pradesh",
                        "Jharkhand",
                        "Karnataka",
                        "Kerala",
                        "Lakshadweep",
                        "Madhya Pradesh",
                        "Maharashtra",
                        "Manipur",
                        "Meghalaya",
                        "Mizoram",
                        "Nagaland",
                        "Odisha",
                        "Puducherry",
                        "Punjab",
                        "Rajasthan",
                        "Sikkim",
                        "Tamil Nadu",
                        "Telangana",
                        "Tripura",
                        "Uttar Pradesh",
                        "Uttarakhand",
                        "West Bengal"
                    );

                    foreach ($states as $state) {
                        $selected = (substr($workerAddress[3], 1) == $state) ? "selected" : "";
                        echo "<option value=\"$state\" $selected>$state</option>";
                    }
                    ?>
                </select>
                <label for="update_userState" class="form-label">State:</label>
            </div>

            <div class="col-md-2 form-floating mb-3">
                <input required type="text" class="form-control" id="update_userZip" name="update_userZip"
                    value="<?php echo substr($workerAddress[4], 1); ?>" placeholder="Zip" maxlength="6" pattern="[0-9]{1,6}">
                <label for="update_userZip" class="form-label">Zip:</label>
            </div>
        </div>

        <hr>

        <div class="col-12 form-floating mb-3">
            <textarea style="height: 150px;" class="form-control" id="update_description" name="update_description"
                placeholder="Update Description (Words remaining: 50)" required><?php echo $row['description']; ?></textarea>
            <label id="update_wordCount" for="update_description" class="form-label">Update Description
                (Words remaining: 50):</label>
        </div>
    <?php } else {
        echo 'Worker details not found.';
    }
} else {
    echo 'Invalid request.';
}

$conn->close();
?>