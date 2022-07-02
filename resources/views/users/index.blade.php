@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Users') }}
                    <button class="btn btn-primary btn-sm float-end" id="btn-create">Add</button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered data-table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th width="100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="javascript:void(0)" id="modal-form" name="modal-form" method="POST">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" autofocus placeholder="Name" required>
                        <span id="nameError" class="text-danger"></span>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" autofocus placeholder="email" required>
                        <span id="emailError" class="text-danger"></span>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" autofocus placeholder="password">
                        <span id="passwordError" class="text-danger"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-save">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
<link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('script')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('.data-table').DataTable({
            processing: true
            , serverSide: true
            , ajax: "{{ route('users.index') }}"
            , columns: [{
                    data: 'id'
                    , name: 'id'
                }
                , {
                    data: 'name'
                    , name: 'name'
                }
                , {
                    data: 'email'
                    , name: 'email'
                }
                , {
                    data: 'action'
                    , name: 'action'
                    , orderable: false
                    , searchable: false
                }
            , ]
        });

        $('#btn-create').click(function() {
            $('#modal-form').trigger("reset");
            $("#id").val('');
            $('#modal-title').html("Add Email");
            $('#modal-dialog').modal('show');
        });

        $('body').on('click', '.btn-edit', function() {
            var id = $(this).data('id');
            $.get("{{ route('users.index') }}" + '/' + id + '/edit', function(data) {
                $('#modal-title').html("Edit User");
                $('#btn-save').val("Update");
                $('#modal-dialog').modal('show');
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#email').val(data.email);
                $("#password").val('');
            })
        });


        $('body').on('click', '#btn-save', function(e) {
            // var id = $("#id").val();
            // var title = $("#title").val();
            e.preventDefault();
            $(this).html('Save');

            $("#btn-save").html('Please Wait...');
            $("#btn-save").attr("disabled", true);

            // ajax
            $.ajax({
                type: "POST"
                , url: "{{ route('users.store') }}"
                , data: $("#modal-form").serialize()
                , dataType: 'json'
                , success: function(res) {
                    $("#modal-dialog").modal('hide');
                    $("#moda-form").trigger('reset');
                    table.draw();
                    $("#btn-save").html('Save');
                    $("#btn-save").attr("disabled", false);
                }
                , error: function(data) {
                    console.log('Error:', data);
                    $('#nameError').text(data.responseJSON.errors.name);
                    $('#emailError').text(data.responseJSON.errors.email);
                    // $('#btn-save').html('Save');
                    $("#btn-save").html('Save');
                    $("#btn-save").attr("disabled", false);
                }
            });
        });


        $('body').on('click', '.btn-delete', function() {

            var id = $(this).data("id");
            confirm("Are You sure want to delete !");

            $.ajax({
                type: "DELETE"
                , url: "{{ route('users.store') }}" + '/' + id
                , success: function(data) {
                    table.draw();
                }
                , error: function(data) {
                    console.log('Error:', data);
                }
            });
        });

    });

</script>
@endpush
