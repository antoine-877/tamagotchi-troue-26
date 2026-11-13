<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import CreatureSprite from '@/components/CreatureSprite.vue';
import LcdScreen from '@/components/LcdScreen.vue';
import PageMasthead from '@/components/PageMasthead.vue';
import { species } from '@/lib/species';
import { home } from '@/routes';

const props = defineProps({
    title: { type: String, default: '' },
    description: { type: String, default: '' },
});

// A creature keeps watch on the form; each page gets its own species.
const watcher = computed(() => {
    const keys = Object.keys(species);

    return keys[props.title.length % keys.length];
});
</script>

<template>
    <PageMasthead class="flex min-h-svh flex-col">
        <template #bar>
            <Link :href="home()" class="-my-1 py-1">
                <AppLogo />
                <span class="sr-only">, retour à l'accueil</span>
            </Link>
            <span class="kicker max-sm:hidden">Espace joueur</span>
        </template>

        <div
            class="mt-3 grid w-full flex-1 content-start items-center gap-8 lg:mt-0 lg:grid-cols-[1fr_minmax(0,460px)] lg:content-center lg:gap-16"
        >
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-4 lg:flex-col lg:items-start">
                    <LcdScreen
                        :grain="6"
                        class="grid size-20 shrink-0 place-items-center rounded-[14px] border-4 border-ink lg:size-44 lg:rounded-[20px]"
                    >
                        <CreatureSprite
                            :species="watcher"
                            class="size-14 lg:size-32"
                        />
                    </LcdScreen>
                    <div class="flex min-w-0 flex-col gap-2">
                        <p class="kicker">
                            {{ species[watcher].label }} de garde
                        </p>
                        <h1
                            class="pixel-title text-[clamp(2rem,8vw,2.75rem)] text-balance lg:text-[clamp(3rem,5vw,4.25rem)]"
                        >
                            {{ title }}
                        </h1>
                    </div>
                </div>
                <p
                    v-if="description"
                    class="max-w-[30rem] text-[17px] text-pretty"
                >
                    {{ description }}
                </p>
            </div>

            <div
                class="min-w-0 border-4 border-ink bg-card p-5 text-card-foreground shadow-[8px_8px_0_0_var(--color-ink)] [--ring:var(--color-night)] sm:p-8 dark:[--border:var(--color-paper)] dark:[--ring:var(--color-acid)]"
            >
                <slot />
            </div>
        </div>
    </PageMasthead>
</template>
