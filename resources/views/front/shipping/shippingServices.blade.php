<div class="modal-inner clearfix">
<a href="#" class="close" data-dismiss="modal">close</a>
      	<ul class="edit-cartaddress clearfix">
        	<li>
            	<div class="col-md-6 left">
                	<div class="custom-radio">
                	<label for="editoption">
                    	<input type="radio" name="editaddress" value="red" id="editoption"><span></span>
                        Ganesh Hulle 123 Victory Drive Delhi,IN 56789
                    </label>
                    </div>
                	<a href="#" title="Edit" class="link">Edit</a>
                </div>
                <div class="col-md-6 right">
                	<div class="vertical custom-radio">
                    	<label for="freeship">
                    		<input type="radio" name="shipoption" value="red" id="freeship" checked><span></span>
                        	Free Shipping
                    	</label>                            
                            @foreach($services as $service)
                                <label for="fedexship" class="bluecolor">
                                    <input type="radio" name="shipoption" value="red" id="fedexship"><span></span>
                                    {{$service['name']}}<em> ${{$service['price']}}</em><br/>
                                </label>
                            @endforeach
                    </div>
                </div>
            </li>
            <li>
            	<div class="col-md-6 left">
                	<div class="custom-radio">
                	<label for="editoption2">
                    	<input type="radio" name="editaddress" value="red" id="editoption2" checked><span></span>
                        Ankur Dossi 2675 Liberty Avenue Ontario, CA 67890iu
                    </label>
                    </div>
                	<a href="#" title="Edit" class="link">Edit</a>
                </div>
                <div class="col-md-6 right">
                	<div class="vertical custom-radio">
                    	<label for="freeship2" class="bluecolor">
                    		<input type="radio" name="shipoption2" value="red" id="freeship2" checked><span></span>
                        	USPS 14-day delivery<em>$15.00</em>
                    	</label>
                        <label for="fedexship2" class="bluecolor">
                    		<input type="radio" name="shipoption2" value="red" id="fedexship2"><span></span>
                        	FedEx 2-day delivery<em>$45.00</em>
                    	</label>
                    </div>
                </div>
            </li>
        </ul>  
        <div class="clearfix text-right">
            <a href="#addaddressmodal" title="Add New Address" class="link pull-left" data-toggle="modal" data-dismiss="modal">Add New Address</a>
            <input type="submit" title="Done" class="btn btn-primary" value="Done">
        </div>	
</div>	