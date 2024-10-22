const { Router } = require('express');
const TournamentContoller = require('../controllers/TournamentContoller');

const router = Router();

// routes
router.get('/tournaments', TournamentContoller.getTournaments)
router.get('/tournaments/:id', TournamentContoller.getTournamentByID)

module.exports = router 