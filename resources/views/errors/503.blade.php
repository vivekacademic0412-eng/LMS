@extends('errors.layout')

@section('title', 'Service Unavailable')
@section('status', '503')
@section('tone', 'cool')
@section('eyebrow', 'Temporary outage')
@section('headline', 'This service is temporarily unavailable.')
@section('subheadline', 'The application may be under maintenance, restarting, or waiting for a required service to recover.')
@section('message', 'Please try again shortly. Once the maintenance or temporary outage ends, normal access should return without any extra steps.')
@section('aside_message', 'A 503 response usually means the app is intentionally unavailable for a short time rather than permanently broken.')
@section('support_note', 'If the maintenance window is unexpected or lasts too long, review server health, queue workers, and dependent services.')
@section('footer_note', '503 | Service unavailable')

@section('tips')
    <li>Wait a little and reopen the page.</li>
    <li>Check whether maintenance or deployment work is currently happening.</li>
    <li>If the platform stays unavailable, notify the administrator with the time you saw this page.</li>
@endsection