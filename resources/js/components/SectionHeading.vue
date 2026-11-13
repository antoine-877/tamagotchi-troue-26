<script setup>
// The heading of a section: optional numbered kicker, pixel h2 on a rule,
// optional description; the default slot goes on the right (links, icon).
defineProps({
    title: { type: String, required: true },
    id: { type: String, default: undefined },
    kicker: { type: String, default: '' },
    description: { type: String, default: '' },
});
</script>

<template>
    <header
        class="flex flex-wrap items-end justify-between gap-x-4 gap-y-1 border-b-2 border-border pb-1.5"
    >
        <div class="flex min-w-0 flex-col gap-1">
            <p v-if="kicker" class="kicker text-muted-foreground">
                {{ kicker }}
            </p>
            <h2 :id="id" class="pixel-title text-[32px] text-balance">
                {{ title }}
            </h2>
            <p v-if="description" class="text-[15px] text-muted-foreground">
                {{ description }}
            </p>
        </div>
        <slot />
    </header>
</template>
