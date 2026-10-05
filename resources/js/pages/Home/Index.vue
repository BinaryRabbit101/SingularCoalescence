<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import BannerCarousel from '@/components/BannerCarousel.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineOptions({ layout: PublicLayout });

interface Character {
    id: number;
    slug: string;
    name: string;
    tagline?: string;
    profile_image?: string;
    action_image?: string;
}

defineProps<{
    characters: Character[];
    banners: { src: string; alt: string }[];
}>();
</script>

<template>
    <Head title="Singular Coalescence" />

    <!-- Hero -->
    <section class="relative overflow-hidden px-6 pt-24 pb-6 md:pt-40 md:pb-10">
        <!-- Background gradient -->
        <div
            class="absolute inset-0"
            style="
                background:
                    radial-gradient(
                        ellipse 80% 60% at 20% 50%,
                        rgba(139, 92, 246, 0.15) 0%,
                        transparent 60%
                    ),
                    radial-gradient(
                        ellipse 80% 60% at 80% 50%,
                        rgba(249, 115, 22, 0.12) 0%,
                        transparent 60%
                    ),
                    #0a0a0f;
            "
        ></div>

        <!-- Grid overlay -->
        <div
            class="absolute inset-0 opacity-10"
            style="
                background-image:
                    linear-gradient(
                        rgba(139, 92, 246, 0.3) 1px,
                        transparent 1px
                    ),
                    linear-gradient(
                        90deg,
                        rgba(139, 92, 246, 0.3) 1px,
                        transparent 1px
                    );
                background-size: 60px 60px;
            "
        ></div>

        <!-- Split light beams -->
        <div
            class="absolute top-0 left-0 h-full w-1/2 opacity-5"
            style="background: linear-gradient(to right, #8b5cf6, transparent)"
        ></div>
        <div
            class="absolute top-0 right-0 h-full w-1/2 opacity-5"
            style="background: linear-gradient(to left, #f97316, transparent)"
        ></div>

        <!-- Content -->
        <div class="relative z-10 mx-auto max-w-4xl text-center">
            <p
                class="mb-6 font-mono text-xs tracking-widest uppercase"
                style="color: #8b5cf6"
            >
                A science-fantasy novel
            </p>

            <h1
                class="mb-6 text-5xl font-bold tracking-tight sm:text-6xl md:text-8xl"
                style="line-height: 1.05"
            >
                <span
                    style="
                        background: linear-gradient(90deg, #8b5cf6, #f97316);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        background-clip: text;
                    "
                    >Singular</span
                ><br />
                <span
                    style="
                        background: linear-gradient(90deg, #8b5cf6, #f97316);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        background-clip: text;
                    "
                    >Coalescence</span
                >
            </h1>
            <p
                class="mx-auto mb-12 max-w-2xl text-lg md:text-xl"
                style="color: #6b7280"
            >
                One consciousness. An eternity of solved equations,
                <em style="color: #9ca3af">and a single unsolvable anomaly.</em>
            </p>

            <div class="flex flex-wrap items-center justify-center gap-4">
                <Link
                    href="/characters"
                    class="rounded px-8 py-3 text-sm font-medium tracking-widest uppercase transition-all duration-200"
                    style="
                        background: linear-gradient(135deg, #8b5cf6, #f97316);
                        color: white;
                    "
                >
                    Meet Charlotte
                </Link>
                <Link
                    href="/novel"
                    class="rounded border px-8 py-3 text-sm tracking-widest uppercase transition-all duration-200"
                    style="
                        border-color: rgba(255, 255, 255, 0.15);
                        color: #9ca3af;
                    "
                    onmouseenter="
                        this.style.borderColor = 'rgba(255,255,255,0.3)';
                        this.style.color = '#e8e8f0';
                    "
                    onmouseleave="
                        this.style.borderColor = 'rgba(255,255,255,0.15)';
                        this.style.color = '#9ca3af';
                    "
                >
                    The Novel
                </Link>
            </div>
        </div>

        <!-- Story pictures -->
        <BannerCarousel
            v-if="banners.length"
            :banners="banners"
            class="relative z-10 mx-auto mt-10 max-w-7xl md:mt-16"
        />
    </section>

    <!-- Characters preview -->
    <section v-if="characters.length" class="px-6 pt-10 pb-24 md:pt-16">
        <div class="mx-auto max-w-7xl">
            <div class="mb-8 md:mb-12">
                <p
                    class="mb-2 text-xs tracking-widest uppercase"
                    style="color: #8b5cf6"
                >
                    Characters
                </p>
            </div>

            <div
                class="grid grid-cols-2 gap-4 md:grid-cols-3 md:gap-6 xl:grid-cols-5"
            >
                <Link
                    v-for="character in characters"
                    :key="character.id"
                    :href="`/characters/${character.slug}`"
                    class="group overflow-hidden rounded-xl border transition-all duration-300"
                    style="
                        border-color: rgba(255, 255, 255, 0.08);
                        background: rgba(255, 255, 255, 0.02);
                    "
                    onmouseenter="
                        this.style.borderColor = 'rgba(139,92,246,0.3)';
                        this.style.transform = 'translateY(-4px)';
                    "
                    onmouseleave="
                        this.style.borderColor = 'rgba(255,255,255,0.08)';
                        this.style.transform = '';
                    "
                >
                    <!-- Portrait -->
                    <div
                        class="relative aspect-[3/4] overflow-hidden"
                        style="
                            background: linear-gradient(
                                160deg,
                                rgba(139, 92, 246, 0.2) 0%,
                                rgba(10, 10, 15, 1) 100%
                            );
                        "
                    >
                        <img
                            v-if="character.profile_image"
                            :src="`/storage/${character.profile_image}`"
                            class="absolute inset-0 h-full w-full object-cover object-top transition-opacity duration-500"
                            :class="
                                character.action_image
                                    ? 'opacity-100 group-hover:opacity-0'
                                    : ''
                            "
                            :alt="character.name"
                        />
                        <img
                            v-if="character.action_image"
                            :src="`/storage/${character.action_image}`"
                            class="absolute inset-0 h-full w-full object-cover object-top opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                            :alt="`${character.name} action`"
                        />
                        <div
                            class="absolute inset-0"
                            style="
                                background: linear-gradient(
                                    to top,
                                    rgba(10, 10, 15, 0.6),
                                    transparent
                                );
                            "
                        ></div>
                    </div>

                    <div class="p-5">
                        <h3
                            class="mb-1 text-lg font-bold"
                            style="color: #e8e8f0"
                        >
                            {{ character.name }}
                        </h3>
                        <p
                            v-if="character.tagline"
                            class="text-sm"
                            style="color: #6b7280"
                        >
                            {{ character.tagline }}
                        </p>
                    </div>
                </Link>
            </div>
        </div>
    </section>

    <!-- Nav cards -->
    <section
        class="border-t px-6 py-24"
        style="border-color: rgba(255, 255, 255, 0.06)"
    >
        <div class="mx-auto max-w-7xl">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <Link
                    href="/universe"
                    class="group cursor-pointer rounded-xl border p-8 transition-all duration-300"
                    style="
                        border-color: rgba(255, 255, 255, 0.08);
                        background: rgba(255, 255, 255, 0.02);
                    "
                    onmouseenter="
                        this.style.borderColor = 'rgba(139,92,246,0.3)';
                        this.style.background = 'rgba(139,92,246,0.04)';
                    "
                    onmouseleave="
                        this.style.borderColor = 'rgba(255,255,255,0.08)';
                        this.style.background = 'rgba(255,255,255,0.02)';
                    "
                >
                    <div class="mb-4 text-3xl">◇</div>
                    <h3 class="mb-2 text-lg font-bold" style="color: #e8e8f0">
                        The Universe
                    </h3>
                    <p class="text-sm" style="color: #6b7280">
                        Factions, locations, and the technology that shapes the
                        world.
                    </p>
                </Link>
                <Link
                    href="/novel"
                    class="group cursor-pointer rounded-xl border p-8 transition-all duration-300"
                    style="
                        border-color: rgba(255, 255, 255, 0.08);
                        background: rgba(255, 255, 255, 0.02);
                    "
                    onmouseenter="
                        this.style.borderColor = 'rgba(249,115,22,0.3)';
                        this.style.background = 'rgba(249,115,22,0.04)';
                    "
                    onmouseleave="
                        this.style.borderColor = 'rgba(255,255,255,0.08)';
                        this.style.background = 'rgba(255,255,255,0.02)';
                    "
                >
                    <div class="mb-4 text-3xl">◆</div>
                    <h3 class="mb-2 text-lg font-bold" style="color: #e8e8f0">
                        The Novel
                    </h3>
                    <p class="text-sm" style="color: #6b7280">
                        A science-fantasy adventure about a girl in two bodies
                        and the farm boy she calls useful.
                    </p>
                </Link>
                <Link
                    href="/products"
                    class="group cursor-pointer rounded-xl border p-8 transition-all duration-300"
                    style="
                        border-color: rgba(255, 255, 255, 0.08);
                        background: rgba(255, 255, 255, 0.02);
                    "
                    onmouseenter="
                        this.style.borderColor = 'rgba(139,92,246,0.3)';
                        this.style.background = 'rgba(139,92,246,0.04)';
                    "
                    onmouseleave="
                        this.style.borderColor = 'rgba(255,255,255,0.08)';
                        this.style.background = 'rgba(255,255,255,0.02)';
                    "
                >
                    <div class="mb-4 text-3xl">◈</div>
                    <h3 class="mb-2 text-lg font-bold" style="color: #e8e8f0">
                        Products &amp; Apps
                    </h3>
                    <p class="text-sm" style="color: #6b7280">
                        Real-world extensions of the universe: apps, tools, and
                        more.
                    </p>
                </Link>
            </div>
        </div>
    </section>
</template>
