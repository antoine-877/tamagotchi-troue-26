<script setup>
import { Form } from '@inertiajs/vue3';
import GiftCodeController from '@/actions/App/Http/Controllers/GiftCodeController';
import InputError from '@/components/InputError.vue';
import PixelIcon from '@/components/PixelIcon.vue';
import PixelSprite from '@/components/PixelSprite.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

// A pair of scissors, for the cutting line around the coupon.
const scissors = [
    '##......##',
    '.##....##.',
    '..##..##..',
    '...####...',
    '....##....',
    '..##..##..',
    '.#..#.#..#',
    '.#..#.#..#',
    '..##...##.',
];
</script>

<template>
    <!-- The coupon stays rose in night mode, so its rules stay ink. -->
    <div
        class="relative border-2 border-dashed border-foreground p-2.5 sm:p-3.5"
    >
        <PixelSprite
            :pixels="scissors"
            class="absolute -top-[11px] left-6 h-5 w-[22px] bg-background px-0.5 text-foreground"
        />
        <div
            class="flex flex-col bg-rose text-ink [--border:var(--color-ink)] [--ring:var(--color-night)] sm:flex-row"
        >
            <div
                class="flex items-center gap-3 border-b-2 border-dashed border-ink px-4 py-3 sm:w-40 sm:flex-col sm:items-start sm:justify-between sm:border-r-2 sm:border-b-0 sm:py-4"
            >
                <PixelIcon name="gift" class="size-12" />
                <p class="kicker">À découper</p>
            </div>

            <div class="flex flex-1 flex-col gap-3 px-4 py-4 sm:px-6">
                <div>
                    <p class="kicker">Bon cadeau · valable une fois</p>
                    <h2
                        id="gift-title"
                        class="mt-1 pixel-title text-[clamp(1.75rem,4vw,2.5rem)]"
                    >
                        Code cadeau
                    </h2>
                    <p class="mt-1 text-pretty">
                        Quatre chiffres, et votre solde est crédité.
                    </p>
                </div>

                <Form
                    v-bind="GiftCodeController.store.form()"
                    v-slot="{ errors, processing }"
                    class="flex flex-col gap-2"
                    reset-on-success
                >
                    <label for="code" class="kicker">
                        Code à quatre chiffres
                    </label>
                    <div class="flex flex-wrap gap-3">
                        <Input
                            id="code"
                            name="code"
                            maxlength="4"
                            autocomplete="off"
                            inputmode="numeric"
                            placeholder="0000"
                            class="h-14 w-[9.5rem] border-ink bg-paper text-center font-mono text-[32px] tracking-[0.3em] text-ink placeholder:text-ink/30"
                        />
                        <Button
                            type="submit"
                            size="lg"
                            class="h-14 bg-ink text-paper"
                            :disabled="processing"
                        >
                            Utiliser
                        </Button>
                    </div>
                    <InputError
                        v-if="errors.code"
                        :message="errors.code"
                        class="self-start border-2 border-ink bg-paper px-2 py-1 [&_p]:!text-alert"
                    />
                </Form>
            </div>
        </div>
    </div>
</template>
