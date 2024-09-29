@extends('layouts.app')

@section('content')
<!-- Main content -->
<section class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-12">
            <div class="card">
               <div class="card-header">
                  <h3 class="card-title">Create Categories</h3>
               </div>
               <!-- /.card-header -->
               <div class="card-body">
                  <div class="container">
                     @if ($errors->any())
                     <div class="alert alert-danger">
                        <ul>
                           @foreach ($errors->all() as $error)
                           <li>{{ $error }}</li>
                           @endforeach
                        </ul>
                     </div>
                     @endif
                     @if (session('success'))
                     <div class="alert alert-success">
                        {{ session('success') }}
                     </div>
                     @endif
                     @if (session('error'))
                     <div class="alert alert-danger">
                        {{ session('error') }}
                     </div>
                     @endif
                     <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                           <label for="name" class="form-label">Category Name:</label>
                           <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                           <div class="invalid-feedback">
                              Please enter a category name.
                           </div>
                        </div>
                        <div class="mb-3">
                           <label for="description" class="form-label">Category Description:</label>
                           <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                           <div class="invalid-feedback">
                              Please enter a category description.
                           </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Category</button>
                     </form>
                  </div>
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

@endsection

