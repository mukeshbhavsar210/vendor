<div class="card">
    <div class="card-body"> 
        <div class="row d-flex justify-content-center ">
            <div class="col-12 col-md-6">
                <h5 class="mb-2 fs-14">Today collection</h5>
                <h2 class="fs-22 mt-0 mb-1 fw-bold">{{ $newOrdersToday }}</h2>
                <p class="mb-0 text-truncate text-muted {{ $percentageChange >= 0 ? 'text-success' : 'text-danger' }}">
                    <span class="text-success">
                        <i class="mdi mdi-trending-up"></i>{{ $percentageChange >= 0 ? '+' : '' }}{{ number_format($percentageChange, 1) }}%
                    </span> 
                    New Sessions Today
                </p>
            </div>
            <div class="col-12 col-md-6 align-self-center text-start text-md-end">
                <button type="button" class="btn btn-outline-primary btn-sm px-2 mt-2 mt-md-0 ">View  Report</button>  
            </div>
        </div>  
    </div> 
</div>