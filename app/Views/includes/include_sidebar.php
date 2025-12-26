<!-- Sidebar -->
<div id="sidebar">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <button id="toggleSidebarClose" class="btn-sidebar-toggle">
                    <i class="fa-solid fa-caret-left"></i>
                </button>
            </div>
        </div>
    </div>
    
    <ul class="sidebar-ul">
        <li><a href="<?php echo esc(base_url('dashboard_admin')); ?>" class="<?= ($active_segment === 'dashboard_admin') ? 'active' : '' ?>"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="<?php echo esc(base_url('outlet_productive')); ?>" class="<?= ($active_segment === 'outlet_productive') ? 'active' : '' ?>"><i class="fas fa-chart-bar"></i> Outlet Productive</a></li>
        <li><a href="<?php echo esc(base_url('sales_mission')); ?>" class="<?= ($active_segment === 'sales_mission') ? 'active' : '' ?>"><i class="fas fa-chart-bar"></i>Sales Mission</a></li>
        <li><a href="<?php echo esc(base_url('blue_device')); ?>" class="<?= ($active_segment === 'blue_device') ? 'active' : '' ?>"><i class="fas fa-home"></i> Blue Device</a></li>
    </ul>
</div>