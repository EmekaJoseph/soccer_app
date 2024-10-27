const { DataTypes } = require('sequelize');
const DATABASE = require('../config/database');


// class User extends Model {
//     static async hashPassword(password) {
//         return await bcrypt.hash(password, 10);
//     }



const Table = {
    options: {
        tableName: 'tbl_users',
        timestamps: false,
    },
    columns: {
        user_id: { type: DataTypes.STRING, primaryKey: true },
        email: DataTypes.STRING,
        password: DataTypes.STRING,
        firstname: DataTypes.STRING,
        lastname: DataTypes.STRING,
        no_of_cups: DataTypes.INTEGER,
        no_of_leagues: DataTypes.INTEGER,
        role: DataTypes.STRING,
    }
}


const UserModel = DATABASE.define('UserModel', Table.columns, Table.options);


// Hash the password before saving the user
// UserModel.beforeCreate(async (user) => {
//     user.password = await User.hashPassword(user.password);
// });

module.exports = UserModel;
