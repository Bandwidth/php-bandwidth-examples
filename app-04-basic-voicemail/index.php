<html>
<head>
<title><?php echo $application->title; ?></title>
<?php require_once(__DIR__."/base.php"); ?>
<?php require_once(__DIR__."/../bootstrap.php"); ?>
</head>

<!-- 
  Catapult Bandwidth's Application 004 Interface 
  Basic VoiceMail. 
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
    <br />
      <p>
        <h4>Overview </h4>
        <br />
        Person:
          <ul class="normal-list">
            <li>- Dial the number displayed on the screen above</li>
            <li>- Once you are at the voicemail, you can leave a message</li>
            <li>- Hangup as you normally would</li>
          </ul>
        <hr />
        Application:
          <ul class="normal-list">
            <li>- Listen to active call events on the number</li>
            <li>- Waits for speech received</li>
            <li>- Fetches the mediaFileUrl for the event</li>
          </ul>
      </p>
      <hr />
      <h3>List of voicemail, so far:</h3>
      <?php if ($voicemailCnt > 0): ?>
        <table>
          <th>From</th>
          <th>To</th>
          <th>VoiceMail</th>
        <?php while ($vm = $voicemail->fetchArray()): 
          $voiceMailData = getRow(sprintf("SELECT * FROM %s WHERE callId = '%s'; ", $application->applicationDataTable, $vm['meta']));
        ?>
           <tr> 
              <td><?php echo $vm['from']; ?></td>
              <td><?php echo $vm['to']; ?></td>
              <td>
                <a href="./data/<?php echo $voiceMailData['recordingId']; ?>">
                Listen to mail
                </a>

              </td>
           </tr>
        <? endwhile; ?>
      <? else: ?>

        <small>This app has no voicemail yet..</small>

      <? endif; ?>

  </div>
</body>
</html>
