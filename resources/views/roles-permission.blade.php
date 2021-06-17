@extends('layouts.default')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Roles and Permissions</div>

                <div class="card-body">
                    @foreach($roles as $role)
                        <strong>{{$role->name}}</strong>
                        @foreach($role->permissions as $permissions)
                            <p>{{$permissions->name}}</p>
                        @endforeach
                    @endforeach

                    @can('manage-treatment-centres-r')
                        Yessssss
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
