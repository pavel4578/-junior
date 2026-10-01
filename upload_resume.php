<?php
session_start();
include 'temp/bd.php'; 

if (empty($_SESSION['role']) || $_SESSION['role'] !== 'student' || empty($_SESSION['id_user'])) {
    header('Location: formavto.php');
    exit;
}

$user_id = intval($_SESSION['id_user']);



    $file = $_FILES['resume_file'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    
    // Получаем расширение файла
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedExts = ['pdf', 'doc', 'docx'];

    //  Проверяем расширение
    if (!in_array($fileExt, $allowedExts)) {
        header('Location: resume.php?error=type');
        exit;
    }

  
    //  Создаем папку для загрузки uploads
    $uploadDir = 'uploads/resumes/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Генерируем уникальное и безопасное имя файла на сервере на основе ID студента
    $newFileName = 'user_' . $user_id . '_cv.' . $fileExt;
    $fileDestination = $uploadDir . $newFileName;

    // Перемещаем файл из временной папки OpenServer в нашу постоянную папку
    if (move_uploaded_file($fileTmpName, $fileDestination)) {
        
        // Проверяем, была ли уже строка для этого пользователя
        $check_sql = "SELECT * FROM resumes WHERE user_id = $user_id";
        $check_res = mysqli_query($mysqli, $check_sql);

        if (mysqli_num_rows($check_res) > 0) {
            // обновляем путь к новому файлу и дату
            $sql = "UPDATE resumes SET file_path = '$fileDestination', uploaded_at = CURRENT_TIMESTAMP WHERE user_id = $user_id";
        } else {
       
            $sql = "INSERT INTO resumes (user_id, file_path, uploaded_at) VALUES ($user_id, '$fileDestination', CURRENT_TIMESTAMP)";
        }

        if (mysqli_query($mysqli, $sql)) {
            header('Location: resume.php?success=1');
        } else {
            header('Location: resume.php?error=db');
        }
    } else {
        header('Location: resume.php?error=upload');
    }
    exit;
else {
    header('Location: resume.php');
    exit;
}
?>
