<script setup>
defineProps({
    label: String,
    value: Number,
});
</script>

<template>
    <div class="grid gap-1 text-sm">
        <div class="flex justify-between">
            <span>{{ label }}</span>
            <span class="text-muted-foreground">{{ value }} / 100</span>
        </div>
        <div class="h-2 overflow-hidden rounded-full bg-muted">
            <div
                class="h-full rounded-full bg-primary"
                :style="{ width: `${value}%` }"
            />
        </div>
    </div>
</template>
