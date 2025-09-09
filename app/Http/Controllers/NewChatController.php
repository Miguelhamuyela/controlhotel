<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Employeee;
use App\Models\Department;
use App\Models\ChatGroup;
use App\Models\ChatMessage;
use App\Events\NewChatMessageSent;

class NewChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $groups = collect();

        // PARA DIRETORES
        if ($user->role === 'director') {
            // Grupo de diretores
            $directorGroup = ChatGroup::firstOrCreate(
                ['groupType' => 'directorGroup'],
                ['name' => 'Diretores']
            );
            $groups->push($directorGroup);

            // Grupo de chefes de departamento
            $deptHeadsGroup = ChatGroup::firstOrCreate(
                ['groupType' => 'departmentHeadsGroup'],
                ['name' => 'Chefes de Departamento']
            );
            $groups->push($deptHeadsGroup);

            // Conversas individuais entre diretores
            $otherDirectors = Admin::where('role', 'director')
                ->where('id', '!=', $user->id)
                ->get();

            foreach ($otherDirectors as $other) {
                $minID = min($user->id, $other->id);
                $maxID = max($user->id, $other->id);
                $conversationKey = 'individual_' . $minID . '_' . $maxID;

                $directorName1 = !empty($user->directorName) ? $user->directorName : $user->email;
                $directorName2 = !empty($other->directorName) ? $other->directorName : $other->email;
                $constructedName = ($user->id == $minID)
                    ? $directorName1 . ' ↔ ' . $directorName2
                    : $directorName2 . ' ↔ ' . $directorName1;

                $ind = ChatGroup::firstOrCreate(
                    ['groupType' => 'individual', 'conversation_key' => $conversationKey],
                    ['name' => $constructedName]
                );
                $groups->push($ind);
            }

            // Conversas individuais entre diretor e cada chefe de departamento
            $deptHeads = Admin::where('role', 'department_head')->get();
            foreach ($deptHeads as $deptHead) {
                $minID = min($user->id, $deptHead->id);
                $maxID = max($user->id, $deptHead->id);
                $conversationKey = 'individual_' . $minID . '_' . $maxID;

                $directorName = !empty($user->directorName) ? $user->directorName : $user->email;
                $deptHeadName = ($deptHead->employee && !empty($deptHead->employee->fullName))
                    ? $deptHead->employee->fullName
                    : $deptHead->email;
                $constructedName = ($user->id == $minID)
                    ? $directorName . ' ↔ ' . $deptHeadName
                    : $deptHeadName . ' ↔ ' . $directorName;

                $ind = ChatGroup::firstOrCreate(
                    ['groupType' => 'individual', 'conversation_key' => $conversationKey],
                    ['name' => $constructedName]
                );
                $groups->push($ind);
            }
        }
        // PARA CHEFES DE DEPARTAMENTO
        elseif ($user->role === 'department_head') {
            // Grupo de chefes de departamento
            $deptHeadsGroup = ChatGroup::firstOrCreate(
                ['groupType' => 'departmentHeadsGroup'],
                ['name' => 'Chefes de Departamento']
            );
            $groups->push($deptHeadsGroup);

            if (!empty($user->department_id)) {
                $departmentGroup = ChatGroup::firstOrCreate(
                    [
                        'groupType'    => 'departmentGroup',
                        'departmentId' => $user->department_id
                    ],
                    ['name' => 'Departamento ' . $this->getDepartmentTitle($user->department_id)]
                );
                $groups->push($departmentGroup);

                // Conversas individuais entre o chefe e os funcionários do departamento
                $employees = Employeee::where('departmentId', $user->department_id)->get();
                foreach ($employees as $emp) {
                    // Evita criar uma conversa do chefe com ele mesmo
                    if ($emp->id != ($user->employee->id ?? 0)) {
                        $conversationKey = "individual_employee_{$emp->id}_{$user->employee->id}";
                        $ind = ChatGroup::firstOrCreate(
                            [
                                'groupType'       => 'individual',
                                'conversation_key'=> $conversationKey
                            ],
                            [
                                'name'         => $emp->fullName . ' ↔ ' . $user->employee->fullName,
                                'departmentId' => $user->department_id,
                                'headId'       => $user->employee->id
                            ]
                        );
                        $groups->push($ind);
                    }
                }
            }

            // Conversas individuais entre o chefe de departamento e cada diretor
            $directors = Admin::where('role', 'director')->get();
            foreach ($directors as $director) {
                $minID = min($user->id, $director->id);
                $maxID = max($user->id, $director->id);
                $conversationKey = 'individual_' . $minID . '_' . $maxID;

                $directorName = !empty($director->directorName) ? $director->directorName : $director->email;
                $deptHeadName = ($user->employee && !empty($user->employee->fullName))
                    ? $user->employee->fullName
                    : $user->email;
                $constructedName = ($user->id == $minID)
                    ? $deptHeadName . ' ↔ ' . $directorName
                    : $directorName . ' ↔ ' . $deptHeadName;

                $ind = ChatGroup::firstOrCreate(
                    ['groupType' => 'individual', 'conversation_key' => $conversationKey],
                    ['name' => $constructedName]
                );
                $groups->push($ind);
            }
        }
        // PARA FUNCIONÁRIOS
        elseif ($user->role === 'employee') {
            $emp = Employeee::where('email', $user->email)->first();
            if ($emp && $emp->departmentId) {
                $departmentGroup = ChatGroup::firstOrCreate(
                    [
                        'groupType'    => 'departmentGroup',
                        'departmentId' => $emp->departmentId
                    ],
                    ['name' => 'Departamento ' . ($emp->department->title ?? '')]
                );
                $groups->push($departmentGroup);

                $headAdmin = Admin::where('role', 'department_head')
                                  ->where('department_id', $emp->departmentId)
                                  ->first();
                if ($headAdmin && $headAdmin->employee) {
                    $conversationKey = "individual_employee_{$emp->id}_{$headAdmin->employee->id}";
                    $ind = ChatGroup::firstOrCreate(
                        [
                            'groupType'       => 'individual',
                            'conversation_key'=> $conversationKey
                        ],
                        [
                            'name'         => $emp->fullName . ' ↔ ' . $headAdmin->employee->fullName,
                            'departmentId' => $emp->departmentId,
                            'headId'       => $headAdmin->employee->id
                        ]
                    );
                    $groups->push($ind);
                }
            }
        }

        $groups = $groups->unique('id')->values();

        // Separação por tipo para a view
        $directorGroup        = $groups->where('groupType', 'directorGroup');
        $departmentHeadsGroup = $groups->where('groupType', 'departmentHeadsGroup');
        $departmentGroups     = $groups->where('groupType', 'departmentGroup');
        $individuals          = $groups->where('groupType', 'individual');

        return view('new-chat.index', compact(
            'directorGroup',
            'departmentHeadsGroup',
            'departmentGroups',
            'individuals'
        ));
    }

    public function show($groupId)
    {
        $group = ChatGroup::findOrFail($groupId);
        if (!$this->userCanViewGroup($group)) {
            abort(403, 'Acesso negado a este grupo de chat.');
        }

        $messages = ChatMessage::with('sender')
            ->where('chatGroupId', $groupId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('new-chat.conversation', compact('group', 'messages'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'chatGroupId' => 'required|exists:chat_groups,id',
            'message'     => 'required|string'
        ]);
        $user = Auth::user();

        $group = ChatGroup::find($request->chatGroupId);
        if (!$group || !$this->userCanViewGroup($group)) {
            return response()->json(['status' => 'forbidden'], 403);
        }

        $senderType = in_array($user->role, ['director', 'department_head', 'admin'])
            ? 'admin'
            : 'employeee';

        // Armazena o e-mail fixo do usuário que envia a mensagem
        $msg = ChatMessage::create([
            'chatGroupId' => $group->id,
            'senderId'    => $user->id,
            'senderType'  => $senderType,
            'senderEmail' => $user->email,
            'message'     => $request->message,
        ]);

        event(new NewChatMessageSent($msg));

        return response()->json(['status' => 'ok']);
    }

    /**
     * Verifica se o usuário logado tem permissão para acessar o grupo de chat.
     */
    private function userCanViewGroup(ChatGroup $group): bool
    {
        $user = Auth::user();

        // Grupo de DIRETORES
        if ($group->groupType === 'directorGroup') {
            return ($user->role === 'director');
        }

        // Grupo de CHEFES DE DEPARTAMENTO
        if ($group->groupType === 'departmentHeadsGroup') {
            return ($user->role === 'department_head');
        }

        // Grupo de DEPARTAMENTO
        if ($group->groupType === 'departmentGroup') {
            if (in_array($user->role, ['department_head', 'admin'])) {
                return $user->department_id == $group->departmentId;
            } elseif ($user->role === 'employee') {
                $emp = Employeee::where('email', $user->email)->first();
                return ($emp && $emp->departmentId == $group->departmentId);
            }
            return false;
        }

        // Grupo de CONVERSA INDIVIDUAL
        if ($group->groupType === 'individual') {
            if (isset($group->conversation_key)) {
                // Espera o formato: "individual_employee_{empId}_{headId}"
                $parts = explode('_', $group->conversation_key);
                $empId = isset($parts[2]) ? intval($parts[2]) : null;
                $headId = isset($parts[3]) ? intval($parts[3]) : null;

                // Se o usuário for funcionário, usamos o ID da tabela employeees
                if ($user->role === 'employee') {
                    $currentEmp = Employeee::where('email', $user->email)->first();
                    if ($currentEmp) {
                        return in_array($currentEmp->id, [$empId, $headId]);
                    }
                }
                // Se for chefe de departamento, utilizamos o id do funcionário vinculado ao admin
                elseif ($user->role === 'department_head') {
                    if (!empty($user->employee) && isset($user->employee->id)) {
                        return in_array($user->employee->id, [$empId, $headId]);
                    }
                    return in_array($user->id, [$empId, $headId]);
                }
                // Para outros roles, comparamos diretamente com o user->id
                return in_array($user->id, [$empId, $headId]);
            }
            // Fallback: busca pelo nome no título do grupo
            $userName = $user->employee ? $user->employee->fullName : $user->email;
            return str_contains($group->name, $userName);
        }

        return false;
    }

    /**
     * Retorna o título do departamento para exibição.
     */
    private function getDepartmentTitle($deptId): string
    {
        $d = Department::find($deptId);
        return $d ? $d->title : '';
    }
}
