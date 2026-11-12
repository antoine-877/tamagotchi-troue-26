<script setup>
import { computed } from 'vue';

// Draws a pixel image: an array of strings where '#' is a lit pixel.
// The size comes from the parent's CSS classes (w-*, h-*), the colour
// from `color` (currentColor).
const props = defineProps({
    pixels: { type: Array, required: true },
    label: { type: String, default: null },
});

const width = computed(() => props.pixels[0]?.length ?? 0);
const height = computed(() => props.pixels.length);

// One rectangle per run of lit pixels on a row, all in a single path.
const path = computed(() =>
    props.pixels
        .flatMap((row, y) =>
            [...row.matchAll(/#+/g)].map(
                (run) => `M${run.index} ${y}h${run[0].length}v1H${run.index}z`,
            ),
        )
        .join(''),
);
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        :viewBox="`0 0 ${width} ${height}`"
        shape-rendering="crispEdges"
        fill="currentColor"
        class="block"
        :role="label ? 'img' : undefined"
        :aria-label="label ?? undefined"
        :aria-hidden="label ? undefined : 'true'"
    >
        <path :d="path" />
    </svg>
</template>
