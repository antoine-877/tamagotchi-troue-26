<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppHeader from '@/components/AppHeader.vue';
import { Toaster } from '@/components/ui/sonner';
import type { BreadcrumbItem } from '@/types';

// Pages pass their options with defineOptions({ layout: { ... } }).
// `bleed`: the page draws its own full-width sections (no centred column).
const { breadcrumbs = [], bleed = false } = defineProps<{
    breadcrumbs?: BreadcrumbItem[];
    bleed?: boolean;
}>();
</script>

<template>
    <div class="flex min-h-screen flex-col">
        <a
            href="#contenu"
            class="sr-only z-50 bg-primary px-4 py-3 font-extrabold text-primary-foreground focus:not-sr-only focus:fixed focus:top-2 focus:left-2"
        >
            Aller au contenu
        </a>

        <!-- Error pages can use this layout for a guest: no header then. -->
        <AppHeader v-if="$page.props.auth.user" />

        <nav
            v-if="breadcrumbs.length > 1"
            aria-label="Fil d'Ariane"
            class="mx-auto w-full max-w-[1280px] px-4 pt-4 sm:px-8"
        >
            <ol class="flex flex-wrap gap-x-2 kicker text-muted-foreground">
                <li
                    v-for="(item, index) in breadcrumbs"
                    :key="index"
                    class="flex items-center gap-x-2"
                >
                    <span v-if="index > 0" aria-hidden="true">/</span>
                    <Link
                        v-if="index < breadcrumbs.length - 1"
                        :href="item.href"
                        class="inline-flex min-h-6 items-center underline-offset-4 hover:text-foreground hover:underline"
                        >{{ item.title }}</Link
                    >
                    <span v-else aria-current="page" class="text-foreground">{{
                        item.title
                    }}</span>
                </li>
            </ol>
        </nav>

        <main
            id="contenu"
            tabindex="-1"
            class="flex flex-1 flex-col outline-none"
            :class="
                bleed
                    ? ''
                    : 'mx-auto w-full max-w-[1280px] py-4 sm:px-4 lg:px-6'
            "
        >
            <slot />
        </main>

        <Toaster />
    </div>
</template>
