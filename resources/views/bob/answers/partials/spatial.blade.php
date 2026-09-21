<style>
/* Style Keypad & Display */
.answer-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
}

.answer-btn {
    padding: 15px;
    font-size: 20px;
    font-weight: bold;
    border: 2px solid #ddd;
    background: white;
    border-radius: 10px;
    cursor: pointer;
}

.answer-btn:hover {
    background: #f5f5f5;
}

.submit-btn {
    width: 100%;
    padding: 12px;
    font-size: 18px;
    font-weight: bold;
}

/* Style Pop-up Overlay */
.popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.popup-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    max-width: 420px;
    width: 90%;
    animation: popupAnimation 0.3s ease-out forwards;
}

@keyframes popupAnimation {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>

<!-- POP-UP JAWABAN TERSIMPAN -->
<div id="popupSaved" class="popup-overlay d-none">
    <div class="popup-card text-center p-4">
        <div class="mb-3">
            <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle" style="width: 70px; height: 70px; font-size: 35px;">
                ✓
            </div>
        </div>
        <h4 class="fw-bold text-dark mb-2">Jawaban Tersimpan!</h4>
        <p class="text-muted mb-0">Silakan tunggu soal berikutnya...</p>
    </div>
</div>

<!-- FORM SOAL SPATIAL -->
<form id="spatialForm" onsubmit="submitAnswer(event)">
    @csrf
    <input type="hidden" name="participant_id" value="{{ $participant->id }}">
    <input type="hidden" name="round_id" value="{{ $round->id }}">
    
    {{-- Input hidden ID round_setting yang aktif --}}
    <input type="hidden" name="round_setting_id" id="activeSettingId" value="{{ $roundSetting->id }}">

    <div class="text-center mb-4">
        <h3 class="fw-bold">
            Soal Nomor {{ $roundSetting->question_number }}
        </h3>
    </div>

    <div class="row" style="min-height: 50vh;">
        {{-- KEYPAD --}}
        <div class="col-6 d-flex justify-content-center align-items-center">
            <div class="keypad-wrapper">
                @for ($i = 1; $i <= 9; $i++)
                    <button type="button" class="keypad-circle input-control" onclick="addNumber('{{ $i }}')">{{ $i }}</button>
                @endfor
                <div></div>
                <button type="button" class="keypad-circle input-control" onclick="addNumber('0')">0</button>
                <div></div>
            </div>
        </div>

        {{-- PANEL JAWABAN --}}
        <div class="col-6 d-flex flex-column justify-content-center px-5">
            <input type="text" id="answerInput" name="answer" class="form-control answer-display mb-4" readonly required>
            <div class="d-grid gap-3">
                <button type="button" class="btn btn-warning action-btn input-control" onclick="deleteNumber()">DELETE</button>
                <button type="submit" id="submitBtn" class="btn btn-success action-btn input-control">SUBMIT</button>
            </div>
        </div>
    </div>
</form>

<script>
let isSubmitted = false;

function addNumber(number) {
    if (isSubmitted) return;
    const input = document.getElementById('answerInput');
    input.value += number;
}

function deleteNumber() {
    if (isSubmitted) return;
    const input = document.getElementById('answerInput');
    input.value = input.value.slice(0, -1);
}

function submitAnswer(e) {
    e.preventDefault();
    if (isSubmitted) return;

    const form = document.getElementById('spatialForm');
    const formData = new FormData(form);

    // Kunci tombol agar tidak bisa klik double submit
    isSubmitted = true;
    document.querySelectorAll('.input-control').forEach(el => el.disabled = true);

    fetch("/spatial/submit-answer", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        // Tampilkan Pop-Up tanpa memberitahu Benar/Salah
        document.getElementById('popupSaved').classList.remove('d-none');
    })
    .catch(err => {
        console.error("Error submitting answer:", err);
        isSubmitted = false;
        document.querySelectorAll('.input-control').forEach(el => el.disabled = false);
    });
}

// Polling: Mengecek apakah Admin sudah mengganti ke soal berikutnya
setInterval(() => {
    fetch("/round/{{ $round->id }}/active-question")
        .then(res => res.json())
        .then(data => {
            const currentSettingId = document.getElementById('activeSettingId').value;
            
            // Jika ID soal aktif di database sudah berganti:
            if (data.round_setting_id && data.round_setting_id != currentSettingId) {
                // Reload halaman: Pop-up otomatis hilang & soal baru langsung muncul
                location.reload(); 
            }
        })
        .catch(err => console.error("Error polling active question:", err));
}, 1500);
</script>