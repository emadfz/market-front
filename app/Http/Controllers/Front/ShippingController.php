<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ShippingController extends Controller
{
    public function __construct(){
			$this->accessKey='FD163A9DFD4D8FAE';
			$this->userId='kendal1232';
			$this->password='InSpree123';	
			$this->useIntegration=true;
    }
    public  function getShippingRate($frompincode,$topincode,$serviceConstant=''){
	$rate = new \Ups\Rate(
	    $this->accessKey,
	    $this->userId,
	    $this->password,
	    $this->useIntegration
	);

	try {
            
	    $shipment = new \Ups\Entity\Shipment();

	    $shipperAddress = $shipment->getShipper()->getAddress();
	    //$shipperAddress->setPostalCode('99205');
            $shipperAddress->setPostalCode($frompincode);
            

	    $address = new \Ups\Entity\Address();
	    $address->setPostalCode($frompincode);
	    $shipFrom = new \Ups\Entity\ShipFrom();
	    $shipFrom->setAddress($address);

	    $shipment->setShipFrom($shipFrom);

	    $shipTo = $shipment->getShipTo();
	    $shipTo->setCompanyName('Test Ship To');
	    $shipToAddress = $shipTo->getAddress();
	    $shipToAddress->setPostalCode($topincode);

	    $package = new \Ups\Entity\Package();
	    $package->getPackagingType()->setCode(\Ups\Entity\PackagingType::PT_PACKAGE);
	    $package->getPackageWeight()->setWeight(100);

	    $dimensions = new \Ups\Entity\Dimensions();
	    $dimensions->setHeight(1);
	    $dimensions->setWidth(1);
	    $dimensions->setLength(1);

	    $unit = new \Ups\Entity\UnitOfMeasurement;
	    $unit->setCode(\Ups\Entity\UnitOfMeasurement::UOM_IN);

	    $dimensions->setUnitOfMeasurement($unit);
	    $package->setDimensions($dimensions);

	    $shipment->addPackage($package);

	    //dd($rate->getRate($shipment)->RatedShipment[0]);
            if(isset($serviceConstant) && !empty($serviceConstant)){
                $service = new \Ups\Entity\Service;                              
                //$service->setCode(\Ups\Entity\Service::S_STANDARD);
                //$service->setCode(\Ups\Entity\Service::S_AIR_2DAY);
                //echo $serviceConstant;die;
                //$service->setCode(constant('\Ups\Entity\Service::TT_S_US_AIR_1DAYAM'));
                $service->setCode($serviceConstant);
                //echo constant('\Ups\Entity\Service::'.$serviceConstant);die;
                $service->setDescription('test description');
                $shipment->setService($service);                                
                return $rateCharges=$rate->getRate($shipment)->RatedShipment[0]->TotalCharges->MonetaryValue;
            }
            $rateCharges=$rate->getRate($shipment);            
            //dd($rateCharges->RatedShipment);
            return view('front.shipping.shippingCalculator',compact('rateCharges','frompincode','topincode') )->render();
            //return view('rate',  compact('rateCharges'));
	}
        catch(\Ups\Exception\InvalidResponseException $e){            
            return false;
            //return $e->getMessage();
        }
        catch (Exception $e) {
            //return view('front.shipping.shippingCalculator',compact('rateCharges','pincode') )->render();
            return false;
	    //return $e->getMessage();
	}
    }
    
    
    public function getShippingServices($frompincode,$topincode){
            $timeInTransit = new \Ups\TimeInTransit($this->accessKey, $this->userId, $this->password,$this->useIntegration);

            try {
                
           /*     $request = new \Ups\Entity\TimeInTransitRequest;
                $totalWeight=100.00;
                // Addresses
                $from = new \Ups\Entity\AddressArtifactFormat;
                //$from->setPoliticalDivision3('Amsterdam');
                $from->setPostcodePrimaryLow($frompincode);
                $from->setCountryCode('US');
                $request->setTransitFrom($from);

                $to = new \Ups\Entity\AddressArtifactFormat;
                //$to->setPoliticalDivision3('Amsterdam');
                $to->setPostcodePrimaryLow($topincode);
                $to->setCountryCode('US');
                $request->setTransitTo($to);

                // Weight
                $shipmentWeight = new \Ups\Entity\ShipmentWeight;
                $shipmentWeight->setWeight($totalWeight);
                $unit = new \Ups\Entity\UnitOfMeasurement;
                $unit->setCode(\Ups\Entity\UnitOfMeasurement::UOM_LBS);
                $shipmentWeight->setUnitOfMeasurement($unit);
                $request->setShipmentWeight($shipmentWeight);

                // Packages
                $request->setTotalPackagesInShipment(1);

                // InvoiceLines
                $invoiceLineTotal = new \Ups\Entity\InvoiceLineTotal;
                $invoiceLineTotal->setMonetaryValue(10.00);
                $invoiceLineTotal->setCurrencyCode('USD');
                $request->setInvoiceLineTotal($invoiceLineTotal);

                // Pickup date
                $request->setPickupDate(new \DateTime('2016-10-18'));
                
          

                // Get data
                $times = $timeInTransit->getTimeInTransit($request);                
                
                foreach($times->ServiceSummary as $index=>$serviceSummary){                                        
                //    $times->ServiceSummary[$index]->pricing=$this->getShippingRate($frompincode,$topincode,$serviceSummary->Service->getCode());
                    echo $serviceSummary->Service->getCode().'<br/>';
                }
                die;
                dd($times);*/
                $service = new \Ups\Entity\Service;
                
                $services=[];
                foreach($service->getServices() as $serviceCodes=>$serviceDetail){
                       //$times->ServiceSummary[$index]->pricing=$this->getShippingRate($frompincode,$topincode,$serviceCodes);
                        $price=$this->getShippingRate($frompincode,$topincode,$serviceCodes);
                        if($price){
                            $services[$serviceCodes]['price']=$price;
                            $services[$serviceCodes]['name']=$serviceDetail;
                        }
                }
                
                
                return view('front.shipping.shippingServices',  compact('services','frompincode','topincode') )->render();

            }
            catch(\Ups\Exception\InvalidResponseException $e){
                return $e->getMessage();
            }
            catch (Exception $e) {
                //return view('front.shipping.shippingCalculator',compact('rateCharges','pincode') )->render();
                return $e->getMessage();
            }
    }
    
    
}
