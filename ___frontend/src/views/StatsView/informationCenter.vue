<template>
    <emptyDataComponent v-if="!stats.tourTeamsInfo.length" icon="bi bi-shield">No teams yet.</emptyDataComponent>

    <div v-else class="teams">
        <article v-for="team in stats.tourTeamsInfo" :key="team.team_id" class="team-card">
            <header class="team-head">
                <span class="crest"><TeamBadge :badge="team.team_badge" :color="team.team_color" :size="30" /></span>
                <div class="team-id">
                    <h2>{{ team.team_name }}</h2>
                    <p v-if="subtitle(team)">{{ subtitle(team) }}</p>
                </div>
                <div v-if="team.rating != null" class="rating" :title="`Won ${team.rating}% of available points`">
                    <span class="rating-value">{{ team.rating }}%</span>
                    <span class="rating-label">Rating</span>
                </div>
            </header>

            <dl class="numbers">
                <div v-for="stat in statsOf(team)" :key="stat.label">
                    <dt :title="stat.title">{{ stat.label }}</dt>
                    <dd>{{ stat.value }}</dd>
                </div>
            </dl>

            <details v-if="squad(team.team_id).length" class="squad">
                <summary>
                    Squad <span>{{ squad(team.team_id).length }}</span>
                    <i class="bi bi-chevron-down" aria-hidden="true"></i>
                </summary>
                <ul>
                    <li v-for="player in squad(team.team_id)" :key="player.player_id">
                        <img v-if="player.image" :src="fx.resolvePhotoSrc(player.image)" width="32" height="32" alt="">
                        <span v-else class="no-photo"><i class="bi bi-person" aria-hidden="true"></i></span>
                        <div class="player">
                            {{ player.first_name }} {{ player.last_name }}
                            <small v-if="player.info">{{ player.info }}</small>
                        </div>
                    </li>
                </ul>
            </details>
        </article>
    </div>
</template>

<script setup lang="ts">
import TeamBadge from '@/components/TeamBadge.vue'
import { useStatsStore } from '@/store/statsStore'
import fx from '@/store/useFunctions'

const stats = useStatsStore()

const squad = (teamId: string) => stats.tourPlayers.filter((p) => p.team_id === teamId)

const subtitle = (team: any) =>
    [team.group_in && `Group ${team.group_in}`, team.manager && `Manager: ${team.manager}`].filter(Boolean).join(' · ')

const statsOf = (team: any) => [
    { label: 'P', title: 'Played', value: team.played },
    { label: 'W', title: 'Won', value: team.won },
    { label: 'D', title: 'Drawn', value: team.draw },
    { label: 'L', title: 'Lost', value: team.lost },
    { label: 'GF', title: 'Goals for', value: team.scored },
    { label: 'GA', title: 'Goals against', value: team.conceded },
]
</script>

<style scoped>
.teams {
    display: grid;
    gap: 12px;
}

.team-card {
    border: 1px solid var(--line);
    border-radius: 14px;
    background: var(--ink-900);
}

.team-head {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 18px;
}

.crest {
    display: grid;
    flex: none;
    place-items: center;
    width: 48px;
    height: 48px;
    border: 1px solid var(--line);
    border-radius: 50%;
    background: var(--ink-800);
}

.team-id {
    flex: 1;
    min-width: 0;
    overflow-wrap: anywhere;
}

.public-page .team-id h2 {
    margin: 0;
    font-size: 1.125rem;
    letter-spacing: -0.02em;
}

.team-id p {
    margin: 2px 0 0;
    font-size: 0.8125rem;
}

.rating {
    display: flex;
    flex: none;
    flex-direction: column;
    align-items: flex-end;
}

.rating-value {
    color: var(--volt);
    font-family: var(--font-mono);
    font-size: 1.125rem;
    font-weight: 500;
}

.rating-label {
    font-size: 0.75rem;
}

.numbers {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    margin: 0;
    border-top: 1px solid var(--line);
}

.numbers > div {
    padding: 12px 4px;
    text-align: center;
}

.numbers > div + div {
    border-left: 1px solid var(--line);
}

.numbers dt {
    font-family: var(--font-mono);
    font-size: 0.6875rem;
    font-weight: 400;
    letter-spacing: 0.06em;
}

.numbers dd {
    margin: 4px 0 0;
    color: var(--fg);
    font-family: var(--font-mono);
    font-size: 1rem;
    font-weight: 500;
}

.squad {
    border-top: 1px solid var(--line);
}

.squad summary {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 14px 18px;
    color: var(--fg);
    font-size: 0.875rem;
    font-weight: 500;
    list-style: none;
    cursor: pointer;
}

.squad summary::-webkit-details-marker {
    display: none;
}

.squad summary span {
    color: var(--fg-muted);
    font-family: var(--font-mono);
    font-size: 0.8125rem;
    font-weight: 400;
}

.squad summary i {
    margin-left: auto;
    color: var(--fg-muted);
    transition: transform 0.15s ease;
}

.squad[open] summary i {
    transform: rotate(180deg);
}

.squad ul {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 12px;
    margin: 0;
    padding: 4px 18px 18px;
    list-style: none;
}

.squad li {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.squad img,
.no-photo {
    flex: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.no-photo {
    display: grid;
    place-items: center;
    background: var(--ink-800);
}

.player {
    min-width: 0;
    color: var(--fg);
    font-size: 0.875rem;
    line-height: 1.3;
    overflow-wrap: anywhere;
}

.player small {
    display: block;
    color: var(--fg-muted);
    font-size: 0.75rem;
}
</style>
