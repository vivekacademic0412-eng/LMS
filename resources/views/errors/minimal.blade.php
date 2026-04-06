@php
    $statusCode = is_object($exception ?? null) && method_exists($exception, 'getStatusCode')
        ? $exception->getStatusCode()
        : 500;
    $statusText = \Symfony\Component\HttpFoundation\Response::$statusTexts[$statusCode] ?? 'System Error';
    $tone = $statusCode >= 500 ? 'alert' : 'warm';
@endphp
@extends('errors.layout')

@section('title', $statusText)
@section('status', (string) $statusCode)
@section('tone', $tone)
@section('eyebrow', 'System response')
@section('headline', 'We could not complete this page request.')
@section('subheadline', 'A fallback error view is handling this response so the platform still looks consistent.')
@section('message', 'Status ' . $statusCode . ' returned while opening this page. Use the actions below to move back to a stable part of the system.')
@section('aside_message', 'This fallback page appears when a dedicated error screen is not available for the current status code.')
@section('support_note', 'If this response is unexpected, capture the status code and route so the correct dedicated error flow can be reviewed.')
@section('footer_note', $statusCode . ' | ' . $statusText)

@section('tips')
    <li>Return to the dashboard or home page and reopen the feature normally.</li>
    <li>Retry once after a short pause if the request was interrupted.</li>
    <li>Report the status code if the same page continues failing.</li>
@endsection