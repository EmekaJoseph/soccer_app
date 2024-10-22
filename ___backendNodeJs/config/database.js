// const mysql = require('mysql2/promise');

// const DB = mysql.createPool({
//   connectionLimit: 10,
//   host: 'localhost',
//   user: 'root',
//   password: '',
//   database: 'soccer_app'
// });



// require('dotenv').config();
const { Sequelize } = require('sequelize');

// Initialize Sequelize
const DB = new Sequelize(
  process.env.DB_DATABASE,
  process.env.DB_USERNAME,
  process.env.DB_PASSWORD,
  {
    dialect: process.env.DB_CONNECTION,
    host: process.env.DB_HOST,
  }
);

// Export the sequelize instance for use in other files
module.exports = DB;



