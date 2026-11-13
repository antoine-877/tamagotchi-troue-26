<script setup>
import { Link } from '@inertiajs/vue3';
import CreatureSprite from '@/components/CreatureSprite.vue';
import LcdScreen from '@/components/LcdScreen.vue';
import { frameNumber } from '@/lib/format';
import { moodOf } from '@/lib/mood';

// An ink film strip with perforations and numbered LCD frames.
// Give `href` (creature => url) to get links; otherwise each frame
// is a button that emits `select` with the creature. `label` names the list.
defineProps({
    creatures: { type: Array, required: true },
    activeId: { type: Number, default: null },
    href: { type: Function, default: null },
    label: { type: String, default: undefined },
});

defineEmits(['select']);
</script>

<template>
    <div class="bg-ink px-2.5 dark:outline-2 dark:outline-paper">
        <div aria-hidden="true" class="film-perforations my-2" />
        <ul
            :aria-label="label"
            class="grid auto-cols-[minmax(112px,150px)] grid-flow-col gap-1.5 overflow-x-auto"
        >
            <li v-for="creature in creatures" :key="creature.id">
                <component
                    :is="href ? Link : 'button'"
                    :href="href ? href(creature) : undefined"
                    :type="href ? undefined : 'button'"
                    :aria-current="
                        href && creature.id === activeId ? 'true' : undefined
                    "
                    :aria-pressed="href ? undefined : creature.id === activeId"
                    class="flex w-full cursor-pointer flex-col gap-1.5 border-[3px] p-1.5 text-left text-paper transition-transform hover:-translate-y-1 hover:text-acid focus-visible:outline-offset-[-6px] focus-visible:outline-acid motion-reduce:transition-none motion-reduce:hover:translate-y-0"
                    :class="
                        creature.id === activeId
                            ? 'border-acid'
                            : 'border-transparent'
                    "
                    @click="href ? null : $emit('select', creature)"
                >
                    <LcdScreen :grain="5" class="grid h-24 place-items-center">
                        <CreatureSprite
                            :species="creature.species"
                            :state="moodOf(creature).state"
                            class="size-20"
                        />
                    </LcdScreen>
                    <span class="truncate font-mono text-[13px] font-bold">
                        {{ frameNumber(creature.id) }} {{ creature.name }}
                    </span>
                </component>
            </li>
        </ul>
        <div aria-hidden="true" class="film-perforations my-2" />
    </div>
</template>
