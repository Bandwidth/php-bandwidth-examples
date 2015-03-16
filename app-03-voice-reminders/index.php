<html>
<head>
<title><?php echo $application->title; ?></title>
<?php require_once("base.php"); ?>
<?php require_once("../bootstrap.php"); ?>
</head>

<!-- 
  Catapult Bandwidth's Application 001 Interface 
  Send Auto Replies to the incoming numbers
  then list them.
 -->
<body>
  <?php generateMenu(); ?> 
  <div class="app-content">
      <form method="POST" enctype="multipart/form-data">
     <h2><?php echo $application->applicationName; ?></h2>
    <div class="box">
      <div class="status <?php echo $status; ?>"></div>
      <small>Status:</small>
      <?php echo $message; ?>
    </div>

    <div>
      <br />
      <p>
        <h4>Overview</h4>
        <br />
        Person:
          <ul class="normal-list">
            <li>- Dial the number displayed on the screen above</li>
            <li>- Listen for voice reminder instructions. </li>
            <li>- Enter valid information for the segments </li>
            <li>- Hangup as you normally would </li>
          </ul>
        <hr />
        Application:
          <ul class="normal-list">
            <li>- Listen to active call events on the number</li>
            
            <li>- Waits for digits to be inputted</li>
            <li>- Fetches the DTMF used in the Gather</li>
          </ul>
      </p>

      <hr />
      <h3>List of reminders, so far:</h3>
      <table>
      <?php if ($remindersCnt > 0): ?>
        <th>From</th>
        <th>To</th>
        <th>Date</th>
        <th>Reminder Recording</th>

        <?php while ($reminder = $reminders->fetchArray()): 
            // now we need to get the
            // reminder data for this call
            $reminderData = getRow(sprintf("SELECT * FROM `%s` WHERE callId = '%s'; ", $application->applicationDataTable, $reminder['meta']));
            $date = makeDate($reminderData);
          ?>
          <tr>
            <td><?php echo $reminder['from']; ?></td>
            <td><?php echo $reminder['to']; ?></td>
            <td><?php echo $date->format("Y-m-d"); ?></td>
            <td><a href="./play.php?recordingId=<?php echo $reminderData['recordingId']; ?>">Play Reminder</a></td>
          </tr>

        <? endwhile; ?>
      </table>


      <? else: ?>
        <small>You currently have no reminders..</small>
      <?endif; ?>

      
    </div>

  </div>
</body>
<html>
