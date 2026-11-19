<script setup>
import { Form, Head, Link } from "@inertiajs/vue3";
import PurchaseController from "@/actions/App/Http/Controllers/PurchaseController";
import ShopController from "@/actions/App/Http/Controllers/ShopController";
import GiftCodeTicket from "@/components/GiftCodeTicket.vue";
import InputError from "@/components/InputError.vue";
import PageMasthead from "@/components/PageMasthead.vue";
import PixelIcon from "@/components/PixelIcon.vue";
import ShopItemScreen from "@/components/ShopItemScreen.vue";
import TextLink from "@/components/TextLink.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { twoDigits } from "@/lib/format";
import { gauges } from "@/lib/species";
import { create } from "@/routes/creatures";
import { index } from "@/routes/shop";

defineOptions({
    layout: {
        breadcrumbs: [{ title: "Magasin", href: index() }],
        bleed: true,
    },
});

defineProps({
    items: Array,
    search: String,
    creatures: Array,
});

const fieldClass =
    "h-11 w-full min-w-0 border-2 border-input bg-background px-3 text-base font-bold";
</script>

<template>
    <Head title="Magasin" />

    <PageMasthead
        aria-labelledby="shop-title"
        kicker="Rubrique 04 · Petites annonces"
        aside="Livraison immédiate dans l'enclos"
    >
        <div
            class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between"
        >
            <div class="flex flex-col gap-3">
                <h1
                    id="shop-title"
                    class="pixel-title text-[clamp(3.5rem,11vw,8rem)] uppercase"
                >
                    Magasin
                </h1>
                <p class="max-w-[460px] text-lg text-pretty">
                    Nourriture, jouets et repos&nbsp;: choisissez l'article, la
                    créature qui le reçoit, et c'est réglé.
                </p>
            </div>

            <Form
                v-bind="ShopController.index.form()"
                role="search"
                class="flex w-full flex-col gap-1.5 lg:max-w-[440px]"
            >
                <label for="q" class="kicker">Chercher un article</label>
                <div class="flex gap-2">
                    <Input
                        id="q"
                        name="q"
                        type="search"
                        :default-value="search"
                        placeholder="Bonbon, coussin…"
                        class="border-ink bg-paper text-ink placeholder:text-ink/55"
                    />
                    <Button type="submit" class="bg-ink text-paper">
                        Chercher
                    </Button>
                </div>
            </Form>
        </div>
    </PageMasthead>

    <div class="bg-ink text-paper dark:border-y-2 dark:border-paper">
        <p
            class="mx-auto flex max-w-[1280px] flex-wrap gap-x-4 px-4 py-3 font-mono text-[15px] tracking-wide sm:px-8"
        >
            <span>
                <span class="font-bold text-acid">EN RAYON</span> ·
                {{ items.length }}
                {{ items.length > 1 ? "articles" : "article" }}
                <template v-if="search">pour « {{ search }} »</template>
            </span>
            <Link
                v-if="search"
                :href="index()"
                class="inline-flex min-h-6 items-center font-bold text-acid underline decoration-2 underline-offset-4"
            >
                Tout le catalogue
            </Link>
        </p>
    </div>

    <div
        class="mx-auto flex w-full max-w-[1280px] flex-col gap-12 px-4 pt-12 pb-14 sm:px-8"
    >
        <div
            v-if="items.length === 0"
            class="flex flex-col items-start gap-4 border-4 border-border bg-card p-5 shadow-hard-lg sm:flex-row sm:items-center sm:p-6"
        >
            <div
                class="lcd grid size-24 shrink-0 place-items-center rounded-xl border-4 border-ink"
            >
                <span
                    aria-hidden="true"
                    class="lcd-blink font-pixel text-5xl font-bold"
                    >?</span
                >
            </div>
            <div class="flex min-w-0 flex-col gap-2">
                <p class="kicker text-muted-foreground">Rayon vide</p>
                <p
                    class="text-2xl leading-tight font-extrabold [overflow-wrap:anywhere]"
                >
                    Aucun article ne correspond à « {{ search }} ».
                </p>
                <TextLink :href="index()" class="self-start">
                    Voir tout le catalogue
                </TextLink>
            </div>
        </div>

        <p
            v-if="creatures.length === 0"
            class="flex flex-wrap items-center gap-x-4 gap-y-2 border-4 border-border bg-card p-4"
        >
            <PixelIcon name="egg" class="size-8 shrink-0" />
            <span class="font-extrabold">
                Les articles se donnent à une créature, et vous n'en avez pas
                encore.
            </span>
            <TextLink :href="create()">Adopter une créature</TextLink>
        </p>

        <ul
            v-if="items.length > 0"
            class="grid gap-x-6 gap-y-9 md:grid-cols-2 xl:grid-cols-3"
        >
            <li
                v-for="(item, position) in items"
                :key="item.id"
                class="flex min-w-0 flex-col border-4 border-border bg-card shadow-hard-lg"
            >
                <article
                    :aria-labelledby="`item-${item.id}`"
                    class="flex flex-1 flex-col"
                >
                    <div class="flex items-start justify-between gap-3 p-4">
                        <ShopItemScreen :item="item" />
                        <!-- The price tag: acid sticker, struck out when sold out. -->
                        <p
                            class="-mt-7 -mr-7 rotate-3 border-[3px] px-3 pt-1 pb-1.5 text-right"
                            :class="
                                item.stock === 0
                                    ? 'border-border bg-muted text-muted-foreground line-through decoration-4'
                                    : 'border-ink bg-acid text-ink shadow-[4px_4px_0_0_var(--color-ink)]'
                            "
                        >
                            <span class="block kicker text-[11px]">Prix</span>
                            <span
                                class="block font-mono text-[clamp(2.25rem,4vw,2.75rem)] leading-none font-bold whitespace-nowrap"
                                >{{ item.price }}</span
                            >
                        </p>
                    </div>

                    <div class="flex flex-1 flex-col gap-2 px-4 pb-4">
                        <p class="kicker text-muted-foreground">
                            Annonce n° {{ twoDigits(position + 1) }}
                        </p>
                        <h2
                            :id="`item-${item.id}`"
                            class="pixel-title text-[28px] [overflow-wrap:anywhere]"
                        >
                            {{ item.name }}
                        </h2>
                        <p class="text-pretty">{{ item.description }}</p>

                        <dl
                            class="mt-auto flex flex-wrap justify-between gap-x-5 gap-y-2 border-y-2 border-border pt-2.5 pb-2"
                        >
                            <div class="flex flex-col gap-0.5">
                                <dt class="kicker text-muted-foreground">
                                    Effet
                                </dt>
                                <dd
                                    class="flex items-center gap-1.5 font-mono font-bold whitespace-nowrap"
                                >
                                    <PixelIcon
                                        :name="item.effect"
                                        class="size-5 shrink-0"
                                    />
                                    {{ gauges[item.effect] }} +
                                    {{ item.effect_value }}
                                </dd>
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <dt class="kicker text-muted-foreground">
                                    Stock
                                </dt>
                                <dd class="font-mono font-bold">
                                    <span
                                        v-if="item.stock === 0"
                                        class="bg-ink px-1.5 text-rose dark:bg-rose dark:text-ink"
                                        >Épuisé</span
                                    >
                                    <span
                                        v-else-if="item.stock <= 3"
                                        class="lcd-blink"
                                        >Plus que {{ item.stock }}</span
                                    >
                                    <template v-else>
                                        {{ item.stock }} en stock
                                    </template>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <Form
                        v-bind="PurchaseController.store.form(item.id)"
                        v-slot="{ errors, processing }"
                        class="flex flex-col gap-3 border-t-4 border-border bg-muted p-4"
                    >
                        <input
                            type="hidden"
                            name="price_cents"
                            :value="item.price_cents"
                        />

                        <div class="grid grid-cols-[1fr_6rem] gap-2.5">
                            <div class="flex min-w-0 flex-col gap-1">
                                <label
                                    :for="`creature-${item.id}`"
                                    class="kicker"
                                >
                                    Pour
                                </label>
                                <select
                                    :id="`creature-${item.id}`"
                                    name="creature_id"
                                    :class="fieldClass"
                                >
                                    <option
                                        v-for="creature in creatures"
                                        :key="creature.id"
                                        :value="creature.id"
                                    >
                                        {{ creature.name }}
                                    </option>
                                    <option
                                        v-if="creatures.length === 0"
                                        disabled
                                        selected
                                    >
                                        Aucune créature
                                    </option>
                                </select>
                            </div>
                            <div class="flex flex-col gap-1">
                                <label
                                    :for="`quantity-${item.id}`"
                                    class="kicker"
                                >
                                    Quantité
                                </label>
                                <Input
                                    :id="`quantity-${item.id}`"
                                    name="quantity"
                                    type="number"
                                    min="1"
                                    default-value="1"
                                    class="font-mono"
                                />
                            </div>
                        </div>
                        <InputError :message="errors.quantity" />

                        <Button
                            type="submit"
                            size="lg"
                            class="w-full"
                            :disabled="processing || creatures.length === 0"
                        >
                            Acheter
                        </Button>
                        <p
                            v-if="creatures.length === 0"
                            class="text-sm text-muted-foreground"
                        >
                            Adoptez d'abord une créature pour acheter.
                        </p>
                    </Form>
                </article>
            </li>
        </ul>

        <section aria-labelledby="gift-title" class="max-w-[720px]">
            <GiftCodeTicket />
        </section>
    </div>
</template>
