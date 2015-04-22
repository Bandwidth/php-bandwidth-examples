<?php 
require_once(__DIR__."/lib/php-bandwidth/source/Catapult.php");
$client = new Catapult\Client('u-mmuxnl7o2u2ijsdg2hrwdsq', 't-54dstcehimsulytk4du6nxy', '5de7mz4ncmqn5odnjvkg44melos6l2uvo2gzy4a');
$transactions = new Catapult\TransactionCollection;
$typeOfTransactions = "credit";
$transactions->listAll(array("size" => 1000, "type" => $typeOfTransactions));
 
$numberCharges = array();
// order the transactions
// by their numbers producing
// a grand total for each number
foreach ($transactions->get() as $transaction) {
   $numberCharges[$transaction->number] += $transaction->amount;
}
// output the number's transactions
// onto our screen
printf("Under this Catapult account you have the following charges:" . PHP_EOL);
foreach ($numberCharges as $number => $charge) {
  printf("Number: %s\t Charge: %s %s", $number, $charge, PHP_EOL);
}
 
?>
