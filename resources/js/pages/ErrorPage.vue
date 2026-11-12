<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { dashboard } from '@/routes';

const props = defineProps({
    status: Number,
});

const titles = {
    403: 'Accès refusé',
    404: 'Page introuvable',
    429: "Trop d'essais",
};

const messages = {
    403: "Vous n'avez pas le droit de faire ça.",
    404: "Cette page n'existe pas.",
    429: 'Réessayez dans une minute.',
};

const title = computed(() => titles[props.status]);
const message = computed(() => messages[props.status]);
</script>

<template>
    <Head :title="title" />

    <div class="space-y-4 p-4">
        <h1 class="text-xl font-semibold">{{ status }} · {{ title }}</h1>
        <p>{{ message }}</p>
        <Link :href="dashboard()" class="underline underline-offset-4">
            Retour à mes créatures
        </Link>
    </div>
</template>
