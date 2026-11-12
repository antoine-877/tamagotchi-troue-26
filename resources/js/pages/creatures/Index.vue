<script setup>
import { Form, Head, Link } from '@inertiajs/vue3';
import CreatureController from '@/actions/App/Http/Controllers/CreatureController';
import CreatureCard from '@/components/CreatureCard.vue';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { create, edit } from '@/routes/creatures';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Mes créatures', href: dashboard() }],
    },
});

defineProps({
    creatures: Array,
});
</script>

<template>
    <Head title="Mes créatures" />

    <div class="space-y-6 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Mes créatures</h1>
            <Button as-child>
                <Link :href="create()">Adopter une créature</Link>
            </Button>
        </div>

        <p v-if="creatures.length === 0" class="text-muted-foreground">
            Votre enclos est vide. Adoptez une première créature.
        </p>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <CreatureCard
                v-for="creature in creatures"
                :key="creature.id"
                :creature="creature"
            >
                <Form v-bind="CreatureController.play.form(creature.id)">
                    <Button type="submit" variant="secondary">Jouer</Button>
                </Form>
                <Button as-child variant="outline">
                    <Link :href="edit(creature.id)">Modifier</Link>
                </Button>
            </CreatureCard>
        </div>
    </div>
</template>
