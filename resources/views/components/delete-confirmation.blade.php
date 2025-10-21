<!-- resources/views/components/DeleteConfirmation.blade.php -->

@props(['id', 'route', 'isConfirm' => false])

<div class="menu-item px-3">
    <form id="delete-form-{{ $id }}" action="{{ $route }}" method="POST">
        @csrf
        @method('DELETE')
        <a class="menu-link px-3" onclick="confirmDelete('{{ $id }}', {{ $isConfirm }})">Hapus</a>
    </form>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Fungsi untuk menampilkan konfirmasi penghapusan
    function confirmDelete(id, isConfirm) {
        // Tampilkan pesan konfirmasi menggunakan SweetAlert
        Swal.fire({
            title: 'Are You Sure?',
            text: "You won't be able to undo this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            // Jika pengguna mengkonfirmasi penghapusan
            if (result.isConfirmed) {
                if(isConfirm) {
                    Swal.fire({
                        title: 'Are You Absolutely Sure?',
                        text: "This is your last chance to cancel!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        reverseButtons: true,
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((secondResult) => {
                        if (secondResult.isConfirmed) {
                            // Submit the delete form
                            document.getElementById('delete-form-' + id).submit();
                        }
                    });
                } else {
                    // Submit formulir penghapusan yang sesuai
                    document.getElementById('delete-form-' + id).submit();
                }
            }
        });
    }
</script>

