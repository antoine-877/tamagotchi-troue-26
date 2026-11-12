<script setup lang="ts">
type Props = {
    title: string;
    description?: string;
    variant?: 'default' | 'small';
};

withDefaults(defineProps<Props>(), {
    variant: 'default',
});
</script>

<template>
    <header
        class="flex flex-col gap-1.5 border-b-2 border-border pb-2"
        :class="variant === 'small' ? '' : 'mb-8'"
    >
        <h2
            class="pixel-title"
            :class="variant === 'small' ? 'text-[26px]' : 'text-[40px]'"
        >
            {{ title }}
        </h2>
        <p v-if="description" class="text-[15px] text-muted-foreground">
            {{ description }}
        </p>
    </header>
</template>
