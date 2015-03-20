<html>
<head>
<title><?php echo $application->title ?></title>
<?php require_once("base.php"); ?>
<?php require_once("../bootstrap.php"); ?>
</head>

<!--
 Catapult Bandwidth's Applicatin 008 BaML Call transfers
 this will perform baML call transfers
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
      <button type="form-control" type="submit" value="Initiate Call">Initiate Call</button>
      <br />
      <small>This will send a message, and perform the auto reply flow</small> 
      <hr />

    <h3>Overview</h3>
       <hr />
       <h4>Person:</h4>
       <li>
       - You can dial the number displayed above and you should receive a 
       call transfer, similar to Application 002
       </li>

       <hr />
       <h4>Application:</h4>
       <li> 
       - Set Content-Type to "application/xml"
       </li>
       <li>
       - Use Catapult BaML objects in your callback
       </li>
       <li>
       - output generated contents
       </li>

    <hr />

    <h3>List of transfers from this application</h3>
    <?php if ($transferCnt > 0): ?>
    <table>
      <th>From</th>
      <th>To</th>
      <th>Markup Generated</th>
    <?php while($entry = $transfers->fetchArray()): 
    // Ready our markup 
    // for screen pres
    
    $xml = xml_entities(xml_prettify(unserialize($entry['meta']))); 
    ?>
       <tr> 
         <td><?php echo $entry['from']; ?></td>
         <td><?php echo $entry['to']; ?></td>
        <td><pre style="white-space: pre; width: 450px;"><?php echo xml_entities(unserialize($entry['meta'])); ?></pre></td>
       </tr>
    <?endwhile; ?> 
    </table>
    <? else: ?>
      Application has not generated BaML yet..
    <? endif; ?>

  </div>
</body>

</html>
