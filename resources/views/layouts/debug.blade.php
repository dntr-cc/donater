@php use DebugBar\StandardDebugBar;  @endphp
@php $debugbarRenderer = (new StandardDebugBar())->getJavascriptRenderer(url(config('app.url') . '/vendor/debugger')); @endphp
{!! $debugbarRenderer->renderHead() !!}
