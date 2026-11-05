<template>
    <section id="how" class="ui-section">
        <div class="container">
            <div class="ui-section-head">
                <span class="ui-eyebrow">How it works</span>
                <h2>From first fixture to final whistle.</h2>
            </div>

            <ol class="steps">
                <li v-for="(step, i) in steps" :key="step.title" class="step">
                    <span class="step-num">0{{ i + 1 }}</span>
                    <h3>{{ step.title }}</h3>
                    <p>{{ step.body }}</p>
                </li>
            </ol>
        </div>
    </section>
</template>

<script setup lang="ts">
const steps = [
    {
        title: 'Set up your tournament',
        body: 'Choose a league or cup format, add your logo, then bring in your teams and players.',
    },
    {
        title: 'Schedule and score',
        body: 'Publish fixtures, then run the match clock and update the score as it happens. Scorer accounts let your team help.',
    },
    {
        title: 'Share one link',
        body: 'Fans follow fixtures, live scores, results and standings from any phone, with no app or sign-up.',
    },
];
</script>

<style scoped>
.steps {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    margin: 0;
    padding: 0;
    list-style: none;
    border-top: 1px solid var(--line-strong);
}

.step {
    padding: 28px 32px 0 0;
}

.step + .step {
    padding-left: 32px;
    border-left: 1px solid var(--line);
}

.step-num {
    display: block;
    margin-bottom: 48px;
    color: var(--volt);
    font-family: var(--font-mono);
    font-size: 0.875rem;
}

.step h3 {
    margin-bottom: 12px;
    font-size: 1.375rem;
    letter-spacing: -0.025em;
}

.step p {
    max-width: 340px;
    margin: 0;
    line-height: 1.6;
}

@media (max-width: 767.98px) {
    .steps {
        grid-template-columns: 1fr;
    }

    .step,
    .step + .step {
        padding: 24px 0;
        border-left: 0;
        border-bottom: 1px solid var(--line);
    }

    .step-num {
        margin-bottom: 20px;
    }
}
</style>
