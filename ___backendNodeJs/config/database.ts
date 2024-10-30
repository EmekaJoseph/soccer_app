const { Sequelize } = require('sequelize');
const { DB_CONNECTION, DB_DATABASE, DB_USERNAME, DB_PASSWORD, DB_HOST } = process.env;

const DATABASE = new Sequelize(DB_DATABASE, DB_USERNAME, DB_PASSWORD,
  { dialect: DB_CONNECTION, host: DB_HOST, }
);

export default DATABASE;



