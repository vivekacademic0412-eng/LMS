@extends('errors.layout')

@section('title', 'Forbidden')
@section('status', '403')
@section('tone', 'alert')
@section('eyebrow', 'Permission denied')
@section('headline', 'You do not have access to this area.')
@section('subheadline', 'The request was understood, but your current role cannot open this page or perform this action.')
@section('message', 'This usually means the feature belongs to a different panel, role, or permission set than the one currently assigned to your account.')
@section('aside_message', 'If you expected to see this page, check whether you signed in with the right account or whether the required permission is still active.')
@section('support_note', 'Share the page URL and this 403 status with your administrator so they can confirm the intended access rules.')
@section('footer_note', '403 | Forbidden')

@section('tips')
    <li>Return to your dashboard and open the feature through the panel menu instead of a saved direct link.</li>
    <li>Verify that your current user role matches the page you are trying to open.</li>
    <li>Contact an administrator if this permission should exist for your account.</li>
@endsection