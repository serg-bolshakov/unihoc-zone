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
    <title>UnihocZoneRussia. Смена пароля. Флорбол. Unihoc и Zonefloorball экипировка</title>
</head>

<body>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> " method="POST"> 
        <p>
            <label for="old_password">Старый пароль: </label>
            <input name="old_password">
        </p>
        <p>
            <label for="new_password">Новый пароль: </label>
            <input name="new_password">
        </p>
        <p>
            <label for="new_password_confirm">Повторите новый пароль: </label>
            <input name="new_password_confirm">
        </p>
        <p>
            <input type="submit" name="submit">
        </p>
    </form>            



<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/db.php';

    $id = $_SESSION['id']; // id юзера из сессии
    $query = "SELECT * FROM users WHERE id = '$id'";

    $res = mysqli_query($connect, $query);
    $user = mysqli_fetch_assoc($res);

    if (!empty($_POST['submit'])) {
        $hash = $user['password']; // солёный пароль из БД
        $oldPassword = $_POST['old_password'];
        $newPassword = $_POST['new_password'];
        $newPasswordConfirm = $_POST['new_password_confirm'];

        // Проверяем новый пароль и подтверждение

        if ($newPassword == $newPasswordConfirm) {
            // Проверяем соответствие хеша из БД введённому старому паролю
            if (password_verify($oldPassword, $hash)) {
                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

                $query = "UPDATE users SET password = '$newPasswordHash' WHERE id = '$id'";
                mysqli_query($connect, $query);
                echo 'Пароль успешно изменён!';
            } else {
                echo 'Старый пароль введён неверно';
            }
        } else {
            echo 'Новые пароли не совпадают';
        }
    }
?>


</body>
</html>