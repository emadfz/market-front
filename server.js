var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
//var Redis = require('ioredis');
var mongoDbHelper = require('./mongoDbHelper');


var nodemailer = require('nodemailer');
var transporter = nodemailer.createTransport({
        service: 'Gmail',
        auth: {
            user: 'ronak.dattani@indianic.com', // Your email id
            pass: 'ronak_indianic@123' // Your password
        }
    });


    
/*var opts = {
    logDirectory:'logfiles',
    fileNamePattern:'test.log',
    dateFormat:'YYYY.MM.DD',
    
};
var log = require('simple-node-logger').createRollingFileLogger( opts );*/

//var redis = new Redis();


//io.on('send-message7', function(channel, message) {
//    console.log('Message Recieved: ' + message);
//    message = JSON.parse(message);
//    io.emit(channel + ':' + message.event, message.data);
//});





io.sockets.on('connection', function (socket) {
    
    
    
    
    socket.on('loadAuction', function(productId) {
        socket.productId=productId;
        //log.info('auction-'+socket.productId);
        //console.log('auction-'+socket.productId);
        socket.join('auction-'+socket.productId);   
    });
    socket.on('send-message', function(message) {
         mongoDbHelper.save_msg(message, function(id) {
            io.sockets.emit('message-'+message.toUserId, message,id);
         });
    });
    socket.on('update-msgflag', function(id) {
         mongoDbHelper.update_msg(id, function() {});
    });
    socket.on('get-message', function() {
        mongoDbHelper.get_msg(function(result) {
            io.sockets.emit('message-'+message.toUserId,result);
         });
        
    });
    
    socket.on('place-bid',function(data){        
        //log.info('place bid call');
         mongoDbHelper.getLastBid(data.productId, function(cursor) {
                           
            cursor.each(function(err, doc) {
                  if (doc != null) {
                        var mailOptions = {
                            from: 'ronak.dattani@indianic.com', // sender address
                            to: doc.email, // list of receivers
                            subject: 'Outbid Alert', // Subject line
                            text: 'Hello', //, // plaintext body
                            html: '<b>You have been outbided with the amount $'+data.bid_amount+'</b>' // You can choose to send an HTML body instead
                        };
                        transporter.sendMail(mailOptions, function(error, info){
                            if(error){
                                console.log(error);    
                                //log.info(error);
                            }else{
                                console.log('Message sent: ' + info.response);        
                                //log.info('Message sent: ' + info.response);        
                                
                            };
                        });
                     
                    //return doc;
                  } else {
                     
                  }
            });
        });
        

        
        
        mongoDbHelper.place_bid(data, function() {});
        //console.log(data);
        //log.info('data: ' + data);       
        socket.broadcast.to('auction-'+socket.productId).emit('getNewBid',data);
        socket.emit('getNewBid',data);
    });
});

http.listen(3005, function(){
    console.log('Listening on Port 3005');
     //log.info('Server listening at port 3005');
});


