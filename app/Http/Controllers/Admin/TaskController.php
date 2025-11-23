<?php

namespace App\Http\Controllers\Admin;

use App\DTO\CreateTaskRequestDTO;
use App\DTO\UpdateTaskRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = $this->taskService->getItems();

        return view('pages.admin.tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTaskRequest $request)
    {
        $data = $request->validated();

        $createTaskRequestDTO = new CreateTaskRequestDTO(
            $data['title'], $data['description'], $data['start'], $data['end'], Auth::user()->id
        );

        $id = $this->taskService->create($createTaskRequestDTO);

        return redirect()->route('adminTasks.tasks.show', $this->taskService->find(3));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = $this->taskService->find($id);

        return view('pages.admin.tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = $this->taskService->find($id);

        return view('pages.admin.tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, string $id)
    {
        $data = $request->validated();

        $updateTaskRequestDTO = new UpdateTaskRequestDTO(
            $data['id'], $data['title'], $data['description'], $data['start'], $data['end']
        );

        $this->taskService->update($updateTaskRequestDTO);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->taskService->delete($id);

        return redirect()->route('adminTasks.tasks.index');
    }
}
