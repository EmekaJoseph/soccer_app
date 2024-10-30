const jwt = require('jsonwebtoken');
const { JWT_SECRET, JWT_EXPIRATION } = process.env;

const jwtFxn = {
    signToken: (user: any) => {
        return jwt.sign({ user }, JWT_SECRET, {
            expiresIn: JWT_EXPIRATION || '1h',
        });
    },

    verifyToken: (token: string) => {
        return jwt.verify(token, JWT_SECRET);
    }
}

export default jwtFxn
