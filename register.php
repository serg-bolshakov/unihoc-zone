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
    <title>UnihocZoneRussia. Регистрация. Флорбол. Unihoc и Zonefloorball экипировка</title>
</head>

<body>

<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/db.php';

// возвращает имя файла, выполняющегося в данный момент скрипта
// htmlspecialchars - преобразует специальные символы в сущности html, заменит такие как < и > на &lt; и &gt; - предотвращает использование вредоносного кода, путём введения кода ХТМЛ или ЯваСкрипт в формах 
    
// определяем переменные, которрые будем использовать в форме, и устанавливаем им первоначально пустые значения:
$name = $email = $emailPosted = $login = $password = $confirm = "";
$nameErr = $emailErr = $dateOfBirthErr = $loginErr = $passwordErr = $confirmErr = "";

// создаём функцию, которая будет убирать пробелы, симводлы табуляции... с помощью функции PHP trim()... 
// удаление косой черты (\) из входных данных stripcslashes

function test_input($data) {
    $data = trim($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}

// соль + пароль: соль для каждого пользователя будет разной и генерироваться случайным образом в момент регистрации.
// функция, которая будет это делать:

function generateSalt() {
    $salt = '';
    $saltLength = 8; // длина соли
        
    for($i = 0; $i < $saltLength; $i++) {
        $salt .= chr(mt_rand(33, 126)); // символ из ASCII-table
    }
    return $salt;
}

// mt_rand — Генерирует случайное значение методом с помощью генератора простых чисел на базе Вихря Мерсенна. mt_rand(int $min, int $max): int
// chr — Генерирует односимвольную строку по заданному числу

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!empty($_POST["name"])) {
        $name = test_input($_POST["name"]);
    } else {
        $nameErr = 'Поле обязательно для заполнения';
    }

    if (!empty($_POST["email"])) {
        $emailPosted = strtolower(test_input($_POST["email"]));
        if (preg_match("/^[A-Za-z0-9]+\_*\.?[A-Za-z0-9]+@[A-Za-z0-9]+\.[A-Za-z0-9]+$/", $emailPosted)) {
            $query = "SELECT email FROM users WHERE email = '$emailPosted'";
            $user = mysqli_fetch_assoc(mysqli_query($connect, $query));
            if (empty($user)) {
                $email = $emailPosted;
            } else {
                $emailErr = 'Пользователь с данным email уже зарегистрирован в системе. Пожалуйста, авторизуйтесь.'; 
            }
        } else {
            $emailErr = 'Введён некорректный формат адреса электронной почты!'; 
        }
    } else {
        $emailErr = 'Поле обязательно для заполнения';
    }

    // пока не решил задачу, если оставить поле незаполненным... сделал поле обязательным для заполнения... данный код не работает в принципе - т.е. не передаёт значение в базу и выпадает фатальная ошибка
    if(!empty ($_POST['dateOfBirth']) ) {
        $dateOfBirth = $_POST['dateOfBirth'];
    } else {
        $dateOfBirth = NULL;
    }

    if (!empty($_POST["login"])) {
        $loginPosted = $_POST["login"];
        if (preg_match("/^[A-Za-z0-9]+$/", $loginPosted) and strlen($loginPosted) >= 4 and strlen($loginPosted) <= 16 ) {
            $query = "SELECT login FROM users WHERE login='$loginPosted'";
            $user = mysqli_fetch_assoc(mysqli_query($connect, $query));
            if (empty($user)) {
                $login = $loginPosted;
            } else {
                $loginErr = 'Логин уже используется. Авторизуйтесь или попробуйте другой!'; 
            }
        } else $loginErr = 'Логин должен быть длиной от 4 до 16 символов, содержать только латинские буквы и цифры.';   
    } else {
        $loginErr = 'Поле обязательно для заполнения';
    }

    $salt = generateSalt();
    
    if (!empty ($_POST['password'])) {
        $password = md5($salt.$_POST['password']);
    } else {
        $passwordErr = 'Поле не может быть пустым.'; 
    }

    if (!empty ($_POST['confirm'])) {
        $confirm = md5($salt.$_POST['confirm']);
    } else {
        $confirmErr = 'Поле не может быть пустым.'; 
    }

    if (!empty($name) and !empty($email) and !empty($login) and !empty($password) and !empty($confirm)) {
        if ($password == $confirm) {
        $redDate = date('d.m.Y');
        $query = "INSERT INTO users SET name = '$name', login = '$login', password = '$password', salt = '$salt', date_of_birth = '$dateOfBirth', email = '$email', reg_date = '$redDate'";
        $connect->query($query); 
        $_SESSION['auth'] = true; // пометка об авторизации
        $id = mysqli_insert_id($connect);
        $_SESSION['id'] = $id;
        echo "Ваш регистрационный номер: $id";
        } else {
            $confirmErr = 'Ведённые вами пароли не совпадают!';
        }
    } 
}

?>

<div class="registration">
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> " method="POST"> 
    <p class="registration-form__input-item"><span class="registration-form__star">*</span> - поля, обязательные для заполнения </p>
    <p class="registration-form__input-item">
        <label for="name">Имя: </label>
        <input class = "registration-form__input" type="text" required id="name" name="name" value = "<?php if (isset ($_POST['name'])) echo $_POST['name'] ?>">
        <span class="registration-error">* <?php echo $nameErr;?>
    </p>


    <p class="registration-form__input-item">
        <label for="email">Email: </label>
        <input class = "registration-form__input" type="email" required placeholder="user@gmail.com" id="email" name="email" value = "<?php if (isset ($_POST['email'])) echo $_POST['email'] ?>">
        <span class="registration-error">*<br><?php echo $emailErr;?>
    </p>

    <p class="registration-form__input-item">
        <label for="dateOfBirth">Дата рождения: </label>
        <input class = "registration-form__input" type="date" required id="dateOfBirth" name="dateOfBirth" value = "<?php if (isset ($_POST['dateOfBirth'])) echo $_POST['dateOfBirth'] ?>">
        <span class="registration-error">*<br><?php echo $dateOfBirthErr;?>
    </p>
    <p class="registration-form__input-item">
        <label for="login">Логин: </label>
        <input class = "registration-form__input" type="text" placeholder="user1234" name="login" required value = "<?php if (isset ($_POST['login'])) echo $_POST['login'] ?>">
        <span class="registration-error">*<br><?php echo $loginErr;?>
    </p>
	
            
    <p class="registration-form__input-item">
        <label for="password">Придумайте пароль: </label>
        <input class = "registration-form__input" name="password" type="password" value = "<?php if (isset ($_POST['password'])) echo $_POST['password'] ?>">
        <span class="registration-error">*<br><?php echo $passwordErr;?>
    </p>
    
    
    <p class="registration-form__input-item">
        <label for="confirm">Повторите пароль: </label>
        <input class = "registration-form__input" name="confirm" type="password">
        <span class="registration-error">*<br><?php echo $confirmErr;?>
    </p>
    
	<button type="submit" class = "registration-form__submit-btn" name="regsubbtn" value="regsubmit">Отправить</button>
</form>
</div>
</body>
</html>