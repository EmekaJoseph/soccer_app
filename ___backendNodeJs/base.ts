const express = require('express');
const morgan = require('morgan');
require('dotenv').config();

const publicRoutes = require('./routes/publicRoutes');
const authRoutes = require('./routes/authRoutes');
// const protectedRoutes = require('./routes/protectedRoutes');

// rest object
const api = express();

// middlewares
api.use(express.json());
api.use(express.urlencoded({ extended: true }));
api.use(morgan('dev'));

// routes
api.use('/api/v1', publicRoutes);
api.use('/api/auth', authRoutes);
// api.use('/api/protected', protectedRoutes);


// port
const PORT = process.env.PORT || 8081;

// listen
api.listen(PORT, () => {
    console.log('Nodejs server running on PORT:' + PORT);
});