const MongoClient = require('mongodb').MongoClient;
const url = "mongodb+srv://myUser:<db_password>@cluster0.mbgk15z.mongodb.net/?appName=Cluster0";
    
  console.log('one')
  MongoClient.connect(url, function(err, db) {
    
  if(err) { console.log(err); }
  else {
    console.log('two')
    var dbo = db.db("library");
    var collection = dbo.collection('books');
    console.log("Success!");
    db.close();
  }
  console.log('three')
});