<div class="modal fade" id="loadDisposal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content px-3">
            <div class="modal-header border-bottom-0">
                <div class="row">
                    <div class="col-12 justify-content-between">
                        <h5 class="modal-title text-left mt-3" id="exampleModalLabel">Product Disposal List</h5>                        
                    </div>
                </div>          
            </div>
            <div class="modal-body mt-0">
                <table class="table table-hover table-bordered mb-5" id="disposalTable">
                    <thead>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Stk Unit</th>
                        <th>UnitCost</th>
                        <th>Stk Qty</th>
                        <th>Disposal Qty</th>
                        <th>Loss Amt </th>
                        <th class="text-center text-danger" style='width:50px;'><i class="fa fa-bolt"></i></th>
                    </thead>
                    <tbody>                        
                    </tbody>
                </table>
                <div class="mx-1">
                    <form action="{{ route('admin.product.disposal.store') }}" method="POST" role="form"
                    enctype="multipart/form-data" id="porduct-disposal-form">
                    @csrf
                        <input type="hidden" id="product_lists" name="product_lists" value="">
                        <div class="row pb-3">
                            <div class="col-md-6 col-12">
                                <label class="control-label" for="reason">Reason</label>
                                <input class="form-control @error('reason') is-invalid @enderror" type="text" id="reason" name="reason" 
                                value="{{ old('reason') }}" placeholder="reason....."/>
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('reason')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>  
                            <div class="col-md-6 col-12">
                                <label class="control-label" for="remarks">Remarks</label>
                                <input class="form-control @error('remarks') is-invalid @enderror" type="text" id="remarks" name="remarks" 
                                value="{{ old('remarks') }}" placeholder="remarks.........."/>
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('remarks')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>                      
                        </div>
                        <button class="btn btn-info" type="submit" id="submit"><i
                            class="fa fa-fw fa-lg fa-check-circle"></i>Save Disposal</button>                    
                    </form>
                </div>

            </div>
            
                
                <div class="modal-footer border-top-0">
                        
                    <button type="button" class="btn bg-gradient-secondary btn-primary" data-dismiss="modal">Close</button>
                </div>
            
        </div>
    </div>
</div>


