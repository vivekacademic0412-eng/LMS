@extends('errors.layout')

@section('title', 'Unauthorized')
@section('status', '401')
@section('tone', 'warm')
@section('eyebrow', 'Authentication required')
@section('headline', 'You need to sign in first.')
@section('subheadline', 'This area is available only after a valid login session is active.')
@section('message', 'Your request reached a protected page, but there is no active authorized session for this action yet.')
@section('aside_message', 'Open the login page, sign in with the correct account, and then return to the feature you were trying to access.')
@section('support_note', 'If you already signed in and still see this page, your session may have expired or your account may not have the required access.')
@section('footer_note', '401 | Unauthorized')

@section('tips')
    <li>Open the login page and sign in again.</li>
    <li>Make sure you are using the correct user account for this panel or route.</li>
    <li>If the page should be available to you, ask an administrator to verify your account permissions.</li>
@endsection