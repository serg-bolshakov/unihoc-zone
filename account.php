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
    <title>UnihocZoneRussia. Личный кабинет. Флорбол. Unihoc и Zonefloorball экипировка</title>
</head>

<body>

<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/db.php';

    // делаем SELECT запрос, который будет доставать из БД пользователя с id из сессии 
    // (при авторизации пользователя в сессию записали его id)

    $id = $_SESSION['id'];
    $query = "SELECT * FROM users WHERE id = '$id'";

    $res = mysqli_query($connect, $query);
    $user = mysqli_fetch_assoc($res);

?>

    <div class="registration">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> " method="POST"> 
                     
            <p class="registration-form__input-item">
                <label for="name">Имя: </label>
                <input class = "registration-form__input" type="text" name="name" value = "<?= $user['name'] ?>">
            </p>

            <p class="registration-form__input-item">
                <label for="login">Дата рождения: </label>
                <input class = "registration-form__input" type="date" name="dateOfBirth" value = "<?= $user['date_of_birth'] ?>">
            </p>

            <button type="submit" class = "registration-form__submit-btn" name="submit" value="submit">Редактировать</button>

        </form>            
    </div>

<?php
    
    if (!empty($_POST['submit'])) {
        $name = $_POST['name'];
        $date = $_POST['dateOfBirth'];
        
        $query = "UPDATE users SET name = '$name', date_of_birth = '$date' WHERE id = $id";
        mysqli_query($connect, $query); 

        header("Location: /account.php");
        die();
    }
?>


</body>
</html>