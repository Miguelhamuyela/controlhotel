// Aguardar carregamento completo do DOM
document.addEventListener('DOMContentLoaded', function() {
    // Elementos do DOM
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.getElementById('passwordToggle');
    const loginButton = document.querySelector('.login-button');
    const rememberCheckbox = document.getElementById('remember');
    
    // Configurações
    const ANIMATION_DURATION = 300;
    const LOADING_DURATION = 2000;
    
    // Inicialização
    init();
    
    function init() {
        setupEventListeners();
        createDynamicParticles();
        setupFormValidation();
        loadSavedCredentials();
        addKeyboardNavigation();
    }
    
    // Event Listeners
    function setupEventListeners() {
        // Toggle de senha
        if (passwordToggle) {
            passwordToggle.addEventListener('click', togglePasswordVisibility);
        }
        
        // Animações dos inputs
        [emailInput, passwordInput].forEach(input => {
            if (input) {
                input.addEventListener('focus', handleInputFocus);
                input.addEventListener('blur', handleInputBlur);
                input.addEventListener('input', handleInputChange);
            }
        });
        
        // Submissão do formulário
        if (loginForm) {
            loginForm.addEventListener('submit', handleFormSubmit);
        }
        
        // Efeito ripple no botão
        if (loginButton) {
            loginButton.addEventListener('click', createRippleEffect);
        }
        
        // Checkbox remember
        if (rememberCheckbox) {
            rememberCheckbox.addEventListener('change', handleRememberChange);
        }
    }
    
    // Toggle da visibilidade da senha
    function togglePasswordVisibility() {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        
        const eyeOpen = passwordToggle.querySelector('.eye-open');
        const eyeClosed = passwordToggle.querySelector('.eye-closed');
        
        if (isPassword) {
            eyeOpen.style.display = 'none';
            eyeClosed.style.display = 'block';
        } else {
            eyeOpen.style.display = 'block';
            eyeClosed.style.display = 'none';
        }
        
        // Adicionar animação
        passwordToggle.style.transform = 'scale(0.9)';
        setTimeout(() => {
            passwordToggle.style.transform = 'scale(1)';
        }, 150);
    }
    
    // Manipuladores de eventos dos inputs
    function handleInputFocus(e) {
        const container = e.target.closest('.input-group');
        if (container) {
            container.classList.add('focused');
        }
    }
    
    function handleInputBlur(e) {
        const container = e.target.closest('.input-group');
        if (container && !e.target.value) {
            container.classList.remove('focused');
        }
    }
    
    function handleInputChange(e) {
        const container = e.target.closest('.input-group');
        if (container) {
            if (e.target.value) {
                container.classList.add('has-value');
            } else {
                container.classList.remove('has-value');
            }
        }
        
        // Validação em tempo real
        validateInput(e.target);
    }
    
    // Validação de input
    function validateInput(input) {
        const container = input.closest('.input-group');
        if (!container) return;
        
        let isValid = true;
        
        if (input.type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            isValid = emailRegex.test(input.value);
        } else if (input.type === 'password' || input.name === 'password') {
            isValid = input.value.length >= 6;
        }
        
        if (input.value && !isValid) {
            container.classList.add('error');
        } else {
            container.classList.remove('error');
        }
    }
    
    // Submissão do formulário
    function handleFormSubmit(e) {
        // Validar todos os campos
        const inputs = loginForm.querySelectorAll('input[required]');
        let isFormValid = true;
        
        inputs.forEach(input => {
            validateInput(input);
            if (!input.value || input.closest('.input-group').classList.contains('error')) {
                isFormValid = false;
            }
        });
        
        if (!isFormValid) {
            e.preventDefault();
            showFormError('Por favor, preencha todos os campos corretamente.');
            return;
        }
        
        // Mostrar loading
        showLoadingState();
        
        // Salvar credenciais se lembrar estiver marcado
        if (rememberCheckbox && rememberCheckbox.checked) {
            saveCredentials();
        }
    }
    
    // Estado de loading
    function showLoadingState() {
        if (loginButton) {
            loginButton.classList.add('loading');
            loginButton.disabled = true;
        }
    }
    
    // Efeito ripple
    function createRippleEffect(e) {
        const button = e.currentTarget;
        const ripple = button.querySelector('.button-ripple');
        
        if (ripple) {
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('active');
            
            setTimeout(() => {
                ripple.classList.remove('active');
            }, 600);
        }
    }
    
    // Gerenciamento de credenciais
    function saveCredentials() {
        if (emailInput && passwordInput && rememberCheckbox.checked) {
            localStorage.setItem('infosi_remember_email', emailInput.value);
        }
    }
    
    function loadSavedCredentials() {
        const savedEmail = localStorage.getItem('infosi_remember_email');
        if (savedEmail && emailInput) {
            emailInput.value = savedEmail;
            emailInput.dispatchEvent(new Event('input'));
            if (rememberCheckbox) {
                rememberCheckbox.checked = true;
            }
        }
    }
    
    function handleRememberChange(e) {
        if (!e.target.checked) {
            localStorage.removeItem('infosi_remember_email');
        }
    }
    
    // Navegação por teclado
    function addKeyboardNavigation() {
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
                const inputs = Array.from(loginForm.querySelectorAll('input'));
                const currentIndex = inputs.indexOf(e.target);
                
                if (currentIndex < inputs.length - 1) {
                    e.preventDefault();
                    inputs[currentIndex + 1].focus();
                }
            }
        });
    }
    
    // Validação do formulário
    function setupFormValidation() {
        // Verificar se há valores iniciais nos inputs
        [emailInput, passwordInput].forEach(input => {
            if (input && input.value) {
                const container = input.closest('.input-group');
                if (container) {
                    container.classList.add('has-value');
                }
            }
        });
    }
    
    // Mostrar erro do formulário
    function showFormError(message) {
        // Remover alertas existentes
        const existingAlerts = document.querySelectorAll('.alert-error');
        existingAlerts.forEach(alert => alert.remove());
        
        // Criar novo alerta
        const alert = document.createElement('div');
        alert.className = 'alert alert-error';
        alert.innerHTML = `<p>${message}</p>`;
        
        // Inserir antes do formulário
        const formContainer = document.querySelector('.form-container');
        const formHeader = document.querySelector('.form-header');
        if (formContainer && formHeader) {
            formContainer.insertBefore(alert, formHeader.nextSibling);
        }
        
        // Remover após 5 segundos
        setTimeout(() => {
            alert.remove();
        }, 5000);
    }
    
    // Criar partículas dinâmicas
    function createDynamicParticles() {
        const particlesContainer = document.querySelector('.floating-particles');
        if (!particlesContainer) return;
        
        // Criar partículas
        for (let i = 0; i < 20; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.cssText = `
                position: absolute;
                width: ${Math.random() * 4 + 2}px;
                height: ${Math.random() * 4 + 2}px;
                background: rgba(59, 130, 246, ${Math.random() * 0.5 + 0.2});
                border-radius: 50%;
                left: ${Math.random() * 100}%;
                top: ${Math.random() * 100}%;
                animation: float ${Math.random() * 10 + 10}s infinite linear;
            `;
            particlesContainer.appendChild(particle);
        }
    }
    
    // Animações de entrada
    function initEntryAnimations() {
        const elements = document.querySelectorAll('.brand-section, .form-section');
        elements.forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                element.style.transition = 'all 0.6s ease';
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, index * 200);
        });
    }
    
    // Executar animações de entrada
    setTimeout(initEntryAnimations, 100);
});

