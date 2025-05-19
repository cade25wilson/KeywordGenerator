<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    group: {
        type: Object,
        required: true,
    },
});

const hasImage = computed(() => {
    return props.group && 
           props.group.randomProductFirstPhoto && 
           props.group.randomProductFirstPhoto.length > 0;
});
</script>

<template>
    <Link :href="`/product-groups/${props.group.id}`" class="relative block h-full w-full overflow-hidden rounded-xl cursor-pointer">
        <!-- Background image if available -->
        <div v-if="hasImage" 
             class="absolute inset-0 bg-cover bg-center" 
             :style="{ backgroundImage: `url(${props.group.randomProductFirstPhoto})` }">
        </div>
        
        <!-- Gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-black/20"></div>
        
        <!-- Fallback pattern if no image -->
        <div v-if="!hasImage" class="absolute inset-0 bg-gradient-to-br from-gray-800 to-gray-900">
            <div class="absolute inset-0 opacity-10">
                <svg class="h-full w-full" xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
                    <path d="M10 10h10v10H10zM30 10h10v10H30zM50 10h10v10H50zM70 10h10v10H70zM90 10h10v10H90zM10 30h10v10H10zM30 30h10v10H30zM50 30h10v10H50zM70 30h10v10H70zM90 30h10v10H90zM10 50h10v10H10zM30 50h10v10H30zM50 50h10v10H50zM70 50h10v10H70zM90 50h10v10H90zM10 70h10v10H10zM30 70h10v10H30zM50 70h10v10H50zM70 70h10v10H70zM90 70h10v10H90zM10 90h10v10H10zM30 90h10v10H30zM50 90h10v10H50zM70 90h10v10H70zM90 90h10v10H90z" fill="currentColor" />
                </svg>
            </div>
        </div>
        
        <!-- Content -->
        <div class="absolute inset-0 flex flex-col justify-end p-4">
            <h3 class="text-xl font-bold text-white drop-shadow-md">
                {{ props.group.name }}
            </h3>
            <div class="mt-2 flex items-center">
                <span class="rounded-md bg-black/50 px-2 py-1 text-xs text-white">
                    {{ props.group.products?.length || 0 }} Products
                </span>
            </div>
        </div>
    </Link>
</template>
