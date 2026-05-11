<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineOptions({ layout: PublicLayout });
import DiaryPageFlip from '@/components/DiaryPageFlip.vue';
import AudioPlayer from '@/components/AudioPlayer.vue';

interface DiaryEntry {
    id: number;
    entry_number: number;
    title: string;
    content: string;
    entry_date?: string;
    published: boolean;
}

interface MusicTrack {
    id: number;
    title: string;
    description?: string;
    file_path: string;
    cover_image?: string;
    duration_seconds?: number;
}

interface Character {
    id: number;
    name: string;
    slug: string;
    tagline?: string;
    description?: string;
    profile_image?: string;
    action_image?: string;
    traits?: string[];
    abilities?: string[];
    cons?: string[];
    diary_entries: DiaryEntry[];
    music_tracks: MusicTrack[];
}

defineProps<{
    character: Character;
}>();

const activeTab = ref<'about' | 'diary' | 'music'>('about');
</script>

<template>
    <Head :title="character.name" />

        <!-- Hero -->
        <section class="relative overflow-hidden" style="background: #0a0a0f;">
            <div class="absolute inset-0" style="background: linear-gradient(160deg, rgba(139,92,246,0.12) 0%, #0a0a0f 70%);"></div>
            <div class="relative z-10 max-w-7xl mx-auto px-6 pt-6">
                <Link href="/characters"
                      class="inline-flex items-center gap-2 text-xs tracking-widest uppercase transition-all duration-200 group"
                      style="color: #6b7280;"
                      onmouseenter="this.style.color='#a78bfa'"
                      onmouseleave="this.style.color='#6b7280'"
                >
                    <span class="transition-transform duration-200 group-hover:-translate-x-1">‹</span>
                    All Characters
                </Link>
            </div>
            <div class="relative z-10 max-w-7xl mx-auto px-6 py-8 flex flex-col md:flex-row items-center gap-8 md:gap-12">
                <!-- Portrait -->
                <div class="group flex-shrink-0 w-48 md:w-56 lg:w-64">
                    <div class="relative rounded-xl overflow-hidden shadow-2xl aspect-[3/4]"
                         style="background: rgba(139,92,246,0.1);">
                        <img v-if="character.profile_image"
                             :src="`/storage/${character.profile_image}`"
                             class="absolute inset-0 w-full h-full object-cover object-top transition-opacity duration-500"
                             :class="character.action_image ? 'opacity-100 group-hover:opacity-0' : ''"
                             :alt="character.name" />
                        <img v-if="character.action_image"
                             :src="`/storage/${character.action_image}`"
                             class="absolute inset-0 w-full h-full object-cover object-top opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                             :alt="`${character.name} action`" />
                        <div v-if="!character.profile_image" class="w-full h-full flex items-center justify-center" style="color: #4b5563;">
                            <span class="text-4xl">?</span>
                        </div>
                    </div>
                </div>
                <!-- Text -->
                <div>
                    <h1 class="text-5xl md:text-7xl font-bold mb-3" style="color: #e8e8f0;">{{ character.name }}</h1>
                    <p v-if="character.tagline" class="text-xl max-w-2xl" style="color: #6b7280;">{{ character.tagline }}</p>
                </div>
            </div>
        </section>

        <!-- Tab navigation -->
        <div class="sticky top-16 z-30 border-b" style="background: rgba(10,10,15,0.95); border-color: rgba(255,255,255,0.08); backdrop-filter: blur(12px);">
            <div class="max-w-7xl mx-auto px-6 flex gap-0">
                <button
                    v-for="tab in [['about', 'About'], ['diary', 'Diary'], ['music', 'Music']]"
                    :key="tab[0]"
                    @click="activeTab = tab[0] as 'about' | 'diary' | 'music'"
                    class="relative px-6 py-4 text-sm tracking-widest uppercase transition-all duration-200 group"
                    :style="activeTab === tab[0]
                        ? 'color: #a78bfa;'
                        : 'color: #6b7280;'"
                >
                    {{ tab[1] }}
                    <span v-if="tab[0] === 'diary'" class="ml-1.5 text-xs px-1.5 py-0.5 rounded-full font-mono"
                          :style="activeTab === 'diary' ? 'background: rgba(139,92,246,0.2); color: #a78bfa;' : 'background: rgba(255,255,255,0.05); color: #4b5563;'">{{ character.diary_entries.length }}</span>
                    <span v-if="tab[0] === 'music'" class="ml-1.5 text-xs px-1.5 py-0.5 rounded-full font-mono"
                          :style="activeTab === 'music' ? 'background: rgba(139,92,246,0.2); color: #a78bfa;' : 'background: rgba(255,255,255,0.05); color: #4b5563;'">{{ character.music_tracks.length }}</span>
                    <!-- Animated underline -->
                    <span class="absolute bottom-0 left-0 right-0 h-0.5 transition-all duration-300"
                          :style="activeTab === tab[0]
                              ? 'background: linear-gradient(90deg, #8b5cf6, #a78bfa); box-shadow: 0 0 8px rgba(139,92,246,0.6);'
                              : 'background: transparent;'"></span>
                </button>
            </div>
        </div>

        <!-- Tab content -->
        <div class="max-w-7xl mx-auto px-6 py-12">

            <!-- About tab -->
            <div v-if="activeTab === 'about'" class="animate-tab-fade">
                <div class="flex flex-col lg:flex-row gap-8 items-start">

                    <!-- Left: Subject File -->
                    <div class="lg:w-1/2 w-full">
                        <div v-if="character.description"
                             class="rounded-xl p-6 h-full"
                             style="border-left: 4px solid #8b5cf6; background: rgba(139,92,246,0.04); border-top: 1px solid rgba(139,92,246,0.15); border-right: 1px solid rgba(139,92,246,0.15); border-bottom: 1px solid rgba(139,92,246,0.15);">
                            <h2 class="text-xs tracking-widest uppercase mb-3 flex items-center gap-2" style="color: #8b5cf6;">
                                <span style="opacity: 0.6;">▌</span> Subject File
                            </h2>
                            <p class="text-base leading-relaxed whitespace-pre-wrap" style="color: #9ca3af;">{{ character.description }}</p>
                        </div>
                    </div>

                    <!-- Right: Markers, Capabilities, Vulnerabilities -->
                    <div class="lg:w-1/2 w-full space-y-8">

                        <!-- Traits / Personality Markers -->
                        <div v-if="character.traits?.length">
                            <h2 class="text-xs tracking-widest uppercase mb-4 flex items-center gap-2" style="color: #8b5cf6;">
                                <span style="opacity:0.6;">▌</span> Personality Markers
                            </h2>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-for="(trait, i) in character.traits"
                                    :key="i"
                                    class="animate-fade-in-up px-3 py-1.5 text-xs rounded border font-mono transition-all duration-200 hover:scale-105"
                                    :style="`
                                        border-color: rgba(139,92,246,0.25);
                                        color: #a78bfa;
                                        background: rgba(139,92,246,0.07);
                                        animation-delay: ${i * 0.05}s;
                                        animation-fill-mode: both;
                                    `"
                                    @mouseenter="(e: MouseEvent) => (e.currentTarget as HTMLElement).style.boxShadow = '0 0 12px rgba(139,92,246,0.4)'"
                                    @mouseleave="(e: MouseEvent) => (e.currentTarget as HTMLElement).style.boxShadow = 'none'"
                                >
                                    {{ trait }}
                                </span>
                            </div>
                        </div>

                        <!-- Abilities / Field Capabilities -->
                        <div v-if="character.abilities?.length">
                            <h2 class="text-xs tracking-widest uppercase mb-4 flex items-center gap-2" style="color: #f97316;">
                                <span style="opacity:0.6;">▌</span> Field Capabilities
                            </h2>
                            <ul class="space-y-2">
                                <li
                                    v-for="(ability, i) in character.abilities"
                                    :key="i"
                                    class="animate-fade-in-up flex items-start gap-3 text-sm"
                                    :style="`color: #9ca3af; animation-delay: ${i * 0.07}s; animation-fill-mode: both;`"
                                >
                                    <span class="text-xs mt-0.5 flex-shrink-0" style="color: #f97316;">◆</span>
                                    {{ ability }}
                                </li>
                            </ul>
                        </div>

                        <!-- Cons / Known Vulnerabilities -->
                        <div v-if="character.cons?.length">
                            <h2 class="text-xs tracking-widest uppercase mb-4 flex items-center gap-2" style="color: #ef4444;">
                                <span style="opacity:0.6;">▌</span> Known Vulnerabilities
                            </h2>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-for="(con, i) in character.cons"
                                    :key="i"
                                    class="animate-fade-in-up px-3 py-1.5 text-xs rounded border font-mono transition-all duration-200 hover:scale-105"
                                    :style="`
                                        border-color: rgba(239,68,68,0.25);
                                        color: #f87171;
                                        background: rgba(239,68,68,0.06);
                                        animation-delay: ${i * 0.05}s;
                                        animation-fill-mode: both;
                                    `"
                                    @mouseenter="(e: MouseEvent) => (e.currentTarget as HTMLElement).style.boxShadow = '0 0 12px rgba(239,68,68,0.35)'"
                                    @mouseleave="(e: MouseEvent) => (e.currentTarget as HTMLElement).style.boxShadow = 'none'"
                                >
                                    ⚠ {{ con }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Diary tab -->
            <div v-if="activeTab === 'diary'" class="animate-tab-fade">
                <div>
                    <p class="text-sm mb-8 text-center" style="color: #6b7280;">
                        Personal log entries — one mind, documenting the impossible.
                    </p>
                    <DiaryPageFlip :entries="character.diary_entries" />
                </div>
            </div>

            <!-- Music tab -->
            <div v-if="activeTab === 'music'" class="animate-tab-fade">
                <div class="max-w-2xl">
                    <p class="text-sm mb-8" style="color: #6b7280;">
                        The soundtrack to Charlotte's existence.
                    </p>
                    <div v-if="character.music_tracks.length">
                        <AudioPlayer :tracks="character.music_tracks" />
                    </div>
                    <p v-else class="text-center py-16" style="color: #4b5563;">No tracks yet.</p>
                </div>
            </div>
        </div>
</template>
