<style>
    .key-btn {
    min-width: 38px;
    height: 48px;
    font-weight: bold;
    font-size: 1.1rem;
    flex: 1;
    max-width: 50px;
}
</style>
<!-- Notifikasi Alert dari Session Controller -->
@if (session('error'))
    <div class="alert alert-danger text-center fw-bold mb-3">
        {{ session('error') }}
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning text-center fw-bold mb-3">
        {{ session('warning') }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success text-center fw-bold mb-3">
        {{ session('success') }}
    </div>
@endif

@php
    $attemptsCount = \App\Models\Answer::where('participant_id', $participant->id)
        ->where('round_id', $round->id)
        ->count();
    $isMaxed = $attemptsCount >= 3;
    
    // Layout QWERTY Keyboard
    $row1 = str_split('QWERTYUIOP');
    $row2 = str_split('ASDFGHJKL');
    $row3 = str_split('ZXCVBNM');
@endphp

<form action="{{ route('answer.store') }}" method="POST" class="d-flex flex-column flex-md-row w-100 gap-4">
    @csrf

    <input type="hidden" name="participant_id" value="{{ $participant->id }}">
    <input type="hidden" name="round_id" value="{{ $round->id }}">

    <!-- KEYBOARD STRING (QWERTY) -->
    <div class="col-12 col-md-7 d-flex justify-content-center align-items-center">
        <div class="keyboard-wrapper w-100 d-flex flex-column gap-2 align-items-center">
            
            <!-- Baris 1: QWERTYUIOP -->
            <div class="d-flex gap-1 justify-content-center w-100">
                @foreach ($row1 as $char)
                    <button type="button" class="btn btn-outline-dark key-btn" 
                        onclick="addChar('{{ $char }}')" 
                        {{ $isMaxed ? 'disabled' : '' }}>
                        {{ $char }}
                    </button>
                @endforeach
            </div>

            <!-- Baris 2: ASDFGHJKL -->
            <div class="d-flex gap-1 justify-content-center w-100">
                @foreach ($row2 as $char)
                    <button type="button" class="btn btn-outline-dark key-btn" 
                        onclick="addChar('{{ $char }}')" 
                        {{ $isMaxed ? 'disabled' : '' }}>
                        {{ $char }}
                    </button>
                @endforeach
            </div>

            <!-- Baris 3: ZXCVBNM + BACKSPACE -->
            <div class="d-flex gap-1 justify-content-center w-100">
                @foreach ($row3 as $char)
                    <button type="button" class="btn btn-outline-dark key-btn" 
                        onclick="addChar('{{ $char }}')" 
                        {{ $isMaxed ? 'disabled' : '' }}>
                        {{ $char }}
                    </button>
                @endforeach
                <button type="button" class="btn btn-warning key-btn px-3" 
                    onclick="deleteChar()" 
                    {{ $isMaxed ? 'disabled' : '' }}>
                    ⌫
                </button>
            </div>

            <!-- Baris 4: SPACE -->
            <div class="d-flex gap-1 justify-content-center w-100 mt-1">
                <button type="button" class="btn btn-secondary w-50" 
                    onclick="addChar(' ')" 
                    {{ $isMaxed ? 'disabled' : '' }}>
                    SPACE
                </button>
            </div>

        </div>
    </div>

    <!-- ANSWER PANEL -->
    <div class="col-12 col-md-5 d-flex flex-column justify-content-center px-3">
        <!-- Informasi Sisa Kesempatan -->
        <div class="text-center mb-3">
            @if ($isMaxed)
                <span class="badge bg-danger fs-6">Kesempatan Menjawab Sudah Habis (3/3)</span>
            @else
                <span class="badge bg-info text-dark fs-6">Percobaan ke-{{ $attemptsCount + 1 }} dari 3</span>
            @endif
        </div>

        <!-- Input jawaban (Tanpa readonly agar peserta bisa pakai keyboard fisik) -->
        <input type="text" id="answerInput" name="answer" 
            class="form-control answer-display mb-4 text-uppercase fw-bold text-center fs-4" 
            placeholder="Ketik jawaban..." 
            autocomplete="off"
            required {{ $isMaxed ? 'disabled' : '' }}>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-success action-btn fw-bold" 
                {{ $isMaxed ? 'disabled' : '' }}>
                SUBMIT
            </button>
        </div>
    </div>
</form>

<script>
    const isMaxed = {{ $isMaxed ? 'true' : 'false' }};

    function addChar(value) {
        if (isMaxed) return;
        const input = document.getElementById('answerInput');
        input.value += value;
        input.focus();
    }

    function deleteChar() {
        if (isMaxed) return;
        const input = document.getElementById('answerInput');
        input.value = input.value.slice(0, -1);
        input.focus();
    }
</script>