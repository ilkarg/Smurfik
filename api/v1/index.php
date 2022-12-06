<?php
require __DIR__.'/db.php';
$_POST = json_decode(file_get_contents('php://input'), true);

if (isset($_POST['function'])) {
    if ($_POST['function'] == 'registration') {
        registration();
    } else if ($_POST['function'] == 'auth') {
        auth();
    } else if ($_POST['function'] == 'objects-load') {
        objects_load();
    } else if ($_POST['function'] == 'logout') {
        logout();
    }
}

function registration() {
    global $connection;
    session_start();

    $login = $_POST['login'];
    $password = md5($_POST['password']);

    if (!$connection) {
        return mysqli_connect_error();
    }

    $query = "SELECT * FROM users WHERE login='$login'";
    $result = $connection->query($query);

    while ($row = $result->fetch_assoc()) {
        if (isset($row['login'])) {
            echo "Пользователь с указанным логином уже существует";
            return;
        }
    }

    $query = "INSERT INTO users (login, password) VALUES ('$login', '$password')";
    if ($connection->query($query) === true) {
        $_SESSION['login'] = $login;
        echo "Пользователь успешно зарегистрирован";
    }
}

function auth()
{
    global $connection;
    session_start();
    $login = $_POST['login'];
    $password = md5($_POST['password']);

    if (!$connection) {
        echo mysqli_connect_error();
        return;
    }

    $query = "SELECT * FROM users WHERE BINARY login='$login' AND password='$password'";
    $result = $connection->query($query);

    if ($result !== false) {
        while ($row = $result->fetch_assoc()) {
            if (isset($row['login'])) {
                $_SESSION['login'] = $row['login'];
                echo "Вы успешно вошли в аккаунт";
                return;
            } else {
                echo "Пользователь с указанными данными не найден";
                return;
            }
        }
    }
    echo "Неверные логин или пароль";
}

function logout() {
    session_start();
    session_unset();
    echo "Вы успешно вышли из аккаунта";
}

function objects_load() {
    global $connection;
    $query = "SELECT * FROM objects";
    $data = array(
        "name" => array(),
        "address" => array(),
        "company" => array(),
        "photo" => array()
    );
    $objects = $connection->query($query);
    while ($row = $objects->fetch_assoc()) {
        if (isset($row["name"])) {
            $data["name"][] = $row["name"];
        }
        if (isset($row["address"])) {
            $data["address"][] = $row["address"];
        }
        if (isset($row["company"])) {
            $data["company"][] = $row["company"];
        }
        if (isset($row["photo"])) {
            $data["photo"][] = $row["photo"];
        }
    }
    if (count($data["name"]) > 0) {
        header('Content-type: application/json');
        echo json_encode($data);
        return;
    }
    echo json_encode(array("message" => "Карточки с объектами не найдены"));
}