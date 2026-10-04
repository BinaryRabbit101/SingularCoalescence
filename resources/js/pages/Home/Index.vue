<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
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
    banner?: string | null;
}>();
</script>

<template>
    <Head title="Singular Coalescence" />

        <!-- Hero -->
        <section class="relative flex items-center justify-center overflow-hidden py-32 md:py-44">
            <!-- Background gradient -->
            <div class="absolute inset-0" style="background: radial-gradient(ellipse 80% 60% at 20% 50%, rgba(139,92,246,0.15) 0%, transparent 60%), radial-gradient(ellipse 80% 60% at 80% 50%, rgba(249,115,22,0.12) 0%, transparent 60%), #0a0a0f;"></div>

            <!-- Grid overlay -->
            <div class="absolute inset-0 opacity-10" style="background-image: linear-gradient(rgba(139,92,246,0.3) 1px, transparent 1px), linear-gradient(90deg, rgba(139,92,246,0.3) 1px, transparent 1px); background-size: 60px 60px;"></div>

            <!-- Split light beams -->
            <div class="absolute left-0 top-0 w-1/2 h-full opacity-5" style="background: linear-gradient(to right, #8b5cf6, transparent);"></div>
            <div class="absolute right-0 top-0 w-1/2 h-full opacity-5" style="background: linear-gradient(to left, #f97316, transparent);"></div>

            <!-- Content -->
            <div class="relative z-10 text-center max-w-4xl mx-auto px-6">
                <p class="text-xs tracking-widest uppercase mb-6 font-mono" style="color: #8b5cf6;">A sci-fi novel</p>

                <h1 class="text-5xl sm:text-6xl md:text-8xl font-bold tracking-tight mb-6" style="line-height: 1.05;">
                    <span style="background: linear-gradient(90deg, #8b5cf6, #f97316); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Singular</span><br />
                    <span style="background: linear-gradient(90deg, #8b5cf6, #f97316); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Coalescence</span>
                </h1>
                <p class="text-lg md:text-xl mb-12 max-w-2xl mx-auto" style="color: #6b7280;">
                    One consciousness. An eternity of solved equations —
                    <em style="color: #9ca3af;">and a single unsolvable anomaly.</em>
                </p>

                <div class="flex flex-wrap items-center justify-center gap-4">
                    <Link href="/characters"
                          class="px-8 py-3 rounded text-sm tracking-widest uppercase font-medium transition-all duration-200"
                          style="background: linear-gradient(135deg, #8b5cf6, #f97316); color: white;">
                        Meet Charlotte
                    </Link>
                    <Link href="/novel"
                          class="px-8 py-3 rounded border text-sm tracking-widest uppercase transition-all duration-200"
                          style="border-color: rgba(255,255,255,0.15); color: #9ca3af;"
                          onmouseenter="this.style.borderColor='rgba(255,255,255,0.3)'; this.style.color='#e8e8f0'"
                          onmouseleave="this.style.borderColor='rgba(255,255,255,0.15)'; this.style.color='#9ca3af'"
                    >
                        The Novel
                    </Link>
                </div>
            </div>

        </section>

        <!-- Storytime banner -->
        <section v-if="banner" class="px-6 pb-4">
            <div class="max-w-7xl mx-auto">
                <img :src="`/storage/${banner}`"
                     class="w-full aspect-[21/9] object-cover rounded-xl border"
                     style="border-color: rgba(255,255,255,0.08);"
                     alt="The cast gathered around Liam as he reads them a story" />
            </div>
        </section>

        <!-- Characters preview -->
        <section v-if="characters.length" class="py-24 px-6">
            <div class="max-w-7xl mx-auto">
                <div class="mb-12">
                    <p class="text-xs tracking-widest uppercase mb-2" style="color: #8b5cf6;">Characters</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4 md:gap-6">
                    <Link
                        v-for="character in characters"
                        :key="character.id"
                        :href="`/characters/${character.slug}`"
                        class="group rounded-xl overflow-hidden border transition-all duration-300"
                        style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);"
                        onmouseenter="this.style.borderColor='rgba(139,92,246,0.3)'; this.style.transform='translateY(-4px)'"
                        onmouseleave="this.style.borderColor='rgba(255,255,255,0.08)'; this.style.transform=''"
                    >
                        <!-- Portrait -->
                        <div class="aspect-[3/4] relative overflow-hidden"
                             style="background: linear-gradient(160deg, rgba(139,92,246,0.2) 0%, rgba(10,10,15,1) 100%);">
                            <img v-if="character.profile_image"
                                 :src="`/storage/${character.profile_image}`"
                                 class="absolute inset-0 w-full h-full object-cover object-top transition-opacity duration-500"
                                 :class="character.action_image ? 'opacity-100 group-hover:opacity-0' : ''"
                                 :alt="character.name" />
                            <img v-if="character.action_image"
                                 :src="`/storage/${character.action_image}`"
                                 class="absolute inset-0 w-full h-full object-cover object-top opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                 :alt="`${character.name} action`" />
                            <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(10,10,15,0.6), transparent);"></div>
                        </div>

                        <div class="p-5">
                            <h3 class="font-bold text-lg mb-1" style="color: #e8e8f0;">{{ character.name }}</h3>
                            <p v-if="character.tagline" class="text-sm" style="color: #6b7280;">{{ character.tagline }}</p>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Nav cards -->
        <section class="py-24 px-6 border-t" style="border-color: rgba(255,255,255,0.06);">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <Link href="/universe"
                          class="group rounded-xl p-8 border transition-all duration-300 cursor-pointer"
                          style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);"
                          onmouseenter="this.style.borderColor='rgba(139,92,246,0.3)'; this.style.background='rgba(139,92,246,0.04)'"
                          onmouseleave="this.style.borderColor='rgba(255,255,255,0.08)'; this.style.background='rgba(255,255,255,0.02)'"
                    >
                        <div class="text-3xl mb-4">◇</div>
                        <h3 class="font-bold text-lg mb-2" style="color: #e8e8f0;">The Universe</h3>
                        <p class="text-sm" style="color: #6b7280;">Factions, locations, and the technology that shapes the world.</p>
                    </Link>
                    <Link href="/novel"
                          class="group rounded-xl p-8 border transition-all duration-300 cursor-pointer"
                          style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);"
                          onmouseenter="this.style.borderColor='rgba(249,115,22,0.3)'; this.style.background='rgba(249,115,22,0.04)'"
                          onmouseleave="this.style.borderColor='rgba(255,255,255,0.08)'; this.style.background='rgba(255,255,255,0.02)'"
                    >
                        <div class="text-3xl mb-4">◆</div>
                        <h3 class="font-bold text-lg mb-2" style="color: #e8e8f0;">The Novel</h3>
                        <p class="text-sm" style="color: #6b7280;">Singular Coalescence — a sci-fi saga of chaos, logic, and connection.</p>
                    </Link>
                    <Link href="/products"
                          class="group rounded-xl p-8 border transition-all duration-300 cursor-pointer"
                          style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);"
                          onmouseenter="this.style.borderColor='rgba(139,92,246,0.3)'; this.style.background='rgba(139,92,246,0.04)'"
                          onmouseleave="this.style.borderColor='rgba(255,255,255,0.08)'; this.style.background='rgba(255,255,255,0.02)'"
                    >
                        <div class="text-3xl mb-4">◈</div>
                        <h3 class="font-bold text-lg mb-2" style="color: #e8e8f0;">Products &amp; Apps</h3>
                        <p class="text-sm" style="color: #6b7280;">Real-world extensions of the universe — apps, tools, and more.</p>
                    </Link>
                </div>
            </div>
        </section>
</template>
