<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import CreatureSprite from '@/components/CreatureSprite.vue';
import LcdScreen from '@/components/LcdScreen.vue';
import LeaderboardPodiumPlace from '@/components/LeaderboardPodiumPlace.vue';
import { moodOf } from '@/lib/mood';
import { species } from '@/lib/species';
import { leaderboard } from '@/routes';
import { show } from '@/routes/enclosures';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Classement', href: leaderboard() }],
        bleed: true,
    },
});

const props = defineProps({
    creatures: Array,
});

const page = usePage();

const score = (creature) => creature.food + creature.mood + creature.energy;
const isMine = (creature) => creature.user.id === page.props.auth.user?.id;
const rank = (position) => String(position).padStart(2, '0');

const podium = computed(() => props.creatures.slice(0, 3));
const others = computed(() => props.creatures.slice(3));

// On large screens the winner stands in the middle: 2, 1, 3.
const podiumOrder = { 0: 'lg:order-2', 1: 'lg:order-1', 2: 'lg:order-3' };
</script>

<template>
    <Head title="Classement" />

    <section
        aria-labelledby="leaderboard-title"
        class="bg-ink px-4 pt-5 pb-10 text-paper [--ring:var(--color-acid)] sm:px-8 dark:border-b-2 dark:border-paper"
    >
        <div class="mx-auto max-w-[1216px]">
            <div
                class="mb-4 flex flex-wrap justify-between gap-x-6 gap-y-1 border-b-2 border-paper pb-2"
            >
                <span class="kicker">Classement · les dix premières</span>
                <span class="kicker text-acid">
                    Score = nourriture + humeur + énergie
                </span>
            </div>

            <h1
                id="leaderboard-title"
                class="pixel-title text-[clamp(2.75rem,9vw,7rem)] uppercase"
            >
                Les plus heureuses
            </h1>

            <ol
                v-if="podium.length"
                class="mt-8 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-center lg:gap-5"
            >
                <li
                    v-for="(creature, position) in podium"
                    :key="creature.id"
                    class="flex lg:w-1/3"
                    :class="podiumOrder[position]"
                >
                    <LeaderboardPodiumPlace
                        :creature="creature"
                        :rank="position + 1"
                        :score="score(creature)"
                        :mine="isMine(creature)"
                        class="w-full"
                    />
                </li>
            </ol>

            <p v-else class="mt-6 max-w-[520px] text-lg">
                Personne au classement pour l'instant. Adoptez une créature pour
                ouvrir la course.
            </p>
        </div>
    </section>

    <section
        v-if="others.length"
        aria-label="Suite du classement"
        class="mx-auto w-full max-w-[1280px] px-4 pt-8 pb-14 sm:px-8"
    >
        <ol :start="4" class="border-t-4 border-border">
            <li
                v-for="(creature, index) in others"
                :key="creature.id"
                class="grid grid-cols-[auto_auto_minmax(0,1fr)_auto] items-center gap-3 border-b-2 border-border px-2 py-3 sm:gap-5 sm:px-3"
                :class="{
                    'bg-lilac text-ink [--ring:var(--color-night)]':
                        isMine(creature),
                }"
            >
                <span
                    class="w-12 font-mono text-3xl leading-none font-bold sm:w-16 sm:text-[40px]"
                >
                    {{ rank(index + 4) }}
                </span>
                <LcdScreen
                    :grain="4"
                    class="grid size-12 place-items-center border-2 border-border"
                >
                    <CreatureSprite
                        :species="creature.species"
                        :state="moodOf(creature).state"
                        class="size-10"
                    />
                </LcdScreen>
                <div class="grid min-w-0 gap-0.5">
                    <p
                        class="pixel-title text-[22px] [overflow-wrap:anywhere] sm:text-2xl"
                    >
                        {{ creature.name }}
                        <span
                            v-if="isMine(creature)"
                            class="ml-1 bg-ink px-1.5 py-0.5 align-middle font-mono text-[11px] font-bold tracking-widest text-acid uppercase"
                        >
                            À vous
                        </span>
                    </p>
                    <div
                        class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center sm:gap-x-3"
                    >
                        <p
                            class="kicker"
                            :class="{
                                'text-muted-foreground': !isMine(creature),
                            }"
                        >
                            {{ species[creature.species]?.label ?? 'Créature' }}
                        </p>
                        <p class="kicker">
                            <span aria-hidden="true" class="max-sm:hidden"
                                >·</span
                            >
                            chez
                            <Link
                                :href="show(creature.user.id)"
                                class="inline-flex min-h-11 items-center [overflow-wrap:anywhere] underline decoration-2 underline-offset-4 hover:decoration-4"
                                :class="
                                    isMine(creature) ? 'text-ink' : 'text-link'
                                "
                            >
                                {{ creature.user.name }}
                            </Link>
                        </p>
                    </div>
                </div>
                <p class="text-right">
                    <span
                        class="block font-mono text-2xl leading-none font-bold sm:text-[32px]"
                    >
                        {{ score(creature) }}
                    </span>
                    <span class="kicker">pts</span>
                </p>
            </li>
        </ol>
    </section>
</template>
