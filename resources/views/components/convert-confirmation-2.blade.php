@props(['id', 'route', 'type'])

<div class="menu-item px-3">
    <form id="convert-form-{{ $id }}" action="{{ $route }}" method="POST">
        @csrf
        <a class="menu-link px-2" onclick="confirmConvert('{{ $id }}', '{{ $type }}')">
            @if ($type === 'project')
                Jadikan Project
            @else
                Jadikan Studio
            @endif
        </a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmConvert(id, type) {
        let title = 'Apakah Anda Yakin?';
        let text = type === 'project' ? 'Anda akan mengubah studio ini menjadi Project!' : 'Anda akan mengubah Project ini menjadi Studio!';
        let confirmButtonText = type === 'project' ? 'Ya, Jadikan Project!' : 'Ya, Jadikan Studio!';

        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: confirmButtonText
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('convert-form-' + id).submit();
            }
        });
    }
</script>
