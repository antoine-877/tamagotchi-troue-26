<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { species } from '@/lib/species';
import { leaderboard } from '@/routes';
import { show } from '@/routes/enclosures';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Classement', href: leaderboard() }],
    },
});

defineProps({
    creatures: Array,
});
</script>

<template>
    <Head title="Classement" />

    <div class="max-w-2xl space-y-6 p-4">
        <h1 class="text-xl font-semibold">Les créatures les plus heureuses</h1>

        <ol class="space-y-2">
            <li
                v-for="(creature, position) in creatures"
                :key="creature.id"
                class="flex items-center gap-4 rounded-lg border p-3"
            >
                <span class="w-6 text-right font-semibold">
                    {{ position + 1 }}
                </span>
                <span class="text-2xl" aria-hidden="true">
                    {{ species[creature.species]?.emoji }}
                </span>
                <span class="flex-1">
                    <span class="font-medium">{{ creature.name }}</span>
                    <span class="text-muted-foreground">
                        , chez
                        <Link
                            :href="show(creature.user.id)"
                            class="underline underline-offset-4"
                        >
                            {{ creature.user.name }}
                        </Link>
                    </span>
                </span>
                <span class="text-sm text-muted-foreground">
                    {{ creature.food + creature.mood + creature.energy }}
                    points
                </span>
            </li>
        </ol>
    </div>
</template>
