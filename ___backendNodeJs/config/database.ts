// const mysql = require('mysql2/promise');

// const DB = mysql.createPool({
//   connectionLimit: 10,
//   host: 'localhost',
//   user: 'root',
//   password: '',
//   database: 'soccer_app'
// });


const { Sequelize } = require('sequelize');
const { DB_CONNECTION, DB_DATABASE, DB_USERNAME, DB_PASSWORD, DB_HOST } = process.env;

const DATABASE = new Sequelize(DB_DATABASE, DB_USERNAME, DB_PASSWORD,
  { dialect: DB_CONNECTION, host: DB_HOST, }
);

export default DATABASE;



