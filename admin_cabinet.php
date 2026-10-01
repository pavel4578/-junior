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
$vacancies_query = "
    SELECT v.id, v.title, v.format_type, v.places_count, 
           COUNT(CASE WHEN sva.status = 'подана заявка' THEN 1 END) AS total_applications
    FROM vacancies v
    LEFT JOIN student_vacancy_actions sva ON v.id = sva.vacancy_id
    GROUP BY v.id
";
$vacancies_result = $mysqli->query($vacancies_query);


$applications_query = "
    SELECT 
        u.id_user, u.fio, u.email, u.phone,
        v.id AS vacancy_id, v.title AS vacancy_title,
        r.file_path AS resume_path,
        sva.action_at,
        ap.status AS admin_status,
        ap.admin_comment
    FROM student_vacancy_actions sva
    JOIN users u ON sva.user_id = u.id_user
    JOIN vacancies v ON sva.vacancy_id = v.id
    LEFT JOIN resumes r ON u.id_user = r.user_id
    LEFT JOIN application_statuses ap ON (sva.user_id = ap.user_id AND sva.vacancy_id = ap.vacancy_id)
    WHERE sva.status = 'подана заявка'
    ORDER BY sva.action_at DESC
";
$applications_result = $mysqli->query($applications_query);
?>


<style>
    .admin-container { max-width: 1200px; margin: 30px auto; padding: 0 15px; font-family: Arial, sans-serif; }
    .admin-title { color: #2c3e50; border-bottom: 2px solid #ecf0f1; padding-bottom: 10px; margin-top: 30px; }
    .admin-table { width: 100%; border-collapse: collapse; margin: 15px 0 40px 0; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .admin-table th, .admin-table td { padding: 12px 15px; border: 1px solid #e1e8ed; text-align: left; }
    .admin-table th { background-color: #34495e; color: white; font-weight: 600; }
    .admin-table tr:nth-child(even) { background-color: #f8f9fa; }
    .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; display: inline-block; }
    .badge-invite { background-color: #d9edf7; color: #31708f; }
    .badge-accept { background-color: #dff0d8; color: #3c763d; }
    .badge-reject { background-color: #f2dede; color: #a94442; }
    .badge-new { background-color: #fcf8e3; color: #8a6d3b; }
    .action-btn { padding: 6px 10px; border-radius: 4px; color: white; font-size: 12px; border: none; cursor: pointer; margin-right: 2px; }
    .btn-create { background-color: #27ae60; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; font-weight: bold; margin-bottom: 15px; }
    .btn-create:hover { background-color: #219653; }
    .btn-edit { background-color: #f39c12; text-decoration: none; }
    .btn-inv { background-color: #2980b9; }
    .btn-acc { background-color: #27ae60; }
    .btn-rej { background-color: #c0392b; }
    .comment-input { width: 90%; padding: 4px; margin-bottom: 6px; font-size: 12px; display: block; border: 1px solid #ccc; border-radius: 4px; }
</style>

<div class="admin-container">
    <h1>Личный кабинет администратора</h1>
    <p>Добро пожаловать, <strong><?php echo htmlspecialchars($_SESSION['fio'] ?? 'Менеджер'); ?></strong>!</p>
      
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название вакансии / стажировки</th>
                <th>Формат</th>
                <th>Доступные места</th>
                <th>Подано заявок студентов</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $vacancies_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                <td><?php echo htmlspecialchars($row['format_type']); ?></td>
                <td><?php echo $row['places_count']; ?></td>
                <td><span style="font-size: 16px; font-weight: bold; color: #2980b9;"><?php echo $row['total_applications']; ?></span></td>
                <td>
                    <a href="form_vacancy.php?id=<?php echo $row['id']; ?>" class="action-btn btn-edit">Изменить</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <h2 class="admin-title">Поступившие заявки от студентов</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Дата подачи</th>
                <th>Студент и контакты</th>
                <th>Выбранная вакансия</th>
                <th>Резюме (PDF)</th>
                <th>Статус модерации</th>
                <th>Управление решением</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($applications_result->num_rows == 0): ?>
                <tr><td colspan="6" style="text-align: center; color: #7f8c8d; font-style: italic;">На данный момент новых поданных заявок нет.</td></tr>
            <?php endif; ?>
            <?php while($app = $applications_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo date('d.m.Y H:i', strtotime($app['action_at'])); ?></td>
                <td>
                    <strong><?php echo ($app['fio']); ?></strong><br>
                    <small>Email: <?php echo ($app['email']); ?> | Тел: <?php echo ($app['phone']); ?></small>
                </td>
                <td><?php echo ($app['vacancy_title']); ?></td>
                <td>
                    <?php if(!empty($app['resume_path'])): ?>
                        <a href="<?php echo($app['resume_path']); ?>" target="_blank" style="color: #2980b9; font-weight: bold; text-decoration: none;"> Открыть PDF</a>
                    <?php else: ?>
                        <span style="color: #95a5a6; font-style: italic;">Не загружено</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($app['admin_status']): ?>
                        <span class="badge badge-<?php echo $app['admin_status']; ?>">
                            <?php echo mb_convert_case($app['admin_status'], MB_CASE_TITLE, "UTF-8"); ?>
                        </span>
                        <?php if($app['admin_comment']): ?>
                            <br><small style="color: #555;"><i>Коммент: <?php echo ($app['admin_comment']); ?></i></small>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="badge badge-new">Новый отклик</span>
                    <?php endif; ?>
                </td>
                <td>
                              <form action="admin_actions.php" method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $app['id_user']; ?>">
                        <input type="hidden" name="vacancy_id" value="<?php echo $app['vacancy_id']; ?>">
                        
                        <input type="text" name="comment" placeholder="Добавить комментарий..." class="comment-input">
                        
                        <button type="submit" name="set_status" value="приглашение" class="action-btn btn-inv">Пригласить</button>
                        <button type="submit" name="set_status" value="принят" class="action-btn btn-acc">Принять</button>
                        <button type="submit" name="set_status" value="отказ" class="action-btn btn-rej">Отказ</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php 
include 'temp/footer.php'; 
?>
