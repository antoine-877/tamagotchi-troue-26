<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Home, PawPrint, Store, Trophy } from '@lucide/vue';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard, leaderboard } from '@/routes';
import { show as enclosure } from '@/routes/enclosures';
import { index as shop } from '@/routes/shop';
import type { NavItem } from '@/types';

const page = usePage();

const mainNavItems = computed<NavItem[]>(() => [
    {
        title: 'Mes créatures',
        href: dashboard(),
        icon: PawPrint,
    },
    {
        title: 'Mon enclos',
        href: enclosure(page.props.auth.user.id),
        icon: Home,
    },
    {
        title: 'Classement',
        href: leaderboard(),
        icon: Trophy,
    },
    {
        title: 'Magasin',
        href: shop(),
        icon: Store,
    },
]);
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
