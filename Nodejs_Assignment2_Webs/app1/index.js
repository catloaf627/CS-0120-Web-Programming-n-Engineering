const fs = require('fs');
const path = require('path');
const { MongoClient } = require('mongodb');

const uri = "mongodb+srv://myUser:myDBPassword@cluster0.mbgk15z.mongodb.net/?appName=Cluster0";
const client = new MongoClient(uri);
const dbName = "zipsDB";
const collectionName = "places";

async function run() {
  const csvFile = path.join(__dirname, "zips.csv");
  const fileData = fs.readFileSync(csvFile, "utf-8").trim();

  const lines = fileData.split("\n");
  const places = [];

  console.log("Reading CSV and building place objects...");

  for (let line of lines) {
    let [place, zip] = line.split(",");

    if (!place || !zip) continue;

    place = place.trim();
    zip = zip.trim();

    let existing = places.find(p => p.place === place);
    if (!existing) {
      places.push({ place: place, zips: [zip] });
      console.log(`Added place: ${place} with zip ${zip}`);
    } else {
      if (!existing.zips.includes(zip)) {
        existing.zips.push(zip);
        console.log(`Updated place: ${place}, added zip ${zip}`);
      }
    }
  }

  console.log(`\nTotal unique places: ${places.length}`);

  try {
    console.log("\nConnecting to MongoDB...");
    await client.connect();
    const db = client.db(dbName);
    const collection = db.collection(collectionName);

    console.log("Clearing old data...");
    await collection.deleteMany({});

    console.log("Uploading data to MongoDB...");
    await collection.insertMany(places);

    console.log("Upload complete!");
    console.log(`DB: ${dbName}, Collection: ${collectionName}`);

  } catch (err) {
    console.error("ERROR:", err);
  } finally {
    client.close();
  }
}

run();
