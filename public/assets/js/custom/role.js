// Get All Data Roles
let roles = null;
$.ajax({
    url: '/ajax/get-all-roles',
    type: 'GET',
    success: function(response) {
        roles = response;
    },
    error: function(xhr) {
        console.log(xhr.responseText);
    }
});

$(() => {
    const roleTable = $('#roles-table').DataTable({
        // responsive: true,
        autoWidth: false,
        // "language": {
        //     "info": ""
        // },
        "columnDefs": [{
            "orderable": false,
            "targets": 0
        }],
        "order": [
            [3, 'desc'],
            [4, 'asc']
        ],
        "searching": true,
        "lengthMenu": [10, 25, 50, 100]
    })

    $('#role-search').on('keyup', function() {
        roleTable.search(this.value).draw();
    });

    $("#btn-submit").on("click", (event) => {
        let formInput = $("#kt_modal_update_role_form").serializeArray();

        let inputName = formInput.find(f => f.name == "name").value;

        event.preventDefault();
        let role = roles.roles.find(f => f.id == $("#btn-submit").attr("data-role-id"));
        oldName = role.name;

        let newName = inputName;

        if(oldName == newName) {
            $("#kt_modal_update_role_form").submit();
        } else {
            role = roles.roles.find(f => f.name == newName);
            if(role == null) {
                $("#kt_modal_update_role_form").submit();
            } else {
                $(".update-name").addClass("is-invalid border border-1 border-danger");
                $(".unique-validate").removeClass("d-none")
            }
        }
    });
})
