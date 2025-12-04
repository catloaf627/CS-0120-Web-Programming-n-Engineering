const MongoClient = require('mongodb').MongoClient;
const url = "mongodb+srv://myUser:myPassword@cluster0.mbgk15z.mongodb.net/?appName=Cluster0";
function main() 
{
  MongoClient.connect(url, function(err, db) {
  if(err) { return console.log(err); }
  
    var dbo = db.db("books");
    var collection = dbo.collection('mybooks');
  
    var newData = {"title": "Who Ate the Cheese", "author": "Fin Haddie"};
    collection.insertOne(newData, function(err, res) {
       if (err) { return console.log(err); }
       console.log("new document inserted");
  });
});
}
main();