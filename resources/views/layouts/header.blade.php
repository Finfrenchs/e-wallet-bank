<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('admin.dashboard') }}" class="nav-link">
        <i class="fas fa-home mr-1"></i> Home
      </a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Current Date/Time -->
    <li class="nav-item d-none d-sm-inline-block">
      <span class="nav-link">
        <i class="far fa-calendar-alt mr-1"></i>
        <span id="currentDateTime"></span>
      </span>
    </li>

    <!-- Notifications Dropdown Menu -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge">3</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">3 Notifications</span>
        <div class="dropdown-divider"></div>
        <a href="{{ route('admin.transaction.index') }}" class="dropdown-item">
          <i class="fas fa-clock mr-2"></i> Pending Transactions
          <span class="float-right text-muted text-sm">just now</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
      </div>
    </li>

    <!-- User Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-user-circle"></i>
        <span class="d-none d-md-inline ml-1">{{ Auth::user()->name ?? 'Admin' }}</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <a href="#" class="dropdown-item">
          <i class="fas fa-user mr-2"></i> Profile
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-cog mr-2"></i> Settings
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{ route('admin.auth.logout') }}" class="dropdown-item text-danger">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
      </div>
    </li>

    <!-- Fullscreen -->
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
  </ul>
</nav>
<!-- /.navbar -->

<script>
  // Update current date and time
  function updateDateTime() {
    const now = new Date();
    const options = {
      weekday: 'short',
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    };
    document.getElementById('currentDateTime').textContent = now.toLocaleDateString('en-US', options);
  }

  // Update immediately and then every minute
  if (document.getElementById('currentDateTime')) {
    updateDateTime();
    setInterval(updateDateTime, 60000);
  }
</script>
