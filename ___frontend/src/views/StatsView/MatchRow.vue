<script setup lang="ts">
import TeamBadge from '@/components/TeamBadge.vue'

export interface MatchSide {
    name?: string | null
    badge?: string | null
    color?: string | null
    score?: number | null
    penalties?: number | null
    /** Omit when the match has no winner (a fixture or a draw). */
    won?: boolean
}

defineProps<{ home: MatchSide; away: MatchSide; status: string; meta?: string }>()
</script>

<template>
    <article class="match">
        <div class="status">{{ status }}</div>
        <div class="body">
            <div v-for="(side, i) in [home, away]" :key="i" class="side" :class="{ lost: side.won === false }">
                <span class="crest"><TeamBadge :badge="side.badge" :color="side.color" :size="22" /></span>
                <span class="name" :class="{ won: side.won }">{{ side.name }}</span>
                <span v-if="side.penalties != null" class="pens">({{ side.penalties }})</span>
                <span v-if="side.score != null" class="score">{{ side.score }}</span>
            </div>
            <div v-if="meta" class="meta">{{ meta }}</div>
        </div>
    </article>
</template>

<style scoped>
.match {
    display: grid;
    grid-template-columns: 64px 1fr;
    align-items: center;
    padding: 14px 18px 14px 0;
}

.status {
    color: var(--fg-muted);
    font-family: var(--font-mono);
    font-size: 0.75rem;
    text-align: center;
}

.body {
    display: grid;
    gap: 8px;
    min-width: 0;
    padding-left: 16px;
    border-left: 1px solid var(--line);
}

.side {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--fg);
}

.side.lost {
    color: var(--fg-muted);
}

.crest {
    display: grid;
    flex: none;
    place-items: center;
    width: 24px;
    height: 24px;
}

.name {
    flex: 1;
    min-width: 0;
    font-size: 0.9375rem;
    overflow-wrap: anywhere;
}

.name.won {
    font-weight: 600;
}

.pens {
    color: var(--fg-muted);
    font-family: var(--font-mono);
    font-size: 0.75rem;
}

.score {
    min-width: 20px;
    font-family: var(--font-mono);
    font-size: 1rem;
    font-weight: 500;
    text-align: right;
}

.meta {
    margin-top: 2px;
    font-size: 0.8125rem;
    text-transform: capitalize;
}
</style>
