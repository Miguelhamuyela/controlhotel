<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Employeee;
use Illuminate\Support\Facades\Log;
app()->setLocale(config('app.locale'));

class AuthController extends Controller
{
    // Exibe o formulário de login
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    // Processa o login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Informe um e-mail.',
            'email.email'       => 'O e-mail informado é inválido.',
            'password.required' => 'Informe a senha.',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect('/dashboard')->with('msg', 'Login realizado com sucesso!');
        }
        return redirect()->back()->withErrors(['email' => 'E-mail ou senha inválidos.'])->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect('login')->with('msg', 'Você saiu do sistema com sucesso!');
    }

    // Exibe o formulário de recuperação de senha
    public function showForgotPasswordForm()
    {
        return view('auth.forgotPassword');
    }

    // Envia o link de redefinição de senha
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Informe um e-mail.',
            'email.email'    => 'O e-mail informado é inválido.',
        ]);

        $email = $request->input('email');

        // Verifica se o e-mail pertence a um Admin
        $admin = Admin::where('email', $email)->first();
        if ($admin) {
            $status = Password::broker('admins')->sendResetLink(['email' => $email]);
            return $status === Password::RESET_LINK_SENT
                ? back()->with('status', 'Link de redefinição enviado com sucesso!')
                : back()->withErrors(['email' => 'Não foi possível enviar o link de redefinição.']);
        }

        // Se não for Admin, verifica se o e-mail pertence a um Employeee
        $employee = Employeee::where('email', $email)->first();
        if ($employee) {
            $status = Password::broker('employees')->sendResetLink(['email' => $email]);
            return $status === Password::RESET_LINK_SENT
                ? back()->with('status', 'Link de redefinição enviado com sucesso!')
                : back()->withErrors(['email' => 'Não foi possível enviar o link de redefinição.']);
        }

        // Se o e-mail não for encontrado em nenhum provider
        return back()->withErrors(['email' => 'E-mail não encontrado no sistema.']);
    }

    // Exibe o formulário de redefinição de senha (o token é passado via URL)
    public function showResetForm($token)
    {
        return view('auth.resetPassword', ['token' => $token]);
    }

    // Processa a redefinição de senha
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'token.required'         => 'Token inválido.',
            'email.required'         => 'Informe um e-mail.',
            'email.email'            => 'O e-mail informado é inválido.',
            'password.required'      => 'Informe a nova senha.',
            'password.min'           => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed'     => 'A confirmação da senha não coincide.',
        ]);

        $email = $request->input('email');
        $broker = null;

        // Verifica se o e-mail pertence a um Admin
        if (Admin::where('email', $email)->exists()) {
            $broker = 'admins';
        }
        // Senão, verifica se o e-mail pertence a um Employeee (funcionário)
        elseif (Employeee::where('email', $email)->exists()) {
            $broker = 'employees';
        }

        if (!$broker) {
            Log::info('E-mail não encontrado para redefinição: ' . $email);
            return back()->withErrors(['email' => 'E-mail não encontrado no sistema.']);
        }

        // Logs da tentativa de redefinir a senha
        Log::info('Tentando redefinir senha para: ' . $email . ' com broker: ' . $broker);
        Log::info('Dados do request: ', $request->only('email', 'password', 'password_confirmation', 'token'));

        $status = Password::broker($broker)->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
                Log::info('Senha redefinida com sucesso para: ' . $user->email);
            }
        );

        Log::info('Status retornado: ' . $status);

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Senha redefinida com sucesso! Faça login com sua nova senha.')
            : back()->withErrors(['email' => 'Não foi possível redefinir a senha. Verifique os dados e tente novamente.']);
    }
}
