<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Employeee;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    // app/Providers/AuthServiceProvider.php

public function boot()
{
    $this->registerPolicies();

    Gate::define('manage-inventory', function ($user) {
        // 1) Se for um Admin “puro” com papel de super-admin:
        if ($user instanceof Admin && $user->role === 'admin') {
            return true;
        }

        // 2) Se for Admin ou Employeee vinculado a um empregado, pega o departamento
        if (method_exists($user, 'employee') && $user->employee) {
            $deptTitle = $user->employee->department->title ?? null;
        } else {
            return false;
        }

        // 3) Checa se é chefe de um dos departamentos autorizados
        return in_array($deptTitle, [
            'Departamento de Gestão de Infra-Estrutura Tecnológica e Serviços Partilhados',
            'Departamento de Administração e Serviços Gerais',
        ], true);
    });
}

}

