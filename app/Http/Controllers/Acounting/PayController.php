<?php

namespace App\Http\Controllers\Acounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Acounting\Transations;
use App\Models\Acounting\UserAcounts;
use App\Models\Acounting\Paytransactions;
use App\Models\Personals\Personal;
use App\Models\Cunsomers\Cunsomer;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;

class PayController extends Controller
{
    public function pay(Request $request)
    {


        $paytr=Paytransactions::where('token',$request->paytoken)
        ->where('expire', '>', Carbon::now())
        ->where('successful',0)
        ->first();

        if(!$paytr){

            return 'امکان انجام تراکنش وجود ندارد';

        }

        $amount=$paytr->amount;



        $data = array('MerchantID' => 'xxxxxdxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
 'Amount' => $amount,
 'CallbackURL' => route('Acounting.Pay.Confirm').'?id='.$paytr->id,
 'Description' => 'پرداخت از اپ');
$jsonData = json_encode($data);
$ch = curl_init('https://sandbox.zarinpal.com/pg/rest/WebGate/PaymentRequest.json');
curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
 'Content-Type: application/json',
 'Content-Length: ' . strlen($jsonData)
));
$result = curl_exec($ch);
$err = curl_error($ch);
$result = json_decode($result, true);
curl_close($ch);
if ($err) {
 echo "cURL Error #:" . $err;
} else {
 if ($result["Status"] == 100) {

 header('Location: https://sandbox.zarinpal.com/pg/StartPay/' . $result["Authority"]);
 die();
 } else {
 echo'ERR: ' . $result["Status"];
 }
}

    }

    public function confirm(Request $request)
    {


        $pay = PayTransactions::find($request->id);

        if($pay->successful==1){
            return 'تراکنش به دلیل تکراری بودن نا موفق بود';
        }

        if($pay->type=='customer'){
            $member = Cunsomer::where('customer_mobile', $pay->mobile)->first();
        }else{
            $member = Personal::where('personal_mobile', $pay->mobile)->first();
        }


        $useracount=$member->useracounts[0];



        $Authority = $_GET['Authority'];
 $data = array('MerchantID' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', 'Authority' => $Authority, 'Amount' => $pay->amount);
 $jsonData = json_encode($data);
 $ch = curl_init('https://sandbox.zarinpal.com/pg/rest/WebGate/PaymentVerification.json');
 curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
 curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_HTTPHEADER, array(
 'Content-Type: application/json',
 'Content-Length: ' . strlen($jsonData)
 ));
 $result = curl_exec($ch);
$err = curl_error($ch);
 curl_close($ch);
 $result = json_decode($result, true);
 if ($err) {
 echo "cURL Error #:" . $err;
 } else {
 if ($result['Status'] == 100) {
    $pay->successful=1;
    $pay->desc=$result['RefID'];
    $pay->update();


    $transaction=new Transations;
    $transaction->user_acounts_id=$useracount->id;
    $transaction->type='واریز';
    $transaction->for='شارژ';
    $transaction->amount=$pay->amount;
    $transaction->description='پرداخت از طریق درگاه زرین پال';

    $transaction->save();


    $useracount->cash=$useracount->cash+$pay->amount;
    $useracount->update();

    $htt= '<section  style="display:block;margin: 0 auto;text-align: center;" id="info">
    <img style="display:block;margin: 0 auto;" src="https://tarfand3.com/wp-content/uploads/2020/01/blue-tick-tarfand3-2.png">
    <h1>پرداخت با موفقیت انجام گردید</h1>
    <p>کد تراکنش</p>'.$result['RefID'].'
    <p>لطفا به اپلیکیشن چهارسو بازگردید</p>
    <a href="http://www.4sooapp.com">چهارسو</a>
</section>';
 echo $htt;
 } else {
 echo 'پرداخت نا موفق بود:' . $result['Status'].'</br> لطفا به اپلیکیشن بازگردید و مجددا تلاش کنید';
 }
 }

    }

}
