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
    props.novel.cover_image ? `/storage/${props.novel.cover_image}` : undefined,
);

function onCover(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0];

    if (file) {
        form.cover_image = file;
        coverPreview.value = URL.createObjectURL(file);
    }
}

function submit() {
    form.transform((data) => ({ ...data, _method: 'PUT' })).post(
        '/admin/novel',
        { forceFormData: true },
    );
}
</script>

<template>
    <AdminLayout>
        <Head title="Novel — Admin" />

        <div class="max-w-3xl">
            <h1 class="mb-8 text-2xl font-bold" style="color: #e8e8f0">
                The Novel
            </h1>

            <form
                @submit.prevent="submit"
                class="space-y-6"
                enctype="multipart/form-data"
            >
                <div class="grid grid-cols-3 gap-8">
                    <!-- Cover -->
                    <div>
                        <label
                            class="mb-2 block text-xs tracking-wide"
                            style="color: #9ca3af"
                            >Cover Image</label
                        >
                        <div
                            class="mb-3 aspect-[2/3] overflow-hidden rounded-xl border"
                            style="
                                background: linear-gradient(
                                    160deg,
                                    rgba(139, 92, 246, 0.1) 0%,
                                    rgba(249, 115, 22, 0.1) 100%
                                );
                                border-color: rgba(255, 255, 255, 0.08);
                            "
                        >
                            <img
                                v-if="coverPreview"
                                :src="coverPreview"
                                class="h-full w-full object-cover"
                            />
                            <div
                                v-else
                                class="flex h-full w-full items-center justify-center"
                                style="color: #4b5563"
                            >
                                ◆
                            </div>
                        </div>
                        <input
                            type="file"
                            accept="image/*"
                            @change="onCover"
                            class="block w-full text-xs"
                            style="color: #6b7280"
                        />
                    </div>

                    <!-- Fields -->
                    <div
                        class="col-span-2 space-y-4 rounded-xl border p-6"
                        style="
                            border-color: rgba(255, 255, 255, 0.08);
                            background: rgba(255, 255, 255, 0.02);
                        "
                    >
                        <div>
                            <label
                                class="mb-1.5 block text-xs tracking-wide"
                                style="color: #9ca3af"
                                >Title *</label
                            >
                            <input
                                v-model="form.title"
                                type="text"
                                required
                                class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                                style="
                                    background: rgba(255, 255, 255, 0.04);
                                    border-color: rgba(255, 255, 255, 0.1);
                                    color: #e8e8f0;
                                "
                            />
                            <p
                                v-if="form.errors.title"
                                class="mt-1 text-xs"
                                style="color: #ef4444"
                            >
                                {{ form.errors.title }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="mb-1.5 block text-xs tracking-wide"
                                style="color: #9ca3af"
                                >Tagline</label
                            >
                            <input
                                v-model="form.tagline"
                                type="text"
                                class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                                style="
                                    background: rgba(255, 255, 255, 0.04);
                                    border-color: rgba(255, 255, 255, 0.1);
                                    color: #e8e8f0;
                                "
                            />
                        </div>

                        <div>
                            <label
                                class="mb-1.5 block text-xs tracking-wide"
                                style="color: #9ca3af"
                                >Description</label
                            >
                            <textarea
                                v-model="form.description"
                                rows="8"
                                class="w-full resize-none rounded border px-3 py-2 text-sm focus:outline-none"
                                style="
                                    background: rgba(255, 255, 255, 0.04);
                                    border-color: rgba(255, 255, 255, 0.1);
                                    color: #e8e8f0;
                                "
                            ></textarea>
                        </div>

                        <div>
                            <label
                                class="mb-1.5 block text-xs tracking-wide"
                                style="color: #9ca3af"
                                >Status</label
                            >
                            <select
                                v-model="form.status"
                                class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                                style="
                                    background: rgba(255, 255, 255, 0.04);
                                    border-color: rgba(255, 255, 255, 0.1);
                                    color: #e8e8f0;
                                "
                            >
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded px-6 py-2.5 text-sm font-medium tracking-widest uppercase transition-opacity"
                        style="
                            background: linear-gradient(
                                135deg,
                                #8b5cf6,
                                #f97316
                            );
                            color: white;
                        "
                        :class="form.processing ? 'opacity-50' : ''"
                    >
                        Save Novel
                    </button>
                    <Link
                        href="/novel"
                        target="_blank"
                        class="text-sm transition-colors"
                        style="color: #6b7280"
                        onmouseenter="this.style.color = '#9ca3af'"
                        onmouseleave="this.style.color = '#6b7280'"
                    >
                        ↗ View Novel Page
                    </Link>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
