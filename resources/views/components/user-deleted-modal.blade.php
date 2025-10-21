@props(['roles', 'circles', 'positions'])

<div class="modal fade" id="userDeletedModal" tabindex="-1" aria-labelledby="userDeletedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userDeletedModalLabel">Recovery User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" class="form">
                    <div class="mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Role</label>
                        <select name="role_id[]" id="role_id" data-control="select2" data-hide-search="true" multiple="multiple"
                            class="form-select form-select-solid mb-3 mb-lg-0 {{ $errors->get('role_id') ? 'is-invalid border border-1 border-danger' : '' }}" aria-placeholder="select roles" placeholder="select roles">
                            @foreach ($roles as $id => $name)
                                <option value="{{ $id }}" {{ old('role_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('role_id')" />
                    </div>
                    <div class="mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Circle</label>
                        <div class="row">
                            <div class="col-6">
                                <select name="circle_id" id="circle_id" data-control="select2"
                                    class="form-select form-select-solid mb-3 mb-lg-0 {{ $errors->has('circle_id') ? 'is-invalid border border-1 border-danger' : '' }}" required>
                                    <option value="">Select Circle</option>
                                    @foreach ($circles as $id => $name)
                                        <option value="{{ $id }}" {{ (old('circle_id') == $id) ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('circle_id')" />
                            </div>
                            <div class="col-6">
                                <select name="position_id" id="position_id" data-control="select2"
                                    class="col-6 form-select form-select-solid mb-3 mb-lg-0 {{ $errors->has('position_id') ? 'is-invalid border border-1 border-danger' : '' }}" required>
                                    <option value="">Position</option>
                                    @foreach ($positions as $id => $name)
                                    <option value="{{ $id }}" {{ (old('position_id') == $id) ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('position_id')" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="recoveryButton">Recovery</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.querySelector('#userDeletedModal');
        modal.addEventListener('show.bs.modal', async function(e) {
            const button = e.relatedTarget;
            const userId = button.getAttribute('data-modal-user-deleted-id');
            const route = `{{ route('resources.user-deleted.recovery', ':userId') }}`.replace(':userId', userId);

            try {
                const response = await fetch(route);
                const data = await response.json();

                if (data.status) {
                    const userData = data.data;
                    const circleMember = userData.circle_member[0] || {}; // Get the first circle member

                    // Populate form fields with the fetched data
                    document.getElementById('modalUserId').value = userData.id;
                    document.getElementById('userName').value = userData.name;
                    document.getElementById('userEmail').value = userData.email;

                    // Pre-select circle_id and position_id based on the IDs in circle_member
                    const circleSelect = document.getElementById('circle_id');
                    const positionSelect = document.getElementById('position_id');

                    // Get the circle name based on circle_id
                    const circleName = @json($circles)[circleMember.circle_id] || '';
                    const positionName = @json($positions)[circleMember.position_id] || '';

                    // Select the circle and position by matching the name, not the value
                    if (circleName) {
                        Array.from(circleSelect.options).forEach(option => {
                            if (option.text === circleName) {
                                option.selected = true;
                            }
                        });
                    }

                    if (positionName) {
                        Array.from(positionSelect.options).forEach(option => {
                            if (option.text === positionName) {
                                option.selected = true;
                            }
                        });
                    }

                    // Refresh the select2 dropdown to reflect the changes
                    if (typeof $().select2 !== 'undefined') {
                        $('#circle_id').select2().trigger('change');
                        $('#position_id').select2().trigger('change');
                    }
                }
            } catch (error) {
                console.error("Error fetching user data:", error);
            }
        });

        // Handle recovery action when the "Recovery" button is clicked
        document.getElementById('recoveryButton').addEventListener('click', function() {
            // Handle the recovery logic (e.g., form submission)
            alert('User recovery logic goes here!');
        });
    });
</script>
