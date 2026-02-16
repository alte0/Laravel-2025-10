<?php

namespace App\Http\API;

use App\Application\DTO\Task\CreateTaskRequestDTO;
use App\Application\DTO\Task\UpdateTaskRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[OA\Components(
    schemas: [
        new OA\Schema(
            schema: 'Task',
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 1),
                new OA\Property(property: 'title', type: 'string', example: 'Sample Task'),
                new OA\Property(property: 'description', type: 'string', example: 'Description of the sample task'),
                new OA\Property(property: 'authorId', type: 'integer', example: 1),
                new OA\Property(property: 'executorId', type: 'integer', example: 1),
                new OA\Property(property: 'createdAt', type: 'string', example: '15.02.26 10:30'),
                new OA\Property(property: 'updatedAt', type: 'string', example: '15.02.26 10:30'),
                new OA\Property(property: 'startDate', type: 'string', example: '15.02.26 09:00'),
                new OA\Property(property: 'endDate', type: 'string', example: '15.02.26 18:00'),
            ],
            type: 'object'
        )
    ]
)]
#[OA\Tag(name: 'Tasks')]
#[OA\PathItem(path: '/api/v1/tasks')]
class TaskApiController extends Controller
{
    private TaskService $taskService;

    public function __construct(
        TaskService  $taskService
    )
    {
        $this->taskService = $taskService;
    }

    #[OA\Get(
        path: '/api/v1/tasks',
        description: 'Returns a list of all tasks',
        summary: 'Get all tasks',
        tags: ['Tasks'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of tasks',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Task')
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized')
        ]
    )]
    public function index()
    {
        return TaskResource::collection($this->taskService->getItems());
    }

    #[OA\Post(
        path: '/api/v1/tasks',
        description: 'Creates a new task',
        summary: 'Create a new task',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(ref: '#/components/schemas/Task')
            )
        ),
        tags: ['Tasks'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Task created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Task created'),
                        new OA\Property(property: 'id', type: 'integer', example: 1)
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Task not created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Task not created')
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 422, description: 'Validation error')
        ]
    )]
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
            'executor_id' => 'required|integer|exists:users,id'
        ]);

        $createTaskRequestDTO = new CreateTaskRequestDTO(
            $validatedData['title'],
            $validatedData['description'] ?? '',
            $validatedData['start'],
            $validatedData['end'],
            Auth::id(),
            $validatedData['executor_id']
        );

        $taskId = $this->taskService->create($createTaskRequestDTO);

        if ($taskId < 1) {
            return Response::json(['message' => 'Task not created'], SymfonyResponse::HTTP_BAD_REQUEST);
        }

        return Response::json(['message' => 'Task created', 'id' => $taskId], SymfonyResponse::HTTP_CREATED);
    }

    #[OA\Get(
        path: '/api/v1/tasks/{id}',
        description: 'Returns a specific task by ID',
        summary: 'Get a specific task',
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Task ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Specific task retrieved',
                content: new OA\JsonContent(ref: '#/components/schemas/Task')
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Task not found')
        ]
    )]
    public function show(string $id)
    {
        $task = $this->taskService->find($id);

        $taskResource = new TaskResource($task);

        return Response::json($taskResource,  SymfonyResponse::HTTP_OK);
    }

    #[OA\Put(
        path: '/api/v1/tasks/{id}',
        description: 'Updates a specific task by ID',
        summary: 'Update a specific task',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(ref: '#/components/schemas/Task')
            )
        ),
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Task ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Task updated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Task updated')
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Task not updated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Task not updated')
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Task not found'),
            new OA\Response(response: 422, description: 'Validation error')
        ]
    )]
    public function update(Request $request, string $id)
    {
        $task = $this->taskService->find($id);

        if ($task === null) {
            return Response::json(['message' => 'Task not found'], SymfonyResponse::HTTP_NOT_FOUND);
        }

        $updateTaskRequestDTO = new UpdateTaskRequestDTO(
            (int)$id,
            $request->get('title', $task->title),
            $request->get('description', $task->description),
            $request->get('start', $task->start_date),
            $request->get('end', $task->end_date),
        );

        $result = $this->taskService->update($updateTaskRequestDTO);

        if ($result) {
            return Response::json(['message' => 'Task updated'], SymfonyResponse::HTTP_OK);
        }

        return Response::json(['message' => 'Task not updated'], SymfonyResponse::HTTP_BAD_REQUEST);
    }

    #[OA\Delete(
        path: '/api/v1/tasks/{id}',
        description: 'Deletes a specific task by ID',
        summary: 'Delete a specific task',
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Task ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 204, description: 'Task deleted'),
            new OA\Response(
                response: 400,
                description: 'Task not deleted',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Task not deleted')
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Task not found')
        ]
    )]
    public function destroy(string $id)
    {
        $task = $this->taskService->find($id);

        if ($task === null) {
            return Response::json(['message' => 'Task not found'], SymfonyResponse::HTTP_NOT_FOUND);
        }

        $result = $this->taskService->delete($id);

        if ($result === false) {
            return Response::json(['message' => 'Task not deleted'], SymfonyResponse::HTTP_BAD_REQUEST);
        }

        return response()->noContent();
    }
}
