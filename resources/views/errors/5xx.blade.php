@php
    $statusCode = is_object($exception ?? null) && method_exists($exception, 'getStatusCode')
        ? $exception->getStatusCode()
        : 500;
    $statusText = \Symfony\Component\HttpFoundation\Response::$statusTexts[$statusCode] ?? 'Server Error';
@endphp
@extends('errors.layout')

@section('title', $statusText)
@section('status', (string) $statusCode)
@section('tone', 'alert')
@section('eyebrow', 'Server issue')
@section('headline', 'The server could not finish this request.')
@section('subheadline', 'The request reached the application correctly, but the response failed during processing.')
@section('message', 'Status ' . $statusCode . ' points to a temporary application or infrastructure problem rather than a normal user mistake.')
@section('aside_message', 'The safest path is to return to a working page, avoid repeated submissions, and try again once the system is stable.')
@section('support_note', 'If this keeps happening, check logs, dependent services, and the exact route involved in the failure.')
@section('footer_note', $statusCode . ' | ' . $statusText)

@section('tips')
    <li>Refresh once and retry only after a short pause.</li>
    <li>Return to the dashboard if the action seems stuck or half-complete.</li>
    <li>Share the status code, route, and time of failure with the technical team.</li>
@endsection