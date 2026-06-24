<?php
// index.php
session_start();

$errors = [];
$successMessage = "";
$registeredData = null;

// Обработка отправки форм
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Обработка авторизации
    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $userpass = isset($_POST['userpass']) ? $_POST['userpass'] : '';

        if (empty($username) || empty($userpass)) {
            $errors[] = "Пожалуйста, введите логин и пароль.";
        } else {
            // В рамках учебной задачи сохраняем логин в сессию
            $_SESSION['username'] = htmlentities($username, ENT_QUOTES, 'UTF-8');
            $successMessage = "Вы успешно вошли как " . $_SESSION['username'] . "!";
        }
    }
    
    // 2. Обработка регистрации
    if (isset($_POST['action']) && $_POST['action'] === 'register') {
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

        // Валидация
        if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
            $errors[] = "Все поля обязательны для заполнения.";
        }
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Некорректный формат Email.";
        }
        if (!empty($password) && strlen($password) < 6) {
            $errors[] = "Пароль должен содержать не менее 6 символов.";
        }
        if ($password !== $confirm_password) {
            $errors[] = "Пароли не совпадают.";
        }

        // Если ошибок нет
        if (empty($errors)) {
            $successMessage = "Регистрация успешно пройдена!";
            $registeredData = [
                'name' => htmlspecialchars($name),
                'email' => htmlspecialchars($email)
            ];
        }
    }
}

// Логика выхода из аккаунта (для удобства тестирования)
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Твоё образование</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://googleapis.com/css2?family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>

    <!-- Вывод системных сообщений (ошибки или успех) сверху страницы, если они есть -->
    <?php if (!empty($errors)): ?>
        <div class="system-alert alert-error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($successMessage)): ?>
        <div class="system-alert alert-success">
            <p><?php echo $successMessage; ?></p>
            <?php if ($registeredData): ?>
                <p>Зарегистрированный пользователь: <strong><?php echo $registeredData['name']; ?></strong> (<?php echo $registeredData['email']; ?>)</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Главный контейнер с фоном фоновых иконок -->
    <div class="hero-wrapper">
        
        <!-- Шапка сайта (Header) -->
        <header class="header">
            <div class="logo">
                <div class="logo-title">Твоё образование</div>
                <div class="logo-subtitle">Дополнительное образование и курсы обучения онлайн</div>
            </div>
            
            <nav class="nav-menu">
                <a href="#" class="nav-link active">Главная</a>
                <a href="#" class="nav-link">Курсы</a>
                <a href="#" class="nav-link">Генераторы</a>
                <a href="#" class="nav-link">Учителю</a>
                <a href="#" class="nav-link">Ученику</a>
                <a href="#" class="nav-link">Руководства</a>
                <a href="#" class="nav-link">Новости</a>
                <a href="#" class="nav-link">Магазин</a>
            </nav>
            
            <!-- Динамическая кнопка: меняется в зависимости от авторизации -->
            <?php if (isset($_SESSION['username'])): ?>
                <div class="user-logged-in">
                    <span>Привет, <strong><?php echo $_SESSION['username']; ?></strong>!</span>
                    <a href="index.php?logout=1" class="btn-logout">Выйти</a>
                </div>
            <?php else: ?>
                <a href="#" class="btn-login" id="showAuthModal">Войти</a>
            <?php endif; ?>
        </header>

        <!-- Основной баннер (Hero Section) -->
        <main class="hero-content">
            <!-- Левая часть: Текст и поиск -->
            <div class="hero-text-block">
                <h1 class="hero-title">
                    Обучающие <span class="orange-text">курсы</span><br>
                    Генераторы <span class="orange-text">задач</span>
                </h1>
                <p class="hero-description">
                    И многое другое для учителей и обучающихся.<br>
                    Проект старается сделать онлайн образовательное более доступным
                </p>
                
                <!-- Форма поиска -->
                <form class="search-form">
                    <div class="search-input-wrapper">
                        <span class="search-icon">🔍</span>
                        <input type="text" placeholder="Искать по сайту..." class="search-input">
                    </div>
                    <button type="submit" class="btn-search">Найти</button>
                </form>
            </div>

            <!-- Правая часть: Композиция картинок -->
            <div class="hero-image-block">
                <img src="media/circle.png" alt="Фон" class="img-circle">
                <img src="media/people.png" alt="Люди" class="img-people">
            </div>
        </main>

    </div>
    
    <!-- Блок преимуществ (Features Section) -->
    <section class="features-section">
        <div class="features-container">
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <img src="media/courses.png" alt="Курсы" class="feature-icon">
                </div>
                <div class="feature-info">
                    <h3 class="feature-title">Курсы</h3>
                    <p class="feature-text">И обучающие материалы для самообразования, повышения квалификации, аттестации. База курсов постоянно расширяется</p>
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <img src="media/generators.png" alt="Генераторы" class="feature-icon">
                </div>
                <div class="feature-info">
                    <h3 class="feature-title">Генераторы</h3>
                    <p class="feature-text">Задачи, на которые нет решений и дети не смогут списать их. Плюс возможность большого выбора заданий для индивидуальной работы</p>
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <img src="media/certification.png" alt="Сертификат" class="feature-icon">
                </div>
                <div class="feature-info">
                    <h3 class="feature-title">Сертификат</h3>
                    <p class="feature-text">Мы работаем над получением образовательной лицензии, чтобы выдавать документы государственного образца</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Блок актуальных программ (Programs Section) -->
    <section class="programs-section">
        <div class="programs-header">
            <img src="media/bulb.png" alt="Лампочка" class="decor-bulb">
            <h2 class="programs-main-title">Актуальные программы</h2>
            <p class="programs-subtitle">Популярные образовательные программы на нашей платформе.</p>
            <img src="media/arrow.png" alt="Стрелка" class="decor-arrow">
        </div>

        <div class="cards-container">
            <!-- Карточка 1 -->
            <div class="program-card">
                <div class="card-image-wrapper">
                    <img src="media/card1.png" alt="Решение текстовых задач" class="card-img">
                </div>
                <div class="card-content">
                    <div class="card-meta">
                        <span class="card-category">Математика 8 класс</span>
                        <span class="card-stars">★★★★★</span>
                    </div>
                    <h3 class="card-title">Решение текстовых задач</h3>
                    <div class="card-price">1000 РУБ</div>
                    <div class="card-divider"></div>
                    <div class="card-stats">
                        <span class="stat-item">🕒 4ч 30мин</span>
                        <span class="stat-item">💻 8 уроков</span>
                        <span class="stat-item">👤 50 учеников</span>
                    </div>
                </div>
                <a href="#" class="btn-enroll">Записаться</a>
            </div>

            <!-- Карточка 2 -->
            <div class="program-card">
                <div class="card-image-wrapper">
                    <img src="media/card2.png" alt="UI/UX дизайн для начинающих" class="card-img">
                </div>
                <div class="card-content">
                    <div class="card-meta">
                        <span class="card-category">UI/UX дизайн</span>
                        <span class="card-stars">★★★★★</span>
                    </div>
                    <h3 class="card-title">UI/UX дизайн для начинающих</h3>
                    <div class="card-price">35000 РУБ</div>
                    <div class="card-divider"></div>
                    <div class="card-stats">
                        <span class="stat-item">🕒 22ч 30мин</span>
                        <span class="stat-item">💻 34 урока</span>
                        <span class="stat-item">👤 250 учеников</span>
                    </div>
                </div>
                <a href="#" class="btn-enroll">Записаться</a>
            </div>

            <!-- Карточка 3 -->
            <div class="program-card">
                <div class="card-image-wrapper">
                    <img src="media/card3.png" alt="UI/UX дизайн для продолжающих" class="card-img">
                </div>
                <div class="card-content">
                    <div class="card-meta">
                        <span class="card-category">UI/UX дизайн</span>
                        <span class="card-stars">★★★★★</span>
                    </div>
                    <h3 class="card-title">UI/UX дизайн для продолжающих</h3>
                    <div class="card-price">65000 РУБ</div>
                    <div class="card-divider"></div>
                    <div class="card-stats">
                        <span class="stat-item">🕒 22ч 30мин</span>
                        <span class="stat-item">💻 34 урока</span>
                        <span class="stat-item">👤 25 учеников</span>
                    </div>
                </div>
                <a href="#" class="btn-enroll">Записаться</a>
            </div>
        </div>
    </section>

    <!-- Блок быстрого доступа (Quick Access Section) -->
    <section class="quick-access-section">
        <div class="quick-access-container">
            <div class="quick-access-media">
                <img src="media/backgroind1.png" alt="Фоновые иконки" class="quick-bg-icons">
                <img src="media/woman.png" alt="Девушка за компьютером" class="quick-img-woman">
            </div>
            <div class="quick-access-content">
                <h2 class="quick-title">Быстрый <span class="quick-title-orange">доступ</span></h2>
                <ul class="quick-list">
                    <li class="quick-item">
                        <div class="quick-marker"></div>
                        <div class="quick-item-text">
                            <h4 class="quick-item-title">Онлайн-курсы</h4>
                            <p class="quick-item-desc">Обучающие, повышение квалификации, переподготовка</p>
                        </div>
                    </li>
                    <li class="quick-item">
                        <div class="quick-marker"></div>
                        <div class="quick-item-text">
                            <h4 class="quick-item-title">Генераторы</h4>
                            <p class="quick-item-desc">Задачи для домашней работы или индивидуальных заданий</p>
                        </div>
                    </li>
                    <li class="quick-item">
                        <div class="quick-marker"></div>
                        <div class="quick-item-text">
                            <h4 class="quick-item-title">Библиотека материалов</h4>
                            <p class="quick-item-desc">Материалы, добавленные другими преподавателями</p>
                        </div>
                    </li>
                    <li class="quick-item">
                        <div class="quick-marker"></div>
                        <div class="quick-item-text">
                            <h4 class="quick-item-title">Методические разработки</h4>
                            <p class="quick-item-desc">От педагогов в помощь педагогам</p>
                        </div>
                    </li>
                    <li class="quick-item">
                        <div class="quick-marker"></div>
                        <div class="quick-item-text">
                            <h4 class="quick-item-title">Онлайн мероприятия</h4>
                            <p class="quick-item-desc">За участие можно получить сертификат в ваше портфолио</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Блок отзывов (Reviews Section) -->
    <section class="reviews-section">
        <img src="media/arrow1.png" alt="Стрелка декоративная" class="decor-review-arrow">
        <img src="media/planet.png" alt="Планета декоративная" class="decor-review-planet">

        <div class="reviews-header">
            <h2 class="reviews-title">Что говорят о нас</h2>
            <p class="reviews-subtitle">Учителя и ученики, которые пользуются нашей платформой</p>
        </div>

        <div class="reviews-container">
            <div class="review-card">
                <p class="review-text">"Teachings of the great explore of truth, the master-builder of human happiness, no one rejects, dislikes, or avoids pleasure itself, pleasure itself"</p>
                <div class="review-user">
                    <img src="media/Review_image1.png" alt="Finlay Kirk" class="user-avatar">
                    <div class="user-info">
                        <h4 class="user-name">Finlay Kirk</h4>
                        <span class="user-role">Web Developer</span>
                    </div>
                </div>
            </div>

            <div class="review-card">
                <p class="review-text">"Complete account of the system and expound the actual Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots"</p>
                <div class="review-user">
                    <img src="media/Review_image2.png" alt="Dannette P. Cervantes" class="user-avatar">
                    <div class="user-info">
                        <h4 class="user-name">Dannette P. Cervantes</h4>
                        <span class="user-role">Web Design</span>
                    </div>
                </div>
            </div>

            <div class="review-card">
                <p class="review-text">"There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour"</p>
                <div class="review-user">
                    <img src="media/Review_image3.png" alt="Clara R. Altman" class="user-avatar">
                    <div class="user-info">
                        <h4 class="user-name">Clara R. Altman</h4>
                        <span class="user-role">UI/UX Design</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="reviews-pagination">
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </section>

    <!-- Блок Наша команда (Team Section) -->
    <section class="team-section">
        <div class="team-header">
            <h2 class="team-title">Наша команда</h2>
            <p class="team-subtitle">Те, кто стоят у истоков образовательного проекта</p>
        </div>

        <div class="team-container">
            <div class="team-card">
                <div class="team-image-wrapper">
                    <img src="media/team1.png" alt="Маминов Сергей" class="team-img">
                </div>
                <div class="team-content">
                    <h3 class="member-name">Маминов Сергей</h3>
                    <div class="member-meta">Учитель математики <span class="member-handle">@maminov</span></div>
                    <p class="member-bio">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex commodo.</p>
                    <div class="team-card-footer">
                        <span class="member-role">Руководитель проекта</span>
                        <div class="social-links">
                            <a href="#" class="social-icon"><img src="media/telegram.png" alt="Telegram"></a>
                            <a href="#" class="social-icon"><img src="media/vk.png" alt="VK"></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="team-card">
                <div class="team-image-wrapper">
                    <img src="media/team2.png" alt="Tracy D. Wright" class="team-img">
                </div>
                <div class="team-content">
                    <h3 class="member-name">Tracy D. Wright</h3>
                    <div class="member-meta">Professor <span class="member-handle">@George Brown College</span></div>
                    <p class="member-bio">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex commodo.</p>
                    <div class="team-card-footer">
                        <span class="member-role orange-role">Engineering physics</span>
                        <div class="social-links">
                            <a href="#" class="social-icon"><img src="media/telegram.png" alt="Telegram"></a>
                            <a href="#" class="social-icon"><img src="media/vk.png" alt="VK"></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="team-card">
                <div class="team-image-wrapper">
                    <img src="media/team3.png" alt="Cynthia A. Nelson" class="team-img">
                </div>
                <div class="team-content">
                    <h3 class="member-name">Cynthia A. Nelson</h3>
                    <div class="member-meta">Professor <span class="member-handle">@George Brown College</span></div>
                    <p class="member-bio">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex commodo.</p>
                    <div class="team-card-footer">
                        <span class="member-role orange-role">Engineering physics</span>
                        <div class="social-links">
                            <a href="#" class="social-icon"><img src="media/telegram.png" alt="Telegram"></a>
                            <a href="#" class="social-icon"><img src="media/vk.png" alt="VK"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Блок подписки на рассылку (Newsletter Section) -->
    <section class="newsletter-section">
        <div class="newsletter-container">
            <img src="media/arrow_orange.png" alt="Оранжевая стрелка" class="decor-news-arrow">
            <img src="media/bulb.png" alt="Лампочка" class="decor-news-bulb">

            <div class="newsletter-content">
                <h2 class="newsletter-title">Подпишись на нашу рассылку</h2>
                <p class="newsletter-subtitle">Обещаем не спамить и высылать полезные материалы.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Email адрес" class="newsletter-input" required>
                    <button type="submit" class="btn-subscribe">Отправить</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Подвал сайта (Footer Section) -->
    <footer class="footer-section">
        <div class="footer-container">
            <div class="footer-column footer-brand">
                <h3 class="footer-logo">Твоё образование</h3>
                <p class="brand-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy a type specimen book.</p>
            </div>

            <div class="footer-column">
                <h4 class="footer-heading">О проекте</h4>
                <ul class="footer-links">
                    <li><a href="#">О нас</a></li>
                    <li><a href="#">Вакансии</a></li>
                    <li><a href="#">Обратная связь</a></li>
                    <li><a href="#">ЧаВО?</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h4 class="footer-heading">Курсы</h4>
                <ul class="footer-links">
                    <li><a href="#">Категории</a></li>
                    <li><a href="#">Как учиться</a></li>
                    <li><a href="#">Стать преподавателем</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h4 class="footer-heading">Генераторы</h4>
                <ul class="footer-links">
                    <li><a href="#">Домашки</a></li>
                    <li><a href="#">Экзамены</a></li>
                    <li><a href="#">Олимпиады</a></li>
                    <li><a href="#">Листы</a></li>
                </ul>
            </div>

            <div class="footer-column footer-contacts">
                <h4 class="footer-heading">Контакты</h4>
                <ul class="contacts-list">
                    <li><a href="tel:+79000000000">+79000000000</a></li>
                    <li><a href="mailto:kursy@tvoeobr.ru">kursy@tvoeobr.ru</a></li>
                    <li class="address-text">Российская Федерация,<br>Челябинская область,<br>г. Магнитогорск</li>
                </ul>
                <div class="footer-socials">
                    <a href="#" class="footer-social-icon"><img src="media/vk.png" alt="ВКонтакте"></a>
                    <a href="#" class="footer-social-icon"><img src="media/telegram.png" alt="Telegram"></a>
                    <a href="#" class="footer-social-icon"><img src="media/odnoklassniki.png" alt="Одноклассники"></a>
                    <a href="#" class="footer-social-icon"><img src="media/icon.png" alt="Rutube"></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-divider"></div>
            <p class="copyright-text">Твоё образование, Все права защищены, 2023-2024</p>
        </div>
    </footer>

    <!-- ========================================== -->
    <!-- МОДАЛЬНЫЕ ОКНА (ИНТЕГРАЦИЯ ЛАБОРАТОРНОЙ) -->
    <!-- ========================================== -->

    <!-- Модальное окно авторизации -->
    <div class="modal-overlay" id="authModal">
        <div class="modal-box">
            <button class="modal-close" id="closeAuth">&times;</button>
            <h3 class="modal-title">Вход в систему</h3>
            
            <form action="index.php" method="POST" class="modal-form">
                <!-- Скрытое поле для определения типа действия -->
                <input type="hidden" name="action" value="login">
                
                <div class="form-group">
                    <label for="username">Логин (Имя пользователя):</label>
                    <input type="text" id="username" name="username" placeholder="Введите ваш логин" required>
                </div>
                
                <div class="form-group">
                    <label for="userpass">Пароль:</label>
                    <input type="password" id="userpass" name="userpass" placeholder="Введите пароль" required>
                </div>
                
                <button type="submit" class="btn-modal-submit">Войти</button>
            </form>
            <p class="modal-switch">Нет аккаунта? <a href="#" id="switchToRegister">Зарегистрироваться</a></p>
        </div>
    </div>

    <!-- Модальное окно регистрации -->
    <div class="modal-overlay" id="registerModal">
        <div class="modal-box">
            <button class="modal-close" id="closeRegister">&times;</button>
            <h3 class="modal-title">Регистрация нового пользователя</h3>
            
            <form action="index.php" method="POST" class="modal-form">
                <!-- Скрытое поле для определения типа действия -->
                <input type="hidden" name="action" value="register">
                
                <div class="form-group">
                    <label for="reg_name">Ваше имя:</label>
                    <input type="text" id="reg_name" name="name" placeholder="Иван" required>
                </div>
                
                <div class="form-group">
                    <label for="reg_email">Электронная почта:</label>
                    <input type="email" id="reg_email" name="email" placeholder="example@mail.ru" required>
                </div>
                
                <div class="form-group">
                    <label for="reg_password">Пароль (не менее 6 символов):</label>
                    <input type="password" id="reg_password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="reg_confirm">Подтвердите пароль:</label>
                    <input type="password" id="reg_confirm" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn-modal-submit">Зарегистрироваться</button>
            </form>
            <p class="modal-switch">Уже есть профиль? <a href="#" id="switchToAuth">Войти</a></p>
        </div>
    </div>

    <!-- Скрипт для открытия/закрытия модальных окон и переключения между ними -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const showAuthModalBtn = document.getElementById("showAuthModal");
            const authModal = document.getElementById("authModal");
            const registerModal = document.getElementById("registerModal");
            
            const closeAuthBtn = document.getElementById("closeAuth");
            const closeRegisterBtn = document.getElementById("closeRegister");
            
            const switchToRegisterBtn = document.getElementById("switchToRegister");
            const switchToAuthBtn = document.getElementById("switchToAuth");

            // Открытие окна входа
            if (showAuthModalBtn) {
                showAuthModalBtn.addEventListener("click", function(e) {
                    e.preventDefault();
                    authModal.classList.add("active");
                });
            }

            // Закрытие окна входа
            if (closeAuthBtn) {
                closeAuthBtn.addEventListener("click", function() {
                    authModal.classList.remove("active");
                });
            }

            // Закрытие окна регистрации
            if (closeRegisterBtn) {
                closeRegisterBtn.addEventListener("click", function() {
                    registerModal.classList.remove("active");
                });
            }

            // Переключение из Входа в Регистрацию
            if (switchToRegisterBtn) {
                switchToRegisterBtn.addEventListener("click", function(e) {
                    e.preventDefault();
                    authModal.classList.remove("active");
                    registerModal.classList.add("active");
                });
            }

            // Переключение из Регистрации во Вход
            if (switchToAuthBtn) {
                switchToAuthBtn.addEventListener("click", function(e) {
                    e.preventDefault();
                    registerModal.classList.remove("active");
                    authModal.classList.add("active");
                });
            }

            // Закрытие окон при клике на темную область вокруг окна
            window.addEventListener("click", function(e) {
                if (e.target === authModal) {
                    authModal.classList.remove("active");
                }
                if (e.target === registerModal) {
                    registerModal.classList.remove("active");
                }
            });
        });
    </script>
</body>
</html>