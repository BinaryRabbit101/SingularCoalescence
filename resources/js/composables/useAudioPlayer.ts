import { ref, computed, reactive } from 'vue';

export interface Track {
    id: number;
    title: string;
    description?: string;
    file_path: string;
    cover_image?: string;
    duration_seconds?: number;
}

// ─── Singleton persistent audio element ──────────────────────────────────────
// Created once at module init and never destroyed — survives layout changes,
// Inertia navigations, and component unmounts. The audio continues playing
// regardless of which page or layout is active.
const _audio: HTMLAudioElement | null = typeof window !== 'undefined' ? new Audio() : null;

// ─── Module-level reactive state ─────────────────────────────────────────────
const tracks = ref<Track[]>([]);
const currentIndex = ref(0);
const playing = ref(false);
const started = ref(false);
const currentTime = ref(0);
const duration = ref(0);
const volume = ref(0.3);
// isSeeking is true from seekTo() until the 'seeked' event fires,
// which blocks timeupdate from overwriting currentTime during async seeking.
let _isSeeking = false;

// ─── Internal helpers ─────────────────────────────────────────────────────────
function _load(index: number) {
    const track = tracks.value[index];
    if (!track || !_audio) return;
    currentIndex.value = index;
    currentTime.value = 0;
    _audio.src = '/stream/' + track.file_path;
    _audio.volume = volume.value;
    _audio.load();
}

function _playIndex(index: number) {
    if (index < 0 || index >= tracks.value.length || !_audio) return;
    started.value = true;
    _load(index);
    // Small delay mirrors the original 50ms timeout to allow load() to settle
    // before play(), improving compatibility across browsers (especially Safari).
    setTimeout(() => _audio?.play().catch(() => {}), 50);
}

// ─── Wire audio events once at module init ────────────────────────────────────
if (_audio) {
    _audio.volume = volume.value;
    _audio.preload = 'auto';

    _audio.addEventListener('timeupdate', () => {
        if (!_isSeeking && _audio) currentTime.value = _audio.currentTime;
    });
    _audio.addEventListener('durationchange', () => {
        if (_audio) duration.value = isFinite(_audio.duration) ? _audio.duration : 0;
    });
    _audio.addEventListener('play', () => { playing.value = true; });
    _audio.addEventListener('pause', () => { playing.value = false; });
    _audio.addEventListener('ended', () => {
        // Guard: spurious 'ended' events fire when .load() resets the element.
        if (!_audio || !(_audio.duration > 0)) return;
        playing.value = false;
        if (currentIndex.value < tracks.value.length - 1) {
            _playIndex(currentIndex.value + 1);
        }
    });
    _audio.addEventListener('seeked', () => {
        // Seek completed — update currentTime with actual position and unblock timeupdate.
        if (_audio) currentTime.value = _audio.currentTime;
        _isSeeking = false;
    });
}

// ─── Composable ───────────────────────────────────────────────────────────────
export function useAudioPlayer() {
    const currentTrack = computed(() => tracks.value[currentIndex.value]);

    /** Safe to call on page mount — no-op if something is currently playing. */
    function setTracks(newTracks: Track[]) {
        if (playing.value) return;
        tracks.value = newTracks;
        currentIndex.value = 0;
        currentTime.value = 0;
        duration.value = 0;
        // Preload first track metadata so duration is visible before user presses play.
        if (_audio && newTracks[0] && !started.value) {
            _audio.src = '/stream/' + newTracks[0].file_path;
            _audio.load();
        }
    }

    /** Always switches tracks — for user-initiated selection (e.g. clicking a different character). */
    function forceTracks(newTracks: Track[]) {
        tracks.value = newTracks;
        currentIndex.value = 0;
        currentTime.value = 0;
        duration.value = 0;
    }

    function playIndex(index: number) {
        _playIndex(index);
    }

    function togglePlay() {
        if (!_audio || !started.value) return;
        if (playing.value) {
            _audio.pause();
        } else {
            _audio.play().catch(() => {});
        }
    }

    function next() {
        if (currentIndex.value < tracks.value.length - 1) {
            _playIndex(currentIndex.value + 1);
        } else {
            playing.value = false;
        }
    }

    function prev() {
        if (currentIndex.value > 0) {
            _playIndex(currentIndex.value - 1);
        }
    }

    function seekTo(time: number) {
        if (!_audio || isNaN(time)) return;
        _isSeeking = true;
        currentTime.value = time; // Optimistic update for smooth display
        _audio.currentTime = time;
    }

    function setVolume(vol: number) {
        volume.value = vol;
        if (_audio) _audio.volume = vol;
    }

    /** Called by the X button — stops audio and hides the bar. */
    function close() {
        if (_audio) {
            _audio.pause();
            _audio.src = '';
        }
        playing.value = false;
        started.value = false;
        _isSeeking = false;
        tracks.value = [];
        currentIndex.value = 0;
        currentTime.value = 0;
        duration.value = 0;
    }

    return reactive({
        tracks,
        currentIndex,
        playing,
        started,
        currentTime,
        duration,
        volume,
        currentTrack,
        setTracks,
        forceTracks,
        playIndex,
        togglePlay,
        next,
        prev,
        seekTo,
        setVolume,
        close,
    });
}
