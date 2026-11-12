<script setup>
// Printer's crop marks in two corners of a flat, like a proof sheet.
// The parent must be `relative`; hidden on phones.
const corners = ['top-1 right-1', 'bottom-1 left-1'];
</script>

<template>
    <svg
        v-for="corner in corners"
        :key="corner"
        aria-hidden="true"
        viewBox="0 0 26 26"
        class="absolute size-6 max-sm:hidden"
        :class="corner"
    >
        <path d="M13 0v26M0 13h26" stroke="currentColor" stroke-width="2" />
        <circle
            cx="13"
            cy="13"
            r="7"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
        />
    </svg>
</template>
