<?php
session_start(); 
include 'temp/bd.php';
include 'temp/head.php';
include 'temp/nav_manager.php'; 


if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('HTTP/1.1 403 Forbidden');
    die("<div style='padding:20px; text-align:center; font-family:Arial; color:red;'><h3>Ошибка: Доступ запрещен.</h3></div>");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['vacancy_id'], $_POST['status'])) {
    

    $user_id = (int)$_POST['user_id'];
    $vacancy_id = (int)$_POST['vacancy_id'];
    

    $status = $_POST['status'];
    $admin_comment = isset($_POST['admin_comment']) ? trim($_POST['admin_comment']) : '';


    $allowed_statuses = ['приглашение', 'принят', 'отказ'];
    if (!in_array($status, $allowed_statuses)) {
        die("Ошибка: Недопустимое значение статуса.");
    }

   
    $sql = "
        INSERT INTO application_statuses (user_id, vacancy_id, status, admin_comment, status_at) 
        VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)
        ON DUPLICATE KEY UPDATE 
            status = VALUES(status), 
            admin_comment = VALUES(admin_comment),
            status_at = CURRENT_TIMESTAMP
    ";

    if ($stmt = $mysqli->prepare($sql)) {

        $stmt->bind_param("iiss", $user_id, $vacancy_id, $status, $admin_comment);
        
        if ($stmt->execute()) {
            $stmt->close();
            
    
            if (!empty($_SERVER['HTTP_REFERER'])) {
                header("Location: " . $_SERVER['HTTP_REFERER']);
            } else {
                header("Location: admin_panel.php"); 
            }
            exit();
        } else {
            die("Ошибка при выполнении запроса: " . $stmt->error);
        }
    } else {
        die("Ошибка подготовки SQL-запроса: " . $mysqli->error);
    }

} else {

    header("Location: admin_panel.php");
    exit();
}
?>
