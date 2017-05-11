var MongoClient = require('./node_modules/mongodb').MongoClient;
var MongoDbName = 'mongodb://10.2.1.85:27017/marketplace';
var MongodbCollection;
var ObjectId = require('mongodb').ObjectID;
var DB;
MongoClient.connect(MongoDbName, function (err, db) {
    if (err) {
        return console.dir(err);
    }
    MongodbCollection = db.collection('message');    
    auto_reconnect:true;
    DB=db;
    console.log('connect mongodb');
});


var query = function (sql, callback) {}
exports.query = query;

exports.save_msg = function (data, callback) {

    console.log('insert Data: ' + JSON.stringify(data));

    var docs = [{
            fromUserId: data.fromUserId,
            toUserId: data.toUserId,
            text: data.text,
            username: data.username,
            greetingImage: data.greetingImage,
            datetime: data.datetime,            
            createdAt: data.createdAt,                        
            is_read: data.is_read,
        }];

    MongodbCollection.insert(docs, {w: 1}, function (err, result) {
        if (err) {
            return console.dir(err);
        }
        callback(docs[0]._id)
    });
}

exports.update_msg = function (id, callback) {
    MongodbCollection.update({_id:new ObjectId(id)}, {$set:{is_read : 'Yes'}}, { w: 1,multi: true}, function(err, result){});
}

//exports.get_msg  = function (callback) {
//    MongodbCollection.find();
//}

exports.get_msg = function(callback) {
   var cursor = MongodbCollection.find();
  return callback(cursor);
//   cursor.each(function(err, doc) {      
//      if (doc != null) {
//         console.dir(doc);
//        return doc;
//      } else {
//         
//      }
//   });
};

exports.place_bid = function (data, callback) {
            var docs=data;
            //console.log(docs);
//        var docs = [{
//            bid_amount: data.bid_amount,
//        }];
        DB.collection('auction').insert(docs, {w: 1}, function (err, result) {
            if (err) {
                return console.dir(err);
            }
       //callback(docs[0]._id)
    });
}

exports.get_bids = function (callback) {
    var cursor = DB.collection('auction').find();
    
//       cursor.each(function(err, doc) {      
//      if (doc != null) {
//         console.dir(doc);
//        return doc;
//      } else {
//         
//      }
      return callback(cursor);
   //});
    
}

exports.getLastBid = function (productId,callback) {
    
    cursor = DB.collection('auction').find({productId:productId}).sort( { createdAt : -1 } ).limit(1);
    //cursor = DB.collection('auction').find({$where: function() { return this.productId == productId }}).sort( { createdAt : -1 } ).limit(1);                
    return callback(cursor);
}