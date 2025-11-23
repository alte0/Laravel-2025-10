@extends('layout/bs')

@section('title', $task->title)

@section('content')
    <div>
        <h3><a href="{{ route('adminTasks.tasks.edit', ['task' => $task], false) }}">{{ $task->title }}</a></h3>
        <p>{{ $task->description }}</p>
        <p>create: {{ $task->created_at->diffForHumans() }}</p>
        <p>start: {{ $task->start_date->format('d.m.Y H:i') }}</p>
        <p>end: {{ $task->end_date->format('d.m.Y H:i') }}</p>
    </div>
@endsection
