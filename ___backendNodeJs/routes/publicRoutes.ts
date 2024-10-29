const publicExpress = require('express');
const TournamentContoller = require('../controllers/TournamentContoller');
const authMiddleware = require('../middleware/authMiddleware');

const publicRouter = publicExpress.Router();

// routes
publicRouter.get('/tournaments', authMiddleware, TournamentContoller.getTournaments)
publicRouter.get('/tournaments/:id', TournamentContoller.getTournamentByID)

module.exports = publicRouter 