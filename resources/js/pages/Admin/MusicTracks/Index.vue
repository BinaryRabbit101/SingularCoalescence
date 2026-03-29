<script setup lang="ts">
import { computed, onUnmounted, type ComponentPublicInstance } from 'vue';
import Sortable from 'sortablejs';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { useAudioPlayer, type Track as AudioTrack } from '@/composables/useAudioPlayer';

interface Character {
    id: number;
    name: string;
}

interface MusicTrack {
    id: number;
    title: string;
    sort_order: number;
    file_path?: string;
    cover_image?: string;
    character: Character;
    duration_seconds?: number;
}

const props = defineProps<{
    tracks: MusicTrack[];
    characters: Character[];
}>();

const store = useAudioPlayer();

/** Play or pause a track. If a different track is active, switches to this one. */
function toggleTrack(group: { character: Character; tracks: MusicTrack[] }, trackId: number) {
    if (store.currentTrack?.id === trackId) {
        store.togglePlay();
        return;
    }
    const audioTracks: AudioTrack[] = group.tracks
        .filter(t => !!t.file_path)
        .map(t => ({
            id: t.id,
            title: t.title,
            file_path: t.file_path as string,
            cover_image: t.cover_image,
            duration_seconds: t.duration_seconds,
        }));
    const idx = audioTracks.findIndex(t => t.id === trackId);
    if (idx === -1 || !audioTracks.length) return;
    store.forceTracks(audioTracks);
    store.playIndex(idx);
}

const grouped = computed(() => {
    const map = new Map<number, { character: Character; tracks: MusicTrack[] }>();
    for (const track of props.tracks) {
        if (!map.has(track.character.id)) {
            map.set(track.character.id, { character: track.character, tracks: [] });
        }
        map.get(track.character.id)!.tracks.push(track);
    }
    return [...map.values()].sort((a, b) => a.character.name.localeCompare(b.character.name));
});

const sortables = new Map<number, Sortable>();

function registerList(el: Element | ComponentPublicInstance | null, characterId: number) {
    if (!(el instanceof HTMLElement)) {
        sortables.get(characterId)?.destroy();
        sortables.delete(characterId);
        return;
    }
    if (sortables.has(characterId)) return;
    sortables.set(characterId, new Sortable(el, {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'sortable-ghost',
        onEnd() {
            const ids = [...el.querySelectorAll('[data-id]')]
                .map(node => parseInt(node.getAttribute('data-id')!));
            router.post('/admin/music-tracks/reorder', { ids }, {
                preserveScroll: true,
                preserveState: true,
            });
        },
    }));
}

onUnmounted(() => {
    sortables.forEach(s => s.destroy());
    sortables.clear();
});

function formatTime(s?: number): string {
    if (!s) return '';
    const m = Math.floor(s / 60);
    const sec = s % 60;
    return `${m}:${String(sec).padStart(2, '0')}`;
}

function destroy(id: number, title: string) {
    if (confirm(`Delete "${title}"?`)) {
        router.delete(`/admin/music-tracks/${id}`);
    }
}
</script>

<template>
    <AdminLayout>
        <Head title="Music Tracks — Admin" />

        <div class="max-w-4xl">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold" style="color: #e8e8f0;">Music Tracks</h1>
                    <p class="text-sm mt-1" style="color: #6b7280;">{{ tracks.length }} tracks</p>
                </div>
                <Link href="/admin/music-tracks/create"
                      class="px-4 py-2 rounded text-xs tracking-widest uppercase font-medium"
                      style="background: linear-gradient(135deg, #8b5cf6, #f97316); color: white;">
                    + Upload Track
                </Link>
            </div>

            <div v-if="!tracks.length" class="text-center py-16 rounded-xl border"
                 style="border-color: rgba(255,255,255,0.08); color: #4b5563;">
                No tracks yet.
            </div>

            <div v-else class="space-y-10">
                <div v-for="group in grouped" :key="group.character.id">
                    <div class="flex items-center gap-3 mb-3">
                        <h2 class="text-xs font-semibold tracking-widest uppercase" style="color: #8b5cf6;">
                            {{ group.character.name }}
                        </h2>
                        <span class="text-xs" style="color: #4b5563;">
                            {{ group.tracks.length }} {{ group.tracks.length === 1 ? 'track' : 'tracks' }}
                        </span>
                        <div class="flex-1 h-px" style="background: rgba(139,92,246,0.15);"></div>
                    </div>

                    <div :ref="(el) => registerList(el, group.character.id)" class="space-y-2">
                        <div v-for="track in group.tracks" :key="track.id" :data-id="track.id"
                             class="flex items-center gap-4 rounded-xl border p-4"
                             style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);">
                            <div class="drag-handle flex-shrink-0 cursor-grab active:cursor-grabbing" style="color: #374151;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <circle cx="9" cy="5" r="1.5"/><circle cx="9" cy="12" r="1.5"/><circle cx="9" cy="19" r="1.5"/>
                                    <circle cx="15" cy="5" r="1.5"/><circle cx="15" cy="12" r="1.5"/><circle cx="15" cy="19" r="1.5"/>
                                </svg>
                            </div>
                            <!-- Play / pause button -->
                            <button
                                v-if="track.file_path"
                                @click="toggleTrack(group, track.id)"
                                class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 transition-all hover:scale-110 active:scale-90"
                                :style="store.playing && store.currentTrack?.id === track.id
                                    ? 'background: linear-gradient(135deg, #8b5cf6, #f97316); color: white;'
                                    : 'background: rgba(255,255,255,0.06); color: #6b7280;'"
                                :title="store.playing && store.currentTrack?.id === track.id ? 'Pause' : 'Play'"
                            >
                                <svg v-if="!(store.playing && store.currentTrack?.id === track.id)" width="10" height="10" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                                <svg v-else width="10" height="10" viewBox="0 0 24 24" fill="currentColor">
                                    <rect x="6" y="4" width="4" height="16" rx="1"/>
                                    <rect x="14" y="4" width="4" height="16" rx="1"/>
                                </svg>
                            </button>
                            <span v-else class="text-lg flex-shrink-0" style="color: #374151;">♪</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate" style="color: #e8e8f0;">{{ track.title }}</p>
                                <p v-if="track.duration_seconds" class="text-xs mt-0.5 font-mono" style="color: #6b7280;">
                                    {{ formatTime(track.duration_seconds) }}
                                </p>
                            </div>
                            <div class="flex gap-2 flex-shrink-0">
                                <Link :href="`/admin/music-tracks/${track.id}/edit`"
                                      class="px-3 py-1.5 rounded border text-xs tracking-wider transition-colors"
                                      style="border-color: rgba(139,92,246,0.3); color: #a78bfa;"
                                      onmouseenter="this.style.background='rgba(139,92,246,0.08)'"
                                      onmouseleave="this.style.background='transparent'"
                                >
                                    Edit
                                </Link>
                                <button @click="destroy(track.id, track.title)"
                                        class="px-3 py-1.5 rounded border text-xs tracking-wider transition-colors"
                                        style="border-color: rgba(239,68,68,0.2); color: #ef4444;"
                                        onmouseenter="this.style.background='rgba(239,68,68,0.08)'"
                                        onmouseleave="this.style.background='transparent'"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
