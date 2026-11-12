<script setup>
import { Form, Head } from '@inertiajs/vue3';
import GiftCodeController from '@/actions/App/Http/Controllers/GiftCodeController';
import PurchaseController from '@/actions/App/Http/Controllers/PurchaseController';
import ShopController from '@/actions/App/Http/Controllers/ShopController';
import InputError from '@/components/InputError.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { gauges } from '@/lib/species';
import { index } from '@/routes/shop';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Magasin', href: index() }],
    },
});

defineProps({
    items: Array,
    search: String,
    creatures: Array,
});
</script>

<template>
    <Head title="Magasin" />

    <div class="space-y-8 p-4">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <h1 class="text-xl font-semibold">Magasin</h1>

            <Form
                v-bind="ShopController.index.form()"
                class="flex items-end gap-2"
            >
                <div class="grid gap-2">
                    <Label for="q">Chercher un article</Label>
                    <Input id="q" name="q" :default-value="search" />
                </div>
                <Button type="submit" variant="secondary">Chercher</Button>
            </Form>
        </div>

        <p v-if="items.length === 0" class="text-muted-foreground">
            Aucun article ne correspond à « {{ search }} ».
        </p>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <Card v-for="item in items" :key="item.id">
                <CardHeader>
                    <CardTitle class="flex items-center justify-between gap-2">
                        {{ item.name }}
                        <Badge variant="secondary">{{ item.price }} €</Badge>
                    </CardTitle>
                    <CardDescription>{{ item.description }}</CardDescription>
                </CardHeader>
                <CardContent class="space-y-4">
                    <p class="text-sm text-muted-foreground">
                        {{ gauges[item.effect] }} + {{ item.effect_value }} ·
                        {{ item.stock }} en stock
                    </p>

                    <Form
                        v-bind="PurchaseController.store.form(item.id)"
                        v-slot="{ errors, processing }"
                        class="space-y-3"
                    >
                        <input type="hidden" name="price" :value="item.price" />

                        <div class="flex gap-2">
                            <div class="grid flex-1 gap-2">
                                <Label :for="`creature-${item.id}`">Pour</Label>
                                <select
                                    :id="`creature-${item.id}`"
                                    name="creature_id"
                                    class="h-9 rounded-md border bg-transparent px-2 text-sm"
                                >
                                    <option
                                        v-for="creature in creatures"
                                        :key="creature.id"
                                        :value="creature.id"
                                    >
                                        {{ creature.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="grid w-24 gap-2">
                                <Label :for="`quantity-${item.id}`">
                                    Quantité
                                </Label>
                                <Input
                                    :id="`quantity-${item.id}`"
                                    name="quantity"
                                    type="number"
                                    min="1"
                                    default-value="1"
                                />
                            </div>
                        </div>
                        <InputError :message="errors.quantity" />

                        <Button
                            type="submit"
                            :disabled="processing || creatures.length === 0"
                        >
                            Acheter
                        </Button>
                    </Form>
                </CardContent>
            </Card>
        </div>

        <section class="max-w-md space-y-4">
            <h2 class="text-lg font-semibold">Code cadeau</h2>

            <Form
                v-bind="GiftCodeController.store.form()"
                v-slot="{ errors, processing }"
                class="flex items-end gap-2"
                reset-on-success
            >
                <div class="grid flex-1 gap-2">
                    <Label for="code">Code à quatre chiffres</Label>
                    <Input id="code" name="code" maxlength="4" />
                    <InputError :message="errors.code" />
                </div>
                <Button type="submit" :disabled="processing">Utiliser</Button>
            </Form>
        </section>
    </div>
</template>
