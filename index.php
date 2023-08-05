<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
  <title>Timetable Generator</title> 
</head>
<body>
  <h1>Timetable Generator</h1>

  <form action="index.php" method="post">
    <label for="subject">Subject</label>
    <input type="text" name="subject" id="subject" class="form-control">
    <label for="teacher">Teacher</label>
    <input type="text" name="teacher" id="teacher" class="form-control">
    <label for="room">Room</label>
    <input type="text" name="room" id="room" class="form-control">
    <label for="day">Day</label>
    <input type="text" name="day" id="day" class="form-control">
    <label for="start_time">Start Time</label>
    <input type="time" name="start_time" id="start_time" class="form-control">
    <label for="end_time">End Time</label>
    <input type="time" name="end_time" id="end_time" class="form-control">
    <center><input type="submit" name="submit" value="Submit" class="btn btn-primary" ></center>
  </form>

  <?php
  // Connect to the database
  $db = new PDO("mysql:host=localhost;dbname=timetable", "root", "");

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
    $sql = "INSERT INTO timetable (subject, teacher, room, day, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->execute([$subject, $teacher, $room, $day, $start_time, $end_time]);

    // Redirect the user to the timetable page
    header("Location: index.php");
  }


  // Display the timetable
  $sql = "SELECT * FROM timetable";
  $results = $db->query($sql);
  ?>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>Subject</th>
        <th>Teacher</th>
        <th>Room</th>
        <th>Day</th>
        <th>Start Time</th>
        <th>End Time</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($results as $row) {
        echo "<tr>";
        echo "<td>" . $row["subject"] . "</td>";
        echo "<td>" . $row["teacher"] . "</td>";
        echo "<td>" . $row["room"] . "</td>";
        echo "<td>" . $row["day"] . "</td>";
        echo "<td>" . $row["start_time"] . "</td>";
        echo "<td>" . $row["end_time"] . "</td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>
</body>
</html>
