<?php

//1. Опишите, какие проблемы могут возникнуть при использовании данного кода
$mysqli = new mysqli("localhost", "my_user", "my_password", "world");
$id = $_GET['id'];
$res = $mysqli->query('SELECT * FROM users WHERE u_id='. $id);
$user = $res->fetch_assoc();

/*
1.1 Уязвимость безопасности:
Проблема: параметр $_GET['id'] напрямую встраивается в SQL-запрос, это делает его уязвимым перед атаками с использованием SQL-инъекций.
Решение: Нужно использовать подготовленные операторы - bind_param для безопасной обработки
*/
$query = $mysqli->prepare('SELECT * FROM users WHERE u_id = ?');
$query->bind_param('i', $id);
$query->execute();
$res = $query->get_result();
$user = $res->fetch_assoc();

/*
1.2 Обработка ошибок:
Проблема: нет обработки ошибок при работе с БД, если что-то пойдет не так, будет сложно понять где все падает
Решение: проверить наличие ошибок после выполнения запроса и обработки соединения
*/
if (!$res) {
    die('Query failed: ' . $mysqli->error);
}

/*
1.3 Закрытие соединения:
Проблема: соединение с БД не закрыто, а значит это тратит ресурсы зря
Решение: добавить close()
*/
$query->close();


//2. Сделайте рефакторинг
$questionsQ = $mysqli->query('SELECT * FROM questions WHERE catalog_id='. $catId);
$result = array();
while ($question = $questionsQ->fetch_assoc()) {
    $userQ = $mysqli->query('SELECT name, gender FROM users WHERE id='. (int)$question['user_id']);
    $user = $userQ->fetch_assoc();
    $result[] = array('question'=>$question, 'user'=>$user);
    $userQ->free();
}
$questionsQ->free();

//Рефакторинг
$query = $mysqli->prepare('SELECT * FROM questions WHERE catalog_id = ?');
$query->bind_param('i', $catId);
$query->execute();
$questionsQ = $query->get_result();
$result = array();

if (!$questionsQ) {
    die("Error in question query: " . $mysqli->error);
}

while ($question = $questionsQ->fetch_assoc()) {
    $userQuery = $mysqli->prepare('SELECT name, gender FROM users WHERE id = ?');
    $userQuery->bind_param('i', $question['user_id']);
    $userQuery->execute();
    $userResult = $userQuery->get_result();

    $user = $userResult->fetch_assoc();

    $result[] = array('question' => $question, 'user' => $user);

    $userResult->free();
    $userQuery->close();
}
$questionsQ->free();
$query->close();







