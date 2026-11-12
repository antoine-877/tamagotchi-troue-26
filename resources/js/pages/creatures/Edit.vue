<script setup>
import { Form, Head } from '@inertiajs/vue3';
import CreatureController from '@/actions/App/Http/Controllers/CreatureController';
import CreatureCard from '@/components/CreatureCard.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { dashboard } from '@/routes';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Mes créatures', href: dashboard() },
            { title: 'Modifier', href: '#' },
        ],
    },
});

defineProps({
    creature: Object,
});
</script>

<template>
    <Head :title="`Modifier ${creature.name}`" />

    <div class="grid max-w-4xl gap-6 p-4 md:grid-cols-2">
        <CreatureCard :creature="creature">
            <Form v-bind="CreatureController.play.form(creature.id)">
                <Button type="submit" variant="secondary">Jouer</Button>
            </Form>
        </CreatureCard>

        <div class="space-y-8">
            <Form
                v-bind="CreatureController.update.form(creature.id)"
                v-slot="{ errors, processing }"
                class="space-y-4"
                novalidate
            >
                <h1 class="text-xl font-semibold">Renommer</h1>

                <div class="grid gap-2">
                    <Label for="name">Nom</Label>
                    <Input
                        id="name"
                        name="name"
                        :default-value="creature.name"
                    />
                    <InputError :message="errors.name" />
                </div>

                <Button type="submit" :disabled="processing">
                    <Spinner v-if="processing" />
                    Enregistrer
                </Button>
            </Form>

            <div class="space-y-4">
                <h2 class="text-lg font-semibold">Relâcher</h2>
                <p class="text-sm text-muted-foreground">
                    Une créature relâchée ne revient pas.
                </p>

                <Dialog>
                    <DialogTrigger as-child>
                        <Button variant="destructive">
                            Relâcher {{ creature.name }}
                        </Button>
                    </DialogTrigger>
                    <DialogContent>
                        <DialogHeader>
                            <DialogTitle>
                                Relâcher {{ creature.name }} ?
                            </DialogTitle>
                            <DialogDescription>
                                Cette action est définitive.
                            </DialogDescription>
                        </DialogHeader>
                        <DialogFooter>
                            <DialogClose as-child>
                                <Button variant="outline">Annuler</Button>
                            </DialogClose>
                            <Form
                                v-bind="
                                    CreatureController.destroy.form(creature.id)
                                "
                            >
                                <Button type="submit" variant="destructive">
                                    Relâcher
                                </Button>
                            </Form>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
            </div>
        </div>
    </div>
</template>
