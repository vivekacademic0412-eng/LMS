@extends('errors.layout')

@section('title', 'Too Many Requests')
@section('status', '429')
@section('tone', 'warm')
@section('eyebrow', 'Rate limit reached')
@section('headline', 'Too many requests were sent in a short time.')
@section('subheadline', 'The system temporarily slowed this action to protect the application and keep it stable.')
@section('message', 'Please wait a moment and try again. Repeating the same action quickly can extend the cooldown window.')
@section('aside_message', 'This is usually temporary. After a short pause, the request should work normally again.')
@section('support_note', 'If normal usage is triggering rate limits, the throttle settings for this route may need to be adjusted.')
@section('footer_note', '429 | Too many requests')

@section('tips')
    <li>Wait briefly before retrying the same action.</li>
    <li>Avoid repeated clicks, refreshes, or rapid submissions on the same page.</li>
    <li>If this happens during regular use, report which action triggered it.</li>
@endsection