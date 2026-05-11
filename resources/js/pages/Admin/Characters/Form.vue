<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

interface Character {
    id: number;
    name: string;
    tagline?: string;
    description?: string;
    profile_image?: string;
    action_image?: string;
    action_image_prompt?: string;
    traits?: string[];
    abilities?: string[];
    cons?: string[];
    sort_order: number;
    published: boolean;
}

const props = defineProps<{
    character: Character | null;
}>();

const isEdit = computed(() => !!props.character);

const generatingProfile = ref(false);
const generateError = ref('');
const generatingActionShot = ref(false);
const actionShotError = ref('');

async function generateProfile() {
    if (!props.character) return;
    generatingProfile.value = true;
    generateError.value = '';
    try {
        const res = await fetch(`/admin/characters/${props.character.id}/generate-profile`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content ?? '',
                'Accept': 'application/json',
            },
        });
        const json = await res.json();
        if (json.description) {
            form.description = json.description;
        } else {
            generateError.value = json.error ?? 'No response from AI.';
        }
    } catch {
        generateError.value = 'Request failed.';
    } finally {
        generatingProfile.value = false;
    }
}

async function generateActionShot() {
    if (!props.character) return;
    generatingActionShot.value = true;
    actionShotError.value = '';
    try {
        const res = await fetch(`/admin/characters/${props.character.id}/generate-action-image`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content ?? '',
                'Accept': 'application/json',
            },
        });
        const json = await res.json();
        if (json.path) {
            actionImagePreview.value = `/storage/${json.path}`;
        } else {
            actionShotError.value = json.error ?? 'No response from AI.';
        }
    } catch {
        actionShotError.value = 'Request failed.';
    } finally {
        generatingActionShot.value = false;
    }
}

const form = useForm({
    name: props.character?.name ?? '',
    tagline: props.character?.tagline ?? '',
    description: props.character?.description ?? '',
    traits: (props.character?.traits ?? []).join('\n'),
    abilities: (props.character?.abilities ?? []).join('\n'),
    cons: (props.character?.cons ?? []).join('\n'),
    sort_order: props.character?.sort_order ?? 0,
    published: props.character?.published ?? true,
    action_image_prompt: props.character?.action_image_prompt ?? '',
    profile_image: null as File | null,
    action_image: null as File | null,
});

function submit() {
    const data = {
        ...form,
        traits: form.traits ? form.traits.split('\n').map(s => s.trim()).filter(Boolean) : [],
        abilities: form.abilities ? form.abilities.split('\n').map(s => s.trim()).filter(Boolean) : [],
        cons: form.cons ? form.cons.split('\n').map(s => s.trim()).filter(Boolean) : [],
    };

    if (isEdit.value) {
        form.transform(() => ({
            ...data,
            _method: 'PUT',
        })).post(`/admin/characters/${props.character!.id}`, {
            forceFormData: true,
        });
    } else {
        form.transform(() => data).post('/admin/characters', {
            forceFormData: true,
        });
    }
}

const imagePreview = ref<string | undefined>(
    props.character?.profile_image ? `/storage/${props.character.profile_image}` : undefined
);

function onImage(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        form.profile_image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
}

const actionImagePreview = ref<string | undefined>(
    props.character?.action_image ? `/storage/${props.character.action_image}` : undefined
);

function onActionImage(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        form.action_image = file;
        actionImagePreview.value = URL.createObjectURL(file);
    }
}
</script>

<template>
    <AdminLayout>
        <Head :title="isEdit ? 'Edit Character' : 'New Character'" />

        <div class="max-w-3xl">
            <div class="flex items-center gap-4 mb-8">
                <Link href="/admin/characters" class="text-sm transition-colors" style="color: #6b7280;"
                      onmouseenter="this.style.color='#9ca3af'" onmouseleave="this.style.color='#6b7280'">
                    ← Characters
                </Link>
                <h1 class="text-2xl font-bold" style="color: #e8e8f0;">{{ isEdit ? 'Edit Character' : 'New Character' }}</h1>
            </div>

            <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                <!-- Basic info -->
                <div class="rounded-xl border p-6 space-y-4" style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);">
                    <h2 class="text-xs tracking-widest uppercase" style="color: #8b5cf6;">Basic Info</h2>

                    <div>
                        <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Name *</label>
                        <input v-model="form.name" type="text" required
                               class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                               style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;" />
                        <p v-if="form.errors.name" class="text-xs mt-1" style="color: #ef4444;">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Tagline</label>
                        <input v-model="form.tagline" type="text"
                               class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                               style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs tracking-wide" style="color: #9ca3af;">Description</label>
                            <button v-if="isEdit" type="button" @click="generateProfile"
                                    :disabled="generatingProfile"
                                    class="text-xs px-3 py-1 rounded border transition-colors"
                                    style="border-color: rgba(139,92,246,0.4); color: #a78bfa;"
                                    onmouseenter="this.style.background='rgba(139,92,246,0.1)'"
                                    onmouseleave="this.style.background='transparent'">
                                {{ generatingProfile ? 'Generating…' : '✦ Generate Profile' }}
                            </button>
                        </div>
                        <p v-if="generateError" class="text-xs mb-1" style="color: #ef4444;">{{ generateError }}</p>
                        <textarea v-model="form.description" rows="5"
                                  class="w-full rounded border px-3 py-2 text-sm focus:outline-none resize-none"
                                  style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;"></textarea>
                    </div>
                </div>

                <!-- Profile image -->
                <div class="rounded-xl border p-6" style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);">
                    <h2 class="text-xs tracking-widest uppercase mb-4" style="color: #8b5cf6;">Portrait</h2>
                    <div class="max-w-xs">
                        <div class="aspect-[2/3] rounded-lg overflow-hidden mb-2 border"
                             style="background: rgba(139,92,246,0.05); border-color: rgba(139,92,246,0.2);">
                            <img v-if="imagePreview" :src="imagePreview" class="w-full h-full object-cover" />
                            <div v-else class="w-full h-full flex items-center justify-center text-sm" style="color: #4b5563;">No image</div>
                        </div>
                        <input type="file" accept="image/*" @change="onImage"
                               class="block w-full text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium"
                               style="color: #6b7280;" />
                    </div>
                </div>

                <!-- Action Shot -->
                <div class="rounded-xl border p-6" style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xs tracking-widest uppercase" style="color: #8b5cf6;">Action Shot</h2>
                        <button v-if="isEdit" type="button" @click="generateActionShot"
                                :disabled="generatingActionShot"
                                class="text-xs px-3 py-1 rounded border transition-colors"
                                style="border-color: rgba(249,115,22,0.4); color: #fb923c;"
                                onmouseenter="this.style.background='rgba(249,115,22,0.1)'"
                                onmouseleave="this.style.background='transparent'">
                            {{ generatingActionShot ? 'Generating…' : '✦ Generate Action Shot' }}
                        </button>
                    </div>
                    <p v-if="actionShotError" class="text-xs mb-3" style="color: #ef4444;">{{ actionShotError }}</p>
                    <div class="mb-4">
                        <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Image Prompt <span style="color: #6b7280;">(saved — used for generation)</span></label>
                        <textarea v-model="form.action_image_prompt" rows="5"
                                  placeholder="Describe the action shot in detail — style, pose, clothing, colors, environment…"
                                  class="w-full rounded border px-3 py-2 text-xs focus:outline-none resize-none font-mono"
                                  style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;"></textarea>
                    </div>
                    <div class="max-w-xs">
                        <div class="aspect-square rounded-lg overflow-hidden mb-2 border"
                             style="background: rgba(249,115,22,0.05); border-color: rgba(249,115,22,0.2);">
                            <img v-if="actionImagePreview" :src="actionImagePreview" class="w-full h-full object-cover" />
                            <div v-else class="w-full h-full flex items-center justify-center text-sm" style="color: #4b5563;">No image</div>
                        </div>
                        <input type="file" accept="image/*" @change="onActionImage"
                               class="block w-full text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium"
                               style="color: #6b7280;" />
                    </div>
                </div>

                <!-- Traits & Abilities -->
                <div class="rounded-xl border p-6" style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);">
                    <h2 class="text-xs tracking-widest uppercase mb-4" style="color: #8b5cf6;">Traits &amp; Abilities</h2>
                    <p class="text-xs mb-4" style="color: #6b7280;">One item per line.</p>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs mb-1.5" style="color: #9ca3af;">Traits (Pros)</label>
                            <textarea v-model="form.traits" rows="6" placeholder="Tactical genius&#10;Dual consciousness&#10;..."
                                      class="w-full rounded border px-3 py-2 text-xs focus:outline-none resize-none font-mono"
                                      style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs mb-1.5" style="color: #9ca3af;">Abilities</label>
                            <textarea v-model="form.abilities" rows="6" placeholder="Pocket dimension access&#10;Teleportation discs&#10;..."
                                      class="w-full rounded border px-3 py-2 text-xs focus:outline-none resize-none font-mono"
                                      style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;"></textarea>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs mb-1.5" style="color: #9ca3af;">Cons / Weaknesses</label>
                        <textarea v-model="form.cons" rows="4" placeholder="Fear of separation&#10;Loneliness&#10;..."
                                  class="w-full rounded border px-3 py-2 text-xs focus:outline-none resize-none font-mono"
                                  style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;"></textarea>
                    </div>
                </div>

                <!-- Settings -->
                <div class="rounded-xl border p-6" style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);">
                    <h2 class="text-xs tracking-widest uppercase mb-4" style="color: #8b5cf6;">Settings</h2>
                    <div class="flex items-center gap-8">
                        <div>
                            <label class="block text-xs mb-1.5" style="color: #9ca3af;">Sort Order</label>
                            <input v-model="form.sort_order" type="number" min="0"
                                   class="w-24 rounded border px-3 py-2 text-sm focus:outline-none"
                                   style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;" />
                        </div>
                        <div class="flex items-center gap-3">
                            <input v-model="form.published" type="checkbox" id="published" class="rounded" />
                            <label for="published" class="text-sm" style="color: #9ca3af;">Published</label>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center gap-4">
                    <button type="submit" :disabled="form.processing"
                            class="px-6 py-2.5 rounded text-sm tracking-widest uppercase font-medium transition-opacity"
                            style="background: linear-gradient(135deg, #8b5cf6, #f97316); color: white;"
                            :class="form.processing ? 'opacity-50' : ''">
                        {{ isEdit ? 'Update Character' : 'Create Character' }}
                    </button>
                    <Link href="/admin/characters" class="text-sm transition-colors" style="color: #6b7280;"
                          onmouseenter="this.style.color='#9ca3af'" onmouseleave="this.style.color='#6b7280'">
                        Cancel
                    </Link>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
