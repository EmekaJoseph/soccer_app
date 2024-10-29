const STATUS_CODES = require('../config/statusCodes')
const bcrypt = require('bcrypt');
const UserModel = require('../models/UserModel');
const { signToken } = require('../utils/jwt');

const login = async (req, res) => {
    const { email, password } = req.body;
    if (!email || !password) return res.status(STATUS_CODES.INVALID_CONTENT).send('Please complete data');

    try {
        const user = await UserModel.findOne({ where: { email } });
        const isValidCredentials = user && await bcrypt.compare(password, user.password);
        if (!isValidCredentials) return res.status(STATUS_CODES.INVALID_CONTENT).send('Invalid username or password');

        const token = signToken(user);
        res.status(STATUS_CODES.CREATED).send({ token });
    } catch (error) {
        res.status(STATUS_CODES.INTERNAL_SERVER_ERROR).send({ error });
    }
};

const signup = async (req, res) => {
    const requiredFields = ['email', 'phone', 'password', 'username', 'country', 'state', 'address'];

    if (!requiredFields.every(field => req.body[field])) {
        return res.status(STATUS_CODES.INVALID_CONTENT).send('Please complete all fields');
    }

    try {
        const existingUser = await UserModel.findOne({ where: { email } });
        if (existingUser) {
            return res.status(STATUS_CODES.CONFLICT).send('Email is already registered');
        }

        const hashedPassword = await bcrypt.hash(password, 10);

        const user = await UserModel.create({ email, password: hashedPassword });

        const token = signToken(user);

        res.status(STATUS_CODES.CREATED).send({ token });
    } catch (error) {
        res.status(STATUS_CODES.INTERNAL_SERVER_ERROR).send({ error });
    }
};



module.exports = { login, signup };
