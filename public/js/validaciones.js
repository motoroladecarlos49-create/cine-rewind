/**
 * RETRO REWIND - VALIDACIONES DEL LADO DEL CLIENTE
 */

document.addEventListener('DOMContentLoaded', function() {

    // ========================================
    // 1. CARRUSEL AUTOMÁTICO
    // ========================================
    const carrusel = document.querySelector('.carrusel');
    if (carrusel) {
        const slides = carrusel.querySelectorAll('.carrusel-slide');
        const dots = carrusel.querySelectorAll('.dot');
        const prevBtn = carrusel.querySelector('.carrusel-btn.prev');
        const nextBtn = carrusel.querySelector('.carrusel-btn.next');
        let currentIndex = 0;
        let interval;

        function mostrarSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
            });
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });
        }

        function siguienteSlide() {
            currentIndex = (currentIndex + 1) % slides.length;
            mostrarSlide(currentIndex);
        }

        function anteriorSlide() {
            currentIndex = (currentIndex - 1 + slides.length) % slides.length;
            mostrarSlide(currentIndex);
        }

        function iniciarAutoplay() {
            if (interval) clearInterval(interval);
            interval = setInterval(siguienteSlide, 5000);
        }

        function detenerAutoplay() {
            if (interval) {
                clearInterval(interval);
                interval = null;
            }
        }

        // Eventos de botones
        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                detenerAutoplay();
                anteriorSlide();
                iniciarAutoplay();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                detenerAutoplay();
                siguienteSlide();
                iniciarAutoplay();
            });
        }

        // Eventos de indicadores
        dots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                detenerAutoplay();
                currentIndex = index;
                mostrarSlide(currentIndex);
                iniciarAutoplay();
            });
        });

        // Pausar al pasar el mouse
        carrusel.addEventListener('mouseenter', detenerAutoplay);
        carrusel.addEventListener('mouseleave', iniciarAutoplay);

        // Iniciar carrusel
        if (slides.length > 0) {
            mostrarSlide(0);
            iniciarAutoplay();
        }
    }

    // ========================================
    // 2. VALIDACIÓN DE REGISTRO
    // ========================================
    const formRegistro = document.querySelector('.register-container form');
    if (formRegistro) {
        formRegistro.addEventListener('submit', function(e) {
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirm');
            
            if (password && passwordConfirm) {
                if (password.value.length < 6) {
                    e.preventDefault();
                    alert('La contraseña debe tener al menos 6 caracteres.');
                    password.focus();
                    return false;
                }
                
                if (password.value !== passwordConfirm.value) {
                    e.preventDefault();
                    alert('Las contraseñas no coinciden.');
                    passwordConfirm.focus();
                    return false;
                }
            }
        });
    }

    // ========================================
    // 3. VALIDACIÓN DE LOGIN
    // ========================================
    const formLogin = document.querySelector('.login-container form');
    if (formLogin) {
        formLogin.addEventListener('submit', function(e) {
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            
            if (email && email.value.trim() === '') {
                e.preventDefault();
                alert('Por favor ingresa tu email.');
                email.focus();
                return false;
            }
            
            if (password && password.value.trim() === '') {
                e.preventDefault();
                alert('Por favor ingresa tu contraseña.');
                password.focus();
                return false;
            }
        });
    }

    // ========================================
    // 4. VALIDACIÓN DE PERFIL
    // ========================================
    const formPerfil = document.querySelector('.perfil-container form[action*="actualizar_perfil"]');
    if (formPerfil) {
        formPerfil.addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre');
            const email = document.getElementById('email');
            
            if (nombre && nombre.value.trim() === '') {
                e.preventDefault();
                alert('El nombre es obligatorio.');
                nombre.focus();
                return false;
            }
            
            if (email && email.value.trim() === '') {
                e.preventDefault();
                alert('El email es obligatorio.');
                email.focus();
                return false;
            }
            
            // Validación básica de email
            if (email && !email.value.includes('@')) {
                e.preventDefault();
                alert('Por favor ingresa un email válido.');
                email.focus();
                return false;
            }
        });
    }

    // ========================================
    // 5. VALIDACIÓN DE CAMBIO DE CONTRASEÑA
    // ========================================
    const formPassword = document.querySelector('.perfil-container form[action*="cambiar_password"]');
    if (formPassword) {
        formPassword.addEventListener('submit', function(e) {
            const actual = document.getElementById('password_actual');
            const nueva = document.getElementById('password_nueva');
            const confirmar = document.getElementById('password_confirm');
            
            if (actual && actual.value.trim() === '') {
                e.preventDefault();
                alert('Ingresa tu contraseña actual.');
                actual.focus();
                return false;
            }
            
            if (nueva && nueva.value.length < 6) {
                e.preventDefault();
                alert('La nueva contraseña debe tener al menos 6 caracteres.');
                nueva.focus();
                return false;
            }
            
            if (nueva && confirmar && nueva.value !== confirmar.value) {
                e.preventDefault();
                alert('Las contraseñas no coinciden.');
                confirmar.focus();
                return false;
            }
        });
    }

    // ========================================
    // 6. VALIDACIÓN DE RESERVA (ENTRADA)
    // ========================================
    const formEntrada = document.querySelector('.entrada-container form');
    if (formEntrada) {
        formEntrada.addEventListener('submit', function(e) {
            const butacaSeleccionada = document.querySelector('.butaca-btn:active');
            if (!butacaSeleccionada) {
                e.preventDefault();
                alert('Por favor selecciona una butaca haciendo clic en ella.');
                return false;
            }
        });
    }

    // ========================================
    // 7. CONFIRMACIÓN DE ELIMINACIÓN (ADMIN)
    // ========================================
    const deleteLinks = document.querySelectorAll('.btn-delete');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const mensaje = this.getAttribute('data-confirm') || '¿Estás seguro de eliminar este elemento?';
            if (!confirm(mensaje)) {
                e.preventDefault();
                return false;
            }
        });
    });

    // ========================================
    // 8. VALIDACIÓN DE FORMULARIO ADMIN (PELÍCULAS)
    // ========================================
    const formAdminPelicula = document.querySelector('.admin-form form[action*="admin_peliculas"]');
    if (formAdminPelicula) {
        formAdminPelicula.addEventListener('submit', function(e) {
            const titulo = document.getElementById('titulo');
            const descripcion = document.getElementById('descripcion');
            const duracion = document.getElementById('duracion');
            const genero = document.getElementById('genero');
            const anio = document.getElementById('anio_estreno');
            const poster = document.getElementById('posterurl');
            
            if (titulo && titulo.value.trim() === '') {
                e.preventDefault();
                alert('El título es obligatorio.');
                titulo.focus();
                return false;
            }
            
            if (descripcion && descripcion.value.trim() === '') {
                e.preventDefault();
                alert('La descripción es obligatoria.');
                descripcion.focus();
                return false;
            }
            
            if (duracion && (duracion.value <= 0 || duracion.value === '')) {
                e.preventDefault();
                alert('La duración debe ser un número positivo.');
                duracion.focus();
                return false;
            }
            
            if (genero && genero.value.trim() === '') {
                e.preventDefault();
                alert('El género es obligatorio.');
                genero.focus();
                return false;
            }
            
            if (anio && (anio.value < 1900 || anio.value > 2030 || anio.value === '')) {
                e.preventDefault();
                alert('El año debe estar entre 1900 y 2030.');
                anio.focus();
                return false;
            }
            
            if (poster && poster.value.trim() === '') {
                e.preventDefault();
                alert('El nombre del archivo de póster es obligatorio.');
                poster.focus();
                return false;
            }
        });
    }

    // ========================================
    // 9. VALIDACIÓN DE FORMULARIO ADMIN (SALAS)
    // ========================================
    const formAdminSala = document.querySelector('.admin-form form[action*="admin_salas"]');
    if (formAdminSala) {
        formAdminSala.addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre');
            const capacidad = document.getElementById('capacidad');
            
            if (nombre && nombre.value.trim() === '') {
                e.preventDefault();
                alert('El nombre de la sala es obligatorio.');
                nombre.focus();
                return false;
            }
            
            if (capacidad && (capacidad.value <= 0 || capacidad.value === '')) {
                e.preventDefault();
                alert('La capacidad debe ser un número positivo.');
                capacidad.focus();
                return false;
            }
        });
    }

    // ========================================
    // 10. VALIDACIÓN DE FORMULARIO ADMIN (FUNCIONES)
    // ========================================
    const formAdminFuncion = document.querySelector('.admin-form form[action*="admin_funciones"]');
    if (formAdminFuncion) {
        formAdminFuncion.addEventListener('submit', function(e) {
            const pelicula = document.getElementById('id_pelicula');
            const sala = document.getElementById('id_sala');
            const fecha = document.getElementById('fecha');
            const horario = document.getElementById('horario');
            const precio = document.getElementById('precio');
            
            if (pelicula && pelicula.value === '') {
                e.preventDefault();
                alert('Selecciona una película.');
                pelicula.focus();
                return false;
            }
            
            if (sala && sala.value === '') {
                e.preventDefault();
                alert('Selecciona una sala.');
                sala.focus();
                return false;
            }
            
            if (fecha && fecha.value === '') {
                e.preventDefault();
                alert('Selecciona una fecha.');
                fecha.focus();
                return false;
            }
            
            if (horario && horario.value === '') {
                e.preventDefault();
                alert('Selecciona un horario.');
                horario.focus();
                return false;
            }
            
            if (precio && (precio.value <= 0 || precio.value === '')) {
                e.preventDefault();
                alert('El precio debe ser un número positivo.');
                precio.focus();
                return false;
            }
        });
    }

    console.log('🎬 RETRO REWIND - Validaciones cargadas correctamente.');
});