@extends('layouts/app')
@section('content')

    <chat :user="{{ auth()->user() }}"></chat>

@endsection