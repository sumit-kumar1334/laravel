@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="heading">
            <h3>Role Management</h3>
            <a href="{{ route('role.create') }}" class="btn btn-success">Create Role</a>
        </div>
        @if(session('success'))
            <div class="alert alert-success">{{session('success')}}</div>
        @endif
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Role Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{$role->id}}</td>
                        <td>{{$role->name}}</td>
                        <td>
                            <a href="{{ route('role.edit', $role->id) }}" class="btn btn-primary">Edit</a>
                            <a href="{{ route('role.delete', $role->id) }}" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
