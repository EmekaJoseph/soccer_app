const express = require('express');
const morgan = require('morgan');
const dotenv = require('dotenv');

// rest object
const api = express();

// env config
dotenv.config();

// middlewares
api.use(express.json());
api.use(morgan('dev'));

// routes
api.use('/api/v1', require('./routes/api'))
// api.get('/test', (req, res) => {
//     res.status(200).send('<h1>This is easy to understand!!!</h1>')
// });

// port
const PORT = process.env.PORT || 8081;

// listen
api.listen(PORT, () => { });