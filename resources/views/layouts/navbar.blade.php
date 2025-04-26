<nav class="navbar navbar-expand-lg navbar-dark  bg-dark">
    <div class="container-fluid">
        <!-- <a class="navbar-brand" href="{{ route('admin_dashboard') }}"><img class="rounded" src="{{asset('img/white-logo.png')}}" height="60" alt="Company Icon"></a> -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('admin_dashboard') }}"><i>DeltaSoft Systems</i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" target="_faizan" href="https://www.deltasoftsys.in/">Official Page</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Services
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" target="_faizan" href="https://www.deltasoftsys.in/website-design">WebSite Design</a></li>
                        <li><a class="dropdown-item" target="_faizan" href="https://www.deltasoftsys.in/website-development">Website Development</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" target="_faizan" href="https://www.deltasoftsys.in/application-development">Application Development</a></li>
                    </ul>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                </li> -->
            </ul>
            <div class="d-flex">
                <form action="{{ route('logout')}}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>