<html> <head>
<?php require_once(__DIR__."/base.php"); ?>
<?php require_once(__DIR__."/form.php"); ?>
<?php require_once(__DIR__."/../bootstrap.php"); ?>
<title><?php echo $application->applicationTitle; ?></title>
</head>

<!--
  Catapult Transaction listener, this will
  list the following transactions:
  charge, 
  payment,
  credit,
  good-will

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
    <form name="transactions" method="POST" action="./">
      <h2>Start Date</h2>
      <!-- HTML5 date fields -->
      <input type="date" name="start_date" />
      <h2>End Date</h2>
      <input type="date" name="end_date" />

      <input type="submit" name="process" />
    </form>
    <hr />
    <?php if (isset($transactions)): ?>
      <table>
        <thead>
          <?php foreach ($headers as $header): ?>
            <th><?php echo $header; ?></th>
          <? endforeach; ?>
        </thead>
            
      <?php foreach ($transactions as $transactionType): // seperate by the transactiontype ?>
         <?php foreach ($transactionType as $productType => $transaction): ?>
            <?php foreach ($transaction as $numberVal => $number): ?>
              <tr>
              <?php foreach ($headers as $header): // treat productType and transactionType different ?> 
                  <?php if ($header == "productType"): ?>
                    <td><?php echo $productType; ?></td>
                  <? elseif ($header == "transactionType"): ?>
                      <td><?php echo $transactionType; ?></td>
                  <? elseif ($header == "number"): ?>
                      <td><?php echo $numberVal; ?></td>
                  <? else: ?>
                   <td><?php echo $number[$header]; ?></td>
                  <? endif; ?>
               </tr>
              <? endforeach; ?>
            <? endforeach; ?>
         <? endforeach; ?>
      <? endforeach; ?>

      </table>

    <? else: ?>
      <h2>Please use the form above to list your transactions</h2>
    <? endif; ?>
 </div>
</body>
</html>

