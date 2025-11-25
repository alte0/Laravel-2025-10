@extends('layout/bs')

@section('content')
    <div class="pt-3 pb-3">
        <form method="post" action="{{ route('adminTasks.tasks.store', [], false) }}">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input
                    name="title"
                    type="text"
                    class="form-control"
                    id="title"
                    value="{{ old('title') }}"
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
                >{{ old('description') }}</textarea>
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
                    value="{{ old('start') }}"
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
                    value="{{ old('end') }}"
                >
                @error('end')
                <div class="alert alert-warning" role="alert">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
@endsection
