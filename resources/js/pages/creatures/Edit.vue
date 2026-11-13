<script setup>
import { Form, Head } from '@inertiajs/vue3';
import CreatureController from '@/actions/App/Http/Controllers/CreatureController';
import FitTitle from '@/components/FitTitle.vue';
import GaugeCard from '@/components/GaugeCard.vue';
import InputError from '@/components/InputError.vue';
import PageMasthead from '@/components/PageMasthead.vue';
import TamagotchiDevice from '@/components/TamagotchiDevice.vue';
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
import { frameNumber } from '@/lib/format';
import { moodOf } from '@/lib/mood';
import { gauges, species } from '@/lib/species';
import { dashboard } from '@/routes';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Mon tamagotchi', href: dashboard() },
            { title: 'Modifier', href: '#' },
        ],
        bleed: true,
    },
});

defineProps({
    creature: Object,
});
</script>

<template>
    <Head :title="`Modifier ${creature.name}`" />

    <PageMasthead
        aria-labelledby="creature-title"
        :kicker="`Fiche n° ${frameNumber(creature.id)} · ${species[creature.species]?.label ?? 'Créature'}`"
        :aside="`Humeur : ${moodOf(creature).word}`"
    >
        <FitTitle id="creature-title" :text="creature.name" />
    </PageMasthead>

    <div
        class="mx-auto grid w-full max-w-[1280px] gap-10 px-4 pt-8 pb-14 sm:px-8 lg:grid-cols-12 lg:gap-x-8"
    >
        <section
            aria-label="La créature"
            class="flex min-w-0 flex-col items-center gap-6 lg:col-span-6"
        >
            <TamagotchiDevice
                :creature="creature"
                :state="moodOf(creature).state"
            >
                <Form
                    v-bind="CreatureController.play.form(creature.id)"
                    :options="{ preserveScroll: true }"
                    #default="{ processing }"
                >
                    <button
                        type="submit"
                        class="device-key"
                        :disabled="processing"
                    >
                        Jouer
                    </button>
                </Form>
            </TamagotchiDevice>

            <div class="grid w-full gap-3 min-[480px]:grid-cols-3 sm:gap-4">
                <GaugeCard
                    v-for="(label, gauge) in gauges"
                    :key="gauge"
                    :gauge="gauge"
                    :value="creature[gauge]"
                />
            </div>
        </section>

        <div class="flex min-w-0 flex-col gap-10 lg:col-span-6">
            <Form
                v-bind="CreatureController.update.form(creature.id)"
                v-slot="{ errors, processing }"
                class="flex flex-col gap-4 border-t-4 border-border pt-3"
                novalidate
            >
                <h2 class="pixel-title text-[28px]">Renommer</h2>
                <p>Un nouveau nom, et c'est tout&nbsp;: les jauges restent.</p>

                <div class="flex max-w-md flex-col gap-2">
                    <Label for="name" class="kicker">Nom</Label>
                    <Input
                        id="name"
                        name="name"
                        autocomplete="off"
                        class="h-12 text-lg"
                        :default-value="creature.name"
                    />
                    <InputError :message="errors.name" />
                </div>

                <Button type="submit" class="self-start" :disabled="processing">
                    <Spinner v-if="processing" />
                    Enregistrer
                </Button>
            </Form>

            <section
                aria-labelledby="release-title"
                class="flex flex-col items-start gap-3 border-4 border-destructive p-4 text-destructive"
            >
                <h2 id="release-title" class="pixel-title text-[28px]">
                    Relâcher
                </h2>
                <p class="font-extrabold">
                    Une créature relâchée ne revient pas.
                </p>
                <p class="text-foreground">
                    {{ creature.name }} quitte votre enclos pour de bon, avec
                    ses jauges.
                </p>

                <Dialog>
                    <DialogTrigger as-child>
                        <Button
                            variant="destructive"
                            class="h-auto min-h-11 max-w-full py-2 text-left [overflow-wrap:anywhere] whitespace-normal"
                        >
                            Relâcher {{ creature.name }}
                        </Button>
                    </DialogTrigger>
                    <DialogContent>
                        <DialogHeader>
                            <DialogTitle class="pixel-title text-2xl">
                                Relâcher {{ creature.name }} ?
                            </DialogTitle>
                            <DialogDescription class="text-foreground">
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
            </section>
        </div>
    </div>
</template>
