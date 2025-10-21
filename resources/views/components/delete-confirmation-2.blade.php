<!-- resources/views/components/DeleteConfirmation2.blade.php -->

@props(['id', 'route', 'isConfirm' => false])


<div class="menu-item px-3">
    <form id="delete-form-{{ $id }}" action="{{ $route }}" method="POST">
        @csrf
        @method('DELETE')
        <a class="menu-link px-3" onclick="confirmDelete('{{ $id }}')">Delete</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are You Sure?',
            text: "If this data is deleted, the file in Google Drive will also be removed.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                confirmFinalDelete(id);
            }
        });
    }

    function confirmFinalDelete(id) {
        Swal.fire({
            title: 'Are You Absolutely Sure?',
            text: "This process cannot be undone once performed.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            reverseButtons: true,
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((secondResult) => {
            if (secondResult.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
