const STATUS_CODES = require('../config/statusCodes')
const TournamentModel = require('../models/TournamentModel');

const getTournaments = async (req, res) => {
    try {
        const data = await TournamentModel.findAll();
        res.status(STATUS_CODES.OK).send(data)
    } catch (error) {
        console.log(error);
        res.status(STATUS_CODES.INTERNAL_SERVER_ERROR).send({
            success: false,
            message: 'Error connecting',
            error
        })

    }
}


const getTournamentByID = async (req, res) => {
    try {
        const tour_id = req.params.id
        const data = await TournamentModel.findByPk(tour_id);
        return res.status(STATUS_CODES.OK).send(data);
    } catch (error) {
        console.log(error);
        return res.status(STATUS_CODES.INTERNAL_SERVER_ERROR).send(error);
    }
}

module.exports = { getTournaments, getTournamentByID };
