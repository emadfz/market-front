<div class="container ">

    <div class="col-md-8">    
        
        @foreach($rateCharges->RatedShipment as $ratedShipment)
            @foreach($ratedShipment->RateShipmentWarning as $rateShipmentWarning)
                <span>{{$rateShipmentWarning}}</span><br/>
            @endforeach       
            <br/>
            <b>From {{$frompincode}} To {{$topincode}}</b>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>UnitOfMeasurement</th>
                        <th>Weight</th>                                                
                    </tr>
                </thead>
                <tbody>                    
                    <tr>
                        <th scope="row">1</th>
                        <td>Billing Weight</td>                        
                        <td>{{$ratedShipment->BillingWeight->UnitOfMeasurement->Code}}</td>
                        <td>{{$ratedShipment->BillingWeight->Weight}}</td>
                    </tr>
                </tbody>
            </table>            
            
            
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Currency Code</th>
                        <th>Value</th>                                                
                    </tr>
                </thead>
                <tbody>                    
                    <tr>
                        <th scope="row">1</th>
                        <td>Transportation Charges</td>                        
                        <td>{{$ratedShipment->TransportationCharges->CurrencyCode}}</td>
                        <td>{{number_format($ratedShipment->TransportationCharges->MonetaryValue,2)}}</td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>ServiceOptions Charges</td>                        
                        <td>{{$ratedShipment->ServiceOptionsCharges->CurrencyCode}}</td>
                        <td>{{number_format($ratedShipment->ServiceOptionsCharges->MonetaryValue,2)}}</td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        <td>Total Charges</td>                        
                        <td>{{$ratedShipment->TotalCharges->CurrencyCode}}</td>
                        <td>{{number_format($ratedShipment->TotalCharges->MonetaryValue,2)}}</td>
                    </tr>
                </tbody>
            </table>            
        
        @endforeach
    </div>  