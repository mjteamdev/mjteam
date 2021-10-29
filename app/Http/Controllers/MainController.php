<?php

namespace App\Http\Controllers;

use App\Models\Pay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MainController extends Controller
{
    public function index() {
        return view('/index');
    }

    public function payment(Request $request) {
    $result = ["code"=>200, "message"=>"success"];

        //아임포트 관리자 페이지의 시스템설정->내정보->REST API 키 값을 입력한다.
        $imp_key = "1236341604079525";
        //아임포트 관리자 페이지의 시스템설정->내정보->REST API Secret 값을 입력한다.
        $imp_secret = "0951e767b1103e1e5dbb743d279e9c9c5aceb2f29e593469b148152e054e62806d7715d31f33afed";
        //결제 모듈을 호출한 페이지에서 ajax로 넘겨받은 merchant_uid값을 저장한다.
        $merchant_uid = $request->input('merchant_uid');
        $imp_uid = $request -> input('imp_uid');

        try {
            $getToken = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post('https://api.iamport.kr/users/getToken', [
                'imp_key' => $imp_key,
                'imp_secret' => $imp_secret,
            ]);
            $getTokenJson = json_decode($getToken, true);

            $access_token = $getTokenJson['response']['access_token'];

            $getPaymentData = Http::withHeaders([
                'Authorization' => $access_token
            ])->get('https://api.iamport.kr/payments/'.$imp_uid);
//
            $getPaymentDataJson = json_decode($getPaymentData, true);
//
            //아임포트에 요청한 실제 결제 정보
            $responseData = $getPaymentDataJson['response'];
//            //아임포트 결제 상태 값 (paid가 정상 결제 된 값)
//            $iamport_status = $responseData['status'];

            // 실제 결제 금액
            $amount = $responseData['amount'];
            // 수강 과목
            $classname = $responseData['name'];
            // 결제자
            $buyer_name = $responseData['buyer_name'];
            // 주문번호
            $merchant_uid = $responseData['merchant_uid'];

            Pay::create([
                'price'=>$amount,
                'classname'=>$classname,
                'buyer_name'=>$buyer_name,
                'merchant_uid'=>$merchant_uid
            ]);

            } catch (\Exception $e) {
            $getPaymentDataJson = "실패";
            }
            return $getPaymentDataJson;
    }
}
