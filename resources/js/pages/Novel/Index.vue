<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineOptions({ layout: PublicLayout });

interface Novel {
    id: number;
    title: string;
    tagline?: string;
    description?: string;
    cover_image?: string;
    status: 'draft' | 'published';
}

defineProps<{
    novel: Novel | null;
}>();
</script>

<template>
    <Head title="Singular Coalescence — The Novel" />

    <div class="mx-auto max-w-7xl px-6 py-20">
        <!-- Header -->
        <div class="mb-16">
            <p
                class="mb-3 text-xs tracking-widest uppercase"
                style="color: #8b5cf6"
            >
                The Story
            </p>
            <h1 class="text-4xl font-bold" style="color: #e8e8f0">
                Singular Coalescence
            </h1>
        </div>

        <div
            v-if="novel"
            class="grid grid-cols-1 items-start gap-16 lg:grid-cols-2"
        >
            <!-- Cover -->
            <div>
                <div
                    class="aspect-[2/3] max-w-sm overflow-hidden rounded-2xl"
                    style="
                        background: linear-gradient(
                            160deg,
                            rgba(139, 92, 246, 0.2) 0%,
                            rgba(249, 115, 22, 0.15) 50%,
                            rgba(10, 10, 15, 1) 100%
                        );
                        box-shadow:
                            0 25px 60px rgba(0, 0, 0, 0.5),
                            0 0 0 1px rgba(255, 255, 255, 0.06);
                    "
                >
                    <img
                        v-if="novel.cover_image"
                        :src="`/storage/${novel.cover_image}`"
                        class="h-full w-full object-cover"
                        :alt="novel.title"
                    />
                    <div
                        v-else
                        class="flex h-full w-full flex-col items-center justify-center gap-6 p-8"
                    >
                        <div
                            class="h-16 w-16 rounded-full"
                            style="
                                background: linear-gradient(
                                    135deg,
                                    #8b5cf6,
                                    #f97316
                                );
                            "
                        ></div>
                        <div class="text-center">
                            <p class="text-xl font-bold" style="color: #e8e8f0">
                                {{ novel.title }}
                            </p>
                            <p
                                v-if="novel.tagline"
                                class="mt-2 text-sm"
                                style="color: #6b7280"
                            >
                                {{ novel.tagline }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Status badge -->
                <div class="mt-6 flex items-center gap-3">
                    <span
                        class="rounded-full border px-3 py-1 font-mono text-xs tracking-widest uppercase"
                        :style="
                            novel.status === 'published'
                                ? 'border-color: rgba(34,197,94,0.4); color: #22c55e; background: rgba(34,197,94,0.08);'
                                : 'border-color: rgba(249,115,22,0.4); color: #f97316; background: rgba(249,115,22,0.08);'
                        "
                    >
                        {{
                            novel.status === 'published'
                                ? '✓ Published'
                                : '◎ Work In Progress'
                        }}
                    </span>
                </div>
            </div>

            <!-- Info -->
            <div class="space-y-8">
                <div>
                    <h2 class="mb-3 text-4xl font-bold" style="color: #e8e8f0">
                        {{ novel.title }}
                    </h2>
                    <p
                        v-if="novel.tagline"
                        class="text-xl italic"
                        style="color: #6b7280"
                    >
                        {{ novel.tagline }}
                    </p>
                </div>

                <div
                    class="h-px w-16"
                    style="background: linear-gradient(90deg, #8b5cf6, #f97316)"
                ></div>

                <div v-if="novel.description" class="space-y-4">
                    <p
                        class="text-base leading-relaxed whitespace-pre-wrap"
                        style="color: #9ca3af"
                    >
                        {{ novel.description }}
                    </p>
                </div>

                <!-- Characters link -->
                <div class="pt-4">
                    <Link
                        href="/characters"
                        class="inline-flex items-center gap-3 text-sm tracking-widest uppercase transition-colors"
                        style="color: #8b5cf6"
                        onmouseenter="this.style.color = '#a78bfa'"
                        onmouseleave="this.style.color = '#8b5cf6'"
                    >
                        Meet the Characters
                        <span>→</span>
                    </Link>
                </div>
            </div>
        </div>

        <!-- No novel yet -->
        <div v-else class="py-24 text-center">
            <div
                class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full"
                style="
                    background: rgba(139, 92, 246, 0.1);
                    border: 1px solid rgba(139, 92, 246, 0.2);
                "
            >
                <span class="text-3xl">◆</span>
            </div>
            <h2 class="mb-3 text-2xl font-bold" style="color: #e8e8f0">
                Coming Soon
            </h2>
            <p style="color: #6b7280">
                The novel is currently being crafted. Check back soon.
            </p>
        </div>
    </div>
</template>
