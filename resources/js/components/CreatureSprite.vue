<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';
import PixelSprite from '@/components/PixelSprite.vue';
import { creatures } from '@/lib/sprites/creatures';
import { icons } from '@/lib/sprites/icons';

// A creature in pixels. Its two images alternate every 500 ms,
// unless the visitor asked for reduced motion.
const props = defineProps({
    species: { type: String, required: true },
    state: { type: String, default: 'idle' },
    label: { type: String, default: null },
});

const frame = ref(0);
let timer = null;

onMounted(() => {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    timer = setInterval(() => {
        frame.value = 1 - frame.value;
    }, 500);
});

onUnmounted(() => clearInterval(timer));

const pixels = computed(() => {
    const images = creatures[props.species];

    if (!images) {
        return icons.egg;
    }

    return (images[props.state] ?? images.idle)[frame.value];
});
</script>

<template>
    <PixelSprite :pixels="pixels" :label="label" />
</template>
