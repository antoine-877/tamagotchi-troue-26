<script setup>
import CropMarks from '@/components/CropMarks.vue';

// The flat colour band that opens a page: crop marks, a bar of two kickers
// (or the `bar` slot), then the page's own content in the default slot.
defineProps({
    tone: { type: String, default: 'lilac' },
    kicker: { type: String, default: '' },
    aside: { type: String, default: '' },
});

// Bright flats always carry ink; the ink flat carries paper.
const tones = {
    lilac: 'bg-lilac',
    acid: 'bg-acid',
    aqua: 'bg-aqua',
    rose: 'bg-rose',
    ink: 'bg-ink text-paper [--border:var(--color-paper)] [--ring:var(--color-acid)] dark:border-b-2 dark:border-paper',
};
</script>

<template>
    <section
        class="masthead @container relative overflow-hidden px-4 pt-5 pb-10 sm:px-8"
        :class="
            tone === 'ink'
                ? tones.ink
                : [
                      tones[tone],
                      'text-ink [--border:var(--color-ink)] [--ring:var(--color-night)]',
                  ]
        "
    >
        <CropMarks />

        <div class="mx-auto flex w-full max-w-[1216px] flex-1 flex-col">
            <div
                class="mb-5 flex flex-wrap items-center justify-between gap-x-6 gap-y-1 border-b-2 border-current pb-2"
            >
                <slot name="bar">
                    <span class="kicker">{{ kicker }}</span>
                    <span v-if="aside" class="kicker">{{ aside }}</span>
                </slot>
            </div>

            <slot />
        </div>
    </section>
</template>

<style scoped>
/* When the tone changes (the mood of the creature), the flat switches in
   three hard steps, like an LCD, instead of a soft fade. */
.masthead {
    transition: background-color 240ms steps(3, end);
}

@media (prefers-reduced-motion: reduce) {
    .masthead {
        transition: none;
    }
}
</style>
