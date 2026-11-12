<script setup>
import { Form, Head } from '@inertiajs/vue3';
import EnclosureController from '@/actions/App/Http/Controllers/EnclosureController';
import MessageController from '@/actions/App/Http/Controllers/MessageController';
import CreatureCard from '@/components/CreatureCard.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Enclos', href: '#' }],
    },
});

defineProps({
    owner: Object,
    isOwner: Boolean,
    creatures: Array,
    messages: Array,
});
</script>

<template>
    <Head :title="`Enclos de ${owner.name}`" />

    <div class="space-y-8 p-4">
        <header class="space-y-2">
            <h1 class="text-xl font-semibold">Enclos de {{ owner.name }}</h1>
            <p v-if="owner.motto" class="text-muted-foreground italic">
                « {{ owner.motto }} »
            </p>
        </header>

        <Form
            v-if="isOwner"
            v-bind="EnclosureController.update.form()"
            v-slot="{ errors, processing }"
            class="flex max-w-xl items-end gap-2"
        >
            <div class="grid flex-1 gap-2">
                <Label for="motto">Ma devise</Label>
                <Input
                    id="motto"
                    name="motto"
                    :default-value="owner.motto ?? ''"
                />
                <InputError :message="errors.motto" />
            </div>
            <Button type="submit" :disabled="processing">Enregistrer</Button>
        </Form>

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <CreatureCard
                v-for="creature in creatures"
                :key="creature.id"
                :creature="creature"
            />
        </section>

        <section class="max-w-2xl space-y-4">
            <h2 class="text-lg font-semibold">Livre d'or</h2>

            <p v-if="messages.length === 0" class="text-muted-foreground">
                Aucun message pour l'instant.
            </p>

            <article
                v-for="message in messages"
                :key="message.id"
                class="rounded-lg border p-3"
            >
                <p class="text-sm text-muted-foreground">
                    {{ message.author }}, le {{ message.date }}
                </p>
                <div v-html="message.body" />
            </article>

            <Form
                v-bind="MessageController.store.form(owner.id)"
                v-slot="{ errors, processing }"
                class="space-y-2"
                reset-on-success
            >
                <Label for="body">Laisser un message</Label>
                <textarea
                    id="body"
                    name="body"
                    rows="3"
                    class="w-full rounded-md border bg-transparent px-3 py-2 text-sm"
                />
                <p class="text-xs text-muted-foreground">
                    Le gras est permis : &lt;b&gt;comme ceci&lt;/b&gt;.
                </p>
                <InputError :message="errors.body" />
                <Button type="submit" :disabled="processing">Envoyer</Button>
            </Form>
        </section>
    </div>
</template>
