<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
// Use these imports instead of DropdownMenuSub and its children.
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from '@/components/ui/dropdown-menu';

const showMenu = ref(false);

const page = usePage();

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

// Dummy handlers for menu actions. Replace with actual logic.
function onEditClick() {
  console.log('Edit clicked');
}
async function onDeleteClick(delete_products: boolean) {
    const response = await fetch(`/product-groups/${props.group.id}/${delete_products}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': page.props.csrf_token,
        },
    });

    if (response.ok) {
        // Handle successful deletion
        console.log('Group deleted successfully');
        router.reload();
    } else {
        // Handle error
        console.error('Error deleting group');
    }
}
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

        <div class="absolute bottom-2 right-2">
            <DropdownMenu v-model="showMenu">
                <DropdownMenuTrigger asChild>
                    <button class="p-2 rounded-full text-white hover:bg-black/20 transition" @click.stop.prevent>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor">
                            <circle cx="5" cy="12" r="1.5" />
                            <circle cx="12" cy="12" r="1.5" />
                            <circle cx="19" cy="12" r="1.5" />
                        </svg>
                    </button>
                </DropdownMenuTrigger>
                <DropdownMenuContent>
                    <DropdownMenuItem @click="onEditClick" class="cursor-pointer">Edit</DropdownMenuItem>
                    <DropdownMenuItem class="text-gray-500 cursor-pointer">Add to Favorites</DropdownMenuItem>
                    <DropdownMenuItem @click="onDeleteClick(false)" class="text-red-600 cursor-pointer">Delete Group</DropdownMenuItem>
                    <DropdownMenuItem @click="onDeleteClick(true)" class="text-red-600 cursor-pointer">Delete Group And All Items</DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>
        </div>
    </Link>
</template>
