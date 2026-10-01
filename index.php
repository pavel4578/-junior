<?php 
session_start(); 
include 'temp/head.php';
include 'temp/bd.php'; 

if(!empty($_SESSION['role'])){
    $role = $_SESSION['role'];
    if($role == 'student'){
        include 'temp/nav_client.php';
    }
    elseif ($role == 'admin'){
        include 'temp/nav_manager.php';
    }
}
else{
    include 'temp/nav.php';
}
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$tech = isset($_GET['tech']) ? trim($_GET['tech']) : '';
$format = isset($_GET['format']) && in_array($_GET['format'], ['вакансия', 'стажировка'])
    ? mysqli_real_escape_string($mysqli, $_GET['format'])
    : null;

$sql = "SELECT vacancies.*, companies.name AS company_name 
FROM vacancies JOIN companies ON vacancies.company_id = companies.id WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND (vacancies.title LIKE '%" . mysqli_real_escape_string($mysqli, $search) . "%' OR companies.name LIKE '%" . mysqli_real_escape_string($mysqli, $search) . "%')";
}
if (!empty($tech)) {
    $sql .= " AND (vacancies.tech_stack LIKE '%" . mysqli_real_escape_string($mysqli, $tech) . "%')";
}
if ($format !== null) { 
    $sql .= " AND (vacancies.format_type = '$format')";
}
$sql .= " ORDER BY vacancies.id DESC LIMIT 30"; 
$result = mysqli_query($mysqli, $sql);
?>
<h1 class="text-center mb-4 font-weight-bold text-primary fw-bold">Сервис поиска вакансий для начинающих IT-специалистов «Junior Hunt» </h1>
<div class="card border-2  rounded-4 p-4 mb-5 bg-white" style="box-shadow: 0px 10px 30px rgba(0, 123, 255, 0.2);">
    <h4 class="fw-bold text-dark mb-3">Полнотекстовый поиск</h4>
    <form method="GET" action="<?= ($_SERVER['PHP_SELF']); ?>" class="row g-3">
        <div class="col-12 col-md-4">
            <label class="form-label small fw-bold text-muted text-uppercase">Ключевые слова</label>
            <input type="text" name="search" class="form-control bg-light border-2 py-2"
                placeholder="Примеры: Python, Стажер, Авито..." value="<?= ($search); ?>">
        </div>
        <div class="col-12 col-md-3">
            <label class="form-label small fw-bold text-muted text-uppercase">Стек технологий</label>
            <input type="text" name="tech" class="form-control bg-light border-2 py-2"
                placeholder="Например: Django, React" value="<?= ($tech); ?>">
        </div>
        <div class="col-12 col-md-3">
            <label class="form-label small fw-bold text-muted text-uppercase">Формат работы</label>
            <select name="format" class="form-select bg-light border-2 py-2">
                <option value="">Все предложения</option> 
                <option value="вакансия" <?= $format === 'вакансия' ? 'selected' : ''; ?>>Вакансии</option>
                <option value="стажировка" <?= $format === 'стажировка' ? 'selected' : ''; ?>>Стажировки</option>
            </select>
        </div>
        <div class="col-12 col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm py-2">Найти</button>
        </div>
    </form>
</div>
<h2 class="text-center mb-4 font-weight-bold text-primary fw-bold">Актуальные предложения</h2>
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
    <?php 
    if(mysqli_num_rows($result) > 0):
        while($row = mysqli_fetch_assoc($result)): ?>
            <div class="col">
                <div class="card h-100 shadow-sm hover-shadow border-2 rounded-3 overflow-hidden d-flex flex-column justify-content-between" >
                    <div>
                        <div class="position-relative">
                            <img src="img/<?= (($row['id'] % 6) + 1).'.png'; ?>" class="card-img-top" alt="Vacancy" style="height: 200px; object-fit: cover;">
                            <span class="position-absolute top-0 start-0 m-3 badge <?= $row['format_type'] == 'вакансия' ? 'bg-success' : 'bg-info'; ?> text-capitalize px-3 py-2 fw-bold text-white shadow-sm">
                                <?= htmlspecialchars($row['format_type']); ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-dark mb-1"><?= ($row['title']); ?></h5>
                            <p class="card-text text-primary small fw-semibold mb-2">@<?= ($row['company_name']); ?></p>
                            <p class="card-text text-success fw-bold mb-3"><?= number_format($row['salary'], 0, '.', ' '); ?> ₽</p>
                            
                            <?php if(!empty($row['tech_stack'])): ?>
                                <div class="mt-2">
                                    <span class="text-muted d-block small mb-1 fw-bold">Стек:</span>
                                    <div class="d-flex flex-wrap gap-1">
                                        <?php foreach(explode(',', $row['tech_stack']) as $tag): ?>
                                            <span class="badge bg-light text-secondary border font-monospace small"><?= (trim($tag)); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                         
                    <div class="card-footer bg-white border-0 p-3 pt-0 d-flex gap-2">
                        <a href="update_cabinet.php?vacancy_id=<?= $row['id']; ?>&status=просмотрено" class="btn btn-outline-primary btn-sm flex-grow-1 fw-bold rounded-2 py-2 text-decoration-none">
                            Подробнее
                        </a>
                        <a href="update_cabinet.php?vacancy_id=<?= $row['id']; ?>&status=подана заявка" class="btn btn-primary btn-sm flex-grow-1 fw-bold rounded-2 py-2 shadow-sm text-decoration-none text-white">
                            Подать Заявку
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; 
    else:
    ?>
        <div class="col-12 text-center my-5">
            <p class="text-muted fs-5">По вашему запросу ничего не найдено.</p>
            <a href="index.php" class="btn btn-secondary btn-sm">Сбросить фильтры</a>
        </div>
    <?php endif; ?>
</div>


<?php 
include 'temp/footer.php';

?>