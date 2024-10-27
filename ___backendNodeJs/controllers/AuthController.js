const STATUS_CODES = require('../config/statusCodes')
const bcrypt = require('bcrypt');
const UserModel = require('../models/UserModel');
const { signToken } = require('../utils/jwt');

const login = async (req, res) => {

    try {
        const { email, password } = req.body;
        const user = await UserModel.findOne({ where: { email } });
        if (!user || !(await bcrypt.compare(password, user.password))) {
            return res.status(STATUS_CODES.INVALID_CONTENT).send('Invalid username or password');
        }
        const token = signToken(user);
        res.status(STATUS_CODES.OK).send({ token: token })
    } catch (error) {
        return res.status(STATUS_CODES.INTERNAL_SERVER_ERROR).send({ error: error });

    }
}


module.exports = { login };
