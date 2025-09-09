<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeeController;
use App\Http\Controllers\EmployeeTypeController;
use App\Http\Controllers\InternController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\MobilityController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\VacationRequestController;
use App\Http\Controllers\SecondmentController;
use App\Http\Controllers\DepartmentHeadController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SalaryPaymentController;
use App\Http\Controllers\InternEvaluationController;
use App\Http\Controllers\RetirementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\NewChatController;
use App\Http\Controllers\StatuteController;
use App\Http\Controllers\ExtraJobController;
use App\Http\Controllers\EmployeeHistoryController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialTransactionController;
use App\Http\Controllers\MaterialTypeController;
use App\Http\Controllers\EmployeeEvaluationController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\LicenseCategoryController;
use App\Http\Controllers\EmployeeCategoryController;
use App\Http\Controllers\CourseController;



/*
|--------------------------------------------------------------------------
| Rotas Login/Logout e Recuperação de Senha - Laravel Sanctum
|--------------------------------------------------------------------------
*/
Route::get("login", [AuthController::class, "showLoginForm"])->name("login");
Route::post("login", [AuthController::class, "login"])->name("login.post");
Route::post("logout", [AuthController::class, "logout"])->name("logout");

Route::get("forgotPassword", [AuthController::class, "showForgotPasswordForm"])->name("forgotPassword");
Route::post("forgotPassword", [AuthController::class, "sendResetLink"])->name("forgotPasswordEmail");
Route::get("resetPassword/{token}", [AuthController::class, "showResetForm"])->name("resetPassword");
Route::post("resetPassword", [AuthController::class, "resetPassword"])->name("resetPasswordUpdate");

// Rotas adicionais para compatibilidade (opcional)
Route::get("password/reset/{token}", [AuthController::class, "showResetForm"])->name("password.reset");
Route::post("password/reset", [AuthController::class, "resetPassword"])->name("password.update");


/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/

Route::get("/", [FrontendController::class, "index"])->name("frontend.index");
Route::get("/sobre", [FrontendController::class, "about"])->name("frontend.about");          
Route::get("/estatuto", [FrontendController::class, "statute"])->name("frontend.statute");    
Route::get("/diretoria", [FrontendController::class, "directors"])->name("frontend.directors");
Route::get("/diretoria/{id}", [FrontendController::class, "showDirector"])->where("id", "[0-9]+")->name("frontend.directors.show"); // Página da Diretoria
Route::get("/contato", [FrontendController::class, "contact"])->name("frontend.contact");


/*
|--------------------------------------------------------------------------
| Rotas Protegidas pelo middleware "auth"
|--------------------------------------------------------------------------
*/

Route::middleware(["auth"])->group(function() {

    // Rota para o Dashboard (rota renomeada para /dashboard)
    Route::get("/dashboard", [DashboardController::class, "index"])->name("dashboard");

      


    // Rota GET com parâmetro ?status=...
    Route::get("employeee/filter-by-status", [EmployeeeController::class, "filterByStatus"])->name("employeee.filterByStatus");

/*
|--------------------------------------------------------------------------
| Módulo de Materiais / Estoque
|--------------------------------------------------------------------------
| Só para quem passa no Gate "manage-inventory".
*/

Route::middleware(["auth","can:manage-inventory"])->group(function () {
     // Tipos de Material
     Route::resource("material-types", MaterialTypeController::class);
     Route::get("material-types/{material_type}/delete", [MaterialTypeController::class, "destroy"])->name("material-types.delete");
 
     // Materiais (CRUD completo: index, create, store, show, edit, update, destroy)
     Route::resource("materials", MaterialController::class)
          ->only(["index","create","store","show","edit","update","destroy"]);
 
     // Transações e relatórios
     Route::prefix("materials")->name("materials.")->group(function () {
         Route::get("{category}/transactions",  [MaterialTransactionController::class,"index"])
              ->name("transactions.index");
         Route::get("{category}/in",           [MaterialTransactionController::class,"createIn"])
              ->name("transactions.in");
         Route::post("{category}/in",          [MaterialTransactionController::class,"storeIn"])
              ->name("transactions.in.store");
         Route::get("{category}/out",          [MaterialTransactionController::class,"createOut"])
              ->name("transactions.out");
         Route::post("{category}/out",         [MaterialTransactionController::class,"storeOut"])
              ->name("transactions.out.store");
 
         Route::get("{category}/report-in",    [MaterialTransactionController::class,"reportIn"])
              ->name("transactions.report-in");
         Route::get("{category}/report-out",   [MaterialTransactionController::class,"reportOut"])
              ->name("transactions.report-out");
         Route::get("{category}/report-all",   [MaterialTransactionController::class,"reportAll"])
              ->name("transactions.report-all");
     });
 });


/*
|--------------------------------------------------------------------------
| Transações e relatórios “globais” para ADMIN (sem {category})
|--------------------------------------------------------------------------
*/
Route::middleware(["auth","can:manage-inventory"])
     ->prefix("admin/materials")
     ->name("admin.materials.")
     ->group(function() {

    // **ÍNDICE** para admin (listar histórico de todas as categorias)
    Route::get("/", [MaterialTransactionController::class,"index"])
         ->name("transactions.index");

     //Criação de novo material (sem categoria)
      Route::get("create", [MaterialController::class,"create"])->name("materials.create");

    // Formulário de Entrada
    Route::get("in",        [MaterialTransactionController::class,"createIn"])
         ->name("transactions.in");
    // Submit de Entrada
    Route::post("in",       [MaterialTransactionController::class,"storeIn"])
         ->name("transactions.in.store");

    // Formulário de Saída
    Route::get("out",       [MaterialTransactionController::class,"createOut"])
         ->name("transactions.out");
    // Submit de Saída
    Route::post("out",      [MaterialTransactionController::class,"storeOut"])
         ->name("transactions.out.store");

    // Relatórios gerais
    Route::get("report-in", [MaterialTransactionController::class,"reportIn"])
         ->name("transactions.report-in");
    Route::get("report-out",[MaterialTransactionController::class,"reportOut"])
         ->name("transactions.report-out");
    Route::get("report-all",[MaterialTransactionController::class,"reportAll"])
         ->name("transactions.report-all");
});


    // ====================== Filtros por datas (Funcionários / Estagiários) ======================
    // Funcionários
    Route::get("employeee/filter", [EmployeeeController::class, "filterByDate"])->name("employeee.filter");
    Route::get("employeee/filter/pdf", [EmployeeeController::class, "pdfFiltered"])->name("employeee.filter.pdf");
    // Estagiários
    Route::get("intern/filter", [InternController::class, "filterByDate"])->name("intern.filter");
    Route::get("intern/filter/pdf", [InternController::class, "pdfFiltered"])->name("intern.filter.pdf");


    // ====================== Rotas Para o Tipo de Funcionário (EmployeeType) ======================
    Route::resource("employeeType", EmployeeTypeController::class);
    Route::get("employeeType/{id}/delete", [EmployeeTypeController::class, "destroy"]);


    // ====================== Rotas Para Categoria de Funcionário (EmployeeCategory) ======================
    Route::resource("employeeCategory", EmployeeCategoryController::class);
    Route::get("employeeCategory/{employeeCategory}/delete", [EmployeeCategoryController::class, "destroy"])->name("employeeCategory.delete");


    // ====================== Rotas Para Cursos (Course) ======================
    Route::resource("course", CourseController::class);
    Route::get("course/{course}/delete", [CourseController::class, "destroy"])->name("course.delete");


    // ====================== Funcionários (Employeee) ======================
     Route::get("employeee/{id}/pdf", [EmployeeeController::class, "showPdf"])->name("employeee.showPdf");
     Route::get("employeee/pdf", [EmployeeeController::class, "pdfAll"])->name("employeee.pdfAll");
     Route::resource("employeee", EmployeeeController::class);
     Route::get("employeee/{id}/delete", [EmployeeeController::class, "destroy"]);

    // Área da Avaliação dos Funcionários
    // routes/web.php

// Antes das rotas de resource:

Route::get("employeeEvaluations/searchEmployee", 
    [EmployeeEvaluationController::class, "searchEmployee"])->name("employeeEvaluations.searchEmployee");

Route::get("employeeEvaluations/pdf-all", 
           [EmployeeEvaluationController::class, "pdfAll"])->name("employeeEvaluations.pdfAll");

Route::get("employeeEvaluations/{employeeEvaluation}/pdf", 
           [EmployeeEvaluationController::class, "pdf"])->name("employeeEvaluations.pdf");

// A seguir, o resource:
Route::resource("employeeEvaluations", EmployeeEvaluationController::class)
     ->names([
         "index"   => "employeeEvaluations.index",
         "create"  => "employeeEvaluations.create",
         "store"   => "employeeEvaluations.store",
         "show"    => "employeeEvaluations.show",
         "edit"    => "employeeEvaluations.edit",
         "update"  => "employeeEvaluations.update",
         "destroy" => "employeeEvaluations.destroy",
     ]);



    // ====================== Perfil do Funcionário ======================
    Route::get("my-profile", [EmployeeeController::class, "myProfile"])->name("profile");


    // ====================== Departamentos ======================
    Route::get("depart/{departmentId}/pdf", [DepartmentController::class, "employeeePdf"])->name("depart.employeee.pdf");
    Route::get("depart/employeee", [DepartmentController::class, "employeee"])->name("depart.employeee");
    Route::resource("depart", DepartmentController::class);
    Route::get("depart/{id}/delete", [DepartmentController::class, "destroy"]);


    // ====================== Cargos (Positions) ======================
    Route::get("positions/employeee", [PositionController::class, "employeee"])->name("positions.employeee.filter");
    Route::get("positions/{positionId}/pdf", [PositionController::class, "pdf"])->name("positions.employeee.pdf");
    Route::resource("positions", PositionController::class);
    Route::get("positions/{id}/delete", [PositionController::class, "destroy"]);


    // ====================== Especialidades (Specialties) ======================
    Route::get("specialties/employeee", [SpecialtyController::class, "employeee"])->name("specialties.employeee.filter");
    Route::get("specialties/{specialtyId}/pdf", [SpecialtyController::class, "pdf"])->name("specialties.pdf");
    Route::resource("specialties", SpecialtyController::class);
    Route::get("specialties/{id}/delete", [SpecialtyController::class, "destroy"]);


    // ====================== Estagiários (Intern) ======================
    Route::get("intern/pdf", [InternController::class, "pdfAll"])->name("intern.pdfAll");
    Route::resource("intern", InternController::class);
    Route::get("intern/{id}/delete", [InternController::class, "destroy"]);


    // ====================== Pagamento de Salário (Salary Payment) ======================
    Route::get("salaryPayment/pdf-period", [SalaryPaymentController::class,"pdfPeriod"])->name("salaryPayment.pdfPeriod");
    Route::get("salaryPayment/pdf-employee/{employeeId}", [SalaryPaymentController::class,"pdfByEmployee"])->name("salaryPayment.pdfByEmployee");


    Route::get("salaryPayment/searchEmployee", [SalaryPaymentController::class, "searchEmployee"])->name("salaryPayment.searchEmployee"); 
    Route::get("salaryPayment/pdf", [SalaryPaymentController::class, "pdfAll"])->name("salaryPayment.pdfAll");
    Route::get("salaryPayment/calculateDiscount", [SalaryPaymentController::class, "calculateDiscount"])->name("salaryPayment.calculateDiscount");
    Route::resource("salaryPayment", SalaryPaymentController::class);


    // ====================== Avaliação dos Estagiários (Intern Evaluation) ======================
    Route::get("internEvaluation/searchIntern", [InternEvaluationController::class, "searchIntern"])->name("internEvaluation.searchIntern");
    Route::get("internEvaluation/pdf/{id}", [InternEvaluationController::class, "pdf"])->name("internEvaluation.pdf");
    Route::get("internEvaluation/pdf", [InternEvaluationController::class, "pdfAll"])->name("internEvaluation.pdfAll");
    Route::resource("internEvaluation", InternEvaluationController::class);


    // ====================== Mobilidade (Mobility) ======================
    Route::get("mobility/pdf", [MobilityController::class, "pdfAll"])->name("mobility.pdfAll");
    Route::get("mobility/search-employee", [MobilityController::class, "searchEmployee"])->name("mobility.searchEmployee");
    Route::resource("mobility", MobilityController::class);


    // ====================== Tipos de Licença (LeaveType) ======================
    Route::resource("leaveType", LeaveTypeController::class);
    Route::get("leaveType/{id}/delete", [LeaveTypeController::class, "destroy"]);


    // ====================== Pedido de Licença (LeaveRequest) ======================
    Route::get("leaveRequest/searchEmployee", [LeaveRequestController::class, "searchEmployee"])->name("leaveRequest.searchEmployee");
    Route::get("leave-request/pdf-filtered", [LeaveRequestController::class, "pdfAll"])->name("leaveRequest.exportFilteredPDF");
    Route::get("leaveRequest/pdf", [LeaveRequestController::class, "pdfAll"])->name("leaveRequest.pdfAll");
    Route::resource("leaveRequest", LeaveRequestController::class);
    Route::get("leaveRequest/{id}/delete", [LeaveRequestController::class, "destroy"]);


    // ====================== Pedido de Férias (Vacation Request) ======================
    Route::get("vacationRequest/departmentSummary", [VacationRequestController::class, "departmentSummary"])->name("vacationRequest.departmentSummary");
    Route::get("vacationRequest/searchEmployee", [VacationRequestController::class, "searchEmployee"])->name("vacationRequest.searchEmployee");
    Route::get("vacation-request/pdf-filtered",  [VacationRequestController::class, "pdfAll"])->name("vacationRequest.exportFilteredPDF");
    Route::get("vacationRequest/pdf", [VacationRequestController::class, "pdfAll"])->name("vacationRequest.pdfAll");
    Route::resource("vacationRequest", VacationRequestController::class);
    Route::get("vacationRequest/{id}/delete", [LeaveRequestController::class, "destroy"]);


    // ====================== Destacamento (Secondment) ======================
    Route::get("secondment/searchEmployee", [SecondmentController::class, "searchEmployee"])->name("secondment.searchEmployee");
    Route::get("secondment/pdf", [SecondmentController::class, "pdfAll"])->name("secondment.pdfAll");
    Route::resource("secondment", SecondmentController::class);


    // ====================== Trabalhos extras(ExtraJobs) ======================
    Route::get("extras/pdf", [ExtraJobController::class, "pdfAll"])->name("extras.pdfAll");
    Route::get("extras/{id}/pdf", [ExtraJobController::class, "pdfShow"])->whereNumber("id")->name("extras.pdfShow");
    Route::get("extras/search-employee", [ExtraJobController::class, "searchEmployee"])->name("extras.searchEmployee");
    Route::resource("extras", ExtraJobController::class)->where(["extras" => "[0-9]+"]);
    Route::get("extras/{id}/delete", [ExtraJobController::class, "destroy"]);


    // ====================== Reforma (Retirement) ======================
    Route::get("retirements/searchEmployee", [RetirementController::class, "searchEmployee"])->name("retirements.searchEmployee"); 
    Route::get("retirements/pdf-filtered", [RetirementController::class, "pdfAll"])->name("retirements.exportFilteredPDF");
    Route::get("retirements/pdf", [RetirementController::class, "pdfAll"])->name("retirements.pdf");
    Route::resource("retirements", RetirementController::class);


    // ====================== Mapa de Efetividade (Attendance) ======================
    Route::get("attendance/pdf", [AttendanceController::class, "pdfAll"])->name("attendance.pdfAll");
    Route::get("attendance/dashboard", [AttendanceController::class, "dashboard"])->name("attendance.dashboard");
    Route::get("attendance/check-status", [AttendanceController::class, "checkStatus"])->name("attendance.checkStatus");
    Route::get("attendance/createBatch", [AttendanceController::class, "createBatch"])->name("attendance.createBatch");
    Route::post("attendance/storeBatch", [AttendanceController::class, "storeBatch"])->name("attendance.storeBatch");
    Route::resource("attendance", AttendanceController::class)->except(["show"]);


     // ====================== Transportes (Drivers) ======================
     Route::get("drivers/pdf", [DriverController::class, "pdfAll"])->name("drivers.pdfAll");
     Route::get("drivers/pdf-filtered", [DriverController::class, "exportFilteredPDF"])->name("drivers.pdfFiltered");
     Route::resource("drivers", DriverController::class);
     Route::get("drivers/{driver}/delete", [DriverController::class,"destroy"])->name("drivers.delete");

     // Viaturas – PDF total e filtrado
     Route::get("vehicles/pdf", [VehicleController::class, "pdfAll"])
          ->name("vehicles.pdfAll");
     Route::get("vehicles/pdf-filtered", [VehicleController::class, "exportFilteredPDF"])
          ->name("vehicles.pdfFiltered");
     Route::resource("vehicles", VehicleController::class);
     Route::get("vehicles/{vehicle}/delete", [VehicleController::class,"destroy"])
          ->name("vehicles.delete");

     // Manutenções – PDF total e filtrado
     Route::get("maintenance/pdf", [MaintenanceController::class, "pdfAll"])
          ->name("maintenance.pdfAll");
     Route::get("maintenance/pdf-filtered", [MaintenanceController::class, "exportFilteredPDF"])
          ->name("maintenance.pdfFiltered");
     Route::resource("maintenance", MaintenanceController::class);
     Route::get("maintenance/{maintenance}/delete", [MaintenanceController::class,"destroy"])
          ->name("maintenance.delete");

                              
          Route::resource("licenseCategories", LicenseCategoryController::class)
               ->names([
                    "index"   => "licenseCategories.index",
                    "create"  => "licenseCategories.create",
                    "store"   => "licenseCategories.store",
                    "show"    => "licenseCategories.show",
                    "edit"    => "licenseCategories.edit",
                    "update"  => "licenseCategories.update",
                    "destroy" => "licenseCategories.destroy",
               ]);
          Route::get("licenseCategories/{licenseCategory}/delete",[LicenseCategoryController::class,"destroy"])->name("licenseCategories.delete");
               //  PDF Individual download
               Route::get("drivers/{driver}/pdf", [DriverController::class,"showPdf"])->name("drivers.showPdf");
               Route::get("vehicles/{vehicle}/pdf", [VehicleController::class,"showPdf"])->name("vehicles.showPdf");
               Route::get("maintenance/{maintenance}/pdf", [MaintenanceController::class,"showPdf"])->name("maintenance.showPdf");



     // ====================== HISTORICO DE CADA FUNCIONARIO(EMPLOYEE HISTORY) ======================
    Route::get("employeee/{id}/history", [EmployeeHistoryController::class, "history"])->name("employee.history");



    // ====================== Grupo de Chefe de Departamento ======================
    Route::prefix("department-head")->name("dh.")->group(function() {
        Route::get("/employee/{id}/vacation/pdf", [DepartmentHeadController::class, "downloadEmployeeVacationPdf"])->name("downloadEmployeeVacationPdf");
        Route::get("/employee/{id}/leave/pdf", [DepartmentHeadController::class, "downloadEmployeeLeavePdf"])->name("downloadEmployeeLeavePdf");
        Route::get("my-employees", [DepartmentHeadController::class, "myEmployees"])->name("myEmployees");
        Route::get("pending-vacations", [DepartmentHeadController::class, "pendingVacations"])->name("pendingVacations");
        Route::post("approve-vacation/{id}", [DepartmentHeadController::class, "approveVacation"])->name("approveVacation");
        Route::post("reject-vacation/{id}", [DepartmentHeadController::class, "rejectVacation"])->name("rejectVacation");

        // Rotas para pedidos de licença
        Route::get("pending-leaves", [DepartmentHeadController::class, "pendingLeaves"])->name("pendingLeaves");
        Route::post("approve-leave/{id}", [DepartmentHeadController::class, "approveLeave"])->name("approveLeave");
        Route::post("reject-leave/{id}", [DepartmentHeadController::class, "rejectLeave"])->name("rejectLeave");

        // Rotas para pedidos de reforma (retirement)
        Route::get("reformas-pendentes", [DepartmentHeadController::class, "pendingRetirements"])->name("pendingRetirements");
        Route::put("reformas/aprovar/{id}", [DepartmentHeadController::class, "approveRetirement"])->name("approveRetirement");
        Route::put("reformas/rejeitar/{id}", [DepartmentHeadController::class, "rejectRetirement"])->name("rejectRetirement");

    });


          // ====================== CHAT DE CONVERSAS (CONVERSATION CHAT) ======================
          Route::get("/new-chat", [NewChatController::class, "index"])->name("new-chat.index");
          //rota para exibir a conversa
          Route::get("/new-chat/{groupId}", [NewChatController::class, "show"])->name("new-chat.show");
          //rota para enviar a mensagem via AJAX
          Route::post("/new-chat/send-message", [NewChatController::class, "sendMessage"])->name("new-chat.sendMessage");


            // ======================NOSSO ESTATUTO(OWR ESTATUTE)======================
            Route::resource("statutes", StatuteController::class);
               Route::get("statutes/{id}/delete", [StatuteController::class, "destroy"])->name("statutes.delete");


            // ====================== HOME ======================
            Route::get("/homeRH-INFOSI", [FrontendController::class, "index"])->name("frontend.index.rhHome");
});


/*
|---------------------------------------------------------------------------
| Rotas de Administradores
| Rotas exclusivas para administradores (e diretores) para gerenciar usuários e outras configurações
|---------------------------------------------------------------------------
*/

// Rota para gerar o contrato em PDF para o administrador cujo papel seja "employee"

Route::get("/{id}/contract", [AdminAuthController::class, "contractPdf"])->name("admins.contract");
Route::prefix("admins")->group(function () {
    Route::get("/", [AdminAuthController::class, "index"])->name("admins.index");
    Route::get("/create", [AdminAuthController::class, "create"])->name("admins.create");
    Route::post("/", [AdminAuthController::class, "store"])->name("admins.store");
    Route::get("/{id}", [AdminAuthController::class, "show"])->name("admins.show");
    Route::get("/{id}/edit", [AdminAuthController::class, "edit"])->name("admins.edit");
    Route::put("/{id}", [AdminAuthController::class, "update"])->name("admins.update");
    Route::delete("/{id}", [AdminAuthController::class, "destroy"])->name("admins.destroy");
    Route::post("/login", [AdminAuthController::class, "login"])->name("admins.login");

    
});







