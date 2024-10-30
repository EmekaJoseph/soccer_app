import STATUS_CODES from '../config/statusCodes'
import TournamentModel from '../models/TournamentModel';

const TournamentController = {
    async getTournaments(req: any, res: any) {
        try {
            const data = await TournamentModel.findAll();
            res.status(STATUS_CODES.OK).send(data);
        } catch (error) {
            res.status(STATUS_CODES.INTERNAL_SERVER_ERROR).send(error);
        }
    },

    async getTournamentByID(req: any, res: any) {
        try {
            const tour_id = req.params.id;
            const data = await TournamentModel.findByPk(tour_id);
            res.status(STATUS_CODES.OK).send(data);
        } catch (error) {
            res.status(STATUS_CODES.INTERNAL_SERVER_ERROR).send(error);
        }
    }
};

export default TournamentController;
