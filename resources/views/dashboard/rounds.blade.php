@extends('dashboard.sidebar.main')

@section('container')
    <div class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between mb-3">
            <div>
                <h4 style="color: var(--primary-color)">Rounds</h4>
            </div>
            <div><a href="rounds/create" class="btn btn-primary">Add Round</a></div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($rounds->isEmpty())
            <p>No round found.</p>
        @else
            <div class="row g-3 mt-3">
                @foreach ($rounds as $round)
                    <div class="col-md-4 d-flex justify-content-center">
                        <div class="card text-center" style="width: 18rem;">
                            <div class="card-body">
                                <h3 class="center">Round {{ $round['number'] }}</h3>
                                <h5 class="card-title">Segment {{ $round['segment'] }} | {{ $round['title'] }}</h5>
                                
                                <a href="/rounds/{{ $round->id }}" class="btn text-light mt-4"
                                    style="background-color: var(--primary-color)">View</a>
                                <a href="/rounds/{{ $round->id }}/edit" class="btn btn-warning text-light mt-4">Edit</a>
                                
                                <button type="button" onclick="confirmDelete({{ $round->id }})"
                                    class="btn btn-danger text-light mt-4">Delete</button>

                                @if ($round->is_active)
                                    <span class="btn btn-success mt-4">Active</span>
                                @else
                                    <form action="/rounds/{{ $round->id }}/activate" method="POST" style="display:inline;">
                                        @csrf
                                        <button class="btn btn-secondary mt-4">Activate</button>
                                    </form>
                                @endif

                                {{-- FORM DELETE DIPINDAHKAN KE DALAM LOOP --}}
                                <form id="delete-form-{{ $round->id }}" action="/rounds/{{ $round->id }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure you want to delete this round?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Delete!',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection