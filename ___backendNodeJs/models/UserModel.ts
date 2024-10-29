import { DataTypes } from 'sequelize';
import DATABASE from '../config/database';


// class User extends Model {
//     static async hashPassword(password) {
//         return await bcrypt.hash(password, 10);
//     }



const userTable = {
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


const UserModel = DATABASE.define('UserModel', userTable.columns, userTable.options);

module.exports = UserModel;
