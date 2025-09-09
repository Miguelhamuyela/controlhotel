@extends("layouts.admin.auth")

@section("title", "Redefinir Senha")

@section("content")
<!-- Container principal -->
<div class="login-container">
    <!-- Logo e branding -->
    <div class="brand-section">
        <div class="logo-container">
            <div class="logo-icon">
                <img src="{{ asset("auth/img/infosi0.png") }}" alt="INFOSI Logo" style="width: 80px; height: 80px; object-fit: contain;">
            </div>
            <h1 class="brand-title">INFOSI</h1>
            <p class="brand-subtitle">Instituto Nacional de Fomento da Sociedade da Informação</p>
        </div>
    </div>

    <!-- Formulário de redefinição de senha -->
    <div class="form-section">
        <div class="form-container">
            <div class="form-header">
                <h2>Redefinir Senha</h2>
                <p>Insira sua nova palavra-passe</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    @foreach ($errors->all() as $error)
                       <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route("resetPasswordUpdate") }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="input-group">
                    <div class="input-container">
                        <input type="email" name="email" id="email" value="{{ old("email") }}" required>
                        <label for="email">E-mail</label>
                        <div class="input-highlight"></div>
                    </div>
                    <div class="input-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <polyline points="22,6 12,13 2,6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-container">
                        <input type="password" name="password" id="password" required>
                        <label for="password">Nova Senha</label>
                        <div class="input-highlight"></div>
                    </div>
                    <div class="input-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="16" r="1" fill="currentColor"/>
                            <path d="M7 11V7A5 5 0 0 1 17 7V11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-container">
                        <input type="password" name="password_confirmation" id="password_confirmation" required>
                        <label for="password_confirmation">Confirmar Nova Senha</label>
                        <div class="input-highlight"></div>
                    </div>
                    <div class="input-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="16" r="1" fill="currentColor"/>
                            <path d="M7 11V7A5 5 0 0 1 17 7V11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                <button type="submit" class="login-button">
                    <span class="button-text">Redefinir Senha</span>
                    <div class="button-loader">
                        <div class="loader-spinner"></div>
                    </div>
                    <div class="button-ripple"></div>
                </button>
            </form>

            <div class="form-footer">
                <p>© 2025 INFOSI - Todos os direitos reservados</p>
                <div class="tech-badge">
                    <span>Política de Privacidade </span>
                    <div class="badge-pulse"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos para as mensagens de alerta (repetidos aqui para garantir que funcionem, mas idealmente estariam em um CSS global ) */
.alert {
    padding: 12px 16px;
    margin-bottom: 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
}

.alert-info {
    background-color: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.3);
    color: var(--secondary-blue);
}

.alert-success {
    background-color: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: var(--success-green);
}

.alert-error {
    background-color: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: var(--error-red);
}

.alert p {
    margin: 0;
}

.alert p:not(:last-child) {
    margin-bottom: 8px;
}
</style>
@endsection
