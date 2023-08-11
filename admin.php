<?php
    session_start();
    
    if (isset ($_SESSION['user_status']) && ($_SESSION['user_status'] == 'admin') && isset($_SESSION['auth']) && ($_SESSION['auth'] = true)) {

        require_once $_SERVER['DOCUMENT_ROOT'].'/db.php';
    
        // $query = "SELECT * FROM users";     
        // $res = mysqli_query($connect, $query) or die(mysqli_error($connect));
        // for ($users = []; $user = mysqli_fetch_assoc($res); $users[] = $user);

        // пробуем джойтить таблицу user_statuses и брать оттуда "понятные" статусы, а не "status_id" из таблицы users...
        $query = "SELECT users.*, user_statuses.name as status FROM users
                    LEFT JOIN user_statuses ON users.status_id = user_statuses.id";
        $res = mysqli_query($connect, $query) or die(mysqli_error($connect));
        for ($users = []; $user = mysqli_fetch_assoc($res); $users[] = $user);
        
        
        //var_dump($users);
        //echo $users[0]['id'];

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $query = "DELETE FROM users WHERE id = '$id'";
            $res = mysqli_query($connect, $query);
            $_SESSION['flash'] = 'Пользователь успешно удалён!'; 
            header("Location:" . $_SERVER["PHP_SELF"]); // перезапрашиваем страницу методом GET
            die();            
        }

        if (isset($_GET['change_status'])) {
            $id = $_GET['change_status'];
            $query = "SELECT * FROM users WHERE id = '$id'";
            $res = mysqli_query($connect, $query) or die(mysqli_error($connect));
            $user = mysqli_fetch_assoc($res);
            
            if ($user['status_id'] == '2') {
                
                $query = "UPDATE users SET status_id='1' WHERE id = '$id'";
                $res = mysqli_query($connect, $query);
                $_SESSION['flash'] = 'Статус пользователя успешно изменён на: "user"!'; 
                header("Location:" . $_SERVER["PHP_SELF"]); // перезапрашиваем страницу методом GET
                die();      
            } elseif ($user['status_id'] == '1') {
                
                $query = "UPDATE users SET status_id='2' WHERE id = '$id'";
                $res = mysqli_query($connect, $query);
                $_SESSION['flash'] = 'Статус пользователя успешно изменён на: "admin"!'; 
                header("Location:" . $_SERVER["PHP_SELF"]); // перезапрашиваем страницу методом GET
                die();      
            } else {
                $_SESSION['flash'] = 'Статус пользователя не определён!'; 
                header("Location:" . $_SERVER["PHP_SELF"]); // перезапрашиваем страницу методом GET
                die();      
            }
            
        }

        if (isset($_SESSION['flash'])) { // одинаково работает и isset, и !empty - пробовал. Выводит и так, и так.
            echo $_SESSION['flash'].'!';
            unset($_SESSION['flash']);
        }

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
    <title>UnihocZoneRussia. Админка. Флорбол. Unihoc и Zonefloorball экипировка</title>
</head>

<body>

<div class="users-list">
		<div class="users-list__head">Список пользователей</div>
        <table>
            <tr>
                <th>Номер</th>
                <th>id</th>
                <th>Status_id</th>
                <th>Статус</th>
                <th>Имя</th>
                <th>Логин</th>
                <th>Дата рождения</th>
                <th></th>
                <th></th>
                <th>Дата регистрации</th>
                
            </tr>
            
            <?php 
            $out = '';
            $i = 1;

            foreach ($users as $user) {

                if ($user['date_of_birth'] != NULL) {
                    $date_of_birth = date('d.m.Y', (strtotime($user['date_of_birth'])));
                } else {
                    $date_of_birth = ' ';
                }

                if ($user['status_id'] == '2') {
                    $class = ' class="admin"';
                    $status_user_target = 'user';
                } else {
                    $class = '';
                    $status_user_target = 'admin';
                }

                $reg_date = date('d.m.Y', (strtotime($user['reg_date'])));


                
                $out .= '<tr'.$class.'>' . 
                '<td>' . $i . '</td>' . 
                '<td>' . '<a href="profile.php?id=' . $user['id'] .'&regsubbtn=regsubmit">'. $user['id'] . '</a>'. '</td>' . 
                '<td>' . $user['status_id'] . '</td>' . '<td>' . $user['status'] . '</td>' . 
                '<td>' . $user['name'] . '</td>' . '<td>' . $user['login'] . '</td>' . 
                '<td>' . $date_of_birth . '</td>' .
                '<td>' . '<a href="admin.php?id=' .$user['id'] .'">'. 'Удалить' . '</a>'. '</td>'. 
                '<td>' . '<a href="admin.php?change_status=' .$user['id'] .'">'. 'Сделать '.$status_user_target.'-ом' . '</a>'. '</td>' .
                '<td>' . $reg_date . '</td>'; 
                $i++;
            }
            echo $out;
           
            ?>

        </table>
    </div>

<style type="text/css">
.users-list {
	width: 1180px;
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

.admin {
	color: #f51b1b;
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
        

<?php 
    } else {
        echo 'Только администраторы имеют право доступа на эту страницу.';
    }
?>