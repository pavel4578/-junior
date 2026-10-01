<?php 
session_start();
include 'temp/head.php';
include 'temp/bd.php'; 


if (empty($_SESSION['role']) || $_SESSION['role'] !== 'student' || empty($_SESSION['id_user'])) {
    die("<div class='container mt-5 alert alert-warning text-center fw-bold'>Для управления резюме необходимо авторизоваться как студент.</div>");
}
include 'temp/nav_client.php';

$user_id = intval($_SESSION['id_user']);
// Проверяем, загружено ли уже резюме у этого студента
$sql = "SELECT * FROM resumes WHERE user_id = $user_id";
$result = mysqli_query($mysqli, $sql);
$current_resume = mysqli_fetch_assoc($result);
?>

<div class="container pt-5 pb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h2 class="fw-bold text-dark mb-1 text-center">Ваше резюме</h2>
                
                <!-- Блок уведомлений об успешной операции или ошибках -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success py-2 small text-center rounded-3 mb-4" role="alert">
                         Файл резюме успешно сохранен в базе данных!
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger py-2 small text-center rounded-3 mb-4" role="alert">
                         Ошибка: <?php 
                            if($_GET['error'] == 'empty') echo 'Выберите файл для загрузки.';
                            elseif($_GET['error'] == 'type') echo 'Недопустимый формат файла! Разрешены только PDF, DOC, DOCX.';
                            elseif($_GET['error'] == 'size') echo 'Файл слишком большой! Максимальный размер — 5 МБ.';
                            else echo 'Не удалось сохранить файл на сервере.';
                        ?>
                    </div>
                <?php endif; ?>

                <!-- УСЛОВИЕ: Если резюме уже загружено, выводим только инфоплашку и кнопку скачивания -->
                <?php if ($current_resume): ?>
                    <div class="bg-light p-4 rounded-3 border border-light text-center">
                        <div class="fs-1 mb-2"></div>
                        <h5 class="fw-bold text-dark mb-1">Резюме успешно прикреплено</h5>
                        <p class="text-muted small mb-3">Ваша анкета доступна работодателям.<br>Дата загрузки: <?php echo date('d.m.Y H:i', strtotime($current_resume['uploaded_at'])); ?></p>
                        <a href="<?php echo htmlspecialchars($current_resume['file_path']); ?>" class="btn btn-primary fw-bold px-4 py-2 shadow-sm rounded-2 text-decoration-none text-white w-100" download>
                             Скачать моё резюме
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Если резюме НЕТ, показываем предупреждение и форму первичной загрузки -->
                    <div class="alert alert-light text-center border-dashed py-4 mb-4 text-muted rounded-3">
                        <span class="fs-2 d-block mb-2"></span>
                        <span class="small font-semibold">Резюме пока не загружено. Работодатели не видят вашу анкету!</span>
                    </div>

                    <form action="upload_resume.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2">Выберите файл резюме</label>
                            <input type="file" name="resume_file" class="form-control bg-light border-0 py-2.5 rounded-3" required>
                            <div class="form-text small text-muted mt-1.5">Разрешенные форматы: PDF, DOC, DOCX. Макс. размер: 5 МБ.</div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2.5 rounded-3 shadow-sm text-white">
                            Загрузить резюме
                        </button>
                    </form>
                <?php endif; ?>

            </div>

        </div>
    </div>
</div>

<?php include 'temp/footer.php'; ?>
