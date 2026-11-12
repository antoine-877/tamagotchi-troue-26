<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';
import CreatureSprite from '@/components/CreatureSprite.vue';
import LcdScreen from '@/components/LcdScreen.vue';
import PixelIcon from '@/components/PixelIcon.vue';
import { isLow } from '@/lib/mood';
import { gauges, species } from '@/lib/species';

// The pink egg-shaped device. Without a creature, the screen shows an egg.
// The default slot holds the keys under the screen.
const props = defineProps({
    creature: { type: Object, default: null },
    state: { type: String, default: 'idle' },
});

const now = ref(new Date());
let timer = null;

onMounted(() => {
    timer = setInterval(() => {
        now.value = new Date();
    }, 10_000);
});

onUnmounted(() => clearInterval(timer));

const two = (n) => String(n).padStart(2, '0');

const caption = computed(() =>
    props.creature ? `N° ${String(props.creature.id).padStart(3, '0')}` : 'Œuf',
);

const spriteLabel = computed(
    () =>
        `${props.creature.name}, ${species[props.creature.species]?.label.toLowerCase() ?? 'créature'} en pixels`,
);
</script>

<template>
    <div
        class="device-shell relative flex w-[316px] max-w-full flex-col items-center gap-4 border-4 border-ink bg-rose px-4 pt-9 pb-[72px] text-ink sm:w-[360px] sm:px-6"
    >
        <span
            aria-hidden="true"
            class="absolute top-[30px] left-[50px] h-4 w-[70px] -rotate-[24deg] rounded-[50%] bg-paper/65"
        />
        <p class="kicker">{{ caption }}</p>

        <LcdScreen
            :grain="11"
            class="flex w-[236px] max-w-full flex-col items-center gap-2 rounded-[16px] border-4 border-ink px-3 pt-2.5 pb-3.5"
        >
            <div
                aria-hidden="true"
                class="flex items-center justify-between self-stretch font-mono text-sm font-bold"
            >
                <div class="flex gap-2">
                    <PixelIcon
                        v-for="(label, gauge) in gauges"
                        :key="gauge"
                        :name="gauge"
                        class="size-6"
                        :class="{
                            'lcd-blink':
                                creature && isLow(gauge, creature[gauge]),
                        }"
                    />
                </div>
                <span>
                    {{ two(now.getHours()) }}<span class="lcd-colon">:</span
                    >{{ two(now.getMinutes()) }}
                </span>
            </div>

            <div class="relative grid size-44 place-items-center">
                <CreatureSprite
                    v-if="creature"
                    :species="creature.species"
                    :state="state"
                    :label="spriteLabel"
                    class="size-full"
                />
                <PixelIcon
                    v-else
                    name="egg"
                    label="Un œuf en pixels"
                    class="size-28"
                />

                <PixelIcon
                    v-if="creature && state === 'happy'"
                    name="mood"
                    class="lcd-rise absolute top-10 left-1.5 size-6"
                />
                <span
                    v-if="creature && state === 'sleepy'"
                    aria-hidden="true"
                    class="absolute top-6 right-2 font-pixel text-2xl font-bold"
                >
                    <span class="lcd-rise inline-block">z</span>
                    <span class="lcd-rise inline-block [animation-delay:750ms]"
                        >Z</span
                    >
                </span>
            </div>
        </LcdScreen>

        <div v-if="$slots.default" class="flex justify-center gap-2">
            <slot />
        </div>
    </div>
</template>
