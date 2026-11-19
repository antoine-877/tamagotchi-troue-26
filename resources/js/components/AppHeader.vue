<script setup lang="ts">
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import AppLogo from "@/components/AppLogo.vue";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import UserMenuContent from "@/components/UserMenuContent.vue";
import { useCurrentUrl } from "@/composables/useCurrentUrl";
import { getInitials } from "@/composables/useInitials";
import { dashboard, leaderboard } from "@/routes";
import { show as enclosure } from "@/routes/enclosures";
import { index as shop } from "@/routes/shop";

const page = usePage();
const user = computed(() => page.props.auth.user);
const { isCurrentUrl, isCurrentOrParentUrl } = useCurrentUrl();

// An error page belongs to no section, whatever its address says.
const isErrorPage = computed(() => page.component === "ErrorPage");

// Numbered like the sections of a magazine.
const sections = computed(() => [
    {
        number: "01",
        title: "Mon tamagotchi",
        href: dashboard(),
        current:
            !isErrorPage.value &&
            (isCurrentUrl(dashboard()) || isCurrentOrParentUrl("/creatures")),
    },
    {
        number: "02",
        title: "Enclos",
        href: enclosure(user.value.id),
        current: !isErrorPage.value && isCurrentUrl(enclosure(user.value.id)),
    },
    {
        number: "03",
        title: "Classement",
        href: leaderboard(),
        current: !isErrorPage.value && isCurrentUrl(leaderboard()),
    },
    {
        number: "04",
        title: "Magasin",
        href: shop(),
        current: !isErrorPage.value && isCurrentOrParentUrl(shop()),
    },
]);
</script>

<template>
    <header class="border-b-4 border-border bg-background">
        <div
            class="mx-auto flex max-w-[1280px] flex-wrap items-center gap-x-3 gap-y-1 px-4 pt-3 pb-1 sm:gap-x-7 sm:px-8 lg:py-3"
        >
            <Link :href="dashboard()" class="-my-1 py-1">
                <AppLogo />
                <span class="sr-only">, accueil du joueur</span>
            </Link>

            <nav
                aria-label="Navigation principale"
                class="order-last basis-full lg:order-none lg:basis-auto"
            >
                <ul
                    class="grid grid-cols-2 gap-x-4 sm:flex sm:flex-wrap sm:gap-x-6"
                >
                    <li v-for="section in sections" :key="section.number">
                        <Link
                            :href="section.href"
                            :aria-current="section.current ? 'page' : undefined"
                            class="flex min-h-11 items-center gap-1.5 border-b-4 pt-1 font-extrabold whitespace-nowrap hover:border-border/40"
                            :class="
                                section.current
                                    ? 'border-border'
                                    : 'border-transparent'
                            "
                        >
                            <span class="font-mono text-[13px]">{{
                                section.number
                            }}</span>
                            {{ section.title }}
                        </Link>
                    </li>
                </ul>
            </nav>

            <div class="ml-auto flex items-center gap-3 sm:gap-5">
                <p class="flex items-baseline gap-2">
                    <span class="kicker max-sm:sr-only">Solde</span>
                    <span class="font-mono text-lg font-bold sm:text-[22px]">{{
                        $page.props.auth.balance
                    }}</span>
                </p>

                <DropdownMenu>
                    <DropdownMenuTrigger
                        class="flex size-11 cursor-pointer items-center justify-center border-2 border-border bg-accent font-mono text-sm font-bold text-accent-foreground shadow-hard-sm hover:bg-primary"
                    >
                        {{ getInitials(user.name) }}
                        <span class="sr-only">Menu de {{ user.name }}</span>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end" class="w-60">
                        <UserMenuContent :user="user" />
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
        </div>
    </header>
</template>
