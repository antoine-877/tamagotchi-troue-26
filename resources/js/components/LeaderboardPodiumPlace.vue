<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import CreatureSprite from '@/components/CreatureSprite.vue';
import LcdScreen from '@/components/LcdScreen.vue';
import { moodOf } from '@/lib/mood';
import { species } from '@/lib/species';
import { show } from '@/routes/enclosures';

// One step of the podium: a flat colour card standing on a numbered block.
const props = defineProps({
    creature: { type: Object, required: true },
    rank: { type: Number, required: true },
    score: { type: Number, required: true },
    mine: { type: Boolean, default: false },
});

const fills = { 1: 'bg-rose', 2: 'bg-acid', 3: 'bg-aqua' };
// The winner's block is the tallest (large screens only).
const heights = { 1: 'lg:pt-24', 2: 'lg:pt-12', 3: 'lg:pt-2' };

const speciesLabel = computed(
    () => species[props.creature.species]?.label ?? 'Créature',
);
</script>

<template>
    <article class="flex min-w-0 flex-col text-ink [--ring:var(--color-night)]">
        <div
            class="flex flex-1 flex-col gap-3 border-4 border-ink p-4 outline-2 outline-paper"
            :class="fills[rank]"
        >
            <div class="flex items-start justify-between gap-3">
                <LcdScreen
                    :grain="5"
                    class="grid size-24 shrink-0 place-items-center border-4 border-ink sm:size-28"
                >
                    <CreatureSprite
                        :species="creature.species"
                        :state="moodOf(creature).state"
                        :label="`${creature.name}, ${speciesLabel.toLowerCase()} en pixels`"
                        class="size-20 sm:size-24"
                    />
                </LcdScreen>
                <div class="flex flex-col items-end gap-2">
                    <!-- Phones have no block under the card: the rank sits here. -->
                    <span
                        aria-hidden="true"
                        class="pixel-title text-[80px] leading-[0.8] lg:hidden"
                    >
                        {{ rank }}
                    </span>
                    <span
                        v-if="mine"
                        class="bg-ink px-1.5 py-0.5 font-mono text-[11px] font-bold tracking-widest text-acid uppercase"
                    >
                        À vous
                    </span>
                </div>
            </div>

            <div class="grid gap-1">
                <h3 class="pixel-title text-[28px] [overflow-wrap:anywhere]">
                    {{ creature.name }}
                </h3>
                <p class="kicker">
                    {{ speciesLabel }} · chez
                    <Link
                        :href="show(creature.user.id)"
                        class="inline-flex min-h-11 items-center [overflow-wrap:anywhere] underline decoration-2 underline-offset-4 hover:decoration-4"
                    >
                        {{ creature.user.name }}
                    </Link>
                </p>
            </div>

            <p
                class="mt-auto flex items-baseline gap-2 border-t-2 border-ink pt-2"
            >
                <span class="font-mono text-6xl leading-none font-bold">
                    {{ score }}
                </span>
                <span class="kicker">points</span>
            </p>
        </div>

        <!-- The numbered block under the card. -->
        <div
            aria-hidden="true"
            class="hidden items-end border-4 border-t-0 border-ink bg-paper px-4 pb-3 outline-2 outline-paper lg:flex"
            :class="heights[rank]"
        >
            <span class="pixel-title text-[120px] leading-[0.8]">{{
                rank
            }}</span>
        </div>
    </article>
</template>
