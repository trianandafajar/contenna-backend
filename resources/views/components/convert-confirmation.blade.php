<!-- resources/views/components/ConvertConfirmation.blade.php -->

@props(['id', 'route'])

<div class="menu-item px-3">
    <form id="convert-form-{{ $id }}" action="{{ $route }}" method="POST">
        @csrf
        @method('PATCH')
        <a class="menu-link px-3" onclick="confirmConvert('{{ $id }}')">Convert</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmConvert(id) {
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: "Anda akan mengubah Rumah Usaha ini menjadi Circle!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, convert it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('convert-form-' + id).submit();
            }
        });
    }
</script>

