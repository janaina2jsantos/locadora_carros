@extends('layouts.app')

@section('content')
    <home-component user_name="{{ $user->name }}"></home-component>
@endsection
