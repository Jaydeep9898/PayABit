<?php
require_once("functionLocator.php");
$dataObject = new functionLocator($_POST['amtTrans'], "CAD");
$returnData = ""; $txsHash = "";
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>QR Code</title>
    <script>let successRate = null;</script>

<!--When the page is load it looks into the blockchain api and looks for the parameters-->
<body onload="OnClickGenOR(<?php $dataObject->getLiveValue()?>);
        let interval = setInterval(function(){
                <?php
                $dataObject->getAfterTransactionValues();
                if ($dataObject->fundInitialization()){
//                    #URGENT We need review how would we get to know the amount transferred to the wallet.

                    $txsHash = $dataObject->getHashID();
                    ?>
//                    the successRate setinterval would look into the chain so api for getting the success or fail status every 10 seconds.
                     successRate = setInterval(function(){

                        <?php
                        $returnData = $dataObject->getStatus($txsHash);?>
                        console.log('Contacting chain.so api.');

                        },10000);
                    console.log('New Balance Received!');
                    clearInterval(interval);
               <?php }else{?>
                    console.log('Going Once more!');
               <?php } ?>},5000);
        " onunload="<?php clearstatcache();?>">


<h1>Current Exchange Rate for amount : <?php echo $dataObject->getPrice() ?> is <?php echo $dataObject->getLiveValue()?></h1>



<canvas id="qrCanvas" style="width:200px;height:200px;position:absolute;top:20%;right:20%;"></canvas>
<script type="application/javascript" src="javascript_modules/QR-Code-generator/javascript/qrcodegen.js"></script>
<script type="application/javascript" src="javascript_modules/QRgenerator.js"></script>

<h1><?php if ($returnData != false){
        echo '<h2> The Transaction Hash generated was '.$returnData.'</h2>';?>
        <script>document.getElementById("qrCanvas").style.visibility = "hidden";
                clearInterval(successRate);
        </script>
    <?php } ?></h1>
</body>
</html>

<!---->