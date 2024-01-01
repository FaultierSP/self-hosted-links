<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"><head>
    <meta charset="utf-8">
    <meta name="description" content="{{ env('YOUR_META_DESCRIPTION') }}">
    <meta name="author" content="{{ env('YOUR_META_AUTHOR') }}">
    <meta name="generator" content="Coffee, two hands and a lot of swearing.">
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <title>{{ env('YOUR_SITE_NAME') }} :: {{ $page_title }}</title>
</head><body class="antialiased">