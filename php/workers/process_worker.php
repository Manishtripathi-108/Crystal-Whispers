<?php
session_start();
include "../connection.php";

// Redirect to admin login page if admin is not logged in
if (!isset($_SESSION["AdminID"])) {
    header('Location: ../../admin/admin-login.php');
    exit;
}

// Handle worker addition, update, or deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do'])) {
    $action = $_POST['do'];

    switch ($action) {
        case 'addWorker':
            handleWorkerAddition();
            break;

        case 'updateWorker':
            handleWorkerUpdate();
            break;

        case 'deleteWorker':
            handleWorkerDeletion();
            break;

        default:
            $_SESSION['workerMessage'] = "Unknown action:" . $action;
            break;
    }
}

// Redirect to admin page
redirectToAdminPage();

// Function to handle worker addition
function handleWorkerAddition()
{
    global $conn;

    $name = ucwords(strtolower($_POST['f_name'] . ' ' . $_POST['l_name']));
    $position = $_POST['position'];
    $gender = $_POST['gender'];
    $email = strtolower($_POST['email']);
    $phone = "+91 " . substr($_POST['phone'], 0, 5) . "-" . substr($_POST['phone'], 5);
    $address = $_POST['userAddress'] . '/ ' . $_POST['userAddress_2'] . '/ ' . $_POST['userCity'] . '/ ' . $_POST['userState'] . '/ ' . $_POST['userZip'];
    $salary = $_POST['salary'];
    $description = isset($_POST['description']) ? $_POST['description'] : 'No Description Available';

    $stmt = $conn->prepare("INSERT INTO workers (WorkerName, WorkerPosition, WorkerGender, WorkerEmail, WorkerPhone, WorkerAddress, WorkerSalary, WorkerDescription) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $name, $position, $gender, $email, $phone, $address, $salary, $description);

    if ($stmt->execute()) {
        $worker_id = $conn->insert_id;
        $_SESSION["workerMessage"] = "Worker added successfully.";
        handleWorkerProfilePhotoUpdate($name, $worker_id);
    } else {
        $_SESSION["workerMessage"] = "Error adding worker. Please try again.";
    }

    $stmt->close();
    redirectToAdminPage();
}

// Function to handle worker update
function handleWorkerUpdate()
{
    global $conn;

    $update_worker_id = $_POST['update_worker_id'];
    $update_name = ucwords(strtolower($_POST['update_f_name'] . ' ' . $_POST['update_l_name']));
    $update_position = $_POST['update_position'];
    $update_gender = $_POST['update_gender'];
    $update_email = strtolower($_POST['update_email']);
    $update_phone = "+91 " . substr($_POST['update_phone'], 0, 5) . "-" . substr($_POST['update_phone'], 5);
    $update_address = $_POST['update_userAddress'] . '/ ' . $_POST['update_userAddress_2'] . '/ ' . $_POST['update_userCity'] . '/ ' . $_POST['update_userState'] . '/ ' . $_POST['update_userZip'];
    $update_salary = $_POST['update_salary'];
    $update_description = isset($_POST['update_description']) ? $_POST['update_description'] : 'No Description Available';

    $stmt = $conn->prepare("UPDATE workers SET WorkerName = ?, WorkerPosition = ?, WorkerGender = ?, WorkerEmail = ?, WorkerPhone = ?, WorkerAddress = ?, WorkerSalary = ?, WorkerDescription = ? WHERE WorkerID = ?");
    $stmt->bind_param("ssssssssi", $update_name, $update_position, $update_gender, $update_email, $update_phone, $update_address, $update_salary, $update_description, $update_worker_id);

    if ($stmt->execute()) {
        $_SESSION["workerMessage"] = "Worker updated successfully.";
        handleWorkerProfilePhotoUpdate($update_name, $update_worker_id, true);
    } else {
        $_SESSION["workerMessage"] = "Error updating worker. Please try again.";
    }

    $stmt->close();
    redirectToAdminPage();
}

// Function to handle profile photo update
function handleWorkerProfilePhotoUpdate($name, $worker_id, $isUpdate = false)
{
    global $conn;

    $profilePhotoKey = $isUpdate ? 'update_profilePhoto' : 'profilePhoto';

    if (isset($_FILES[$profilePhotoKey]) && $_FILES[$profilePhotoKey]['error'] === UPLOAD_ERR_OK) {
        $fileName = $_FILES[$profilePhotoKey]['name'];
        $fileInfo = pathinfo($fileName);
        $fileExtension = strtolower($fileInfo['extension']);
        $profilePhoto = $name . '_' . $worker_id . '_' . time() . '_.' . $fileExtension;
        $profilePhoto = str_replace([' ', '(', ')'], ['_', '_', '_'], $profilePhoto);
        $temp_name = $_FILES[$profilePhotoKey]['tmp_name'];
        $path = '../../assets/images/workers/' . $profilePhoto;

        if (move_uploaded_file($temp_name, $path)) {
            $sql = "UPDATE workers SET WorkerImage = ? WHERE WorkerID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $profilePhoto, $worker_id);

            $oldImage = getOldImage($worker_id);

            if ($stmt->execute()) {
                $_SESSION["workerMessage"] .= "<br>Profile Photo updated successfully.";
                removeOldImage($oldImage);
            } else {
                $_SESSION["workerMessage"] .= "<br>Error updating Profile Photo. Please try again.";
            }
        } else {
            $_SESSION["workerMessage"] .= "<br>Error uploading image";
        }
    } else {
        $_SESSION["workerMessage"] .= "<br>Profile Photo not found or upload error.";
    }
}

// Function to get old image
function getOldImage($worker_id)
{
    global $conn;

    $getOldImage = "SELECT WorkerImage FROM workers WHERE WorkerID = ?";
    $stmt = $conn->prepare($getOldImage);
    $stmt->bind_param("i", $worker_id);
    $stmt->execute();
    $oldImageResult = $stmt->get_result();
    $oldImage = $oldImageResult->fetch_assoc()['WorkerImage'];
    $stmt->close();

    return $oldImage;
}

// Function to remove old image 
function removeOldImage($oldImage)
{
    if ($oldImage != 'profile.png' && $oldImage != null && file_exists('../../assets/images/workers/' . $oldImage)) {
        unlink('../../assets/images/workers/' . $oldImage);
    }
}

// Function to handle worker deletion
function handleWorkerDeletion()
{
    global $conn;

    $workerId = $_POST['deleteWorkerWithId'];

    removeOldImage(getOldImage($workerId));

    $sql = "DELETE FROM workers WHERE WorkerID = " . $workerId;
    $result = $conn->query($sql);
    if ($result) {
        $_SESSION["workerMessage"] = "Worker deleted successfully.";
    } else {
        $_SESSION["workerMessage"] = "Error deleting worker. Please try again.";
    }

    redirectToAdminPage();
}

// Function to redirect to admin page
function redirectToAdminPage()
{
    $_SESSION['section'] = 'Workers';
    header('Location: ../../admin/index.php');
    exit;
}
