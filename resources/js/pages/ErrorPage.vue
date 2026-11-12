<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import CreatureSprite from '@/components/CreatureSprite.vue';
import CropMarks from '@/components/CropMarks.vue';
import LcdScreen from '@/components/LcdScreen.vue';
import PixelIcon from '@/components/PixelIcon.vue';
import { dashboard, home } from '@/routes';

defineOptions({
    layout: { bleed: true },
});

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

// What the LCD shows: a padlock, a lost creature, an exhausted one.
const screens = {
    403: { icon: 'lock', tone: 'bg-rose' },
    404: { species: 'fox', state: 'sad', tone: 'bg-lilac' },
    429: { species: 'cat', state: 'sleepy', tone: 'bg-aqua' },
};

const title = computed(() => titles[props.status]);
const message = computed(() => messages[props.status]);
const screen = computed(() => screens[props.status] ?? screens[404]);
</script>

<template>
    <Head :title="title" />

    <section
        aria-labelledby="error-title"
        class="@container relative flex flex-1 flex-col px-4 pt-5 pb-12 text-ink [--ring:var(--color-night)] sm:px-8"
        :class="screen.tone"
    >
        <CropMarks />

        <div class="mx-auto flex w-full max-w-[1216px] flex-1 flex-col">
            <div
                class="mb-6 flex flex-wrap items-center justify-between gap-x-6 gap-y-1 border-b-2 border-ink pb-2"
            >
                <!-- Guests have no header: give them the logo. -->
                <Link
                    v-if="!$page.props.auth.user"
                    :href="home()"
                    class="-my-1 py-1"
                >
                    <AppLogo />
                    <span class="sr-only">, retour à l'accueil</span>
                </Link>
                <span class="kicker">Erreur {{ status }}</span>
            </div>

            <div
                class="flex flex-1 flex-col gap-8 lg:grid lg:grid-cols-12 lg:items-center lg:gap-x-4"
            >
                <div class="flex flex-col items-start gap-4 lg:col-span-7">
                    <p
                        aria-hidden="true"
                        class="status-code pixel-title font-bold"
                    >
                        {{ status }}
                    </p>
                    <h1
                        id="error-title"
                        class="pixel-title text-[clamp(2.25rem,6vw,3.75rem)] uppercase"
                    >
                        <span class="sr-only">Erreur {{ status }} : </span
                        >{{ title }}
                    </h1>
                    <p
                        class="text-[clamp(1.25rem,2.4vw,1.5rem)] font-extrabold"
                    >
                        {{ message }}
                    </p>
                    <Link
                        :href="$page.props.auth.user ? dashboard() : home()"
                        class="mt-2 inline-flex min-h-12 items-center border-2 border-ink bg-paper px-6 font-extrabold text-ink shadow-[3px_3px_0_0_var(--color-ink)] hover:bg-acid"
                    >
                        {{
                            $page.props.auth.user
                                ? 'Retour à mes créatures'
                                : "Retour à l'accueil"
                        }}
                    </Link>
                </div>

                <div
                    class="mx-auto w-[280px] max-w-full rounded-[24px] shadow-[8px_8px_0_0_var(--color-ink)] sm:w-[320px] lg:col-span-5"
                >
                    <LcdScreen
                        :grain="10"
                        class="flex flex-col gap-2 rounded-[24px] border-4 border-ink px-4 pt-3 pb-5"
                    >
                        <div
                            aria-hidden="true"
                            class="flex justify-between font-mono text-sm font-bold"
                        >
                            <span>ERR</span>
                            <span class="lcd-blink">{{ status }}</span>
                        </div>
                        <div class="grid aspect-square place-items-center">
                            <PixelIcon
                                v-if="screen.icon"
                                :name="screen.icon"
                                class="size-3/5"
                            />
                            <CreatureSprite
                                v-else
                                :species="screen.species"
                                :state="screen.state"
                                class="size-4/5"
                            />
                        </div>
                    </LcdScreen>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
/* The status code: giant, but never wider than the page. */
.status-code {
    font-size: clamp(6rem, 38cqi, 16rem);
    line-height: 0.8;
}

@media (width >= 64rem) {
    .status-code {
        font-size: clamp(10rem, 24cqi, 18rem);
    }
}
</style>
