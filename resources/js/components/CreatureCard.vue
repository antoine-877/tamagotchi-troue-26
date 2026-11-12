<script setup>
import { computed } from 'vue';
import CreatureSprite from '@/components/CreatureSprite.vue';
import Gauge from '@/components/Gauge.vue';
import LcdScreen from '@/components/LcdScreen.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { moodOf } from '@/lib/mood';
import { gauges, species } from '@/lib/species';

const props = defineProps({
    creature: Object,
});

const mood = computed(() => moodOf(props.creature));
const speciesLabel = computed(
    () => species[props.creature.species]?.label ?? 'Créature',
);
</script>

<template>
    <Card class="gap-5">
        <CardHeader class="flex items-center gap-4">
            <LcdScreen
                :grain="5"
                class="grid size-20 shrink-0 place-items-center border-[3px] border-ink"
            >
                <CreatureSprite
                    :species="creature.species"
                    :state="mood.state"
                    :label="`${creature.name}, ${speciesLabel.toLowerCase()} en pixels`"
                    class="size-16"
                />
            </LcdScreen>
            <div class="grid min-w-0 gap-1.5">
                <CardTitle class="pixel-title text-2xl break-words">
                    {{ creature.name }}
                </CardTitle>
                <CardDescription class="kicker">
                    {{ speciesLabel }} · {{ mood.word }}
                </CardDescription>
            </div>
        </CardHeader>
        <CardContent class="grid gap-3">
            <Gauge
                v-for="(label, gauge) in gauges"
                :key="gauge"
                :label="label"
                :value="creature[gauge]"
            />
        </CardContent>
        <CardFooter v-if="$slots.default" class="flex-wrap gap-2">
            <slot />
        </CardFooter>
    </Card>
</template>
