<?php

namespace Tests\Unit;

use App\DTO\CreateTaskRequestDTO;
use App\DTO\UpdateTaskRequestDTO;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('AdminTaskDtoTest')]
class AdminTaskDtoTest extends TestCase
{
    public function test_create_task_request_dto()
    {
        $title = 'title';
        $description = 'description';
        $start = '12-09-2025';
        $end = '13-09-2025';
        $authorId = 1;

        $createTaskRequestDTO = new CreateTaskRequestDTO(
            $title,
            $description,
            $start,
            $end,
            $authorId
        );

        $this->assertEquals($title, $createTaskRequestDTO->getTitle());
        $this->assertEquals($description, $createTaskRequestDTO->getDescription());
        $this->assertEquals($start, $createTaskRequestDTO->getStart());
        $this->assertEquals($end, $createTaskRequestDTO->getEnd());
        $this->assertEquals($authorId, $createTaskRequestDTO->getAuthorId());
    }

    public function test_update_task_request_dto()
    {
        $id = 2;
        $title = 'title 2';
        $description = 'description 2';
        $start = '10-09-2025';
        $end = '12-09-2025';

        $updateTaskRequestDTO = new UpdateTaskRequestDTO(
            $id,
            $title,
            $description,
            $start,
            $end
        );

        $this->assertEquals($title, $updateTaskRequestDTO->getTitle());
        $this->assertEquals($description, $updateTaskRequestDTO->getDescription());
        $this->assertEquals($start, $updateTaskRequestDTO->getStart());
        $this->assertEquals($end, $updateTaskRequestDTO->getEnd());
        $this->assertEquals($id, $updateTaskRequestDTO->getId());
    }
}
