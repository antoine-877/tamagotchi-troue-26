<script setup>
import PixelSprite from '@/components/PixelSprite.vue';
import { icons } from '@/lib/sprites/icons';

// A 12 × 12 pixel icon from lib/sprites/icons.js, by name.
defineProps({
    name: { type: String, required: true },
    label: { type: String, default: null },
});
</script>

<template>
    <PixelSprite v-if="icons[name]" :pixels="icons[name]" :label="label" />
</template>
