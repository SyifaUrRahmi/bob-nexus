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
    // Menghitung jumlah attempt yang sudah dilakukan peserta di ronde ini
    $attemptsCount = \App\Models\Answer::where('participant_id', $participant->id)
        ->where('round_id', $round->id)
        ->count();
    $isMaxed = $attemptsCount >= 3;
@endphp

<form action="{{ route('answer.store') }}" method="POST" class="d-flex w-100">
    @csrf

    <input type="hidden" name="participant_id" value="{{ $participant->id }}">
    <input type="hidden" name="round_id" value="{{ $round->id }}">

    <!-- KEYPAD -->
    <div class="col-6 d-flex justify-content-center align-items-center">
        <div class="keypad-wrapper">

            @for ($i = 1; $i <= 9; $i++)
                <button type="button" class="keypad-circle" 
                    onclick="addNumber('{{ $i }}')" 
                    {{ $isMaxed ? 'disabled' : '' }} style="background: #dd3f66;">
                    {{ $i }}
                </button>
            @endfor

            <div></div>

            <button type="button" class="keypad-circle" 
                onclick="addNumber('0')" 
                {{ $isMaxed ? 'disabled' : '' }} style="background: #dd3f66;">
                0
            </button>

            <div></div>

        </div>
    </div>

    <!-- ANSWER PANEL -->
    <div class="col-6 d-flex flex-column justify-content-center px-5">
            <!-- Pesan Informasi Sisa Kesempatan -->
<div class="text-center mb-3">
    @if ($isMaxed)
        <span class="badge fs-6"  style="background: #dd3f66;">Kesempatan Menjawab Sudah Habis (3/3)</span>
    @else
        <span class="badge fs-6" style="background: #5a70f9;">Percobaan ke-{{ $attemptsCount + 1 }} dari 3</span>
    @endif
</div>

        <input type="text" id="answerInput" name="answer" class="form-control answer-display mb-4" readonly required {{ $isMaxed ? 'disabled' : '' }}>

        <div class="d-grid gap-3">
            <button type="button" class="btn btn-warning action-btn" 
                onclick="deleteNumber()" 
                {{ $isMaxed ? 'disabled' : '' }}>
                DELETE
            </button>

            <button type="submit" class="btn btn-success action-btn" 
                {{ $isMaxed ? 'disabled' : '' }}>
                SUBMIT
            </button>
        </div>

    </div>

</form>
<script>
    const isMaxed = {{ $isMaxed ? 'true' : 'false' }};

    function addNumber(value) {
        if (isMaxed) return;
        const input = document.getElementById('answerInput');
        input.value += value;
    }

    function deleteNumber() {
        if (isMaxed) return;
        const input = document.getElementById('answerInput');
        input.value = input.value.slice(0, -1);
    }
</script>