const authExpress = require('express');
import AuthController from '../controllers/AuthController';

const authRouter = authExpress.Router();

authRouter.post('/login', AuthController.login);
authRouter.post('/signup', AuthController.signup);

module.exports = authRouter;
