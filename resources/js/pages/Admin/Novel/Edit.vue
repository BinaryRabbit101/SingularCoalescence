<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

interface Novel {
    id: number;
    title: string;
    tagline?: string;
    description?: string;
    cover_image?: string;
    status: 'draft' | 'published';
}

const props = defineProps<{
    novel: Novel;
}>();

const form = useForm({
    title: props.novel.title,
    tagline: props.novel.tagline ?? '',
    description: props.novel.description ?? '',
    status: props.novel.status,
    cover_image: null as File | null,
});

const coverPreview = ref<string | undefined>(
    props.novel.cover_image ? `/storage/${props.novel.cover_image}` : undefined
);

function onCover(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        form.cover_image = file;
        coverPreview.value = URL.createObjectURL(file);
    }
}

function submit() {
    form.transform((data) => ({ ...data, _method: 'PUT' }))
        .post('/admin/novel', { forceFormData: true });
}
</script>

<template>
    <AdminLayout>
        <Head title="Novel — Admin" />

        <div class="max-w-3xl">
            <h1 class="text-2xl font-bold mb-8" style="color: #e8e8f0;">The Novel</h1>

            <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                <div class="grid grid-cols-3 gap-8">
                    <!-- Cover -->
                    <div>
                        <label class="block text-xs tracking-wide mb-2" style="color: #9ca3af;">Cover Image</label>
                        <div class="aspect-[2/3] rounded-xl overflow-hidden border mb-3"
                             style="background: linear-gradient(160deg, rgba(139,92,246,0.1) 0%, rgba(249,115,22,0.1) 100%); border-color: rgba(255,255,255,0.08);">
                            <img v-if="coverPreview" :src="coverPreview" class="w-full h-full object-cover" />
                            <div v-else class="w-full h-full flex items-center justify-center" style="color: #4b5563;">◆</div>
                        </div>
                        <input type="file" accept="image/*" @change="onCover"
                               class="block w-full text-xs" style="color: #6b7280;" />
                    </div>

                    <!-- Fields -->
                    <div class="col-span-2 rounded-xl border p-6 space-y-4" style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);">
                        <div>
                            <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Title *</label>
                            <input v-model="form.title" type="text" required
                                   class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                                   style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;" />
                            <p v-if="form.errors.title" class="text-xs mt-1" style="color: #ef4444;">{{ form.errors.title }}</p>
                        </div>

                        <div>
                            <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Tagline</label>
                            <input v-model="form.tagline" type="text"
                                   class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                                   style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;" />
                        </div>

                        <div>
                            <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Description</label>
                            <textarea v-model="form.description" rows="8"
                                      class="w-full rounded border px-3 py-2 text-sm focus:outline-none resize-none"
                                      style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Status</label>
                            <select v-model="form.status"
                                    class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                                    style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" :disabled="form.processing"
                            class="px-6 py-2.5 rounded text-sm tracking-widest uppercase font-medium transition-opacity"
                            style="background: linear-gradient(135deg, #8b5cf6, #f97316); color: white;"
                            :class="form.processing ? 'opacity-50' : ''">
                        Save Novel
                    </button>
                    <Link href="/novel" target="_blank" class="text-sm transition-colors" style="color: #6b7280;"
                          onmouseenter="this.style.color='#9ca3af'" onmouseleave="this.style.color='#6b7280'">
                        ↗ View Novel Page
                    </Link>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
