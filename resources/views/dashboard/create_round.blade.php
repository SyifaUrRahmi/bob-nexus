@extends('dashboard.sidebar.main')

@section('container')
    <div class="flex-grow-1 p-4">
        <a href="/rounds" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <h4 class="my-3" style="color: var(--primary-color)">Add Round</h4>
        
        <form id="roundForm" action="/rounds" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="rounded p-5" style="border: 1px solid #ccc;">
                
                <div class="row">
                    <div class="col-2">
                        <label for="number">Round</label>
                    </div>
                    <div class="col-1 text-end">:</div>
                    <div class="col-6">
                        <input type="number" name="number" id="number"
                            class="form-control @error('number') is-invalid @enderror" required
                            value="{{ old('number') }}">
                        @error('number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-2">
                        <label for="title">Title</label>
                    </div>
                    <div class="col-1 text-end">:</div>
                    <div class="col-6">
                        <input type="text" name="title" id="title"
                            class="form-control @error('title') is-invalid @enderror" required 
                            value="{{ old('title') }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-2">
                        <label for="segment">Segment</label>
                    </div>
                    <div class="col-1 text-end">:</div>
                    <div class="col-6">
                        <input type="number" name="segment" id="segment"
                            class="form-control @error('segment') is-invalid @enderror" required 
                            value="{{ old('segment') }}">
                        @error('segment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-2">
                        <label for="type">Type</label>
                    </div>
                    <div class="col-1 text-end">:</div>
                    <div class="col-6">
                        <select name="type" id="type" class="form-control" required>
                            <option value="">Select Type</option>
                            <option value="numeric">Numeric</option>
                            <option value="image_sequence">Image Sequence</option>
                            <option value="spatial">Spatial</option>
                            <option value="logic">Logic</option>
                            <option value="string">String</option>
                        </select>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-2">
                        <label>Correct Answer</label>
                    </div>
                    <div class="col-1 text-end">:</div>
                    <div class="col-6" id="answerContainer"></div>
                    <div id="memoryError" class="text-danger mb-2"></div>
                </div>

            </div>

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary px-4 py-2 shadow mt-3">
                    <i class="bi bi-save"></i> Save
                </button>
            </div>
        </form>
    </div>

    <script>
    const type = document.getElementById('type');
    const container = document.getElementById('answerContainer');
    const form = document.getElementById('roundForm');
    const memoryError = document.getElementById('memoryError');

    type.addEventListener('change', renderInput);

    form.addEventListener('submit', function(e) {
        if (type.value === 'image_sequence') {
            const inputs = document.querySelectorAll('.image-input');
            let empty = [];

            inputs.forEach(input => {
                if (input.files.length === 0) {
                    empty.push(input.name);
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (empty.length > 0) {
                e.preventDefault();
                memoryError.innerHTML = `Semua kotak gambar harus diisi. Masih ada ${empty.length} gambar yang kosong.`;
                return false;
            }
        }
    });

    function renderInput() {
        memoryError.innerHTML = ''; // Reset pesan error saat ganti tipe

        if (type.value === 'numeric') {
            container.innerHTML = `
                <input
                    type="number"
                    name="correct_answer"
                    class="form-control"
                    placeholder="Masukkan jawaban benar (angka)"
                    required>
            `;
        } else if (type.value === 'string') {
            container.innerHTML = `
                <input
                    type="text"
                    name="correct_answer"
                    class="form-control"
                    placeholder="Masukkan jawaban benar (teks/string)"
                    required>
            `;
        } else if (type.value === 'image_sequence') {
            let cols = ['A', 'B', 'C', 'D', 'E', 'F'];
            let html = `
                <div class="table-responsive">
                    <table class="table table-bordered table-sm text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="50"></th>
            `;

            cols.forEach(col => { html += `<th>${col}</th>`; });

            html += `
                            </tr>
                        </thead>
                        <tbody>
            `;

            for (let row = 1; row <= 5; row++) {
                html += `<tr><th>${row}</th>`;
                cols.forEach(col => {
                    html += `
                        <td style="width:120px;height:120px">
                            <div class="mb-2">
                                <img id="preview-${row}-${col}" src="" style="width:80px;height:80px;object-fit:cover;display:none;border-radius:8px;">
                            </div>
                            <input type="file" class="form-control image-input" name="memory[${row}][${col}]" accept="image/*" required data-preview="preview-${row}-${col}">
                        </td>
                    `;
                });
                html += `</tr>`;
            }

            html += `
                        </tbody>
                    </table>
                </div>
            `;
            container.innerHTML = html;

            document.querySelectorAll('.image-input').forEach(input => {
                input.addEventListener('change', function () {
                    const file = this.files[0];
                    const preview = document.getElementById(this.dataset.preview);
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            preview.src = e.target.result;
                            preview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });

        } else if (type.value === 'spatial') {
            let html = `
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <strong>Kunci Jawaban Spatial (12 Soal)</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
            `;

            for (let question = 1; question <= 12; question++) {
                html += `
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Soal ${question}</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-box"></i></span>
                            <input type="number" name="correct_answers[${question}]" class="form-control" min="0" placeholder="Jumlah Kubus" required>
                        </div>
                    </div>
                `;
            }

            html += `
                        </div>
                    </div>
                </div>
            `;
            container.innerHTML = html;

        } else if (type.value === 'logic') {
            container.innerHTML = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Jumlah Papan</label>
                        <input type="number" id="boardCount" name="board_count" class="form-control" min="1" max="20" value="5" placeholder="Contoh: 5 papan" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Jumlah Soal per Papan</label>
                        <input type="number" id="questionsPerBoard" name="questions_per_board" class="form-control" min="1" max="50" value="10" placeholder="Contoh: 10 soal" required>
                    </div>
                </div>
                <div id="logicalBoardContainer"></div>
            `;

            const boardInput = document.getElementById('boardCount');
            const questionsInput = document.getElementById('questionsPerBoard');
            const boardContainer = document.getElementById('logicalBoardContainer');

            function generateBoardGrid() {
                const boards = parseInt(boardInput.value) || 0;
                const qPerBoard = parseInt(questionsInput.value) || 0;

                boardContainer.innerHTML = '';
                if (boards <= 0 || qPerBoard <= 0) return;

                let globalQuestionNumber = 1;

                for (let b = 1; b <= boards; b++) {
                    const startNum = globalQuestionNumber;
                    const endNum = globalQuestionNumber + qPerBoard - 1;

                    let html = `
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <strong class="fs-6"><i class="bi bi-grid-3x3-gap-fill me-1"></i> Papan ${b}</strong>
                                <span class="badge bg-light text-primary">Soal ${startNum} - ${endNum}</span>
                            </div>
                            <div class="card-body">
                                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 row-cols-lg-10 g-2">
                    `;

                    for (let q = 1; q <= qPerBoard; q++) {
                        html += `
                            <div class="col">
                                <div class="card text-center border shadow-sm">
                                    <div class="card-header p-1 bg-secondary text-white fw-bold" style="font-size: 11px;">
                                        #${globalQuestionNumber}
                                    </div>
                                    <div class="card-body p-1">
                                        <input type="text" name="logic_answers[${globalQuestionNumber}]" class="form-control text-center p-1 fw-bold" placeholder="Jwb" style="font-size: 13px; text-transform: uppercase;" required>
                                    </div>
                                </div>
                            </div>
                        `;
                        globalQuestionNumber++;
                    }

                    html += `
                                </div>
                            </div>
                        </div>
                    `;
                    boardContainer.innerHTML += html;
                }
            }

            generateBoardGrid();
            boardInput.addEventListener('input', generateBoardGrid);
            questionsInput.addEventListener('input', generateBoardGrid);

        } else {
            container.innerHTML = '';
        }
    }

    // Jalankan awal
    renderInput();
    </script>
@endsection