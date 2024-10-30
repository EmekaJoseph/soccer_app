import { DataTypes } from 'sequelize';
import DATABASE from '../config/database';


const Table = {
    options: {
        tableName: 'tbl_tournament',
        timestamps: false,
    },
    columns: {
        tour_id: { type: DataTypes.STRING, primaryKey: true },
        tour_title: DataTypes.STRING,
        tour_type: DataTypes.STRING,
        tour_logo: DataTypes.STRING,
        tour_desc: DataTypes.STRING,
    }
}

const TournamentModel = DATABASE.define('TournamentModel', Table.columns, Table.options);

export default TournamentModel;