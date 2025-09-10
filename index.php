<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiborti Analytics - Próximamente</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            height: 100vh;
            overflow: hidden;
            background: #000;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, 
                rgba(255, 107, 107, 0.3), 
                rgba(238, 90, 36, 0.2), 
                rgba(95, 39, 205, 0.3), 
                rgba(52, 31, 151, 0.4), 
                rgba(10, 189, 227, 0.2), 
                rgba(0, 107, 166, 0.3)
            );
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            z-index: 0;
        }

        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255, 107, 107, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(95, 39, 205, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(10, 189, 227, 0.1) 0%, transparent 50%);
            animation: pulseGlow 8s ease-in-out infinite alternate;
            z-index: 0;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            25% { background-position: 100% 50%; }
            50% { background-position: 100% 100%; }
            75% { background-position: 50% 100%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes pulseGlow {
            0% { opacity: 0.3; }
            100% { opacity: 0.8; }
        }

        .main-container {
            position: relative;
            z-index: 10;
            height: 100vh;
        }

        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        .logo-container {
            animation: fadeInUp 1.5s ease-out;
        }

        .logo {
            font-size: 5rem;
            font-weight: 300;
            color: white;
            letter-spacing: -2px;
            margin-bottom: 0.5rem;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .tagline {
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            letter-spacing: 8px;
            text-align: center;
            font-weight: 300;
        }

        .coming-soon {
            font-size: 3.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 2rem;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
            background: linear-gradient(45deg, #fff, #e3f2fd);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: pulse 2s ease-in-out infinite alternate, fadeInUp 1.5s ease-out 0.3s both;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            100% { transform: scale(1.05); }
        }

        .description {
            font-size: 1.4rem;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 3rem;
            line-height: 1.6;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 1.5s ease-out 0.3s both;
        }

        .countdown-container {
            margin-bottom: 3rem;
            animation: fadeInUp 1.5s ease-out 0.6s both;
        }

        .countdown-item {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            padding: 1.5rem;
            min-width: 80px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin: 0.5rem;
        }

        .countdown-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.7);
            background: rgba(255, 255, 255, 0.12);
        }

        .countdown-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            display: block;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .countdown-label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 0.5rem;
        }

        .notify-form {
            animation: fadeInUp 1.5s ease-out 0.9s both;
            max-width: 400px;
        }

        .email-input {
            padding: 1rem 1.5rem;
            border: none;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(15px);
            color: white;
            font-size: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.15);
            transition: all 0.3s ease;
            width: 100%;
        }

        .email-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .email-input:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.12);
            box-shadow: none;
        }

        .notify-btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(238, 90, 36, 0.4);
            width: 100%;
            margin-top: 1rem;
        }

        .notify-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(238, 90, 36, 0.6);
        }

        .footer-info {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            animation: fadeInUp 1.5s ease-out 1.2s both;
            margin-top: 2rem;
        }

        .floating-particles {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            animation: float 20s infinite linear;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        }

        @keyframes float {
            0% {
                opacity: 0;
                transform: translateY(100vh) rotate(0deg);
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                opacity: 0;
                transform: translateY(-100vh) rotate(360deg);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .logo {
                font-size: 3.5rem;
            }
            
            .tagline {
                font-size: 1rem;
                letter-spacing: 4px;
            }

            .coming-soon {
                font-size: 2.5rem;
            }

            .description {
                font-size: 1.1rem;
            }

            .countdown-number {
                font-size: 2rem;
            }

            .countdown-item {
                min-width: 60px;
                padding: 1rem;
                margin: 0.25rem;
            }

            .footer-info {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .notify-form {
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="background-overlay"></div>
    <div class="floating-particles" id="particles"></div>
    
    <div class="main-container">
        <div class="container-fluid h-100">
            <div class="row h-100 justify-content-center align-items-center">
                <div class="col-12">
                    
                    <!-- Logo Section -->
                    <div class="text-center mb-5 logo-container">
                        <div class="logo">Fiborti</div>
                        <div class="tagline">analytics</div>
                    </div>

                    <!-- Main Content -->
                    <div class="text-center">
                        <h1 class="coming-soon">Próximamente</h1>
                        <p class="description">
                            Estamos preparando algo extraordinario. Una nueva forma de entender los datos, 
                            analizar tendencias y transformar información en decisiones inteligentes.
                        </p>


                    </div>

                    <!-- Footer -->
                    <div class="footer-info">
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        // Crear partículas flotantes
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            for (let i = 0; i < 20; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.width = Math.random() * 4 + 2 + 'px';
                particle.style.height = particle.style.width;
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (Math.random() * 10 + 15) + 's';
                particlesContainer.appendChild(particle);
            }
        }

        // Countdown timer (configurar para 30 días desde hoy)
        function updateCountdown() {
            const now = new Date().getTime();
            const launchDate = new Date();
            launchDate.setDate(launchDate.getDate() + 30); // 30 días desde hoy
            const distance = launchDate.getTime() - now;

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('days').textContent = days.toString().padStart(2, '0');
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');

            if (distance < 0) {
                document.getElementById('countdown').innerHTML = '<div class="col-12"><h2 class="text-white">¡Ya estamos aquí!</h2></div>';
            }
        }

        // Manejar formulario de notificación
        document.getElementById('notifyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('.email-input').value;
            
            // Simular envío exitoso
            const button = this.querySelector('.notify-btn');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="bi bi-check-circle me-2"></i>¡Listo!';
            button.style.background = 'linear-gradient(45deg, #27ae60, #2ecc71)';
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.background = 'linear-gradient(45deg, #ff6b6b, #ee5a24)';
                this.querySelector('.email-input').value = '';
            }, 2000);
        });

        // Inicializar
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            updateCountdown();
            setInterval(updateCountdown, 1000);
        });

        // Efecto de paralaje sutil con el mouse
        document.addEventListener('mousemove', (e) => {
            const particles = document.querySelectorAll('.particle');
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;
            
            particles.forEach((particle, index) => {
                const speed = (index % 3 + 1) * 0.5;
                const x = (mouseX - 0.5) * speed;
                const y = (mouseY - 0.5) * speed;
                particle.style.transform += ` translate(${x}px, ${y}px)`;
            });
        });
    </script>
</body>
</html>