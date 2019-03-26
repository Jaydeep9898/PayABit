<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <title>Amount</title>
</head>
<body onunload="<?php clearstatcache();?>">

<form action="welcome.php" method="post">
    <?php
    require_once("functionLocator.php");
    $dataObject = new functionLocator(0,"CAD");
    ?>
    <h1>The current Bitcoin exchange rate are for 1 BTC = <?php echo $dataObject->getBitcoinValue()?> <?php echo $dataObject->getCountryCurrency()?> / <?php echo round(($dataObject->getBitcoinValue()/$dataObject->convertToUSD()),2)?> USD</h1>
    <h3>Amount of Money to Transfer: <input type="text" name="amtTrans"><br></h3>
<!--    Bitcoin Address: <input type="text" name="bitAdd"><br>-->
    <input type="submit">
</form>

</body>
</html>