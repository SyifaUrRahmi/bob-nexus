@extends('layouts.main')

@section('container')
    <style>
        .result-screen {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 5rem;
            font-weight: bold;
            color: white;
        }

        .correct {
            background-color: #28a745;
        }

        .wrong {
            background-color: #dc3545;
        }
    </style>

@if($answer->round->type === 'numeric' || $answer->round->type === 'string')
    <div class="result-screen {{ $answer->is_correct ? 'correct' : 'wrong' }}">
        {{ $answer->is_correct ? 'CORRECT ANSWER!' : 'WRONG ANSWER!' }}
    </div>
@endif

    @if($answer->round->type === 'image_sequence')

    <h3 class="result-screen text-warning">
        {{ $answer->score }} dari {{ $totalImage }} gambar benar
    </h3>
@endif

    <script>
        setTimeout(function() {
            window.location.href = "/";
        }, 2000);
    </script>
@endsection
