<?php
session_start();

// ゲーム初期化
if (!isset($_SESSION['money'])) {
    $_SESSION['money'] = 3000; // 初期所持金
}

if (isset($_POST['play'])) {
    // ゲームをプレイする
    if ($_SESSION['money'] >= 1000) {
        $_SESSION['money'] -= 1000; // 1000クレ消費

        // スロットの数字を生成
        $slots = [];
        for ($i = 0; $i < 3; $i++) {
            $slots[] = rand(0, 7);
        }

        //$_SESSION['slots'] = $slots; // スロットの結果をセッションに保存

        // 所持金の更新
        $multiplier = 0;
        if ($slots[0] === $slots[1] && $slots[1] === $slots[2]) {
            $multiplier = $slots[0] + 1; // 0が揃った場合、倍率は1（1000クレがプラス）
        }

        $_SESSION['money'] += 1000 * $multiplier;

        // ゲームクリアまたはGameOverチェック
        if ($_SESSION['money'] >= 10000) {
            $message = "ゲームクリア！所持金: " . $_SESSION['money'] . " クレ";
            session_destroy(); // ゲーム終了
        } elseif ($_SESSION['money'] <= 0) {
            header("Location: main.php"); // Game Overでmain.phpにリダイレクト
            exit();
        } else {
            $message = " | 所持金: " . $_SESSION['money'] . " クレ";
        }
    } else {
        $message = "所持金が足りません！残り: " . $_SESSION['money'] . " クレ";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>スロットゲーム</title>
    <style>
        .slot { font-size: 2em; margin: 10px; }
    </style>
    <script>
        let slots = [];
        let interval;
        let stopped = [false, false, false]; // 各スロットの停止状態を管理

        function startSlots() {
            if (stopped.every(s => s === false)&& <?php echo $_SESSION['money']; ?> >= 1000) { // すべてのスロットが停止していない場合
                slots = [0, 0, 0];
                interval = setInterval(() => {
                    for (let i = 0; i < 3; i++) {
                        if (!stopped[i]) { // 停止していないスロットだけ更新
                            slots[i] = Math.floor(Math.random() * 8); // 0から7の乱数を生成
                        }
                    }
                    document.getElementById("slot1").textContent = slots[0];
                    document.getElementById("slot2").textContent = slots[1];
                    document.getElementById("slot3").textContent = slots[2];
                }, 100);
				return false;//ファームの送信を防ぐ
            }
			return true;//ファームの送信を実行する
        }

        function stopSlot(index) {
            stopped[index - 1] = true; // 停止状態を記録
            document.getElementById(`slot${index}`).textContent = slots[index - 1]; // 数字を表示
            // すべてのスロットが停止した場合、インターバルをクリア
            if (stopped.every(s => s === true)) {
                clearInterval(interval);
            }
        }
    </script>
</head>
<body>
    <h1>スロットゲーム</h1>
    <p>所持金: <?php echo $_SESSION['money']; ?> クレ</p>
    <div>
        <div class="slot" id="slot1">0</div>
        <div class="slot" id="slot2">0</div>
        <div class="slot" id="slot3">0</div>
    </div>
    
    <form method="post"id="slotForm" onsubmit="return startSlots();">
    <button type="submit" name="play">スロットを開始</button>
    </form>
    <button onclick="stopSlot(1)">1番目を止める</button>
    <button onclick="stopSlot(2)">2番目を止める</button>
    <button onclick="stopSlot(3)">3番目を止める</button>
</body>
</html>
