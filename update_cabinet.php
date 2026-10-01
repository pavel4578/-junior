<?php
session_start(); 
include 'temp/bd.php'; 
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'student' || empty($_SESSION['id_user'])) {
    header('Location: formavto.php?error=session_expired');
    exit;
}
$user_id = intval($_SESSION['id_user']); 

$vacancy_id = isset($_GET['vacancy_id']) ? intval($_GET['vacancy_id']) : 0;
$status = isset($_GET['status']) ? mysqli_real_escape_string($mysqli, trim($_GET['status'])) : '';
if ($vacancy_id > 0 && ($status === 'просмотрено' || $status === 'подана заявка')) {
    $check_sql = "SELECT * FROM student_vacancy_actions WHERE user_id = $user_id AND vacancy_id = $vacancy_id";
    $check_res = mysqli_query($mysqli, $check_sql);
    if (mysqli_num_rows($check_res) > 0) {
        // Если запись есть — обновляем статус
        $update_sql = "UPDATE student_vacancy_actions SET status = '$status', action_at = CURRENT_TIMESTAMP WHERE user_id = $user_id AND vacancy_id = $vacancy_id";
        mysqli_query($mysqli, $update_sql);
    } else {
        // Если записи нет — создаем новую для проверки
        $insert_sql = "INSERT INTO student_vacancy_actions (user_id, vacancy_id, status, action_at) VALUES ($user_id, $vacancy_id, '$status', CURRENT_TIMESTAMP)";
        mysqli_query($mysqli, $insert_sql);
    }
        //  НАВИГАЦИЯ НА ОСНОВЕ НАЖАТОЙ КНОПКИ
    if ($status === 'подана заявка') {
        // Если подал заявку — отправляем в Личный кабинет
        header('Location: lich_cabinet.php');
    } else {
        // Если просто "подробнее" — отправляем на страницу детального описания вакансии
        header('Location: view_vacancy.php?id=' . $vacancy_id);
    }
    exit;
} else {
    header('Location: index.php');
    exit;
}
?>
