<html>
<head>
<title><?php echo $application->applicationTitle; ?></title>
<?php require_once(__DIR__."/base.php"); ?>
<?php require_once(__DIR__."/../bootstrap.php"); ?>
</head>

<!-- 
  Catapult Bandwidth's Application 006 Interface 
  this will list the advanced conferences held
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
      <th>Gather Codes (access)</th>
    <?php while($conference = $conferences->fetchArray()): 
       $conferenceData = getRows(sprintf("SELECT * FROM %s WHERE conference_id = '%s'", $application->applicationDataTable, $conference['meta']));
       $codes = "";
       foreach ($conferenceData as $cd) {
          $codes .= $cd['receiver_call_from'] .":". $cd['code'] . "<br />";
       }


    ?>
       <tr> 
         <td><?php echo $conference['meta']; ?></td>
         <td><?php echo $conference['from']; ?></td>
        <td><?php echo $codes; ?></td>
       </tr>
    <?endwhile; ?> 
    </table>
    <? else: ?>
      Application has not initiated any conferences yet
    <? endif; ?>

  </div>
</body>

</html>
