<html>
<head>
<title><?php echo $application->title; ?></title>
<?php require_once(__DIR__."/base.php"); ?>
<?php require_once(__DIR__."/../bootstrap.php"); ?>
</head>

<!-- 
  Catapult Bandwidth's Application 001 Interface 
  Send Auto Replies to the incoming numbers
  then list them.
 -->
<body>
  <?php generateMenu(); ?> 
  <div class="app-content">
      <form method="POST" enctype="multipart/form-data" action="./initiate.php">
     <h2><?php echo $application->applicationName; ?></h2>
    <div class="box">
      <div class="status <?php echo $status; ?>"></div>
      <small>Status:</small>
      <?php echo $message; ?>
    </div>
    <h4>Initiate it yourself</h4>
      <button type="form-control" type="submit" value="Initiate Call">Initiate Conference</button>
      <br />
      <small>This will start a conference, and perform the conference flow</small> 
      <hr />

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
        $conferenceAttendees = getRows(sprintf("SELECT * FROM `%s` WHERE conferenceId = '%s'; ", $application->applicationDataTable, $entry['meta']));

        $attendees = ""; 
        foreach ($conferenceAttendees as $cs) {
          $attendees .= $cs['callFrom'] . ", ";
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
