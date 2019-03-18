<?php

$servername = "localhost";
$username = "mikle";
$password = "PassWord123!";
$dbname = "blog";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection faild:" . $conn->connect_error);
}

if (count($_POST) > 0) {
    $name = trim($_POST['user_name']);
    $tema = trim($_POST['tema']);
    $feedback = trim($_POST['feedback']);


    if (empty($name)) {
        mysqli_close();
    } else {
        mysqli_query($mysql_connect, "SET NAMES 'utf8';");
        mysqli_query($mysql_connect, "SET CHARACTER SET 'utf8';");
        mysqli_query($mysql_connect, "SET SESSION collation_connection = 'utf8_general_ci';");
        $sql = "INSERT INTO users (name, tema, feedback) VALUE ('$name', '$tema', '$feedback')";
        $conn->query($sql);
    }
}


$num = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$result00 = "SELECT COUNT(id) as total FROM users";
$temp = $conn->query($result00)->fetch_assoc();
$total = intval($temp['total']);
$offset = ($page-1) * $num;

if ($page !=1) $firstpage = '<a href="index.php?page=1">первая</a> | <a href="index.php?page="' . ($page - 1) .'>Предыдущая</a> |';
if ($page !=$total) $nextpage = '<a href="index.php?page="' . ($page +1) .'>Следующая</a> | <a href="index.php?page="' . $total .'>Последняя</a>';

if ($page - 5 > 0) $page5left = '<a href=index.php?page=' . ($page - 5) .'>'. ($page - 5) .'</a> |';
if ($page - 4 > 0) $page4left = '<a href=index.php?page=' . ($page - 4) .'>'. ($page - 4) .'</a> |';
if ($page - 3 > 0) $page3left = '<a href=index.php?page=' . ($page - 3) .'>'. ($page - 3) .'</a> |';
if ($page - 2 > 0) $page2left = '<a href=index.php?page=' . ($page - 2) .'>'. ($page - 2) .'</a> |';
if ($page - 1 > 0) $page1left = '<a href=index.php?page=' . ($page - 1) .'>'. ($page - 1) .'</a> |';

if ($page + 5 <= $total) $page5right = ' | <a href=index.php?page=' . ($page + 5) .'>'. ($page + 5) .'</a>';
if ($page + 4 <= $total) $page4right = ' | <a href=index.php?page=' . ($page + 4) .'>'. ($page + 4) .'</a>';
if ($page + 3 <= $total) $page3right = ' | <a href=index.php?page=' . ($page + 3) .'>'. ($page + 3) .'</a>';
if ($page + 2 <= $total) $page2right = ' | <a href=index.php?page=' . ($page + 2) .'>'. ($page + 2) .'</a>';
if ($page + 1 <= $total) $page1right = ' | <a href=index.php?page=' . ($page + 1) .'>'. ($page + 1) .'</a>';

if ($total > 1){
    Error_Reporting(E_ALL & E_NOTICE);
    echo "<div class='\"pstrnav\"'>";
    echo $firstpage.$page5left.$page4left.$page3left.$page2left.$page1left.'<br>'.$page.$page1right.$page2right.$page3right.$page4right.$page5right;
    echo "</div>";
}

if ($_REQUEST['up']){
    $result = "SELECT date,name,tema,feedback, image FROM users LIMIT " . $offset . "," . $num;
    $res = $conn->query($result);
    if ($res->num_rows>0){
        while ($row = $res->fetch_assoc()){
            $a[] = $row;
        }
    }
}



if ($_REQUEST['down']){
    $result = "SELECT date,name,tema,feedback, image FROM users ORDER BY date DESC LIMIT " . $offset . "," . $num;
    $res = $conn->query($result);
    if ($res->num_rows>0){
        while ($row = $res->fetch_assoc()){
            $a[] = $row;
        }
    }
}




?>
<style>
    .brd {
        float: right;
        background: white;
        padding: 60px;
        margin-right: 10px;
        width: 60%;
    }
    .n_brd{
        background: white;
        border: 1px solid black;

    }
    </style>
<div class="brd">
    <form method="POST">
        <div>Сортировать по:</div>
        <input type="submit" value="возрастанию" name="up">
        <input type="submit" value="убыванию" name="down">
        </select><br>
    </form><br><br>
    <?php
    foreach ($a as $key => $v) {
        ?>
        <div class="n_brd">
        <div><p><?= $v['date'] ?></p></div>
        <div><p><?= $v['name'] ?></p></div>
        <div><p><?= $v['tema'] ?></p></div>
        <div><p><?= $v['feedback'] ?></p></div>
        </div><br><br>

        <?php
    }
    ?>
</div>




<form method="POST">

    <input name="user_name" required placeholder="Введите ваши имя и фамилию" style="height:30px; width:300px"><br>
    <br>
    <select name="tema" size="4" multiple style="width: 300px">
        <option selected value="Благодарность">Благодарность</option>
        <option value="Жалоба">Жалоба</option>
        <option value="Предложение">Предложение</option>
    </select><br>
    <br>
    <textarea name="feedback" rows="10" cols="35" required placeholder="Оставьте свой отзыв"></textarea><br>
    <br>
    <input type="file" accept="image/jpeg,image/png" атрибуты>
    <br>
    <br>
    <input type="submit" value="Отправить">


</form>