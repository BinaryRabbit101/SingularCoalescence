<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useAudioPlayer } from '@/composables/useAudioPlayer';

interface Track {
    id: number;
    title: string;
    description?: string;
    file_path: string;
    cover_image?: string;
    duration_seconds?: number;
}

const props = defineProps<{
    tracks: Track[];
}>();

const store = useAudioPlayer();

// Local seek state — prevents timeupdate fighting the drag thumb
const isSeeking = ref(false);
const dragTime = ref(0);

// True when the store is playing tracks from this component's character
const isSameTrackSet = computed(() => {
    if (store.tracks.length !== props.tracks.length) return false;
    return props.tracks.every((t, i) => store.tracks[i]?.id === t.id);
});

// The highlighted index in the local list (-1 when a different character's music is playing)
const activeLocalIndex = computed(() => (isSameTrackSet.value ? store.currentIndex : -1));

function coverUrl(path?: string): string | undefined {
    return path ? '/storage/' + path : undefined;
}

function formatTime(seconds: number): string {
    if (!seconds || isNaN(seconds)) return '0:00';
    const m = Math.floor(seconds / 60);
    const s = Math.floor(seconds % 60);
    return `${m}:${s.toString().padStart(2, '0')}`;
}

function playTrack(index: number) {
    if (!isSameTrackSet.value) store.forceTracks(props.tracks);
    store.playIndex(index);
}

function togglePlay() {
    if (!isSameTrackSet.value) {
        store.forceTracks(props.tracks);
        store.playIndex(0);
        return;
    }
    store.togglePlay();
}

function prev() {
    if (!isSameTrackSet.value) { store.forceTracks(props.tracks); store.playIndex(0); return; }
    store.prev();
}

function next() {
    if (!isSameTrackSet.value) { store.forceTracks(props.tracks); store.playIndex(0); return; }
    store.next();
}

// --- Progress bar seek fix ---

function onSeekInput(e: Event) {
    isSeeking.value = true;
    dragTime.value = parseFloat((e.target as HTMLInputElement).value);
}

function commitSeek(e: Event) {
    const val = parseFloat((e.target as HTMLInputElement).value);
    if (!isNaN(val)) store.seekTo(val);
    isSeeking.value = false;
}

function onVolumeChange(e: Event) {
    store.setVolume(parseFloat((e.target as HTMLInputElement).value));
}

onMounted(() => {
    // Load this character's tracks if nothing is currently playing
    store.setTracks(props.tracks);
});
</script>

<template>
    <div class="rounded-xl overflow-hidden border" style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08);">
        <!-- Now Playing -->
        <div class="p-5 border-b" style="border-color: rgba(255,255,255,0.08); background: rgba(139,92,246,0.06);">
            <div class="flex flex-col items-center gap-4">
                <!-- Vinyl disc -->
                <div class="relative flex-shrink-0" style="width: 120px; height: 120px;">
                    <!-- Pulsing glow ring when playing this character's tracks -->
                    <div
                        v-show="store.playing && isSameTrackSet"
                        class="absolute rounded-full animate-vinyl-glow pointer-events-none"
                        style="inset: -18px; background: radial-gradient(circle at center, rgba(139,92,246,0.55) 0%, rgba(249,115,22,0.2) 45%, transparent 70%); z-index: 0;"
                    ></div>
                    <!-- Grooved disc body -->
                    <div
                        class="absolute inset-0 rounded-full"
                        style="
                            background: repeating-radial-gradient(
                                circle at center,
                                #1a1625 0px, #1a1625 6px,
                                #130f1e 7px, #130f1e 12px,
                                #1a1625 13px, #1a1625 18px,
                                #130f1e 19px, #130f1e 24px,
                                #1a1625 25px, #1a1625 30px,
                                #130f1e 31px, #130f1e 36px,
                                #1a1625 37px, #1a1625 42px,
                                #130f1e 43px, #130f1e 48px,
                                #1a1625 49px, #1a1625 54px,
                                #130f1e 55px, #130f1e 60px
                            );
                            box-shadow: 0 0 0 1px rgba(139,92,246,0.2), 0 8px 32px rgba(0,0,0,0.6);
                        "
                    ></div>
                    <!-- Conic light-sweep overlay — spins with disc -->
                    <div
                        :class="(store.playing && isSameTrackSet) ? 'animate-spin-vinyl' : 'animate-spin-vinyl-paused'"
                        class="absolute inset-0 rounded-full pointer-events-none"
                        style="background: conic-gradient(from 0deg, transparent 0%, rgba(255,255,255,0.0) 5%, rgba(255,255,255,0.09) 12%, rgba(255,255,255,0.15) 18%, rgba(255,255,255,0.05) 25%, transparent 30%);"
                    ></div>
                    <!-- Center label -->
                    <div class="absolute"
                         style="width: 44px; height: 44px; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 5;">
                        <div
                            :class="(store.playing && isSameTrackSet) ? 'animate-spin-vinyl-inner' : 'animate-spin-vinyl-inner-paused'"
                            class="w-full h-full rounded-full overflow-hidden"
                            style="background: conic-gradient(#8b5cf6 0deg 180deg, #f97316 180deg 360deg); box-shadow: 0 0 12px rgba(139,92,246,0.5);"
                        >
                            <img v-if="isSameTrackSet && coverUrl(store.currentTrack?.cover_image)"
                                 :src="coverUrl(store.currentTrack?.cover_image)"
                                 class="w-full h-full object-cover"
                                 :alt="store.currentTrack?.title" />
                        </div>
                    </div>
                    <!-- Center hole -->
                    <div class="absolute rounded-full"
                         style="width: 8px; height: 8px; top: 50%; left: 50%; transform: translate(-50%,-50%); background: #0a0a0f; z-index: 10;"></div>
                    <!-- Shine -->
                    <div class="absolute inset-0 rounded-full pointer-events-none"
                         style="background: linear-gradient(135deg, rgba(255,255,255,0.08) 0%, transparent 55%);"></div>
                </div>

                <!-- Track info -->
                <div class="text-center">
                    <p class="font-medium text-sm" style="color: #e8e8f0;">
                        {{ isSameTrackSet ? store.currentTrack?.title : (tracks[0]?.title ?? 'Select a track') }}
                    </p>
                    <p v-if="isSameTrackSet && store.currentTrack?.description" class="text-xs mt-0.5" style="color: #6b7280;">
                        {{ store.currentTrack.description }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <div class="px-4 py-3">
            <!-- Progress -->
            <div class="flex items-center gap-2 mb-3">
                <span class="text-xs tabular-nums" style="color: #6b7280; min-width: 2.5rem;">
                    {{ formatTime(isSeeking ? dragTime : (isSameTrackSet ? store.currentTime : 0)) }}
                </span>
                <input
                    type="range"
                    :min="0"
                    :max="(isSameTrackSet ? store.duration : 0) || 100"
                    step="any"
                    :value="isSeeking ? dragTime : (isSameTrackSet ? store.currentTime : 0)"
                    @input="onSeekInput"
                    @mouseup="commitSeek"
                    @touchend="commitSeek"
                    @change="commitSeek"
                    class="flex-1 h-1 rounded-full appearance-none cursor-pointer"
                    style="accent-color: #8b5cf6;"
                />
                <span class="text-xs tabular-nums" style="color: #6b7280; min-width: 2.5rem; text-align: right;">
                    {{ formatTime(isSameTrackSet ? store.duration : 0) }}
                </span>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button @click="prev"
                            :disabled="isSameTrackSet && store.currentIndex === 0"
                            class="w-8 h-8 rounded-full flex items-center justify-center transition-all"
                            style="color: #6b7280;"
                            :class="(isSameTrackSet && store.currentIndex === 0) ? 'opacity-30 cursor-not-allowed' : 'hover:text-white hover:scale-110 active:scale-95'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6 6h2v12H6zm3.5 6 8.5 6V6z"/>
                        </svg>
                    </button>
                    <button @click="togglePlay"
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all hover:scale-105 active:scale-95"
                            style="background: linear-gradient(135deg, #8b5cf6, #f97316); color: white; box-shadow: 0 0 20px rgba(139,92,246,0.4);">
                        <svg v-if="!(store.playing && isSameTrackSet)" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                        <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <rect x="6" y="4" width="4" height="16" rx="1"/>
                            <rect x="14" y="4" width="4" height="16" rx="1"/>
                        </svg>
                    </button>
                    <button @click="next"
                            :disabled="isSameTrackSet && store.currentIndex === tracks.length - 1"
                            class="w-8 h-8 rounded-full flex items-center justify-center transition-all"
                            style="color: #6b7280;"
                            :class="(isSameTrackSet && store.currentIndex === tracks.length - 1) ? 'opacity-30 cursor-not-allowed' : 'hover:text-white hover:scale-110 active:scale-95'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6 18l8.5-6L6 6v12zm10-12v12h2V6h-2z"/>
                        </svg>
                    </button>
                </div>

                <!-- Volume -->
                <div class="flex items-center gap-2">
                    <span style="color: #6b7280; line-height: 0;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/>
                        </svg>
                    </span>
                    <input type="range" min="0" max="1" step="0.05"
                           :value="store.volume"
                           @input="onVolumeChange"
                           class="w-20 h-1 rounded-full appearance-none cursor-pointer"
                           style="accent-color: #8b5cf6;" />
                </div>
            </div>
        </div>

        <!-- Track List -->
        <div class="border-t" style="border-color: rgba(255,255,255,0.06);">
            <button
                v-for="(track, i) in tracks"
                :key="track.id"
                @click="playTrack(i)"
                class="w-full flex items-center gap-3 px-4 py-3 text-left transition-all duration-200 border-b last:border-b-0 border-l-2"
                :style="i === activeLocalIndex
                    ? 'background: rgba(139,92,246,0.08); border-color: rgba(255,255,255,0.04); border-left-color: #8b5cf6; box-shadow: inset 3px 0 12px rgba(139,92,246,0.15);'
                    : 'border-color: rgba(255,255,255,0.04); border-left-color: transparent;'"
            >
                <span class="flex items-end justify-center gap-px flex-shrink-0" style="width: 20px; height: 16px;">
                    <template v-if="i === activeLocalIndex && store.playing">
                        <span class="w-1 rounded-sm animate-eq-bar-1" style="background: #8b5cf6;"></span>
                        <span class="w-1 rounded-sm animate-eq-bar-2" style="background: #8b5cf6;"></span>
                        <span class="w-1 rounded-sm animate-eq-bar-3" style="background: #8b5cf6;"></span>
                    </template>
                    <span v-else class="text-xs self-center" :style="i === activeLocalIndex ? 'color: #8b5cf6;' : 'color: #4b5563;'">
                        {{ i + 1 }}
                    </span>
                </span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm truncate" :style="i === activeLocalIndex ? 'color: #a78bfa;' : 'color: #9ca3af;'">
                        {{ track.title }}
                    </p>
                </div>
                <span v-if="track.duration_seconds" class="text-xs flex-shrink-0" style="color: #4b5563;">
                    {{ formatTime(track.duration_seconds) }}
                </span>
            </button>
        </div>
    </div>
</template>
