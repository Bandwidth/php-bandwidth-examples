<!DOCTYPE HTML>
<?php
require_once(__DIR__."/base.php");
?>
<html>
<head>
<!--
  Application 002
  Call transfers
-->
<title><?php echo $application->applicationName; ?></title>
<?php require_once(__DIR__."/../bootstrap.php"); ?>
</head>
  <body>
    <div style="float:left;width:30%; ">
    <?php generateMenu(); ?>
    </div>
    <div class="app-content">
      <form method="POST" action="./initiate.php">
      <h2><?php echo $application->applicationName; ?></h2>
      <div class="box">
        <div class="status <?php echo $status; ?>"></div>
        <small>Status:</small>
        <?php echo $message; ?>
      </div>
      <h4>Initiate it yourself</h4>
      <button type="form-control" type="submit" value="Initiate Call">Initiate Call</button>
      <br />
      <small>This will make the call on your behalf and make a transfer</small> 
      <hr />
    


      <h3>Heres all the calls catapult has forwarded so far:</h3>
      <?php if ($callCnt > 0): ?>
          <div class="innerbox">
              <h5>Looks like we found <?php echo $callCnt; ?> transfers</h45>
          </div>
          <table>
          <th>From</th>
          <th>To</th>
          <th>State</th>
        <?php while ($call = $calls->fetchArray()): ?>
          <tr> 
            <td><?php echo $call['from']; ?></td>
            <td><?php echo $call['to']; ?></td>
            <td><?php echo $call['meta']; ?></td>
          </tr>
        <? endwhile; ?>
          </table>
      <? else: ?>
        <small>There are no transfer calls, yet..</small>
      <? endif; ?>
      </form>
      </div>
  </body>
</html>
