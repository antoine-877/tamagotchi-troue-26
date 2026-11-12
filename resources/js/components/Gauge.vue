<script setup>
import { computed } from 'vue';
import { isLow } from '@/lib/mood';
import { gauges } from '@/lib/species';

// Compact gauge: label, value and ten segments.
const props = defineProps({
    label: String,
    value: Number,
});

const gauge = computed(() =>
    Object.keys(gauges).find((key) => gauges[key] === props.label),
);
const low = computed(() =>
    gauge.value ? isLow(gauge.value, props.value) : props.value < 30,
);
</script>

<template>
    <div class="grid gap-1 text-sm">
        <div class="flex items-baseline justify-between gap-2">
            <span class="font-bold">{{ label }}</span>
            <span
                class="font-mono font-bold"
                :class="{ 'text-destructive': low }"
            >
                {{ value }}<span class="text-muted-foreground"> / 100</span>
            </span>
        </div>
        <div aria-hidden="true" class="flex gap-0.5">
            <span
                v-for="i in 10"
                :key="i"
                class="h-2.5 flex-1 border-2 border-border"
                :class="
                    value >= i * 10 - 5 &&
                    (low ? 'bg-destructive' : 'bg-foreground')
                "
            />
        </div>
    </div>
</template>
