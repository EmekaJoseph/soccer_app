import STATUS_CODES from '../config/statusCodes'
const bcrypt = require('bcrypt');
import UserModel from '../models/UserModel';
import jwtFxn from '../useFxn/jwt';

const AuthController = {
    async login(req: any, res: any) {
        const { email, password } = req.body;
        if (!email || !password) {
            return res.status(STATUS_CODES.INVALID_CONTENT).send('Please complete data');
        }

        try {
            const user = await UserModel.findOne({ where: { email: email } });
            const isValidCredentials = user && await bcrypt.compare(password, user.password);
            if (!isValidCredentials) {
                return res.status(STATUS_CODES.INVALID_CONTENT).send('Invalid username or password');
            }

            const token = jwtFxn.signToken(user);
            res.status(STATUS_CODES.CREATED).send({ token });
        } catch (error) {
            res.status(STATUS_CODES.INTERNAL_SERVER_ERROR).send({ error });
        }
    },

    async signup(req: any, res: any) {
        const request = req.body;
        const requiredFields = ['email', 'phone', 'password', 'username', 'country', 'state', 'address'];

        if (!requiredFields.every(field => request[field])) {
            return res.status(STATUS_CODES.INVALID_CONTENT).send('Please complete all fields');
        }

        try {
            const existingUser = await UserModel.findOne({ where: { email: request.email } });
            if (existingUser) {
                return res.status(STATUS_CODES.CONFLICT).send('Email is already registered');
            }

            const hashedPassword = await bcrypt.hash(request.password, 10);
            const user = await UserModel.create({ email: request.email, password: hashedPassword });
            const token = jwtFxn.signToken(user);

            res.status(STATUS_CODES.CREATED).send({ token });
        } catch (error) {
            res.status(STATUS_CODES.INTERNAL_SERVER_ERROR).send({ error });
        }
    }
};

export default AuthController;
