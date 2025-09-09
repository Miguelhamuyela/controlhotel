<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Employeee;
use App\Models\Department;
use App\Models\Position;
use App\Models\Specialty;
use App\Models\EmployeeType;
use App\Models\EmployeeCategory;
use App\Models\Course;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Mail\NewEmployeeNotification;

class EmployeeeController extends Controller
{
    public function index(Request $request)
    {
        // Obtemos o usuário logado
        $user = auth()->user();

        // Inicia a query para buscar os funcionários
        $query = Employeee::query();

        // Se o usuário for chefe de departamento ou funcionário, aplicamos os filtros
        if ($user->role === 'department_head' || $user->role === 'employee') {
            // Excluir funcionários que estejam vinculados a um administrador com role "director"
            // Utilizamos o relacionamento 'admin' definido no modelo Employeee
            $query->whereDoesntHave('admin', function ($q) {
                $q->where('role', 'director');
            });

            // Se for chefe de departamento, limitar a visualização apenas aos funcionários do seu departamento
            if ($user->role === 'department_head') {
                // Supondo que o usuário logado possua o relacionamento 'employee' e o campo 'departmentId'
                $departmentId = $user->employee->departmentId;
                $query->where('departmentId', $departmentId);
            }
        }

        // Implementa a pesquisa por nome se o parâmetro 'search' for enviado
        if ($search = $request->get('search')) {
            $query->where('fullName', 'like', '%' . $search . '%');
        }

        // Ordena os registros e obtém os resultados
        $data = $query->orderByDesc('id')->get();

        return view('employeee.index', ['data' => $data]);
    }


    public function create()
    {
        $departments        = Department::all();
        $positions          = Position::all();
        $specialties        = Specialty::all();
        $employeeTypes      = EmployeeType::all();
        $employeeCategories = EmployeeCategory::all();
        $courses            = Course::all(); // Adicionado

        return view('employeee.create', compact('departments', 'positions', 'specialties', 'employeeTypes', 'employeeCategories', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
        'depart'             => 'nullable',
        'fullName'           => [
            'required',
            'string',
            'max:255',
            'regex:/^[\pL\s]+$/u'  // apenas letras 
        ],
        'address'            => 'required',
        'mobile'             => 'required',
        'bi'                 => 'required|unique:employeees',
        'biPhoto'            => 'nullable|file|mimes:pdf,jpeg,png,jpg',
        'birth_date'         => [
            'required',
            'date',
            'date_format:Y-m-d',
            'before_or_equal:' . Carbon::now()->subYears(18)->format('Y-m-d'),   // ≥18 anos
            'after_or_equal:'  . Carbon::now()->subYears(120)->format('Y-m-d')  // ≤120 anos
        ],
        'nationality'        => 'required',
        'gender'             => 'required',
        'email'              => 'required|unique:employeees,email|regex:/^[a-zA-Z0-9._%+-]+$/',
        'iban'               => [
            'nullable',
            'string',
            'max:25',
            'regex:/^AO06[0-9]{21}$/',
        ],
        'employeeTypeId'     => 'required|exists:employee_types,id',
        'employeeCategoryId' => 'required|exists:employee_categories,id',
        'positionId'         => 'required|exists:positions,id',
        'specialtyId'        => 'required|exists:specialties,id',
        'academicLevel'      => 'nullable|string|max:255', // Adicionado
        'courseId'           => 'nullable|exists:courses,id', // Adicionado
        'photo'              => 'nullable|image',
    ], [
        'fullName.regex'               => 'O nome só pode conter letras e espaços.',
        'birth_date.before_or_equal'   => 'A idade minima permitida é 18 anos.',
        'iban.regex'                   => 'O IBAN deve começar por AO06 seguido de 21 dígitos.',
        'email.regex'                  => 'O email deve conter apenas o nome e o sobrenome.',
    ]);


        $data = new Employeee();
        $data->departmentId    = $request->depart;
        $data->fullName        = $request->fullName;
        $data->address         = $request->address;
        $data->mobile          = $request->mobile;
        $data->phone_code      = $request->phone_code;
        $data->bi              = $request->bi;
        $data->birth_date      = $request->birth_date;
        $data->nationality     = $request->nationality;
        $data->gender          = $request->gender;
        $data->email           = $request->email . '@infosi.gov.ao';
        $data->iban            = $request->iban;
        $data->employeeTypeId  = $request->employeeTypeId;
        $data->employeeCategoryId = $request->employeeCategoryId;
        $data->positionId      = $request->positionId;
        $data->specialtyId     = $request->specialtyId;
        $data->academicLevel   = $request->academicLevel; // Adicionado
        $data->courseId        = $request->courseId; // Adicionado
        $data->employmentStatus= 'active';

        if ($request->hasFile('photo')) {
            $photoName = time().'_'.$request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('frontend/images/departments'), $photoName);
            $data->photo = $photoName;
        }

        if ($request->hasFile('biPhoto')) {
            $biName = time().'_'.$request->file('biPhoto')->getClientOriginalName();
            $request->file('biPhoto')->move(public_path('frontend/images/biPhotos'), $biName);
            $data->biPhoto = $biName;
        }

        $data->save();
        Mail::to($data->email)->send(new NewEmployeeNotification($data));

        return redirect()->route('employeee.create')
                         ->with('msg','Funcionário cadastrado e e-mail enviado!');
    }

    public function show($id)
    {
        $data = Employeee::findOrFail($id);
        return view('employeee.show', compact('data'));
    }

     
     //Faz o download da ficha individual em PDF
     
    public function showPdf($id)
    {
        // Carrega o funcionário e relacionamentos que você precisar exibir
        $employee = Employeee::with(['department', 'employeeType', 'position', 'specialty', 'employeeCategory', 'course']) // Adicionado
                             ->findOrFail($id);

        // Renderiza o Blade 'employeee.show_pdf' e gera o PDF
        $pdf = PDF::loadView('employeee.show_pdf', compact('employee'))
                  ->setPaper('a4', 'portrait');

        // Força o download com nome de arquivo dinâmico
        return $pdf->stream("Ficha_Funcionario_{$employee->id}.pdf");
    }


    public function edit($id)
    {
        $data               = Employeee::findOrFail($id);
        $departs            = Department::orderByDesc('id')->get();
        $employeeTypes      = EmployeeType::all();
        $positions          = Position::all();
        $specialties        = Specialty::all();
        $employeeCategories = EmployeeCategory::all();
        $courses            = Course::all(); // Adicionado

        return view('employeee.edit', compact('data','departs','employeeTypes','positions','specialties', 'employeeCategories', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
        'depart'             => 'nullable',
        'fullName'           => [
            'required',
            'string',
            'max:255',
            'regex:/^[\pL\s]+$/u'
        ],
        'address'            => 'required',
        'mobile'             => 'required',
        'bi'                 => 'required|unique:employeees,bi,'.$id,
        'biPhoto'            => 'nullable|file|mimes:pdf,jpeg,png,jpg',
        'birth_date'         => [
            'required',
            'date',
            'date_format:Y-m-d',
            'before_or_equal:' . Carbon::now()->subYears(18)->format('Y-m-d'),
            'after_or_equal:'  . Carbon::now()->subYears(120)->format('Y-m-d')
        ],
        'email'              => 'required|unique:employeees,email,'.$id.'|regex:/^[a-zA-Z0-9._%+-]+$/',
        'iban'               => [
            'nullable',
            'string',
            'max:25',
            'regex:/^AO06[0-9]{21}$/',
        ],
        'employeeTypeId'     => 'required|exists:employee_types,id',
        'employeeCategoryId' => 'required|exists:employee_categories,id',
        'nationality'        => 'required',
        'academicLevel'      => 'nullable|string|max:255', // Adicionado
        'courseId'           => 'nullable|exists:courses,id', // Adicionado
    ], [
        'fullName.regex'               => 'O nome só pode conter letras e espaços.',
        'birth_date.before_or_equal'   => 'Você deve ter no mínimo 18 anos.',
        'iban.regex'                   => 'O IBAN deve começar por AO06 seguido de 21 dígitos.',
        'email.regex'                  => 'O email deve conter apenas o nome e o sobrenome.',
    ]);

        $data = Employeee::findOrFail($id);
        $data->departmentId    = $request->depart;
        $data->fullName        = $request->fullName;
        $data->address         = $request->address;
        $data->mobile          = $request->mobile;
        $data->phone_code      = $request->phone_code;
        $data->bi              = $request->bi;
        $data->birth_date      = $request->birth_date;
        $data->nationality     = $request->nationality;
        $data->gender          = $request->gender;
        $data->email           = $request->email . '@infosi.gov.ao';
        $data->iban            = $request->iban;
        $data->employeeTypeId  = $request->employeeTypeId;
        $data->employeeCategoryId = $request->employeeCategoryId;
        $data->positionId      = $request->positionId;
        $data->specialtyId     = $request->specialtyId;
        $data->academicLevel   = $request->academicLevel; // Adicionado
        $data->courseId        = $request->courseId; // Adicionado

        if ($request->hasFile('photo')) {
            $photoName = time().'_'.$request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('frontend/images/departments'), $photoName);
            $data->photo = $photoName;
        }

        if ($request->hasFile('biPhoto')) {
            $biName = time().'_'.$request->file('biPhoto')->getClientOriginalName();
            $request->file('biPhoto')->move(public_path('frontend/images/biPhotos'), $biName);
            $data->biPhoto = $biName;
        }

        $data->save();

        return redirect()->route('employeee.edit',$id)
                         ->with('msg','Dados atualizados com sucesso');
    }

    public function myProfile()
{
    $user = Auth::user();
    // Pode ser null se não estiver vinculado
    $employee = $user->employee;

    // Passa sempre $employee (null ou modelo) para a view
    return view('employeee.myProfile', compact('employee'));
}


    public function filterByDate(Request $request)
    {
        $employeeTypes = EmployeeType::all();
        $employeeCategories = EmployeeCategory::all();
        $courses = Course::all(); // Adicionado

        if (!$request->has('start_date') && !$request->has('end_date') && !$request->has('employeeTypeId') && !$request->has('employeeCategoryId') && !$request->has('academicLevel') && !$request->has('courseId')) {
            return view('employeee.filter', ['employeeTypes' => $employeeTypes, 'employeeCategories' => $employeeCategories, 'courses' => $courses]);
        }

        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $typeId    = $request->input('employeeTypeId');
        $categoryId = $request->input('employeeCategoryId');
        $academicLevel = $request->input('academicLevel'); // Adicionado
        $courseId = $request->input('courseId'); // Adicionado
        $query = Employeee::query();

        if ($startDate && $endDate) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date'   => 'required|date|after_or_equal:start_date',
            ]);

            $start = Carbon::parse($startDate)->startOfDay();
            $end   = Carbon::parse($endDate)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        if ($typeId) {
            $query->where('employeeTypeId', $typeId);
        }

        if ($categoryId) {
            $query->where('employeeCategoryId', $categoryId);
        }

        if ($academicLevel) { // Adicionado
            $query->where('academicLevel', 'like', '%' . $academicLevel . '%');
        }

        if ($courseId) { // Adicionado
            $query->where('courseId', $courseId);
        }

        $filtered = $query->orderByDesc('id')->get();

        return view('employeee.filter', [
            'employeeTypes' => $employeeTypes,
            'employeeCategories' => $employeeCategories,
            'courses' => $courses, // Adicionado
            'filtered'      => $filtered,
            'start'         => $startDate,
            'end'           => $endDate,
            'selectedType'  => $typeId,
            'selectedCategory' => $categoryId,
            'selectedAcademicLevel' => $academicLevel, // Adicionado
            'selectedCourse' => $courseId, // Adicionado
        ]);
    }

    public function filterByStatus(Request $request)
    {
        $status = $request->input('status');
        $data = Employeee::where('employmentStatus', $status)->orderByDesc('id')->get();
        return view('employeee.index', ['data' => $data]);
    }

    public function pdfFiltered(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $typeId    = $request->input('employeeTypeId');
        $categoryId = $request->input('employeeCategoryId');
        $academicLevel = $request->input('academicLevel'); // Adicionado
        $courseId = $request->input('courseId'); // Adicionado
        $query = Employeee::query();

        if ($startDate && $endDate) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date'   => 'required|date|after_or_equal:start_date',
            ]);

            $start = Carbon::parse($startDate)->startOfDay();
            $end   = Carbon::parse($endDate)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        if ($typeId) {
            $query->where('employeeTypeId', $typeId);
        }

        if ($categoryId) {
            $query->where('employeeCategoryId', $categoryId);
        }

        if ($academicLevel) {
            $query->where('academicLevel', 'like', '%' . $academicLevel . '%');
        }

        if ($courseId) {
            $query->where('courseId', $courseId);
        }

        $filtered = $query->orderByDesc('id')->get();

        $pdf = PDF::loadView('employeee.filtered_pdf', [
            'filtered'  => $filtered,
            'startDate' => $startDate,
            'endDate'   => $endDate,
            'typeId'    => $typeId,
            'categoryId' => $categoryId,
            'academicLevel' => $academicLevel, // Adicionado
            'courseId' => $courseId, // Adicionado
        ])->setPaper('a3', 'landscape');

        return $pdf->stream("RelatorioFuncionariosFiltrados.pdf");
    }

    public function pdfAll()
    {
        $allEmployees = Employeee::with(['department', 'position', 'specialty', 'employeeCategory', 'course'])->get();
        $pdf = PDF::loadView('employeee.employeee_pdf', compact('allEmployees'))
                  ->setPaper('a3', 'portrait');
        return $pdf->stream('RelatorioTodosFuncionarios.pdf');
    }

    public function destroy($id)
    {
        Employeee::destroy($id);
        return redirect('employeee');
    }
}