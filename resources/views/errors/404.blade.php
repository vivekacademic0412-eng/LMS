@extends('errors.layout')

@section('title', 'Page Not Found')
@section('status', '404')
@section('tone', 'cool')
@section('eyebrow', 'Missing page')
@section('headline', 'We could not find that page.')
@section('subheadline', 'The destination may have moved, been removed, or the address may no longer be valid.')
@section('message', 'If you opened an old bookmark or pasted a direct link, the path may not match the current project structure anymore.')
@section('aside_message', 'Use the dashboard menus or homepage links to reopen the feature from a valid route inside the application.')
@section('support_note', 'If this 404 appears from a normal in-app click, the link may need to be updated inside the project.')
@section('footer_note', '404 | Page not found')

@section('tips')
    <li>Check the URL for typing mistakes or missing path segments.</li>
    <li>Return to the dashboard and navigate from the sidebar or panel cards.</li>
    <li>If you reached this page from a button inside the app, note the route so it can be corrected.</li>
@endsection