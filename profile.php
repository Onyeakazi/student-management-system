<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout');
} else {
    $id = $_SESSION['sturecmsaid'];

    // Fetch user data
    $sql = "SELECT u.*, 
                s.dob, 
                s.level, 
                s.matric_number, 
                s.department, 
                s.guardian_name, 
                s.guardian_phone,
                i.department AS instructor_department,
                i.staff_id
            FROM users u
            LEFT JOIN students s ON u.id = s.user_id
            LEFT JOIN lecturers i ON u.id = i.user_id
            WHERE u.id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_OBJ);

    if (!$user) {
        echo "<script>alert('User not found.');</script>";
        exit;
    }

    $role = $user->role;

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $userId = $_SESSION['sturecmsaid']; // use the correct session
    $role = $user->role;

    // User table update
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    $query = "UPDATE users SET fullname=?, phone=?, email=?, gender=?, address=?";
    $params = [$fullname, $phone, $email, $gender, $address];

    if ($password) {
        $query .= ", password=?";
        $params[] = $password;
    }

    $query .= " WHERE id=?";
    $params[] = $userId;

    $stmt = $dbh->prepare($query);
    $stmt->execute($params);

    // Student table update
    if ($role === 'student') {
        $name = $_POST['fullname'];
        $dob = $_POST['dob'] ?? '';
        $level = $_POST['level'] ?? '';
        $matric_number = $_POST['matric_number'] ?? '';
        $department = $_POST['department'] ?? '';
        $guardian_name = $_POST['guardian_name'] ?? '';
        $guardian_phone = $_POST['guardian_phone'] ?? '';

        $query2 = "UPDATE students SET name=?, dob=?, level=?, matric_number=?, department=?, guardian_name=?, guardian_phone=? WHERE user_id=?";
        $params2 = [$name, $dob, $level, $matric_number, $department, $guardian_name, $guardian_phone, $userId];

        $stmt2 = $dbh->prepare($query2);
        $stmt2->execute($params2);
    } elseif ($role === 'instructor') {
        $name = $_POST['fullname'];
        $department = $_POST['department'] ?? '';
        $staff_id = $_POST['staff_id'] ?? '';

        $query2 = "UPDATE lecturers SET department=?, name=?, staff_id=? WHERE user_id=?";
        $params2 = [$department, $name, $staff_id, $userId];

        $stmt2 = $dbh->prepare($query2);
        $stmt2->execute($params2);

    }

    echo "<script>alert('Profile updated successfully.'); window.location.href='profile';</script>";
    exit;
}

?>

<!-- HTML START -->
<?php include_once('includes/header.php'); ?>
<div class="container-fluid page-body-wrapper">
    <?php include_once('includes/sidebar.php'); ?>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">Update Profile</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title text-center">Update Profile - <?php echo ucfirst($role); ?></h4>
                            <form class="forms-sample" method="post">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" name="fullname" value="<?php echo $user->fullname; ?>" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Contact Number</label>
                                    <input type="text" name="phone" value="<?php echo $user->phone; ?>" class="form-control" maxlength="11" required pattern="[0-9]+">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" value="<?php echo $user->email; ?>" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                                </div>
                                <div class="form-group">
                                    <label>Gender</label>
                                    <input type="text" name="gender" value="<?php echo $user->gender; ?>" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="address" name="address" value="<?php echo $user->address; ?>" class="form-control" required>
                                </div>

                                <?php if ($role === 'student') { ?>
                                    <!-- Student-specific field (if needed) -->
                                    <div class="form-group">
                                      <label>Matric Number</label>
                                      <input type="text" value="<?php echo $user->matric_number ?? ''; ?>" class="form-control" name="matric_number">
                                    </div>
                                    <div class="form-group">
                                      <label>Date Of Birth</label>
                                      <input type="date" value="<?php echo $user->dob ?? ''; ?>" class="form-control" name="dob">
                                    </div>
                                    <div class="form-group">
                                      <label>Level</label>
                                      <input type="text" value="<?php echo $user->level ?? ''; ?>" class="form-control" name="level">
                                    </div>
                                    <div class="form-group">
                                      <label>Department</label>
                                      <input type="text" value="<?php echo $user->department ?? ''; ?>" class="form-control" name="department">
                                    </div>
                                    <div class="form-group">
                                      <label>Guardian Name</label>
                                      <input type="text" value="<?php echo $user->guardian_name ?? ''; ?>" class="form-control" name="guardian_name">
                                  </div>
                                  <div class="form-group">
                                      <label>Guardian Phone</label>
                                      <input type="text" value="<?php echo $user->guardian_phone ?? ''; ?>" class="form-control" name="guardian_phone">
                                  </div>
                                  
                                <?php } elseif ($role === 'instructor') { ?>
                                    <!-- Instructor-specific field (if needed) -->
                                    <div class="form-group">
                                        <label for="exampleInputEmail3">Department</label>
                                        <select  name="department" class="form-control" required='true'>
                                        <option>-- Select Department --</option>
                                        <?php 
                                            $query = "SELECT * FROM departments";
                                            $query = $dbh->prepare($query);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            if($query->rowCount() > 0) {
                                            foreach($results as $result) {
                                        ?> 
                                        <option value="<?php echo htmlentities($result->dept); ?>" <?php if($user->instructor_department == $result->dept) echo 'selected'; ?>>
                                            <?php echo htmlentities($result->dept); ?>
                                        </option>
                                        <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Staff ID</label>
                                        <input type="text" value="<?php echo $user->staff_id ?? ''; ?>" class="form-control" name="staff_id">
                                    </div>
                                    <!-- <div class="form-group">
                                        <label>Courses</label>
                                        <input type="text" value="</?php echo $user->courses ?? ''; ?>" class="form-control" name="courses">
                                    </div> -->
                                <?php } ?>

                                <div class="form-group">
                                    <label>Registration Date</label>
                                    <input type="text" value="<?php echo $user->created_at; ?>" class="form-control" readonly>
                                </div>

                                <button type="submit" class="btn btn-primary mr-2" name="submit">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once('includes/footer.php'); ?>
    </div>
</div>
<?php } ?>
