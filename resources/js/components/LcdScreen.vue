<script setup>
// The LCD surface (green-grey, fine pixel grid, inner shadow).
// `grain` is the size of a grid cell in pixels; borders and radius
// come from the parent's classes.
defineProps({
    grain: { type: Number, default: 6 },
});
</script>

<template>
    <div class="lcd" :style="{ '--lcd-grain': `${grain}px` }">
        <slot />
    </div>
</template>
