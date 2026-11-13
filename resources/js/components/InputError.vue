<script setup lang="ts">
defineProps<{
    message?: string;
}>();
</script>

<template>
    <div v-show="message">
        <!-- The mark means the colour is not the only signal. -->
        <p
            class="flex items-start gap-2 text-sm font-bold text-destructive"
            role="alert"
        >
            <span
                aria-hidden="true"
                class="mt-px flex size-5 shrink-0 items-center justify-center border-2 border-current font-mono text-xs leading-none"
                >!</span
            >
            <span>{{ message }}</span>
        </p>
    </div>
</template>
