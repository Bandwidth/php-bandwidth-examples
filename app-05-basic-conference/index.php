<html>
<head>
<title><?php echo $application->title; ?></title>
<?php require_once(__DIR__."/base.php"); ?>
<?php require_once(__DIR__."/../bootstrap.php"); ?>
</head>

<!-- 
  Catapult Bandwidth's Application 005
  Lists the Basic conferences
 -->
<body>
  <?php generateMenu(); ?> 
  <div class="app-content">
     <h2><?php echo $application->applicationName; ?></h2>
    <div class="box">
      <div class="status <?php echo $status; ?>"></div>
      <small>Status:</small>
      <?php echo $message; ?>
    </div>
      <hr />

      <form method="POST" enctype="multipart/form-data" action="./add.php">
    <h4>Add an attendee</h2>
        <small>This adds an attendee to the conference</small>
        <label>E.164 Phone Number</label>
        <br />
        <input name="attendee" placeholder="e.g: +17192995670" />
        <input type="submit" />
      </form>
      <form method="POST" enctype="multipart/form-data" action="./initiate.php">
      <hr />
    <h4>Initiate it yourself</h4>
      <button type="form-control" type="submit" value="Initiate Call">Initiate Conference</button>
      <br />
      <small>This will start a conference, and perform the conference flow</small> 
      <hr />
      </form>

    <h3>List of conferences from this application</h3>
    <?php if ($conferencesCnt > 0): ?>
    <table>
      <th>Conference Id</th>
      <th>From</th>
      <th>Date</th>
      <th>Attendees</th>
    <?php while($entry = $conferences->fetchArray()): 
        // get the conference
        // attendees
        $conferenceAttendees = getRows(sprintf("SELECT * FROM %s WHERE conference_id = '%s'; ", $application->applicationDataTable, $entry['meta']));

        $attendees = "None yet.."; 
        if (sizeof($conferenceAttendees) > 0) {
          $attendees = "";
          foreach ($conferenceAttendees as $cs) {
            $attendees .= $cs['call_from'] . ", ";
          } 
        }

    ?>
       <tr> 
         <td><?php echo $entry['meta']; ?></td>
         <td><?php echo $entry['from']; ?></td>
         <td><?php echo $entry['date']; ?></td>
        <td><?php echo $attendees; ?></td>
       </tr>
    <?endwhile; ?> 
    </table>
    <? else: ?>
      Application has not initiated any conferences yet
    <? endif; ?>

  </div>
</body>

</html>
