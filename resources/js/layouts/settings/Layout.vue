<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import PageMasthead from '@/components/PageMasthead.vue';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { twoDigits } from '@/lib/format';
import { toUrl } from '@/lib/utils';
import { edit as editAppearance } from '@/routes/appearance';
import { edit as editProfile } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';
import type { NavItem } from '@/types';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Profil',
        href: editProfile(),
    },
    {
        title: 'Sécurité',
        href: editSecurity(),
    },
    {
        title: 'Apparence',
        href: editAppearance(),
    },
];

const { isCurrentOrParentUrl } = useCurrentUrl();
</script>

<template>
    <PageMasthead aria-labelledby="settings-title" kicker="Votre compte">
        <h1
            id="settings-title"
            class="pixel-title text-[clamp(3rem,10vw,6rem)] uppercase"
        >
            Réglages
        </h1>
        <p class="mt-3 max-w-[520px] text-lg text-pretty">
            Gérez votre profil et les réglages de votre compte
        </p>
    </PageMasthead>

    <div class="mx-auto w-full max-w-[1280px] px-4 pt-8 pb-14 sm:px-8">
        <div class="flex flex-col gap-10 lg:flex-row lg:gap-14">
            <aside class="w-full lg:w-56 lg:shrink-0">
                <nav aria-label="Réglages">
                    <ul
                        class="grid grid-cols-3 border-2 border-border lg:grid-cols-1 lg:border-0 lg:border-t-2"
                    >
                        <li
                            v-for="(item, index) in sidebarNavItems"
                            :key="toUrl(item.href)"
                            class="border-border not-last:border-r-2 lg:border-b-2 lg:not-last:border-r-0"
                        >
                            <Link
                                :href="item.href"
                                :aria-current="
                                    isCurrentOrParentUrl(item.href)
                                        ? 'page'
                                        : undefined
                                "
                                class="flex min-h-14 flex-col items-center justify-center gap-0.5 px-1 text-center font-extrabold lg:flex-row lg:justify-start lg:gap-3 lg:px-3 lg:text-left"
                                :class="
                                    isCurrentOrParentUrl(item.href)
                                        ? 'bg-primary text-primary-foreground'
                                        : 'hover:bg-accent hover:text-accent-foreground'
                                "
                            >
                                <span class="font-mono text-[13px]">{{
                                    twoDigits(index + 1)
                                }}</span>
                                {{ item.title }}
                            </Link>
                        </li>
                    </ul>
                </nav>
            </aside>

            <div class="min-w-0 flex-1 md:max-w-2xl">
                <section class="max-w-xl space-y-12">
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>
