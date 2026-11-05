<template>
    <section id="features" class="ui-section">
        <div class="container">
            <div class="ui-section-head">
                <span class="ui-eyebrow">Features</span>
                <h2>Everything matchday needs.</h2>
            </div>

            <div class="bento">
                <article class="cell cell-wide">
                    <i class="bi bi-broadcast" aria-hidden="true"></i>
                    <h3>Live match control</h3>
                    <p>Start a match, keep the clock running and update the score in a tap. Fans see each change as it
                        happens.</p>
                    <div class="live-demo" aria-hidden="true">
                        <span class="live-time"><span class="ui-live-dot"></span>67'</span>
                        <span class="live-score">HC <b>2</b> – <b>1</b> NU</span>
                    </div>
                </article>

                <article class="cell cell-narrow">
                    <i class="bi bi-bar-chart-line" aria-hidden="true"></i>
                    <h3>Standings that keep up</h3>
                    <p>Publish a result and your league table or cup groups update without any manual counting.</p>
                    <div class="table-demo" aria-hidden="true">
                        <div><span>1</span><span class="table-name">Harbour City</span><b>17</b></div>
                        <div><span>2</span><span class="table-name">Riverside</span><b>14</b></div>
                    </div>
                </article>

                <article v-for="feature in features" :key="feature.title" class="cell cell-third">
                    <i :class="['bi', feature.icon]" aria-hidden="true"></i>
                    <h3>{{ feature.title }}</h3>
                    <p>{{ feature.body }}</p>
                </article>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
const features = [
    {
        title: 'Teams and players',
        body: 'Keep every squad in one place, with team logos and player positions.',
        icon: 'bi-shield',
    },
    {
        title: 'A page for your fans',
        body: 'Copy your tournament link and share it anywhere. It works on any screen.',
        icon: 'bi-link-45deg',
    },
    {
        title: 'Fan predictions',
        body: 'Let fans predict how the tournament finishes, and review every entry yourself.',
        icon: 'bi-trophy',
    },
];
</script>

<style scoped>
.bento {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 12px;
}

.cell {
    display: flex;
    flex-direction: column;
    padding: 28px;
    border: 1px solid var(--line);
    border-radius: 16px;
    background: var(--ink-900);
    transition: border-color 0.2s ease;
}

.cell:hover {
    border-color: var(--line-strong);
}

.cell-wide {
    grid-column: span 7;
}

.cell-narrow {
    grid-column: span 5;
}

.cell-third {
    grid-column: span 4;
}

.cell i {
    margin-bottom: 40px;
    color: var(--volt);
    font-size: 1.375rem;
    line-height: 1;
}

.cell h3 {
    margin-bottom: 10px;
    font-size: 1.25rem;
    letter-spacing: -0.025em;
}

.cell p {
    max-width: 44ch;
    margin: 0 0 28px;
    line-height: 1.6;
}

.cell-third p {
    margin-bottom: 0;
}

.table-demo {
    margin-top: auto;
    padding-top: 28px;
    font-family: var(--font-mono);
    font-size: 0.8125rem;
}

.table-demo > div {
    display: flex;
    gap: 14px;
    padding-block: 8px;
    border-top: 1px solid var(--line);
}

.table-name {
    flex: 1;
    color: var(--fg);
    font-family: var(--font-sans);
}

.table-demo b {
    color: var(--fg);
    font-weight: 600;
}

.live-demo {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: auto;
    padding: 14px 18px;
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--ink-950);
    font-family: var(--font-mono);
    font-size: 0.875rem;
}

.live-time {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: var(--volt);
}

.live-score b {
    color: var(--fg);
    font-weight: 600;
}

@media (max-width: 991.98px) {
    .cell-wide,
    .cell-narrow {
        grid-column: span 12;
    }

    .cell-third {
        grid-column: span 6;
    }

    .cell-third:last-child {
        grid-column: span 12;
    }
}

@media (max-width: 575.98px) {
    .cell-third {
        grid-column: span 12;
    }

    .cell i {
        margin-bottom: 24px;
    }
}
</style>
