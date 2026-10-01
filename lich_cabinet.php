<?php 
include 'temp/head.php';
include 'temp/bd.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Проверяю авторизацию студента
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'student' || empty($_SESSION['id_user'])) {
    echo "<div class='container mt-5 alert alert-warning text-center fw-bold'>Для просмотра личного кабинета необходимо авторизоваться как студент.</div>";
    include 'temp/footer.php';
    exit;
}

include 'temp/nav_client.php';

$user_id = intval($_SESSION['id_user']);

$sql = "SELECT 
            actions.status AS student_status, 
            actions.action_at,
            vacancies.title AS vacancy_title, 
            vacancies.salary, 
            vacancies.format_type,
            companies.name AS company_name,
            statuses.status AS admin_status,
            statuses.admin_comment
        FROM student_vacancy_actions AS actions
        JOIN vacancies ON actions.vacancy_id = vacancies.id
        JOIN companies ON vacancies.company_id = companies.id
        LEFT JOIN application_statuses AS statuses ON (actions.user_id = statuses.user_id AND actions.vacancy_id = statuses.vacancy_id)
        WHERE actions.user_id = $user_id
        ORDER BY actions.action_at DESC";

$result = mysqli_query($mysqli, $sql);
?>

<div class="container pt-5 pb-5">
    <div class="d-flex flex-col flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <div>
            <h1 class="fw-bold text-dark mb-1">Личный кабинет студента</h1>
            <p class="text-muted mb-0">Ниже представлены вакансии и стажировки, которые вы просматривали, а также статусы ответов от компаний.</p>
        </div>
        <div class="bg-light px-3 py-2 rounded border text-md-end">
            <span class="small text-muted d-block">Вы вошли как:</span>
            <strong class="text-primary"><?php echo ($_SESSION['fio'] ?? 'Студент'); ?></strong>
        </div>
    </div>

      <div class="card border-2 shadow-sm rounded-4 p-4 bg-white">
        <h3 class="fw-bold text-dark mb-4">История вашей активности</h3>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle custom-table">
                    <thead class="table-light text-uppercase fs-7 tracking-wider">
                        <tr>
                            <th scope="col" class="py-3">Компания и Позиция</th>
                            <th scope="col" class="py-3">Тип</th>
                            <th scope="col" class="py-3">Ваш статус</th>
                            <th scope="col" class="py-3">Ответ компании</th>
                            <th scope="col" class="py-3 text-end">Дата действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="py-3">
                                    <h6 class="fw-bold text-dark mb-0"><?php echo ($row['vacancy_title']); ?></h6>
                                    <span class="text-primary small fw-semibold">@<?php echo ($row['company_name']); ?></span>
                                    <span class="text-muted small d-block"><?php echo number_format($row['salary'], 0, '.', ' '); ?> ₽</span>
                                </td>
                                <td>
                                    <span class="badge <?php echo $row['format_type'] == 'вакансия' ? 'bg-success' : 'bg-info'; ?> text-white text-capitalize">
                                        <?php echo ($row['format_type']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($row['student_status'] === 'подана заявка'): ?>
                                        <span class="badge bg-primary px-3 py-2 rounded-pill shadow-xs">Подана заявка</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary text-white px-3 py-2 rounded-pill">Просмотрено</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($row['admin_status'])): ?>
                                        <?php 
                                        $badge_class = 'bg-warning text-dark';
                                        if ($row['admin_status'] === 'принят') $badge_class = 'bg-success text-white';
                                        if ($row['admin_status'] === 'отказ') $badge_class = 'bg-danger text-white';
                                        if ($row['admin_status'] === 'приглашение') $badge_class = 'bg-purple text-white';
                                        ?>
                                        <span class="badge <?php echo $badge_class; ?> px-2.5 py-1.5 rounded fw-bold text-uppercase fs-7 mb-1 d-inline-block">
                                            <?php echo ($row['admin_status']); ?>
                                        </span>
                                        <?php if (!empty($row['admin_comment'])): ?>
                                            <small class="d-block text-muted bg-light p-2 rounded border border-light mt-1" style="max-width: 300px;">
                                                 <strong>Комментарий:</strong> <?php echo ($row['admin_comment']); ?>
                                            </small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted small italic">На рассмотрении</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end text-muted small py-3">
                                    <?php echo date('d.m.Y H:i', strtotime($row['action_at'])); ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5 bg-light rounded-3 border border-dashed">
                <div class="fs-1 text-muted mb-2"></div>
                <h5 class="fw-bold text-secondary">Вы еще не совершали активностей</h5>
                <p class="text-muted small mb-3">Перейдите на главную страницу, чтобы изучить доступные предложения, открыть детальную информацию или оставить отклик.</p>
                <a href="index.php" class="btn btn-primary btn-sm fw-bold px-4 py-2 shadow-sm">Найти вакансию</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'temp/footer.php'; ?>

