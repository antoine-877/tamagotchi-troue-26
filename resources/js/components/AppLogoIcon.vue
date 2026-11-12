<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();

const shell =
    'M24 3C37 3 45 15 45 30C45 44 36 53 24 53C12 53 3 44 3 30C3 15 11 3 24 3Z';
</script>

<template>
    <!-- A tiny pocket device: egg-shaped shell, LCD face, three keys. -->
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 52 58"
        aria-hidden="true"
        :class="className"
        v-bind="$attrs"
    >
        <path :d="shell" fill="#14101F" transform="translate(3 3)" />
        <path :d="shell" fill="#FF5FA2" stroke="#14101F" stroke-width="4" />
        <ellipse
            cx="15"
            cy="11"
            rx="5"
            ry="2.2"
            fill="#FFFDF7"
            opacity="0.7"
            transform="rotate(-28 15 11)"
        />
        <rect
            x="12.5"
            y="15.5"
            width="23"
            height="18"
            rx="3"
            fill="#C3CFA1"
            stroke="#14101F"
            stroke-width="3"
        />
        <rect x="18" y="20" width="4" height="4" fill="#1F2A1C" />
        <rect x="26" y="20" width="4" height="4" fill="#1F2A1C" />
        <rect x="20" y="27" width="8" height="2.5" fill="#1F2A1C" />
        <circle
            cx="16"
            cy="42"
            r="2.8"
            fill="#E6FF3D"
            stroke="#14101F"
            stroke-width="1.8"
        />
        <circle
            cx="24"
            cy="44"
            r="2.8"
            fill="#2EE6C5"
            stroke="#14101F"
            stroke-width="1.8"
        />
        <circle
            cx="32"
            cy="42"
            r="2.8"
            fill="#FFFDF7"
            stroke="#14101F"
            stroke-width="1.8"
        />
    </svg>
</template>
