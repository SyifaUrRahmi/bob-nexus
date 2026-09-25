<style>
/* Kontainer khusus agar HANYA bagian papan soal yang terscroll */
#logicalBoardScrollContainer {
    max-height: calc(100vh - 120px); /* Menyesuaikan dengan tinggi layar minus header/padding */
    overflow-y: auto;
    overflow-x: hidden;
    padding-right: 8px; /* Ruang untuk scrollbar */
}

/* Custom Scrollbar agar tampil lebih rapi dan modern */
#logicalBoardScrollContainer::-webkit-scrollbar {
    width: 8px;
}
#logicalBoardScrollContainer::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}
#logicalBoardScrollContainer::-webkit-scrollbar-thumb {
    background: #0d6efd;
    border-radius: 4px;
}
#logicalBoardScrollContainer::-webkit-scrollbar-thumb:hover {
    background: #0b5ed7;
}

.board-card {
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.question-btn {
    width: 100%;
    height: 50px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 8px;
    transition: all 0.2s;
}
/* Warna Status Soal */
.q-available {
    background-color: #f8f9fa;
    border: 2px solid #0d6efd;
    color: #0d6efd;
}
.q-available:hover {
    background-color: #0d6efd;
    color: white;
}
.q-solved-global {
    background-color: #198754 !important;
    border-color: #198754 !important;
    color: white !important;
    cursor: not-allowed;
    opacity: 0.85;
}
.q-wrong-self {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
    color: white !important;
    cursor: not-allowed;
    opacity: 0.85;
}
</style>

{{-- BUNGKUS DENGAN DIV KHUSUS SCROLL --}}
<div id="logicalBoardScrollContainer" class="container-fluid py-3">
    @php
        $questionsPerBoard = $round->questions_per_board ?? 10;
        $settingsCollection = $round->roundSettings ?? $round->roundsettings;
        $groupedSettings = $settingsCollection ? $settingsCollection->sortBy('question_number')->chunk($questionsPerBoard) : collect();
    @endphp

    <div id="boardsWrapper">
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($groupedSettings as $boardIndex => $settings)
                @php
                    $boardNum = $boardIndex + 1;
                    $firstQ = $settings->first()->question_number ?? 1;
                    $lastQ = $settings->last()->question_number ?? 10;
                @endphp
                <div class="col">
                    <div class="card board-card h-100">
                        <div class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center py-3">
                            <span class="fs-5"><i class="bi bi-grid-3x3-gap-fill me-2"></i> BOARD {{ $boardNum }}</span>
                            <span class="badge bg-light text-primary fs-6">Question {{ $firstQ }} - {{ $lastQ }}</span>
                        </div>
                        <div class="card-body p-3">
                            <div class="row row-cols-5 g-2">
                                @foreach ($settings as $setting)
                                    <div class="col">
                                        <button 
                                            type="button" 
                                            id="btn-q-{{ $setting->question_number }}"
                                            class="btn question-btn q-available"
                                            onclick="openAnswerModal({{ $setting->question_number }}, {{ $setting->id }})">
                                            {{ $setting->question_number }}
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- MODAL POP-UP INPUT JAWABAN --}}
<div class="modal fade" id="answerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Enter Answer - Question <span id="modalQNum">1</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="logicalAnswerForm" action="{{ route('answer.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 text-center">
                    <input type="hidden" name="round_id" value="{{ $round->id }}">
                    <input type="hidden" name="participant_id" value="{{ $participant->id }}">
                    <input type="hidden" name="question_number" id="modalQuestionNumInput">
                    <input type="hidden" name="round_setting_id" id="modalRoundSettingIdInput">

                    <!-- <label class="form-label fw-bold text-muted mb-2">MASUKKAN JAWABAN ANDA:</label> -->
                    <input 
                        type="text" 
                        name="answer" 
                        id="modalAnswerInput" 
                        class="form-control form-control-lg text-center fw-bold fs-3 text-uppercase" 
                        placeholder="Answer..." 
                        required 
                        autocomplete="off">
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-success px-5 fw-bold">SUBMIT</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Fungsi Pembuka Modal (Global)
window.openAnswerModal = function(qNum, settingId) {
    document.getElementById('modalQNum').innerText = '#' + qNum;
    document.getElementById('modalQuestionNumInput').value = qNum;
    document.getElementById('modalRoundSettingIdInput').value = settingId;
    document.getElementById('modalAnswerInput').value = '';

    const modalEl = document.getElementById('answerModal');
    let modalObj = bootstrap.Modal.getInstance(modalEl);
    if (!modalObj) {
        modalObj = new bootstrap.Modal(modalEl);
    }
    modalObj.show();
    setTimeout(() => document.getElementById('modalAnswerInput').focus(), 400);
};

// Fungsi Fetch Status Soal (Global)
window.fetchQuestionStatuses = function() {
    const activeParticipantId = "{{ $participant->id }}";
    fetch(`/round/{{ $round->id }}/logical-statuses?participant_id=${activeParticipantId}`)
        .then(res => res.json())
        .then(data => {
            document.querySelectorAll('.question-btn').forEach(btn => {
                const qNum = btn.id.replace('btn-q-', '');
                btn.className = "btn question-btn q-available";
                btn.innerText = `#${qNum}`;
                btn.disabled = false;
            });

            if (data.global_correct_questions) {
                data.global_correct_questions.forEach(qNum => {
                    const btn = document.getElementById(`btn-q-${qNum}`);
                    if (btn) {
                        btn.className = "btn question-btn q-solved-global";
                        btn.innerText = `#${qNum} ✓`;
                        btn.disabled = true;
                    }
                });
            }

            if (data.self_wrong_questions) {
                data.self_wrong_questions.forEach(qNum => {
                    const btn = document.getElementById(`btn-q-${qNum}`);
                    if (btn) {
                        btn.className = "btn question-btn q-wrong-self";
                        btn.innerText = `#${qNum} ✗`;
                        btn.disabled = true;
                    }
                });
            }
        })
        .catch(err => console.error("Error fetching statuses:", err));
};

// Event Listener Submit Form
document.addEventListener('submit', async function(e) {
    if (e.target && e.target.id === 'logicalAnswerForm') {
        e.preventDefault();

        const formData = new FormData(e.target);

        try {
            const response = await fetch("{{ route('answer.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: formData
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Terjadi kesalahan pada server.');
            }

            const modalEl = document.getElementById('answerModal');
            const modalObj = bootstrap.Modal.getInstance(modalEl);
            if (modalObj) {
                modalObj.hide();
            }

            window.fetchQuestionStatuses();

            // ==========================================
            // LOGIKA BENAR / SALAH BESERTA AUDIO
            // ==========================================
            if (data.is_correct) {
                // 1. Putar Audio Benar
                let audioBenar = new Audio("{{ asset('audio/correct_answer.mp4') }}");
                audioBenar.play().catch(err => console.log("Audio diblokir:", err));

                // 2. Tampilkan SweetAlert Benar
                Swal.fire({
                    icon: 'success',
                    title: 'CORRECT ANSWER!',
                    text: 'This question has been successfully solved.',
                    timer: 5000, 
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = "/"; // Redirect ke halaman awal
                });
            } else {
                // 1. Putar Audio Salah
                let audioSalah = new Audio("{{ asset('audio/wrong_answer.mp4') }}");
                audioSalah.play().catch(err => console.log("Audio diblokir:", err));

                // 2. Tampilkan SweetAlert Salah
                Swal.fire({
                    icon: 'error',
                    title: 'WRONG ANSWER!',
                    text: 'You are no longer allowed to answer this question.',
                    timer: 5000, 
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = "/"; // Redirect ke halaman awal
                });
            }

        } catch (error) {
            console.error('ERROR SUBMIT:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyimpan',
                text: error.message
            });
        }
    }
});

// Jalankan pemuatan status saat pertama kali Blade di-include
window.fetchQuestionStatuses();
</script>