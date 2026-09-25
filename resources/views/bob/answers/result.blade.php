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
            cursor: pointer;
        }

        .correct {
            background-color: #28a745;
        }

        .wrong {
            background-color: #dc3545;
        }
    </style>

    @if($answer->round->type === 'numeric' || $answer->round->type === 'string')
        <div class="result-screen {{ $answer->is_correct ? 'correct' : 'wrong' }}" id="screen-result">
            {{ $answer->is_correct ? 'CORRECT ANSWER!' : 'WRONG ANSWER!' }}
        </div>
    @endif

    @if($answer->round->type === 'image_sequence')
        <h3 class="result-screen text-warning" id="screen-result">
            {{ $answer->score }} out of {{ $totalImage }} images are correct
        </h3>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // 1. Tentukan URL Audio dari file lokal (folder public)
            let audioUrl = "";

            @if($answer->round->type === 'numeric' || $answer->round->type === 'string')
                // Menggunakan fungsi asset() Laravel untuk memanggil file di folder public
                audioUrl = "{{ $answer->is_correct ? asset('audio/correct_answer.mp4') : asset('audio/wrong_answer.mp4') }}";
            @elseif($answer->round->type === 'image_sequence')
                // Audio khusus untuk image_sequence (hanya 1 audio untuk semua kondisi)
                audioUrl = "{{ asset('audio/answers_saved.mp4') }}";
            @endif

            // 2. Fungsi untuk memutar suara
            function playSound() {
                if (audioUrl !== "") {
                    let audio = new Audio(audioUrl);
                    
                    audio.play().then(() => {
                        console.log("Audio lokal berhasil diputar.");
                    }).catch((error) => {
                        console.log("Autoplay diblokir oleh browser. Menunggu interaksi user (klik layar)...");
                    });
                }
            }

            // Jalankan suara otomatis
            playSound();

            // Membantu bypass blokir browser jika user mengklik layar
            document.body.addEventListener('click', playSound, { once: true });

            // 3. Pindah halaman setelah 4 detik agar suara selesai diputar
            setTimeout(function() {
                window.location.href = "/";
            }, 5000); 
        });
    </script>
@endsection