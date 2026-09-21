@extends('dashboard.sidebar.main')

@section('container')
    <div class="flex-grow-1 p-4">
        <a href="/participants" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <h4 class="my-3" style="color: var(--primary-color)">Add Participant</h4>
        <form action="/participants" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="rounded p-5" style="border: 1px solid #ccc;">
                <div class="row">
                    <div class="col-2">
                        <label for="queue_number">Queue Number</label>
                    </div>
                    <div class="col-1 text-end">
                        :
                    </div>
                    <div class="col-6">
                        <input type="number" name="queue_number"
                            class="form-control @error('queue_number') is-invalid @enderror"
                            value="{{ old('queue_number') }}" required>

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
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-2">
                        <label for="school">School</label>
                    </div>
                    <div class="col-1 text-end">
                        :
                    </div>
                    <div class="col-6">
                        <input type="text" name="school" class="form-control @error('school') is-invalid @enderror"
                            value="{{ old('school') }}" required>
                    </div>
                </div>
            </div>
            <input type="hidden" name="role" value="student">
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
