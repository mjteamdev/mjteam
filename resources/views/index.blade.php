@extends('layout.layout')

@section('content')
<h2>결제페이지</h2>
{{--    <form action="/payment" method="post">--}}
{{--        @csrf--}}
{{--        상품<input type="text" name="name"><br>--}}
{{--        가격<input type="text" name="price"><br>--}}
{{--        주문자<input type="text" name="username"><br>--}}
{{--        <button type="submit">결제하기</button>--}}
{{--    </form>--}}
<!-- jQuery -->
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js" ></script>
<!-- iamport.payment.js -->
<script type="text/javascript" src="https://cdn.iamport.kr/js/iamport.payment-1.2.0.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

    <input id="name" value="이태호"/>
    <input id="classname" value="수학"/>
    <input id="price" value="1000"/>
    <button onclick="requestPay()">결제하기</button>
<script>

    function requestPay() {
        var IMP = window.IMP; // 생략 가능
        IMP.init("imp48474059"); // 가맹점 식별코드

        var name = $('#name').val();
        var price = $('#price').val();
        var classname = $('#classname').val();
        console.log(name,price,classname);

        IMP.request_pay({ // param
            pg: "html5_danal",
            pay_method: "",
            merchant_uid: "zxzx2222",
            name: classname,
            amount: price,
            buyer_email: "taeho9421@naver.com",
            buyer_name: name,
            buyer_tel: "010-4242-4242",
            buyer_addr: "서울특별시 종로구 부암동",
            buyer_postcode: "1111"
        }, function (rsp) { // callback
            if (rsp.success) {
                // 결제 성공 시 로직,
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type : "POST",
                    url : "/payment",
                    data : {
                        'merchant_uid': rsp.merchant_uid,
                        'imp_uid' : rsp.imp_uid,
                    },
                    success: function (data) {
                        console.log(data);
                    },
                    error : function (data) {
                        console.log("error:",data);
                    }
                })
            } else {
                // 결제 실패 시 로직,
                alert('결제취소')
            }
        });
    }
</script>
@endsection
