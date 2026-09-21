@extends('dashboard.sidebar.main')

@section('container')
    <div class="flex-grow-1 p-4">
        <a href="/participants" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <h4 class="my-3" style="color: var(--primary-color)">Edit Participant</h4>
        <form action="/participants/{{ $participant->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="rounded p-5" style="border: 1px solid #ccc;">
                <div class="row">
                    <div class="col-2">
                        <label for="queue_number">Queue Number</label>
                    </div>
                    <div class="col-1 text-end">
                        :
                    </div>
                    <div class="col-6">
                        <input type="number" name="queue_number" id="queue_number"
                            class="form-control @error('queue_number') is-invalid @enderror"
                            value="{{ old('queue_number', $participant->queue_number) }}">
                        @error('queue_number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-2">
                        <label for="name">Name</label>
                    </div>
                    <div class="col-1 text-end">
                        :
                    </div>
                    <div class="col-6">
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ old('name', $participant->name) }}">
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-2">
                        <label for="school">school</label>
                    </div>
                    <div class="col-1 text-end">
                        :
                    </div>
                    <div class="col-6">
                        <input type="text" name="school" id="school" class="form-control"
                            value="{{ old('school', $participant->school) }}">
                    </div>
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
        document.getElementById('nis').addEventListener('input', function() {
            var nisValue = document.getElementById('nis').value; // Ambil nilai dari NIS
            document.getElementById('username').value = nisValue; // Isi nilai ke username
        });
    </script>
@endsection
