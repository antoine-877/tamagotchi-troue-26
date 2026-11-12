<script setup>
import { species } from '@/lib/species';

// A row of chips, one per species; the chosen one is inked.
// Use with v-model (the species key, e.g. 'dragon').
const model = defineModel({ type: String, required: true });
</script>

<template>
    <div class="flex flex-wrap justify-center gap-1.5">
        <button
            v-for="(entry, key) in species"
            :key="key"
            type="button"
            :aria-pressed="model === key"
            class="min-h-11 cursor-pointer border-2 border-ink px-2.5 font-mono text-[13px] font-bold tracking-wide uppercase focus-visible:outline-night"
            :class="
                model === key
                    ? 'bg-ink text-paper'
                    : 'bg-paper text-ink hover:bg-acid'
            "
            @click="model = key"
        >
            {{ entry.label }}
        </button>
    </div>
</template>
