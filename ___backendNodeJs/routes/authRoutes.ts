// routes/auth.js
const authExpress = require('express');
const AuthController = require('../controllers/AuthController');

const authRouter = authExpress.Router();

authRouter.post('/login', AuthController.login);

module.exports = authRouter;
