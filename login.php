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
    <title>UnihocZoneRussia. Авторизация. Флорбол. Unihoc и Zonefloorball экипировка</title>
</head>

<body>

<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/db.php';
  
if ($_SERVER["REQUEST_METHOD"] == "POST") {   
    if (!empty($_POST['password']) and !empty($_POST['login']) ) {
        $login = $_POST['login'];
        $query = "SELECT * FROM users WHERE login = '$login'";
        
        $res = mysqli_query($connect, $query);
        // $res = $connect->query($query); - можно в таком виде запрос оформлять (подключение к БД и запрос)

        $user = mysqli_fetch_assoc($res);

        if (!empty($user)) {
            /* -------------------- Реализация с md5 и солью из БД -----------------------------------------------
            // пользователь с таким логином есть, теперь надо проверять пароль
            $salt = $user['salt']; // соль из БД
            $hash = $user['password']; // солёный пароль из БД
            $password = md5($salt . $_POST['password']); // солёный пароль от юзера

            // сравниваем солёные пароли
            if ($password == $hash) {
                // всё ок, авторизуем
                $_SESSION['auth'] = true;
                $_SESSION['flash'] = 'Вы авторизовались. Мы ждали вас, '.$user['name']; 
                //header("Location: /");
                //die();
            -----------------------------------------------------------------------------------------------------*/

            $hash = $user['password']; // солёный пароль из БД
            // Проверяем соответствие хеша из БД введённому паролю:
            if (password_verify($_POST['password'], $hash)) {
                // всё ок, авторизуем
                $_SESSION['auth'] = true;
                $_SESSION['flash'] = 'Вы авторизовались. Мы ждали вас, '.$user['name']; 
            } else {
                // пароль не подошёл, выведем сообщение
                $_SESSION['flash'] = 'Неверно введены логин или пароль (не подошёл пароль)'; 
                //header("Location: login.php");
            }
        } else {
            // пользователя с таким логином нет, выведем сообщение
            $_SESSION['flash'] = 'Неверно введены логин или пароль'; 
            //header("Location: login.php");
        } 
    }

    if (isset($_SESSION['flash'])) { // одинаково работает и isset, и !empty - пробовал. Выводит и так, и так.
        echo $_SESSION['flash'].'!';
        unset($_SESSION['flash']);
    }

   	if (!empty($_SESSION['auth'])) {
		var_dump($_SESSION['auth']);
	}
}
    
?>

<div class="registration">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> " method="POST"> 
        <p class="registration-form__input-item"><span class="registration-form__star">*</span> - поля, обязательные для заполнения </p>
        
        <p class="registration-form__input-item">
            <label for="login">Логин: </label>
            <input class = "registration-form__input" required type="text" placeholder="user1234" name="login" value = "<?php if (isset ($_POST['login'])) echo $_POST['login'] ?>">
            <span class="registration-error">*</span>
        </p>
                        
        <p class="registration-form__input-item">
            <label for="password">Пароль: </label>
            <input class = "registration-form__input" required name="password" type="password" value = "">
            <span class="registration-error">*</span>
        </p>
        
        <button type="submit" class = "registration-form__submit-btn" name="regsubbtn" value="regsubmit">Авторизация</button>
    </form>
</div>
</body>
</html>