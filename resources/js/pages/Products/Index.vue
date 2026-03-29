<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineOptions({ layout: PublicLayout });

interface Product {
    id: number;
    name: string;
    tagline?: string;
    description?: string;
    type: 'product' | 'app';
    images?: string[];
    url?: string;
    sort_order: number;
}

defineProps<{
    products: Product[];
}>();

const galleryIndex = ref<Record<number, number>>({});

function currentImage(product: Product): string | undefined {
    const idx = galleryIndex.value[product.id] ?? 0;
    return product.images?.[idx] ? `/storage/${product.images[idx]}` : undefined;
}

function nextImage(product: Product) {
    const len = product.images?.length ?? 0;
    if (!len) return;
    const cur = galleryIndex.value[product.id] ?? 0;
    galleryIndex.value[product.id] = (cur + 1) % len;
}

function prevImage(product: Product) {
    const len = product.images?.length ?? 0;
    if (!len) return;
    const cur = galleryIndex.value[product.id] ?? 0;
    galleryIndex.value[product.id] = (cur - 1 + len) % len;
}
</script>

<template>
    <Head title="Products" />

        <div class="max-w-7xl mx-auto px-6 py-20">
            <!-- Header -->
            <div class="mb-16">
                <p class="text-xs tracking-widest uppercase mb-3" style="color: #8b5cf6;">Real World</p>
                <h1 class="text-4xl font-bold mb-4" style="color: #e8e8f0;">Products &amp; Apps</h1>
                <p class="text-base max-w-2xl" style="color: #6b7280;">
                    Extensions of the universe into the real world — apps, tools, and products inspired by the story.
                </p>
            </div>

            <!-- Empty state -->
            <div v-if="!products.length" class="text-center py-24">
                <div class="w-16 h-16 rounded-xl mx-auto mb-6 flex items-center justify-center"
                     style="background: rgba(139,92,246,0.1); border: 1px solid rgba(139,92,246,0.2);">
                    <span class="text-2xl">◈</span>
                </div>
                <h2 class="text-xl font-bold mb-2" style="color: #e8e8f0;">Coming Soon</h2>
                <p style="color: #6b7280;">Products and apps are being developed.</p>
            </div>

            <!-- Products grid -->
            <div v-else class="space-y-16">
                <div
                    v-for="product in products"
                    :key="product.id"
                    class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center"
                >
                    <!-- Screenshot gallery -->
                    <div class="relative rounded-2xl overflow-hidden border"
                         style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08); aspect-ratio: 16/10;">
                        <img v-if="currentImage(product)" :src="currentImage(product)" :alt="product.name"
                             class="w-full h-full object-cover" />
                        <div v-else class="w-full h-full flex items-center justify-center">
                            <span class="text-4xl opacity-20">{{ product.type === 'app' ? '📱' : '◈' }}</span>
                        </div>

                        <!-- Gallery controls -->
                        <div v-if="(product.images?.length ?? 0) > 1" class="absolute inset-0 flex items-center justify-between px-3">
                            <button @click="prevImage(product)"
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-sm"
                                    style="background: rgba(0,0,0,0.6); color: white; backdrop-filter: blur(4px);">
                                ‹
                            </button>
                            <button @click="nextImage(product)"
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-sm"
                                    style="background: rgba(0,0,0,0.6); color: white; backdrop-filter: blur(4px);">
                                ›
                            </button>
                        </div>

                        <!-- Dot indicators -->
                        <div v-if="(product.images?.length ?? 0) > 1"
                             class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
                            <div
                                v-for="(_, i) in product.images"
                                :key="i"
                                class="w-1.5 h-1.5 rounded-full transition-all"
                                :style="i === (galleryIndex[product.id] ?? 0)
                                    ? 'background: #8b5cf6; width: 1rem;'
                                    : 'background: rgba(255,255,255,0.3);'"
                            ></div>
                        </div>

                        <!-- Type badge -->
                        <div class="absolute top-3 left-3">
                            <span class="text-xs font-mono px-2 py-0.5 rounded border capitalize"
                                  :style="product.type === 'app'
                                    ? 'color: #f97316; border-color: rgba(249,115,22,0.4); background: rgba(10,10,15,0.7); backdrop-filter: blur(4px);'
                                    : 'color: #8b5cf6; border-color: rgba(139,92,246,0.4); background: rgba(10,10,15,0.7); backdrop-filter: blur(4px);'">
                                {{ product.type }}
                            </span>
                        </div>
                    </div>

                    <!-- Info -->
                    <div>
                        <h2 class="text-3xl font-bold mb-2" style="color: #e8e8f0;">{{ product.name }}</h2>
                        <p v-if="product.tagline" class="text-lg italic mb-6" style="color: #6b7280;">{{ product.tagline }}</p>

                        <div class="w-12 h-px mb-6" style="background: linear-gradient(90deg, #8b5cf6, #f97316);"></div>

                        <p v-if="product.description" class="text-base leading-relaxed whitespace-pre-wrap mb-8"
                           style="color: #9ca3af;">{{ product.description }}</p>

                        <a v-if="product.url" :href="product.url" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-3 px-6 py-3 rounded text-sm tracking-widest uppercase font-medium transition-all"
                           style="background: linear-gradient(135deg, #8b5cf6, #f97316); color: white;">
                            {{ product.type === 'app' ? 'View App' : 'Learn More' }}
                            <span>↗</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
</template>
