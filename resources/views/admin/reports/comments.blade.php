@extends('layouts.app')

@section('title', 'Pending Comments')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Pending Comments</h1>

    @if($comments->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <p class="text-gray-500">No pending comments.</p>
        </div>
    @else
        <div class="grid gap-6">
            @foreach($comments as $comment)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-gray-900 font-medium">{{ $comment->user->name }} on {{ $comment->menu->name }}</p>
                            <p class="text-gray-500 text-sm">{{ $comment->created_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <form action="{{ route('comments.approve', $comment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                    Approve
                                </button>
                            </form>
                            <form action="{{ route('comments.reject', $comment->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                    Reject
                                </button>
                            </form>
                        </div>
                    </div>
                    <p class="text-gray-700">{{ $comment->content }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $comments->links() }}
        </div>
    @endif
</div>
@endsection
