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
    <title>UnihocZoneRussia. Профиль пользователя. Флорбол. Unihoc и Zonefloorball экипировка</title>
</head>

<body>

<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/db.php';

    if ($_SERVER["REQUEST_METHOD"] == "GET") {   
        if (!empty($_GET['id'])) {
            $id = $_GET['id'];
            $query = "SELECT * FROM users WHERE id = '$id'";
            
            $res = mysqli_query($connect, $query);
            $user = mysqli_fetch_assoc($res);
    
            if (!empty($user)) {
                // получим требуемые данные из профиля пользователя
                $name = $user['name'];
                $login = $user['login'];
                $dateOfBirth = $user['date_of_birth'];
                $date = date_create($dateOfBirth); // создаём объект 'дата', с которым можно выполнять некоторые операции...
                $dateofBirthUnix = strtotime($dateOfBirth); // преобразуем дату из БД в timestamp...
                $userAge = floor((time() - $dateofBirthUnix) / 31556926); // 31556926 - количество секунд в одном годе... и округляем в мЕньшую сторону... получаем количество полных лет...

            } else {
                // пользователя с таким id нет, выведем сообщение
                $idErr = 'Пользователя с таким id не существует';                
            } 
        }
    }
    
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
    
    <div class="registration">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> " method="GET"> 
            <p class="registration-form__input-item"><span class="registration-form__star">*</span> - введите в поле id регистрационный номер требуемого пользователя</p>
            
            <p class="registration-form__input-item">
                <label for="id">id: </label>
                <input class = "registration-form__input" required type="text" placeholder="1234" name="id" value = "<?php if (isset ($_GET['id'])) echo $_GET['id'] ?>">
                <span class="registration-error">*<br><?php if (isset ($idErr)) echo $idErr ?></span>
            </p>

            <button type="submit" class = "registration-form__submit-btn" name="regsubbtn" value="regsubmit">Поиск</button>
        </form>

            <p class="registration-form__input-item">
                <label for="name">Имя: </label>
                <input class = "registration-form__input" type="text" name="name" value = "<?php if (isset ($name)) echo $name ?>">
            </p>

            <p class="registration-form__input-item">
                <label for="login">Логин: </label>
                <input class = "registration-form__input" type="text" name="login" value = "<?php if (isset ($login)) echo $login ?>">
            </p>

            <p class="registration-form__input-item">
                <label for="login">Дата рождения: </label>
                <input class = "registration-form__input" type="date" name="dateOfBirth" value = "<?php if (isset ($dateOfBirth)) echo $dateOfBirth ?>">
            </p>
            <p>Дата рождения из БД: <?php if (isset ($dateOfBirth)) echo $dateOfBirth ?></p>
            <p>День рождения: <?php if (isset ($date)) echo date_format($date, 'd-m-Y') ?></p>
            <p>Возраст: <?php if (isset ($userAge)) echo $userAge . " " . chooseAgeWord ($userAge) ?></p>
            
    </div>
</body>
</html>