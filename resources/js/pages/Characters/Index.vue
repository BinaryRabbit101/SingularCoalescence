<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineOptions({ layout: PublicLayout });

interface Character {
    id: number;
    slug: string;
    name: string;
    tagline?: string;
    profile_image?: string;
    action_image?: string;
}

defineProps<{
    characters: Character[];
}>();

const activeId = ref<number | null>(null);

function handleTouch(id: number, event: TouchEvent) {
    if (activeId.value === id) {
        return;
    } // allow link navigation on second tap

    event.preventDefault();
    activeId.value = id;
}
</script>

<template>
    <Head title="Characters" />

    <div class="mx-auto max-w-7xl px-6 py-20">
        <!-- Header -->
        <div class="mb-16">
            <p
                class="mb-3 text-xs tracking-widest uppercase"
                style="color: #8b5cf6"
            >
                The Universe
            </p>
            <h1 class="mb-4 text-4xl font-bold" style="color: #e8e8f0">
                Characters
            </h1>
            <p class="max-w-2xl text-base" style="color: #6b7280">
                Each character in the universe carries a story shaped by the
                world around them.
            </p>
        </div>

        <!-- Empty state -->
        <div v-if="!characters.length" class="py-20 text-center">
            <p style="color: #4b5563">No characters yet.</p>
        </div>

        <!-- Characters grid -->
        <div
            v-else
            class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-5"
        >
            <Link
                v-for="character in characters"
                :key="character.id"
                :href="`/characters/${character.slug}`"
                class="group overflow-hidden rounded-xl border transition-all duration-300"
                style="
                    border-color: rgba(255, 255, 255, 0.08);
                    background: rgba(255, 255, 255, 0.02);
                "
                onmouseenter="
                    this.style.borderColor = 'rgba(139,92,246,0.3)';
                    this.style.transform = 'translateY(-2px)';
                "
                onmouseleave="
                    this.style.borderColor = 'rgba(255,255,255,0.08)';
                    this.style.transform = '';
                "
                @touchstart="handleTouch(character.id, $event)"
            >
                <!-- Portrait -->
                <div
                    class="relative aspect-[3/4] overflow-hidden"
                    style="
                        background: linear-gradient(
                            160deg,
                            rgba(139, 92, 246, 0.25) 0%,
                            rgba(10, 10, 15, 1) 80%
                        );
                    "
                >
                    <img
                        v-if="character.profile_image"
                        :src="`/storage/${character.profile_image}`"
                        class="absolute inset-0 h-full w-full object-cover object-top transition-opacity duration-500"
                        :class="
                            character.action_image
                                ? activeId === character.id
                                    ? 'opacity-0'
                                    : 'opacity-100 group-hover:opacity-0'
                                : 'opacity-100'
                        "
                        :alt="character.name"
                    />
                    <img
                        v-if="character.action_image"
                        :src="`/storage/${character.action_image}`"
                        class="absolute inset-0 h-full w-full object-cover object-top transition-opacity duration-500"
                        :class="
                            activeId === character.id
                                ? 'opacity-100'
                                : 'opacity-0 group-hover:opacity-100'
                        "
                        :alt="`${character.name} action`"
                    />
                    <div
                        class="absolute inset-x-0 bottom-0 h-12"
                        style="
                            background: linear-gradient(
                                to top,
                                rgba(10, 10, 15, 0.9),
                                transparent
                            );
                        "
                    ></div>
                </div>

                <div class="p-3">
                    <h2
                        class="mb-0.5 text-base font-bold"
                        style="color: #e8e8f0"
                    >
                        {{ character.name }}
                    </h2>
                    <p
                        v-if="character.tagline"
                        class="text-xs leading-snug"
                        style="color: #6b7280"
                    >
                        {{ character.tagline }}
                    </p>
                </div>
            </Link>
        </div>
    </div>
</template>
