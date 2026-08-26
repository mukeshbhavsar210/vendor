<div class="card">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-8">                      
                <h4 class="card-title">Recents Order</h4>                      
            </div>
            <div class="col-4"> 
                <select id="orderFilter" class="form-select form-select-sm w-auto">
                    <option value="today" selected>Today</option>
                    <option value="week" >Last Week</option>
                    <option value="month">This Month</option>
                    <option value="year">This Year</option>
                </select>
                {{-- 
                <div class="dropdown">
                    <a href="#" class="btn bt btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icofont-calendar fs-5 me-1"></i> This Month<i class="las la-angle-down ms-1"></i>
                    </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="#">Today</a>
                    <a class="dropdown-item" href="#">Last Week</a>
                    <a class="dropdown-item" href="#">Last Month</a>
                    <a class="dropdown-item" href="#">This Year</a>
                </div> --}}                                   
            </div>
        </div>                                    
        <div class="table-responsive">
            <table class="table mb-0">
                <tbody id="recentOrdersContainer">
                    @include('admin.dashboard.recent-orders', ['orders' => $recentOrders])                        
                </tbody>
            </table>
        </div>
    </div> 
</div> 