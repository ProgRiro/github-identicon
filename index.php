<?php

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $birth = $_POST["year"] . $_POST["month"] . $_POST["day"];
    $px = $_POST["px"];
    $color_tmp = [];
    $color_hash = md5(str_shuffle($birth));
    for ($i = 0; $i < 6; $i++)
        $color_tmp[$i] = $color_hash[$i];
    $color = "#" . implode($color_tmp);
} else {
    $birth = "20200202";
    $px = 8;
    $color = "#b76fcc";
}

$timestamp = (string) time();
$width = 250 / $px;
$rowWidth = $width * $px;
$hash;

// var_dump($color);
// var_dump($birth);
// var_dump($timestamp);

function for32bit($birth, $timestamp)
{
    $tmp = $birth . $timestamp;
    $hash = md5(str_shuffle($tmp));
    return $hash;
}

function for64bit($birth, $timestamp)
{
    $tmp = $birth . $timestamp;
    $h1 = md5(str_shuffle($tmp));
    $h2 = md5(str_shuffle($tmp));
    $hash = $h1 . $h2;
    return $hash;
}

function for96bit($birth, $timestamp)
{
    $tmp = $birth . $timestamp;
    $h1 = md5(str_shuffle($tmp));
    $h2 = md5(str_shuffle($tmp));
    $h3 = md5(str_shuffle($tmp));
    $hash = $h1 . $h2 . $h3;
    return $hash;
}

function for128bit($birth, $timestamp)
{
    $tmp = $birth . $timestamp;
    $h1 = md5(str_shuffle($tmp));
    $h2 = md5(str_shuffle($tmp));
    $h3 = md5(str_shuffle($tmp));
    $h4 = md5(str_shuffle($tmp));
    $hash = $h1 . $h2 . $h3 . $h4;
    return $hash;
}

function for160bit($birth, $timestamp)
{
    $tmp = $birth . $timestamp;
    $h1 = md5(str_shuffle($tmp));
    $h2 = md5(str_shuffle($tmp));
    $h3 = md5(str_shuffle($tmp));
    $h4 = md5(str_shuffle($tmp));
    $h5 = md5(str_shuffle($tmp));
    $hash = $h1 . $h2 . $h3 . $h4 . $h5;
    return $hash;
}

function makeIdenticon($hash, $px)
{
    $oddOreven = $px % 2;   // $pxが奇数だった場合は1, 偶数だった場合は0
    $identicon = array(array());
    $hashNum = 0;

    // 折り返し前
    for ($i = 0; $i < ($px / 2) + $oddOreven; $i++) {
        for ($j = 0; $j < $px; $j++) {
            $identicon[$i][$j] = $hash[$hashNum++];
        }
    }

    $hashNum = 0;

    // 折り返し部分
    for ($i = ($px / 2) - 1, $ii = ($px / 2) + $oddOreven; $ii < $px; $i--, $ii++) {
        for ($j = 0; $j < $px; $j++) {
            $identicon[$ii][$j] = $identicon[$i][$j];
        }
    }

    return $identicon;
}

switch ($px) {
    case 5:
        $hash = for32bit($birth, $timestamp);
        break;
    case 6:
    case 7:
    case 8:
        $hash = for64bit($birth, $timestamp);
        break;
    case 9:
        $hash = for96bit($birth, $timestamp);
        break;
    case 10:
    case 11:
        $hash = for128bit($birth, $timestamp);
        break;
    case 12:
        $hash = for160bit($birth, $timestamp);
        break;
}

$identicon = makeIdenticon($hash, $px);
// var_dump($hash);

$newIdenticon = array(array());
for ($i = 0; $i < $px; $i++) {
    for ($j = 0; $j < $px; $j++) {
        $tmp = hexdec($identicon[$i][$j]);
        $newIdenticon[$i][$j] = ($tmp % 2 == 1) ? 0 : 1;
    }
}
// var_dump($newIdenticon);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:description" content="みんな大好きな identicon をいつでも・どこでも生成します">
    <meta name="twitter:title" content="【Identicon Maker】">
    <meta name="twitter:url" content="https://identicon-maker.progriro.net/">
    <meta name="twitter:image" content="https://identicon-maker.progriro.net/imgs/forTwitter.png">
    <meta name="twitter:creator" content="@progriro">
    <meta name="twitter:site" content="@progriro">
    <title>Identicon Maker</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Federant&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/9b712899ea.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container text-center">
        <h1 class="title">Identicon Maker</h1>
        <div class="identicon" id="capture">
            <?php for ($i = 0; $i < $px; $i++) : ?>
                <div class="row" style="width: <?php echo $rowWidth; ?>px; margin: 0;">
                    <?php for ($j = 0; $j < $px; $j++) : ?>
                        <?php if ($newIdenticon[$j][$i] == 1) : ?>
                            <div class="on" style="height: <?php echo $width; ?>px; width: <?php echo $width; ?>px; background: <?php echo $color; ?>"></div>
                        <?php else : ?>
                            <div class="off" style="height: <?php echo $width; ?>px; width: <?php echo $width; ?>px;"></div>
                        <?php endif ?>
                    <?php endfor ?>
                </div>
            <?php endfor ?>
        </div>
        <small class="px"><?php echo "$px x $px" ?></small>

        <form class="identicon-form" name="identicon_form" action="" method="post">
            <h2 class="select-label">Input Any Date</h2>
            <div class="birth-box">
                <div class="birth-select birth">
                    <select id="select_year" name="year" required></select>
                </div>
                <div class="birth-select birth">
                    <select id="select_month" name="month" required></select>
                </div>
                <div class="birth-select birth">
                    <select id="select_day" name="day" required></select>
                </div>
            </div>
            <h2 class="select-label">Input Resolution</h2>
            <div class="birth-box">
                <div class="birth-select birth">
                    <select name="px" required>
                        <option value="5">5 x 5</option>
                        <option value="6">6 x 6</option>
                        <option value="7">7 x 7</option>
                        <option value="8" selected>8 x 8</option>
                        <option value="9">9 x 9</option>
                        <option value="10">10 x 10</option>
                        <option value="11">11 x 11</option>
                        <option value="12">12 x 12</option>
                    </select>
                </div>
            </div>
            <input class="submit-btn" type="submit" value="Regeneration">
            <div class="button-box">
                <a class="make-btn" onclick="makeIdenticon()">Download</a>
                <a id="download" href="" download="identicon.png" hidden></a>
            </div>
        </form>
        <a class="twitter-link" href="http://twitter.com/share?url=https://identicon-maker.progriro.net&text=【Identicon Maker】みんな大好きな identicon をいつでも・どこでも生成します" target="_blank"><i class="fab fa-twitter twitter-icon"></i><span class="twitter-label">Tweet</span></a>
    </div>
    <footer>
        <div class="icon-box">
            <ul class="icons">
                <li class="icon">
                    <a class="icon-link" href="https://twitter.com/progriro" target="_blank"><i class="fab fa-twitter twitter"></i></a>
                </li>
                <li class="icon">
                    <a class="icon-link" href="https://github.com/ProgRiro" target="_blank"><i class="fab fa-github github"></i></a>
                </li>
            </ul>
        </div>
        <small class="copyright">Copyright © 2020 ProgRiro All Rights Reserved.</small>
    </footer>

    <script src="./jquery-3.4.1.js"></script>
    <script src="./html2canvas.js"></script>
    <script src="./identicon.js"></script>
    <script src="./birth.js"></script>
</body>

</html>