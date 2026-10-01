<?php 
session_start();
include 'temp/head.php';
include 'temp/bd.php'; 
if(!empty($_SESSION['role'])){
    $role = $_SESSION['role'];
    if($role == 'student'){
        include 'temp/nav_client.php';
    }
    if($role == 'admin'){
        include 'temp/nav_manager.php';
    }
}else{
    include 'temp/nav.php';
}
$vacancy_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT vacancies.*, companies.name AS company_name, companies.address AS company_address, companies.contact_info AS company_contacts
        FROM vacancies 
        JOIN companies ON vacancies.company_id = companies.id 
        WHERE vacancies.id = $vacancy_id";
$result = mysqli_query($mysqli, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    $vacancy = mysqli_fetch_assoc($result);
} else {
    echo "<div class='container mt-5 alert alert-danger text-center fw-bold'>Вакансия не найдена в базе данных!</div>";
    include 'temp/footer.php';
    exit;
}
?>

<div class="container pt-5 pb-5">
    <div class="mb-4">
        <a href="index.php" class="btn btn-link text-decoration-none p-0 fw-bold"> Назад к списку предложений</a>
    </div>

    <div class="row g-4">

        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge <?php echo (isset($vacancy['format_type']) && $vacancy['format_type'] == 'вакансия') ? 'bg-success' : 'bg-info'; ?> text-capitalize px-3 py-2 fw-bold text-white shadow-sm">
                        <?php echo ($vacancy['format_type'] ?? 'Предложение'); ?>
                    </span>
                    <span class="text-muted small">Опубликовано: <?php echo isset($vacancy['created_at']) ? date('d.m.Y', strtotime($vacancy['created_at'])) : date('d.m.Y'); ?></span>
                </div>

                <h1 class="fw-bold text-dark mb-2"><?php echo($vacancy['title'] ?? 'Без названия'); ?></h1>
                <h4 class="text-success fw-bold mb-4"><?php echo isset($vacancy['salary']) ? number_format($vacancy['salary'], 0, '.', ' ') : '0'; ?> ₽</h4>

                <h5 class="fw-bold text-dark mb-3">Обязанности и требования</h5>
                <p class="text-muted leading-relaxed mb-4">
                    Ищем амбициозного специалиста в команду разработки. Вам предстоит работать над улучшением текущих сервисов компании, проектировать новые модули архитектуры и оптимизировать запросы к базам данных. 
                    <br><br>
                    <strong>Что мы ждем от кандидата:</strong> умение разбираться в чужом коде, базовые знания алгоритмов, ответственность, желание развиваться и осваивать новые технологии в сфере автоматизации процессов.
                </p>

                <?php if(!empty($vacancy['tech_stack'])): ?>
                    <h5 class="fw-bold text-dark mb-2">Требуемый стек технологий</h5>
                    <div class="d-flex flex-wrap gap-1 mb-4">
                        <?php foreach(explode(',', $vacancy['tech_stack']) as $tag): ?>
                            <span class="badge bg-light text-secondary border font-monospace px-3 py-2 fs-6"><?php echo htmlspecialchars(trim($tag)); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="border-top pt-4 mt-2">
                    <a href="update_cabinet.php?vacancy_id=<?php echo $vacancy['id']; ?>&status=подана заявка" class="btn btn-primary btn-lg fw-bold shadow-sm px-5 py-2.5 text-white text-decoration-none">
                        Откликнуться на вакансию
                    </a>
                </div>
            </div>
        </div>

     
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top: 20px;">
                <h5 class="fw-bold text-dark mb-3">Работодатель</h5>
                <h4 class="fw-bold text-primary mb-3">@<?php echo ($vacancy['company_name'] ?? 'Компания'); ?></h4>
                
                <div class="mb-3">
                    <span class="text-muted small d-block font-weight-bold">Адрес офиса:</span>
                    <span class="text-dark small"><?php echo !empty($vacancy['company_address']) ? ($vacancy['company_address']) : 'Адрес не указан'; ?></span>
                </div>

                <div class="mb-3">
                    <span class="text-muted small d-block font-weight-bold">Контакты HR-отдела:</span>
                    <span class="text-dark small"><?php echo !empty($vacancy['company_contacts']) ? ($vacancy['company_contacts']) : 'Контакты не указаны'; ?></span>
                </div>

                <div class="bg-light p-3 rounded-3 border text-center mt-4">
                    <span class="small text-muted d-block mb-1">Доступные места</span>
                    <h3 class="fw-bold text-dark mb-0"><?php echo intval($vacancy['places_count'] ?? 1); ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'temp/footer.php'; ?>
