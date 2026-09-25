@extends('dashboard.sidebar.main')

@section('container')
    <style id="5z9j3x">
        .highlight-new {
            background-color: #fff3cd;
            animation: fadeHighlight 2s forwards;
        }

        @keyframes fadeHighlight {
            from { background-color: #fff3cd; }
            to { background-color: transparent; }
        }

        .nav {
            --bs-nav-link-color: #000000;
            --bs-nav-link-hover-color: var(--primary-color);
        }

        .nav-tabs .nav-link.active {
            background-color: var(--primary-color);
            color: #ffffff;
        }

        /* Style Khusus Grid Monitoring Logic */
        .admin-board-card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .admin-q-box {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 8px 4px;
            text-align: center;
            background-color: #f8f9fa;
            min-height: 85px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: all 0.3s;
        }
        .q-num-title {
            font-weight: bold;
            font-size: 0.9rem;
            margin-bottom: 4px;
        }
        .status-solved {
            background-color: #d1e7dd !important;
            border-color: #198754 !important;
            color: #0f5132;
        }
        .status-wrong {
            background-color: #f8d7da !important;
            border-color: #dc3545 !important;
            color: #842029;
        }
    </style>

    <div class="flex-grow-1 p-4">
        <a href="/rounds" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <div class="d-flex justify-content-between mb-3">
            <div>
                <h4 style="color: var(--primary-color)">Round Details - {{ strtoupper($round->type) }}</h4>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- CONTROL PANEL SPATIAL --}}
        @if ($round->type === 'spatial')
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h5 class="mb-2">Spatial Control</h5>
                    
                    <div class="mb-3">
                        @if ($activeSetting)
                            <h3 class="fw-bold text-primary mb-1" id="sessionDisplay">
                                Soal Aktif: No. {{ $activeSetting->question_number }}
                            </h3>
                            <span class="badge bg-success">ACTIVE</span>
                        @else
                            <h5 class="text-muted">Belum ada soal yang aktif. Silakan pilih nomor soal di bawah.</h5>
                        @endif
                    </div>

                    {{-- GRID TOMBOL 12 SOAL (3x4) --}}
                    <div class="row justify-content-center g-2 mt-2" style="max-width: 500px; margin: 0 auto;">
                        @for ($q = 1; $q <= 12; $q++)
                            @php
                                $isActive = $activeSetting && $activeSetting->question_number == $q;
                            @endphp
                            <div class="col-3">
                                <button type="button" 
                                    class="btn {{ $isActive ? 'btn-success fw-bold shadow' : 'btn-outline-primary' }} w-100 py-2"
                                    onclick="setActiveQuestion({{ $q }})">
                                    {{ $q }}
                                </button>
                            </div>
                        @endfor
                    </div>

                </div>
            </div>
        @endif

        {{-- TAMPILAN KHUSUS LOGIC: MONITORING GRID PAPAN SOAL --}}
        @if ($round->type === 'logic')
            @php
                $questionsPerBoard = $round->questions_per_board ?? 10;
                $settingsCollection = $round->roundSettings ?? $round->roundsettings;
                $groupedSettings = $settingsCollection ? $settingsCollection->sortBy('question_number')->chunk($questionsPerBoard) : collect();
            @endphp

            <div class="row row-cols-1 row-cols-md-2 g-4 mb-4">
                @foreach ($groupedSettings as $boardIndex => $settings)
                    @php
                        $boardNum = $boardIndex + 1;
                        $firstQ = $settings->first()->question_number ?? 1;
                        $lastQ = $settings->last()->question_number ?? 10;
                    @endphp
                    <div class="col">
                        <div class="card admin-board-card h-100">
                            <div class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center py-2">
                                <span><i class="bi bi-grid-3x3-gap-fill me-2"></i> PAPAN {{ $boardNum }}</span>
                                <span class="badge bg-light text-primary">Soal #{{ $firstQ }} - #{{ $lastQ }}</span>
                            </div>
                            <div class="card-body p-3">
                                <div class="row row-cols-5 g-2">
                                   @foreach ($settings as $setting)
                                        <div class="col">
                                            <div id="q-box-{{ $setting->question_number }}" class="admin-q-box">
                                                <div class="q-num-title">#{{ $setting->question_number }}</div>
                                                <div id="q-status-{{ $setting->question_number }}" class="small text-muted">
                                                    @if($setting->answer && $setting->answer->participant)
                                                        <span class="text-success fw-bold">
                                                            {{ $setting->answer->participant->name }}
                                                        </span>
                                                    @else
                                                        Belum Dijawab
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- TABEL RIWAYAT JAWABAN (UNTUK SEMUA TIPE RONDE) --}}
        <div class="card">
            <div class="card-header bg-white fw-bold">
                Submission History
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">Queue Number</th>
                            <th class="text-center">Name</th>
                            @if($round->type === 'logic')
                                <th class="text-center">No. Soal</th>
                            @endif
                            @if($round->type !== 'image_sequence')
                                <th class="text-center">Submitted Answer</th>
                            @endif
                            <th class="text-center">Result</th>
                            <th class="text-center">Response Time</th>
                        </tr>
                    </thead>
                    <tbody id="answersTable">
                        <tr>
                            @php
                                $colspan = 5; // Default (contoh: spatial, image_sequence)
                                if($round->type === 'logic') $colspan = 6;
                                if($round->type === 'image_sequence') $colspan = 4;
                            @endphp
                            <td colspan="{{ $colspan }}" class="text-center text-muted">
                                Waiting for answers...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script id="3t6qj1">
let displayedIds = [];

function loadAnswers() {
    let url = `/round/{{ $round->id }}/answers`;

    @if ($round->type === 'spatial')
        const activeSettingId = "{{ $activeSetting->id ?? 0 }}";
        url += `?round_setting_id=${activeSettingId}`;
    @elseif ($round->type !== 'logic')
        const currentQuestion = "{{ $activeSetting->question_number ?? 0 }}";
        if (currentQuestion > 0) {
            url += `?question_number=${currentQuestion}`;
        }
    @endif

   fetch(url)
            .then(response => response.json())
            .then(data => {
                let table = document.getElementById('answersTable');
                
                // Hitung total kolom secara dinamis dengan Blade
                let totalCols = 5;
                @if($round->type === 'logic')
                    totalCols = 6;
                @elseif($round->type === 'image_sequence')
                    totalCols = 4;
                @endif

                if (data.length > 0 && table.querySelector(`td[colspan="${totalCols}"]`)) {
                    table.innerHTML = '';
                }

                data.forEach(answer => {
                    let queueNum = answer.participant ? answer.participant.queue_number : '-';
                    let name = answer.participant ? answer.participant.name : 'Unknown';
                    let qNum = answer.question_number || (answer.round_setting ? answer.round_setting.question_number : '-');
                    
                    // AMBIL TEKS JAWABAN (Sesuaikan 'answer_text' dengan database)
                    let textJawaban = answer.answer_text || answer.answer || '-'; 

                    @if ($round->type === 'logic')
                        if (qNum !== '-') {
                            const qBox = document.getElementById(`q-box-${qNum}`);
                            const qStatus = document.getElementById(`q-status-${qNum}`);

                            if (qBox && qStatus) {
                                if (answer.is_correct) {
                                    qBox.className = "admin-q-box status-solved";
                                    qStatus.innerHTML = `<span class="fw-bold text-success">✓ #${queueNum} ${name}</span>`;
                                } else if (!qBox.classList.contains('status-solved')) {
                                    qBox.className = "admin-q-box status-wrong";
                                    qStatus.innerHTML = `<span class="fw-bold text-danger">✗ #${queueNum} ${name}</span>`;
                                }
                            }
                        }
                    @endif

                    if (!displayedIds.includes(answer.id)) {
                        displayedIds.push(answer.id);

                        let row = document.createElement("tr");
                        row.classList.add("highlight-new");

                        let resultDisplay;
                        @if ($round->type === 'image_sequence')
                            resultDisplay = `<span class="badge bg-primary">Benar: ${answer.score}</span>`;
                        @else
                            resultDisplay = answer.is_correct 
                                ? '<span class="badge bg-success">Benar</span>'
                                : '<span class="badge bg-danger">Salah</span>';
                        @endif

                        let timeFormatted = new Date(answer.created_at).toLocaleTimeString('id-ID');

                        // Susun HTML Baris dengan mengecualikan jawaban jika tipenya image_sequence
                        row.innerHTML = `
                            <td class="text-center">${queueNum}</td>
                            <td class="text-center">${name}</td>
                            @if($round->type === 'logic')
                                <td class="text-center fw-bold">#${qNum}</td>
                            @endif
                            @if($round->type !== 'image_sequence')
                                <td class="text-center text-primary fw-bold">${textJawaban}</td>
                            @endif
                            <td class="text-center">${resultDisplay}</td>
                            <td class="text-center">${timeFormatted}</td>
                        `;
                        table.appendChild(row);
                    }
                });
            })
            .catch(error => console.error("Error loading answers:", error));
}

setInterval(loadAnswers, 1000);
loadAnswers();

// Fungsi Baru: Aktifkan nomor soal secara langsung saat diklik
// Fungsi: Aktifkan nomor soal secara langsung saat diklik
function setActiveQuestion(questionNumber) {
    fetch("/round/{{ $round->id }}/spatial-control", { // Sesuaikan URL dengan route controller Anda
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ question_number: questionNumber })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            location.reload();
        } else {
            alert(data.message || 'Gagal mengubah soal');
        }
    })
    .catch(err => console.error("Error setting active question:", err));
}
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection