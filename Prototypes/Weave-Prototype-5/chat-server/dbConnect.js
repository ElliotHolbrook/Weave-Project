var mysql = require('mysql');

var con = mysql.createConnection({
    host: "localhost",
    user: "root",
    password: "",
    database: "kayakraft_social"
  });

  con.connect((err)=> {
    if (err) throw err;
    console.log("Connected to MySQL Database");
  });

  module.exports = con;
  