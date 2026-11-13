<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, reactive, ref } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import CropMarks from '@/components/CropMarks.vue';
import FilmStrip from '@/components/FilmStrip.vue';
import GaugeCard from '@/components/GaugeCard.vue';
import LcdScreen from '@/components/LcdScreen.vue';
import PageMasthead from '@/components/PageMasthead.vue';
import PixelIcon from '@/components/PixelIcon.vue';
import SectionHeading from '@/components/SectionHeading.vue';
import SpeciesPicker from '@/components/SpeciesPicker.vue';
import TamagotchiDevice from '@/components/TamagotchiDevice.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { moodOf, reactionOf } from '@/lib/mood';
import { gauges, species } from '@/lib/species';
import { dashboard, home, login, register } from '@/routes';

// A demo creature that lives only in this page: nothing is sent to the server.
const speciesKeys = Object.keys(species);

const pet = reactive({
    name: 'Bip',
    species: 'dragon',
    food: 70,
    mood: 60,
    energy: 65,
});

// The device shows « N° 001 » to « N° 005 », like the frames of the film.
const creature = computed(() => ({
    ...pet,
    id: speciesKeys.indexOf(pet.species) + 1,
}));

const clamp = (value) => Math.max(0, Math.min(100, value));

const effects = {
    feed: { food: 30, mood: 5, energy: 0 },
    play: { food: -10, mood: 25, energy: -15 },
    sleep: { food: -5, mood: 0, energy: 40 },
};

// Right after a key, the creature reacts for a few seconds.
const reaction = ref(null);
let reactionTimer = null;

function press(action) {
    for (const gauge of Object.keys(gauges)) {
        pet[gauge] = clamp(pet[gauge] + effects[action][gauge]);
    }

    clearTimeout(reactionTimer);
    reaction.value = action;
    reactionTimer = setTimeout(() => {
        reaction.value = null;
    }, 3000);
}

const mood = computed(() => {
    return reaction.value ? reactionOf(reaction.value, pet) : moodOf(pet);
});

// Like a real pet, the gauges slowly go down.
let decayTimer = null;

onMounted(() => {
    decayTimer = setInterval(() => {
        pet.food = clamp(pet.food - 2);
        pet.mood = clamp(pet.mood - 1);
        pet.energy = clamp(pet.energy - 1);
    }, 5000);
});

onUnmounted(() => {
    clearInterval(decayTimer);
    clearTimeout(reactionTimer);
});

// The five species on the film, each in a different mood.
const showcase = [
    { food: 80, mood: 90, energy: 80 },
    { food: 60, mood: 50, energy: 60 },
    { food: 60, mood: 60, energy: 10 },
    { food: 15, mood: 60, energy: 60 },
    { food: 80, mood: 85, energy: 70 },
].map((values, index) => ({
    id: index + 1,
    name: species[speciesKeys[index]].label,
    species: speciesKeys[index],
    ...values,
}));

const device = ref(null);

function pickFromFilm(picked) {
    pet.species = picked.species;

    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
    device.value?.scrollIntoView({
        block: 'center',
        behavior: reduce.matches ? 'auto' : 'smooth',
    });
}

const sections = [
    {
        number: '01',
        icon: 'egg',
        kicker: 'Adopter',
        title: 'Une créature, puis toute une portée',
        text: 'Adoptez des créatures, donnez-leur un nom et veillez sur leurs trois jauges : nourriture, humeur, énergie.',
    },
    {
        number: '02',
        icon: 'house',
        kicker: 'Visiter',
        title: "L'enclos des voisins",
        text: "Passez voir les créatures des autres joueurs et laissez un mot dans leur livre d'or.",
    },
    {
        number: '03',
        icon: 'trophy',
        kicker: 'Briller',
        title: 'Classement et magasin',
        text: 'Grimpez au classement des créatures les plus heureuses et dépensez votre solde au magasin.',
    },
];
</script>

<template>
    <Head title="Bienvenue" />

    <div class="flex min-h-screen flex-col">
        <header class="border-b-4 border-border bg-background">
            <div
                class="mx-auto flex max-w-[1280px] flex-wrap items-center justify-between gap-x-6 gap-y-2 px-4 py-3 sm:px-8"
            >
                <Link :href="home()" class="-my-1 py-1">
                    <AppLogo />
                    <span class="sr-only">, accueil</span>
                </Link>

                <nav aria-label="Compte" class="flex items-center gap-x-5">
                    <Button v-if="$page.props.auth.user" as-child>
                        <Link :href="dashboard()">Mon tamagotchi</Link>
                    </Button>
                    <template v-else>
                        <TextLink :href="login()">Se connecter</TextLink>
                        <Button as-child>
                            <Link :href="register()">Créer un compte</Link>
                        </Button>
                    </template>
                </nav>
            </div>
        </header>

        <main id="contenu" class="flex-1">
            <PageMasthead
                aria-labelledby="cover-title"
                :tone="mood.tone"
                kicker="Jeu d'élevage en pixels"
                :aside="`Démo · Humeur : ${mood.word}`"
            >
                <div
                    class="flex flex-col gap-8 lg:grid lg:grid-cols-12 lg:items-start lg:gap-x-4"
                >
                    <div
                        class="flex flex-col gap-5 lg:col-span-7 lg:row-start-1"
                    >
                        <h1
                            id="cover-title"
                            class="cover-title pixel-title uppercase"
                        >
                            Élevez <br />des bêtes <br />en pixels
                        </h1>
                        <p
                            class="max-w-[34rem] text-[clamp(1.25rem,2.4vw,1.5rem)] leading-snug font-extrabold text-balance"
                        >
                            Un tamagotchi en ligne : adoptez des créatures,
                            nourrissez-les, faites-les jouer et rendez visite à
                            celles des autres joueurs.
                        </p>
                        <div
                            v-if="!$page.props.auth.user"
                            class="flex flex-wrap items-center gap-x-6 gap-y-3"
                        >
                            <Button
                                as-child
                                size="lg"
                                class="bg-paper text-ink [--border:var(--color-ink)]"
                            >
                                <Link :href="register()">
                                    Adopter ma première créature
                                </Link>
                            </Button>
                            <TextLink :href="login()" plain>
                                J'ai déjà un compte
                            </TextLink>
                        </div>
                        <Button
                            v-else
                            as-child
                            size="lg"
                            class="self-start bg-paper text-ink [--border:var(--color-ink)]"
                        >
                            <Link :href="dashboard()">
                                Retrouver mes créatures
                            </Link>
                        </Button>
                    </div>

                    <div
                        ref="device"
                        class="flex flex-col items-center gap-7 lg:col-span-5 lg:row-span-2 lg:row-start-1"
                    >
                        <TamagotchiDevice
                            :creature="creature"
                            :state="mood.state"
                        >
                            <button
                                type="button"
                                class="device-key"
                                @click="press('feed')"
                            >
                                Nourrir
                            </button>
                            <button
                                type="button"
                                class="device-key"
                                @click="press('play')"
                            >
                                Jouer
                            </button>
                            <button
                                type="button"
                                class="device-key"
                                @click="press('sleep')"
                            >
                                Dodo
                            </button>
                        </TamagotchiDevice>

                        <div
                            role="group"
                            aria-labelledby="picker-label"
                            class="flex flex-col items-center gap-2"
                        >
                            <span id="picker-label" class="kicker">
                                Changer d'espèce
                            </span>
                            <SpeciesPicker v-model="pet.species" />
                        </div>
                    </div>

                    <div
                        class="flex flex-col gap-3 lg:col-span-7 lg:row-start-2"
                    >
                        <p
                            class="border-t-2 border-ink pt-3 text-[clamp(1.25rem,2.4vw,1.5rem)] leading-tight font-extrabold text-balance"
                        >
                            {{ mood.headline }}
                        </p>
                        <div class="grid gap-3 min-[480px]:grid-cols-3">
                            <GaugeCard
                                v-for="(label, gauge) in gauges"
                                :key="gauge"
                                :gauge="gauge"
                                :value="pet[gauge]"
                            />
                        </div>
                        <p class="text-pretty">
                            Essayez les touches du boîtier. Ici, rien n'est
                            enregistré : c'est une démonstration.
                        </p>
                    </div>
                </div>
            </PageMasthead>

            <div class="bg-ink text-paper dark:border-y-2 dark:border-paper">
                <p
                    aria-live="polite"
                    class="mx-auto max-w-[1280px] px-4 py-3 font-mono text-[15px] tracking-wide sm:px-8"
                >
                    <span class="font-bold text-acid">DÉMO</span> ·
                    {{ mood.status }}
                </p>
            </div>

            <section
                aria-labelledby="species-title"
                class="mx-auto flex max-w-[1280px] flex-col gap-3 px-4 pt-9 sm:px-8"
            >
                <SectionHeading
                    id="species-title"
                    title="Cinq espèces à adopter"
                >
                    <p class="kicker text-muted-foreground">
                        Touchez une image pour la mettre dans le boîtier
                    </p>
                </SectionHeading>
                <FilmStrip
                    :creatures="showcase"
                    :active-id="creature.id"
                    label="Choisir l'espèce de la créature de démonstration"
                    @select="pickFromFilm"
                />
            </section>

            <section
                aria-labelledby="sections-title"
                class="mx-auto max-w-[1280px] px-4 pt-12 pb-14 sm:px-8"
            >
                <h2 id="sections-title" class="mb-4 kicker">
                    Au sommaire du jeu
                </h2>
                <ol class="grid gap-6 lg:grid-cols-3">
                    <li
                        v-for="section in sections"
                        :key="section.number"
                        class="flex flex-col gap-2 border-4 border-border bg-card p-5 shadow-hard-lg"
                    >
                        <div
                            class="flex items-start justify-between border-b-2 border-border pb-2"
                        >
                            <span
                                class="font-mono text-[64px] leading-none font-bold"
                                >{{ section.number }}</span
                            >
                            <PixelIcon :name="section.icon" class="size-10" />
                        </div>
                        <p class="mt-1 kicker">{{ section.kicker }}</p>
                        <h3 class="pixel-title text-[28px] text-balance">
                            {{ section.title }}
                        </h3>
                        <p class="text-pretty">{{ section.text }}</p>
                    </li>
                </ol>
            </section>

            <section
                aria-labelledby="egg-title"
                class="relative border-y-4 border-ink bg-acid px-4 py-10 text-ink [--ring:var(--color-night)] sm:px-8"
            >
                <CropMarks />
                <div
                    class="mx-auto flex max-w-[1216px] flex-col items-start gap-6 sm:flex-row sm:items-center"
                >
                    <LcdScreen
                        :grain="6"
                        class="grid size-28 shrink-0 place-items-center rounded-[14px] border-4 border-ink"
                    >
                        <PixelIcon name="egg" class="lcd-blink size-16" />
                    </LcdScreen>
                    <div class="flex flex-col items-start gap-3">
                        <h2
                            id="egg-title"
                            class="pixel-title text-[clamp(2.25rem,5vw,3.5rem)] uppercase"
                        >
                            Un œuf vous attend.
                        </h2>
                        <Button
                            as-child
                            size="lg"
                            class="bg-paper text-ink [--border:var(--color-ink)]"
                        >
                            <Link
                                v-if="$page.props.auth.user"
                                :href="dashboard()"
                            >
                                Aller à mon tamagotchi
                            </Link>
                            <Link v-else :href="register()">
                                Créer un compte
                            </Link>
                        </Button>
                    </div>
                </div>
            </section>
        </main>

        <footer>
            <div
                class="mx-auto flex max-w-[1280px] flex-wrap items-center justify-between gap-x-6 gap-y-2 px-4 py-6 sm:px-8"
            >
                <p class="kicker">Tamagotchi · un jeu d'élevage en pixels</p>
                <p v-if="!$page.props.auth.user" class="flex gap-x-5">
                    <TextLink :href="login()">Se connecter</TextLink>
                    <TextLink :href="register()">Créer un compte</TextLink>
                </p>
            </div>
        </footer>
    </div>
</template>

<style scoped>
/* The cover title: as large as its column allows. */
.cover-title {
    font-size: clamp(3rem, 17cqi, 8rem);
    line-height: 0.95;
}

@media (width >= 64rem) {
    .cover-title {
        font-size: clamp(4rem, 10.5cqi, 9rem);
    }
}
</style>
