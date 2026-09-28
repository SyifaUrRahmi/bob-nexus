@extends('layouts.main')
@section('container')
    <style>
        body {
            height: 100vh;
            overflow: hidden;
        }

        /* Left Section */
        .left-panel {
            background: #c3e6db;
            color: #660033;
        }

        .round-number {
            font-size: 3rem;
            font-weight: bold;
        }

        .round-title {
            font-size: 1.3rem;
            /* opacity: 0.8; */
        }

        /* Tambahan CSS untuk gambar di panel kiri */
        .left-panel-img {
            max-width: 150px; /* Silakan sesuaikan ukurannya */
            height: auto;
            margin: 20px 0; /* Memberi jarak atas dan bawah */
        }

        /* Right Section */
        .top-section {
            background-color: #fff4bd;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 50px; /* Memberi jarak antara gambar dan teks */
            font-size: 1.8rem;
            font-weight: bold;
            letter-spacing: 2px;
            color: #660033;
        }

        /* Tambahan CSS untuk gambar di top section */
        .top-section-img {
            max-height: 130px; /* Silakan sesuaikan tingginya */
            width: auto;
            object-fit: contain;
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
            background-color: #f4c7d3;
            color: #660033;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .participant-circle:hover {
            background-color: #fbcd70;
            color: #ffffff;
            transform: scale(1.1);
        }

        /* CSS untuk deretan gambar di bawah */
        .image-footer {
            height: 30%; 
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px; 
            padding-bottom: 50px;
            background-color: transparent; 
        }

        .image-footer img {
            height: 100%;
            max-height: 250px; 
            width: auto;      
            object-fit: contain;
        }
    </style>

    <div class="container-fluid h-100">
        <div class="row h-100">
            <!-- Panel Kiri -->
            <div class="col-md-4 col-lg-3 left-panel d-flex flex-column justify-content-center align-items-center">
                <div class="text-center">
                    
                    <!-- Gambar Atas di Panel Kiri -->
                    <img src="{{ asset('images/gambar1.png') }}" alt="Logo Atas" class="left-panel-img">

                    <div class="round-number">
                        Round {{ $round->number ?? 'No Active Round' }}
                    </div>
                    <div class="round-title mt-2">
                        {{ $round->title ?? '' }}
                    </div>

                    <!-- Gambar Bawah di Panel Kiri -->
                    <img src="{{ asset('images/gambar5.png') }}" alt="Logo Bawah" class="left-panel-img">

                </div>
            </div>
            
            <!-- Panel Kanan -->
            <div class="col-md-8 col-lg-9 d-flex flex-column p-0">
                
                <!-- Judul Atas (20%) -->
                <div class="top-section" style="height: 20%;">
                    <!-- Gambar di samping teks -->
                    <img src="{{ asset('images/gambar4.png') }}" alt="Icon Select" class="top-section-img" width="1000">
                    <span>SELECT PARTICIPANT NUMBER</span>
                    <img src="{{ asset('images/gambar6.png') }}" alt="Icon Select" class="top-section-img" width="900">
                </div>
                
                <!-- Nomor Peserta (Sisa ruang / flex-grow) -->
                <div class="bottom-section d-flex flex-wrap gap-3 justify-content-center align-content-start p-4"
                    style="flex-grow: 1;">
                    @foreach ($participants as $participant)
                        <a href="{{ route('answer.show', [$participant->id, $round->id]) }}"
                            class="participant-circle text-decoration-none">
                            {{ $participant->queue_number }}
                        </a>
                    @endforeach
                </div>

                <!-- Deretan Gambar di Paling Bawah (15%) -->
                <div class="image-footer">
                    <img src="{{ asset('images/gambar1.png') }}" alt="Image 1">
                    <img src="{{ asset('images/gambar2.png') }}" alt="Image 2">
                    <img src="{{ asset('images/gambar3.png') }}" alt="Image 3">
                    <img src="{{ asset('images/gambar4.png') }}" alt="Image 4">
                    <img src="{{ asset('images/gambar5.png') }}" alt="Image 5">
                    <img src="{{ asset('images/gambar6.png') }}" alt="Image 6">
                    <img src="{{ asset('images/gambar7.png') }}" alt="Image 7">
                </div>

            </div>
        </div>
    </div>
@endsection