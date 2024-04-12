<nav class="uk-navbar">
    <a href="" class="uk-navbar-brand">DG Tournaments</a>
    <ul class="uk-navbar-nav">
        <li><a href="{{ url('admin') }}">Dashboard</a></li>
        <li><a href="{{ url('admin/directors') }}">Directors</a></li>
        <li><a href="{{ url('admin/tournaments') }}">Tournaments</a></li>
        <li><a href="{{ url('admin/videos') }}">Videos</a></li>
    </ul>
    <div class="uk-navbar-flip">
        <ul class="uk-navbar-nav">
            <li class="uk-parent" data-uk-dropdown>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    <i class="uk-icon-user"></i> {{ Auth::user()->name }} <span class="caret"></span>
                </a>

                <div class="uk-dropdown uk-dropdown-small uk-dropdown-navbar">
                    <ul class="uk-nav uk-nav-navbar">
                        <li>
                            <a href="{{ url('/logout') }}"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>