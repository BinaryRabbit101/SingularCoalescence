<script setup lang="ts">
import { ref } from 'vue';
import { useAudioPlayer } from '@/composables/useAudioPlayer';

const store = useAudioPlayer();

// isDragging is local UI state: true while the user drags the thumb.
// It controls whether to display dragTime or store.currentTime in the progress bar,
// preventing the thumb from snapping back to the playback position during a drag.
// The actual seek-blocking (preventing timeupdate during async seek) is handled
// by _isSeeking inside the composable, wired to the audio 'seeked' event.
const isDragging = ref(false);
const dragTime = ref(0);

function coverUrl(path?: string): string | undefined {
    return path ? '/storage/' + path : undefined;
}

function formatTime(seconds: number): string {
    if (!seconds || isNaN(seconds)) {
        return '0:00';
    }

    const m = Math.floor(seconds / 60);
    const s = Math.floor(seconds % 60);

    return `${m}:${s.toString().padStart(2, '0')}`;
}

function onSeekInput(e: Event) {
    isDragging.value = true;
    dragTime.value = parseFloat((e.target as HTMLInputElement).value);
}

function commitSeek(e: Event) {
    const val = parseFloat((e.target as HTMLInputElement).value);
    dragTime.value = val;
    store.seekTo(val);
    isDragging.value = false;
}

function onVolumeChange(e: Event) {
    store.setVolume(parseFloat((e.target as HTMLInputElement).value));
}
</script>

<template>
    <div
        v-show="store.started"
        class="fixed right-0 bottom-0 left-0 z-50 border-t"
        style="
            background: rgba(10, 10, 15, 0.97);
            border-color: rgba(139, 92, 246, 0.2);
            backdrop-filter: blur(16px);
        "
    >
        <div class="mx-auto flex h-20 max-w-7xl items-center gap-4 px-4">
            <!-- Cover thumbnail -->
            <div
                class="h-10 w-10 flex-shrink-0 overflow-hidden rounded"
                style="
                    background: linear-gradient(
                        135deg,
                        rgba(139, 92, 246, 0.3),
                        rgba(249, 115, 22, 0.3)
                    );
                "
            >
                <img
                    v-if="store.currentTrack?.cover_image"
                    :src="coverUrl(store.currentTrack.cover_image)"
                    class="h-full w-full object-cover"
                    :alt="store.currentTrack.title"
                />
            </div>

            <!-- Track info -->
            <div class="w-40 min-w-0 flex-shrink-0">
                <p class="truncate text-xs font-medium" style="color: #e8e8f0">
                    {{ store.currentTrack?.title ?? '—' }}
                </p>
                <p class="truncate text-xs" style="color: #6b7280">
                    {{ store.currentTrack?.description ?? '' }}
                </p>
            </div>

            <!-- Playback controls -->
            <div class="flex flex-shrink-0 items-center gap-3">
                <button
                    @click="store.prev()"
                    :disabled="store.currentIndex === 0"
                    class="flex h-7 w-7 items-center justify-center transition-all"
                    style="color: #6b7280"
                    :class="
                        store.currentIndex === 0
                            ? 'cursor-not-allowed opacity-30'
                            : 'hover:text-white'
                    "
                >
                    <svg
                        width="14"
                        height="14"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                    >
                        <path d="M6 6h2v12H6zm3.5 6 8.5 6V6z" />
                    </svg>
                </button>

                <button
                    @click="store.togglePlay()"
                    class="flex h-9 w-9 items-center justify-center rounded-full transition-all hover:scale-105 active:scale-95"
                    style="
                        background: linear-gradient(135deg, #8b5cf6, #f97316);
                        color: white;
                    "
                >
                    <svg
                        v-if="!store.playing"
                        width="14"
                        height="14"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                    >
                        <path d="M8 5v14l11-7z" />
                    </svg>
                    <svg
                        v-else
                        width="14"
                        height="14"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                    >
                        <rect x="6" y="4" width="4" height="16" rx="1" />
                        <rect x="14" y="4" width="4" height="16" rx="1" />
                    </svg>
                </button>

                <button
                    @click="store.next()"
                    :disabled="store.currentIndex >= store.tracks.length - 1"
                    class="flex h-7 w-7 items-center justify-center transition-all"
                    style="color: #6b7280"
                    :class="
                        store.currentIndex >= store.tracks.length - 1
                            ? 'cursor-not-allowed opacity-30'
                            : 'hover:text-white'
                    "
                >
                    <svg
                        width="14"
                        height="14"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                    >
                        <path d="M6 18l8.5-6L6 6v12zm10-12v12h2V6h-2z" />
                    </svg>
                </button>
            </div>

            <!-- Progress bar -->
            <div class="flex min-w-0 flex-1 items-center gap-2">
                <span
                    class="flex-shrink-0 text-xs tabular-nums"
                    style="color: #6b7280; min-width: 2.5rem"
                >
                    {{ formatTime(isDragging ? dragTime : store.currentTime) }}
                </span>
                <input
                    type="range"
                    :min="0"
                    :max="store.duration || 100"
                    step="any"
                    :value="isDragging ? dragTime : store.currentTime"
                    @input="onSeekInput"
                    @mouseup="commitSeek"
                    @touchend="commitSeek"
                    @change="commitSeek"
                    class="h-1 flex-1 cursor-pointer appearance-none rounded-full"
                    style="accent-color: #8b5cf6"
                />
                <span
                    class="flex-shrink-0 text-xs tabular-nums"
                    style="color: #6b7280; min-width: 2.5rem; text-align: right"
                >
                    {{ formatTime(store.duration) }}
                </span>
            </div>

            <!-- Volume -->
            <div class="flex flex-shrink-0 items-center gap-2">
                <span style="color: #6b7280; line-height: 0">
                    <svg
                        width="12"
                        height="12"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                    >
                        <path
                            d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"
                        />
                    </svg>
                </span>
                <input
                    type="range"
                    min="0"
                    max="1"
                    step="0.05"
                    :value="store.volume"
                    @input="onVolumeChange"
                    class="h-1 w-16 cursor-pointer appearance-none rounded-full"
                    style="accent-color: #8b5cf6"
                />
            </div>

            <!-- Close button -->
            <button
                @click="store.close()"
                class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full transition-all hover:bg-white/10 active:scale-90"
                style="color: #6b7280"
                title="Close player"
            >
                <svg
                    width="14"
                    height="14"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                >
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>
    </div>
</template>
