<?php

namespace App\Http\Controllers\Admin;

use App\DTO\CreateTaskRequestDTO;
use App\DTO\UpdateTaskRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminTask\CreateTaskRequest;
use App\Http\Requests\AdminTask\DeleteTaskRequest;
use App\Http\Requests\AdminTask\UpdateTaskRequest;
use App\Http\Requests\AdminTask\ViewAnyTaskRequest;
use App\Http\Requests\AdminTask\ViewCreateTaskRequest;
use App\Http\Requests\AdminTask\ViewEditTaskRequest;
use App\Http\Requests\AdminTask\ViewTaskRequest;
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
    public function index(ViewAnyTaskRequest $request)
    {
        $tasks = $this->taskService->getItems();

        return view('pages.admin.tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ViewCreateTaskRequest $request)
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
            $data['title'], $data['description'], $data['start'], $data['end'], Auth::id()
        );

        $id = $this->taskService->create($createTaskRequestDTO);

        return redirect()->route('adminTasks.tasks.show', $this->taskService->find($id));
    }

    /**
     * Display the specified resource.
     */
    public function show(ViewTaskRequest $request, string $id)
    {
        $task = $this->taskService->find($id);

        return view('pages.admin.tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ViewEditTaskRequest $request, string $id)
    {
        $task = $this->taskService->find($id);

        return view('pages.admin.tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request)
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
    public function destroy(DeleteTaskRequest $request, string $id)
    {
        $this->taskService->delete($id);

        return redirect()->route('adminTasks.tasks.index');
    }
}
