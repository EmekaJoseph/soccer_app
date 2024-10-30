import { DataTypes } from 'sequelize';
import DATABASE from '../config/database';


const Table = {
    options: {
        tableName: 'tbl_teams',
        timestamps: false,
    },
    columns: {
        team_id: { type: DataTypes.STRING, primaryKey: true },
        team_name: DataTypes.STRING,
        tour_id: DataTypes.STRING,
        match_played: DataTypes.INTEGER,
        group_in: DataTypes.STRING,
        address: DataTypes.STRING,
        manager: DataTypes.STRING,
        team_brief: DataTypes.TEXT,
        team_badge: DataTypes.STRING,
        team_color: DataTypes.STRING,
        created_at: DataTypes.STRING,
        updated_at: DataTypes.STRING,
    }
}

const TeamModel = DATABASE.define('TeamModel', Table.columns, Table.options);

export default TeamModel;