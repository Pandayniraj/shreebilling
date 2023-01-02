<!-- Head for the theme -->
@if(isset($app_theme))
  @if($app_theme == 'green')
    @include('themes.green.partials._theme_head')
  @elseif($app_theme == 'red')
  	@include('themes.red.partials._theme_head')
  @elseif($app_theme == 'yellow')
    @include('themes.yellow.partials._theme_head')
  @elseif($app_theme == 'purple')
    @include('themes.purple.partials._theme_head')
  @elseif($app_theme == 'black')
    @include('themes.black.partials._theme_head')
  @else
    @include('partials._theme_head')
  @endif
@else
  @include('partials._theme_head')
@endif


