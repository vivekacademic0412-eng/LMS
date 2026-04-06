@extends('errors.layout')

@section('title', 'Server Error')
@section('status', '500')
@section('tone', 'alert')
@section('eyebrow', 'Application issue')
@section('headline', 'Something went wrong on our side.')
@section('subheadline', 'The request reached the application, but the server could not finish processing it safely.')
@section('message', 'This is usually temporary. Please try again after a moment or return to another area of the platform while the issue is reviewed.')
@section('aside_message', 'A 500 response means the page logic failed during processing. The safer move is to step back and reopen the feature cleanly.')
@section('support_note', 'If this happens repeatedly, capture the route, time, and action you performed so the error can be traced in logs quickly.')
@section('footer_note', '500 | Internal server error')

@section('tips')
    <li>Refresh the page once and retry the action.</li>
    <li>If the error returns, go back to the dashboard and avoid repeated submissions.</li>
    <li>Share the route and the exact action that triggered the failure with the developer or administrator.</li>
@endsection