@extends('layouts.main')
@section('container')
    <style>
        body {
            height: 100vh;
            overflow: hidden;
        }

        .left-panel {
            background: #c3e6db;
            color: #660033;
        }

        /* CSS untuk Gambar di Panel Kiri (Atas dan Bawah) */
        .left-panel-img {
            max-width: 100px; /* Silakan atur lebar gambar di sini */
            height: auto;
            object-fit: contain;
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
            background-color: #fff4bd;
            color: #660033;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px; /* Jarak antara teks dan gambar di sampingnya */
            font-size: 1.8rem;
            font-weight: bold;
        }

        /* CSS untuk Gambar di Panel Atas (Samping Kiri dan Kanan) */
        .top-section-img {
            height: 90px; /* Atur tinggi gambar di sini */
            width: auto;
            object-fit: contain;
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
            background-color: #f4c7d3;
            color: #660033;
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

                <!-- 1. GAMBAR POSISI ATAS -->
                <img src="{{ asset('images/gambar3.png') }}" alt="Logo Atas" class="left-panel-img mb-4">

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

                <!-- 2. GAMBAR POSISI BAWAH -->
                <img src="{{ asset('images/gambar1.png') }}" alt="Logo Bawah" class="left-panel-img mt-4">

            </div>

            <!-- RIGHT 70% -->
            <div class="col-lg-10 d-flex flex-column p-0">

                <!-- TOP 20% -->
                <!-- GAMBAR POSISI SAMPING KIRI DAN KANAN TEKS -->
                <div class="top-section" style="height:15%;">
                    <img src="{{ asset('images/gambar7.png') }}" alt="Icon Kiri" class="top-section-img">
                    <img src="{{ asset('images/gambar2.png') }}" alt="Icon Kiri" class="top-section-img">
                    <img src="{{ asset('images/gambar3.png') }}" alt="Icon Kiri" class="top-section-img">
                    <span>ENTER YOUR ANSWER</span>
                    <img src="{{ asset('images/gambar4.png') }}" alt="Icon Kanan" class="top-section-img">
                    <img src="{{ asset('images/gambar5.png') }}" alt="Icon Kanan" class="top-section-img">
                    <img src="{{ asset('images/gambar6.png') }}" alt="Icon Kanan" class="top-section-img">
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