@extends('errors.layout')

@section('title', 'Page Expired')
@section('status', '419')
@section('tone', 'warm')
@section('eyebrow', 'Session timeout')
@section('headline', 'Your session expired before the request finished.')
@section('subheadline', 'This often happens after staying on a form for a long time or submitting an old page tab.')
@section('message', 'Refresh the page, sign in again if needed, and then resend the form or action with a fresh session.')
@section('aside_message', 'The platform is protecting your session. Once the page is refreshed, the action should work again in most cases.')
@section('support_note', 'If form submissions keep expiring too quickly, review the session configuration and browser cookie behavior.')
@section('footer_note', '419 | Page expired')

@section('tips')
    <li>Refresh the page before submitting the form again.</li>
    <li>Sign in again if the platform redirects you to the login screen.</li>
    <li>Avoid submitting very old tabs that were left open for a long time.</li>
@endsection