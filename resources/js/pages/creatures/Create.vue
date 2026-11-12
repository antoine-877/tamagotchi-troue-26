<script setup>
import { Form, Head } from '@inertiajs/vue3';
import CreatureController from '@/actions/App/Http/Controllers/CreatureController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { species as speciesList } from '@/lib/species';
import { dashboard } from '@/routes';
import { create } from '@/routes/creatures';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Mes créatures', href: dashboard() },
            { title: 'Adopter une créature', href: create() },
        ],
    },
});

defineProps({
    species: Array,
});
</script>

<template>
    <Head title="Adopter une créature" />

    <div class="max-w-xl p-4">
        <h1 class="mb-6 text-xl font-semibold">Adopter une créature</h1>

        <Form
            v-bind="CreatureController.store.form()"
            v-slot="{ errors, processing }"
            class="space-y-6"
            novalidate
        >
            <div class="grid gap-2">
                <Label for="name">Nom</Label>
                <Input id="name" name="name" />
                <InputError :message="errors.name" />
            </div>

            <fieldset class="grid gap-2">
                <legend class="mb-2 text-sm font-medium">Espèce</legend>
                <Label
                    v-for="key in species"
                    :key="key"
                    class="flex items-center gap-3"
                >
                    <input type="radio" name="species" :value="key" />
                    <span>
                        {{ speciesList[key].emoji }}
                        {{ speciesList[key].label }}
                    </span>
                </Label>
                <InputError :message="errors.species" />
            </fieldset>

            <Button type="submit" :disabled="processing">
                <Spinner v-if="processing" />
                Adopter
            </Button>
        </Form>
    </div>
</template>
