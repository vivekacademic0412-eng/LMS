@php
    $statusCode = is_object($exception ?? null) && method_exists($exception, 'getStatusCode')
        ? $exception->getStatusCode()
        : 400;
    $statusText = \Symfony\Component\HttpFoundation\Response::$statusTexts[$statusCode] ?? 'Client Error';
@endphp
@extends('errors.layout')

@section('title', $statusText)
@section('status', (string) $statusCode)
@section('tone', 'warm')
@section('eyebrow', 'Request issue')
@section('headline', 'This request could not be completed.')
@section('subheadline', 'The platform understood the request, but something about it prevented a successful response.')
@section('message', 'Status ' . $statusCode . ' means the request needs attention before it can continue normally.')
@section('aside_message', 'Returning to a stable page and reopening the feature from the normal navigation path is usually the quickest recovery step.')
@section('support_note', 'If the same request keeps failing with this status, share the status code and route with your administrator.')
@section('footer_note', $statusCode . ' | ' . $statusText)

@section('tips')
    <li>Go back and repeat the action from inside the application menu.</li>
    <li>Check whether the page requires a different login, permission, or a fresh session.</li>
    <li>If the request still fails, report the status code shown on this page.</li>
@endsection