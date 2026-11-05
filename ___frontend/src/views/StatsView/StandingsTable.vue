<script setup lang="ts">
import TeamBadge from '@/components/TeamBadge.vue'

defineProps<{ title?: string; rows: any[] }>()

const signed = (value: number) => (value > 0 ? `+${value}` : `${value}`)
</script>

<template>
    <section class="standings">
        <h2 v-if="title" class="standings-title">{{ title }}</h2>
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th scope="col" class="pos">#</th>
                        <th scope="col" class="team-col">Team</th>
                        <th scope="col" title="Played">P</th>
                        <th scope="col" title="Won">W</th>
                        <th scope="col" title="Drawn">D</th>
                        <th scope="col" title="Lost">L</th>
                        <th scope="col" title="Goal difference">GD</th>
                        <th scope="col" title="Points">Pts</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(row, i) in rows" :key="row.team_id ?? row.team_name" :class="{ leader: i === 0 && row.played > 0 }">
                        <td class="pos">{{ i + 1 }}</td>
                        <th scope="row" class="team-col">
                            <span class="team-cell">
                                <span class="crest"><TeamBadge :badge="row.team_badge" :color="row.team_color" :size="20" /></span>
                                {{ row.team_name }}
                            </span>
                        </th>
                        <td>{{ row.played }}</td>
                        <td>{{ row.won }}</td>
                        <td>{{ row.draw }}</td>
                        <td>{{ row.lose }}</td>
                        <td>{{ signed(row.goal_diff) }}</td>
                        <td class="points">{{ row.points }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>

<style scoped>
.standings + .standings {
    margin-top: 28px;
}

.public-page .standings-title {
    margin-bottom: 10px;
    color: var(--fg-muted);
    font-family: var(--font-mono);
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.table-card {
    overflow-x: auto;
    border: 1px solid var(--line);
    border-radius: 14px;
    background: var(--surface);
}

table {
    width: 100%;
    border-collapse: collapse;
    font-family: var(--font-mono);
    font-size: 0.8125rem;
}

th,
td {
    padding: 13px 4px;
    color: var(--fg-muted);
    font-weight: 400;
    text-align: right;
}

th:last-child,
td:last-child {
    padding-right: 16px;
}

thead th {
    border-bottom: 1px solid var(--line);
    font-size: 0.6875rem;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}

tbody tr + tr > * {
    border-top: 1px solid var(--line);
}

.pos {
    width: 40px;
    padding-left: 16px;
    text-align: left;
}

.team-col {
    width: 100%;
    padding-inline: 4px 8px;
    text-align: left;
}

tbody .team-col {
    color: var(--fg);
    font-family: var(--font-sans);
    font-size: 0.9375rem;
}

.team-cell {
    display: flex;
    align-items: center;
    gap: 10px;
    overflow-wrap: anywhere;
}

.crest {
    display: grid;
    flex: none;
    place-items: center;
    width: 22px;
    height: 22px;
}

td:not(.pos) {
    min-width: 30px;
}

.points {
    color: var(--fg);
    font-weight: 600;
}

.leader > * {
    background: var(--volt-soft);
}

@media (max-width: 575.98px) {
    th,
    td {
        padding-inline: 2px;
    }

    .pos {
        width: 30px;
        padding-left: 12px;
    }

    td:not(.pos) {
        min-width: 24px;
    }

    th:last-child,
    td:last-child {
        padding-right: 12px;
    }

    tbody .team-col {
        font-size: 0.875rem;
    }

    .team-cell {
        gap: 8px;
    }
}
</style>
