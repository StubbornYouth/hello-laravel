<!--将所有的键遍历-->
@foreach (['danger', 'warning', 'success', 'info'] as $msg)
<!--判断会话键对应的值是否为空-->
  @if(session()->has($msg))
    <div class="flash-message">
      <p class="alert alert-{{ $msg }}">
        <!--输出会话缓存信息-->
        {{ session()->get($msg) }}
      </p>
    </div>
  @endif
@endforeach