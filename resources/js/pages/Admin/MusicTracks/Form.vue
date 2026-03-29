<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

interface Character {
    id: number;
    name: string;
}

interface MusicTrack {
    id: number;
    character_id: number;
    title: string;
    description?: string;
    file_path: string;
    cover_image?: string;
    duration_seconds?: number;
    sort_order: number;
}

const props = defineProps<{
    track: MusicTrack | null;
    characters: Character[];
}>();

const isEdit = computed(() => !!props.track);

const form = useForm({
    character_id: props.track?.character_id ?? '',
    title: props.track?.title ?? '',
    description: props.track?.description ?? '',
    duration_seconds: props.track?.duration_seconds ?? '',
    sort_order: props.track?.sort_order ?? 0,
    audio_file: null as File | null,
    cover_image: null as File | null,
});

const coverPreview = ref<string | undefined>(
    props.track?.cover_image ? `/storage/${props.track.cover_image}` : undefined
);

function onCover(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        form.cover_image = file;
        coverPreview.value = URL.createObjectURL(file);
    }
}

function onAudio(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) form.audio_file = file;
}

function submit() {
    if (isEdit.value) {
        form.transform((data) => ({ ...data, _method: 'PUT' }))
            .post(`/admin/music-tracks/${props.track!.id}`, { forceFormData: true });
    } else {
        form.post('/admin/music-tracks', { forceFormData: true });
    }
}
</script>

<template>
    <AdminLayout>
        <Head :title="isEdit ? 'Edit Track' : 'Upload Track'" />

        <div class="max-w-2xl">
            <div class="flex items-center gap-4 mb-8">
                <Link href="/admin/music-tracks" class="text-sm transition-colors" style="color: #6b7280;"
                      onmouseenter="this.style.color='#9ca3af'" onmouseleave="this.style.color='#6b7280'">
                    ← Music Tracks
                </Link>
                <h1 class="text-2xl font-bold" style="color: #e8e8f0;">{{ isEdit ? 'Edit Track' : 'Upload Track' }}</h1>
            </div>

            <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                <div class="rounded-xl border p-6 space-y-4" style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);">
                    <div>
                        <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Character *</label>
                        <select v-model="form.character_id" required
                                class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                                style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;">
                            <option value="" disabled>Select character...</option>
                            <option v-for="char in characters" :key="char.id" :value="char.id">{{ char.name }}</option>
                        </select>
                        <p v-if="form.errors.character_id" class="text-xs mt-1" style="color: #ef4444;">{{ form.errors.character_id }}</p>
                    </div>

                    <div>
                        <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Title *</label>
                        <input v-model="form.title" type="text" required
                               class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                               style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;" />
                        <p v-if="form.errors.title" class="text-xs mt-1" style="color: #ef4444;">{{ form.errors.title }}</p>
                    </div>

                    <div>
                        <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Description</label>
                        <textarea v-model="form.description" rows="3"
                                  class="w-full rounded border px-3 py-2 text-sm focus:outline-none resize-none"
                                  style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">
                            Audio File {{ isEdit ? '(leave empty to keep existing)' : '*' }}
                        </label>
                        <div v-if="isEdit && track" class="text-xs mb-2 font-mono p-2 rounded border"
                             style="color: #6b7280; border-color: rgba(255,255,255,0.06); background: rgba(255,255,255,0.02);">
                            Current: {{ track.file_path.split('/').pop() }}
                        </div>
                        <input type="file" accept="audio/mp3,audio/wav,audio/ogg,audio/mp4,.mp3,.wav,.ogg,.m4a"
                               @change="onAudio" :required="!isEdit"
                               class="block w-full text-xs" style="color: #6b7280;" />
                        <p v-if="form.errors.audio_file" class="text-xs mt-1" style="color: #ef4444;">{{ form.errors.audio_file }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Cover Image</label>
                            <div class="w-20 h-20 rounded overflow-hidden mb-2 border"
                                 style="background: rgba(139,92,246,0.05); border-color: rgba(139,92,246,0.2);">
                                <img v-if="coverPreview" :src="coverPreview" class="w-full h-full object-cover" />
                                <div v-else class="w-full h-full flex items-center justify-center text-sm" style="color: #4b5563;">♪</div>
                            </div>
                            <input type="file" accept="image/*" @change="onCover"
                                   class="block w-full text-xs" style="color: #6b7280;" />
                        </div>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Duration (seconds)</label>
                                <input v-model="form.duration_seconds" type="number" min="1"
                                       class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                                       style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;" />
                            </div>
                            <div>
                                <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Sort Order</label>
                                <input v-model="form.sort_order" type="number" min="0"
                                       class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                                       style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" :disabled="form.processing"
                            class="px-6 py-2.5 rounded text-sm tracking-widest uppercase font-medium transition-opacity"
                            style="background: linear-gradient(135deg, #8b5cf6, #f97316); color: white;"
                            :class="form.processing ? 'opacity-50' : ''">
                        {{ isEdit ? 'Update Track' : 'Upload Track' }}
                    </button>
                    <Link href="/admin/music-tracks" class="text-sm transition-colors" style="color: #6b7280;"
                          onmouseenter="this.style.color='#9ca3af'" onmouseleave="this.style.color='#6b7280'">
                        Cancel
                    </Link>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
