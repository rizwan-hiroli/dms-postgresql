@extends('layouts.app')

@section('content')

<!-- Main content -->
<section class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-12">
            <div class="card">
               <div class="card-header">
                  <h3 class="card-title">All Categories</h3>
                  <div class="d-flex justify-content-end">
                     <div class="pull-right"><a href="{{route('categories.create')}}" class="btn btn-sm btn-primary pull-right">Add</a></div>
                  </div>
               </div>
               <!-- /.card-header -->
               <div class="card-body">
                  <table id="categories-table" class="table table-bordered table-hover">
                     <thead>
                        <tr>
                           <th>ID</th>
                           <th>Name</th>
                           <th>Description</th>
                           <th>Created At</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                     </tbody>
                  </table>
               </div>
               <!-- /.card-body -->
            </div>
            <!-- /.card -->
         </div>
         <!-- /.col -->
      </div>
      <!-- /.row -->
   </div>
   <!-- /.container-fluid -->
</section>
<!-- /.content -->
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <form method="POST" action="" id="deleteForm">
            @csrf
            @method('DELETE')
            <div class="modal-body">
               Deleting Categories will delete all of its products. Are you sure you want to delete this record?
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
               <button type="submit" class="btn btn-danger">Delete</button>
            </div>
         </form>
      </div>
   </div>
</div>

<script>
    $(document).ready(function() {
        $('#categories-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('categories.data') }}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });

    //mapping id to deleted modal.
    $(document).ready(function() {
        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var categoryId = button.data('id'); // Extract user ID from data-* attributes
            var form = $('#deleteForm'); // Form inside the modal
            var action = '{{ route('categories.destroy', ':id') }}'; // Replace :id in the route with the actual ID
            form.attr('action', action.replace(':id', categoryId)); // Set the form action with the correct ID
        });
    });
</script>
@endsection
