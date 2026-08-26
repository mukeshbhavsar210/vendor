<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-10">                      
                <h5>Monthly Avg. Income</h5>
            </div>
            <div class="col-2"> 
                <select onchange="loadDashboard(this.value)" class="form-select">
                    <option value="today">Today</option>
                    <option value="week">Last Week</option>
                    <option value="month">This Month</option>
                    <option value="year" selected>This Year</option>
                </select>
            </div>
        </div>                                    
    </div>

    <div class="card-body pt-0">
        <div class="d-flex">
            <div class="y-axis d-flex flex-column justify-content-between" style="margin-top:20px;"></div>            
            <div class="row" id="chartContainer"></div>
        </div>
        
        <div class="row mt-3">
            <div class="col-md-6 col-lg-3"> 
                <div class="card shadow-none border mb-3 mb-lg-0">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col text-center">         
                                <span class="fs-18 fw-semibold" id="totalIncome">₹0</span>                                
                                <h6 class="text-uppercase text-muted mt-2 m-0">Today's Revenue</h6>                
                            </div>
                        </div> 
                    </div>
                </div>                      
            </div>
            <div class="col-md-6 col-lg-3"> 
                <div class="card shadow-none border mb-3 mb-lg-0">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col text-center">              
                                <span class="fs-18 fw-semibold" id="conversionRate"></span>                                                                                          
                                <h6 class="text-uppercase text-muted mt-2 m-0">Conversion Rate</h6>                
                            </div>
                        </div> 
                    </div>
                </div>                      
            </div>
            
            <div class="col-md-6 col-lg-3"> 
                <div class="card shadow-none border mb-3 mb-lg-0">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col text-center">       
                                ₹<span id="expenses" class="fs-18 fw-semibold"></span>    
                                <h6 class="text-uppercase text-muted mt-2 m-0">Total Expenses</h6>                
                            </div>
                        </div> 
                    </div>
                </div>                      
            </div> 
            <div class="col-md-6 col-lg-3"> 
                <div class="card shadow-none border mb-3 mb-lg-0">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col text-center">                
                                ₹<span id="avgValue" class="fs-18 fw-semibold"></span>
                                <h6 class="text-uppercase text-muted mt-2 m-0">Avg. Value</h6>                
                            </div>
                        </div> 
                    </div>
                </div>                      
            </div>                             
        </div>
    </div> 
</div>