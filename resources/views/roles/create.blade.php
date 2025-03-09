@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="heading">
            <h3>Create Role</h3>
            <a href="{{ route('role.index') }}" class="btn btn-success">Back</a>
        </div>
        <div class="container-fluid mt-4">
            <form action="{{ route('role.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role_name">Role Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="permissions">Select Permissions</label><br>
                            @foreach ($permissions as $permission)
                                <input type="checkbox" name="permissions[]" class="" value="{{$permission->name}}">
                                {{$permission->name}}
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-secondary">Submit</button>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </form>
        </div>
    </div>
@endsection
