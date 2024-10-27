const jwt = require('jsonwebtoken');
const { JWT_SECRET, JWT_EXPIRATION } = process.env;

const signToken = (user) => {
    return jwt.sign({ user }, JWT_SECRET, {
        expiresIn: JWT_EXPIRATION || '1h',
    });
};

const verifyToken = (token) => {
    return jwt.verify(token, JWT_SECRET);
};

module.exports = { signToken, verifyToken };
