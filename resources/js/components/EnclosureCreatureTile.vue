<script setup>
import { computed } from 'vue';
import CreatureSprite from '@/components/CreatureSprite.vue';
import LcdScreen from '@/components/LcdScreen.vue';
import PixelIcon from '@/components/PixelIcon.vue';
import { frameNumber } from '@/lib/format';
import { isLow, moodOf } from '@/lib/mood';
import { gauges, species } from '@/lib/species';

// One frame of the contact sheet: LCD, name, species, three compact gauges.
// The default slot is a footer for small links (e.g. « Modifier »).
const props = defineProps({
    creature: { type: Object, required: true },
});

const mood = computed(() => moodOf(props.creature));
const speciesLabel = computed(
    () => species[props.creature.species]?.label ?? 'Créature',
);
</script>

<template>
    <article
        class="flex min-w-0 flex-col border-4 border-border bg-card text-card-foreground shadow-hard"
    >
        <LcdScreen
            :grain="5"
            class="relative grid h-32 place-items-center border-b-4 border-border"
        >
            <span
                class="absolute top-1.5 left-2 font-mono text-[11px] font-bold"
            >
                N° {{ frameNumber(creature.id) }}
            </span>
            <CreatureSprite
                :species="creature.species"
                :state="mood.state"
                :label="`${creature.name}, ${speciesLabel.toLowerCase()} en pixels`"
                class="size-24"
            />
        </LcdScreen>

        <div class="flex flex-1 flex-col gap-3 p-3">
            <div class="grid gap-1">
                <h3 class="pixel-title text-2xl [overflow-wrap:anywhere]">
                    {{ creature.name }}
                </h3>
                <p class="kicker text-muted-foreground">
                    {{ speciesLabel }} · {{ mood.word }}
                </p>
            </div>

            <dl class="grid gap-1.5">
                <div
                    v-for="(label, gauge) in gauges"
                    :key="gauge"
                    class="grid grid-cols-[1rem_1fr_2.25rem] items-center gap-2"
                >
                    <dt>
                        <PixelIcon :name="gauge" class="size-4" />
                        <span class="sr-only">{{ label }}</span>
                    </dt>
                    <dd aria-hidden="true" class="flex gap-px">
                        <span
                            v-for="i in 10"
                            :key="i"
                            class="h-2 flex-1 border border-border"
                            :class="
                                creature[gauge] >= i * 10 - 5 &&
                                (isLow(gauge, creature[gauge])
                                    ? 'bg-destructive'
                                    : 'bg-foreground')
                            "
                        />
                    </dd>
                    <dd
                        class="text-right font-mono text-sm font-bold"
                        :class="{
                            'text-destructive': isLow(gauge, creature[gauge]),
                        }"
                    >
                        {{ creature[gauge]
                        }}<span class="sr-only"> sur 100</span>
                    </dd>
                </div>
            </dl>

            <div
                v-if="$slots.default"
                class="mt-auto flex flex-wrap gap-x-4 border-t-2 border-border pt-1"
            >
                <slot />
            </div>
        </div>
    </article>
</template>
