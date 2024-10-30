const publicExpress = require('express');
import TournamentContoller from '../controllers/TournamentContoller';
import authMiddleware from '../middleware/authMiddleware';


const publicRouter = publicExpress.Router();

// routes
publicRouter.get('/tournaments', authMiddleware, TournamentContoller.getTournaments)
publicRouter.get('/tournaments/:id', TournamentContoller.getTournamentByID)

module.exports = publicRouter