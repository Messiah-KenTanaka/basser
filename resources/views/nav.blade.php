<nav class="navbar navbar-expand navbar-light dusty-grass-gradient main-nav">

  <a class="navbar-brand" href="/"><i class="h3 font-weight-bold nav-title">BASSER</i></a>

  <ul class="navbar-nav ml-auto">

    <li class="nav-item">
      <a class="nav-link" href="#"><i class="fas fa-crown"></i>順位</a>
    </li>

    @guest
    <li class="nav-item">
      <a class="nav-link" href="{{ route('register') }}"><i class="fas fa-user"></i>登録</a>
    </li>
    @endguest

    @guest
    <li class="nav-item">
      <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i>ログイン</a>
    </li>
    @endguest

    @auth
    <li class="nav-item">
      <a class="nav-link" href="{{ route('articles.create') }}"><i class="fas fa-pen mr-1"></i>投稿</a>
    </li>
    @endauth

    @auth
    <!-- Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-user-circle"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
        <button class="dropdown-item" type="button"
        onclick="location.href='{{ route("users.show", ["name" => Auth::user()->name]) }}'">
          マイページ
        </button>
        <div class="dropdown-divider"></div>
        <button form="logout-button" class="dropdown-item" type="submit">
          ログアウト
        </button>
        <div class="dropdown-divider"></div>
        <button class="dropdown-item" type="button"
                onclick="location.href=''">
          使い方
        </button>
      </div>
    </li>
    <form id="logout-button" method="POST" action="{{ route('logout') }}">
      @csrf
    </form>
    @endauth
    <!-- Dropdown -->

  </ul>

</nav>
