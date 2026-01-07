@props(['title' => null])

@include('layouts.seller', [
    'title' => $title,
    'slot' => $slot
])
