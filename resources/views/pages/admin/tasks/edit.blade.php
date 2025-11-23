@extends('layout/bs')

@section('title', $task->title)

@section('content')
{{--    @dump($task)--}}
    <div class="pt-3 pb-3">
        <form method="post" action="{{ route('adminTasks.tasks.update', ['task' => $task], false) }}">
            @csrf
            @method('PATCH')
            <input name="id" type="hidden" value="{{ $task->id }}"
            >
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input
                    name="title"
                    type="text"
                    class="form-control"
                    id="title"
                    value="{{ $task->title }}"
                >
                @error('title')
                <div class="alert alert-warning" role="alert">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea
                    name="description"
                    class="form-control"
                    id="description"
                    rows="5"
                >{{ $task->description }}</textarea>
                @error('description')
                <div class="alert alert-warning" role="alert">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="start" class="form-label">Date start</label>
                <input
                    name="start"
                    type="datetime-local"
                    class="form-control"
                    id="start"
                    value="{{ $task->start_date ? $task->start_date : '' }}"
                >
                @error('start')
                <div class="alert alert-warning" role="alert">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="start" class="form-label">Date end</label>
                <input
                    name="end"
                    type="datetime-local"
                    class="form-control"
                    id="start"
                    value="{{ $task->end_date ? $task->end_date : '' }}"
                >
                @error('end')
                <div class="alert alert-warning" role="alert">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
