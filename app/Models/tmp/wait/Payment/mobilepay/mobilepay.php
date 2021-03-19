<?php

    class MobilePay{


        public static function send($order_ref_id,$callbackurl="",$accepturl = ""){

            
            $sql = "select * from settings_payment  where name = 'yourpay'";


            $yourpay = current(DB::select($sql));
            $prices  = Order::load_prices();
            $order   = Order::get();


            $live = $yourpay["live"];

            if($live){ $MerchantNumber = $yourpay["merchant_id"]; }
            else{ $MerchantNumber = $yourpay["test_merchant_id"]; } 


            $subtotal = $prices["subtotal"];

            // gang beløb op med 100 for at sende beløbet i kr. og øre;
            $subtotal *= 100;
            
            
            if($order["is_paid"]){

                return false;

            }



            $time = time();
            $amount = $subtotal; // Amount in lowest currency, 100 = 1 EUR
            $currencycode = 208; // CurrencyCode for your transaction. Click here to see a complete list of currency codes https://en.wikipedia.org/wiki/ISO_4217
            $orderid = $order["reference_number"]; // Yourpay orderID
            $ccrg = "1"; // If set to one, then subscriptions is activated
            $language = "da-dk"; // Language of payment page – Full list to be found at https://msdn.microsoft.com/en-us/library/ee825488(v=cs.20).aspx
            $comments = ""; // Additional comments to the transaction
            $ct = "off"; // If CT is set, the transaction-fee will be forwarded to the consumer (and paid by the consumer)
            $autocapture = "no"; //If autocapture is set to yes the transaction will be captured automaticly.
            

            return "https://payments.yourpay.se/betalingsvindue_summary.php?method=mobilepay&MerchantNumber=$MerchantNumber&ShopPlatform=DATAS&accepturl=$accepturl&callbackurl=$callbackurl&time=$time&use3d=0&amount=$amount&CurrencyCode=$currencycode&cartid=$orderid&lang=$language&ct=$ct&ccrg=$ccrg&comments=$comments&autocapture=$autocapture";

            
           

        }

    }

?>