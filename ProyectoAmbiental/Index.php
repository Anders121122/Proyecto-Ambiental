<!-- filepath: c:\xampp1\htdocs\ProyectoAmbiental\HTML\Index.php -->
<?php
session_start();
include 'FUENTE/CONTROLADORES/conexion.php';

// Obtener proyectos de la base de datos
$query = "SELECT * FROM proyectos LIMIT 3";
$stmt = $pdo->query($query);
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Función para renderizar errores
function renderErrores($errores) {
    if (!empty($errores)) {
        echo '<ul class="errores">';
        foreach ($errores as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voluntariado Ambiental</title>
    <link rel="stylesheet" href="SERVICIOS/CSS/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Encabezado principal -->
    <header>
        <div class="contenedor">
            <h1>Únete a la Causa Ambiental</h1>
            <p>Juntos podemos hacer la diferencia. Únete a nuestra comunidad de voluntarios ambientales.</p>
            <div class="cta-buttons">
                <a href="#" class="btn-cta" id="registroBton">
                    <i class="fas fa-user-plus"></i> Regístrate
                </a>
                <a href="#" class="btn-cta" id="loginBtn">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </a>
            </div>
        </div>
    </header>

    <!-- Sección de proyectos destacados -->
    <section class="proyectos-destacados">
        <div class="contenedor">
            <h2><i class="fas fa-leaf"></i> Proyectos Destacados</h2>
            <div class="proyectos">
                <?php foreach ($proyectos as $proyecto): ?>
                    <div class="proyecto">
                        <i class="fas fa-tree proyecto-icon"></i>
                        <h3><?= htmlspecialchars($proyecto['nombre']); ?></h3>
                        <p><?= htmlspecialchars($proyecto['descripcion']); ?></p>
                        <a href="projects.php?id=<?= htmlspecialchars($proyecto['id_proyecto']); ?>" class="btn-cta">
                            <i class="fas fa-arrow-right"></i> Ver Proyecto
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>


<!-- Formulario de registro deslizante -->
<div class="form-container" id="formContainer">
        <h2><i class="fas fa-user-plus"></i> Registro</h2>
    <?php if (isset($_SESSION['error_registro'])): ?>
        <div class="error-message">
            <?php 
                echo htmlspecialchars($_SESSION['error_registro']); 
            ?>
        </div>
    <?php endif; ?>
    <form action="FUENTE/CONTROLADORES/registroUsuario.php" method="POST">
        <input type="text" name="nombre" placeholder="Nombres" value="<?= htmlspecialchars($_SESSION['registro_datos']['nombre'] ?? '') ?>" required>
        <input type="text" name="apellido" placeholder="Apellidos" value="<?= htmlspecialchars($_SESSION['registro_datos']['apellido'] ?? '') ?>" required>
        <input type="email" name="email" placeholder="Correo Electrónico" value="<?= htmlspecialchars($_SESSION['registro_datos']['email'] ?? '') ?>" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <input type="password" name="password_confirmar" placeholder="Confirmar Contraseña" required>
        <select name="tipo_usuario" required>
            <option value="" disabled <?= empty($_SESSION['registro_datos']['tipo_usuario']) ? 'selected' : '' ?>>Selecciona tu rol</option>
            <option value="voluntario" <?= (($_SESSION['registro_datos']['tipo_usuario'] ?? '') === 'voluntario') ? 'selected' : '' ?>>Voluntario</option>
            <option value="organizador" <?= (($_SESSION['registro_datos']['tipo_usuario'] ?? '') === 'organizador') ? 'selected' : '' ?>>Organizador</option>
        </select>
        <button type="submit">Registrar</button>
    </form>
</div>

<!-- Formulario de inicio de sesión deslizante -->
<div class="form-container" id="loginFormContainer">

        <h2> <i class="fas fa-sign-in-alt"> </i> Iniciar Sesión</h2>
            <?php 
                if (isset($_SESSION['success_login'])): 
            ?>

            <div class="success-message">
                <?php 
                    echo htmlspecialchars($_SESSION['success_login']); 
                    unset($_SESSION['success_login']); // Eliminar el mensaje después de mostrarlo
                ?>
            </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_login'])): ?>
        <div class="error-message">
            <?php 
                echo htmlspecialchars($_SESSION['error_login']); 
                unset($_SESSION['error_login']); // Eliminar el mensaje después de mostrarlo
            ?>
        </div>
    <?php endif; ?>
    <form action="FUENTE/CONTROLADORES/verificarUsuario.php" method="POST">
        <input type="email" name="email" placeholder="Correo Electrónico" value="<?= htmlspecialchars($_SESSION['login_email'] ?? '') ?>" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Iniciar Sesión</button>
    </form>
</div>
    

    <!-- Sección de beneficios -->
    <section class="beneficios">
        <div class="contenedor">
            <h2>Beneficios de Participar</h2>
            <ul>
                <li>Hacer una diferencia real en el medio ambiente.</li>
                <li>Obtener recompensas y puntos por tu participación.</li>
                <li>Ser parte de una comunidad comprometida con el futuro del planeta.</li>
            </ul>
        </div>
    </section>

    <!-- Sección de testimonios -->
    <section class="testimonios">
        <div class="contenedor">
            <h2>Testimonios de Voluntarios</h2>
            <div class="testimonio">
                <p>"Participar en estos proyectos me ha cambiado la vida. Es muy gratificante saber que estamos haciendo algo por nuestro planeta."</p>
                <p>- Ana, Voluntaria</p>
            </div>
            <div class="testimonio">
                <p>"Nunca imaginé que podía marcar la diferencia hasta que me uní a la plataforma. ¡Recomiendo a todos ser parte!"</p>
                <p>- Carlos, Voluntario</p>
            </div>
        </div>
    </section>

    <!-- Pie de página -->
    <footer>
        <div class="contenedor">
            <p>&copy; 2025 Plataforma de Voluntariado Ambiental. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
document.addEventListener('DOMContentLoaded', () => {
    const registroBtn = document.getElementById('registroBton');
    const loginBtn = document.getElementById('loginBtn');
    const registroForm = document.getElementById('formContainer');
    const loginForm = document.getElementById('loginFormContainer');

    // Función para cerrar ambos formularios
    const closeAllForms = () => {
        registroForm.classList.remove('show');
        loginForm.classList.remove('show');
    };

    // Evitar que los clics dentro del formulario lo cierren
    const stopPropagation = (event) => {
        event.stopPropagation();
    };

    // Agregar evento para detener la propagación en los formularios
    registroForm.querySelector('form').addEventListener('click', stopPropagation);
    loginForm.querySelector('form').addEventListener('click', stopPropagation);
    registroForm.querySelector('h2').addEventListener('click', stopPropagation);
    loginForm.querySelector('h2').addEventListener('click', stopPropagation);

    // Abrir el formulario de registro
    registroBtn.addEventListener('click', (event) => {
        event.preventDefault();
        closeAllForms();
        registroForm.classList.add('show');
    });

    // Abrir el formulario de inicio de sesión
    loginBtn.addEventListener('click', (event) => {
        event.preventDefault();
        closeAllForms();
        loginForm.classList.add('show');
    });

    // Cerrar al hacer clic fuera del formulario
    registroForm.addEventListener('click', (event) => {
        if (event.target === registroForm) {
            registroForm.classList.remove('show');
        }
    });

    loginForm.addEventListener('click', (event) => {
        if (event.target === loginForm) {
            loginForm.classList.remove('show');
        }
    });

    // Abrir automáticamente los formularios si hay mensajes
    if (registroForm.querySelector('.error-message')) {
        registroForm.classList.add('show');
    }

    if (loginForm.querySelector('.error-message')) {
        loginForm.classList.add('show');
    }

    if (loginForm.querySelector('.success-message')) {
        loginForm.classList.add('show');
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // ...código existente...

    // Añadir efecto de aparición suave a los proyectos
    const proyectos = document.querySelectorAll('.proyecto');
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px'
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    proyectos.forEach(proyecto => {
        proyecto.style.opacity = '0';
        proyecto.style.transform = 'translateY(20px)';
        proyecto.style.transition = 'all 0.5s ease-out';
        observer.observe(proyecto);
    });

    // Añadir efecto de hover a los botones
    const buttons = document.querySelectorAll('.btn-cta');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', (e) => {
            button.style.transform = 'translateY(-3px)';
        });
        button.addEventListener('mouseleave', (e) => {
            button.style.transform = 'translateY(0)';
        });
    });
});
</script>

<?php
// Limpiar mensajes de error y datos temporales después de mostrarlos
unset($_SESSION['error_registro']);
unset($_SESSION['registro_datos']);
unset($_SESSION['error_login']);
unset($_SESSION['login_email']);
?>
</body>
</html>