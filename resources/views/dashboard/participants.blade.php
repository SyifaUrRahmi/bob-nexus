@extends('dashboard.sidebar.main')

@section('container')
    <style>
        .nav {
            --bs-nav-link-color: #000000;
            --bs-nav-link-hover-color: var(--primary-color);
        }

        .nav-tabs .nav-link.active {
            background-color: var(--primary-color);
            color: #ffffff;
        }

        .page-link {
            color: var(--primary-color);
        }

        .pagination {
            --bs-pagination-hover-color: var(--primary-color);
            --bs-pagination-focus-color: var(--primary-color);
            --bs-pagination-focus-box-shadow: var(--primary-color);
            --bs-pagination-active-bg: var(--primary-color);
            --bs-pagination-active-border-color: var(--primary-color);

        }
    </style>
    <div class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between mb-3">
            <div>
                <h4 style="color: var(--primary-color)">Participants</h4>
            </div>
            <div><a href="participants/create" class="btn btn-primary">Add Participant
                </a></div>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($participants->isEmpty())
            <p>No participants found.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Queue Number</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">School</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($participants as $participant)
                        <tr>
                            <td class="px-3 text-center">{{ $participant->queue_number }}</td>
                            <td class="text-center">{{ $participant->name }}</td>
                            <td class="text-center">{{ $participant->school }}</td>
                            <!-- Kolom Status Baru -->
                            <td class="text-center">
                                <form action="/participants/{{ $participant->id }}/toggle-status" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <!-- Ubah warna dan teks tombol berdasarkan nilai kolom 'status' -->
                                    <button type="submit" class="btn btn-sm {{ $participant->status === 'active' ? 'btn-success' : 'btn-secondary' }}">
                                        {{ $participant->status === 'active' ? 'Aktif' : 'Eliminasi' }}
                                    </button>
                                </form>
                            </td>
                            <td class="d-flex justify-content-center">
                                <div class="bg-warning px-2 rounded me-2"><a
                                        href="/participants/{{ $participant->id }}/edit"><i
                                            class="bi bi-pencil-square text-light"></i></a>
                                </div>
                                <div class="bg-danger px-2 rounded"><a href="#"
                                        onclick="confirmDelete({{ $participant->id }})"><i
                                            class="bi bi-trash-fill text-light"></i></a></div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <form id="delete-form-{{ $participant->id }}" action="/participants/{{ $participant->id }}" method="POST"
                style="display: none;">
                @csrf
                @method('DELETE')
            </form>

            <!-- Pagination links -->
            <div class="d-flex justify-content-center">
                {{ $participants->links() }}
            </div>
        @endif
    </div>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure you want to delete this participant?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Delete!',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mengarah ke URL hapus menggunakan metode POST
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
