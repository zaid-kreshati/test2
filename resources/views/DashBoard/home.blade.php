@extends('DashBoard.header')

@section('content')
@section('title', __('Post Blug'))

<body>
    <div class="image-container">
        <img src="/PostBlug/Dashboard-Logo.png" alt="{{ __('Post Blug') }}">
    </div>

    <div class="main-content">
        <h1>{{ __('Home') }}</h1>
        <div class="btn-group">
            <a href="{{ route('categories.index') }}" class="btn-post">{{ __('View All Categories') }}</a>



        </div>
    </div>

@endsection
</body>
