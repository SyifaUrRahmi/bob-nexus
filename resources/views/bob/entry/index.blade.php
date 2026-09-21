@extends('layouts.main')
@section('container')
    <style>
        body {
            height: 100vh;
            overflow: hidden;
        }

        /* Left Section */
        .left-panel {
            /* background: #1e293b; */
            background: #5a70f9;
            color: white;
        }

        .round-number {
            font-size: 3rem;
            font-weight: bold;
        }

        .round-title {
            font-size: 1.3rem;
            opacity: 0.8;
        }

        /* Right Section */
        .top-section {
            /* background-color: #5a70f9; */
            background-color: #fbcd70;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: bold;
            letter-spacing: 2px;
            color: #ffffff;
        }

        .bottom-section {
            overflow-y: auto;
        }

        .bottom-section a {
            font-size: 2rem;
        }

        /* Circle Button */
        .participant-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            /* background-color: #0d6efd; */
            background-color: #dd3f66;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .participant-circle:hover {
            /* background-color: #0b5ed7; */
            /* background-color: #cfb10a; */
            background-color: #fbcd70;
            color: #ffffff;
            transform: scale(1.1);
        }
    </style>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-md-4 col-lg-3 left-panel d-flex flex-column justify-content-center align-items-center">
                <div class="text-center">
                    <div class="round-number">
                        Round {{ $round->number ?? 'No Active Round' }}
                    </div>
                    <div class="round-title mt-2">
                        {{ $round->title ?? '' }}
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-lg-9 d-flex flex-column p-0">
                <div class="top-section" style="height: 20%;">
                    SELECT PARTICIPANT NUMBER
                </div>
                <div class="bottom-section d-flex flex-wrap gap-3 justify-content-center align-content-start p-4"
                    style="height: 80%;">
                    @foreach ($participants as $participant)
                        <a href="{{ route('answer.show', [$participant->id, $round->id]) }}"
                            class="participant-circle text-decoration-none">
                            {{ $participant->queue_number }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
