<div class="modal fade" id="loadRequisition" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content px-3">
            <div class="modal-header border-bottom-0">
                <div class="row">
                    <div class="col-12">
                        <h5 class="modal-title text-left mt-3" id="exampleModalLabel">Search Requisition To Supplier</h5>
                    </div>
                    <div class="col-12">
                        <form class="mt-3">                    
                            <input class="form-control @error('search_requisition_no') is-invalid @enderror" type="text" id="search_requisition_no" name="search_requisition_no" 
                            value="{{ old('search_requisition_no') }}" placeholder="Requisition No"/>
                            <button type="submit" class="btn btn-primary mt-3" id="btn-requisition">Search</button>
                        </form>  
                    </div>
                    <div class="col-12">
                        <h6 class="text-center text-normal mt-2 mb-4">OR</h6>
                    </div>
                    <div class="col-12">
                        <form id="needs-validation">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">                                    
                                        <input type="text" class="form-control datetimepicker w-100" name="from_date"
                                            placeholder="From Date" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">                                    
                                        <input type="text" class="form-control datetimepicker w-100" name="to_date"
                                            placeholder="To Date" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">                                    
                                        <select name="supplierId" id="supplierId" class="form-control" required>                                           
                                            @foreach($suppliers as $supplier)
                                            <option value=""></option>                                          
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12"><button type="submit" class="btn btn-primary" id="btn-s-requisition">Search</button></div>
                            </div>
                        </form>
                    </div>
                </div>          
            </div>
            <div class="modal-body text-center mt-0">
                <table class="table table-hover table-bordered" id="requisitionTable">
                    <thead>
                        <th>Requisition No</th>
                        <th>Requisition Date</th>
                        <th>Expected Delivery Date</th>
                        <th>Total Qty</th>
                        <th>Total Cost</th>
                        <th>Action</th>
                    </thead>
                    <tbody>                        
                    </tbody>
                </table>


            </div>
            <div class=" modal-footer border-top-0">
                <button type="button" class="btn bg-gradient-secondary btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


