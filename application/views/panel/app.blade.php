<!doctype html>
<html lang="en">
	<head>
		@include('panel.components.styles')
        <title>@yield('title','Elibrary')</title>
    </head>
    <body>
        <div id="layout-wrapper">
            @include('panel.components.header')
            @include('panel.components.sidebar')
            <div class="main-content">
                @yield('content')
				@include('panel.components.footer')
            </div>
        </div>
        @include('panel.components.scripts')
    </body>
</html>
