<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ route('admin.dashboard') }}" class="brand-link text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <i class="fas fa-wallet" style="font-size: 24px; color: white;"></i>
    <span class="brand-text font-weight-bold" style="color: white;">EasyPay Admin</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
          <i class="fas fa-user-shield text-white"></i>
        </div>
      </div>
      <div class="info">
        <a href="#" class="d-block">
          <strong>{{ Auth::user()->name ?? 'Admin' }}</strong>
          <br>
          <small class="text-muted">Administrator</small>
        </a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Dashboard -->
        <li class="nav-item">
          <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
              <span class="badge badge-info right">New</span>
            </p>
          </a>
        </li>

        <!-- Divider -->
        <li class="nav-header">MANAGEMENT</li>

        <!-- Transactions -->
        <li class="nav-item">
          <a href="{{ route('admin.transaction.index') }}" class="nav-link {{ Request::routeIs('admin.transaction.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-exchange-alt"></i>
            <p>
              Transactions
            </p>
          </a>
        </li>

        <!-- Divider -->
        <li class="nav-header">SETTINGS</li>

        <!-- Logout -->
        <li class="nav-item">
          <a href="{{ route('admin.auth.logout') }}" class="nav-link text-danger">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>
              Logout
            </p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
