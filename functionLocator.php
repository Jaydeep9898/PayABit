<?php
/**
 * Created by PhpStorm.
 * User: jaydeepkhambholja
 * Date: 2019-03-22
 * Time: 20:45
 */

class functionLocator
{
    public $price;
    public $countryCurrency;
    public $availabelAmount;
    public $afterTransactionAmount;
    public $totalReceived;
    public $totalSpend;
    public $aftertotalReceived;
    public $aftertotalSpend;
    public $satoshiValue = 25127.00;

    public function __construct($price, $currency){
        $this->price = $price;
        $this->countryCurrency = $currency;
        $this->availabelAmount = $this->getAvailabelAmount();
        $this->totalReceived = $this->getTotalReceived();
        $this->totalSpend = $this->getTotalSpend();
    }

    public function getLiveValue(){

        // create curl resource
        $ch = curl_init();
        // set url
        curl_setopt($ch, CURLOPT_URL, "https://blockchain.info/tobtc?currency=" . $this->countryCurrency . "&value=" . $this->price);
        // $output contains the output json
        $output = curl_exec($ch);
        // close curl resource to free up system resources
        curl_close($ch);
        // {"name":"Baron","gender":"male","probability":0.88,"count":26}
        //var_dump(json_decode($output, true));
        return $output;
    }

    public function getBitcoinValue(){


        $blockQuery = "https://blockchain.info/ticker";
        $query_content = file_get_contents($blockQuery);
        $query_content_array = json_decode($query_content, true);
        $a = $query_content_array[$this->countryCurrency]['last'];
        return $a;
    }

    public function getCountryCurrency(){
        return $this->countryCurrency;
    }

    public function getPrice(){
        return $this->price;
    }

    public function getAfterTransactionValues(){
         $this->afterTransactionAmount = $this->getAvailabelAmount();
         $this->aftertotalReceived = $this->getTotalReceived();
         $this->aftertotalSpend = $this->getTotalSpend();
    }

    function getAvailabelAmount($address = "13pZFT6RoGCaP4JzKhasojnG2yY2w9VYXp"){
        //$address = '1Ma2DrB78K7jmAwaomqZNRMCvgQrNjE2QC';
        $contains = urlencode($address);
        $blockQuery = "https://blockchain.info/rawaddr/".$contains;
        $query_content = file_get_contents($blockQuery);
        $query_content_array = json_decode($query_content, true);
        $a = $query_content_array['final_balance'];
        return $a;
        //var_dump($a);
    }

    function getTotalReceived($address = "13pZFT6RoGCaP4JzKhasojnG2yY2w9VYXp"){
        //$address = '1Ma2DrB78K7jmAwaomqZNRMCvgQrNjE2QC';
        $contains = urlencode($address);
        $blockQuery = "https://blockchain.info/rawaddr/".$contains;
        $query_content = file_get_contents($blockQuery);
        $query_content_array = json_decode($query_content, true);
        $a = $query_content_array['total_received'];
        return $a;
        //var_dump($a);
    }

    function getTotalSpend($address = "13pZFT6RoGCaP4JzKhasojnG2yY2w9VYXp"){
        //$address = '1Ma2DrB78K7jmAwaomqZNRMCvgQrNjE2QC';
        $contains = urlencode($address);
        $blockQuery = "https://blockchain.info/rawaddr/".$contains;
        $query_content = file_get_contents($blockQuery);
        $query_content_array = json_decode($query_content, true);
        $a = $query_content_array['total_sent'];
        return $a;
        //var_dump($a);
    }


    function convertToUSD()
    {
        $contains = urlencode($this->countryCurrency);
        $blockQuery = "https://www.freeforexapi.com/api/live?pairs=USD".$contains;
        $query_content = file_get_contents($blockQuery);
        $query_content_array = json_decode($query_content, true);
        $a = $query_content_array['rates']['USD'.$this->countryCurrency.'']['rate'];
        return round($a,2);
    }

    function convertToSatoshi(){
        $convert = round($this->price/$this->convertToUSD(),2);
        $convert = $convert*$this->satoshiValue;
        return $convert;
    }

    function getHashID($address = "13pZFT6RoGCaP4JzKhasojnG2yY2w9VYXp")
    {
        //$address = '1Ma2DrB78K7jmAwaomqZNRMCvgQrNjE2QC';
        $contains = urlencode($address);
        $blockQuery = "https://blockchain.info/rawaddr/" . $contains;
        $query_content = file_get_contents($blockQuery);
        $query_content_array = json_decode($query_content, true);
        $a = $query_content_array['txs'][0]['hash'];
        return $a;
    }

    function getStatus($transacationHash){
        $chainQuery = "https://chain.so/api/v2/is_tx_confirmed/BTC/".$transacationHash;
        $query_content = file_get_contents($chainQuery);
        $query_content_array = json_decode($query_content, true);
        $a = $query_content_array['status'];
        if ($a == 'success'){
            return $transacationHash;
        }else{
            return false;
        }
    }

    function fundInitialization(){
        if ($this->availabelAmount < $this->afterTransactionAmount) {
            if((($this->afterTransactionAmount-$this->availabelAmount) - $this->convertToSatoshi()) < 100){
                return true;
            }
        }
        return false;
    }
}