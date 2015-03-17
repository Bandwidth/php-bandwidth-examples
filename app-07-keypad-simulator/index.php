<html>
<head>
<title><?php echo $application->title; ?></title>
<?php require_once(__DIR__."/base.php"); ?>
<?php require_once(__DIR__."/../bootstrap.php"); ?>
</head>

<!-- 
  Catapult Bandwidth's Application 007 Interface 
  This will list the keypad simulator trials.
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
      
    <h3>List of keypad simulations from this application</h3>
    <?php if ($simulationsCnt > 0): ?>
    <table>
      <th>From</th>
      <th>To</th>
      <th>Call ID</th>
    <?php while($entry = $simulations->fetchArray()): ?>
       <tr> 
         <td><?php echo $entry['from']; ?></td>
         <td><?php echo $entry['to']; ?></td>
        <td><?php echo $entry['meta']; ?></td>
       </tr>
    <?endwhile; ?> 
    </table>
    <? else: ?>
      Application has recorded any keypad simulations yet..
    <? endif; ?>

       
         
         
        <hr />
        <h4>Overview </h4>
        <p>
        
        Keypad simulator is a quick way to simulate a fully capable keypad service
        it will provide an easy way to capture DTMF keys<br />
        sequentially save them using SQLite
        and even perform transfers!
        </p> 
        <br />
        Person:
          <ul class="normal-list">
            <li>- Dial the number displayed on the screen above</li>
            <li>- Once you are provided instruction on keypad combinations, you should follow what you want</li>
            <li>- Hangup as you normally would</li>
          </ul>
        <hr />
        Application:
          <ul class="normal-list">
            <li>- Listen to active call events on the number</li>
            <li>- Store each DTMF event -- associate with incoming numbers</li>
            <li>- Output instruction speech according to application settings</li>
          </ul>
      </p>

  </div>
</body>

</html>
