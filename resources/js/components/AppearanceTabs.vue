<script setup lang="ts">
import { Monitor, Moon, Sun } from '@lucide/vue';
import { useAppearance } from '@/composables/useAppearance';

const { appearance, updateAppearance } = useAppearance();

const tabs = [
    { value: 'light', Icon: Sun, label: 'Clair' },
    { value: 'dark', Icon: Moon, label: 'Sombre' },
    { value: 'system', Icon: Monitor, label: 'Système' },
] as const;
</script>

<template>
    <div class="inline-flex flex-wrap border-2 border-border bg-card">
        <button
            v-for="{ value, Icon, label } in tabs"
            :key="value"
            type="button"
            :aria-pressed="appearance === value"
            @click="updateAppearance(value)"
            :class="[
                'flex min-h-11 items-center gap-1.5 border-r-2 border-border px-4 font-extrabold last:border-r-0',
                appearance === value
                    ? 'bg-acid text-ink'
                    : 'text-foreground hover:bg-muted',
            ]"
        >
            <component :is="Icon" class="size-4" />
            <span class="text-sm">{{ label }}</span>
        </button>
    </div>
</template>
