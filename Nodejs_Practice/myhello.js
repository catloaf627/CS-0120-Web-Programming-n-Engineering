var http = require('http');
var urlObj = require("url");
var qs = require("querystring");
http.createServer(function (req, res) {
  res.writeHead(200, {'Content-Type': 'text/html'});

  path = urlObj.parse(req.url).pathname // just the pathname
  // path = req.url; // has ? and everything

  console.log("The path is : " + path); // the console on your laptop
  if (path == '/') {
    res.write("<h1>Home</h1>")
    res.write("Sign up for the newsletter")
    res.write(
        "<form method='post' action='/process'>" +
        "<br />Name: <input type='text' name='name'>" +
        "<br />Email: <input type='text' name='email'>" +
        "<br /><input type='submit' value='Submit'>" +
        "</form>"
    );
    res.end();
  } else if (path == '/about') {
    res.write("<h1>About</h1>")
    res.write("This is the aboutpage")
    res.end();
  } else if (path == '/contact') {
    res.write("<h1>Contact</h1>")
    res.write("This is the contactpage")
    res.end();
  }  else if (path == '/process') {
    res.write("Processing")
    // GET
    // var qObj = urlObj.parse(req.url, true).query
    // res.write("The name is: " + qObj.name)
    // res.write("The email is: " + qObj.email)
    // POST
    
    var myFormData = '';
    req.on('data', newdata => { myFormData += newdata.toString();  });
    req.on('end', () => {
      name = qs.parse(myFormData).name
      email = qs.parse(myFormData).email
      res.write("The email is: " + name)
      res.write("The name is: " + email)
      res.end();
    })
  } 

}).listen(8080);