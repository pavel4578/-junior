<?php
session_start();
include 'temp/head.php';
include 'temp/bd.php'; 
include 'temp/nav_manager.php';

if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<div style='padding:20px; text-align:center; color:red;'><h3>Доступ запрещен.</h3></div>";
    include 'temp/footer.php';
    exit();
}
$id = 0;
$company_id = 1; 
$title = '';
$salary = '';
$tech_stack = '';
$format_type = 'вакансия';
$places_count = 1;
$description = '';
$page_title = "Добавление новой позиции";
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $page_title = "Редактирование вакансии ";
    

    $stmt = $mysqli->prepare("SELECT company_id, title, salary, tech_stack, format_type, places_count, description FROM vacancies WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($company_id, $title, $salary, $tech_stack, $format_type, $places_count, $description);
    $stmt->fetch();
    $stmt->close();
}
$companies_result = $mysqli->query("SELECT id, name FROM companies ORDER BY name ASC");

// ОБРАБОТКА ОТПРАВКИ ФОРМЫ (POST-запрос)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $company_id = (int)$_POST['company_id'];
    $title = trim($_POST['title']);
    $salary = !empty($_POST['salary']) ? (float)$_POST['salary'] : null;
    $tech_stack = trim($_POST['tech_stack']);
    $format_type = $_POST['format_type'];
    $places_count = (int)$_POST['places_count'];
    $description = trim($_POST['description']);
    $img = "11.png";

    if (!empty($title)) {
        if ($id === 0) {
            // ДЕЙСТВИЕ ДОБАВЛЕНИЕ НОВОЙ ЗАПИСИ
            $insert_stmt = $mysqli->prepare("
                INSERT INTO vacancies (company_id, title, salary, tech_stack, img, format_type, places_count, description) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $insert_stmt->bind_param("isssssis", $company_id, $title, $salary, $tech_stack, $img, $format_type, $places_count, $description);
            $insert_stmt->execute();
            $insert_stmt->close();
        } else {
            // ДЕЙСТВИЕ ОБНОВЛЕНИЕ СУЩЕСТВУЮЩЕЙ ЗАПИСИ
            $update_stmt = $mysqli->prepare("
                UPDATE vacancies 
                SET company_id = ?, title = ?, salary = ?, tech_stack = ?, format_type = ?, places_count = ?, description = ? 
                WHERE id = ?
            ");
            $update_stmt->bind_param("issssisi", $company_id, $title, $salary, $tech_stack, $format_type, $places_count, $description, $id);
            $update_stmt->execute();
            $update_stmt->close();
        }
        
          echo "<script>window.location.href='admin_cabinet.php';</script>";
        exit();
    }
}
?>



<div class="form-container">
    <h2 class="form-title"><?php echo $page_title; ?></h2>
    
    <form action="form_vacancy.php" method="POST">
        <!-- Скрытое поле для определения: добавление (0) или редактирование (ID) -->
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        
        <div class="form-group">
            <label>Компания-работодатель</label>
            <select name="company_id" class="form-control" required>
                <?php while($comp = $companies_result->fetch_assoc()): ?>
                    <option value="<?php echo $comp['id']; ?>" <?php if($comp['id'] == $company_id) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($comp['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Название вакансии / стажировки</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($title); ?>" required placeholder="Например: Junior PHP Developer">
        </div>

        <div class="form-group">
            <label>Формат позиции</label>
            <select name="format_type" class="form-control">
                <option value="вакансия" <?php if($format_type == 'вакансия') echo 'selected'; ?>>вакансия</option>
                <option value="стажировка" <?php if($format_type == 'стажировка') echo 'selected'; ?>>стажировка</option>
            </select>
        </div>

        <div class="form-group">
            <label>Заработная плата (руб.)</label>
            <input type="number" name="salary" class="form-control" value="<?php echo htmlspecialchars($salary); ?>" placeholder="Например: 80000">
        </div>

        <div class="form-group">
            <label>Технологический стек</label>
            <input type="text" name="tech_stack" class="form-control" value="<?php echo htmlspecialchars($tech_stack); ?>" placeholder="Например: PHP, MySQL, Git">
        </div>

        <div class="form-group">
            <label>Количество доступных мест</label>
            <input type="number" name="places_count" class="form-control" value="<?php echo $places_count; ?>" min="1" required>
        </div>

        <div class="form-group">
            <label>Описание обязанностей и требований</label>
            <textarea name="description" class="form-control" placeholder="Опишите задачи, требования к кандидату и условия работы..."><?php echo htmlspecialchars($description); ?></textarea>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-submit">Сохранить изменения</button>
            <a href="admin_cabinet.php" class="btn-cancel">Отмена</a>
        </div>
    </form>
</div>

<?php 
include 'temp/footer.php'; 
?>
