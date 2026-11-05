<template>
    <div class="preview animate__animated animate__fadeIn" role="img"
        aria-label="Example of the public tournament page, showing a live match score and a league table">
        <div class="panel">
            <div class="panel-head">
                <span>Example league</span>
                <span class="live"><span class="ui-live-dot"></span>Live 67'</span>
            </div>
            <div class="score">
                <div class="team">
                    <span class="crest">HC</span>
                    Harbour City
                </div>
                <div class="digits">2<span>–</span>1</div>
                <div class="team">
                    <span class="crest">NU</span>
                    Northgate Utd
                </div>
            </div>
        </div>

        <div class="panel table-panel">
            <div class="row-table head">
                <span>#</span><span>Team</span><span>P</span><span>GD</span><span>Pts</span>
            </div>
            <div v-for="(team, i) in table" :key="team.name" class="row-table" :class="{ top: i === 0 }">
                <span>{{ i + 1 }}</span>
                <span class="name">{{ team.name }}</span>
                <span>{{ team.played }}</span>
                <span>{{ team.gd }}</span>
                <span class="pts">{{ team.pts }}</span>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
// Illustrative data only: this panel is a picture of the product, not real results.
const table = [
    { name: 'Harbour City', played: 7, gd: '+9', pts: 17 },
    { name: 'Riverside', played: 7, gd: '+5', pts: 14 },
    { name: 'Northgate Utd', played: 7, gd: '+2', pts: 11 },
    { name: 'Eastfield Town', played: 7, gd: '−1', pts: 9 },
];
</script>

<style scoped>
.preview {
    display: grid;
    gap: 12px;
}

.panel {
    border: 1px solid var(--line);
    border-radius: 16px;
    background: var(--ink-900);
    box-shadow: 0 24px 60px -24px rgba(0, 0, 0, 0.8);
}

.panel-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    border-bottom: 1px solid var(--line);
    font-size: 0.8125rem;
}

.live {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--volt);
    font-family: var(--font-mono);
    font-size: 0.75rem;
    font-weight: 500;
}

.score {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    gap: 8px;
    padding: 28px 16px;
}

.team {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    color: var(--fg);
    font-size: 0.875rem;
    font-weight: 500;
    text-align: center;
}

.crest {
    display: grid;
    place-items: center;
    width: 44px;
    height: 44px;
    border: 1px solid var(--line-strong);
    border-radius: 50%;
    background: var(--ink-800);
    color: var(--fg-muted);
    font-family: var(--font-mono);
    font-size: 0.75rem;
}

.digits {
    color: var(--fg);
    font-family: var(--font-mono);
    font-size: clamp(2.5rem, 5vw, 3.25rem);
    font-weight: 500;
    letter-spacing: -0.04em;
    line-height: 1;
}

.digits span {
    margin-inline: 8px;
    color: var(--fg-muted);
    font-weight: 400;
}

.table-panel {
    padding: 8px;
}

.row-table {
    display: grid;
    grid-template-columns: 24px 1fr 32px 40px 36px;
    align-items: center;
    padding: 10px 12px;
    border-radius: 8px;
    font-family: var(--font-mono);
    font-size: 0.8125rem;
    text-align: right;
}

.row-table > :nth-child(-n + 2) {
    text-align: left;
}

.row-table.head {
    font-size: 0.6875rem;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}

.row-table.top {
    background: var(--volt-soft);
}

.name {
    color: var(--fg);
    font-family: var(--font-sans);
    font-size: 0.875rem;
}

.pts {
    color: var(--fg);
    font-weight: 600;
}
</style>
