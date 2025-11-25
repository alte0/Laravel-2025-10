@extends('layout/bs')

@section('title', 'Задачи')

@section('content')
    <h1>Задачи</h1>
    <div>
        @foreach($tasks as $task)
            <div>
                <h3><a href="{{ route('adminTasks.tasks.show', ['task' => $task], false) }}">{{ $task->title }}</a></h3>
                <p>{{ $task->description }}</p>
                <p>create: {{ $task->created_at->diffForHumans() }}</p>
                <p>start: {{ $task->start_date->format('d.m.Y H:i') }}</p>
                <p>end: {{ $task->end_date->format('d.m.Y H:i') }}</p>
            </div>
            @if(!$loop->last)
            <hr>
            @endif
        @endforeach
    </div>
@endsection
