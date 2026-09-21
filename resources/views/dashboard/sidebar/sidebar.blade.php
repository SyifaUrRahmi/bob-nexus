<style>
    .sidebar-list:hover,
    .sidebar-list.active {
        background-color: var(--secondary-color);
        color: rgb(0, 0, 0);
    }

    .sidebar-list:hover i {
        color: rgb(0, 0, 0);
    }
</style>
<div class="bg-light border-end vh-100 d-flex flex-column" id="sidebar-wrapper" style="width: 250px;">
    <div class="sidebar-heading text-center p-3" style="background-color: var(--primary-color); color:rgb(255, 255, 255)">
        <h5>Menu</h5>
    </div>

    <!-- Container utama -->
    <div class="d-flex flex-column justify-content-between flex-grow-1">

        <!-- 🔹 MENU ATAS -->
        <div>
            <hr class="my-0">
            <a href="/dashboard"
                class="sidebar-list list-group-item list-group-item-action d-flex align-items-center p-3 {{ Request::is('dashboard*') ? 'active' : '' }}">
                <i class="bi bi-house me-2"></i> Home
            </a>

            <hr class="my-0">
            <a href="/participants"
                class="sidebar-list list-group-item list-group-item-action d-flex align-items-center p-3 {{ Request::is('participants*') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i> Participants
            </a>

            <hr class="my-0">
            <a href="/rounds"
                class="sidebar-list list-group-item list-group-item-action d-flex align-items-center p-3 {{ Request::is('rounds*') ? 'active' : '' }}">
                <i class="bi bi-list-task me-2"></i> Rounds
            </a>
        </div>

        <!-- 🔹 LOGOUT BAWAH -->
        <div>
            <hr class="my-0">
            <form action="{{ route('logout') }}" method="POST"
                class="sidebar-list list-group-item list-group-item-action d-flex align-items-center p-3"
                id="logoutForm">
                @csrf
                <button type="submit" class="btn w-100 text-start p-0 border-0 bg-transparent">
                    <i class="bi bi-box-arrow-right me-2"></i> Keluar
                </button>
            </form>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('logoutForm').addEventListener('submit', function(e) {
        e.preventDefault(); // cegah form langsung submit

        Swal.fire({
            title: 'Yakin ingin keluar?',
            text: "Kamu akan keluar dari sesi saat ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, keluar!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Kalau dikonfirmasi, submit form manual
                e.target.submit();
            }
        });
    });
</script>
