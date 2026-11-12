<script setup>
import { Form, Head, Link } from '@inertiajs/vue3';
import { computed, onUnmounted, ref } from 'vue';
import CreatureController from '@/actions/App/Http/Controllers/CreatureController';
import FilmStrip from '@/components/FilmStrip.vue';
import GaugeCard from '@/components/GaugeCard.vue';
import PixelIcon from '@/components/PixelIcon.vue';
import TamagotchiDevice from '@/components/TamagotchiDevice.vue';
import { Button } from '@/components/ui/button';
import { moodOf, reactionOf } from '@/lib/mood';
import { gauges, species } from '@/lib/species';
import { dashboard, leaderboard } from '@/routes';
import { create, edit } from '@/routes/creatures';
import { index as shop } from '@/routes/shop';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Mon tamagotchi', href: dashboard() }],
        bleed: true,
    },
});

const props = defineProps({
    creatures: Array,
});

// The creature on the device: the first one, or the one picked on the film.
const selectedId = ref(props.creatures[0]?.id ?? null);
const creature = computed(
    () =>
        props.creatures.find((c) => c.id === selectedId.value) ??
        props.creatures[0] ??
        null,
);

// After « Jouer » or « Dodo », the creature reacts for a few seconds.
const reaction = ref(null);
let reactionTimer = null;

function react(action) {
    clearTimeout(reactionTimer);
    reaction.value = action;
    reactionTimer = setTimeout(() => {
        reaction.value = null;
    }, 4000);
}

function select(picked) {
    clearTimeout(reactionTimer);
    reaction.value = null;
    selectedId.value = picked.id;
}

onUnmounted(() => clearTimeout(reactionTimer));

const mood = computed(() => {
    if (!creature.value) {
        return {
            state: 'idle',
            tone: 'lilac',
            word: 'en attente',
            headline: 'Votre enclos est vide. Adoptez une première créature.',
            status: 'Un œuf attend au chaud.',
        };
    }

    return reaction.value
        ? reactionOf(reaction.value, creature.value)
        : moodOf(creature.value);
});

const tones = { lilac: 'bg-lilac', acid: 'bg-acid', aqua: 'bg-aqua' };

const title = computed(() => creature.value?.name ?? 'Rien ici. Encore.');

const fileCaption = computed(() =>
    creature.value
        ? `Fiche n° ${String(creature.value.id).padStart(3, '0')} · ${species[creature.value.species]?.label ?? 'Créature'}`
        : 'Fiche vierge',
);
</script>

<template>
    <Head title="Mon tamagotchi" />

    <section
        aria-labelledby="scene-title"
        class="@container relative overflow-hidden px-4 pt-5 pb-9 text-ink transition-colors duration-500 [--ring:var(--color-night)] motion-reduce:transition-none sm:px-8"
        :class="tones[mood.tone]"
    >
        <svg
            v-for="corner in ['top-1 right-1', 'bottom-1 left-1']"
            :key="corner"
            aria-hidden="true"
            viewBox="0 0 26 26"
            class="absolute size-6 max-sm:hidden"
            :class="corner"
        >
            <path d="M13 0v26M0 13h26" stroke="currentColor" stroke-width="2" />
            <circle
                cx="13"
                cy="13"
                r="7"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            />
        </svg>

        <div class="mx-auto max-w-[1216px]">
            <div
                class="mb-3.5 flex flex-wrap justify-between gap-x-6 gap-y-1 border-b-2 border-ink pb-2"
            >
                <span class="kicker">{{ fileCaption }}</span>
                <span class="kicker">Humeur : {{ mood.word }}</span>
            </div>

            <!-- Large screens: explicit grid lines, so the name may run
                 behind the device (columns 8 to 12) instead of avoiding it. -->
            <div
                class="flex flex-col gap-5 lg:grid lg:grid-cols-12 lg:items-start lg:gap-x-4"
            >
                <h1
                    id="scene-title"
                    class="creature-name pixel-title uppercase lg:col-start-1 lg:col-end-10 lg:row-start-1"
                    :style="{ '--chars': title.length }"
                >
                    {{ title }}
                </h1>

                <div
                    class="relative z-10 mt-2 self-center lg:col-start-8 lg:col-end-13 lg:row-span-3 lg:row-start-1 lg:justify-self-center"
                >
                    <TamagotchiDevice :creature="creature" :state="mood.state">
                        <template v-if="creature" #default>
                            <Link :href="shop()" class="device-key">
                                Nourrir
                            </Link>
                            <Form
                                v-bind="
                                    CreatureController.play.form(creature.id)
                                "
                                :options="{ preserveScroll: true }"
                                #default="{ processing }"
                                @success="react('play')"
                            >
                                <button
                                    type="submit"
                                    class="device-key"
                                    :disabled="processing"
                                >
                                    Jouer
                                </button>
                            </Form>
                            <Form
                                v-bind="
                                    CreatureController.sleep.form(creature.id)
                                "
                                :options="{ preserveScroll: true }"
                                #default="{ processing }"
                                @success="react('sleep')"
                            >
                                <button
                                    type="submit"
                                    class="device-key"
                                    :disabled="processing"
                                >
                                    Dodo
                                </button>
                            </Form>
                        </template>
                    </TamagotchiDevice>
                </div>

                <div
                    class="flex flex-col gap-2.5 lg:col-start-1 lg:col-end-8 lg:row-start-2"
                >
                    <p
                        class="text-[clamp(1.5rem,3vw,1.875rem)] leading-tight font-extrabold text-balance"
                    >
                        {{ mood.headline }}
                    </p>
                    <p v-if="creature" class="max-w-[520px] text-pretty">
                        Trois touches sur le boîtier&nbsp;:
                        «&nbsp;Nourrir&nbsp;» mène au magasin,
                        «&nbsp;Jouer&nbsp;» remonte son humeur,
                        «&nbsp;Dodo&nbsp;» recharge son énergie.
                    </p>
                    <Button v-else as-child size="lg" class="self-start">
                        <Link :href="create()">Adopter une créature</Link>
                    </Button>
                </div>

                <div
                    v-if="creature"
                    class="grid gap-3 min-[480px]:grid-cols-3 sm:gap-4 lg:col-start-1 lg:col-end-8 lg:row-start-3"
                >
                    <GaugeCard
                        v-for="(label, gauge) in gauges"
                        :key="gauge"
                        :gauge="gauge"
                        :value="creature[gauge]"
                    />
                </div>
            </div>
        </div>
    </section>

    <div class="bg-ink text-paper dark:border-y-2 dark:border-paper">
        <p
            aria-live="polite"
            class="mx-auto max-w-[1280px] px-4 py-3 font-mono text-[15px] tracking-wide sm:px-8"
        >
            <span class="font-bold text-acid">EN CE MOMENT</span> ·
            {{ mood.status }}
        </p>
    </div>

    <div
        class="mx-auto grid w-full max-w-[1280px] gap-8 px-4 pt-7 pb-12 sm:px-8 lg:grid-cols-12 lg:gap-6"
    >
        <section
            v-if="creature"
            aria-labelledby="film-title"
            class="flex min-w-0 flex-col gap-2.5 lg:col-span-6"
        >
            <div
                class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1 border-b-2 border-border pb-1.5"
            >
                <h2 id="film-title" class="pixel-title text-[26px]">
                    Mes créatures
                </h2>
                <div class="flex flex-wrap gap-x-5">
                    <Link
                        :href="edit(creature.id)"
                        class="inline-flex min-h-11 items-center font-extrabold text-link underline decoration-2 underline-offset-4"
                    >
                        Modifier la fiche
                    </Link>
                    <Link
                        :href="create()"
                        class="inline-flex min-h-11 items-center font-extrabold text-link underline decoration-2 underline-offset-4"
                    >
                        Adopter une créature
                    </Link>
                </div>
            </div>
            <FilmStrip
                :creatures="creatures"
                :active-id="creature.id"
                aria-label="Choisir la créature affichée sur le boîtier"
                @select="select"
            />
        </section>

        <section
            aria-labelledby="ranking-title"
            class="flex flex-col items-start gap-2 border-4 border-border bg-card p-4 shadow-hard-lg"
            :class="creature ? 'lg:col-span-3' : 'lg:col-span-6'"
        >
            <PixelIcon name="trophy" class="size-10" />
            <h2 id="ranking-title" class="kicker">03 · Classement</h2>
            <p class="pixel-title text-[28px]">Qui est la plus heureuse ?</p>
            <p>Les créatures de tous les joueurs, rangées par points.</p>
            <Link
                :href="leaderboard()"
                class="mt-auto inline-flex min-h-11 items-center font-extrabold text-link underline decoration-2 underline-offset-4"
            >
                Voir le classement
            </Link>
        </section>

        <section
            aria-labelledby="shop-title"
            class="flex flex-col items-start gap-2 border-4 border-ink bg-acid p-4 text-ink shadow-hard-lg [--ring:var(--color-night)]"
            :class="creature ? 'lg:col-span-3' : 'lg:col-span-6'"
        >
            <PixelIcon name="shop" class="size-10" />
            <h2 id="shop-title" class="kicker">04 · Au magasin</h2>
            <p class="pixel-title text-[28px]">De quoi la régaler</p>
            <p>Nourriture, jouets et repos pour vos créatures.</p>
            <Link
                :href="shop()"
                class="mt-auto inline-flex min-h-11 items-center font-extrabold underline decoration-2 underline-offset-4"
            >
                Aller au magasin
            </Link>
        </section>
    </div>
</template>

<style scoped>
/* The giant name: as big as the scene allows, never wider than it. */
.creature-name {
    font-size: clamp(2.5rem, min(20cqi, 150cqi / var(--chars)), 10rem);
    overflow-wrap: anywhere;
}

@media (width >= 64rem) {
    .creature-name {
        font-size: clamp(3.5rem, min(12.5cqi, 150cqi / var(--chars)), 10rem);
    }
}
</style>
