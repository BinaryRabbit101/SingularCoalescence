<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
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
    if (activeId.value === id) return; // allow link navigation on second tap
    event.preventDefault();
    activeId.value = id;
}
</script>

<template>
    <Head title="Characters" />

        <div class="max-w-7xl mx-auto px-6 py-20">
            <!-- Header -->
            <div class="mb-16">
                <p class="text-xs tracking-widest uppercase mb-3" style="color: #8b5cf6;">The Universe</p>
                <h1 class="text-4xl font-bold mb-4" style="color: #e8e8f0;">Characters</h1>
                <p class="text-base max-w-2xl" style="color: #6b7280;">
                    Each character in the universe carries a story shaped by the world around them.
                </p>
            </div>

            <!-- Empty state -->
            <div v-if="!characters.length" class="text-center py-20">
                <p style="color: #4b5563;">No characters yet.</p>
            </div>

            <!-- Characters grid -->
            <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <Link
                    v-for="character in characters"
                    :key="character.id"
                    :href="`/characters/${character.slug}`"
                    class="group rounded-xl overflow-hidden border transition-all duration-300"
                    style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);"
                    onmouseenter="this.style.borderColor='rgba(139,92,246,0.3)'; this.style.transform='translateY(-2px)'"
                    onmouseleave="this.style.borderColor='rgba(255,255,255,0.08)'; this.style.transform=''"
                    @touchstart="handleTouch(character.id, $event)"
                >
                    <!-- Portrait -->
                    <div class="aspect-[3/4] relative overflow-hidden"
                         style="background: linear-gradient(160deg, rgba(139,92,246,0.25) 0%, rgba(10,10,15,1) 80%);">
                        <img v-if="character.profile_image"
                             :src="`/storage/${character.profile_image}`"
                             class="absolute inset-0 w-full h-full object-cover object-top transition-opacity duration-500"
                             :class="character.action_image ? (activeId === character.id ? 'opacity-0' : 'opacity-100 group-hover:opacity-0') : 'opacity-80 group-hover:opacity-100'"
                             :alt="character.name" />
                        <img v-if="character.action_image"
                             :src="`/storage/${character.action_image}`"
                             class="absolute inset-0 w-full h-full object-cover object-top transition-opacity duration-500"
                             :class="activeId === character.id ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'"
                             :alt="`${character.name} action`" />
                        <div class="absolute inset-x-0 bottom-0 h-12"
                             style="background: linear-gradient(to top, rgba(10,10,15,0.9), transparent);"></div>
                    </div>

                    <div class="p-3">
                        <h2 class="text-base font-bold mb-0.5" style="color: #e8e8f0;">{{ character.name }}</h2>
                        <p v-if="character.tagline" class="text-xs leading-snug" style="color: #6b7280;">{{ character.tagline }}</p>
                    </div>
                </Link>
            </div>
        </div>
</template>
