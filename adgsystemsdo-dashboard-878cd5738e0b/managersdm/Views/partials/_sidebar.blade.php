
<div id="nav-col">
    <section id="col-left" class="col-left-nano">
        <div id="col-left-inner" class="col-left-nano-content">
                <div id="user-left-box" class="clearfix hidden-sm hidden-xs dropdown profile2-dropdown">
                    <div class="user-box">
                        <span class="name">
                          <i class="fa fa-user"></i>  {{  $authUser->name  }}
                        </span>
                        <span style="font-size:12px;">
                            Admin
                        </span>
                    </div>
                </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">
                <ul class="nav nav-pills nav-stacked">
                    <li class="nav-header nav-header-first hidden-sm hidden-xs">
                        Menu de Opciones
                    </li>
                    <li id="menu-dashboard">
                        <a href="/">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="/manager/users">
                            <i class="fa fa-user"></i>
                            <span>Usuarios</span>
                        </a>
                    </li>
                    <li>
                        <a href="/manager/clients">
                            <i class="fa fa-users"></i>
                            <span>Clientes</span>
                        </a>
                    </li>
                    <li>
                        <a href="/manager/seriales">
                            <i class="fa fa-certificate"></i>
                            <span>Seriales de clientes</span>
                        </a>
                    </li>
                    <li>
                        <a href="/manager/users/credentials">
                            <i class="fa fa-key"></i>
                            <span>Credenciales de Usuario</span>
                        </a>
                    </li>
                    {{--<li>--}}
                        {{--<a href="/manager/scripts">--}}
                            {{--<i class="fa fa-code"></i>--}}
                            {{--<span>Scripts</span>--}}
                        {{--</a>--}}
                    {{--</li>--}}

                </ul>

            </div>
        </div>
    </section>
</div>
       