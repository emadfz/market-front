<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
//use Illuminate\Foundation\Inspiring;

class EndAuction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'endauction';

    /**
     * The console command description.
     *
     * @var string
     */
 //   protected $description = 'Display an inspiring quote';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        
        $auction_mongo_data=\App\Models\Auction::get();
        $mytime = \Carbon\Carbon::now();
        $endedAuctions=\App\Models\ProductAuction::where('end_datetime','<=',$mytime)->where('auction_status','open')->get();
        //$endedAuctions->auction_status='close';
        //dd($endedAuctions);
        $products=\App\Models\Product::whereIn('id',$endedAuctions->pluck('product_id'))->get();
        
        foreach($endedAuctions as $endedAuction){            
            $data=$auction_mongo_data->where('productId',$endedAuction->product_id)->sortByDesc('createdAt')->first();
            //$updateData=array();
            $endedAuction->auction_status='close';
            if(isset($data) && !empty($data)){
                $endedAuction->auction_winner_id=@$data->user_id;
                    try{
                        $a=\Mail::send('front.email_templates.render_email', ['html'=>'Congratulations You won the Auciton product - '.$products->where('id',$endedAuction->product_id)->first()->name], function ($message) use($data) {
                            
                            if(isset($data) && !empty($data)){
                                    $message->from('info@devanche.com', 'info');
                                    $message->to($data->email, $data->username);
                                    $message->subject('Congratulations You won the Auciton');
                            }

                            //$message->setBody($event->mailData['emailContent']);
                        });
                        
                    }
                    catch(\Exception $e){
                        
                        //echo $e->getMessage();
                    }
            $endedAuction->save();    
            }                        
            
        }
        //die;
        //$endedAuctions->update(['auction_status'=>'close']);
        //dd($endedAuctions);
        //$this->comment(PHP_EOL.Inspiring::quote().PHP_EOL.$mytime->toDateTimeString());
    }
}
