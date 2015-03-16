<html>
<head>
<title><?php echo $application->applicationTitle; ?></title>
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
      <button type="form-control" type="submit" value="Initiate Call">Initiate SMS</button>
      <br />
      <small>This will send a message, and perform the auto reply flow</small> 
      <hr />

    <h3>List of auto replies from this application</h3>
    <?php if ($messagesCnt > 0): ?>
    <table>
      <th>From</th>
      <th>To</th>
      <th>Text</th>
    <?php while($entry = $messages->fetchArray()): ?>
       <tr> 
         <td><?php echo $entry['from']; ?></td>
         <td><?php echo $entry['to']; ?></td>
        <td><?php echo $entry['meta']; ?></td>
       </tr>
    <?endwhile; ?> 
    </table>
    <? else: ?>
      Application has not sent any auto replies yet..
    <? endif; ?>

  </div>
</body>

</html>
