<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AudioPlayer from '@/components/AudioPlayer.vue';
import DiaryPageFlip from '@/components/DiaryPageFlip.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineOptions({ layout: PublicLayout });

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

const props = defineProps<{
    character: Character;
}>();

const tabs = computed(() => {
    const list: ['about' | 'diary' | 'music', string][] = [['about', 'About']];

    if (props.character.diary_entries.length) {
        list.push(['diary', 'Diary']);
    }

    if (props.character.music_tracks.length) {
        list.push(['music', 'Music']);
    }

    return list;
});

const activeTab = ref<'about' | 'diary' | 'music'>('about');
</script>

<template>
    <Head :title="character.name" />

    <!-- Hero -->
    <section class="relative overflow-hidden" style="background: #0a0a0f">
        <div
            class="absolute inset-0"
            style="
                background: linear-gradient(
                    160deg,
                    rgba(139, 92, 246, 0.12) 0%,
                    #0a0a0f 70%
                );
            "
        ></div>
        <div class="relative z-10 mx-auto max-w-7xl px-6 pt-6">
            <Link
                href="/characters"
                class="group inline-flex items-center gap-2 text-xs tracking-widest uppercase transition-all duration-200"
                style="color: #6b7280"
                onmouseenter="this.style.color = '#a78bfa'"
                onmouseleave="this.style.color = '#6b7280'"
            >
                <span
                    class="transition-transform duration-200 group-hover:-translate-x-1"
                    >‹</span
                >
                All Characters
            </Link>
        </div>
        <div
            class="relative z-10 mx-auto flex max-w-7xl flex-col items-center gap-8 px-6 py-8 md:flex-row md:gap-12"
        >
            <!-- Portrait -->
            <div class="group w-48 flex-shrink-0 md:w-56 lg:w-64">
                <div
                    class="relative aspect-[3/4] overflow-hidden rounded-xl shadow-2xl"
                    style="background: rgba(139, 92, 246, 0.1)"
                >
                    <img
                        v-if="character.profile_image"
                        :src="`/storage/${character.profile_image}`"
                        class="absolute inset-0 h-full w-full object-cover object-top transition-opacity duration-500"
                        :class="
                            character.action_image
                                ? 'opacity-100 group-hover:opacity-0'
                                : ''
                        "
                        :alt="character.name"
                    />
                    <img
                        v-if="character.action_image"
                        :src="`/storage/${character.action_image}`"
                        class="absolute inset-0 h-full w-full object-cover object-top opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                        :alt="`${character.name} action`"
                    />
                    <div
                        v-if="!character.profile_image"
                        class="flex h-full w-full items-center justify-center"
                        style="color: #4b5563"
                    >
                        <span class="text-4xl">?</span>
                    </div>
                </div>
            </div>
            <!-- Text -->
            <div>
                <h1
                    class="mb-3 text-5xl font-bold md:text-7xl"
                    style="color: #e8e8f0"
                >
                    {{ character.name }}
                </h1>
                <p
                    v-if="character.tagline"
                    class="max-w-2xl text-xl"
                    style="color: #6b7280"
                >
                    {{ character.tagline }}
                </p>
            </div>
        </div>
    </section>

    <!-- Tab navigation -->
    <div
        v-if="tabs.length > 1"
        class="sticky top-16 z-30 border-b"
        style="
            background: rgba(10, 10, 15, 0.95);
            border-color: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
        "
    >
        <div class="mx-auto flex max-w-7xl gap-0 overflow-x-auto px-6">
            <button
                v-for="tab in tabs"
                :key="tab[0]"
                @click="activeTab = tab[0] as 'about' | 'diary' | 'music'"
                class="group relative px-6 py-4 text-sm tracking-widest uppercase transition-all duration-200"
                :style="
                    activeTab === tab[0] ? 'color: #a78bfa;' : 'color: #6b7280;'
                "
            >
                {{ tab[1] }}
                <span
                    v-if="tab[0] === 'diary'"
                    class="ml-1.5 rounded-full px-1.5 py-0.5 font-mono text-xs"
                    :style="
                        activeTab === 'diary'
                            ? 'background: rgba(139,92,246,0.2); color: #a78bfa;'
                            : 'background: rgba(255,255,255,0.05); color: #4b5563;'
                    "
                    >{{ character.diary_entries.length }}</span
                >
                <span
                    v-if="tab[0] === 'music'"
                    class="ml-1.5 rounded-full px-1.5 py-0.5 font-mono text-xs"
                    :style="
                        activeTab === 'music'
                            ? 'background: rgba(139,92,246,0.2); color: #a78bfa;'
                            : 'background: rgba(255,255,255,0.05); color: #4b5563;'
                    "
                    >{{ character.music_tracks.length }}</span
                >
                <!-- Animated underline -->
                <span
                    class="absolute right-0 bottom-0 left-0 h-0.5 transition-all duration-300"
                    :style="
                        activeTab === tab[0]
                            ? 'background: linear-gradient(90deg, #8b5cf6, #a78bfa); box-shadow: 0 0 8px rgba(139,92,246,0.6);'
                            : 'background: transparent;'
                    "
                ></span>
            </button>
        </div>
    </div>

    <!-- Tab content -->
    <div class="mx-auto max-w-7xl px-6 py-12">
        <!-- About tab -->
        <div v-if="activeTab === 'about'" class="animate-tab-fade">
            <div class="flex flex-col items-start gap-8 lg:flex-row">
                <!-- Left: Subject File -->
                <div class="w-full lg:w-1/2">
                    <div
                        v-if="character.description"
                        class="h-full rounded-xl p-6"
                        style="
                            border-left: 4px solid #8b5cf6;
                            background: rgba(139, 92, 246, 0.04);
                            border-top: 1px solid rgba(139, 92, 246, 0.15);
                            border-right: 1px solid rgba(139, 92, 246, 0.15);
                            border-bottom: 1px solid rgba(139, 92, 246, 0.15);
                        "
                    >
                        <h2
                            class="mb-3 flex items-center gap-2 text-xs tracking-widest uppercase"
                            style="color: #8b5cf6"
                        >
                            <span style="opacity: 0.6">▌</span> Subject File
                        </h2>
                        <p
                            class="text-base leading-relaxed whitespace-pre-wrap"
                            style="color: #9ca3af"
                        >
                            {{ character.description }}
                        </p>
                    </div>
                </div>

                <!-- Right: Markers, Capabilities, Vulnerabilities -->
                <div class="w-full space-y-8 lg:w-1/2">
                    <!-- Traits / Personality Markers -->
                    <div v-if="character.traits?.length">
                        <h2
                            class="mb-4 flex items-center gap-2 text-xs tracking-widest uppercase"
                            style="color: #8b5cf6"
                        >
                            <span style="opacity: 0.6">▌</span> Personality
                            Markers
                        </h2>
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="(trait, i) in character.traits"
                                :key="i"
                                class="animate-fade-in-up rounded border px-3 py-1.5 font-mono text-xs transition-all duration-200 hover:scale-105"
                                :style="`
                                        border-color: rgba(139,92,246,0.25);
                                        color: #a78bfa;
                                        background: rgba(139,92,246,0.07);
                                        animation-delay: ${i * 0.05}s;
                                        animation-fill-mode: both;
                                    `"
                                @mouseenter="
                                    (e: MouseEvent) =>
                                        ((
                                            e.currentTarget as HTMLElement
                                        ).style.boxShadow =
                                            '0 0 12px rgba(139,92,246,0.4)')
                                "
                                @mouseleave="
                                    (e: MouseEvent) =>
                                        ((
                                            e.currentTarget as HTMLElement
                                        ).style.boxShadow = 'none')
                                "
                            >
                                {{ trait }}
                            </span>
                        </div>
                    </div>

                    <!-- Abilities / Field Capabilities -->
                    <div v-if="character.abilities?.length">
                        <h2
                            class="mb-4 flex items-center gap-2 text-xs tracking-widest uppercase"
                            style="color: #f97316"
                        >
                            <span style="opacity: 0.6">▌</span> Field
                            Capabilities
                        </h2>
                        <ul class="space-y-2">
                            <li
                                v-for="(ability, i) in character.abilities"
                                :key="i"
                                class="animate-fade-in-up flex items-start gap-3 text-sm"
                                :style="`color: #9ca3af; animation-delay: ${i * 0.07}s; animation-fill-mode: both;`"
                            >
                                <span
                                    class="mt-0.5 flex-shrink-0 text-xs"
                                    style="color: #f97316"
                                    >◆</span
                                >
                                {{ ability }}
                            </li>
                        </ul>
                    </div>

                    <!-- Cons / Known Vulnerabilities -->
                    <div v-if="character.cons?.length">
                        <h2
                            class="mb-4 flex items-center gap-2 text-xs tracking-widest uppercase"
                            style="color: #ef4444"
                        >
                            <span style="opacity: 0.6">▌</span> Known
                            Vulnerabilities
                        </h2>
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="(con, i) in character.cons"
                                :key="i"
                                class="animate-fade-in-up rounded border px-3 py-1.5 font-mono text-xs transition-all duration-200 hover:scale-105"
                                :style="`
                                        border-color: rgba(239,68,68,0.25);
                                        color: #f87171;
                                        background: rgba(239,68,68,0.06);
                                        animation-delay: ${i * 0.05}s;
                                        animation-fill-mode: both;
                                    `"
                                @mouseenter="
                                    (e: MouseEvent) =>
                                        ((
                                            e.currentTarget as HTMLElement
                                        ).style.boxShadow =
                                            '0 0 12px rgba(239,68,68,0.35)')
                                "
                                @mouseleave="
                                    (e: MouseEvent) =>
                                        ((
                                            e.currentTarget as HTMLElement
                                        ).style.boxShadow = 'none')
                                "
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
                <p class="mb-8 text-center text-sm" style="color: #6b7280">
                    Personal log entries — one mind, documenting the impossible.
                </p>
                <DiaryPageFlip :entries="character.diary_entries" />
            </div>
        </div>

        <!-- Music tab -->
        <div v-if="activeTab === 'music'" class="animate-tab-fade">
            <div class="max-w-2xl">
                <p class="mb-8 text-sm" style="color: #6b7280">
                    The soundtrack to {{ character.name }}'s existence.
                </p>
                <div v-if="character.music_tracks.length">
                    <AudioPlayer :tracks="character.music_tracks" />
                </div>
                <p v-else class="py-16 text-center" style="color: #4b5563">
                    No tracks yet.
                </p>
            </div>
        </div>
    </div>
</template>
