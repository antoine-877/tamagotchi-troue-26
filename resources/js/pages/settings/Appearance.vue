<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import SectionHeading from '@/components/SectionHeading.vue';
import { edit } from '@/routes/appearance';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: "Réglages de l'apparence",
                href: edit(),
            },
        ],
        bleed: true,
    },
});
</script>

<template>
    <Head title="Réglages de l'apparence" />

    <div class="space-y-6">
        <SectionHeading
            title="Réglages de l'apparence"
            description="Modifiez l'apparence de votre compte"
        />
        <AppearanceTabs />
    </div>
</template>
