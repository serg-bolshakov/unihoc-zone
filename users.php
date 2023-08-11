<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Всё для флорбола. Найти, выбрать и купить лучшую экипировку для флорбола для детей и взрослых от ведущего мирового производителя." />
    <meta name="keywords" content="Товары для флорбола, флорбольная экипировка для взрослых и детей. Флорбольные клюшки, мячи, борта, ворота. Для вратарей." />
    <meta name="robots" content="INDEX,FOLLOW" />
    <link type="image/png" sizes="16x16" rel="icon" href="/icon/favicon-16x16.png">
    <link type="image/png" sizes="32x32" rel="icon" href="/icons/favicon-32x32.png">
    <link type="image/png" sizes="96x96" rel="icon" href="/icons/favicon-96x96.png">
    <link type="image/png" sizes="120x120" rel="icon" href="/icons/favicon-120x120.png">
    <link type="image/png" sizes="256x256" rel="icon" href="/icons/favicon-256x256.png"
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="/styles/style.css" />
    <link rel="stylesheet" href="/styles/main.css" />
    <link rel="stylesheet" href="/styles/products.css"> 
    <title>UnihocZoneRussia. Список пользователей. Флорбол. Unihoc и Zonefloorball экипировка</title>
</head>

<body>

    <?php
        require_once $_SERVER['DOCUMENT_ROOT'].'/db.php';
    
        $query = "SELECT * FROM users";
        $res = mysqli_query($connect, $query);
        for ($users = []; $user = mysqli_fetch_assoc($res); $users[] = $user);
         
            // if (!empty($user)) {
            //     // получим требуемые данные из профиля пользователя
            //     $id = $user['id'];
            //     $name = $user['name'];
            //     $login = $user['login'];
            //     $dateOfBirth = $user['date_of_birth'];
            //     $date = date_create($dateOfBirth); // создаём объект 'дата', с которым можно выполнять некоторые операции...
            //     $dateofBirthUnix = strtotime($dateOfBirth); // преобразуем дату из БД в timestamp...
            //     $dateOfBirthFormatted = date('d.m.Y', $dateofBirthUnix);
            //     $userAge = floor((time() - $dateofBirthUnix) / 31556926); // 31556926 - количество секунд в одном годе... и округляем в мЕньшую сторону... получаем количество полных лет...

            // } 
       
         
         // опишем функцию, возвращающую слово в значении возраста ( 15 лет ... 21 год ... 23 года...)
         function chooseAgeWord ($userAge) {
             if ($userAge > 10 and $userAge < 85) {
                 if ($userAge % 10 == 1) {
                     $ageWord = 'год';
                 } elseif ($userAge % 10 == 2 or $userAge % 10 == 3 or $userAge % 10 == 4) {
                     $ageWord = 'года';
                 } else {
                     $ageWord = 'лет';
                 }
             } else {
                 $ageWord = 'Странный возраст. Вы, наверное, хотите казаться очень умным?';
             } 
             return $ageWord;
         }    
         

    ?>

    <div class="users-list">
		<div class="users-list__head">Список пользователей</div>
        <table>
            <tr>
                <th>Номер</th>
                <th>id</th>
                <th>Имя</th>
                <th>Логин</th>
                <th>Дата рождения</th>
                <th>Возраст</th>
            </tr>
            
            <?php 
            $out = '';
            $i = 1;

            foreach ($users as $user) {
                $out .= '<tr>' . '<td>' . $i . '</td>' . '<td>' . '<a href="profile.php?id=' . $user['id'] .'&regsubbtn=regsubmit">'. $user['id'] . '</a>'. '</td>' . '<td>' . $user['name'] . '</td>' . '<td>' . $user['login'] . '</td>' . 
                '<td>' . date('d.m.Y', (strtotime($user['date_of_birth']))) . '</td>' . 
                '<td>' . floor((time() - strtotime($user['date_of_birth'])) / 31556926) . ' ' . chooseAgeWord(floor((time() - strtotime($user['date_of_birth'])) / 31556926))   . '</td>' . '</tr>';
                $i++;
            }

            echo $out;

            ?>

        </table>
    </div>

<style type="text/css">
.users-list {
	width: 640px;
	display: inline-block;
	vertical-align: top;
	margin: 0 16px 20px;
	font: 14px/1.2 Arial, sans-serif;
}
.users-list__head {
	text-align: center;
	padding: 5px;
	font-weight: 700;
	font-size: 14px;
}
.users-list table {
	border-collapse: collapse;
	width: 100%;
}
.users-list th {
	font-size: 12px;
	padding: 6px 7px;
	text-align: center;
	color: #888;
	font-weight: normal;
}
.users-list td {
	font-size: 13px;
	padding: 6px 5px;
	text-align: center;
	border: 1px solid #ddd;
}

</style>

</body>
</html>