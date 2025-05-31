<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import {
    DropdownMenu,
    DropdownMenuTrigger,
    DropdownMenuContent,
    DropdownMenuItem,
} from "@/components/ui/dropdown-menu";

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});
const page = usePage();

const firstImage = computed(() => {
    return props.product.pictures[0];
});

const showMenu = ref(false);

const onEditClick = () => {
    // handle edit action
};

const onDeleteClick = () => {
    const response = fetch(`/products/${props.product.id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    });

    response.then(res => {
        if (res.ok) {
            router.reload();
        }
    });
};

const onRemoveFromGroupClick = () => {
    const response = fetch(`/product-group-products/${page.props.productGroup.id}/${props.product.id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    });

    response.then(res => {
        if (res.ok) {
            //remove product from the list
            const index = page.props.productGroup.products.findIndex(product => product.id === props.product.id);
            if (index !== -1) {
                page.props.productGroup.products.splice(index, 1);
            }

        }
    });    
};
</script>

<template>
    <Link :href="`/products/${props.product.id}`" class="relative block h-full w-full overflow-hidden rounded-xl cursor-pointer">
        <!-- Background image if available -->
        <div v-if="firstImage" 
             class="absolute inset-0 bg-cover bg-center" 
             :style="{ backgroundImage: 'url(' + 'https://keywordgenerator2.s3.us-east-2.amazonaws.com/' + firstImage.image_path + ')' }">
        </div>
        
        <!-- Gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-black/20"></div>
        
        <!-- Fallback pattern if no image -->
        <div v-if="!firstImage" class="absolute inset-0 bg-gradient-to-br from-gray-800 to-gray-900">
            <div class="absolute inset-0 opacity-10">
                <svg class="h-full w-full" xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
                    <path d="M10 10h10v10H10zM30 10h10v10H30zM50 10h10v10H50zM70 10h10v10H70zM90 10h10v10H90zM10 30h10v10H10zM30 30h10v10H30zM50 30h10v10H50zM70 30h10v10H70zM90 30h10v10H90zM10 50h10v10H10zM30 50h10v10H30zM50 50h10v10H50zM70 50h10v10H70zM90 50h10v10H90zM10 70h10v10H10zM30 70h10v10H30zM50 70h10v10H50zM70 70h10v10H70zM90 70h10v10H90zM10 90h10v10H10zM30 90h10v10H30zM50 90h10v10H50zM70 90h10v10H70zM90 90h10v10H90z" fill="currentColor" />
                </svg>
            </div>
        </div>
        
        <!-- Content -->
        <div class="absolute inset-0 flex flex-col justify-end p-4">
            <h2 class="text-3xl font-bold text-white drop-shadow-md">
                {{ props.product.title }} ({{ props.product.status }})
            </h2>
        </div>

        <!-- Dropdown menu -->
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
                    <DropdownMenuItem class="cursor-pointer" @click="onEditClick">Edit</DropdownMenuItem>
                    <DropdownMenuItem class="text-gray-500 cursor-pointer">Add to Favorites</DropdownMenuItem>
                    <DropdownMenuItem v-if="page.url !== '/products'" @click="onRemoveFromGroupClick" class="text-gray-500 cursor-pointer">
                        Remove Product From Group
                    </DropdownMenuItem>
                    <DropdownMenuItem @click="onDeleteClick" class="text-red-600 cursor-pointer">Delete Product</DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>
        </div>
    </Link>
</template>
