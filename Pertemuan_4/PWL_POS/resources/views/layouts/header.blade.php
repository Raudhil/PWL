<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="../../index3.html" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown mx-2">
            <a class="nav-link position-relative" data-toggle="dropdown" href="#">
                <i class="fas fa-comment fa-lg"></i>
                <span
                    class="position-absolute top-10 start-100 translate-middle badge rounded-pill bg-danger badge-notif">
                    3
                </span>
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item custom-hover" href="#">Pesan 1</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item custom-hover" href="#">Pesan 2</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item custom-hover" href="#">Pesan 3</a></li>
            </ul>
        </li>
        <li class="nav-item dropdown mx-2">
            <a class="nav-link position-relative" data-toggle="dropdown" href="#">
                <i class="fas fa-bell fa-lg"></i>
                <span
                    class="position-absolute top-10 start-100 translate-middle badge rounded-pill bg-warning text-dark badge-notif">15</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0">
                <li class="dropdown-header bg-light py-2 px-3 border-bottom">
                    <strong>15 Notifications</strong>
                </li>

                <li>
                    <a class="dropdown-item d-flex justify-content-between align-items-center py-2 px-3" href="#">
                        <div>
                            <i class="fas fa-envelope me-2 text-primary"></i> 4 new messages
                        </div>
                        <small class="text-muted">3 mins</small>
                    </a>
                </li>

                <li>
                    <a class="dropdown-item d-flex justify-content-between align-items-center py-2 px-3" href="#">
                        <div>
                            <i class="fas fa-users me-2 text-success"></i> 8 friend requests
                        </div>
                        <small class="text-muted">12 hours</small>
                    </a>
                </li>

                <li>
                    <a class="dropdown-item d-flex justify-content-between align-items-center py-2 px-3" href="#">
                        <div>
                            <i class="fas fa-file me-2 text-warning"></i> 3 new reports
                        </div>
                        <small class="text-muted">2 days</small>
                    </a>
                </li>

                <li>
                    <hr class="my-1">
                </li>

                <li>
                    <a class="dropdown-item text-center text-primary fw-semibold py-2" href="#">See All
                        Notifications</a>
                </li>
            </ul>

        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown"
                role="button" data-toggle="dropdown" aria-expanded="false">
                <img src="{{ asset('profile-picture/' . (session('profile_picture') ?? 'gambar.png')) }}"
                    onerror="this.src='{{ asset('profile-picture/gambar.png') }}'" alt="Profile"
                    class="rounded-circle border border-2 border-primary shadow-sm" width="32" height="32">
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 p-2"
                aria-labelledby="profileDropdown">
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="#" data-toggle="modal"
                        data-target="#uploadModal">
                        <i class="fas fa-image me-2 text-primary"></i> Ganti Profile Picture
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="{{ url('/logout') }}">
                        <i class="fas fa-sign-out-alt me-2 text-danger"></i> Logout
                    </a>
                </li>
            </ul>
        </li>

    </ul>
</nav>


<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Ganti Foto Profil</h5>
                <button type="button" class="btn-close" data--dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ url('upload-profile') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="profilePicture" class="form-label">Pilih gambar baru</label>
                        <input class="form-control" type="file" id="profi lePicture" name="profilePicture"
                            accept="image/*" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
    .badge-notif {
        font-size: 0.65rem;
        padding: 3px 6px;
        line-height: 1;
    }

    .dropdown-item.custom-hover:hover {
        background-color: #f0f0f0;
        color: #000;
    }
</style>
