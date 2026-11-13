<script setup>
import { Form, Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import CreatureController from '@/actions/App/Http/Controllers/CreatureController';
import CreatureSprite from '@/components/CreatureSprite.vue';
import InputError from '@/components/InputError.vue';
import LcdScreen from '@/components/LcdScreen.vue';
import PageMasthead from '@/components/PageMasthead.vue';
import TamagotchiDevice from '@/components/TamagotchiDevice.vue';
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
            { title: 'Mon tamagotchi', href: dashboard() },
            { title: 'Adopter une créature', href: create() },
        ],
        bleed: true,
    },
});

defineProps({
    species: Array,
});

// What the visitor typed and picked, only to feed the live preview.
// The form itself is sent from the real fields (name and species).
const name = ref('');
const chosen = ref(null);

const preview = computed(() =>
    chosen.value
        ? {
              id: 0,
              name: name.value,
              species: chosen.value,
              food: 50,
              mood: 50,
              energy: 50,
          }
        : null,
);
</script>

<template>
    <Head title="Adopter une créature" />

    <PageMasthead aria-labelledby="adopt-title" kicker="Fiche d'adoption">
        <h1
            id="adopt-title"
            class="pixel-title text-[clamp(3rem,10vw,6rem)] uppercase"
        >
            Adopter
        </h1>
        <p class="mt-3 max-w-[520px] text-lg text-pretty">
            Choisissez une espèce, donnez-lui un nom&nbsp;: elle arrive dans
            votre enclos avec des jauges à moitié pleines.
        </p>
    </PageMasthead>

    <div
        class="mx-auto grid w-full max-w-[1280px] gap-10 px-4 pt-8 pb-14 sm:px-8 lg:grid-cols-12 lg:gap-x-8"
    >
        <div class="flex min-w-0 flex-col gap-6 lg:col-span-7">
            <Form
                v-bind="CreatureController.store.form()"
                v-slot="{ errors, processing }"
                class="flex flex-col gap-8"
                novalidate
            >
                <fieldset class="flex min-w-0 flex-col gap-3">
                    <legend class="mb-3 kicker">01 · L'espèce</legend>
                    <ul
                        class="grid grid-cols-2 gap-3 min-[480px]:grid-cols-3 sm:gap-4"
                    >
                        <li v-for="key in species" :key="key">
                            <label
                                class="group flex h-full cursor-pointer flex-col gap-2 border-4 border-ink bg-paper p-2 text-ink transition-colors hover:bg-acid/60 has-checked:bg-acid has-checked:shadow-hard has-focus-visible:outline-[3px] has-focus-visible:outline-offset-2 has-focus-visible:outline-ring motion-reduce:transition-none"
                            >
                                <input
                                    v-model="chosen"
                                    type="radio"
                                    name="species"
                                    :value="key"
                                    class="sr-only"
                                />
                                <LcdScreen
                                    :grain="5"
                                    class="grid h-28 place-items-center border-2 border-ink"
                                >
                                    <CreatureSprite
                                        :species="key"
                                        :state="
                                            chosen === key ? 'happy' : 'idle'
                                        "
                                        class="size-24"
                                    />
                                </LcdScreen>
                                <span
                                    class="flex min-h-11 items-center justify-between gap-2 px-1 font-extrabold"
                                >
                                    {{ speciesList[key].label }}
                                    <span
                                        aria-hidden="true"
                                        class="hidden size-5 shrink-0 border-2 border-ink bg-ink group-has-checked:block"
                                    />
                                    <span
                                        aria-hidden="true"
                                        class="size-5 shrink-0 border-2 border-ink bg-paper group-has-checked:hidden"
                                    />
                                </span>
                            </label>
                        </li>
                    </ul>
                    <InputError :message="errors.species" />
                </fieldset>

                <div class="flex max-w-md flex-col gap-2">
                    <Label for="name" class="kicker">02 · Le nom</Label>
                    <Input
                        id="name"
                        v-model="name"
                        name="name"
                        autocomplete="off"
                        class="h-12 text-lg"
                    />
                    <InputError :message="errors.name" />
                </div>

                <Button
                    type="submit"
                    size="lg"
                    class="self-start"
                    :disabled="processing"
                >
                    <Spinner v-if="processing" />
                    Adopter
                </Button>
            </Form>
        </div>

        <!-- Live preview: under the form on a phone, beside it on a large screen. -->
        <aside
            aria-label="Aperçu de la créature"
            class="flex min-w-0 flex-col items-center gap-3 border-t-4 border-border pt-8 lg:col-span-5 lg:border-t-0 lg:pt-4"
        >
            <p class="self-stretch border-b-2 border-border pb-1.5 kicker">
                Aperçu
            </p>
            <TamagotchiDevice
                :creature="preview"
                :state="preview ? 'happy' : 'idle'"
            />
            <p
                class="mt-3 max-w-full text-center pixel-title text-[28px] break-words"
            >
                {{ name.trim() || (chosen ? 'Sans nom' : 'Un œuf attend') }}
            </p>
        </aside>
    </div>
</template>
