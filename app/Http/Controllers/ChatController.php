<?php
// app/Http/Controllers/ChatController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatGroup;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Exibe a lista de conversas em abas conforme o nível de acesso
    public function index()
    {
        $user = Auth::user();
        $groups = collect();

        // Caso seja Diretor:
        if ($user->role === 'director' && $user->employee) {
            // Grupo "Diretores"
            $directorGroup = ChatGroup::firstOrCreate(
                ['groupType' => 'directorGroup'],
                ['name' => 'Diretores']
            );
            $groups->push($directorGroup);
            
            // Conversas individuais: Diretor com cada Chefe de Departamento
            $individuals = ChatGroup::where('groupType', 'individual')
                ->where('headId', $user->employee->id)
                ->get();
            $groups = $groups->merge($individuals);
        }

        // Caso seja Chefe de Departamento:
        if ($user->role === 'department_head' && $user->employee) {
            // Grupo do Departamento: todos os funcionários e o próprio chefe
            $departmentGroup = ChatGroup::firstOrCreate(
                [
                    'groupType'    => 'departmentGroup',
                    'departmentId' => $user->employee->departmentId
                ],
                ['name' => 'Departamento ' . $user->employee->department->title]
            );
            $groups->push($departmentGroup);

            // Grupo de Chefes de Departamento (caso necessário)
            $chefeGroup = ChatGroup::firstOrCreate(
                ['groupType' => 'directorGroup'],
                ['name' => 'Chefes de Departamento']
            );
            $groups->push($chefeGroup);

            // Conversas individuais entre chefes ou entre chefe e funcionário
            $individuals = ChatGroup::where('groupType', 'individual')
                ->where('headId', $user->employee->id)
                ->get();
            $groups = $groups->merge($individuals);
        }

        // Caso seja Funcionário:
        if ($user->role === 'employee' && $user->employee && $user->employee->departmentId) {
            // Grupo do Departamento
            $departmentGroup = ChatGroup::firstOrCreate(
                [
                    'groupType'    => 'departmentGroup',
                    'departmentId' => $user->employee->departmentId
                ],
                ['name' => 'Departamento ' . $user->employee->department->title]
            );
            $groups->push($departmentGroup);

            // Conversa individual com seu Chefe
            $head = \App\Models\Admin::where('role', 'department_head')
                ->where('department_id', $user->employee->departmentId)
                ->first();
            if ($head && $head->employee) {
                $individualGroup = ChatGroup::firstOrCreate(
                    [
                        'groupType'    => 'individual',
                        'departmentId' => $user->employee->departmentId,
                        'headId'       => $head->employee->id
                    ],
                    ['name' => $user->employee->fullName . ' ↔ ' . $head->employee->fullName]
                );
                $groups->push($individualGroup);
            }
        }

        $groups = $groups->unique('id')->values();

        // Separa os grupos por tipo
        $directorGroup    = $groups->where('groupType', 'directorGroup');
        $departmentGroups = $groups->where('groupType', 'departmentGroup');
        $individuals      = $groups->where('groupType', 'individual');

        return view('chat.index', compact('directorGroup', 'departmentGroups', 'individuals'));
    }

    // Exibe a tela de conversa do grupo selecionado
    public function show($groupId)
    {
        $group = ChatGroup::findOrFail($groupId);
        // Carrega mensagens com o relacionamento sender e ordena pela data
        $messages = ChatMessage::with('sender')
                     ->where('chatGroupId', $groupId)
                     ->orderBy('created_at')
                     ->get();
        return view('chat.conversation', compact('group', 'messages'));
    }

    // Envia uma mensagem e dispara o evento para atualização em tempo real
    public function sendMessage(Request $request)
    {
        $request->validate([
            'chatGroupId' => 'required|exists:chat_groups,id',
            'message'     => 'required|string',
        ]);

        $user = Auth::user();

        // Cria a mensagem. Observe que o senderType segue a sua definição ('admin' ou 'employeee')
        $msg = ChatMessage::create([
            'chatGroupId' => $request->chatGroupId,
            'senderId'    => $user->id,
            'senderType'  => $user->role === 'admin' ? 'admin' : 'employeee',
            'message'     => $request->message,
        ]);

        // Dispara o evento para o broadcast
        //event(new \App\Events\ChatMessageSent($msg));

        return response()->json(['status' => 'ok']);
    }
}
