<?php
$host ="localhost";
$username ="root";
$password ="";
$dbName ="coursat";
$con = mysqli_connect($host ,$username ,$password ,$dbName);

//create
if(isset($_POST["submit"])){
    $name= $_POST['name'];
    $phone= $_POST['phone'];
    $gender= $_POST['gender'];
    $courseId= $_POST['courseId'];
    $instructorId= $_POST['instructorId'];
    $image_name = rand(0,255).rand(0,255).$_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $location = "./upload/" . $image_name ;
move_uploaded_file($image_tmp,$location);   
//insert query
$insert = "INSERT INTO students VALUES (NULL,'$name','$phone','$gender','$image_name','$courseId','$instructorId')";
$insertQuery = mysqli_query($con,$insert);
}

//empty variables
$mode = "create";
$name= "";
$phone= "";
$gender="";
$image= Null;
$courseId= "";
$instructorId= "";
$userid=NULL;

//edit show
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $selectOne = "SELECT * FROM StudentsJoinCourses WHERE stID = $id";
    $getOne = mysqli_query($con, $selectOne);
    $row = mysqli_fetch_assoc($getOne);
    $name = $row["stName"];
    $phone = $row["phone"];
    $gender = $row['gender'];
    $image = $row['image'];
    $courseId = $row['coName'];
    $instructorId = $row['inName'];
    $mode = "update";
    $userid = $id;

}
//edit
if(isset($_POST["update"])){
    $name= $_POST["name"];
    $phone= $_POST["phone"];
    $gender= $_POST["gender"];
    $courseId= $_POST["courseId"];
    $instructorId= $_POST["instructorId"];
    if($_FILES['image']['name'] == null){
        $image_name = $image;
            }else{
            $image_name = rand(0,255).rand(0,255). $_FILES['image']['name'];
            $image_tmp = $_FILES['image']['tmp_name'];
            $location = "./upload/" . $image_name ;
            move_uploaded_file($image_tmp,$location);
            unlink("./upload/$image");
            }
    $update= "UPDATE students SET `name` = '$name', `phone` = '$phone', `gender` = '$gender',image = '$image_name', `courseId` = '$courseId', `instructorId` = '$instructorId' WHERE id = $userid";
    $updateQuery= mysqli_query($con,$update);
    $mode = "create";
    header('Location: coursat.php');
}

//delete query
if(isset($_GET['delete'])){
    $id=$_GET['delete']; 
    $selectoneDelete = "SELECT * FROM students WHERE id = $id";
    $selectoneDeleteQuery = mysqli_query($con,$selectoneDelete);
    $rowdDataDeleted = mysqli_fetch_assoc($selectoneDeleteQuery );
    $oldimage = $rowdDataDeleted ['image'];
    unlink("./upload/$oldimage");   
    $delete= "DELETE FROM students WHERE id = $id";
    $deleteQuery = mysqli_query($con,$delete);
    header("Location: coursat.php");
}
//select query
$select = "SELECT * FROM StudentsJoinCourses";
$selectQuery = mysqli_query ($con,$select);

$selectCourse = "SELECT * FROM courses";
$Courses = mysqli_query($con,$selectCourse);

$selectInstructor = "SELECT * FROM instructors";
$Instructors = mysqli_query($con,$selectInstructor);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CORSAT</title>
    <!--Link css bootstrap-->
    <!--Link js bootstrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="./style.css"> -->
</head>
<body style="background-color: black;">
    <div class="container col-7 py-5">
        <div class="row justify-content-center mt-5">
            <div class="col-12">
                <div class="card bg-dark text-light">
                    <div class="card-body">
                        <form method="POST"  enctype="multipart/form-data">
                            <div class="form-group mb-3" >
                                <label for="name" class="form-label"> Name </label>
                                <input type="text" placeholder="Enter your Name" value="<?= $name ?>" class="form-control" name="name">
                            </div>
                            <div class="form-group mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" placeholder="Enter your Phone number" value="<?= $phone ?>" class="form-control" name="phone">
                            </div>
                            <div class="form-group mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select name="gender" id="gender" class="form-select">
                                    <?php if ($gender == "male") :?>
                                    <option selected value="male">Male</option>
                                    <option value="female">Female</option>
                                    <?php elseif ($gender == "female") :?>
                                    <option value="male">Male</option>
                                    <option selected value="female">Female</option>
                                    <?php else : ?>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <?php endif ; ?>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                            <label> Student Image : <?php if($image != null ): ?> <img width = 60 src="./upload/<?= $image?>" alt="" ><?php endif;?>  </label>
                            <input type = "file" accept="image/*" name="image" class = "form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label for="courseId" class="form-label">Course</label>
                                <select name="courseId" class="form-select">
                                    <?php foreach ($Courses as $course) : ?>
                                        <option value="<?= $course['id'] ?>" <?php if ($courseId == $course['id']) echo 'selected'; ?>>
                                        <?= $course['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="instructorId" class="form-label">Instructor</label>
                                <select name="instructorId" class="form-select">
                                    <?php foreach ($Instructors as $instructor) : ?>
                                        <option value="<?= $instructor['id'] ?>" <?php if ($instructorId == $instructor['id']) echo 'selected'; ?>>
                                        <?= $instructor['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="text-center form-group">
                            <?php if ($mode == "create") :?>
                                <button name="submit" class="btn btn-primary">Add Student</button>
                            <?php else : ?>
                                <button name="update" class="btn btn-primary">Update Student</button>
                                <a href="Coursat.php" class="btn btn-warning">Cancel</a>
                            <?php endif ; ?>
                            </div>
                                                       
                        </form>

<div class="col-12">
    <table class="table table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Gender</th>
            <th>Image</th>
            <th>Course</th>
            <th>Instructor</th>
            <th colspan="2">Action</th>
        </tr>
        <?php foreach ($selectQuery as $student) : ?>
        <tr>
            <td><?= $student['stID']?></td>
            <td><?= $student['stName']?></td>
            <td><?= $student['phone']?></td>
            <td><?= $student['gender']?></td>
            <td><img width = 70 src="./upload/<?= $student['image']?>" alt =""></td>
            <td><?= $student['coName']?></td>
            <td><?= $student['inName']?></td>
            <td> <a href="?edit=<?= $student['stID']?>" class= "btn btn-info">Edit</a></td>
            <td> <a href="?delete=<?= $student['stID']?>" class= "btn btn-danger">Delete</a></td>
        </tr>
<?php endforeach;?>
    </table>
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
