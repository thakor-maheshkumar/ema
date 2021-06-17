@extends('layouts.app')

@section('title', 'EMA Users')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Users List</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                                @foreach($user->roles as $role)
                                    <td>{{$role->name}}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection