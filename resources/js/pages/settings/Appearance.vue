<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import Heading from '@/components/Heading.vue';
import { edit } from '@/routes/appearance';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: "Réglages de l'apparence",
                href: edit(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Réglages de l'apparence" />

    <h1 class="sr-only">Réglages de l'apparence</h1>

    <div class="space-y-6">
        <Heading
            variant="small"
            title="Réglages de l'apparence"
            description="Modifiez l'apparence de votre compte"
        />
        <AppearanceTabs />
    </div>
</template>
