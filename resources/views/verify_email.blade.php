@extends('layouts.app')

@section('title', 'Verification Email')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12 homebg d-flex align-items-center text-center justify-content-center">
     <div class="alert alert-success">
      {{ $message }}
    </div>
  </div>
</div>
</div>
@endsection