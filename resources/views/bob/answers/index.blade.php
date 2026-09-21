@extends('layouts.main')
@section('container')
    <style>
        body {
            height: 100vh;
            overflow: hidden;
        }

        .color-list {
  color: #8f9cf9;
  color: #98ee5e;
  color: #de89f1;
  color: #dd3f66;
  color: #fbcd70;
  color: #5a70f9;
  color: #93a1ff;
  color: #ff6b22
}

        .left-panel {
            background: #5a70f9;;
            
            color: white;
        }

        .round-number {
            font-size: 2rem;
            font-weight: bold;
        }

        .round-title {
            font-size: 1.2rem;
            opacity: 0.8;
        }

        .participant-info {
            margin-top: 30px;
            font-size: 1.1rem;
        }

        .top-section {
            /* background: #f8f9fa; */
            background: #fbcd70;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .answer-display {
            height: 80px;
            font-size: 2rem;
            text-align: right;
        }

        .keypad-wrapper {
            display: grid;
            grid-template-columns: repeat(3, 100px);
            gap: 20px;
            justify-content: center;
        }

        .keypad-circle {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background-color: #FDDA09;
            color: white;
            font-size: 1.8rem;
            font-weight: bold;
            border: none;
            transition: 0.3s;
        }

        .keypad-circle:hover {
            background-color: #cfb10a;
            transform: scale(1.1);
        }

        .action-btn {
            height: 70px;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .answer-display {
            height: 80px;
            font-size: 2rem;
            text-align: right;
        }

        .keypad-btn {
            height: 70px;
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>

    <div class="container-fluid h-100">
        <div class="row h-100">

            <!-- LEFT 30% -->
            <div class="col-lg-2 left-panel d-flex flex-column justify-content-center align-items-center text-center">

                <div class="round-number">
                    ROUND {{ $round->number }}
                </div>

                <div class="round-title mt-2">
                    {{ $round->title }}
                </div>

                <div class="participant-info">
                    <hr class="bg-light">
                    <div>Queue Number: {{ $participant->queue_number }}</div>
                    <div>{{ $participant->name }}</div>
                </div>

            </div>

            <!-- RIGHT 70% -->
            <div class="col-lg-10 d-flex flex-column p-0">

                <!-- TOP 20% -->
                <div class="top-section" style="height:15%;">
                    ENTER YOUR ANSWER
                </div>

                <!-- BOTTOM 80% -->
                <div class="d-flex p-4" style="height:85%;">

                    @if ($round->type == 'numeric')
                        @include('bob.answers.partials.numeric')

                    @elseif($round->type == 'image_sequence')
                        @include('bob.answers.partials.image_sequence')

                    @elseif($round->type == 'logic')
                        @include('bob.answers.partials.logic')

                    @elseif($round->type == 'spatial')
                        @include('bob.answers.partials.spatial')

                    @elseif($round->type == 'string')
                        @include('bob.answers.partials.string')
                    @endif

                </div>

            </div>

        </div>
    </div>

    <script>
        function addNumber(num) {
            document.getElementById('answerInput').value += num;
        }

        function deleteNumber() {
            let input = document.getElementById('answerInput');
            input.value = input.value.slice(0, -1);
        }
    </script>
@endsection
