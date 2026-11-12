<script setup>
import Gauge from '@/components/Gauge.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { gauges, species } from '@/lib/species';

defineProps({
    creature: Object,
});
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle class="flex items-center gap-3 text-lg">
                <span class="text-3xl" aria-hidden="true">
                    {{ species[creature.species]?.emoji }}
                </span>
                {{ creature.name }}
            </CardTitle>
            <CardDescription>
                {{ species[creature.species]?.label }}
            </CardDescription>
        </CardHeader>
        <CardContent class="grid gap-3">
            <Gauge
                v-for="(label, gauge) in gauges"
                :key="gauge"
                :label="label"
                :value="creature[gauge]"
            />
        </CardContent>
        <CardFooter v-if="$slots.default" class="gap-2">
            <slot />
        </CardFooter>
    </Card>
</template>
