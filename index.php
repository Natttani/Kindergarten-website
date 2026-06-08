<?php
// Подключаем базу данных
$db_path = __DIR__ . '/kindergarten.db';
$db = new SQLite3($db_path);

// Создаём таблицу, если её нет
$db->exec('
    CREATE TABLE IF NOT EXISTS messages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL,
        message TEXT NOT NULL,
        created_at TEXT DEFAULT CURRENT_TIMESTAMP
    )
');

$success_message = '';
$error_message = '';

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    $errors = [];
    if (empty($name)) $errors[] = 'Введите имя';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Введите корректный email';
    if (empty($message)) $errors[] = 'Введите сообщение';
    
    if (empty($errors)) {
        $stmt = $db->prepare('INSERT INTO messages (name, email, message) VALUES (:name, :email, :message)');
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':message', $message, SQLITE3_TEXT);
        
        if ($stmt->execute()) {
            $success_message = 'Сообщение отправлено! Мы свяжемся с вами.';
        } else {
            $error_message = 'Ошибка при отправке. Попробуйте позже.';
        }
    } else {
        $error_message = implode(', ', $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="description" content="Официальный сайт МАДОУ детский сад №154">
    <title>МАДОУ д/с №154 | Детский сад в Новосибирске</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
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

<header class="hero">
    <div class="container hero-content">
        <div class="hero-badge">Муниципальное автономное учреждение</div>
        <h1>МАДОУ д/с №154</h1>
        <div class="hero-divider"></div>
        <p>Официальный сайт дошкольного образовательного учреждения</p>
        <div class="hero-buttons">
            <a href="#contacts" class="btn-primary">Связаться</a>
            <a href="#about" class="btn-outline">О садике</a>
        </div>
    </div>
    <div class="hero-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120">
            <path fill="#ffffff" fill-opacity="1" d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
        </svg>
    </div>
</header>

<nav class="navbar">
    <div class="container nav-container">
        <div class="logo">Д/с №154</div>
        <ul class="nav-links">
            <li><a href="#about">О нас</a></li>
            <li><a href="#groups">Группы</a></li>
            <li><a href="#news">Новости</a></li>
            <li><a href="#contacts">Контакты</a></li>
        </ul>
        <div class="menu-toggle" id="mobile-menu">
            <i class="fas fa-bars"></i>
        </div>
    </div>
</nav>

<main>
    <section id="about" class="container section">
        <div class="section-header">
            <span class="section-tag">Кто мы</span>
            <h2>О детском саде</h2>
            <div class="underline"></div>
        </div>
        <div class="about-grid">
            <div class="about-text">
                <p><strong>Муниципальное автономное дошкольное образовательное учреждение детский сад №154</strong> осуществляет образовательную деятельность по программам дошкольного образования в соответствии с ФГОС.</p>
                <p>Основной задачей учреждения является создание комфортных, безопасных и развивающих условий для обучения, воспитания и всестороннего развития детей дошкольного возраста.</p>
                <p>Мы работаем, чтобы каждый ребенок чувствовал себя защищенным, уверенным и счастливым, раскрывая свои способности в дружной атмосфере.</p>
                <div class="about-stats">
                    <div class="stat"><span class="stat-number">10+</span><span class="stat-label">лет опыта</span></div>
                    <div class="stat"><span class="stat-number">8</span><span class="stat-label">возрастных групп</span></div>
                    <div class="stat"><span class="stat-number">24/7</span><span class="stat-label">забота о детях</span></div>
                </div>
            </div>
            <div class="about-image">
                <i class="fas fa-child"></i>
                <i class="fas fa-puzzle-piece"></i>
                <i class="fas fa-book-open"></i>
            </div>
        </div>
    </section>

    <section id="groups" class="container section">
        <div class="section-header">
            <span class="section-tag">Возрастные категории</span>
            <h2>Наши группы</h2>
            <div class="underline"></div>
            <p class="section-subtitle">Индивидуальный подход к каждому возрасту и этапу развития</p>
        </div>
        <div class="cards">
            <div class="card">
                <div class="card-icon"><i class="fas fa-baby-carriage"></i></div>
                <h3>Ясельная группа</h3>
                <p>Возраст: <strong>от 1,5 до 3 лет</strong></p>
                <p>Адаптация, развитие моторики, режим дня, первые шаги в общении.</p>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-smile"></i></div>
                <h3>Младшая группа</h3>
                <p>Возраст: <strong>от 3 до 4 лет</strong></p>
                <p>Развитие речи, основы этикета, творчество и игровая деятельность.</p>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-users"></i></div>
                <h3>Средняя группа</h3>
                <p>Возраст: <strong>от 4 до 5 лет</strong></p>
                <p>Логика, любознательность, первые навыки работы в коллективе.</p>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-graduation-cap"></i></div>
                <h3>Старшая группа</h3>
                <p>Возраст: <strong>от 5 до 6 лет</strong></p>
                <p>Подготовка к школе, основы математики, чтение, проектная деятельность.</p>
            </div>
        </div>
    </section>

    <section id="news" class="container section">
        <div class="section-header">
            <span class="section-tag">События и жизнь сада</span>
            <h2>Новости</h2>
            <div class="underline"></div>
        </div>
        <div class="news-grid">
            <div class="news-item">
                <div class="news-date">
                    <span class="day">28</span>
                    <span class="month">Май</span>
                </div>
                <div class="news-content">
                    <h3>Подготовка к летнему сезону</h3>
                    <p>На территории учреждения обновлены игровые площадки, установлены новые теневые навесы, а также обустроены зоны отдыха с безопасным покрытием.</p>
                </div>
            </div>
            <div class="news-item">
                <div class="news-date">
                    <span class="day">01</span>
                    <span class="month">Июнь</span>
                </div>
                <div class="news-content">
                    <h3>Праздник ко Дню защиты детей</h3>
                    <p>Для воспитанников была организована яркая праздничная программа с аниматорами, конкурсами и сладкими угощениями. Дети получили массу положительных эмоций!</p>
                </div>
            </div>
            <div class="news-item">
                <div class="news-date">
                    <span class="day">10</span>
                    <span class="month">Июнь</span>
                </div>
                <div class="news-content">
                    <h3>Выпускной в старшей группе</h3>
                    <p>Состоялся трогательный выпускной бал. Наши воспитанники прощаются с детским садом и готовятся к школе. Желаем удачи первоклассникам!</p>
                </div>
            </div>
        </div>
    </section>

    <section id="contacts" class="container section">
        <div class="section-header">
            <span class="section-tag">Обратная связь</span>
            <h2>Контакты</h2>
            <div class="underline"></div>
        </div>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        
        <div class="contacts-grid">
            <div class="contacts-info">
                <div class="contact-card">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h4>Адрес</h4>
                        <p>г. Екатеринбург, ул. Детская, д. 15</p>
                    </div>
                </div>
                <div class="contact-card">
                    <i class="fas fa-phone-alt"></i>
                    <div>
                        <h4>Телефон</h4>
                        <p>+7 (900) 000-00-00</p>
                        <p class="small">Приемная: пн-пт 8:00–17:00</p>
                    </div>
                </div>
                <div class="contact-card">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Email</h4>
                        <p>sad154@edu.ekb.ru</p>
                    </div>
                </div>
                <div class="contact-card">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h4>Режим работы</h4>
                        <p>Понедельник — Пятница: 7:00 – 19:00</p>
                    </div>
                </div>
            </div>
            <div class="contact-form">
                <h3>Напишите нам</h3>
                <form method="POST" action="">
                    <input type="text" name="name" placeholder="Ваше имя" required>
                    <input type="email" name="email" placeholder="Email для ответа" required>
                    <textarea name="message" rows="4" placeholder="Сообщение..." required></textarea>
                    <button type="submit" name="send_message" class="btn-primary">Отправить</button>
                </form>
            </div>
        </div>
    </section>
</main>

<footer>
    <div class="container footer-content">
        <div class="footer-col">
            <h4>МАДОУ д/с №154</h4>
            <p>Счастливое детство начинается здесь</p>
            <div class="social-icons">
                <a href="#"><i class="fab fa-vk"></i></a>
                <a href="#"><i class="fab fa-telegram"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
        <div class="footer-col">
            <h4>Навигация</h4>
            <ul>
                <li><a href="#about">О нас</a></li>
                <li><a href="#groups">Группы</a></li>
                <li><a href="#news">Новости</a></li>
                <li><a href="#contacts">Контакты</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Документы</h4>
            <ul>
                <li><a href="#">Лицензия</a></li>
                <li><a href="#">Устав</a></li>
                <li><a href="#">Правила приема</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <p>© 2026 МАДОУ д/с №154. Все права защищены.</p>
        </div>
    </div>
</footer>

<script>
    const menuToggle = document.getElementById('mobile-menu');
    const navLinks = document.querySelector('.nav-links');
    if(menuToggle) {
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            const icon = menuToggle.querySelector('i');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        });
    }
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if(target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                if(navLinks.classList.contains('active')) {
                    navLinks.classList.remove('active');
                    const icon = menuToggle.querySelector('i');
                    icon.classList.add('fa-bars');
                    icon.classList.remove('fa-times');
                }
            }
        });
    });
</script>
</body>
</html>