const { Router } = require('express');
const TournamentContoller = require('../controllers/TournamentContoller');
const authMiddleware = require('../middleware/authMiddleware');

const router = Router();

// routes
router.get('/tournaments', authMiddleware, TournamentContoller.getTournaments)
router.get('/tournaments/:id', TournamentContoller.getTournamentByID)

module.exports = router 