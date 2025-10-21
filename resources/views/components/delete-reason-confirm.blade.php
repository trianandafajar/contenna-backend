@props(['id', 'route'])

<div class="menu-item px-3">
    <form id="delete-form-{{ $id }}" action="{{ $route }}" method="POST">
        @csrf
        @method('DELETE')
        <a class="menu-link px-3" onclick="confirmDelete('{{ $id }}')">Keluarkan</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Function to show confirmation before deletion
    function confirmDelete(id) {
        // Show confirmation alert using SweetAlert
        Swal.fire({
            title: 'Are You Sure?',
            text: "You won't be able to undo this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Removal Member!'
        }).then((result) => {
            // If the user confirms the deletion
            if (result.isConfirmed) {
                // Show a prompt for the deletion reason
                Swal.fire({
                    title: 'Enter Reason for Member Removal',
                    input: 'text',
                    inputPlaceholder: 'Please provide a reason for removing this member...',
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    cancelButtonText: 'Cancel',
                    inputAttributes: {
                        'aria-label': 'Please provide a reason for removing this member',
                    },
                    preConfirm: (reason) => {
                        if (!reason) {
                            Swal.showValidationMessage('Please enter a reason for removing this member');
                        }
                        return reason;
                    }
                }).then((inputResult) => {
                    if (inputResult.isConfirmed) {
                        const reasonInput = document.createElement('input');
                        reasonInput.type = 'hidden';
                        reasonInput.name = 'deleted_reason';
                        reasonInput.value = inputResult.value;
                        document.getElementById('delete-form-' + id).appendChild(reasonInput);

                        // Submit the deletion form
                        document.getElementById('delete-form-' + id).submit();
                    }
                });

            }
        });
    }
</script>
