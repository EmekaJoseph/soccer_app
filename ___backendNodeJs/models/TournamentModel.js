const { DataTypes } = require('sequelize');
const DB = require('../config/database');

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

const TournamentModel = DB.define('TournamentModel', Table.columns, Table.options);

module.exports = TournamentModel;