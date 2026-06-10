<?php
session_start();
$db_path = __DIR__ . '/kindergarten.db';

// Пароль: admin
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

if (isset($_POST['login']) && !empty($_POST['password'])) {
    if (md5($_POST['password']) === '21232f297a57a5a743894a0e4a801fc3') { // md5('admin')
        $_SESSION['admin_logged_in'] = true;
    } else {
        $error = 'Неверный пароль';
    }
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Форма входа
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Вход для администратора</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">

        <link rel="icon" href="/logo.svg" type="image/svg+xml">

        <style>
            * { margin:0; padding:0; box-sizing:border-box; }
            body { font-family:'Inter',sans-serif; background:#f9fafb; min-height:100vh; display:flex; align-items:center; justify-content:center; }
            .login-box { background:white; padding:2rem; border-radius:24px; box-shadow:0 20px 35px -10px rgba(0,0,0,0.1); width:90%; max-width:400px; text-align:center; }
            .login-box h2 { margin-bottom:1.5rem; color:#2b7a3e; }
            input { width:100%; padding:0.9rem; margin-bottom:1rem; border:1px solid #e5e7eb; border-radius:16px; font-family:inherit; font-size:1rem; }
            button { background:#2b7a3e; color:white; padding:0.9rem; border:none; border-radius:40px; font-weight:600; cursor:pointer; width:100%; font-size:1rem; }
            button:hover { background:#1e5a2f; }
            .error { color:#dc2626; margin-top:0.5rem; font-size:0.9rem; background:#fee2e2; padding:0.5rem; border-radius:12px; }
            .hint { margin-top:1rem; font-size:0.75rem; color:#9ca3af; }
        </style>

        <!-- Yandex.Metrika counter -->
        <script type="text/javascript">
            (function(m,e,t,r,i,k,a){
                m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
                m[i].l=1*new Date();
                for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
                k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
            })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=109724150', 'ym');

            ym(109724150, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", referrer: document.referrer, url: location.href, accurateTrackBounce:true, trackLinks:true});
        </script>
        <noscript><div><img src="https://mc.yandex.ru/watch/109724150" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        <!-- /Yandex.Metrika counter -->

    </head>
    <body>
        <div class="login-box">
            <h2>Вход в админ-панель</h2>
            <form method="POST">
                <input type="password" name="password" placeholder="Введите пароль" required autofocus>
                <button type="submit" name="login">Войти</button>
                <?php if (isset($error)): ?>
                    <div class="error">❌ <?= htmlspecialchars($error) ?></div>
                    <div class="hint">Подсказка: пароль — <strong>admin</strong> (без кавычек)</div>
                <?php endif; ?>
            </form>
            <div class="hint">Пароль по умолчанию: admin</div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// --- АДМИН-ПАНЕЛЬ ---
$db = new SQLite3($db_path);

// Удаление сообщения
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $stmt = $db->prepare('DELETE FROM messages WHERE id = :id');
    $stmt->bindValue(':id', (int)$_GET['delete'], SQLITE3_INTEGER);
    $stmt->execute();
    header('Location: admin.php');
    exit;
}

// Получение всех сообщений
$result = $db->query('SELECT id, name, email, message, created_at FROM messages ORDER BY id DESC');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель | МАДОУ д/с №154</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:#f9fafb; padding:2rem 1rem; }
        .container { max-width:1280px; margin:0 auto; }
        .header { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:2rem; background:white; padding:1rem 2rem; border-radius:24px; box-shadow:0 4px 12px rgba(0,0,0,0.05); }
        .header h1 { color:#2b7a3e; font-size:1.6rem; }
        .stats { background:#e8f5e9; padding:0.5rem 1rem; border-radius:40px; font-size:0.9rem; font-weight:600; }
        .logout-btn { background:#ef4444; color:white; border:none; padding:0.5rem 1.2rem; border-radius:40px; cursor:pointer; font-weight:600; transition:0.2s; }
        .logout-btn:hover { background:#dc2626; }
        .back-link { color:#2b7a3e; text-decoration:none; margin-right:1rem; }
        .back-link:hover { text-decoration:underline; }
        table { width:100%; background:white; border-radius:24px; overflow:hidden; box-shadow:0 8px 25px rgba(0,0,0,0.05); border-collapse:collapse; }
        th, td { padding:1rem; text-align:left; border-bottom:1px solid #edf2f7; }
        th { background:#f1f5f9; color:#1f2937; font-weight:600; }
        tr:hover { background:#fafafa; }
        .message-text { max-width:300px; word-wrap:break-word; color:#4b5563; line-height:1.4; }
        .delete-btn { background:#fee2e2; color:#dc2626; border:none; padding:0.3rem 0.8rem; border-radius:20px; cursor:pointer; font-size:0.8rem; transition:0.2s; text-decoration:none; display:inline-block; }
        .delete-btn:hover { background:#fecaca; }
        .empty-row td { padding:3rem; text-align:center; color:#9ca3af; }
        .date { font-size:0.8rem; color:#6b7280; }
        @media (max-width:768px) {
            body { padding:1rem; }
            th, td { padding:0.75rem; font-size:0.85rem; }
            .message-text { max-width:150px; }
            .header { flex-direction:column; text-align:center; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <a href="/" class="back-link"><i class="fas fa-arrow-left"></i> На сайт</a>
            <h1><i class="fas fa-envelope"></i> Сообщения из формы обратной связи</h1>
        </div>
        <div style="display:flex; gap:1rem; align-items:center;">
            <span class="stats"><i class="fas fa-comments"></i> <?= $db->querySingle('SELECT COUNT(*) FROM messages') ?> сообщений</span>
            <form method="POST" style="margin:0;">
                <button type="submit" name="logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Выйти</button>
            </form>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Сообщение</th>
                <th>Дата</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $has_messages = false;
            while ($row = $result->fetchArray(SQLITE3_ASSOC)):
                $has_messages = true;
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                    <td><a href="mailto:<?= htmlspecialchars($row['email']) ?>" style="color:#2b7a3e;"><?= htmlspecialchars($row['email']) ?></a></td>
                    <td class="message-text"><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                    <td class="date"><?= htmlspecialchars(date('d.m.Y H:i', strtotime($row['created_at']))) ?></td>
                    <td><a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Удалить сообщение от <?= htmlspecialchars($row['name']) ?>?')" class="delete-btn"><i class="fas fa-trash-alt"></i> Удалить</a></td>
                </tr>
            <?php endwhile; ?>
            <?php if (!$has_messages): ?>
                <tr class="empty-row">
                    <td colspan="6"><i class="fas fa-inbox"></i> Сообщений пока нет</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>