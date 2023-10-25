<?php
session_start();

function calculate($operand1, $operand2, $operation)
{ //四則計算
    switch ($operation) {
        case '+':
            return $operand1 + $operand2;
        case '-':
            return $operand1 - $operand2;
        case '*':
            return $operand1 * $operand2;
        case '/':
            if ($operand2 == 0) {
                return 'Error';
            }
            return $operand1 / $operand2;
        default:
            return null;
    }
}

$memory = $_SESSION['memory'] ?? 0; //メモリー変数
$currentDisplay = $_POST['currentDisplay'] ?? '0'; //現在の表示
$isNewOperation = $_SESSION['isNewOperation'] ?? false; //新しい操作のフラグ
$display = $currentDisplay;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['input'];

    switch ($input) {
        case 'CA':
            $_SESSION = array();
            $display = '0';
            break;

        case 'C':
            $display = '0';
            break;

        case 'M+':
            $memory += (float)$currentDisplay;
            $_SESSION['memory'] = $memory;
            $isNewOperation = true;
            break;

        case 'M-':
            $memory -= (float)$currentDisplay;
            $_SESSION['memory'] = $memory;
            $isNewOperation = true;
            break;

        case 'RM':
            $display = (string)$memory;
            $isNewOperation = true;
            break;

        case 'CM':
            $memory = 0;
            $_SESSION['memory'] = $memory;
            break;

        case '.':
            if (strpos($currentDisplay, '.') === false) {
                if ($currentDisplay === '0') {
                    $display = '0.';
                } else {
                    $display .= '.';
                }
                $isNewOperation = false;
            }
            break;

        case '=':
            if (isset($_SESSION['operation']) && isset($_SESSION['operand'])) {
                $firstOperand = (float)$_SESSION['operand'];
                $currentOperand = (float)$currentDisplay;
                $operation = $_SESSION['operation'];

                $result = calculate($firstOperand, $currentOperand, $operation);
                $display = (string)$result;

                unset($_SESSION['operand'], $_SESSION['operation']);
                $isNewOperation = true;
            }
            break;

        case '0':
        case '00':
            if ($currentDisplay !== '0') {
                $display .= $input;
            }
            break;

        case '+':
        case '-':
        case '*':
        case '/':
            if (isset($_SESSION['operation']) && isset($_SESSION['operand']) && !$isNewOperation) {
                $firstOperand = (float)$_SESSION['operand'];
                $currentOperand = (float)$currentDisplay;
                $operation = $_SESSION['operation'];
                $result = calculate($firstOperand, $currentOperand, $operation);
                $display = (string)$result;

                $_SESSION['operand'] = $display;
            } else {
                $_SESSION['operand'] = $currentDisplay;
            }
            $_SESSION['operation'] = $input;
            $isNewOperation = true;
            break;

        case '%':
            $display = (string)($currentDisplay / 100);
            $isNewOperation = true;
            break;

        default:
            if ($isNewOperation) {
                $display = $input;
                $isNewOperation = false;
            } elseif ($currentDisplay === '0') {
                $display = $input;
            } else {
                $display .= $input;
            }
            break;
    }

    $_SESSION['isNewOperation'] = $isNewOperation;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>電卓アプリ</title>
    <style>
        body {
            width: 350px;
            margin: 0 auto;
            text-align: center;
            padding-top: 50px;
        }

        input[type="text"] {
            width: 318px;
            height: 50px;
            font-size: 32px;
            padding: 10px;
            margin-bottom: 20px;
        }

        button {
            width: 60px;
            height: 60px;
            font-size: 24px;
            margin: 5px;
            border: none;
            cursor: pointer;
            float: left;
        }

        #equal {
            width: 130px;
        }
    </style>
</head>

<body>

    <form action="" method="post">
        <div>
            <input type="text" name="currentDisplay" readonly value="<?= htmlspecialchars($display, ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div>
            <button type="submit" name="input" value="CM">CM</button>
            <button type="submit" name="input" value="RM">RM</button>
            <button type="submit" name="input" value="M-">M-</button>
            <button type="submit" name="input" value="M+">M+</button>
            <button type="submit" name="input" value="CA">CA</button>
        </div>
        <div>
            <button type="submit" name="input" value="7">7</button>
            <button type="submit" name="input" value="8">8</button>
            <button type="submit" name="input" value="9">9</button>
            <button type="submit" name="input" value="%">%</button>
            <button type="submit" name="input" value="C">C</button>
        </div>
        <div>
            <button type="submit" name="input" value="4">4</button>
            <button type="submit" name="input" value="5">5</button>
            <button type="submit" name="input" value="6">6</button>
            <button type="submit" name="input" value="/">/</button>
            <button type="submit" name="input" value="*">*</button>
        </div>
        <div>
            <button type="submit" name="input" value="1">1</button>
            <button type="submit" name="input" value="2">2</button>
            <button type="submit" name="input" value="3">3</button>
            <button type="submit" name="input" value="+">+</button>
            <button type="submit" name="input" value="-">-</button>
        </div>
        <div>
            <button type="submit" name="input" value="0">0</button>
            <button type="submit" name="input" value="00">00</button>
            <button type="submit" name="input" value=".">.</button>
            <button id="equal" type="submit" name="input" value="=">=</button>
        </div>
    </form>

</body>

</html>