<?php
// camera_map.php : 지도를 클릭해서 카메라 정류장을 등록하는 도구
header('Content-Type: text/html; charset=utf-8');
$saveFile = 'cameras.json';

// 저장 로직
if (isset($_POST['bsId'])) {
    $current = file_exists($saveFile) ? json_decode(file_get_contents($saveFile), true) : [];
    $current[$_POST['bsId']] = $_POST['msg'];
    file_put_contents($saveFile, json_encode($current, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    echo "<script>alert('✅ 등록 완료!'); location.href='camera_map.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>카메라 지점 등록</title>
    
    <script type="text/javascript" src="https://dapi.kakao.com/v2/maps/sdk.js?appkey=7b13b7fcb5e91627558af56e0a3fe252"></script>
    
    <style>
        body, html { margin:0; padding:0; height:100%; overflow:hidden; }
        #map { width:100%; height:100%; }
        .ui-box { position:fixed; bottom:20px; left:5%; width:90%; background:white; padding:15px; border-radius:15px; z-index:1000; box-shadow:0 0 20px rgba(0,0,0,0.3); display:none; }
        input { width:100%; padding:10px; margin-bottom:10px; border:1px solid #ccc; border-radius:8px; font-size:16px; }
        button { width:100%; padding:15px; background:#3b82f6; color:white; border:none; border-radius:8px; font-weight:bold; }
    </style>
</head>
<body>
    <div id="map"></div>
    
    <div id="uiBox" class="ui-box">
        <h4 id="targetTitle" style="margin:0 0 10px 0; color:#334155;">이 근처에 카메라가 있나요?</h4>
        <form method="POST">
            <input type="hidden" name="bsId" id="bsIdInput">
            <input type="text" name="msg" placeholder="예: 시지중학교 앞 50km 주의" required>
            <button type="submit">🚀 이 지점에 카메라 등록</button>
        </form>
    </div>

    <script>
        // 카카오 지도 로딩 에러 체크 로직
        if (typeof kakao === 'undefined') {
            alert("🚨 카카오 지도를 불러오지 못했습니다!\n\n1. 코드의 키가 'JavaScript 키'인지 확인하세요.\n2. 카카오 플랫폼 Web 도메인에 [http://ppackkom.ipdisk.co.kr:8443] 주소가 등록되었는지 확인하세요.");
        } else {
            // 정상 로드 시 지도 그리기
            var mapContainer = document.getElementById('map'), 
                mapOption = { center: new kakao.maps.LatLng(35.849, 128.765), level: 4 };
            var map = new kakao.maps.Map(mapContainer, mapOption);

            kakao.maps.event.addListener(map, 'click', function(mouseEvent) {
                var bsId = prompt("이 지점의 정류장 ID를 입력해주세요 (앱 하단에 나오는 10자리 숫자)");
                if(bsId) {
                    document.getElementById('bsIdInput').value = bsId;
                    document.getElementById('uiBox').style.display = 'block';
                }
            });
        }
    </script>
</body>
</html>