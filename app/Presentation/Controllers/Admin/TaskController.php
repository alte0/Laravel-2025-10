<?php

namespace App\Presentation\Controllers\Admin;

use App\Application\DTO\Task\CreateTaskRequestDTO;
use App\Application\DTO\Task\UpdateTaskRequestDTO;
use App\Application\Handlers\CreateTakHandler;
use App\Application\Handlers\UpdateTakHandler;
use App\Http\Controllers\Controller;
use App\Presentation\Requests\AdminTask\CreateTaskRequest;
use App\Presentation\Requests\AdminTask\DeleteTaskRequest;
use App\Presentation\Requests\AdminTask\UpdateTaskRequest;
use App\Presentation\Requests\AdminTask\ViewAnyTaskRequest;
use App\Presentation\Requests\AdminTask\ViewCreateTaskRequest;
use App\Presentation\Requests\AdminTask\ViewEditTaskRequest;
use App\Presentation\Requests\AdminTask\ViewTaskRequest;
use App\Services\TaskService;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService,
        private readonly UpdateTakHandler $updateTakHandler,
        private readonly CreateTakHandler $createTakHandler,
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
        $createTaskRequestDTO = new CreateTaskRequestDTO(
            $request->get('title', ''),
            $request->get('description', ''),
            $request->get('start', ''),
            $request->get('end', ''),
            Auth::id(),
            $request->get('executor_id')
        );

//        $id = $this->taskService->create($createTaskRequestDTO);
        $id = $this->createTakHandler->handle($createTaskRequestDTO);

        return redirect()->route('adminTasks.tasks.show', ['task' => $id]);
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
        $updateTaskRequestDTO = new UpdateTaskRequestDTO(
            $request->get('id'),
            $request->get('title', ''),
            $request->get('description', ''),
            $request->get('start', ''),
            $request->get('end', ''),
        );

        //$this->taskService->update($updateTaskRequestDTO);
        $this->updateTakHandler->handle($updateTaskRequestDTO);

        return redirect()->route('adminTasks.tasks.edit', ['task' => $updateTaskRequestDTO->getId()]);
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
