<?php
// Connect to the database
$db = new PDO("mysql:host=localhost;dbname=timetable", "root", "");

$id = 0;
$subject = '';
$teacher = '';
$room = '';
$day = '';
$start_time = '';
$end_time = '';
$update = false;

// Check if the form is submitted
if (isset($_POST['submit'])) {
  // Retrieve form data
  $subject = $_POST['subject'];
  $teacher = $_POST['teacher'];
  $room = $_POST['room'];
  $day = $_POST['day'];
  $start_time = $_POST['start_time'];
  $end_time = $_POST['end_time'];

  // Insert data into the database
  $sql = "INSERT INTO timetable (subject, teacher, room, day, start_time, end_time) VALUES (:subject, :teacher, :room, :day, :start_time, :end_time)";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':subject', $subject);
  $stmt->bindParam(':teacher', $teacher);
  $stmt->bindParam(':room', $room);
  $stmt->bindParam(':day', $day);
  $stmt->bindParam(':start_time', $start_time);
  $stmt->bindParam(':end_time', $end_time);
  $stmt->execute();

  // Redirect to index.php after submission
  header('location: index.php');
  exit();
}

// Code for handling edit
if (isset($_GET['edit_id'])) {
  $id = $_GET['edit_id'];

  // Retrieve data for the selected ID from the database
  $sql = "SELECT * FROM timetable WHERE id=:id";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  // Set the form data for editing
  $subject = $row['subject'];
  $teacher = $row['teacher'];
  $room = $row['room'];
  $day = $row['day'];
  $start_time = $row['start_time'];
  $end_time = $row['end_time'];

  $update = true; // Set the flag to indicate an update operation
}

// Code for handling update
if (isset($_POST['update'])) {
  $id = $_POST['id'];

  // Retrieve form data for updating
  $subject = $_POST['subject'];
  $teacher = $_POST['teacher'];
  $room = $_POST['room'];
  $day = $_POST['day'];
  $start_time = $_POST['start_time'];
  $end_time = $_POST['end_time'];

  // Update data in the database
  $sql = "UPDATE timetable SET subject=:subject, teacher=:teacher, room=:room, day=:day, start_time=:start_time, end_time=:end_time WHERE id=:id";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':subject', $subject);
  $stmt->bindParam(':teacher', $teacher);
  $stmt->bindParam(':room', $room);
  $stmt->bindParam(':day', $day);
  $stmt->bindParam(':start_time', $start_time);
  $stmt->bindParam(':end_time', $end_time);
  $stmt->bindParam(':id', $id);
  $stmt->execute();

  // Redirect to index.php after update
  header('location: index.php');
  exit();
}

// Code for handling delete
if (isset($_GET['delete_id'])) {
  $id = $_GET['delete_id'];

  // Delete data from the database
  $sql = "DELETE FROM timetable WHERE id=:id";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':id', $id);
  $stmt->execute();

  // Redirect to index.php after deletion
  header('location: index.php');
  exit();
}

// Retrieve all data from the database for displaying in the table
$sql = "SELECT * FROM timetable";
$stmt = $db->query($sql);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="style.css">
  <title>Timetable Generator</title> 
</head>
<body>
  <h1>Timetable Generator</h1>

  <form action="index.php" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <label for="subject">Subject</label>
    <input type="text" name="subject" id="subject" class="form-control" value="<?php echo $subject; ?>">
    <label for="teacher">Teacher</label>
    <input type="text" name="teacher" id="teacher" class="form-control" value="<?php echo $teacher; ?>">
    <label for="room">Room</label>
    <input type="text" name="room" id="room" class="form-control" value="<?php echo $room; ?>">
    <label for="day">Day</label>
    <input type="text" name="day" id="day" class="form-control" value="<?php echo $day; ?>">
    <label for="start_time">Start Time</label>
    <input type="time" name="start_time" id="start_time" class="form-control" value="<?php echo $start_time; ?>">
    <label for="end_time">End Time</label>
    <input type="time" name="end_time" id="end_time" class="form-control" value="<?php echo $end_time; ?>">
    <?php if ($update == true): ?>
      <center>
        <input type="submit" name="update" value="Update" class="btn btn-primary">
        <a href="index.php" class="btn btn-secondary">Cancel</a>
      </center>
    <?php else: ?>
      <center>
        <input type="submit" name="submit" value="Submit" class="btn btn-primary">
      </center>
    <?php endif; ?>
  </form>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>Subject</th>
        <th>Teacher</th>
        <th>Room</th>
        <th>Day</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($results as $row): ?>
        <tr>
          <td><?php echo $row["subject"]; ?></td>
          <td><?php echo $row["teacher"]; ?></td>
          <td><?php echo $row["room"]; ?></td>
          <td><?php echo $row["day"]; ?></td>
          <td><?php echo $row["start_time"]; ?></td>
          <td><?php echo $row["end_time"]; ?></td>
          <td>
            <a href="index.php?edit_id=<?php echo $row["id"]; ?>" class="btn btn-primary edit-btn">Edit</a>
            <a href="index.php?delete_id=<?php echo $row["id"]; ?>" class="btn btn-danger delete-btn">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
