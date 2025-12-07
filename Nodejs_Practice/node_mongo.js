const { MongoClient } = require("mongodb");

const url = "mongodb+srv://myUser:Test12345@cluster0.mbgk15z.mongodb.net/";
const client = new MongoClient(url);

console.log("one");

async function run() {
  try {
    await client.connect();
    console.log("two");

    const dbo = client.db("test");
    console.log("Connected!!!");
  } catch (err) {
    console.log("Connection error:", err);
  } finally {
    await client.close();
    console.log("three");
  }
}

run();
