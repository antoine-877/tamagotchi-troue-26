<script setup>
// A giant h1 sized on its own column: the longer the text, the smaller the
// letters. `fit` is the width budget (in % of the column) shared by the letters.
defineProps({
    text: { type: String, required: true },
    id: { type: String, default: undefined },
    fit: { type: Number, default: 150 },
});
</script>

<template>
    <div class="@container min-w-0">
        <h1
            :id="id"
            class="fit-title pixel-title uppercase"
            :style="{ '--chars': text.length, '--fit': `${fit}cqi` }"
        >
            {{ text }}
        </h1>
    </div>
</template>

<style scoped>
.fit-title {
    font-size: clamp(2.5rem, min(20cqi, var(--fit) / var(--chars)), 10rem);
    overflow-wrap: anywhere;
}

@media (width >= 64rem) {
    .fit-title {
        font-size: clamp(3.5rem, var(--fit) / var(--chars), 10rem);
    }
}
</style>
